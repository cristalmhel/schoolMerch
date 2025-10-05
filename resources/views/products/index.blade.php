@extends('layouts.sidebar')

@section('content')
<x-alert type="success" />
<x-alert type="error" />
<div class="p-2">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manage Products</h1>
            <p class="text-gray-500">Add, edit, and remove merchandise from your inventory</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition duration-200">
            + Add New Product
        </a>
    </div>

    <hr class="my-4">

    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">

        <!-- ✅ Filter Bar -->
        <div class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-lg shadow mb-6">
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, ID, or description..." 
                class="w-96 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            >
            
            <select name="department" class="w-48 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">All Departments</option>
                <option value="BSIT" {{ request('department') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                <option value="BSED" {{ request('department') == 'BSED' ? 'selected' : '' }}>BSED</option>
                <option value="BSA"  {{ request('department') == 'BSA' ? 'selected' : '' }}>BSA</option>
                <option value="BSBA" {{ request('department') == 'BSBA' ? 'selected' : '' }}>BSBA</option>
            </select>

            <select name="price_range" class="w-40 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">₱0 - ₱1000+</option>
                <option value="0-500" {{ request('price_range') == '0-500' ? 'selected' : '' }}>₱0 - ₱500</option>
                <option value="500-1000" {{ request('price_range') == '500-1000' ? 'selected' : '' }}>₱500 - ₱1000</option>
            </select>

            <select name="sort" class="w-40 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">Sort By</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
            </select>

            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium px-12 py-2 rounded-lg shadow transition duration-200">
                Filter
            </button>
           
        </div>

        <!-- ✅ Products Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3"><input type="checkbox" id="select-all"></th>
                        <th class="p-3">Image</th>
                        <th class="p-3">Product Name</th>
                        <th class="p-3">Description</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Department</th>
                        <th class="p-3">Stock</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y>
                    @forelse ($products as $product)
                        <tr class="text-sm">
                            <td class="p-3">
                                <input type="checkbox" name="selected_products[]" value="{{ $product->id }}">
                            </td>
                            <td class="p-3">
                                <img
                                    src="{{ asset('storage/' . $product->image_path) }}"  
                                    alt="Product"
                                    class="rounded object-cover"
                                    width="45" height="45"
                                >
                            </td>
                            <td class="p-3 font-semibold">{{ $product->product_name }}</td>
                            <td class="p-3 text-gray-600 overflow-hidden text-ellipsis whitespace-nowrap" style="max-width: 200px;">
                                {{ $product->description }}
                            </td>
                            <td class="p-3 font-medium">₱{{ number_format($product->price, 2) }}</td>
                            <td class="p-3">{{ $product->department ?? '-' }}</td>
                            <td class="p-3">{{ $product->stock_quantity ?? 0 }}</td>
                            <td class="p-3">
                               <span class="{{ $product->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-medium px-3 py-1 rounded-full">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="p-1 text-center space-x-2">
                                <a href="#" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded text-sm">View</a>
                                <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-sm">Edit</a>
                                <a href="#" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $products->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </form>

    <!-- JS for select all -->
    <script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name="selected_products[]"]').forEach(cb => cb.checked = e.target.checked);
    });
    </script>

</div>
@endsection
