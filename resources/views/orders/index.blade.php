@extends('layouts.sidebar')

@section('content')
<x-alert type="success" />
<x-alert type="error" />

<div class="p-2">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manage Orders</h1>
            <p class="text-gray-500">View and manage all customer orders.</p>
        </div>
    </div>

    <hr class="my-4">

    <!-- ===================== -->
    <!-- 🔍 FILTER FORM        -->
    <!-- ===================== -->
    <form method="GET" action="{{ route('orders.index') }}" class="space-y-4">

        <div class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-lg shadow mb-6">

            <!-- Search -->
            <input  
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by Order ID or User..."
                class="w-72 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            >

            <!-- Department Filter (Super Admin Only) -->
            @auth
                @if(auth()->user()->role === 'super_admin')
                    <select name="department" 
                        class="w-48 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                        <option value="">All Departments</option>
                        @foreach(config('constants.college_departments') as $key => $dep)
                            <option value="{{ $key }}" {{ request('department') == $key ? 'selected' : '' }}>
                                {{ $dep }}
                            </option>
                        @endforeach
                    </select>
                @endif
            @endauth

            <!-- Status Filter -->
            <select name="status" 
                class="w-40 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="refunded" {{ request('status')=='refunded' ? 'selected' : '' }}>Refunded</option>
            </select>

            <!-- Sorting -->
            <select name="sort" 
                class="w-40 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">Sort By</option>
                <option value="date_desc" {{ request('sort')=='date_desc' ? 'selected' : '' }}>Latest First</option>
                <option value="date_asc" {{ request('sort')=='date_asc' ? 'selected' : '' }}>Oldest First</option>
            </select>

            <button 
                type="submit"
                class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium px-10 py-2 rounded-lg shadow">
                Filter
            </button>

        </div>

        <!-- ===================== -->
        <!-- 📦 ORDERS TABLE       -->
        <!-- ===================== -->

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3">Order Number</th>
                        <th class="p-3">User</th>
                        <th class="p-3">Department</th>
                        <th class="p-3">Items</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Created At</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($orders as $order)
                    <tr class="text-sm">

                        <td class="p-3 font-semibold">#{{ $order->order_number }}</td>

                        <td class="p-3">
                            {{ $order->user->email ?? 'Unknown User' }}
                        </td>

                        <td class="p-3">
                            {{ $order->user->department ?? '-' }}
                        </td>

                        <td class="p-3">
                            @foreach($order->items as $item)
                                <div class="text-gray-700">{{ $item->product_name }} (x{{ $item->quantity }})</div>
                            @endforeach
                        </td>

                        <td class="p-3 font-medium">₱{{ number_format($order->total_amount, 2) }}</td>

                        <td class="p-3">
                            @php
                                $badge = match($order->status) {
                                    'pending'    => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipped'    => 'bg-indigo-100 text-indigo-700',
                                    'delivered'  => 'bg-green-100 text-green-700',
                                    'cancelled'  => 'bg-red-100 text-red-700',
                                    'refunded'   => 'bg-purple-100 text-purple-700',
                                    default      => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="{{ $badge }} px-3 py-1 text-xs rounded-full capitalize">
                                {{ $order->status }}
                            </span>
                        </td>

                        <td class="p-3">{{ $order->created_at->format('M d, Y h:i A') }}</td>

                        <td class="p-3 text-center space-x-2">
                            <a 
                                href="{{ route('orders.edit', $order->id) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-sm">Edit</a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">No orders found.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

            <div class="p-4">
                {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
            </div>

        </div>

    </form>

</div>

@endsection
