<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex bg-gray-100 min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between">
            <div>
                <div class="p-4 text-center border-b border-gray-700">
                    <h1 class="text-xl font-bold">ADMIN PANEL</h1>
                    <p class="text-gray-400 text-sm">Management System</p>
                </div>
                <nav class="mt-6 space-y-1 px-4">
                    <a href="{{ route('dashboard.index') }}" 
                    class="flex items-center px-6 py-3 rounded-lg transition duration-200 
                            {{ request()->routeIs('dashboard') 
                                    ? 'bg-blue-600 text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        🏠 <span class="ml-3 text-sm font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('products.index') }}" 
                    class="flex items-center px-6 py-3 rounded-lg transition duration-200 
                            {{ request()->routeIs('products.*') 
                                    ? 'bg-blue-600 text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        📦 <span class="ml-3 text-sm font-medium">Manage Products</span>
                    </a>

                    <a href="{{ route('departments.index') }}" 
                    class="flex items-center px-6 py-3 rounded-lg transition duration-200 
                            {{ request()->routeIs('departments.*') 
                                    ? 'bg-blue-600 text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        🏢 <span class="ml-3 text-sm font-medium">Manage Departments</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                    class="flex items-center px-6 py-3 rounded-lg transition duration-200 
                            {{ request()->routeIs('users.*') 
                                    ? 'bg-blue-600 text-white' 
                                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        👥 <span class="ml-3 text-sm font-medium">Manage Users</span>
                    </a>
                </nav>
            </div>

            <div class="p-4">
                <a href="/shop"
                class="mb-4 block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg shadow transition duration-200">
                    🛒 Go to Shop
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-white flex items-center justify-center">
                        🚪 <span class="ml-2">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </body>
</html>
