<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Request as StockRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $requests = StockRequest::where('user_id', $user->id)
            ->with(['product', 'approver'])
            ->orderBy('request_date', 'desc')
            ->paginate(15);

        return view('employee.requests.index', compact('requests'));
    }

    public function create()
    {
        $products = Product::where('quantity_in_stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('employee.requests.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        // Check if requested quantity is available
        $product = Product::find($validated['product_id']);
        if ($validated['quantity'] > $product->quantity_in_stock) {
            return back()->withErrors(['quantity' => 'Requested quantity exceeds available stock.'])->withInput();
        }

        StockRequest::create([
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'request_date' => now(),
            'request_status' => 'pending',
        ]);

        return redirect()->route('employee.requests.index')
            ->with('success', 'Stock request submitted successfully.');
    }

    public function show(StockRequest $request)
    {
        // Ensure user can only view their own requests
        if ($request->user_id !== auth()->id()) {
            abort(403);
        }

        $request->load(['product', 'approver']);

        return view('employee.requests.show', compact('request'));
    }
}
