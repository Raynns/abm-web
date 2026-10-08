<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('kategori');
        $category = is_string($category) ? $category : null;
        $categories = Category::orderBy('name')->get();
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($category, function ($query) use ($category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        return view('shop.home', compact('categories', 'products', 'category'));
    }
}
