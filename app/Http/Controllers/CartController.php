<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Handles adding a product to the cart via AJAX, returning JSON.
     *
     * @param Request $request
     * @param Product $product The product instance resolved by route model binding.
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request, Product $product)
    {
        // 1. Validate Input
        try {
            $request->validate([
                // We validate that the product exists and has a price.
                // You should probably check for stock availability here too.
                'product_id' => 'required|exists:products,id', 
                'size' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:50',
                'qty' => 'required|integer|min:1',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input provided.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Get the current user or generate a session ID for guests
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        // 2. Find or Create the Cart
        if ($userId) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $userId, 'status' => 'active'],
                ['session_id' => $sessionId]
            );
        } else {
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId, 'status' => 'active'],
                ['user_id' => null]
            );
        }

        // 3. Prepare Attributes and Item Search
        $attributes = array_filter([
            'size' => $request->input('size'),
            'color' => $request->input('color'),
        ]);
        
        $attributesJson = empty($attributes) ? null : json_encode($attributes);
        $requestedQuantity = $request->input('qty');
        $requestedSize = $request->input('size');
        $requestedColor = $request->input('color');

        // 4. Check if the exact item already exists
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            // Use DB::raw or a more complex query for reliable JSON comparison
            ->where(DB::raw('JSON_UNQUOTE(JSON_COMPACT(attributes))'), $attributesJson) 
            ->first();

        // Use a transaction for atomic operation (good practice for e-commerce logic)
        DB::beginTransaction();
        try {
            if ($cartItem) {
                $cartItem->update([
                    'quantity'   => $requestedQuantity,
                    'size'       => $requestedSize,
                    'color'      => $requestedColor,
                    'attributes' => $attributes,
                ]);
            } else {
                // Item does not exist: Create new item
                $cartItem = new CartItem([
                    'product_id' => $product->id,
                    'price' => $product->price, 
                    'quantity' => $requestedQuantity,
                    'size' => $requestedSize,
                    'color' => $requestedColor,
                    'attributes' => $attributes, 
                ]);
                $cart->items()->save($cartItem);
            }

            DB::commit();

            // 5. Calculate New Cart Total Items (Count of unique CartItem rows)
            $newCartItemCount = $cart->items()->count();
            
            // 6. Return JSON response with the new count
            return response()->json([
                'status' => 'success',
                'message' => 'Item added to cart!',
                'cart_item_count' => $newCartItemCount,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("AJAX Cart addition failed: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add item to cart due to a server error.',
            ], 500);
        }
    }

    /**
     * Helper function to get the current cart item count for initial page load.
     * This is useful for pre-populating the cart badge in your navigation.
     */
    public static function getCartItemCount()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cart = Cart::where('status', 'active')
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->when(!$userId, fn ($query) => $query->where('session_id', $sessionId))
            ->first();

        return $cart ? $cart->items()->count() : 0;
    }
}