<x-app-layout>
    <div class="bg-gray-100 font-inter min-h-screen">

        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-indigo-600">Secure Checkout</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Fulfillment & Contact Information</h2>
                    
                    <form id="checkoutForm">
                        
                        <div class="mb-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-indigo-700 mb-3">How would you like to receive your order?</h3>

                            <div class="flex flex-wrap gap-6">
                                <div class="flex items-center">
                                    <input type="radio" id="order_type_delivery" name="order_type" value="delivery" checked
                                        class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="order_type_delivery" class="ml-3 text-base font-medium text-gray-700">
                                        Delivery <span class="text-sm text-gray-500">(Requires Shipping Address)</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="order_type_pickup" name="order_type" value="pickup"
                                        class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <label for="order_type_pickup" class="ml-3 text-base font-medium text-gray-700">
                                        Pickup <span class="text-sm text-gray-500">(No Shipping Required)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="customer_name" name="customer_name" required 
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="customer_email" name="customer_email" required 
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (Optional)</label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div id="shippingAddressFields" class="border p-6 rounded-xl border-gray-200 mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Shipping Address</h3>

                            <div class="mb-4">
                                <label for="shipping_address_line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                <input type="text" id="shipping_address_line1" name="shipping_address_line1" required 
                                    class="shipping-field w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="mb-4">
                                <label for="shipping_address_line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Apt, Suite, etc.)</label>
                                <input type="text" id="shipping_address_line2" name="shipping_address_line2" 
                                    class="shipping-field w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" id="shipping_city" name="shipping_city" required 
                                        class="shipping-field w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="shipping_zip" class="block text-sm font-medium text-gray-700 mb-1">Zip/Postal Code</label>
                                    <input type="text" id="shipping_zip" name="shipping_zip" required 
                                        class="shipping-field w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 pt-3 border-t mt-6">Payment Method</h3>
                            <select id="payment_method" name="payment_method" required 
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="card">Credit Card (Mock)</option>
                            </select>
                        </div>

                        <button type="submit" id="placeOrderBtn"
                                class="w-full py-3 px-4 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 transition duration-150 ease-in-out disabled:opacity-50">
                            <span id="btn-text">Place Order Now</span>
                        </button>
                        
                        <div id="statusMessage" class="mt-4 p-3 rounded-lg text-sm text-center hidden" role="alert"></div>

                    </form>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 sticky top-20">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-3">Order Summary</h2>

                        @if(isset($product) && $qty > 0)
                            <div id="orderItems">
                                <div class="flex justify-between items-center py-2 border-b border-dashed">
                                    <p class="text-sm text-gray-600">{{ $product->product_name }}
                                         @if(isset($size) || isset($color))
                                            ({{ $size ?? '' }}{{ (isset($size) && isset($color)) ? ', ' : '' }}{{ $color ?? '' }}) 
                                        @endif
                                         + {{ $qty }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-800">₱{{ number_format(($product->price ?? 0) * ($qty ?? 1), 2) }}</p>
                                </div>
                            </div>
                        @else
                            {{-- Fallback if cart is empty or data is missing --}}
                            <div class="text-sm text-gray-500 py-2 border-b border-dashed">No items in order.</div>
                        @endif
                        
                        <div class="space-y-2 mt-4 pt-4 border-t">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span>₱{{ number_format(($product->price ?? 0) * ($qty ?? 1), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700" id="shippingCostRow">
                                <span>Shipping</span>
                                <span id="shippingCost">₱120.00</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-gray-900 mt-4">
                                <span>Order Total</span>
                                <span id="orderTotal">₱{{ number_format(($total ?? 0) + 120, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('checkoutForm');
            const orderTypeRadios = document.querySelectorAll('input[name="order_type"]');
            const shippingAddressFields = document.getElementById('shippingAddressFields');
            const shippingInputFields = shippingAddressFields.querySelectorAll('.shipping-field');
            const shippingCostRow = document.getElementById('shippingCostRow');
            const shippingCostDisplay = document.getElementById('shippingCost');
            const placeOrderBtn = document.getElementById('placeOrderBtn');
            const btnText = document.getElementById('btn-text');
            const statusMessage = document.getElementById('statusMessage');
            // Assuming you have a meta tag for CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ? 
                             document.querySelector('meta[name="csrf-token"]').getAttribute('content') : 
                             '';

            const DELIVERY_SHIPPING_COST = 120.00;
            const PICKUP_SHIPPING_COST = 0.00;
            const MOCK_SUBTOTAL = {{ number_format($total, 2, '.', '') }};

            const PRODUCT_ID = {{ $product->id ?? 'null' }}; 
            const PRODUCT_SIZE = '{{ $size ?? "" }}'; 
            const PRODUCT_COLOR = '{{ $color ?? "" }}';
            const PRODUCT_QTY = {{ $qty ?? 1 }}; // Use 1 as a safe default quantity

            // --- UI/UX Functions ---

            function updateShippingVisibility(orderType) {
                const isDelivery = orderType === 'delivery';

                if (isDelivery) {
                    // Show shipping fields
                    shippingAddressFields.classList.remove('hidden');
                    // Add 'required' attribute back to shipping fields
                    shippingInputFields.forEach(field => field.setAttribute('required', 'required'));
                    shippingCostRow.classList.remove('hidden');
                    shippingCostDisplay.textContent = `₱${DELIVERY_SHIPPING_COST.toFixed(2)}`;
                } else {
                    // Hide shipping fields
                    shippingAddressFields.classList.add('hidden');
                    // Remove 'required' attribute for pickup
                    shippingInputFields.forEach(field => field.removeAttribute('required'));
                    shippingCostRow.classList.add('hidden');
                    shippingCostDisplay.textContent = `₱${PICKUP_SHIPPING_COST.toFixed(2)}`;
                }
                
                // Update total in the summary
                document.getElementById('orderTotal').textContent = `₱${(MOCK_SUBTOTAL + (isDelivery ? DELIVERY_SHIPPING_COST : PICKUP_SHIPPING_COST)).toFixed(2)}`;
            }

            function showStatus(message, type = 'success') {
                statusMessage.textContent = message;
                statusMessage.className = 'mt-4 p-3 rounded-lg text-sm text-center';
                statusMessage.classList.add(type === 'success' ? 'bg-green-100' : 'bg-red-100', 
                                            type === 'success' ? 'text-green-800' : 'text-red-800');
                statusMessage.classList.remove('hidden');
            }

            // --- Event Listeners ---

            // 1. Listen for Order Type changes
            orderTypeRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    updateShippingVisibility(e.target.value);
                });
            });

            // Initialize on load (since 'delivery' is checked by default)
            updateShippingVisibility('delivery');

            // 2. Form Submission Logic
            function getFormData() {
                const orderType = document.querySelector('input[name="order_type"]:checked').value;
                const baseData = {
                    selected_product_id: PRODUCT_ID,
                    product_size: PRODUCT_SIZE,
                    product_color: PRODUCT_COLOR,
                    product_qty: PRODUCT_QTY,

                    customer_name: document.getElementById('customer_name').value,
                    customer_email: document.getElementById('customer_email').value,
                    customer_phone: document.getElementById('customer_phone').value,
                    payment_method: document.getElementById('payment_method').value,
                    order_type: orderType, // Send the chosen type to the server
                    
                    // Mock Totals to pass to the server (Server MUST recalculate)
                    subtotal_mock: MOCK_SUBTOTAL,
                    shipping_cost_mock: orderType === 'delivery' ? DELIVERY_SHIPPING_COST : PICKUP_SHIPPING_COST,
                    order_total_mock: MOCK_SUBTOTAL + (orderType === 'delivery' ? DELIVERY_SHIPPING_COST : PICKUP_SHIPPING_COST)
                };

                if (orderType === 'delivery') {
                    return {
                        ...baseData,
                        // Only include shipping address fields if it's delivery
                        shipping_address_line1: document.getElementById('shipping_address_line1').value,
                        shipping_address_line2: document.getElementById('shipping_address_line2').value,
                        shipping_city: document.getElementById('shipping_city').value,
                        shipping_zip: document.getElementById('shipping_zip').value,
                    };
                }

                return baseData;
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                
                placeOrderBtn.disabled = true;
                btnText.textContent = 'Processing...';
                statusMessage.classList.add('hidden');

                const formData = getFormData();
                
                try {
                    // Make sure you have the correct route defined in your routes/web.php
                    const response = await fetch('{{ route("orders.store") }}', { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken 
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showStatus(result.message || 'Order placed successfully!', 'success');
                        const thankYouUrl = '{{ route("orders.thankyou", ":id") }}'.replace(':id', result.order_id);
                        window.location.href = thankYouUrl;
                    } else if (response.status === 422) {
                        const errors = Object.values(result.errors).flat();
                        showStatus('Validation Error: ' + errors.join(', '), 'error');
                    } else {
                        showStatus(result.message || 'Failed to place order due to a server error.', 'error');
                    }

                } catch (error) {
                    console.error('Network or server error:', error);
                    showStatus('A network error occurred. Please try again.', 'error');
                } finally {
                    placeOrderBtn.disabled = false;
                    btnText.textContent = 'Place Order Now';
                }
            });
        });
    </script>
</x-app-layout>