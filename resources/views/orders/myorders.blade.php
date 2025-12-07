<x-app-layout>
<div class="container mx-auto p-4">

    <h2 class="text-2xl font-bold mb-6">My Orders</h2>

    @if($orders->count() > 0)
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Order ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        @php
                            $total = $order->items->sum(fn($item) => $item->quantity * $item->unit_price) + $order->shipping_cost;
                        @endphp
                        <tr>
                            <td class="px-6 py-3 text-gray-700">{{ $order->order_number }}</td>
                            <td class="px-6 py-3 text-gray-700">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-3">
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
                            </td>
                            <td class="px-6 py-3 text-gray-700">₱{{ number_format($total, 2) }}</td>
                            <td class="px-6 py-3">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm shadow transition">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <p class="text-gray-500">You have no orders yet.</p>
    @endif

</div>
</x-app-layout>
