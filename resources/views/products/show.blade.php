@extends('layouts.sidebar')

@section('content')
    <div class="p-6 max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Product Details: {{ $product->product_name }}</h1>
        <div class="flex space-x-3 mt-4 md:mt-0">
            {{-- DELETE BUTTON: Triggers the modal --}}
            <button 
                type="button" 
                onclick="confirmDelete({{ $product->id }}, '{{ $product->product_name }}')"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow font-medium transition">
                Delete Product
            </button>

            <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow font-medium transition">
                Edit Product
            </a>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium px-4 py-2 border border-blue-600 rounded-lg transition">
                ← Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-8">
            <div class="lg:col-span-1 border-r border-gray-200 lg:pr-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Product Image</h2>
                <div class="border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50">
                    {{-- Check if image_path exists and display it --}}
                    @if ($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}" class="w-64 h-64 object-contain rounded-lg shadow-md">
                    @else
                        <div class="w-64 h-64 flex items-center justify-center text-gray-500 bg-gray-200 rounded-lg border-2 border-dashed border-gray-400">
                            No Image Available
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="border-b pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-3">Basic Information</h2>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <dt class="text-gray-500 font-medium">Product Name:</dt>
                        <dd class="text-gray-900 font-semibold">{{ $product->product_name }}</dd>

                        <dt class="text-gray-500 font-medium">Product ID:</dt>
                        <dd class="text-gray-900">{{ $product->id }}</dd> {{-- Assuming the primary key is 'id' --}}

                        <dt class="text-gray-500 font-medium">SKU:</dt>
                        <dd class="text-gray-900">{{ $product->sku }}</dd>

                        <dt class="col-span-2 text-gray-500 font-medium">Description:</dt>
                        <dd class="col-span-2 text-gray-700 whitespace-pre-wrap">{{ $product->description }}</dd>
                    </dl>
                </div>
                
                <div class="border-b pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-3">Pricing & Inventory</h2>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <dt class="text-gray-500 font-medium">Price:</dt>
                        <dd class="text-green-600 font-bold text-lg">₱{{ number_format($product->price, 2) }}</dd>

                        <dt class="text-gray-500 font-medium">Stock Quantity:</dt>
                        <dd class="text-gray-900 font-semibold">{{ $product->stock_quantity }}</dd>

                       <dt class="text-gray-500 font-medium">Category:</dt>
                        <dd class="text-gray-900">
                            {{ config('constants.product_categories.' . $product->category) ?? $product->category }}
                        </dd>

                        <dt class="text-gray-500 font-medium">Department:</dt>
                        <dd class="text-gray-900">
                            {{ config('constants.college_departments.' . $product->department) ?? $product->department }}
                        </dd>
                    </dl>
                </div>
                
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-3">Attributes & Status</h2>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <dt class="text-gray-500 font-medium">Color:</dt>
                        <dd class="text-gray-900">{{ $product->color ?? 'N/A' }}</dd>

                        <dt class="text-gray-500 font-medium">Available Sizes:</dt>
                        <dd class="text-gray-900">
                            {{ is_array($product->available_sizes) ? implode(', ', $product->available_sizes) : ($product->available_sizes ?? 'N/A') }}
                        </dd>

                        <dt class="text-gray-500 font-medium">Status:</dt>
                        <dd class="font-semibold @if($product->status == 'Active') text-green-600 @else text-red-600 @endif">{{ $product->status }}</dd>

                        <dt class="text-gray-500 font-medium">Featured Product:</dt>
                        <dd class="text-gray-900">
                            @if($product->is_featured)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">No</span>
                            @endif
                        </dd>

                        <dt class="text-gray-500 font-medium">Limited Edition:</dt>
                        <dd class="text-gray-900">
                            @if($product->is_limited)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">No</span>
                            @endif
                        </dd>
                        
                        <dt class="text-gray-500 font-medium">Online Orders:</dt>
                        <dd class="text-gray-900">
                            @if($product->available_online)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Available</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Unavailable</span>
                            @endif
                        </dd>
                    </dl>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Product</h3>
            <div class="mt-2 px-7 py-3">
                <p id="modal-text" class="text-sm text-gray-500">
                    Are you sure you want to delete the product? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                {{-- Form for DELETE request --}}
                <form id="deleteForm" method="POST" action="" class="inline w-full"> 
                    @csrf
                    @method('DELETE')
                    
                    {{-- Use Flexbox to align and space the buttons --}}
                    <div class="flex justify-between space-x-4"> 
                        <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const modalText = document.getElementById('modal-text');

    function confirmDelete(productId, productName) {
        // 1. Set the action URL for the form to the correct route
        // Assuming your base route is /products/{id}
        const url = `/products/${productId}`; 
        deleteForm.action = url;

        // 2. Update the modal text
        modalText.innerHTML = `Are you sure you want to delete the product **${productName}**? This action cannot be undone.`;

        // 3. Show the modal
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection