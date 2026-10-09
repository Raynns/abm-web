<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $snapshot = $this->cart->snapshot($request);

        if (!$snapshot['lines']) {
            return redirect()
                ->route('cart.index')
                ->withErrors([
                    'cart' => 'Keranjang masih kosong.'
                ]);
        }

        if ($snapshot['hasIssues']) {
            return redirect()
                ->route('cart.index')
                ->withErrors([
                    'cart' => 'Periksa stok dan produk dalam keranjang sebelum checkout.'
                ]);
        }

        return view('shop.checkout', $snapshot);
    }


    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([

            'customer_name' => [
                'required',
                'string',
                'max:255'
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:25'
            ],

            'province_id' => [
                'required',
                'exists:provinces,id'
            ],

            'regency_id' => [
                'required',
                'exists:regencies,id'
            ],

            'district_id' => [
                'required',
                'exists:districts,id'
            ],

            'village_id' => [
                'required',
                'exists:villages,id'
            ],

            'address_detail' => [
                'required',
                'string',
                'min:10',
                'max:2000'
            ],

            'expected_total' => [
                'required'
            ],

        ], [

            'customer_name.required'
                => 'Nama pembeli wajib diisi.',

            'customer_phone.required'
                => 'Nomor HP wajib diisi.',

            'province_id.required'
                => 'Provinsi harus dipilih.',

            'regency_id.required'
                => 'Kabupaten/Kota harus dipilih.',

            'district_id.required'
                => 'Kecamatan harus dipilih.',

            'village_id.required'
                => 'Desa/Kelurahan harus dipilih.',

            'address_detail.required'
                => 'Alamat detail wajib diisi.',

        ]);


        $quantities = $this->cart->quantities($request);


        if (!$quantities) {
            return redirect()
                ->route('cart.index')
                ->withErrors([
                    'cart' => 'Keranjang masih kosong.'
                ]);
        }


        $province = Province::findOrFail($data['province_id']);
        $regency = Regency::findOrFail($data['regency_id']);
        $district = District::findOrFail($data['district_id']);
        $village = Village::findOrFail($data['village_id']);


        $order = DB::transaction(function () use (
            $data,
            $quantities,
            $province,
            $regency,
            $district,
            $village
        ): Order {


            $products = Product::whereIn(
                'id',
                array_keys($quantities)
            )
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');


            $lines = [];
            $subtotal = 0;


            foreach ($quantities as $id => $quantity) {

                $product = $products->get($id);


                if (
                    !$product ||
                    !$product->is_active ||
                    $quantity > $product->stock
                ) {

                    throw ValidationException::withMessages([
                        'cart'
                        =>
                        'Produk atau stok berubah. Silakan periksa kembali keranjang.'
                    ]);
                }


                $lineTotal =
                    (int)$product->price * $quantity;


                $subtotal += $lineTotal;


                $lines[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'lineTotal' => $lineTotal
                ];
            }


            if ($subtotal !== (int)$data['expected_total']) {

                throw ValidationException::withMessages([
                    'cart'
                    =>
                    'Harga produk berubah. Silakan muat ulang checkout.'
                ]);
            }


            $fullAddress =
                $data['address_detail']
                . ', '
                . $village->name
                . ', '
                . $district->name
                . ', '
                . $regency->name
                . ', '
                . $province->name;


            $order = Order::create([

                'order_number'
                    =>
                    'ABM-'
                    . now()->format('ymd')
                    . '-'
                    . Str::upper(Str::random(12)),


                'customer_name'
                    =>
                    $data['customer_name'],


                'customer_phone'
                    =>
                    $data['customer_phone'],


                'shipping_address'
                    =>
                    $fullAddress,


                'subtotal'
                    =>
                    $subtotal,


                'total'
                    =>
                    $subtotal,


                'payment_method'
                    =>
                    'cod',


                'status'
                    =>
                    'pending',

            ]);



            foreach ($lines as $line) {

                $product = $line['product'];


                $order->items()->create([

                    'product_id'
                        =>
                        $product->id,

                    'product_name'
                        =>
                        $product->name,

                    'price'
                        =>
                        $product->price,

                    'quantity'
                        =>
                        $line['quantity'],

                    'subtotal'
                        =>
                        $line['lineTotal'],

                ]);


                $product->decrement(
                    'stock',
                    $line['quantity']
                );
            }


            return $order;

        }, attempts: 3);



        $request->session()
            ->forget(CartService::SESSION_KEY);


        $request->session()
            ->put(
                'abm_last_order',
                $order->order_number
            );


        return redirect()
            ->route('checkout.success');
    }



    public function success(Request $request): View|RedirectResponse
    {
        $orderNumber =
            $request->session()
                ->get('abm_last_order');


        if (!is_string($orderNumber)) {
            return redirect()->route('shop.home');
        }


        $order =
            Order::with('items')
                ->where(
                    'order_number',
                    $orderNumber
                )
                ->first();


        if (!$order) {
            return redirect()->route('shop.home');
        }


        return view(
            'shop.success',
            compact('order')
        );
    }
}