<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

         // 1. Get product count
        if ($user->role === 'super_admin') {
            $productCount = Product::count(); // all products
        } else {
            $productCount = Product::where('department', $user->department)->count(); // user's department only
        }

        // 2. Get total users **only if super_admin**
        $userCount = 0;
        if ($user->role === 'super_admin') {
            $userCount = User::count();
        }

        // 3. Other metrics for super_admin only
        // Example: Active users
        // $activeUserCount = 0;
        // if ($user->role === 'super_admin') {
        //     $activeUserCount = User::where('status', 'active')->count();
        // }

        // 4. Return view with compacted data
        return view('dashboard.index', compact('productCount', 'userCount'));
    }

}
