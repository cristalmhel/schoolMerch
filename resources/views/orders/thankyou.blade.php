<x-app-layout>
    <div class="bg-gray-100 font-inter min-h-screen flex items-center justify-center">

        <div class="max-w-xl mx-auto p-8 bg-white rounded-xl shadow-2xl border-t-8 border-green-500">
            <div class="text-center">
                <!-- Checkmark Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h1 class="mt-4 text-3xl font-extrabold text-gray-900">Order Placed Successfully!</h1>
                
                <p class="mt-4 text-lg text-gray-600">
                    Your order (Ref: **#{{ $order_id }}**) has been confirmed and is being processed. 
                    A detailed receipt has been sent to your email.
                </p>
                
                <p class="mt-6 text-sm text-gray-500">
                    Estimated delivery is 3-5 business days.
                </p>

                <!-- Back to Shop Button -->
                <div class="mt-8">
                    <a href="{{ url('/shop') }}" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>