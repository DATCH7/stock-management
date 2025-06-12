<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function createEntry()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-manager.entries.create', compact('products'));
    }

    public function storeEntry(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'entry_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Create stock entry record
            StockEntry::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'entry_date' => $request->entry_date,
                'recorded_by' => auth()->id()
            ]);

            // Update product stock
            $product = Product::where('product_id', $request->product_id)->first();
            $product->quantity_in_stock += $request->quantity;
            $product->save();

            DB::commit();

            return redirect()->route('stock.dashboard')
                ->with('success', "Successfully added {$request->quantity} units to {$product->name}");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to record stock entry: ' . $e->getMessage()])->withInput();
        }
    }

    public function createExit()
    {
        $products = Product::where('quantity_in_stock', '>', 0)->orderBy('name')->get();
        return view('stock-manager.exits.create', compact('products'));
    }

    public function storeExit(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
            'exit_date' => 'required|date',
            'reason' => 'required|string|max:500'
        ]);

        $product = Product::where('product_id', $request->product_id)->first();

        if ($request->quantity > $product->quantity_in_stock) {
            return back()->withErrors(['quantity' => 'Exit quantity cannot exceed available stock (' . $product->quantity_in_stock . ')'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Create stock exit record
            StockExit::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'exit_date' => $request->exit_date,
                'recorded_by' => auth()->id(),
                'reason' => $request->reason
            ]);

            // Update product stock
            $product->quantity_in_stock -= $request->quantity;
            $product->save();

            DB::commit();

            return redirect()->route('stock.dashboard')
                ->with('success', "Successfully recorded exit of {$request->quantity} units from {$product->name}");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to record stock exit: ' . $e->getMessage()])->withInput();
        }
    }

    public function movements()
    {
        $entries = StockEntry::with(['product', 'recordedBy'])
            ->orderBy('entry_date', 'desc')
            ->paginate(10, ['*'], 'entries_page');

        $exits = StockExit::with(['product', 'recordedBy'])
            ->orderBy('exit_date', 'desc')
            ->paginate(10, ['*'], 'exits_page');

        return view('stock-manager.movements.index', compact('entries', 'exits'));
    }
}
