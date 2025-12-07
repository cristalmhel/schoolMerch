<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;

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

        // 🏫 Department filter
        $query->when(auth()->user()->role !== 'super_admin', function ($q) {
            // Non-super-admin users are restricted to their own department
            $q->where('department', auth()->user()->department);
        })
        ->when(auth()->user()->role === 'super_admin' && $request->input('department'), function ($q, $department) {
            // Super-admin can filter by request department
            $q->where('department', $department);
        });

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
         $request->merge([
            'is_featured' => $request->boolean('is_featured'),
            'is_limited' => $request->boolean('is_limited'),
            'available_online' => $request->boolean('available_online'),
        ]);

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
            'available_sizes' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Low,Out',
            'is_featured' => 'nullable|boolean',
            'is_limited' => 'nullable|boolean',
            'available_online' => 'nullable|boolean',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->available_sizes = array_map('trim', explode(',', $request->available_sizes));

        // Handle image upload if provided
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $path = $file->store('uploads/products', 'public');
            $validated['image_path'] = $path;
        }

        // Default booleans
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_limited'] = $request->boolean('is_limited');
        $validated['available_online'] = $request->boolean('available_online');

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
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->merge([
            'is_featured' => $request->boolean('is_featured'),
            'is_limited' => $request->boolean('is_limited'),
            'available_online' => $request->boolean('available_online'),
        ]);
        
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'available_sizes' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Low,Out',
            'is_featured' => 'nullable|boolean',
            'is_limited' => 'nullable|boolean',
            'available_online' => 'nullable|boolean',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image_path')) {
            
            // A. Store the NEW image
            $newPath = $request->file('image_path')->store('uploads/products', 'public');
            $validated['image_path'] = $newPath;

            // B. Delete the OLD image (if one exists)
            if ($product->image_path) {
                // Check if the old file actually exists in storage before attempting deletion
                if (Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }
            }
        }

        // Default booleans
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_limited'] = $request->boolean('is_limited');
        $validated['available_online'] = $request->boolean('available_online');
        $product->available_sizes = array_map('trim', explode(',', $request->available_sizes));

        $product->update($validated);

        return redirect()->route('products.show', $product->id)
                        ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        // 1. Find the product
        $product = Product::findOrFail($id);

        // 2. Delete the associated image file (important cleanup step)
        if ($product->image_path) {
            if (Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
        }
        
        // 3. Delete the product record from the database
        $product->delete();

        // 4. Redirect back to the product list with a success message
        return redirect()->route('products.index')
                        ->with('success', 'Product "' . $product->product_name . '" deleted successfully.');
    }
}
