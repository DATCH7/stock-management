<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Add Stock Entry</h1>
                        <p class="mt-2 text-gray-600">Record new stock arrivals and update inventory levels.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('stock.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Stock Entry Details</h3>
                    <p class="text-sm text-gray-600">Fill in the details for the new stock entry.</p>
                </div>

                <form action="{{ route('stock.entries.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Selection -->
                        <div class="md:col-span-2">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product <span class="text-red-500">*</span>
                            </label>
                            <select id="product_id"
                                name="product_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('product_id') border-red-500 @enderror"
                                required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->product_id }}"
                                    {{ old('product_id') == $product->product_id ? 'selected' : '' }}
                                    data-current-stock="{{ $product->quantity_in_stock }}">
                                    {{ $product->name }} - {{ $product->category }} (Current: {{ $product->quantity_in_stock }})
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                id="quantity"
                                name="quantity"
                                min="1"
                                value="{{ old('quantity') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('quantity') border-red-500 @enderror"
                                placeholder="Enter quantity to add"
                                required>
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Entry Date -->
                        <div>
                            <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Entry Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                id="entry_date"
                                name="entry_date"
                                value="{{ old('entry_date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('entry_date') border-red-500 @enderror"
                                required>
                            @error('entry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes"
                                name="notes"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('notes') border-red-500 @enderror"
                                placeholder="Add any additional notes about this stock entry...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Stock Display -->
                    <div id="current-stock-info" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Current Stock Information</p>
                                <p class="text-sm text-blue-600">
                                    Current stock: <span id="current-stock-amount">0</span> units
                                    <span id="new-stock-calculation" class="ml-2"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('stock.dashboard') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Add Stock Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const currentStockInfo = document.getElementById('current-stock-info');
            const currentStockAmount = document.getElementById('current-stock-amount');
            const newStockCalculation = document.getElementById('new-stock-calculation');

            function updateStockInfo() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const quantity = parseInt(quantityInput.value) || 0;

                if (selectedOption.value && selectedOption.dataset.currentStock !== undefined) {
                    const currentStock = parseInt(selectedOption.dataset.currentStock);
                    const newTotal = currentStock + quantity;

                    currentStockAmount.textContent = currentStock;

                    if (quantity > 0) {
                        newStockCalculation.innerHTML = `→ New total: <strong>${newTotal}</strong> units (+${quantity})`;
                    } else {
                        newStockCalculation.textContent = '';
                    }

                    currentStockInfo.classList.remove('hidden');
                } else {
                    currentStockInfo.classList.add('hidden');
                }
            }

            productSelect.addEventListener('change', updateStockInfo);
            quantityInput.addEventListener('input', updateStockInfo);

            // Initialize on page load
            updateStockInfo();
        });
    </script>
</x-app-layout>