<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'availableCount' => Product::where('is_active', true)->where('stock', '>', 0)->count(),
            'orderCount' => Order::count(),
            'pendingCount' => Order::where('status', 'pending')->count(),
            'completedValue' => Order::where('status', 'completed')->sum('total'),
            'latestOrders' => Order::latest()->limit(5)->get(),
        ]);
    }
}
