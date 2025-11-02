<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the total number of products
        $productCount = Product::count();

        // 2. Get the total number of users
        $userCount = User::count();

        // 3. You can also get other metrics (e.g., Active Users)
        // $activeUserCount = User::where('status', 'active')->count();

        // 4. Return the view, passing the counts
        return view('dashboard.index', compact('productCount', 'userCount'));
    }
}
