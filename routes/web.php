<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index')->middleware(['auth', 'verified']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard.index')
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Manage Products
Route::resource('products', ProductController::class)->middleware(['auth']);

// Manage Orders
Route::resource('orders', OrderController::class)->middleware(['auth']);

// Manage Users
Route::resource('users', UserController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
