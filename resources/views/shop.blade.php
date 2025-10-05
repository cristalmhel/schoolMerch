<x-app-layout>
    
    <header class="w-full border-b bg-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 py-3 space-y-3 md:space-y-0 md:space-x-6">

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
                <x-text-input id="search_product" placeholder="Search for products..." class="w-full" type="text" name="search_product" :value="old('search_product')" />
                <button 
                    class="px-5 py-2 bg-blue-400 hover:bg-blue-500 text-white font-medium rounded-full transition-all duration-200 ease-in-out"
                >
                    Search
                </button>
            </div>


            <!-- Right: Department Dropdown -->
            <div class="flex items-center justify-center md:justify-end space-x-2">
                <span class="text-gray-800 font-medium text-sm md:text-base">Shop by Department</span>
                <select 
                    class="border border-gray-300 rounded-md px-3 py-2 min-w-[8rem] md:min-w-[10rem] text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm md:text-base"
                >
                    <option>CCS</option>
                    <option>COA</option>
                    <option>COE</option>
                    <option>CAS</option>
                </select>
            </div>
        </div>
    </header>
    <div class="bg-[#243447] py-3 px-4 overflow-x-auto">
    <div class="flex gap-4 min-w-max">
        <span class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition">
            T-Shirts
        </span>
        <span class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition">
            Hoodies
        </span>
        <span class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition">
            Uniform
        </span>
        <span class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition">
            Lanyard
        </span>
        <span class="bg-[#8BAAB8] text-white text-[11px] font-medium px-3 py-1.5 rounded-full cursor-pointer whitespace-nowrap hover:bg-[#9BB9C7] transition">
            Drinkwear
        </span>
    </div>
</div>



    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
