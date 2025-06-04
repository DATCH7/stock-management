<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Request as StockRequest;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Get featured products (in stock, sorted by quantity)
        $featuredProducts = Product::where('quantity_in_stock', '>', 0)
            ->orderBy('quantity_in_stock', 'desc')
            ->take(8)
            ->get();

        // Get low stock products (less than 10 items)
        $lowStockProducts = Product::where('quantity_in_stock', '<', 10)
            ->where('quantity_in_stock', '>', 0)
            ->orderBy('quantity_in_stock', 'asc')
            ->take(5)
            ->get();

        // Get out of stock products
        $outOfStockProducts = Product::where('quantity_in_stock', 0)->count();

        // Get user's recent requests (for sidebar)
        $userRequests = StockRequest::where('user_id', $user->id)
            ->with('product')
            ->orderBy('request_date', 'desc')
            ->take(3)
            ->get();

        // Get product statistics
        $totalProducts = Product::count();
        $inStockProducts = Product::where('quantity_in_stock', '>', 0)->count();
        $categories = Product::distinct('category')->pluck('category');

        return view('employee.dashboard', compact(
            'featuredProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'userRequests',
            'totalProducts',
            'inStockProducts',
            'categories'
        ));
    }
}
