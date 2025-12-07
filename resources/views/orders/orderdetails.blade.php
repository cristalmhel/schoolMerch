<x-app-layout>
<div class="container mx-auto p-4">

    <h2 class="text-2xl font-bold mb-6">Order Details - #{{ $order->order_number }}</h2>

    {{-- Order Information --}}
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-700">
            Order Information
        </div>
        <div class="px-6 py-4 space-y-2 text-gray-700">
            <p><span class="font-semibold">Order ID:</span> #{{ $order->order_number }}</p>
            <p><span class="font-semibold">User:</span> {{ $order->user->name }} ({{ $order->user->email }})</p>
            <p><span class="font-semibold">Order Date:</span> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><span class="font-semibold">Status:</span>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-purple-100 text-purple-800',
                    ];
                    $badge = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="px-2 py-1 rounded-full text-sm font-medium {{ $badge }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white shadow rounded-lg mb-6 overflow-x-auto">
        <div class="px-6 py-4 border-b border-gray-200 font-semibold text-gray-700">
            Order Items
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Product</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Department</th>
                    <th class="px-6 py-3 text-sm font-medium text-gray-700">Quantity</th>
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

                {{-- Shipping Cost --}}
                <tr class="bg-gray-50">
                    <td colspan="4" class="px-6 py-3 font-semibold text-gray-700 text-right">Shipping Cost</td>
                    <td class="px-6 py-3 text-gray-700">₱{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>

                {{-- Grand Total --}}
                <tr class="bg-gray-100">
                    <td colspan="4" class="px-6 py-3 font-semibold text-gray-700 text-right">Grand Total</td>
                    <td class="px-6 py-3 font-bold text-gray-800">
                        ₱{{ number_format($total + $order->shipping_cost, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Back Button --}}
    <div class="mt-4">
        <a href="{{ route('my-orders') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg shadow transition">
            Back to Orders
        </a>
    </div>

</div>
</x-app-layout>
