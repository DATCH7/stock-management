<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Request as StockRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = StockRequest::where('user_id', $user->id)
            ->with(['product', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('request_status', $request->get('status'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('request_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('request_date', '<=', $request->get('date_to'));
        }

        $requests = $query->orderBy('request_date', 'desc')->paginate(15);

        // Preserve query parameters in pagination links
        $requests->appends($request->query());

        return view('employee.requests.index', compact('requests'));
    }

    public function create()
    {
        // Include all products, not just those in stock
        $products = Product::orderBy('name')->get();

        // Get all stock managers for assignment
        $stockManagerRole = Role::where('role_name', 'stock_manager')->first();
        $stockManagers = User::where('role_id', $stockManagerRole->id)->get();

        return view('employee.requests.create', compact('products', 'stockManagers'));
    }

    public function store(Request $request)
    {
        if ($request->input('request_type') === 'new') {
            // Handle new product request
            $validated = $request->validate([
                'request_type' => 'required|in:existing,new',
                'new_product_name' => 'required|string|max:255',
                'new_product_category' => 'required|string|max:100',
                'new_product_description' => 'required|string',
                'new_product_brand' => 'nullable|string|max:255',
                'estimated_price' => 'nullable|numeric|min:0',
                'supplier_info' => 'nullable|string',
                'quantity' => 'required|integer|min:1',
                'reason' => 'required|string|max:500',
                'priority' => 'required|in:low,normal,high,urgent',
                'approver_id' => 'required|exists:users,id',
            ]);

            // Create detailed reason for new product
            $detailedReason = "NEW PRODUCT REQUEST\n";
            $detailedReason .= "Product: {$validated['new_product_name']}\n";
            $detailedReason .= "Category: {$validated['new_product_category']}\n";
            $detailedReason .= "Description: {$validated['new_product_description']}\n";

            if (!empty($validated['new_product_brand'])) {
                $detailedReason .= "Brand: {$validated['new_product_brand']}\n";
            }

            if (!empty($validated['estimated_price'])) {
                $detailedReason .= "Estimated Price: {$validated['estimated_price']} DHS\n";
            }

            if (!empty($validated['supplier_info'])) {
                $detailedReason .= "Supplier Info: {$validated['supplier_info']}\n";
            }

            $detailedReason .= "Reason: {$validated['reason']}";

            StockRequest::create([
                'product_id' => null, // No existing product
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'reason' => $detailedReason,
                'priority' => $validated['priority'],
                'request_date' => now(),
                'request_status' => 'pending',
                'approver_id' => $validated['approver_id'],
            ]);

            return redirect()->route('employee.requests.index')
                ->with('success', 'New product request submitted successfully. The selected stock manager will review your request and may add this product to inventory.');
        } else {
            // Handle existing product request
            $validated = $request->validate([
                'request_type' => 'required|in:existing,new',
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:1',
                'reason' => 'required|string|max:500',
                'priority' => 'required|in:low,normal,high,urgent',
                'approver_id' => 'required|exists:users,id',
            ]);

            // Get the product
            $product = Product::find($validated['product_id']);

            // Allow requests even if stock is insufficient, but add a note
            $requestStatus = 'pending';
            $additionalNotes = '';

            if ($validated['quantity'] > $product->quantity_in_stock) {
                $additionalNotes = " [INSUFFICIENT STOCK: Requested {$validated['quantity']}, Available {$product->quantity_in_stock}]";
            }

            StockRequest::create([
                'product_id' => $validated['product_id'],
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] . $additionalNotes,
                'priority' => $validated['priority'],
                'request_date' => now(),
                'request_status' => $requestStatus,
                'approver_id' => $validated['approver_id'],
            ]);

            $message = 'Stock request submitted successfully to the selected stock manager.';
            if ($validated['quantity'] > $product->quantity_in_stock) {
                $message .= ' Note: Requested quantity exceeds available stock. Stock manager will review for restocking options.';
            }

            return redirect()->route('employee.requests.index')
                ->with('success', $message);
        }
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
