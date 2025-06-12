<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Request as StockRequest;
use App\Models\StockEntry;
use App\Models\StockExit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockManagerController extends Controller
{
    public function dashboard()
    {
        // Get basic statistics
        $totalProducts = Product::count();
        $inStockProducts = Product::where('quantity_in_stock', '>', 0)->count();
        $lowStockProducts = Product::where('quantity_in_stock', '>', 0)
            ->where('quantity_in_stock', '<', 10)
            ->count();
        $outOfStockProducts = Product::where('quantity_in_stock', 0)->count();

        // Get low stock alerts (products with less than 10 items)
        $lowStockAlerts = Product::where('quantity_in_stock', '>', 0)
            ->where('quantity_in_stock', '<', 10)
            ->orderBy('quantity_in_stock', 'asc')
            ->take(5)
            ->get();

        // Get pending requests assigned to current stock manager
        $pendingRequests = StockRequest::where('request_status', 'pending')
            ->where('approver_id', auth()->id())
            ->with(['product', 'user'])
            ->orderBy('request_date', 'desc')
            ->take(5)
            ->get();

        // Get recent stock movements
        $recentMovements = collect();

        // Get recent entries
        $recentEntries = StockEntry::with(['product', 'recordedBy'])
            ->orderBy('entry_date', 'desc')
            ->take(3)
            ->get()
            ->map(function ($entry) {
                return [
                    'type' => 'entry',
                    'product' => $entry->product,
                    'quantity' => $entry->quantity,
                    'date' => $entry->entry_date,
                    'user' => $entry->recordedBy,
                    'reason' => $entry->reason ?? 'Stock entry'
                ];
            });

        // Get recent exits
        $recentExits = StockExit::with(['product', 'recordedBy'])
            ->orderBy('exit_date', 'desc')
            ->take(3)
            ->get()
            ->map(function ($exit) {
                return [
                    'type' => 'exit',
                    'product' => $exit->product,
                    'quantity' => $exit->quantity,
                    'date' => $exit->exit_date,
                    'user' => $exit->recordedBy,
                    'reason' => $exit->reason ?? 'Stock exit'
                ];
            });

        $recentMovements = $recentEntries->concat($recentExits)
            ->sortByDesc('date')
            ->take(5);

        // Calculate stock value
        $totalStockValue = Product::selectRaw('SUM(quantity_in_stock * price) as total_value')
            ->value('total_value') ?? 0;

        $averageProductValue = $totalProducts > 0 ? $totalStockValue / $totalProducts : 0;
        $totalUnits = Product::sum('quantity_in_stock');

        return view('dashboards.stock', compact(
            'totalProducts',
            'inStockProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockAlerts',
            'pendingRequests',
            'recentMovements',
            'totalStockValue',
            'averageProductValue',
            'totalUnits'
        ));
    }

    public function lowStockReport()
    {
        $lowStockProducts = Product::whereBetween('quantity_in_stock', [1, 10])
            ->orderBy('quantity_in_stock', 'asc')
            ->paginate(20);

        return view('stock-manager.reports.low-stock', compact('lowStockProducts'));
    }

    public function stockMovements()
    {
        $entries = StockEntry::with(['product', 'recordedBy'])
            ->orderBy('entry_date', 'desc')
            ->paginate(15);

        $exits = StockExit::with(['product', 'recordedBy'])
            ->orderBy('exit_date', 'desc')
            ->paginate(15);

        return view('stock-manager.movements.index', compact('entries', 'exits'));
    }

    public function pendingRequests(Request $request)
    {
        $query = StockRequest::where('request_status', 'pending')
            ->where('approver_id', auth()->id()) // Only show requests assigned to current stock manager
            ->with(['product', 'user']);

        // Filter by employee
        if ($request->filled('employee')) {
            $query->where('user_id', $request->get('employee'));
        }

        // Filter by product
        if ($request->filled('product')) {
            $query->where('product_id', $request->get('product'));
        }

        // Sort options
        $sort = $request->get('sort', 'priority');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('request_date', 'asc');
                break;
            case 'newest':
                $query->orderBy('request_date', 'desc');
                break;
            case 'quantity_high':
                $query->orderBy('quantity', 'desc');
                break;
            case 'quantity_low':
                $query->orderBy('quantity', 'asc');
                break;
            case 'priority':
            default:
                // Sort by priority (urgent first) then by date
                $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
                    ->orderBy('request_date', 'desc');
                break;
        }

        $requests = $query->paginate(15);

        // Preserve query parameters in pagination links
        $requests->appends($request->query());

        return view('stock-manager.requests.pending', compact('requests'));
    }

    public function approveRequest(StockRequest $request)
    {
        // Handle new product requests differently
        if ($request->isNewProductRequest()) {
            $request->update([
                'request_status' => 'approved',
                'approver_id' => auth()->id(),
                'approval_date' => now()
            ]);

            return redirect()->back()->with('success', 'New product request approved. You can now create this product in the inventory system.');
        }

        $request->update([
            'request_status' => 'approved',
            'approver_id' => auth()->id(),
            'approval_date' => now()
        ]);

        // Only update stock and create exit record if there's sufficient stock
        $product = $request->product;
        if ($request->quantity <= $product->quantity_in_stock) {
            // Update product stock
            $product->quantity_in_stock -= $request->quantity;
            $product->save();

            // Create stock exit record
            StockExit::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'exit_date' => now(),
                'recorded_by' => auth()->id(),
                'reason' => 'Approved request from ' . $request->user->full_name . ' - ' . $request->reason
            ]);
        }

        return redirect()->back()->with('success', 'Request approved successfully.');
    }

    public function rejectRequest(StockRequest $request)
    {
        $request->update([
            'request_status' => 'rejected',
            'approver_id' => auth()->id(),
            'approval_date' => now()
        ]);

        return redirect()->back()->with('success', 'Request rejected.');
    }

    public function bulkApproveRequests(Request $request)
    {
        $requestIds = $request->input('request_ids', []);

        if (empty($requestIds)) {
            return redirect()->back()->withErrors(['error' => 'No requests selected.']);
        }

        $requests = StockRequest::whereIn('id', $requestIds)
            ->where('request_status', 'pending')
            ->with(['product', 'user'])
            ->get();

        $approvedCount = 0;
        $errors = [];

        foreach ($requests as $stockRequest) {
            // Approve the request
            $stockRequest->update([
                'request_status' => 'approved',
                'approver_id' => auth()->id(),
                'approval_date' => now()
            ]);

            // Only update stock if there's sufficient stock available
            $product = $stockRequest->product;
            if ($stockRequest->quantity <= $product->quantity_in_stock) {
                // Update product stock
                $product->quantity_in_stock -= $stockRequest->quantity;
                $product->save();

                // Create stock exit record
                StockExit::create([
                    'product_id' => $stockRequest->product_id,
                    'quantity' => $stockRequest->quantity,
                    'exit_date' => now(),
                    'recorded_by' => auth()->id(),
                    'reason' => 'Bulk approved request from ' . $stockRequest->user->full_name . ' - ' . $stockRequest->reason
                ]);
            }

            $approvedCount++;
        }

        $message = "Successfully approved {$approvedCount} requests.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    public function bulkRejectRequests(Request $request)
    {
        $requestIds = $request->input('request_ids', []);

        if (empty($requestIds)) {
            return redirect()->back()->withErrors(['error' => 'No requests selected.']);
        }

        $rejectedCount = StockRequest::whereIn('id', $requestIds)
            ->where('request_status', 'pending')
            ->update([
                'request_status' => 'rejected',
                'approver_id' => auth()->id(),
                'approval_date' => now()
            ]);

        return redirect()->back()->with('success', "Successfully rejected {$rejectedCount} requests.");
    }
}
