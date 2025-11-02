<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/shop', function () {
    return view('shop');
})->middleware(['auth', 'verified'])->name('shop');

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

// Manage Departments
Route::resource('departments', DepartmentController::class)->middleware(['auth']);

// Manage Users
Route::resource('users', UserController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
