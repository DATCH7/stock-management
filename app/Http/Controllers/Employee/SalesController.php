<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.productId' => 'required|exists:products,product_id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,card,mobile',
            'amount_received' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $totalItems = 0;

            foreach ($request->cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $totalItems += $item['quantity'];
            }

            $taxAmount = $subtotal * 0.08; // 8% tax
            $totalAmount = $subtotal + $taxAmount;

            // Calculate change for cash payments
            $amountReceived = $request->payment_method === 'cash' ? $request->amount_received : $totalAmount;
            $changeGiven = $request->payment_method === 'cash' ? $amountReceived - $totalAmount : 0;

            // Create sale record
            $sale = Sale::create([
                'employee_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'amount_received' => $amountReceived,
                'change_given' => $changeGiven,
                'total_items' => $totalItems,
                'sale_date' => now(),
            ]);

            // Create sale items and update product quantities
            foreach ($request->cart as $item) {
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->sale_id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Update product quantity
                $product = Product::where('product_id', $item['productId'])->first();
                if ($product) {
                    $product->quantity_in_stock -= $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale recorded successfully',
                'sale_id' => $sale->sale_id,
                'total' => $totalAmount,
                'change' => $changeGiven,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function processCurrentCart(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cash,card,mobile',
            'amount_received' => 'nullable|numeric|min:0',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $totalItems = 0;

            // Verify stock availability and calculate totals
            foreach ($cart as $item) {
                $product = Product::where('product_id', $item['product_id'])->first();

                if (!$product) {
                    throw new \Exception("Product {$item['name']} not found");
                }

                if ($product->quantity_in_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$item['name']}. Available: {$product->quantity_in_stock}");
                }

                $subtotal += $item['price'] * $item['quantity'];
                $totalItems += $item['quantity'];
            }

            $taxAmount = $subtotal * 0.08; // 8% tax
            $totalAmount = $subtotal + $taxAmount;

            // Validate payment amount for cash
            if ($request->payment_method === 'cash') {
                if (!$request->amount_received || $request->amount_received < $totalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient payment amount'
                    ], 400);
                }
            }

            // Calculate change for cash payments
            $amountReceived = $request->payment_method === 'cash' ? $request->amount_received : $totalAmount;
            $changeGiven = $request->payment_method === 'cash' ? $amountReceived - $totalAmount : 0;

            // Create sale record
            $sale = Sale::create([
                'employee_id' => auth()->id(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'amount_received' => $amountReceived,
                'change_given' => $changeGiven,
                'total_items' => $totalItems,
                'sale_date' => now(),
            ]);

            // Create sale items and update product quantities
            foreach ($cart as $item) {
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->sale_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Update product quantity
                $product = Product::where('product_id', $item['product_id'])->first();
                if ($product) {
                    $product->quantity_in_stock -= $item['quantity'];
                    $product->save();
                }
            }

            // Clear the cart after successful sale
            Session::forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'sale' => [
                    'sale_id' => $sale->sale_id,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'amount_received' => $amountReceived,
                    'change_given' => $changeGiven,
                    'total_items' => $totalItems,
                    'sale_date' => $sale->sale_date->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function dailySales(Request $request)
    {
        $date = $request->get('date', today());
        $employeeId = auth()->id();

        $sales = Sale::byEmployee($employeeId)
            ->whereDate('sale_date', $date)
            ->with(['saleItems.product'])
            ->orderBy('sale_date', 'desc')
            ->get();

        $dailyTotal = $sales->sum('total_amount');
        $dailyItems = $sales->sum('total_items');
        $salesCount = $sales->count();

        return response()->json([
            'date' => $date,
            'total_sales' => $dailyTotal,
            'total_items' => $dailyItems,
            'sales_count' => $salesCount,
            'sales' => $sales,
        ]);
    }

    public function employeeSalesReport(Request $request)
    {
        $startDate = $request->get('start_date', today()->startOfMonth());
        $endDate = $request->get('end_date', today());
        $employeeId = $request->get('employee_id', auth()->id());

        $sales = Sale::byEmployee($employeeId)
            ->dateRange($startDate, $endDate)
            ->selectRaw('DATE(sale_date) as date, COUNT(*) as sales_count, SUM(total_amount) as daily_total, SUM(total_items) as daily_items')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $totalSales = Sale::byEmployee($employeeId)
            ->dateRange($startDate, $endDate)
            ->sum('total_amount');

        $totalItems = Sale::byEmployee($employeeId)
            ->dateRange($startDate, $endDate)
            ->sum('total_items');

        return response()->json([
            'employee_id' => $employeeId,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_sales' => $totalSales,
                'total_items' => $totalItems,
                'sales_count' => $sales->sum('sales_count'),
            ],
            'daily_breakdown' => $sales,
        ]);
    }

    public function todaysSummary()
    {
        $employeeId = auth()->id();
        $today = today();

        $todaysSales = Sale::byEmployee($employeeId)
            ->whereDate('sale_date', $today)
            ->get();

        $summary = [
            'date' => $today->format('Y-m-d'),
            'total_sales' => $todaysSales->sum('total_amount'),
            'total_items' => $todaysSales->sum('total_items'),
            'sales_count' => $todaysSales->count(),
            'payment_methods' => $todaysSales->groupBy('payment_method')->map(function ($sales, $method) {
                return [
                    'method' => $method,
                    'count' => $sales->count(),
                    'total' => $sales->sum('total_amount'),
                ];
            })->values(),
        ];

        return response()->json($summary);
    }
}
