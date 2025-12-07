@extends('layouts.sidebar')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Product: {{ $product->product_name }}</h1>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
            ← Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Action: Point to the update route, passing the product ID --}}
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        {{-- IMPORTANT: Use the @method directive to override POST to PUT/PATCH --}}
        @method('PUT') 

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Product Image</h2>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center text-gray-500">
                    
                    <img id="preview-image" 
                         src="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}" 
                         alt="Preview" 
                         class="{{ $product->image_path ? '' : 'hidden' }} w-40 h-40 object-cover rounded mb-3">

                    <span id="no-image-text" class="{{ $product->image_path ? 'hidden' : '' }} mb-2">
                        {{ $product->image_path ? 'Current Image' : 'No image uploaded' }}
                    </span>

                    <label class="cursor-pointer bg-blue-50 border border-blue-300 hover:bg-blue-100 text-blue-600 font-medium px-4 py-2 rounded-lg transition">
                        Upload New Image
                        <input 
                            type="file" 
                            name="image_path" 
                            accept=".png,.jpg,.jpeg,.svg" 
                            class="hidden" 
                            onchange="previewFile(event)">
                    </label>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Basic Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Product Name *</label>
                        {{-- Set existing value: $product->product_name --}}
                        <input type="text" name="product_name" required
                            value="{{ old('product_name', $product->product_name) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Product ID</label>
                        <input type="text" name="product_id" value="{{ $product->id }}" disabled
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">SKU *</label>
                        {{-- Set existing value: $product->sku --}}
                        <input type="text" name="sku" required disabled
                            value="{{ old('sku', $product->sku) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Description *</label>
                        {{-- Set existing value: $product->description --}}
                        <textarea name="description" rows="3" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Pricing & Inventory</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Price *</label>
                    {{-- Set existing value: $product->price --}}
                    <input type="number" step="0.01" name="price" required
                        value="{{ old('price', $product->price) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Stock Quantity *</label>
                    {{-- Set existing value: $product->stock_quantity --}}
                    <input type="number" name="stock_quantity" required
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Category *</label>
                    <select name="category" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="" disabled selected hidden> Select </option>
                        @foreach(config('constants.product_categories') as $key => $proCategory)
                            <option value="{{ $key }}" {{ (old('department', $product->category) == $key) ? 'selected' : '' }}>
                                {{ $proCategory }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Department *</label>
                    <select name="department" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="" disabled selected hidden> Select College Department </option>
                        @foreach(config('constants.college_departments') as $key => $department)
                            <option value="{{ $key }}" {{ (old('department', $product->department) == $key) ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Product Attributes</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">
                        Color
                        <span class="text-gray-500 text-xs italic">(add comma as separator)</span>
                    </label>
                    <input type="text" name="color" placeholder="Blue, Red, Black"
                        value="{{ old('color', $product->color) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>

                @php
                    // Convert array to comma string safely
                    $sizesString = is_array($product->available_sizes)
                        ? implode(', ', $product->available_sizes)
                        : $product->available_sizes;
                @endphp

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">
                        Available Sizes 
                        <span class="text-gray-500 text-xs italic">(add comma as separator)</span>
                    </label>

                    <input type="text" name="available_sizes" placeholder="XS, S, M, L, XL"
                        value="{{ old('available_sizes', $sizesString) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Status *</label>
                    <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                        {{-- Select option logic --}}
                        <option value="Active" {{ (old('status', $product->status) == 'Active') ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ (old('status', $product->status) == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Additional Settings</h2>
            <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_featured"
                        class="text-blue-600 focus:ring-blue-500"
                        {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <span class="text-gray-700">Featured Product</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_limited"
                        class="text-blue-600 focus:ring-blue-500"
                        {{ old('is_limited', $product->is_limited) ? 'checked' : '' }}>
                    <span class="text-gray-700">Limited Edition</span>
                </label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="available_online"
                        class="text-blue-600 focus:ring-blue-500"
                        {{ old('available_online', $product->available_online) ? 'checked' : '' }}>
                    <span class="text-gray-700">Available for Online Orders</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                Update Product
            </button>
        </div>
    </form>
</div>

{{-- Keep your existing JavaScript function for image preview --}}
<script>
    function previewFile(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        const noImageText = document.getElementById('no-image-text');

        // ... (your existing previewFile logic goes here)
        if (file) {
             const reader = new FileReader();
             reader.onload = function(e) {
                 preview.src = e.target.result;
                 preview.classList.remove('hidden');
                 noImageText.classList.add('hidden');
             };
             reader.readAsDataURL(file);
         } else {
             // Logic to show existing image if file input is cleared but product has one
             if (preview.src && preview.src.includes('{{ asset('storage') }}')) {
                 preview.classList.remove('hidden');
                 noImageText.classList.add('hidden');
             } else {
                 preview.src = '';
                 preview.classList.add('hidden');
                 noImageText.classList.remove('hidden');
             }
         }
    }
</script>
@endsection