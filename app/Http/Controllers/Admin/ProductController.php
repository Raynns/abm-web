<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(8));
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image']);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($data['image']);
        $oldImage = $product->image_path;
        $product->update($data);
        if ($oldImage && isset($data['image_path'])) {
            Storage::disk('public')->delete($oldImage);
        }
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diubah.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Lock untuk mencegah race dengan checkout yang sedang memesan produk ini.
        $result = DB::transaction(function () use ($product): array {
            $lockedProduct = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
            if ($lockedProduct->orderItems()->exists()) {
                $lockedProduct->update(['is_active' => false]);
                return ['archived' => true, 'image' => null];
            }
            $image = $lockedProduct->image_path;
            $lockedProduct->delete();
            return ['archived' => false, 'image' => $image];
        }, attempts: 3);

        if ($result['archived']) {
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk memiliki riwayat transaksi; produk dinonaktifkan agar stok tetap terlacak.');
        }
        if ($result['image']) {
            Storage::disk('public')->delete($result['image']);
        }
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0', 'max:999999999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
