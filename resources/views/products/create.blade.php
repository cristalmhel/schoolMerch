@extends('layouts.sidebar')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Add Product</h1>
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

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf

        <!-- Top Section: Image + Basic Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Image -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Product Image</h2>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center text-gray-500">
                    <!-- Image Preview -->
                    <img id="preview-image" src="" alt="Preview" class="hidden w-40 h-40 object-cover rounded mb-3">

                    <span id="no-image-text" class="mb-2">No image uploaded</span>

                    <!-- Upload Button -->
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

            <!-- Basic Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Basic Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Product Name *</label>
                        <input type="text" name="product_name" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Product ID</label>
                        <input type="text" name="product_id" placeholder="Auto-generated"
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2" disabled>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">SKU *</label>
                        <input type="text" name="sku" placeholder="SKU-XXXX(Auto-generated)" readonly
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Description *</label>
                        <textarea name="description" rows="3" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Pricing & Inventory</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Price *</label>
                    <input type="number" step="0.01" name="price" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Category *</label>
                    <input type="text" name="category" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Department *</label>
                    <select name="department" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">Select</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSED">BSED</option>
                        <option value="BSBA">BSBA</option>
                        <option value="BSA">BSA</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Attributes -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Product Attributes</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Color</label>
                    <div class="flex items-center space-x-3">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="color" value="Blue" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">Blue</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="color" value="Red" class="text-red-600 focus:ring-red-500">
                            <span class="text-gray-700">Red</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Available Sizes</label>
                    <input type="text" name="sizes" placeholder="XS, S, M, L, XL"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Status *</label>
                    <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Additional Settings -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Additional Settings</h2>
            <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="featured" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Featured Product</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="limited" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Limited Edition</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="online" checked class="text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Available for Online Orders</span>
                </label>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function previewFile(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        const noImageText = document.getElementById('no-image-text');

        if (file) {
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type. Please upload PNG, JPG, JPEG, or SVG.');
                event.target.value = ''; // Clear invalid file
                preview.src = '';
                preview.classList.add('hidden');
                noImageText.classList.remove('hidden');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                noImageText.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            noImageText.classList.remove('hidden');
        }
    }
</script>
@endsection
