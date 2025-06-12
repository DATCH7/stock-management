<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Request Details</h1>
                        <p class="mt-2 text-gray-600">View your stock request information</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('employee.requests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Requests
                        </a>
                        @if($request->isPending())
                        <a href="{{ route('employee.requests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Request
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Request Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Request Information</h3>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    {{ $request->request_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $request->request_status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $request->request_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($request->request_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Product Information -->
                            <div class="mb-8">
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-xl font-semibold text-gray-900">
                                            @if($request->isNewProductRequest())
                                            <span class="inline-flex items-center">
                                                {{ $request->product_name }}
                                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    NEW PRODUCT REQUEST
                                                </span>
                                            </span>
                                            @else
                                            {{ $request->product->name }}
                                            @endif
                                        </h4>
                                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            @if(!$request->isNewProductRequest())
                                            <div>
                                                <span class="font-medium">Category:</span> {{ $request->product->category }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Price:</span> {{ $request->product->price }} DHS
                                            </div>
                                            <div>
                                                <span class="font-medium">Current Stock:</span> {{ $request->product->quantity_in_stock }}
                                            </div>
                                            @else
                                            <div class="col-span-2">
                                                <span class="font-medium text-purple-700">This is a request for a new product that doesn't exist in inventory yet.</span>
                                            </div>
                                            @endif
                                            <div>
                                                <span class="font-medium">Requested Qty:</span> <strong>{{ $request->quantity }}</strong>
                                            </div>
                                            <div>
                                                <span class="font-medium">Priority:</span>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $request->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $request->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                                    {{ $request->priority === 'normal' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $request->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                    {{ ucfirst($request->priority ?? 'normal') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div class="space-y-6">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Request Reason</h5>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <p class="text-gray-900">{{ $request->reason }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Request Date</h5>
                                        <p class="text-gray-900">{{ $request->request_date->format('F d, Y') }}</p>
                                        <p class="text-sm text-gray-600">{{ $request->request_date->format('g:i A') }}</p>
                                    </div>

                                    @if($request->approval_date)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">
                                            {{ ucfirst($request->request_status) }} Date
                                        </h5>
                                        <p class="text-gray-900">{{ $request->approval_date->format('F d, Y') }}</p>
                                        <p class="text-sm text-gray-600">{{ $request->approval_date->format('g:i A') }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if($request->approver)
                                <div>
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">
                                        {{ ucfirst($request->request_status) }} By
                                    </h5>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $request->approver->full_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $request->approver->role->role_name ?? 'Stock Manager' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div class="mt-8 bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Request Timeline</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <!-- Request Submitted -->
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900">Request submitted</p>
                                                        <p class="text-xs text-gray-500">{{ $request->request_date->format('M d, Y g:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Status Update -->
                                    @if($request->approval_date)
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        {{ $request->request_status === 'approved' ? 'bg-green-500' : 'bg-red-500' }}">
                                                        @if($request->request_status === 'approved')
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        @else
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900">
                                                            Request {{ $request->request_status }} by {{ $request->approver->full_name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">{{ $request->approval_date->format('M d, Y g:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @else
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900">Awaiting approval</p>
                                                        <p class="text-xs text-gray-500">Pending review by stock manager</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
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
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('employee.requests.create') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">New Request</p>
                                        <p class="text-xs text-gray-500">Submit another request</p>
                                    </div>
                                </a>

                                <a href="{{ route('employee.requests.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">All Requests</p>
                                        <p class="text-xs text-gray-500">View request history</p>
                                    </div>
                                </a>

                                <a href="{{ route('employee.dashboard') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Dashboard</p>
                                        <p class="text-xs text-gray-500">Back to main dashboard</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Request Status Info -->
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Status Information</h3>
                        </div>
                        <div class="p-6">
                            @if($request->isPending())
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">Pending Review</p>
                                        <p class="text-xs text-yellow-700">Your request is waiting for stock manager approval.</p>
                                    </div>
                                </div>
                            </div>
                            @elseif($request->isApproved())
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Approved</p>
                                        <p class="text-xs text-green-700">Your request has been approved and stock has been allocated.</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Rejected</p>
                                        <p class="text-xs text-red-700">Your request was not approved. You may submit a new request.</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>