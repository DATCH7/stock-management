<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Pending Requests</h1>
                        <p class="mt-2 text-gray-600">Review and manage employee stock requests</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('stock.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $requests->total() }}</span> pending requests
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-green-800">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if($requests->count() > 0)
            <!-- Filter Section -->
            <div class="bg-white rounded-xl border border-gray-200 mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('stock.requests') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-64">
                            <label for="employee" class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                            <select name="employee" id="employee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Employees</option>
                                @foreach($requests->pluck('user')->unique('id') as $user)
                                <option value="{{ $user->id }}" {{ request('employee') == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-64">
                            <label for="product" class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                            <select name="product" id="product" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Products</option>
                                @foreach($requests->pluck('product')->unique('product_id') as $product)
                                <option value="{{ $product->product_id }}" {{ request('product') == $product->product_id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-64">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="priority" {{ request('sort') === 'priority' || !request('sort') ? 'selected' : '' }}>Priority (Urgent First)</option>
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="quantity_high" {{ request('sort') === 'quantity_high' ? 'selected' : '' }}>Highest Quantity</option>
                                <option value="quantity_low" {{ request('sort') === 'quantity_low' ? 'selected' : '' }}>Lowest Quantity</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-xl border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Select All</span>
                            </label>
                            <span id="selectedCount" class="text-sm text-gray-600">0 selected</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="bulkApprove" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Approve Selected
                            </button>
                            <button id="bulkReject" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Reject Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requests List -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Pending Requests ({{ $requests->total() }})
                    </h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <div class="p-6 hover:bg-gray-50 transition-colors request-item" data-request-id="{{ $request->id }}">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 pt-1">
                                <input type="checkbox" class="request-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $request->id }}">
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    @if($request->isNewProductRequest())
                                                    <span class="inline-flex items-center">
                                                        {{ $request->product_name }}
                                                        <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                            NEW PRODUCT
                                                        </span>
                                                    </span>
                                                    @else
                                                    {{ $request->product->name }}
                                                    @endif
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600">
                                                    <span>Requested by: <strong>{{ $request->user->full_name }}</strong></span>
                                                    <span>•</span>
                                                    <span>Quantity: <strong>{{ $request->quantity }}</strong></span>
                                                    <span>•</span>
                                                    @if(!$request->isNewProductRequest())
                                                    <span>Available: <strong>{{ $request->product->quantity_in_stock }}</strong></span>
                                                    @else
                                                    <span class="text-purple-600"><strong>New Product Request</strong></span>
                                                    @endif
                                                    <span>•</span>
                                                    <span>Priority:
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                                {{ $request->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                                                {{ $request->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                                                {{ $request->priority === 'normal' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                {{ $request->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                            {{ ucfirst($request->priority ?? 'normal') }}
                                                        </span>
                                                    </span>
                                                    <span>•</span>
                                                    <span>{{ $request->request_date->format('M d, Y g:i A') }}</span>
                                                </div>
                                                @if($request->reason)
                                                <p class="mt-2 text-sm text-gray-700">
                                                    <strong>Reason:</strong> {{ Str::limit($request->reason, 100) }}
                                                </p>
                                                @endif

                                                <!-- Stock Status Warning -->
                                                @if($request->isNewProductRequest())
                                                <div class="mt-2 p-2 bg-purple-50 border border-purple-200 rounded-lg">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        <span class="text-xs text-purple-800">New product request - This product doesn't exist in inventory yet. Approval will allow adding it to the system.</span>
                                                    </div>
                                                </div>
                                                @elseif($request->quantity > $request->product->quantity_in_stock)
                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                        </svg>
                                                        <span class="text-xs text-red-800">Insufficient stock! Requested {{ $request->quantity }}, available {{ $request->product->quantity_in_stock }}</span>
                                                    </div>
                                                </div>
                                                @elseif($request->product->quantity_in_stock - $request->quantity < 5)
                                                    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                        </svg>
                                                        <span class="text-xs text-yellow-800">Low stock warning! Only {{ $request->product->quantity_in_stock - $request->quantity }} will remain after approval</span>
                                                    </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <div class="text-sm text-gray-600">
                                            @if($request->isNewProductRequest())
                                            New Product
                                            @else
                                            Product Value
                                            @endif
                                        </div>
                                        <div class="font-semibold text-gray-900">
                                            @if($request->isNewProductRequest())
                                            {{ $request->quantity }} units
                                            @else
                                            {{ number_format($request->quantity * $request->product->price, 2) }} DHS
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <form method="POST" action="{{ route('stock.requests.approve', $request) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to approve this {{ $request->isNewProductRequest() ? 'new product' : '' }} request?')">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('stock.requests.reject', $request) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to reject this request?')">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Pending Requests</h3>
                    <p class="text-gray-600 mb-6">All requests have been processed. Great job!</p>
                    <a href="{{ route('stock.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const requestCheckboxes = document.querySelectorAll('.request-checkbox');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkApproveBtn = document.getElementById('bulkApprove');
        const bulkRejectBtn = document.getElementById('bulkReject');

        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
            const count = checkedBoxes.length;

            selectedCountSpan.textContent = `${count} selected`;

            // Enable/disable bulk action buttons
            bulkApproveBtn.disabled = count === 0;
            bulkRejectBtn.disabled = count === 0;

            // Update select all checkbox state
            if (count === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (count === requestCheckboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }

        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            requestCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });

        // Individual checkbox functionality
        requestCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // Bulk approve functionality
        bulkApproveBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
            const requestIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (requestIds.length > 0 && confirm(`Are you sure you want to approve ${requestIds.length} requests?`)) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("stock.requests.bulk-approve") }}';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add request IDs
                requestIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'request_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });

        // Bulk reject functionality
        bulkRejectBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.request-checkbox:checked');
            const requestIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (requestIds.length > 0 && confirm(`Are you sure you want to reject ${requestIds.length} requests?`)) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("stock.requests.bulk-reject") }}';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add request IDs
                requestIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'request_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });

        // Initialize count
        updateSelectedCount();
    </script>
</x-app-layout>