<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\RequestController as EmployeeRequestController;
use App\Http\Controllers\Employee\ProductController as EmployeeProductController;
use App\Http\Controllers\Employee\SalesController;
use App\Http\Controllers\Employee\CartController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\StockManagerController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role->role_name;

    return match ($role) {
        'Admin' => redirect()->route('admin.dashboard'),
        'stock_manager' => redirect()->route('stock.dashboard'),
        'Employee' => redirect()->route('employee.dashboard'),
        default => abort(403, 'Unauthorized'),
    };
});

// ✅ REMOVE THIS — no longer needed
// Route::get('/dashboard', ... );

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:stock_manager'])->prefix('stock')->name('stock.')->group(function () {
    Route::get('/dashboard', [StockManagerController::class, 'dashboard'])->name('dashboard');
    Route::get('/low-stock-report', [StockManagerController::class, 'lowStockReport'])->name('low-stock-report');
    Route::get('/movements', [StockMovementController::class, 'movements'])->name('movements');
    Route::get('/requests', [StockManagerController::class, 'pendingRequests'])->name('requests');
    Route::post('/requests/{request}/approve', [StockManagerController::class, 'approveRequest'])->name('requests.approve');
    Route::post('/requests/{request}/reject', [StockManagerController::class, 'rejectRequest'])->name('requests.reject');
    Route::post('/requests/bulk-approve', [StockManagerController::class, 'bulkApproveRequests'])->name('requests.bulk-approve');
    Route::post('/requests/bulk-reject', [StockManagerController::class, 'bulkRejectRequests'])->name('requests.bulk-reject');

    // Stock Entry routes
    Route::get('/entries/create', [StockMovementController::class, 'createEntry'])->name('entries.create');
    Route::post('/entries', [StockMovementController::class, 'storeEntry'])->name('entries.store');

    // Stock Exit routes
    Route::get('/exits/create', [StockMovementController::class, 'createExit'])->name('exits.create');
    Route::post('/exits', [StockMovementController::class, 'storeExit'])->name('exits.store');
});

Route::middleware(['auth', 'role:Employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');

    // Request routes
    Route::get('/requests', [EmployeeRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [EmployeeRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [EmployeeRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{request}', [EmployeeRequestController::class, 'show'])->name('requests.show');

    // Product routes
    Route::get('/products', [EmployeeProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [EmployeeProductController::class, 'create'])->name('products.create');
    Route::post('/products', [EmployeeProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [EmployeeProductController::class, 'show'])->name('products.show');

    // Cart routes
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

    // Sales routes
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::post('/sales/process-cart', [SalesController::class, 'processCurrentCart'])->name('sales.process-cart');
    Route::get('/sales/daily', [SalesController::class, 'dailySales'])->name('sales.daily');
    Route::get('/sales/report', [SalesController::class, 'employeeSalesReport'])->name('sales.report');
    Route::get('/sales/today', [SalesController::class, 'todaysSummary'])->name('sales.today');
});

Route::get('/redirect-after-login', function () {
    $role = auth()->user()->role->role_name;

    return match ($role) {
        'Admin' => redirect()->route('admin.dashboard'),
        'stock_manager' => redirect()->route('stock.dashboard'),
        'Employee' => redirect()->route('employee.dashboard'),
        default => abort(403),
    };
})->middleware('auth');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.admin');
    })->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);

    // Product routes
    Route::resource('products', AdminProductController::class);

    // Sales routes
    Route::get('/sales', [AdminSalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/dashboard', [AdminSalesController::class, 'dashboard'])->name('sales.dashboard');
    Route::get('/sales/employee-report/{employee?}', [AdminSalesController::class, 'employeeReport'])->name('sales.employee-report');
});


require __DIR__ . '/auth.php';
