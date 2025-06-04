<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', today()->startOfMonth());
        $endDate = $request->get('end_date', today());

        // Get sales with employee information
        $sales = Sale::with(['employee', 'saleItems.product'])
            ->dateRange($startDate, $endDate)
            ->orderBy('sale_date', 'desc')
            ->paginate(20);

        // Get summary statistics
        $totalSales = Sale::dateRange($startDate, $endDate)->sum('total_amount');
        $totalItems = Sale::dateRange($startDate, $endDate)->sum('total_items');
        $salesCount = Sale::dateRange($startDate, $endDate)->count();

        // Get top employees
        $topEmployees = Sale::select('employee_id')
            ->selectRaw('COUNT(*) as sales_count, SUM(total_amount) as total_sales')
            ->with('employee')
            ->dateRange($startDate, $endDate)
            ->groupBy('employee_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        // Get daily sales chart data
        $dailySales = Sale::selectRaw('DATE(sale_date) as date, SUM(total_amount) as daily_total')
            ->dateRange($startDate, $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.sales.index', compact(
            'sales',
            'totalSales',
            'totalItems',
            'salesCount',
            'topEmployees',
            'dailySales',
            'startDate',
            'endDate'
        ));
    }

    public function employeeReport(Request $request, $employeeId = null)
    {
        $startDate = $request->get('start_date', today()->startOfMonth());
        $endDate = $request->get('end_date', today());

        // Get all employees with sales
        $employees = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Employee');
        })->withCount(['sales' => function ($query) use ($startDate, $endDate) {
            $query->dateRange($startDate, $endDate);
        }])->with(['sales' => function ($query) use ($startDate, $endDate) {
            $query->dateRange($startDate, $endDate)
                ->selectRaw('employee_id, SUM(total_amount) as total_sales, SUM(total_items) as total_items')
                ->groupBy('employee_id');
        }])->get();

        $selectedEmployee = null;
        $employeeSales = collect();
        $dailyBreakdown = collect();

        if ($employeeId) {
            $selectedEmployee = User::find($employeeId);

            $employeeSales = Sale::byEmployee($employeeId)
                ->dateRange($startDate, $endDate)
                ->with(['saleItems.product'])
                ->orderBy('sale_date', 'desc')
                ->paginate(15);

            $dailyBreakdown = Sale::byEmployee($employeeId)
                ->dateRange($startDate, $endDate)
                ->selectRaw('DATE(sale_date) as date, COUNT(*) as sales_count, SUM(total_amount) as daily_total, SUM(total_items) as daily_items')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('admin.sales.employee-report', compact(
            'employees',
            'selectedEmployee',
            'employeeSales',
            'dailyBreakdown',
            'startDate',
            'endDate'
        ));
    }

    public function dashboard()
    {
        $today = today();
        $thisMonth = today()->startOfMonth();

        // Today's statistics
        $todayStats = [
            'sales' => Sale::whereDate('sale_date', $today)->sum('total_amount'),
            'items' => Sale::whereDate('sale_date', $today)->sum('total_items'),
            'transactions' => Sale::whereDate('sale_date', $today)->count(),
        ];

        // This month's statistics
        $monthStats = [
            'sales' => Sale::dateRange($thisMonth, $today)->sum('total_amount'),
            'items' => Sale::dateRange($thisMonth, $today)->sum('total_items'),
            'transactions' => Sale::dateRange($thisMonth, $today)->count(),
        ];

        // Top performing employees this month
        $topEmployees = Sale::select('employee_id')
            ->selectRaw('COUNT(*) as sales_count, SUM(total_amount) as total_sales')
            ->with('employee')
            ->dateRange($thisMonth, $today)
            ->groupBy('employee_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        // Recent sales
        $recentSales = Sale::with(['employee', 'saleItems'])
            ->orderBy('sale_date', 'desc')
            ->limit(10)
            ->get();

        // Sales trend (last 7 days)
        $salesTrend = Sale::selectRaw('DATE(sale_date) as date, SUM(total_amount) as daily_total')
            ->where('sale_date', '>=', today()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.sales.dashboard', compact(
            'todayStats',
            'monthStats',
            'topEmployees',
            'recentSales',
            'salesTrend'
        ));
    }
}
