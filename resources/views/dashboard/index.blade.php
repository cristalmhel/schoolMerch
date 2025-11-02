@extends('layouts.sidebar')

@section('content')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-500">Manage your merchandise and users</p>
    </div>
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-full flex items-center">
        <span class="h-3 w-3 bg-green-600 rounded-full mr-2"></span> System Online
    </div>
</div>

<hr class="my-6">

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-gray-600 font-semibold">Total Products</h2>
        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $productCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Active items</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-gray-600 font-semibold">Total Users</h2>
        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $userCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Registered users</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-gray-600 font-semibold">Departments</h2>
        <p class="text-4xl font-bold text-gray-900 mt-2">12</p>
        <p class="text-sm text-gray-500 mt-1">Active categories</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 text-center">
        <h2 class="text-gray-600 font-semibold">Revenue</h2>
        <p class="text-4xl font-bold text-gray-900 mt-2">₱45K</p>
        <p class="text-sm text-gray-500 mt-1">This month</p>
    </div>
</div>
@endsection
