<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Stock filter
        if ($request->filled('stock_filter')) {
            $stockFilter = $request->get('stock_filter');
            if ($stockFilter === 'in_stock') {
                $query->where('quantity_in_stock', '>', 0);
            } elseif ($stockFilter === 'low_stock') {
                $query->where('quantity_in_stock', '>', 0)
                    ->where('quantity_in_stock', '<', 10);
            } elseif ($stockFilter === 'out_of_stock') {
                $query->where('quantity_in_stock', '=', 0);
            }
        }

        $products = $query->orderBy('name')->paginate(12);

        // Get unique categories for filter dropdown
        $categories = Product::distinct()->pluck('category')->filter()->sort();

        return view('employee.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('employee.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('employee.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('employee.products.show', compact('product'));
    }
}
