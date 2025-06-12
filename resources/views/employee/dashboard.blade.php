<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Point of Sale</h1>
                        <p class="mt-2 text-gray-600">Welcome back, {{ auth()->user()->full_name }}! Start selling products and manage your inventory.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            All Products
                        </a>
                        <a href="{{ route('employee.requests.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Request
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Products</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">In Stock</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $inStockProducts }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Low Stock</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockProducts->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Out of Stock</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $outOfStockProducts }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Featured Products (Main Content) -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Featured Products</h3>
                                <a href="{{ route('employee.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">View all products</a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($featuredProducts->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($featuredProducts as $product)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="p-4">
                                        <!-- Product Header -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $product->name }}</h4>
                                                <p class="text-xs text-gray-500 truncate">{{ $product->category }}</p>
                                            </div>
                                            <div class="ml-2 flex-shrink-0">
                                                <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded
                                                    {{ $product->quantity_in_stock > 10 ? 'bg-green-100 text-green-700' : '' }}
                                                    {{ $product->quantity_in_stock > 0 && $product->quantity_in_stock <= 10 ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                                    {{ $product->quantity_in_stock > 0 ? 'In Stock' : 'Out' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Product Details -->
                                        <div class="space-y-1 mb-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Price:</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($product->price, 2) }} DHS</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Available:</span>
                                                <span class="text-xs font-medium text-gray-900">{{ $product->quantity_in_stock }} units</span>
                                            </div>
                                        </div>

                                        <!-- Quick Sale Button -->
                                        @if($product->quantity_in_stock > 0)
                                        <div class="border-t border-gray-200 pt-3">
                                            <form class="sale-form" data-product-id="{{ $product->product_id }}" data-product-price="{{ $product->price }}">
                                                <div class="flex items-center space-x-1 mb-2">
                                                    <input type="number"
                                                        name="quantity"
                                                        min="1"
                                                        max="{{ $product->quantity_in_stock }}"
                                                        value="1"
                                                        class="quantity-input w-12 px-1 py-0.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-gray-500">
                                                    <span class="text-xs text-gray-500">×</span>
                                                    <span class="text-xs text-gray-900">{{ number_format($product->price, 2) }} DHS</span>
                                                </div>

                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-medium text-gray-700">Total:</span>
                                                    <span class="total-price text-sm font-bold text-green-600">{{ number_format($product->price, 2) }} DHS</span>
                                                </div>

                                                <button type="button"
                                                    class="add-to-sale w-full px-2 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                                    Add to Sale
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="border-t border-gray-200 pt-3">
                                            <button disabled class="w-full px-2 py-1.5 bg-gray-300 text-gray-500 text-xs font-medium rounded cursor-not-allowed">
                                                Out of Stock
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No products available</h3>
                                <p class="mt-1 text-sm text-gray-500">Check back later for available products.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <a href="{{ route('employee.products.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">All Products</p>
                                    <p class="text-xs text-gray-500">Browse & sell products</p>
                                </div>
                            </a>

                            <a href="{{ route('employee.requests.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">New Request</p>
                                    <p class="text-xs text-gray-500">Request stock items</p>
                                </div>
                            </a>

                            <a href="{{ route('employee.requests.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">My Requests</p>
                                    <p class="text-xs text-gray-500">Track your requests</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Requests -->
                    @if($userRequests->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Requests</h3>
                                <a href="{{ route('employee.requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($userRequests as $request)
                                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $request->product->name }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $request->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $request->request_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $request->request_status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $request->request_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($request->request_status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Low Stock Alert -->
                    @if($lowStockProducts->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Low Stock Items</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($lowStockProducts as $product)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->category }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-yellow-600">{{ $product->quantity_in_stock }}</p>
                                        <p class="text-xs text-gray-500">remaining</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
        <div class="flex flex-col h-full">
            <!-- Cart Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Cart</h3>
                    <div class="flex items-center space-x-2">
                        <button id="clear-cart" class="text-red-500 hover:text-red-700 text-sm">
                            Clear All
                        </button>
                        <button id="close-cart" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Items: <span id="total-items">0</span></p>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <div id="cart-items" class="space-y-4">
                    <!-- Cart items will be populated here -->
                </div>
                <div id="empty-cart" class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Cart is empty</p>
                    <p class="text-gray-400 text-sm mt-1">Add products to start a sale</p>
                </div>
            </div>

            <!-- Cart Summary & Checkout -->
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                <!-- Subtotal -->
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Subtotal:</span>
                    <span id="cart-subtotal" class="text-sm font-medium text-gray-900">0.00 DHS</span>
                </div>



                <!-- Total -->
                <div class="flex justify-between items-center mb-4 pt-2 border-t border-gray-300">
                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                    <span id="cart-total" class="text-xl font-bold text-green-600">0.00 DHS</span>
                </div>

                <!-- Payment Section -->
                <div id="payment-section" class="space-y-3 hidden">
                    <div>
                        <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="cash">Cash</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="mobile">Mobile Payment</option>
                        </select>
                    </div>

                    <div id="cash-payment" class="space-y-2">
                        <label for="amount-received" class="block text-sm font-medium text-gray-700">Amount Received</label>
                        <input type="number" id="amount-received" step="0.01" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="0.00">
                        <div id="change-amount" class="text-sm text-gray-600 hidden">
                            Change: <span class="font-semibold text-green-600">0.00 DHS</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <button id="checkout-btn" class="w-full px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                        Proceed to Payment
                    </button>

                    <button id="process-sale" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors hidden">
                        Complete Sale
                    </button>

                    <button id="cancel-payment" class="w-full px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors hidden">
                        Back to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Toggle Button - Enhanced -->
    <button id="cart-toggle" style="position: fixed !important; bottom: 24px !important; right: 24px !important; z-index: 9999 !important; display: block !important; width: 70px; height: 70px; background: #111827; color: white; border-radius: 50%; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: none; cursor: pointer; transition: all 0.3s ease;">
        <div class="relative">
            <svg style="width: 32px; height: 32px; margin: 0 auto; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
            <span id="cart-count" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; background: #dc2626; color: white; font-size: 14px; font-weight: bold; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2); display: none;">0</span>
        </div>
        <div style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); font-size: 10px; font-weight: bold; color: white;">
            CART
        </div>
    </button>

    <!-- Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sale Completed!</h3>
                    <div id="sale-summary" class="text-sm text-gray-600 mb-4">
                        <!-- Sale summary will be populated here -->
                    </div>
                    <button id="close-success-modal" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Start New Sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Shopping cart functionality
        let cart = [];

        // Update quantity and total price
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', function() {
                const form = this.closest('.sale-form');
                const price = parseFloat(form.dataset.productPrice);
                const quantity = parseInt(this.value) || 1;
                const total = price * quantity;

                form.querySelector('.total-price').textContent = '$' + total.toFixed(2);
            });
        });

        // Add to cart functionality
        document.querySelectorAll('.add-to-sale').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.sale-form');
                const productId = form.dataset.productId;
                const price = parseFloat(form.dataset.productPrice);
                const quantity = parseInt(form.querySelector('.quantity-input').value);
                const productName = form.closest('.bg-gray-50').querySelector('h4').textContent;

                addToCart(productId, productName, price, quantity);

                // Reset quantity input
                form.querySelector('.quantity-input').value = 1;
                form.querySelector('.total-price').textContent = '$' + price.toFixed(2);

                // Show success feedback
                showAddToCartFeedback(button);
            });
        });

        function showAddToCartFeedback(button) {
            const originalText = button.textContent;
            button.textContent = 'Added!';
            button.classList.add('bg-green-700');

            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-700');
            }, 1000);
        }

        function addToCart(productId, productName, price, quantity) {
            const existingItem = cart.find(item => item.productId === productId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    productId: productId,
                    productName: productName,
                    price: price,
                    quantity: quantity
                });
            }

            updateCartDisplay();

            // Removed auto-open cart functionality - cart will only open when clicked
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            const cartSubtotal = document.getElementById('cart-subtotal');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-count');
            const totalItems = document.getElementById('total-items');
            const checkoutBtn = document.getElementById('checkout-btn');

            if (cart.length === 0) {
                cartItems.innerHTML = '';
                emptyCart.style.display = 'block';
                cartCount.style.display = 'none';
                checkoutBtn.disabled = true;
                cartSubtotal.textContent = '0.00 DHS';
                cartTotal.textContent = '0.00 DHS';
                totalItems.textContent = '0';
                return;
            }

            emptyCart.style.display = 'none';
            cartCount.style.display = 'flex';
            checkoutBtn.disabled = false;

            const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            cartCount.textContent = totalQuantity;
            totalItems.textContent = totalQuantity;
            cartSubtotal.textContent = total.toFixed(2) + ' DHS';
            cartTotal.textContent = total.toFixed(2) + ' DHS';

            cartItems.innerHTML = cart.map((item, index) => `
                <div class="bg-white border border-gray-200 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-900 text-sm">${item.productName}</h4>
                        <button onclick="removeFromCart('${item.productId}')" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <button onclick="updateQuantity('${item.productId}', -1)" class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300">-</button>
                            <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.productId}', 1)" class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300">+</button>
                            <span class="text-xs text-gray-500">× ${item.price.toFixed(2)} DHS</span>
                        </div>
                        <span class="font-semibold text-gray-900">${(item.price * item.quantity).toFixed(2)} DHS</span>
                    </div>
                </div>
            `).join('');
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.productId === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    updateCartDisplay();
                }
            }
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.productId !== productId);
            updateCartDisplay();
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
            hidePaymentSection();
        }

        // Payment functionality
        document.getElementById('checkout-btn').addEventListener('click', function() {
            showPaymentSection();
        });

        document.getElementById('cancel-payment').addEventListener('click', function() {
            hidePaymentSection();
        });

        document.getElementById('clear-cart').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear the cart?')) {
                clearCart();
            }
        });

        function showPaymentSection() {
            document.getElementById('payment-section').classList.remove('hidden');
            document.getElementById('checkout-btn').classList.add('hidden');
            document.getElementById('process-sale').classList.remove('hidden');
            document.getElementById('cancel-payment').classList.remove('hidden');

            // Set focus on amount received for cash payments
            const total = parseFloat(document.getElementById('cart-total').textContent.replace('$', ''));
            document.getElementById('amount-received').value = total.toFixed(2);
            document.getElementById('amount-received').focus();
            document.getElementById('amount-received').select();
        }

        function hidePaymentSection() {
            document.getElementById('payment-section').classList.add('hidden');
            document.getElementById('checkout-btn').classList.remove('hidden');
            document.getElementById('process-sale').classList.add('hidden');
            document.getElementById('cancel-payment').classList.add('hidden');
            document.getElementById('change-amount').classList.add('hidden');
        }

        // Calculate change
        document.getElementById('amount-received').addEventListener('input', function() {
            const total = parseFloat(document.getElementById('cart-total').textContent.replace(' DHS', ''));
            const received = parseFloat(this.value) || 0;
            const change = received - total;

            const changeElement = document.getElementById('change-amount');
            if (received >= total && received > 0) {
                changeElement.classList.remove('hidden');
                changeElement.querySelector('span').textContent = change.toFixed(2) + ' DHS';
                changeElement.querySelector('span').className = change >= 0 ? 'font-semibold text-green-600' : 'font-semibold text-red-600';
            } else {
                changeElement.classList.add('hidden');
            }
        });

        // Process sale
        document.getElementById('process-sale').addEventListener('click', function() {
            const paymentMethod = document.getElementById('payment-method').value;
            const total = parseFloat(document.getElementById('cart-total').textContent.replace(' DHS', ''));
            const received = parseFloat(document.getElementById('amount-received').value) || 0;

            if (paymentMethod === 'cash' && received < total) {
                alert('Insufficient payment amount!');
                return;
            }

            completeSale(paymentMethod, total, received);
        });

        function completeSale(paymentMethod, total, received) {
            const change = paymentMethod === 'cash' ? received - total : 0;
            const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);

            // Prepare sale data
            const saleData = {
                cart: cart,
                payment_method: paymentMethod,
                amount_received: paymentMethod === 'cash' ? received : null,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Show loading state
            const processSaleBtn = document.getElementById('process-sale');
            const originalText = processSaleBtn.textContent;
            processSaleBtn.textContent = 'Processing...';
            processSaleBtn.disabled = true;

            // Send sale to backend
            fetch('{{ route("employee.sales.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(saleData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success modal with actual data from backend
                        const modal = document.getElementById('success-modal');
                        const summary = document.getElementById('sale-summary');

                        summary.innerHTML = `
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Sale ID:</span>
                                <span class="font-mono text-sm">#${data.sale_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Items:</span>
                                <span>${itemCount}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total:</span>
                                <span class="font-semibold">${data.total.toFixed(2)} DHS</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Payment:</span>
                                <span>${paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1)}</span>
                            </div>
                            ${paymentMethod === 'cash' ? `
                            <div class="flex justify-between">
                                <span>Received:</span>
                                <span>${received.toFixed(2)} DHS</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Change:</span>
                                <span class="font-semibold text-green-600">${data.change.toFixed(2)} DHS</span>
                            </div>
                            ` : ''}
                        </div>
                    `;

                        modal.classList.remove('hidden');

                        // Clear cart and reset UI
                        clearCart();
                        closeCartSidebar();
                    } else {
                        alert('Error processing sale: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error processing sale. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    processSaleBtn.textContent = originalText;
                    processSaleBtn.disabled = false;
                });
        }

        // Modal close
        document.getElementById('close-success-modal').addEventListener('click', function() {
            document.getElementById('success-modal').classList.add('hidden');
        });

        // Cart sidebar toggle
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        const cartToggle = document.getElementById('cart-toggle');
        const closeCart = document.getElementById('close-cart');

        function openCart() {
            cartSidebar.classList.remove('translate-x-full');
            cartOverlay.classList.remove('hidden');
        }

        function closeCartSidebar() {
            cartSidebar.classList.add('translate-x-full');
            cartOverlay.classList.add('hidden');
            hidePaymentSection();
        }

        cartToggle.addEventListener('click', openCart);
        closeCart.addEventListener('click', closeCartSidebar);
        cartOverlay.addEventListener('click', closeCartSidebar);

        // Initialize display
        updateCartDisplay();
    </script>
</x-app-layout>