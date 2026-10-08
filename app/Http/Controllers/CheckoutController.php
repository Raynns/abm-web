<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function show(Request $request): View|RedirectResponse
    {
        $snapshot = $this->cart->snapshot($request);
        if (!$snapshot['lines']) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang masih kosong.']);
        }
        if ($snapshot['hasIssues']) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Periksa stok dan produk dalam keranjang sebelum checkout.']);
        }

        return view('shop.checkout', $snapshot);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'min:2', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9][0-9\s()\-]{7,23}$/'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:2000'],
            'expected_total' => ['required', 'integer', 'min:0'],
        ], [
            'customer_phone.regex' => 'Masukkan nomor HP yang valid (angka, boleh diawali +62).',
        ]);

        $quantities = $this->cart->quantities($request);
        if (!$quantities) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        // Mengunci produk pada database agar dua checkout bersamaan tidak menjual stok yang sama.
        $order = DB::transaction(function () use ($data, $quantities): Order {
            $products = Product::whereIn('id', array_keys($quantities))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $lines = [];
            $subtotal = 0;

            foreach ($quantities as $id => $quantity) {
                $product = $products->get($id);
                if (!$product || !$product->is_active || $quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Produk atau stok berubah. Kembali ke keranjang untuk memeriksa pesanan.',
                    ]);
                }

                $lineTotal = (int) $product->price * $quantity;
                $subtotal += $lineTotal;
                $lines[] = compact('product', 'quantity', 'lineTotal');
            }

            if ($subtotal !== (int) $data['expected_total']) {
                throw ValidationException::withMessages([
                    'cart' => 'Harga produk berubah. Muat ulang halaman checkout dan periksa total terbaru.',
                ]);
            }

            $order = Order::create([
                'order_number' => 'ABM-'.now()->format('ymd').'-'.Str::upper(Str::random(12)),
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            foreach ($lines as $line) {
                /** @var Product $product */
                $product = $line['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $line['quantity'],
                    'subtotal' => $line['lineTotal'],
                ]);
                $product->decrement('stock', $line['quantity']);
            }

            return $order;
        }, attempts: 3);

        $request->session()->forget(CartService::SESSION_KEY);
        $request->session()->put('abm_last_order', $order->order_number);

        return redirect()->route('checkout.success');
    }

    public function success(Request $request): View|RedirectResponse
    {
        $orderNumber = $request->session()->get('abm_last_order');
        if (!is_string($orderNumber)) {
            return redirect()->route('shop.home');
        }

        $order = Order::with('items')->where('order_number', $orderNumber)->first();
        if (!$order) {
            return redirect()->route('shop.home');
        }

        return view('shop.success', compact('order'));
    }
}
