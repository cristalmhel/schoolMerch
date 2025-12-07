@extends('layouts.sidebar')

@section('content')
<div class="container mx-auto p-4">

    <h2 class="text-2xl font-bold mb-6">Edit Order #{{ $order->id }}</h2>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =======================
         ORDER INFORMATION
       ======================= --}}
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-700">
            Order Information
        </div>
        <div class="px-6 py-4 space-y-2 text-gray-700">
            <p><span class="font-semibold">Order ID:</span> #{{ $order->id }}</p>
            <p><span class="font-semibold">Order Number:</span> #{{ $order->order_number }}</p>
            <p><span class="font-semibold">User:</span> {{ $order->user->name }} ({{ $order->user->email }})</p>
            <p><span class="font-semibold">Created At:</span> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><span class="font-semibold">Current Status:</span>
                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- =======================
     ORDER ITEMS TABLE
   ======================= --}}
    <div class="bg-white shadow rounded-lg mb-6 overflow-x-auto">
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-700">
            Order Items
        </div>
        <table class="min-w-full text-left divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Product</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Department</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Qty</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Unit Price</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $total = 0; @endphp
                @foreach($order->items as $item)
                @php $subtotal = $item->quantity * $item->unit_price; $total += $subtotal; @endphp
                <tr>
                    <td class="px-6 py-3 text-gray-700">{{ $item->product_name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $item->product->department }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-gray-700">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-6 py-3 text-gray-700">₱{{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach

                {{-- Shipping cost row --}}
                <tr class="bg-gray-50">
                    <td colspan="4" class="px-6 py-3 font-semibold text-gray-700 text-right">Shipping Cost</td>
                    <td class="px-6 py-3 text-gray-700">₱{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>

                {{-- Grand total row --}}
                <tr class="bg-gray-100">
                    <td colspan="4" class="px-6 py-3 font-semibold text-gray-700 text-right">Grand Total</td>
                    <td class="px-6 py-3 font-bold text-gray-800">
                        ₱{{ number_format($total + $order->shipping_cost, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    {{-- =======================
         STATUS UPDATE FORM
       ======================= --}}
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-700">
            Update Order Status
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="block text-gray-700 font-medium mb-2">Order Status</label>
                    <select name="status" id="status" class="block w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200" required>
                        <option value="pending"    {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped"    {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered"  {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled"  {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded"   {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                        Save Changes
                    </button>
                    <a href="{{ route('orders.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg shadow transition">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
