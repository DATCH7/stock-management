<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">Product Details & History</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Product
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Product Information -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Main Product Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                                <p class="text-lg text-gray-900">{{ $product->category }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Price</label>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($product->price, 2) }} DHS</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Current Stock</label>
                                <div class="flex items-center space-x-2">
                                    <p class="text-lg font-semibold text-gray-900">{{ $product->quantity_in_stock }} units</p>
                                    @if($product->quantity_in_stock <= 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                        </span>
                                        @elseif($product->quantity_in_stock <= 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low Stock
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                In Stock
                                            </span>
                                            @endif
                                </div>
                            </div>
                        </div>

                        @if($product->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                            <p class="text-gray-900 leading-relaxed">{{ $product->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <!-- Stock Value -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Total Stock Value</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($product->quantity_in_stock * $product->price, 2) }} DHS</p>
                    </div>

                    <!-- Request Stats -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-4">Request Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Requests</span>
                                <span class="text-sm font-medium text-gray-900">{{ $product->requests->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $product->requests->where('request_status', 'pending')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Approved</span>
                                <span class="text-sm font-medium text-green-600">{{ $product->requests->where('request_status', 'approved')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Rejected</span>
                                <span class="text-sm font-medium text-red-600">{{ $product->requests->where('request_status', 'rejected')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Product
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="w-full" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Requests -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Requests</h3>
                    </div>
                    <div class="p-6">
                        @if($product->requests->count() > 0)
                        <div class="space-y-4">
                            @foreach($product->requests->sortByDesc('created_at')->take(5) as $request)
                            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-medium text-gray-600">{{ substr($request->user->first_name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $request->user->full_name }}</span>
                                        requested {{ $request->quantity }} units
                                    </p>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $request->request_status === 'pending' ? 'bg-amber-50 text-amber-800' : '' }}
                                                {{ $request->request_status === 'approved' ? 'bg-green-50 text-green-800' : '' }}
                                                {{ $request->request_status === 'rejected' ? 'bg-red-50 text-red-800' : '' }}">
                                            {{ ucfirst($request->request_status) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">No requests for this product yet</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Stock Movements -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Stock Movements</h3>
                    </div>
                    <div class="p-6">
                        @php
                        $stockMovements = collect();

                        // Add stock entries
                        foreach($product->stockEntries as $entry) {
                        $stockMovements->push([
                        'type' => 'entry',
                        'quantity' => $entry->quantity,
                        'date' => $entry->entry_date,
                        'reason' => $entry->reason ?? 'Stock Entry'
                        ]);
                        }

                        // Add stock exits
                        foreach($product->stockExits as $exit) {
                        $stockMovements->push([
                        'type' => 'exit',
                        'quantity' => $exit->quantity,
                        'date' => $exit->exit_date,
                        'reason' => $exit->reason ?? 'Stock Exit'
                        ]);
                        }

                        $stockMovements = $stockMovements->sortByDesc('date')->take(5);
                        @endphp

                        @if($stockMovements->count() > 0)
                        <div class="space-y-4">
                            @foreach($stockMovements as $movement)
                            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $movement['type'] === 'entry' ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($movement['type'] === 'entry')
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium {{ $movement['type'] === 'entry' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movement['type'] === 'entry' ? '+' : '-' }}{{ $movement['quantity'] }}
                                        </span>
                                        units - {{ $movement['reason'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($movement['date'])->format('M j, Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">No stock movements recorded</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>