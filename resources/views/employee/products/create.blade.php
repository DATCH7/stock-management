<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Add New Product</h1>
                        <p class="mt-2 text-gray-600">Add a new product to the inventory system.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Product Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Fill in the details for the new product.</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('employee.products.store') }}" class="space-y-6">
                        @csrf

                        <!-- Basic Information Section -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Product Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                        required>
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        id="category"
                                        name="category"
                                        value="{{ old('category') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors @error('category') border-red-500 @enderror"
                                        required>
                                    @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">e.g., Electronics, Clothing, Food, etc.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Description</h4>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Product Description
                                </label>
                                <textarea id="description"
                                    name="description"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors @error('description') border-red-500 @enderror"
                                    placeholder="Enter a detailed description of the product...">{{ old('description') }}</textarea>
                                @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing and Inventory Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Pricing & Inventory</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Price (DHS) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">DHS</span>
                                        </div>
                                        <input type="number"
                                            id="price"
                                            name="price"
                                            value="{{ old('price') }}"
                                            step="0.01"
                                            min="0"
                                            class="w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors @error('price') border-red-500 @enderror"
                                            required>
                                    </div>
                                    @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Initial Stock Quantity -->
                                <div>
                                    <label for="quantity_in_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                        Initial Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                        id="quantity_in_stock"
                                        name="quantity_in_stock"
                                        value="{{ old('quantity_in_stock') }}"
                                        min="0"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-colors @error('quantity_in_stock') border-red-500 @enderror"
                                        required>
                                    @error('quantity_in_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Number of units available in stock</p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Preview</h4>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="max-w-sm mx-auto bg-white rounded-xl border border-gray-200 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 id="preview-name" class="text-lg font-semibold text-gray-900 mb-1">Product Name</h3>
                                                <p id="preview-category" class="text-sm text-gray-500">Category</p>
                                            </div>
                                            <div class="ml-4">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    In Stock
                                                </span>
                                            </div>
                                        </div>

                                        <p id="preview-description" class="text-sm text-gray-600 mb-4">Product description will appear here...</p>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Price:</span>
                                                <span id="preview-price" class="text-lg font-bold text-gray-900">0.00 DHS</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Available:</span>
                                                <span id="preview-stock" class="text-sm font-medium text-gray-900">0 units</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center space-x-4">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Product
                                </button>
                                <a href="{{ route('employee.products.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live preview functionality
        function updatePreview() {
            const name = document.getElementById('name').value || 'Product Name';
            const category = document.getElementById('category').value || 'Category';
            const description = document.getElementById('description').value || 'Product description will appear here...';
            const price = parseFloat(document.getElementById('price').value) || 0;
            const stock = parseInt(document.getElementById('quantity_in_stock').value) || 0;

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-category').textContent = category;
            document.getElementById('preview-description').textContent = description;
            document.getElementById('preview-price').textContent = price.toFixed(2) + ' DHS';
            document.getElementById('preview-stock').textContent = stock + ' units';
        }

        // Add event listeners for live preview
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('category').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);
        document.getElementById('price').addEventListener('input', updatePreview);
        document.getElementById('quantity_in_stock').addEventListener('input', updatePreview);

        // Initialize preview
        updatePreview();
    </script>
</x-app-layout>