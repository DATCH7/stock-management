<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">New Stock Request</h1>
                        <p class="mt-2 text-gray-600">Request products from inventory for sales or operations</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Request Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Request Details</h3>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('employee.requests.store') }}" id="requestForm">
                                @csrf

                                <!-- Product Selection -->
                                <div class="mb-6">
                                    <label for="product_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Request Type <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="request_type" value="existing" id="existing_product" class="mr-3" checked>
                                            <div>
                                                <div class="font-medium text-gray-900">Existing Product</div>
                                                <div class="text-sm text-gray-600">Request from current inventory</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="request_type" value="new" id="new_product" class="mr-3">
                                            <div>
                                                <div class="font-medium text-gray-900">New Product</div>
                                                <div class="text-sm text-gray-600">Request a product not in inventory</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Existing Product Selection -->
                                <div id="existing_product_section" class="mb-6">
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Select Product <span class="text-red-500">*</span>
                                    </label>
                                    <select name="product_id" id="product_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('product_id') border-red-500 @enderror">
                                        <option value="">Choose a product...</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->product_id }}"
                                            data-stock="{{ $product->quantity_in_stock }}"
                                            data-price="{{ $product->price }}"
                                            data-category="{{ $product->category }}"
                                            {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                            (Stock: {{ $product->quantity_in_stock }}{{ $product->quantity_in_stock == 0 ? ' - OUT OF STOCK' : '' }})
                                            - {{ $product->price }} DHS
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Product Details -->
                                <div id="new_product_section" class="mb-6 hidden">
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-blue-800">You're requesting a new product that doesn't exist in our inventory yet. Please provide detailed information.</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="new_product_name" class="block text-sm font-medium text-gray-700 mb-2">
                                                Product Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="new_product_name" id="new_product_name"
                                                value="{{ old('new_product_name') }}"
                                                placeholder="Enter the product name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_product_name') border-red-500 @enderror">
                                            @error('new_product_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="new_product_category" class="block text-sm font-medium text-gray-700 mb-2">
                                                Category <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="new_product_category" id="new_product_category"
                                                value="{{ old('new_product_category') }}"
                                                placeholder="e.g., Electronics, Clothing, Food"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_product_category') border-red-500 @enderror">
                                            @error('new_product_category')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="new_product_brand" class="block text-sm font-medium text-gray-700 mb-2">
                                                Brand/Manufacturer
                                            </label>
                                            <input type="text" name="new_product_brand" id="new_product_brand"
                                                value="{{ old('new_product_brand') }}"
                                                placeholder="Brand or manufacturer name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label for="estimated_price" class="block text-sm font-medium text-gray-700 mb-2">
                                                Estimated Price (DHS)
                                            </label>
                                            <input type="number" name="estimated_price" id="estimated_price"
                                                value="{{ old('estimated_price') }}"
                                                placeholder="0.00" step="0.01" min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label for="new_product_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Product Description <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="new_product_description" id="new_product_description" rows="3"
                                            placeholder="Detailed description of the product, specifications, features, etc."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_product_description') border-red-500 @enderror">{{ old('new_product_description') }}</textarea>
                                        @error('new_product_description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="supplier_info" class="block text-sm font-medium text-gray-700 mb-2">
                                            Supplier Information
                                        </label>
                                        <textarea name="supplier_info" id="supplier_info" rows="2"
                                            placeholder="Where can this product be purchased? Include supplier names, websites, or contact information if known."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('supplier_info') }}</textarea>
                                    </div>
                                </div>

                                <!-- Product Info Display -->
                                <div id="productInfo" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 id="selectedProductName" class="font-semibold text-gray-900"></h4>
                                            <div class="text-sm text-gray-600">
                                                <span>Available Stock: <strong id="availableStock"></strong></span>
                                                <span class="mx-2">•</span>
                                                <span>Price: <strong id="productPrice"></strong> DHS</span>
                                                <span class="mx-2">•</span>
                                                <span>Category: <strong id="productCategory"></strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="mb-6">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity Requested <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input type="number" name="quantity" id="quantity" min="1" required
                                            value="{{ old('quantity') }}"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror"
                                            placeholder="Enter requested quantity">
                                        <div class="text-sm text-gray-600">
                                            <span>Available: <strong id="availableQuantity">-</strong></span>
                                        </div>
                                    </div>
                                    @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <span class="text-sm font-medium text-blue-800">Stock Request Info</span>
                                                <p class="text-xs text-blue-700">You can request any quantity needed for restocking, even if it exceeds current stock levels. The stock manager will review and approve based on business needs.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Priority -->
                                <div class="mb-6">
                                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                        Request Priority <span class="text-red-500">*</span>
                                    </label>
                                    <select name="priority" id="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror">
                                        <option value="">Select priority level...</option>
                                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low - Can wait</option>
                                        <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal - Standard request</option>
                                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High - Needed soon</option>
                                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent - Immediate need</option>
                                    </select>
                                    @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Reason -->
                                <div class="mb-6">
                                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        Reason for Request <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="reason" id="reason" rows="4" required
                                        placeholder="Please explain why you need these items (e.g., for customer orders, low stock alert, etc.)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                                    @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quick Reason Buttons -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quick Reasons</label>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" onclick="setReason('Customer order - need immediate stock')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                            Customer Order
                                        </button>
                                        <button type="button" onclick="setReason('Low stock alert - restocking for sales')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                            Low Stock
                                        </button>
                                        <button type="button" onclick="setReason('Promotional campaign - increased demand expected')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                            Promotion
                                        </button>
                                        <button type="button" onclick="setReason('Display restocking - front store needs replenishment')" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                            Display Restock
                                        </button>
                                    </div>
                                </div>

                                <!-- Assign to Stock Manager -->
                                <div class="mb-6">
                                    <label for="approver_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Assign to Stock Manager <span class="text-red-500">*</span>
                                    </label>
                                    <select name="approver_id" id="approver_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('approver_id') border-red-500 @enderror">
                                        <option value="">Select a stock manager...</option>
                                        @foreach($stockManagers as $manager)
                                        <option value="{{ $manager->id }}" {{ old('approver_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->full_name }}
                                            @if($manager->username)
                                            ({{ $manager->username }})
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('approver_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Choose which stock manager should review and approve your request.</p>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('employee.requests.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                        Cancel
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Submit Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Low Stock Alerts -->
                    @php
                    $lowStockProducts = $products->where('quantity_in_stock', '<', 10)->where('quantity_in_stock', '>', 0);
                        @endphp
                        @if($lowStockProducts->count() > 0)
                        <div class="bg-white rounded-xl border border-gray-200">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900">Low Stock Alert</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-4">These products are running low and may need restocking:</p>
                                <div class="space-y-3">
                                    @foreach($lowStockProducts->take(5) as $product)
                                    <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-600">Only {{ $product->quantity_in_stock }} left</p>
                                        </div>
                                        <button type="button" onclick="selectProduct({{ $product->product_id }})" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Request
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Request Tips -->
                        <div class="bg-white rounded-xl border border-gray-200">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Request Tips</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <span class="text-xs font-semibold text-blue-600">1</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Be Specific</p>
                                            <p class="text-xs text-gray-600">Provide clear reasons for your request to speed up approval.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <span class="text-xs font-semibold text-blue-600">2</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Check Stock</p>
                                            <p class="text-xs text-gray-600">Ensure requested quantity doesn't exceed available stock.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <span class="text-xs font-semibold text-blue-600">3</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Plan Ahead</p>
                                            <p class="text-xs text-gray-600">Submit requests early to avoid stock shortages.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Request type toggle
        const existingProductRadio = document.getElementById('existing_product');
        const newProductRadio = document.getElementById('new_product');
        const existingProductSection = document.getElementById('existing_product_section');
        const newProductSection = document.getElementById('new_product_section');
        const productIdSelect = document.getElementById('product_id');

        function toggleRequestType() {
            if (existingProductRadio.checked) {
                existingProductSection.classList.remove('hidden');
                newProductSection.classList.add('hidden');
                productIdSelect.setAttribute('required', 'required');

                // Remove required from new product fields
                document.getElementById('new_product_name').removeAttribute('required');
                document.getElementById('new_product_category').removeAttribute('required');
                document.getElementById('new_product_description').removeAttribute('required');
            } else {
                existingProductSection.classList.add('hidden');
                newProductSection.classList.remove('hidden');
                productIdSelect.removeAttribute('required');

                // Add required to new product fields
                document.getElementById('new_product_name').setAttribute('required', 'required');
                document.getElementById('new_product_category').setAttribute('required', 'required');
                document.getElementById('new_product_description').setAttribute('required', 'required');

                // Clear existing product selection
                productIdSelect.value = '';
                document.getElementById('productInfo').classList.add('hidden');
            }
        }

        existingProductRadio.addEventListener('change', toggleRequestType);
        newProductRadio.addEventListener('change', toggleRequestType);

        // Product selection handler
        document.getElementById('product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const productInfo = document.getElementById('productInfo');

            if (selectedOption.value) {
                const stock = selectedOption.dataset.stock;
                const price = selectedOption.dataset.price;
                const category = selectedOption.dataset.category;

                document.getElementById('selectedProductName').textContent = selectedOption.text.split(' (Stock:')[0];
                document.getElementById('availableStock').textContent = stock;
                document.getElementById('productPrice').textContent = price;
                document.getElementById('productCategory').textContent = category;
                document.getElementById('availableQuantity').textContent = stock;

                productInfo.classList.remove('hidden');
            } else {
                productInfo.classList.add('hidden');
                document.getElementById('availableQuantity').textContent = '-';
            }
        });

        // Remove quantity validation restrictions - employees can request any amount for restocking

        // Quick reason setter
        function setReason(reason) {
            document.getElementById('reason').value = reason;
        }

        // Product selector from low stock alerts
        function selectProduct(productId) {
            // Switch to existing product mode
            existingProductRadio.checked = true;
            toggleRequestType();

            document.getElementById('product_id').value = productId;
            document.getElementById('product_id').dispatchEvent(new Event('change'));

            // Scroll to form
            document.getElementById('requestForm').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Initialize the form state
        toggleRequestType();
    </script>
</x-app-layout>