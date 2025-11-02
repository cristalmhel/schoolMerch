<x-app-layout>
    
<header class="w-full border-b bg-white">
    <form 
        method="GET" 
        action="{{ route('shop.index') }}" 
        class="flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 py-3 space-y-3 md:space-y-0 md:space-x-6"
    >
        <!-- Left: Profile + Welcome -->
        <div class="flex items-center justify-start space-x-3">
            <div class="flex underline items-center justify-center w-10 h-10 rounded-full bg-gray-800 text-white font-semibold uppercase">
                {{ Str::limit(Str::upper(Auth::user()->firstname), 2, '') }}
            </div>
            <span class="text-base md:text-lg font-medium text-gray-800 sm:inline">
                Welcome back, {{ Auth::user()->firstname }}
            </span>
        </div>

        <!-- Middle: Search -->
        <div class="flex items-center w-full max-w-md mx-auto md:max-w-lg gap-4">
            <x-text-input 
                id="search" 
                placeholder="Search for products..." 
                class="w-full" 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
            />
            <button 
                type="submit"
                class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-full transition-all duration-200 ease-in-out"
            >
                Search
            </button>
        </div>

        <!-- Right: Department Dropdown -->
        <div class="flex items-center justify-center md:justify-end space-x-2">
            <span class="text-gray-800 font-medium text-sm md:text-base">Shop by Department</span>
            <select 
                name="department"
                onchange="this.form.submit()" 
                class="border border-gray-300 rounded-md px-3 py-2 min-w-[8rem] md:min-w-[10rem] text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm md:text-base"
            >
                <option value="">All Dept.</option>
                @foreach(config('constants.college_departments') as $key => $department)
                    <option value="{{ $key }}" {{ request('department') == $key ? 'selected' : '' }}>
                        {{ $department }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</header>

<!-- Categories Bar -->
<div class="bg-[#243447] py-3 px-4 overflow-x-auto">
    <div class="flex flex-wrap gap-3">
        @foreach(config('constants.product_categories') as $key => $category)
            <button
                type="submit"
                form="shopFilterForm"
                name="category"
                value="{{ $key }}"
                class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition {{ request('category') == $key ? 'bg-[#9BB9C7]' : '' }}"
                onclick="window.location='{{ route('shop.index', array_merge(request()->except('page'), ['category' => $key])) }}'"
            >
                {{ $category }}
            </button>
        @endforeach
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            {{ $viewAll ? 'All Products' : 'Featured Products' }}
        </h1>
        <a href="{{ route('shop.index', ['view_all' => !$viewAll]) }}" 
            class="text-blue-600 hover:underline text-sm font-semibold">
            {{ $viewAll ? 'View Featured →' : 'View All →' }}
        </a>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @forelse($products as $product)
            <div class="bg-white border rounded-2xl p-4 shadow hover:shadow-lg transition text-center relative">
                
                {{-- 🏷️ Limited Edition Badge --}}
                @if($product->is_limited)
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase shadow">
                        Limited Edition
                    </div>
                @endif

                <div class="w-full h-40 bg-gray-50 flex items-center justify-center rounded-lg mb-3">
                    <img  src="{{ asset('storage/' . $product->image_path) }}"  
                        alt="{{ $product->name }}" 
                        class="max-h-36 object-contain">
                </div>

                <h3 class="font-semibold text-gray-900 text-sm">{{ $product->name }}</h3>
                <p class="text-gray-500 text-xs line-clamp-2">{{ $product->description }}</p>
                <p class="text-green-600 font-semibold text-sm mt-1">${{ number_format($product->price, 2) }}</p>

                <button class="mt-3 w-full bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                    🛒 Add to Cart
                </button>
            </div>
        @empty
            <p class="text-gray-500 col-span-full text-center">No products found.</p>
        @endforelse
    </div>


    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
</x-app-layout>
