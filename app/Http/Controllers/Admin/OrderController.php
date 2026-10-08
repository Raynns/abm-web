<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::latest()->paginate(15),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items')]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['processing', 'completed', 'canceled'])],
        ]);

        DB::transaction(function () use ($order, $data): void {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $next = $data['status'];
            $allowed = [
                'pending' => ['processing', 'canceled'],
                'processing' => ['completed', 'canceled'],
                'completed' => [],
                'canceled' => [],
            ];

            if (!in_array($next, $allowed[$lockedOrder->status] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status tidak diperbolehkan dari kondisi pesanan saat ini.',
                ]);
            }

            if ($next === 'canceled') {
                $items = $lockedOrder->items()->whereNotNull('product_id')->orderBy('product_id')->get();
                $products = Product::whereIn('id', $items->pluck('product_id')->all())
                    ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

                foreach ($items as $item) {
                    $product = $products->get($item->product_id);
                    if (!$product) {
                        throw ValidationException::withMessages([
                            'status' => 'Ada produk yang sudah dihapus permanen sehingga stok tidak dapat dipulihkan otomatis.',
                        ]);
                    }
                    if ($product->stock + $item->quantity > 4294967295) {
                        throw ValidationException::withMessages([
                            'status' => 'Stok akan melebihi kapasitas sistem jika pesanan dibatalkan.',
                        ]);
                    }
                    $product->increment('stock', $item->quantity);
                }
            }

            $lockedOrder->update(['status' => $next]);
        }, attempts: 3);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
