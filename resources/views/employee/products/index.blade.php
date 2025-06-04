<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Products</h1>
                        <p class="mt-2 text-gray-600">Browse and manage products in the inventory.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Product
                        </a>
                        <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Search Toggle Button -->
            <div class="mb-6">
                <button id="search-toggle" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Search & Filter</span>
                    <svg id="search-chevron" class="w-4 h-4 text-gray-600 ml-2 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Search and Filters (Hidden by default) -->
            <div id="search-filters" class="bg-white rounded-xl border border-gray-200 mb-8 hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('employee.products.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                <input type="text"
                                    id="search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search products..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select id="category"
                                    name="category"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stock Filter -->
                            <div>
                                <label for="stock_filter" class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                                <select id="stock_filter"
                                    name="stock_filter"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                                    <option value="">All Products</option>
                                    <option value="in_stock" {{ request('stock_filter') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="low_stock" {{ request('stock_filter') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out_of_stock" {{ request('stock_filter') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        @if(request()->hasAny(['search', 'category', 'stock_filter']))
                        <div class="flex justify-end">
                            <a href="{{ route('employee.products.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'category', 'stock_filter']))
            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-600">Active filters:</span>

                    @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Search: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('category'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Category: {{ request('category') }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('stock_filter'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Stock: {{ ucfirst(str_replace('_', ' ', request('stock_filter'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['stock_filter' => null]) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                @forelse($products as $product)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 max-w-sm mx-auto w-full">
                    <!-- Product Image Placeholder -->
                    <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-t-lg">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $product->category }}</p>
                        </div>
                    </div>

                    <!-- Product Content -->
                    <div class="p-6">
                        <!-- Product Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 h-14 flex items-center">
                            {{ $product->name }}
                        </h3>

                        <!-- Stock Status -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $product->quantity_in_stock > 10 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $product->quantity_in_stock > 0 && $product->quantity_in_stock <= 10 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $product->quantity_in_stock == 0 ? 'bg-red-100 text-red-800' : '' }}">
                                @if($product->quantity_in_stock > 10)
                                ✓ In Stock ({{ $product->quantity_in_stock }})
                                @elseif($product->quantity_in_stock > 0)
                                ⚠ Low Stock ({{ $product->quantity_in_stock }})
                                @else
                                ✗ Out of Stock
                                @endif
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($product->price, 2) }} DHS</span>
                        </div>

                        <!-- Add to Cart Section -->
                        @if($product->quantity_in_stock > 0)
                        <div class="space-y-4">
                            <form class="sale-form" data-product-id="{{ $product->product_id }}" data-product-price="{{ $product->price }}">
                                <!-- Quantity Selector -->
                                <div class="flex items-center justify-between mb-4">
                                    <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" class="qty-decrease px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-colors">-</button>
                                        <input type="number"
                                            name="quantity"
                                            min="1"
                                            max="{{ $product->quantity_in_stock }}"
                                            value="1"
                                            class="quantity-input w-16 px-3 py-2 text-center border-0 focus:ring-0 text-sm bg-white">
                                        <button type="button" class="qty-increase px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition-colors">+</button>
                                    </div>
                                </div>

                                <!-- Total Price Display -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total:</span>
                                        <span class="total-price text-xl font-bold text-green-600">{{ number_format($product->price, 2) }} DHS</span>
                                    </div>
                                </div>

                                <!-- Add to Cart Button -->
                                <button type="button"
                                    class="add-to-sale w-full bg-gray-900 text-white py-3 px-4 rounded-lg font-semibold text-sm uppercase tracking-wide hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    ADD TO CART
                                </button>
                            </form>
                        </div>
                        @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-lg font-semibold text-sm uppercase tracking-wide cursor-not-allowed">
                            OUT OF STOCK
                        </button>
                        @endif

                        <!-- View Details Link -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('employee.products.show', $product) }}"
                                class="inline-block text-sm text-gray-600 hover:text-gray-800 font-medium border border-gray-600 hover:border-gray-800 px-6 py-2 rounded-lg transition-colors duration-200 uppercase tracking-wide">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center max-w-md mx-auto">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your search or filter criteria.</p>
                        <a href="{{ route('employee.products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add First Product
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="bg-white rounded-lg shadow-lg border-l-4 p-4 max-w-sm">
            <div class="flex items-center">
                <div id="notification-icon" class="flex-shrink-0 mr-3">
                    <!-- Icon will be inserted here -->
                </div>
                <div>
                    <p id="notification-message" class="text-sm font-medium text-gray-900"></p>
                </div>
                <button id="close-notification" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" style="position: fixed !important; top: 0 !important; right: 0 !important; width: 384px !important; height: 100vh !important; background: white !important; box-shadow: -10px 0 25px rgba(0,0,0,0.3) !important; transform: translateX(100%) !important; transition: transform 0.3s ease-in-out !important; z-index: 9998 !important; overflow: hidden;">
        <div style="display: flex; flex-direction: column; height: 100%;">
            <!-- Cart Header -->
            <div style="padding: 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Current Sale</h3>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button id="new-customer" style="padding: 4px 12px; background: #3b82f6; color: white; font-size: 14px; border-radius: 6px; border: none; cursor: pointer;">
                            New Customer
                        </button>
                        <button id="clear-cart" style="color: #ef4444; font-size: 14px; background: none; border: none; cursor: pointer;">Clear All</button>
                        <button id="close-cart" style="color: #9ca3af; background: none; border: none; cursor: pointer; padding: 4px;">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Running Total Display -->
                <div style="background: white; border-radius: 8px; padding: 12px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 14px; color: #6b7280;">Items: <span id="header-item-count" style="font-weight: 600;">0</span></span>
                        <span style="font-size: 18px; font-weight: bold; color: #111827;" id="header-total">0.00 DHS</span>
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div style="flex: 1; overflow-y: auto; padding: 24px;">
                <div id="cart-items" style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Cart items will be populated here -->
                </div>
                <div id="empty-cart" style="text-align: center; padding: 32px 0;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15"></path>
                    </svg>
                    <p style="color: #6b7280; margin: 0;">No items in cart</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div style="border-top: 1px solid #e5e7eb; padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px;">
                        <span style="font-size: 18px; font-weight: 600; color: #111827;">Total:</span>
                        <span id="cart-total" style="font-size: 20px; font-weight: bold; color: #111827;">0.00 DHS</span>
                    </div>
                </div>
                <button id="checkout-btn" style="width: 100%; padding: 12px 16px; background: #111827; color: white; font-weight: 500; border-radius: 8px; border: none; cursor: pointer; transition: background-color 0.2s;" disabled>
                    Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Toggle Button - Enhanced -->
    <button id="cart-toggle" style="position: fixed !important; bottom: 24px !important; right: 24px !important; z-index: 9999 !important; display: block !important; width: 70px; height: 70px; background: #111827; color: white; border-radius: 50%; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: none; cursor: pointer; transition: all 0.3s ease;">
        <div class="relative">
            <svg style="width: 32px; height: 32px; margin: 0 auto; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
            <span id="cart-count" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; background: #dc2626; color: white; font-size: 14px; font-weight: bold; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">0</span>
        </div>
        <div style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); font-size: 10px; font-weight: bold; color: white;">
            CART
        </div>
    </button>

    <!-- Overlay -->
    <div id="cart-overlay" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; background: rgba(0,0,0,0.5) !important; z-index: 9997 !important; display: none !important;"></div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Complete Sale</h3>
                    <button id="close-checkout" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between font-semibold text-lg pt-2">
                            <span>Total:</span>
                            <span id="checkout-total">0.00 DHS</span>
                        </div>
                    </div>
                </div>

                <form id="checkout-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select id="payment-method" name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile">Mobile Payment</option>
                        </select>
                    </div>

                    <div id="cash-payment" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount Received</label>
                        <input type="number" id="amount-received" name="amount_received" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0.00">
                        <div id="change-display" class="mt-2 text-sm text-gray-600 hidden">
                            Change: <span id="change-amount" class="font-semibold text-green-600">0.00 DHS</span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" id="cancel-checkout" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="process-payment" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                            Process Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Cart state
        let cartData = {
            items: [],
            total: 0,
            total_items: 0,
            item_count: 0
        };

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing cart system...');
            console.log('CSRF Token:', csrfToken);

            // Check if cart button exists
            const cartButton = document.getElementById('cart-toggle');
            console.log('Cart button found:', cartButton);
            if (cartButton) {
                console.log('Cart button styles:', window.getComputedStyle(cartButton));
                // Make sure it's visible
                cartButton.style.display = 'block';
                cartButton.style.position = 'fixed';
                cartButton.style.bottom = '24px';
                cartButton.style.right = '24px';
                cartButton.style.zIndex = '9999';

                // Add click event
                cartButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Cart button clicked!');
                    openCart();
                });
                console.log('Cart toggle event listener attached');
            }

            // Setup other event listeners
            const closeCart = document.getElementById('close-cart');
            const cartOverlay = document.getElementById('cart-overlay');
            const clearCartBtn = document.getElementById('clear-cart');
            const newCustomerBtn = document.getElementById('new-customer');

            if (closeCart) {
                closeCart.addEventListener('click', closeCartSidebar);
            }

            if (cartOverlay) {
                cartOverlay.addEventListener('click', closeCartSidebar);
            }

            if (clearCartBtn) {
                clearCartBtn.addEventListener('click', async function() {
                    if (confirm('Are you sure you want to clear the cart?')) {
                        await clearCartFunction();
                    }
                });
            }

            if (newCustomerBtn) {
                newCustomerBtn.addEventListener('click', newCustomer);
            }

            // Initialize cart display with empty state
            updateCartDisplay();

            // Load cart from server
            loadCart();

            // Show search filters if there are active filters
            const urlParams = new URLSearchParams(window.location.search);
            const hasActiveFilters = urlParams.has('search') || urlParams.has('category') || urlParams.has('stock_filter');

            if (hasActiveFilters) {
                const searchFilters = document.getElementById('search-filters');
                const chevron = document.getElementById('search-chevron');
                searchFilters.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            }

            // Setup notification close button
            document.getElementById('close-notification').addEventListener('click', hideNotification);

            console.log('Cart system initialized');
        });

        // Search toggle functionality
        document.getElementById('search-toggle').addEventListener('click', function() {
            const searchFilters = document.getElementById('search-filters');
            const chevron = document.getElementById('search-chevron');

            if (searchFilters.classList.contains('hidden')) {
                searchFilters.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                searchFilters.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });

        // Load cart from server
        async function loadCart() {
            console.log('Loading cart...');
            try {
                const response = await fetch('{{ route("employee.cart.get") }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                console.log('Cart response status:', response.status);
                const data = await response.json();
                console.log('Cart data received:', data);

                if (data.success) {
                    cartData = data.cart;
                    console.log('Cart loaded successfully:', cartData);
                    updateCartDisplay();
                } else {
                    console.error('Failed to load cart:', data);
                }
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        // Update quantity and total price for product forms
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', function() {
                const form = this.closest('.sale-form');
                const price = parseFloat(form.dataset.productPrice);
                const quantity = parseInt(this.value) || 1;
                const total = price * quantity;

                form.querySelector('.total-price').textContent = total.toFixed(2) + ' DHS';
            });
        });

        // Quantity increase/decrease buttons
        document.querySelectorAll('.qty-decrease').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    input.dispatchEvent(new Event('input'));
                }
            });
        });

        document.querySelectorAll('.qty-increase').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const maxValue = parseInt(input.getAttribute('max'));
                const currentValue = parseInt(input.value) || 1;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('input'));
                }
            });
        });

        // Add to cart functionality
        document.querySelectorAll('.add-to-sale').forEach(button => {
            button.addEventListener('click', async function() {
                const form = this.closest('.sale-form');
                const productId = form.dataset.productId;
                const quantity = parseInt(form.querySelector('.quantity-input').value);

                await addToCart(productId, quantity, button);
            });
        });

        async function addToCart(productId, quantity, button) {
            console.log('Adding to cart:', {
                productId,
                quantity
            });

            // Show loading state
            const originalText = button.textContent;
            button.textContent = 'Adding...';
            button.disabled = true;

            try {
                console.log('Making request to add to cart...');
                const response = await fetch('{{ route("employee.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                console.log('Add to cart response status:', response.status);
                const data = await response.json();
                console.log('Add to cart response data:', data);

                if (data.success) {
                    cartData = data.cart;
                    console.log('Cart updated:', cartData);
                    updateCartDisplay();

                    // Show success feedback
                    button.textContent = 'Added!';
                    button.classList.add('bg-green-700');

                    // Reset quantity input
                    const form = button.closest('.sale-form');
                    form.querySelector('.quantity-input').value = 1;
                    form.querySelector('.quantity-input').dispatchEvent(new Event('input'));

                    // Show a brief notification
                    showNotification(`${quantity} item(s) added to cart!`, 'success');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bg-green-700');
                        button.disabled = false;
                    }, 1500);
                } else {
                    console.error('Failed to add to cart:', data);
                    showNotification('Error: ' + data.message, 'error');
                    button.textContent = originalText;
                    button.disabled = false;
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Error adding item to cart', 'error');
                button.textContent = originalText;
                button.disabled = false;
            }
        }

        function updateCartDisplay() {
            console.log('Updating cart display with data:', cartData);

            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-count');
            const checkoutBtn = document.getElementById('checkout-btn');

            // Header elements
            const headerItemCount = document.getElementById('header-item-count');
            const headerTotal = document.getElementById('header-total');

            // Always show cart count (even if 0)
            if (cartCount) {
                cartCount.style.display = 'flex';
                cartCount.textContent = cartData.total_items || 0;
                console.log('Cart count updated to:', cartData.total_items || 0);
            }

            if (cartData.item_count === 0) {
                console.log('Cart is empty, showing empty state');
                cartItems.innerHTML = '';
                emptyCart.style.display = 'block';
                checkoutBtn.disabled = true;
                cartTotal.textContent = '0.00 DHS';
                headerItemCount.textContent = '0';
                headerTotal.textContent = '0.00 DHS';

                // Make cart count gray when empty
                if (cartCount) {
                    cartCount.style.background = '#9ca3af';
                }
                return;
            }

            console.log('Cart has items, updating display');
            emptyCart.style.display = 'none';
            checkoutBtn.disabled = false;

            // Make cart count red when has items
            if (cartCount) {
                cartCount.style.background = '#dc2626';
            }

            // Update header
            headerItemCount.textContent = cartData.total_items;
            headerTotal.textContent = parseFloat(cartData.total).toFixed(2) + ' DHS';

            cartItems.innerHTML = cartData.items.map(item => `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <div class="flex items-center space-x-2 mt-1">
                            <button onclick="updateQuantity('${item.product_id}', ${item.quantity - 1})" class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300 ${item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''}">-</button>
                            <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.product_id}', ${item.quantity + 1})" class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300 ${item.quantity >= item.max_stock ? 'opacity-50 cursor-not-allowed' : ''}">+</button>
                            <span class="text-xs text-gray-500">× ${parseFloat(item.price).toFixed(2)} DHS</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900">${parseFloat(item.total).toFixed(2)} DHS</span>
                        <button onclick="removeFromCart('${item.product_id}')" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');

            cartTotal.textContent = parseFloat(cartData.total).toFixed(2) + ' DHS';

            console.log('Cart display updated successfully');
        }

        async function updateQuantity(productId, newQuantity) {
            if (newQuantity < 0) return;

            try {
                const response = await fetch('{{ route("employee.cart.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();
                if (data.success) {
                    cartData = data.cart;
                    updateCartDisplay();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        async function removeFromCart(productId) {
            try {
                const response = await fetch('{{ route("employee.cart.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });

                const data = await response.json();
                if (data.success) {
                    cartData = data.cart;
                    updateCartDisplay();
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
            }
        }

        // Clear cart
        document.getElementById('clear-cart').addEventListener('click', async function() {
            if (confirm('Are you sure you want to clear the cart?')) {
                await clearCartFunction();
            }
        });

        // New Customer functionality
        async function newCustomer() {
            if (cartData.item_count > 0) {
                if (confirm('Start a new customer? This will clear the current cart.')) {
                    await clearCartFunction();
                    showNotification('Ready for new customer!', 'success');
                }
            } else {
                showNotification('Ready for new customer!', 'success');
            }
        }

        async function clearCartFunction() {
            try {
                const response = await fetch('{{ route("employee.cart.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    cartData = data.cart;
                    updateCartDisplay();
                    closeCartSidebar();
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
            }
        }

        // Cart sidebar toggle
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        const cartToggle = document.getElementById('cart-toggle');
        const closeCart = document.getElementById('close-cart');

        function openCart() {
            console.log('Opening cart sidebar...');
            const cartSidebar = document.getElementById('cart-sidebar');
            const cartOverlay = document.getElementById('cart-overlay');

            cartSidebar.style.transform = 'translateX(0)';
            cartOverlay.style.display = 'block';
        }

        function closeCartSidebar() {
            console.log('Closing cart sidebar...');
            const cartSidebar = document.getElementById('cart-sidebar');
            const cartOverlay = document.getElementById('cart-overlay');

            cartSidebar.style.transform = 'translateX(100%)';
            cartOverlay.style.display = 'none';
        }

        // Checkout functionality
        const checkoutModal = document.getElementById('checkout-modal');
        const checkoutBtn = document.getElementById('checkout-btn');
        const closeCheckout = document.getElementById('close-checkout');
        const cancelCheckout = document.getElementById('cancel-checkout');
        const paymentMethodSelect = document.getElementById('payment-method');
        const cashPaymentDiv = document.getElementById('cash-payment');
        const amountReceivedInput = document.getElementById('amount-received');
        const changeDisplay = document.getElementById('change-display');
        const changeAmount = document.getElementById('change-amount');
        const processPaymentBtn = document.getElementById('process-payment');

        checkoutBtn.addEventListener('click', function() {
            // Update checkout totals
            document.getElementById('checkout-total').textContent = parseFloat(cartData.total).toFixed(2) + ' DHS';

            checkoutModal.classList.remove('hidden');
        });

        closeCheckout.addEventListener('click', () => checkoutModal.classList.add('hidden'));
        cancelCheckout.addEventListener('click', () => checkoutModal.classList.add('hidden'));

        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'cash') {
                cashPaymentDiv.classList.remove('hidden');
                amountReceivedInput.required = true;
            } else {
                cashPaymentDiv.classList.add('hidden');
                changeDisplay.classList.add('hidden');
                amountReceivedInput.required = false;
            }
            updateProcessButton();
        });

        amountReceivedInput.addEventListener('input', function() {
            const received = parseFloat(this.value) || 0;
            const total = parseFloat(cartData.total);

            if (received >= total) {
                const change = received - total;
                changeAmount.textContent = change.toFixed(2) + ' DHS';
                changeDisplay.classList.remove('hidden');
            } else {
                changeDisplay.classList.add('hidden');
            }
            updateProcessButton();
        });

        function updateProcessButton() {
            const paymentMethod = paymentMethodSelect.value;
            const amountReceived = parseFloat(amountReceivedInput.value) || 0;

            if (paymentMethod === 'cash') {
                processPaymentBtn.disabled = amountReceived < parseFloat(cartData.total);
            } else {
                processPaymentBtn.disabled = !paymentMethod;
            }
        }

        // Process payment
        document.getElementById('checkout-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const paymentData = Object.fromEntries(formData);

            processPaymentBtn.textContent = 'Processing...';
            processPaymentBtn.disabled = true;

            try {
                const response = await fetch('{{ route("employee.sales.process-cart") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(paymentData)
                });

                const data = await response.json();

                if (data.success) {
                    // Hide checkout modal
                    checkoutModal.classList.add('hidden');

                    // Show success modal
                    showSuccessModal(data.sale);

                    // Reset cart
                    cartData = {
                        items: [],
                        total: 0,
                        total_items: 0,
                        item_count: 0
                    };
                    updateCartDisplay();
                    closeCartSidebar();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error processing payment:', error);
                alert('Error processing payment');
            }

            processPaymentBtn.textContent = 'Process Sale';
            processPaymentBtn.disabled = false;
        });

        function showSuccessModal(sale) {
            const saleSummary = document.getElementById('sale-summary');
            saleSummary.innerHTML = `
                <div class="space-y-1">
                    <p><strong>Sale ID:</strong> ${sale.sale_id}</p>
                    <p><strong>Total:</strong> ${parseFloat(sale.total_amount).toFixed(2)} DHS</p>
                    <p><strong>Payment:</strong> ${sale.payment_method.charAt(0).toUpperCase() + sale.payment_method.slice(1)}</p>
                    ${parseFloat(sale.change_given) > 0 ? `<p><strong>Change:</strong> ${parseFloat(sale.change_given).toFixed(2)} DHS</p>` : ''}
                    <p><strong>Items:</strong> ${sale.total_items}</p>
                </div>
            `;

            document.getElementById('success-modal').classList.remove('hidden');
        }

        document.getElementById('close-success-modal').addEventListener('click', function() {
            document.getElementById('success-modal').classList.add('hidden');

            // Reset checkout form
            document.getElementById('checkout-form').reset();
            cashPaymentDiv.classList.add('hidden');
            changeDisplay.classList.add('hidden');
            processPaymentBtn.disabled = true;
        });

        // Notification system
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notification-message');
            const notificationIcon = document.getElementById('notification-icon');

            notificationMessage.textContent = message;

            // Set icon and colors based on type
            if (type === 'success') {
                notification.querySelector('.bg-white').className = 'bg-white rounded-lg shadow-lg border-l-4 border-green-500 p-4 max-w-sm';
                notificationIcon.innerHTML = `
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            } else if (type === 'error') {
                notification.querySelector('.bg-white').className = 'bg-white rounded-lg shadow-lg border-l-4 border-red-500 p-4 max-w-sm';
                notificationIcon.innerHTML = `
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                `;
            }

            // Show notification
            notification.classList.remove('translate-x-full');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                hideNotification();
            }, 3000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.add('translate-x-full');
        }
    </script>
</x-app-layout>