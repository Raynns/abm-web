<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(Request $request): View
    {
        return view('shop.cart', $this->cart->snapshot($request));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.CartService::MAX_QUANTITY],
        ]);
        if (!$product->is_active || $product->stock < 1) {
            return back()->withErrors(['cart' => 'Produk tidak tersedia saat ini.']);
        }

        $quantities = $this->cart->quantities($request);
        if (!isset($quantities[$product->id]) && count($quantities) >= CartService::MAX_PRODUCTS) {
            return back()->withErrors(['cart' => 'Keranjang maksimal berisi 50 jenis produk.']);
        }

        $requested = ($quantities[$product->id] ?? 0) + (int) $data['quantity'];
        if ($requested > CartService::MAX_QUANTITY || $requested > $product->stock) {
            return back()->withErrors(['cart' => 'Jumlah melebihi stok tersedia atau batas 999 item per produk.']);
        }

        $quantities[$product->id] = $requested;
        $request->session()->put(CartService::SESSION_KEY, $quantities);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.CartService::MAX_QUANTITY],
        ]);
        $quantities = $this->cart->quantities($request);
        if (!isset($quantities[$productId])) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Produk tidak ada di keranjang.']);
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active || (int) $data['quantity'] > $product->stock) {
            return back()->withErrors(['cart' => 'Jumlah tidak sesuai stok atau produk sudah tidak tersedia.']);
        }

        $quantities[$productId] = (int) $data['quantity'];
        $request->session()->put(CartService::SESSION_KEY, $quantities);
        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy(Request $request, int $productId): RedirectResponse
    {
        $quantities = $this->cart->quantities($request);
        unset($quantities[$productId]);
        $request->session()->put(CartService::SESSION_KEY, $quantities);
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
