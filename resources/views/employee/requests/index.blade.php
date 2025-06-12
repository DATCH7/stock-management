<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">My Requests</h1>
                        <p class="mt-2 text-gray-600">Track and manage your stock requests</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('employee.requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Request
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filter Section -->
            <div class="bg-white rounded-xl border border-gray-200 mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('employee.requests.index') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-64">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Requests</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-64">
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex-1 min-w-64">
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Requests List -->
            @if($requests->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Your Requests ({{ $requests->total() }})
                    </h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
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
                                            <span>Quantity: <strong>{{ $request->quantity }}</strong></span>
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
                                            <span>Requested: {{ $request->request_date->format('M d, Y') }}</span>
                                            @if($request->approval_date)
                                            <span>•</span>
                                            <span>{{ ucfirst($request->request_status) }}: {{ $request->approval_date->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                        @if($request->reason)
                                        <p class="mt-2 text-sm text-gray-700">
                                            <strong>Reason:</strong> {{ $request->reason }}
                                        </p>
                                        @endif
                                        @if($request->approver && $request->request_status !== 'pending')
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ ucfirst($request->request_status) }} by: {{ $request->approver->full_name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        {{ $request->request_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->request_status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->request_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($request->request_status) }}
                                </span>
                                <a href="{{ route('employee.requests.show', $request) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Requests Found</h3>
                    <p class="text-gray-600 mb-6">You haven't made any stock requests yet.</p>
                    <a href="{{ route('employee.requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Your First Request
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>