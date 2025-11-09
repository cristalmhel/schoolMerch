@extends('layouts.sidebar')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow">
        <h1 class="text-3xl font-bold text-gray-800">Manage Products</h1>
        <p class="text-gray-600 mt-2">Welcome to your product management page.</p>

        <div class="mt-6">
            <a href="#" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                ➕ Add New Product
            </a>
        </div>
    </div>
@endsection
