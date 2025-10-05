<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str; 

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Product::query();

        // 🔍 Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 🏫 Department
        if ($department = $request->input('department')) {
            $query->where('department', $department);
        }

        // 💰 Price range
        if ($range = $request->input('price_range')) {
            [$min, $max] = explode('-', $range);
            $query->whereBetween('price', [(float) $min, (float) $max]);
        }

        // 🔤 Sorting
        if ($sort = $request->input('sort')) {
            if ($sort === 'name_asc') $query->orderBy('product_name', 'asc');
            if ($sort === 'name_desc') $query->orderBy('product_name', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(10);

        return view('products.index', compact('products'));
    }


    public function create()
    {
        // Show form to create new product
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_id' => 'nullable|string|max:50|unique:products,product_id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'available_sizes' => 'nullable|array', // expecting array input like ['S', 'M', 'L']
            'status' => 'required|in:Active,Inactive,Low,Out',
            'is_featured' => 'nullable|boolean',
            'is_limited' => 'nullable|boolean',
            'available_online' => 'nullable|boolean',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert sizes array to CSV if provided
        if (isset($validated['available_sizes'])) {
            $validated['available_sizes'] = implode(',', $validated['available_sizes']);
        }

        // Handle image upload if provided
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $path = $file->store('uploads/products', 'public');
            $validated['image_path'] = $path;
        }

        // Default booleans
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_limited'] = $request->has('is_limited');
        $validated['available_online'] = $request->has('available_online') ? true : false;

        // 🔹 Auto-generate Product ID
        $lastProduct = Product::latest('id')->first();
        $nextNumber = $lastProduct ? $lastProduct->id + 1 : 1;
        $validated['product_id'] = 'PRD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Auto-generate SKU
        $skuPrefix = strtoupper(substr($request->product_name, 0, 3)); // first 3 letters of product name
        $random = strtoupper(Str::random(4)); // random 4 characters
        $validated['sku'] = "SKU-{$skuPrefix}-{$random}";

        // Create the product
        Product::create($validated);

        // Redirect back to product list
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }


    public function show($id)
    {
        // Show a specific product
    }

    public function edit($id)
    {
        // Show form to edit a product
    }

    public function update(Request $request, $id)
    {
        // Update product
    }

    public function destroy($id)
    {
        // Delete product
    }
}
