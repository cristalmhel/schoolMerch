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

                <div 
                    class="w-full h-40 bg-gray-50 flex items-center justify-center rounded-lg mb-3"
                    onclick="openProductModal({{ json_encode($product) }})"
                >
                    <img  src="{{ asset('storage/' . $product->image_path) }}"  
                        alt="{{ $product->product_name }}" 
                        class="max-h-36 object-contain">
                </div>

                <h3 class="font-semibold text-gray-900 text-sm">{{ $product->product_name }}</h3>
                <p class="text-gray-500 text-xs line-clamp-2">{{ $product->description }}</p>
                <p class="text-green-600 font-semibold text-sm mt-1">₱{{ number_format($product->price, 2) }}</p>

               <button 
                    class="mt-3 w-full p-2 bg-orange-600 text-white text-sm font-semibold rounded-lg hover:bg-orange-700 transition"
                    onclick="openProductModal({{ json_encode($product) }})"
                >
                    🛒 Check out
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

<!-- Product Details Modal -->
<div id="productModal" 
     class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">

    <div class="bg-white w-11/12 md:w-1/2 lg:w-1/3 rounded-xl shadow-lg p-6 relative">

        <!-- ✖ Close button -->
        <button onclick="closeProductModal()" 
                class="absolute top-2 right-2 text-gray-500 text-xl hover:text-black">
            &times;
        </button>

        <div class="text-center mb-4">
            <div class="relative mx-auto w-full max-w-xs overflow-hidden rounded-lg">
                <img id="modalImage" 
                    class="mx-auto max-h-40 object-contain transition-transform duration-300 ease-in-out hover:scale-150 cursor-zoom-in" />
            </div>
        </div>

        <div class="text-center">
            <h2 class="text-lg font-bold text-gray-900" id="modalName"></h2>
            <p class="text-sm text-gray-600 mt-2" id="modalDescription"></p>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-4 text-sm text-gray-700">
            <p><strong>SKU:</strong> <span id="modalSKU"></span></p>
            <p><strong>Category:</strong> <span id="modalCategory"></span></p>
            <p><strong>Department:</strong> <span id="modalDepartment"></span></p>
            <p><strong>Color:</strong> <span id="modalColor"></span></p>
            <p><strong>Sizes:</strong> <span id="modalSizes"></span></p>
            <p class="text-center col-span-2 text-green-600 font-bold text-lg mt-1">
                ₱<span id="modalPrice"></span>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between gap-3">

            <button id="modalAddToCartBtn"
                class="w-1/2 px-4 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700">
                🛒 Add to Cart
            </button>

            <button id="modalBuyBtn"
                class="w-1/2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                Buy Now
            </button>
        </div>

        <!-- Fullscreen Image Viewer -->
        <div id="imageFullscreen"
            class="fixed inset-0 bg-black/90 hidden justify-center items-center z-[60]"
            onclick="closeFullscreenImage()">

            <img id="fullscreenImg"
                class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-xl">
        </div>

        <!-- ADD TO CART BOTTOM SHEET -->
        <div id="bottomSheet"
            class="fixed inset-0 bg-black/40 hidden justify-end items-end z-[60]">

            <div id="sheetContent"
                class="bg-white w-full md:w-1/2 lg:w-1/3 p-6 rounded-t-2xl shadow-2xl transform translate-y-full transition-transform duration-300">

                <h2 class="text-lg font-bold text-gray-900 mb-4">Add to Cart</h2>

                <!-- Size -->
                <label class="block text-sm font-semibold text-gray-700">Select Size</label>
                <select id="sheetSize"
                        class="w-full border rounded-lg mt-1 p-2 text-sm">
                </select>

                <!-- Color -->
                <label class="block text-sm font-semibold text-gray-700">Select Color</label>
                <select id="sheetColor"
                        class="w-full border rounded-lg mt-1 p-2 text-sm">
                </select>

                <!-- Quantity -->
                <label class="block text-sm font-semibold text-gray-700 mt-4">Quantity</label>
                <input type="number" id="sheetQty" value="1" min="1"
                    class="w-full border rounded-lg mt-1 p-2 text-sm">

                <!-- Action Buttons -->
                <div id="sheetButtons" class="mt-6 flex justify-between gap-3">
                    <button id="sheetAddCartBtn"
                            class="w-1/2 px-4 py-3 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700">
                        <span id="btn-text-add-to-cart">🛒 Add to Cart</span>
                    </button>

                    <button id="sheetBuyBtn"
                            class="w-1/2 px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">
                        <span id="btn-text-buy-now">💳 Buy Now</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedProductId = null;
    let currentProduct = null;

    const cartCountElement = document.getElementById('cart-item-count');
    const cartCountElementMobile = document.getElementById('cart-item-count-mobile');
    const sheetAddCartBtn = document.getElementById('sheetAddCartBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // --- Initialization ---
    function updateCartBadge(count) {
        // Only update elements that actually exist
        if (cartCountElement) {
            cartCountElement.textContent = 'Cart (' + count + ')';
        }
        if (cartCountElementMobile) {
            cartCountElementMobile.textContent = 'Cart (' + count + ')';
        }

        // Fallback if neither element exists
        if (!cartCountElement && !cartCountElementMobile) {
            console.log('Cart count initialized to: ' + count);
        }
    }

    // PHP injects the number directly
    updateCartBadge({{ App\Http\Controllers\CartController::getCartItemCount() }});

    // ---------------- OPEN MAIN PRODUCT MODAL ------------------
    function openProductModal(product) {

        selectedProductId = product.id;
        currentProduct = product;

        // Fill modal info
        document.getElementById('modalImage').src = "/storage/" + product.image_path;
        document.getElementById('modalName').textContent = product.product_name;
        document.getElementById('modalDescription').textContent = product.description;
        document.getElementById('modalSKU').textContent = product.sku ?? 'N/A';
        document.getElementById('modalCategory').textContent = product.category ?? 'N/A';
        document.getElementById('modalDepartment').textContent = product.department ?? 'N/A';
        document.getElementById('modalColor').textContent = product.color ?? 'N/A';
        document.getElementById('modalSizes').textContent =
            Array.isArray(product.available_sizes) && product.available_sizes.length
                ? product.available_sizes.join(', ')
                : (product.available_sizes ?? 'N/A');
        document.getElementById('modalPrice').textContent = Number(product.price).toFixed(2);

        // Show modal
        const modal = document.getElementById('productModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // ---------------- CLOSE MAIN MODAL ------------------
    function closeProductModal() {
        const modal = document.getElementById('productModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ---------------- OPEN BOTTOM SHEET ------------------
    // When user clicks Add to Cart in modal
    document.getElementById('modalAddToCartBtn').addEventListener('click', function () {
        if (currentProduct) openBottomSheet(currentProduct, "cart");
    });

    // When user clicks Buy Now in modal
    document.getElementById('modalBuyBtn').addEventListener('click', function () {
        if (currentProduct) openBottomSheet(currentProduct, "buy");
    });

    // mode = "buy" or "cart"
    function openBottomSheet(product, mode = "cart") {
        const sizeSelect = document.getElementById('sheetSize');
        const colorSelect = document.getElementById('sheetColor');

        // Clear previous options
        sizeSelect.innerHTML = '';
        colorSelect.innerHTML = '';

        // Ensure available_sizes is an array
        const sizes = Array.isArray(product.available_sizes) 
        ? product.available_sizes 
        : (product.available_sizes ? product.available_sizes.split(',').map(s => s.trim()) : []);

        if (sizes.length) {
            sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size;
                option.textContent = size;
                sizeSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'N/A';
            sizeSelect.appendChild(option);
        }

        // handle colors
        const colors = product.color ? product.color.split(',').map(s => s.trim()) : [];

        if (colors.length) {
            colors.forEach(color => {
                const option = document.createElement('option');
                option.value = color;
                option.textContent = color;
                colorSelect.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'N/A';
            colorSelect.appendChild(option);
        }

        // Show/hide buttons based on mode
        const addCartBtn = document.getElementById('sheetAddCartBtn');
        const buyBtn = document.getElementById('sheetBuyBtn');

        if (mode === "buy") {
            addCartBtn.style.display = "none";
            buyBtn.style.width = "100%"; // take full width
        } else {
            addCartBtn.style.display = "block";
            addCartBtn.style.width = "50%";
            buyBtn.style.display = "block";
            buyBtn.style.width = "50%";
        }

        // Show bottom sheet
        const wrapper = document.getElementById('bottomSheet');
        const sheet = document.getElementById('sheetContent');

        wrapper.classList.remove('hidden');
        wrapper.classList.add('flex');

        // Slide up animation
        setTimeout(() => {
            sheet.classList.remove('translate-y-full');
        }, 10);
    }


    // ---------------- CLOSE BOTTOM SHEET BY CLICKING SHADOW ------------------
    document.getElementById('bottomSheet').addEventListener('click', function (e) {
        if (e.target.id === 'bottomSheet') closeBottomSheet();
    });

    function closeBottomSheet() {
        const wrapper = document.getElementById('bottomSheet');
        const sheet = document.getElementById('sheetContent');

        sheet.classList.add('translate-y-full');
        setTimeout(() => {
            wrapper.classList.add('hidden');
            wrapper.classList.remove('flex');
        }, 300);
    }

    // ---------------- BOTTOM SHEET: ADD TO CART (AJAX Version) ------------------
    sheetAddCartBtn.addEventListener('click', async function () {
        // Disable button and show loading state
        sheetAddCartBtn.disabled = true;
        document.getElementById('btn-text-add-to-cart').textContent = 'Adding...';

        const size = document.getElementById('sheetSize').value;
        const color = document.getElementById('sheetColor').value;
        const qty = parseInt(document.getElementById('sheetQty').value);

        // 1. Prepare data for POST request
        const data = {
            product_id: selectedProductId,
            size: size,
            color: color,
            qty: qty,
            _token: csrfToken // Include the CSRF token
        };

        try {
            // 2. Send asynchronous request to the Laravel controller
            const response = await fetch(`/cart/add/${selectedProductId}`, {
                method: 'POST', // Use POST (as per recommendation)
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Also commonly sent in header
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                // 3. Request succeeded (200 status code)
                updateCartBadge(result.cart_item_count);
                showStatus(result.message, 'success');
            } else {
                // 4. Request failed (e.g., 422 validation, 500 server error)
                const errorMsg = result.message || 'An unknown error occurred.';
                showStatus(errorMsg, 'error');
            }

        } catch (error) {
            // 5. Network or server connection failed
            console.error('Fetch error:', error);
            showStatus('Network error. Check your connection.', 'error');
        } finally {
            // Re-enable button
            sheetAddCartBtn.disabled = false;
            document.getElementById('btn-text-add-to-cart').textContent = '🛒 Add to Cart';
            closeBottomSheet();
        }
    });
    
    // --- Initialization ---
    // In a real Laravel app, you'd fetch the initial count here (e.g., using the static helper)
    // For this demo, we initialize to 0.
    // updateCartBadge(<?php // echo App\Http\Controllers\CartController::getCartItemCount(); ?>); 
    // updateCartBadge(0);

    // ---------------- BOTTOM SHEET: BUY NOW ------------------
    document.getElementById('sheetBuyBtn').addEventListener('click', function () {

        const size = document.getElementById('sheetSize').value;
        const color = document.getElementById('sheetColor').value;
        const qty = document.getElementById('sheetQty').value;
        
        window.location.href = '{{ route("orders.create") }}' 
            + `?selectedProductId=${selectedProductId}&size=${size}&color=${color}&qty=${qty}`;
    });

    // Open fullscreen image on click
    document.getElementById('modalImage').addEventListener('click', function () {
        document.getElementById('fullscreenImg').src = this.src;
        document.getElementById('imageFullscreen').classList.remove('hidden');
        document.getElementById('imageFullscreen').classList.add('flex');
    });

    // Close fullscreen when clicking anywhere
    function closeFullscreenImage() {
        document.getElementById('imageFullscreen').classList.add('hidden');
        document.getElementById('imageFullscreen').classList.remove('flex');
    }
</script>



</x-app-layout>
