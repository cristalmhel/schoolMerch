<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $search = $request->input('search');
        $category = $request->input('category');
        $department = $request->input('department');
        $viewAll = $request->boolean('view_all');

        // Base query
        $query = Product::query()
        ->where('status', 'Active')
        ->where('stock_quantity', '>', 0);

        // Apply filters
        if ($search) {
            $query->where('product_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($department) {
            $query->where('department', $department);
        }

        // Featured or All
        if (!$viewAll) {
            $query->where('is_featured', true);
        }

        // Get products
        $products = $query->latest()->paginate(12);

        // Get filter data
        $categories = config('constants.product_categories');
        $departments = config('constants.college_departments');

        return view('shop.index', compact('products', 'categories', 'departments', 'viewAll', 'search', 'category', 'department'));
    }
}
