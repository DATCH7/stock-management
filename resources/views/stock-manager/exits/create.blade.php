<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Record Stock Exit</h1>
                        <p class="mt-2 text-gray-600">Record stock removals and update inventory levels.</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Stock Exit Details</h3>
                    <p class="text-sm text-gray-600">Fill in the details for the stock exit.</p>
                </div>

                <form action="{{ route('stock.exits.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Selection -->
                        <div class="md:col-span-2">
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product <span class="text-red-500">*</span>
                            </label>
                            <select id="product_id"
                                name="product_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors @error('product_id') border-red-500 @enderror"
                                required>
                                <option value="">Select a product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->product_id }}"
                                    {{ old('product_id') == $product->product_id ? 'selected' : '' }}
                                    data-current-stock="{{ $product->quantity_in_stock }}">
                                    {{ $product->name }} - {{ $product->category }} (Available: {{ $product->quantity_in_stock }})
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors @error('quantity') border-red-500 @enderror"
                                placeholder="Enter quantity to remove"
                                required>
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Exit Date -->
                        <div>
                            <label for="exit_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Exit Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                id="exit_date"
                                name="exit_date"
                                value="{{ old('exit_date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors @error('exit_date') border-red-500 @enderror"
                                required>
                            @error('exit_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="md:col-span-2">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason <span class="text-red-500">*</span>
                            </label>
                            <select id="reason_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors mb-3">
                                <option value="">Select a reason</option>
                                <option value="Damaged goods">Damaged goods</option>
                                <option value="Expired products">Expired products</option>
                                <option value="Return to supplier">Return to supplier</option>
                                <option value="Internal use">Internal use</option>
                                <option value="Theft/Loss">Theft/Loss</option>
                                <option value="Quality control">Quality control</option>
                                <option value="Other">Other (specify below)</option>
                            </select>
                            <textarea id="reason"
                                name="reason"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors @error('reason') border-red-500 @enderror"
                                placeholder="Specify the reason for stock exit..."
                                required>{{ old('reason') }}</textarea>
                            @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Stock Display -->
                    <div id="current-stock-info" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Stock Information</p>
                                <p class="text-sm text-red-600">
                                    Available stock: <span id="current-stock-amount">0</span> units
                                    <span id="new-stock-calculation" class="ml-2"></span>
                                </p>
                                <p id="stock-warning" class="text-xs text-red-500 mt-1 hidden">⚠️ Warning: Exit quantity exceeds available stock!</p>
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
                            id="submit-btn"
                            class="px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Record Stock Exit
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
            const reasonSelect = document.getElementById('reason_select');
            const reasonTextarea = document.getElementById('reason');
            const currentStockInfo = document.getElementById('current-stock-info');
            const currentStockAmount = document.getElementById('current-stock-amount');
            const newStockCalculation = document.getElementById('new-stock-calculation');
            const stockWarning = document.getElementById('stock-warning');
            const submitBtn = document.getElementById('submit-btn');

            function updateStockInfo() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const quantity = parseInt(quantityInput.value) || 0;

                if (selectedOption.value && selectedOption.dataset.currentStock !== undefined) {
                    const currentStock = parseInt(selectedOption.dataset.currentStock);
                    const newTotal = currentStock - quantity;

                    currentStockAmount.textContent = currentStock;

                    if (quantity > 0) {
                        newStockCalculation.innerHTML = `→ New total: <strong>${newTotal}</strong> units (-${quantity})`;

                        if (quantity > currentStock) {
                            stockWarning.classList.remove('hidden');
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            stockWarning.classList.add('hidden');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    } else {
                        newStockCalculation.textContent = '';
                        stockWarning.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }

                    currentStockInfo.classList.remove('hidden');
                } else {
                    currentStockInfo.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function updateReason() {
                if (reasonSelect.value && reasonSelect.value !== 'Other') {
                    reasonTextarea.value = reasonSelect.value;
                } else if (reasonSelect.value === 'Other') {
                    reasonTextarea.value = '';
                    reasonTextarea.focus();
                }
            }

            productSelect.addEventListener('change', updateStockInfo);
            quantityInput.addEventListener('input', updateStockInfo);
            reasonSelect.addEventListener('change', updateReason);

            // Initialize on page load
            updateStockInfo();
        });
    </script>
</x-app-layout>