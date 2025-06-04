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
                            <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
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
                                        <span class="total-price text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
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

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
        <div class="flex flex-col h-full">
            <!-- Cart Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Current Sale</h3>
                    <button id="close-cart" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
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
                    <p class="text-gray-500">No items in cart</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="border-t border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                    <span id="cart-total" class="text-xl font-bold text-green-600">$0.00</span>
                </div>
                <button id="process-sale" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                    Process Sale
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Toggle Button -->
    <button id="cart-toggle" class="fixed bottom-6 right-6 w-14 h-14 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 transition-colors z-40">
        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
        </svg>
        <span id="cart-count" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center hidden">0</span>
    </button>

    <!-- Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    <script>
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

        // Show search filters if there are active filters (check URL parameters)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasActiveFilters = urlParams.has('search') || urlParams.has('category') || urlParams.has('stock_filter');

            if (hasActiveFilters) {
                const searchFilters = document.getElementById('search-filters');
                const chevron = document.getElementById('search-chevron');
                searchFilters.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            }
        });

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
            button.addEventListener('click', function() {
                const form = this.closest('.sale-form');
                const productId = form.dataset.productId;
                const price = parseFloat(form.dataset.productPrice);
                const quantity = parseInt(form.querySelector('.quantity-input').value);
                const productName = form.closest('.bg-white').querySelector('h3').textContent;

                addToCart(productId, productName, price, quantity);
            });
        });

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
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const emptyCart = document.getElementById('empty-cart');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-count');
            const processSaleBtn = document.getElementById('process-sale');

            if (cart.length === 0) {
                cartItems.innerHTML = '';
                emptyCart.style.display = 'block';
                cartCount.style.display = 'none';
                processSaleBtn.disabled = true;
                cartTotal.textContent = '$0.00';
                return;
            }

            emptyCart.style.display = 'none';
            cartCount.style.display = 'flex';
            cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            processSaleBtn.disabled = false;

            cartItems.innerHTML = cart.map(item => `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.productName}</h4>
                        <p class="text-sm text-gray-500">${item.quantity} × $${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900">$${(item.price * item.quantity).toFixed(2)}</span>
                        <button onclick="removeFromCart('${item.productId}')" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotal.textContent = '$' + total.toFixed(2);
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.productId !== productId);
            updateCartDisplay();
        }

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
        }

        cartToggle.addEventListener('click', openCart);
        closeCart.addEventListener('click', closeCartSidebar);
        cartOverlay.addEventListener('click', closeCartSidebar);

        // Process sale
        document.getElementById('process-sale').addEventListener('click', function() {
            if (cart.length === 0) return;

            // Here you would typically send the cart data to your backend
            alert('Sale processed successfully!\nTotal: ' + document.getElementById('cart-total').textContent);
            cart = [];
            updateCartDisplay();
            closeCartSidebar();
        });
    </script>
</x-app-layout>