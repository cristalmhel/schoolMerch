<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupDetail;
use App\Models\ShippingDetail;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    const DELIVERY_SHIPPING_COST = 120.00;
    const PICKUP_SHIPPING_COST = 0.00;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Load relations
        $query = Order::with(['user', 'items'])->select('*');

        /* --------------------------------------------
        | ROLE-BASED ACCESS
        |---------------------------------------------
        | super_admin → all orders
        | admin       → only orders that contain products
        |              whose department = user department
        */
        if ($user->role !== 'super_admin') {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('department', $user->department);
            });
        }

        /* --------------------------------------------
        | SEARCH (Order ID or User Name)
        |-------------------------------------------- */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'LIKE', "%{$search}%");
                });
            });
        }

        /* --------------------------------------------
        | FILTER BY DEPARTMENT (Super Admin Only)
        |-------------------------------------------- */
        // Department filter (user's department)
        if ($user->role === 'super_admin' && $request->filled('department')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        /* --------------------------------------------
        | STATUS FILTER
        |-------------------------------------------- */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* --------------------------------------------
        | SORTING
        |-------------------------------------------- */
        if ($request->sort === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            // default and for "date_desc"
            $query->orderBy('created_at', 'desc');
        }

        /* --------------------------------------------
        | PAGINATION
        |-------------------------------------------- */
        $orders = $query->paginate(10)->appends($request->query());

        return view('orders.index', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('orders.edit', compact('order'));
    }

    /* ============================================================
       UPDATE ORDER STATUS
       ============================================================ */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()
            ->route('orders.index')
            ->with('success', "Order #{$order->id} status updated to {$order->status}");
    }


    /**
     * Show the form for creating a new order.
     */
    public function create(Request $request)
    {
        // Read query parameters from the URL
        $selectedProductId = $request->query('selectedProductId');
        $size = $request->query('size');
        $color = $request->query('color');
        $qty = $request->query('qty');

        // Fetch product from database
        $product = Product::find($selectedProductId);

        if (!$product) {
            abort(404, "Product not found");
        }

        // Calculate total price
        $total = $product->price * $qty;

        return view('orders.create', [
            'product' => $product,
            'size' => $size,
            'color' => $color,
            'qty' => $qty,
            'total' => $total,
        ]);
    }


    /**
     * Handles the AJAX POST request to finalize the order.
     * (This is the route named 'order.store' referenced in your JavaScript)
     */
    // Define the shipping costs here (matching the frontend constants)
    public function store(Request $request)
    {
        // 1. Validation Rules
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string|max:255',
            'order_type' => 'required|string|in:delivery,pickup',
            
            // Product item validation (based on single-item checkout flow)
            'selected_product_id' => 'required|exists:products,id',
            'product_size' => 'required|string|max:50',
            'product_color' => 'required|string|max:50',
            'product_qty' => 'required|integer|min:1',
        ];

        // Conditional validation for delivery
        if ($request->order_type === 'delivery') {
            $rules = array_merge($rules, [
                'shipping_address_line1' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:100',
                'shipping_zip' => 'required|string|max:20',
                'shipping_address_line2' => 'nullable|string|max:255',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Fetch Real Product Data and Calculate Totals (SERVER-SIDE CALCULATION)
        try {
            $product = Product::findOrFail($request->selected_product_id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $unitPrice = $product->price;
        $qty = (int)$request->product_qty;
        
        $subtotal = $unitPrice * $qty;
        $shippingCost = $request->order_type === 'delivery' 
            ? self::DELIVERY_SHIPPING_COST 
            : self::PICKUP_SHIPPING_COST;

        $totalAmount = $subtotal + $shippingCost;
        
        // Use a transaction to ensure all database operations succeed together
        try {
            $order = DB::transaction(function () use ($request, $subtotal, $shippingCost, $totalAmount, $unitPrice, $product, $qty) {
                // --- B. Create the Main Order ---
                $newOrder = Order::create([
                    'user_id' => Auth::id() ?? 0, // Use authenticated user or 0 for guest
                    'order_number' => 'ORD-' . time() . rand(10, 99),
                    'order_type' => $request->order_type,
                    'total_amount' => $totalAmount,
                    'shipping_cost' => $shippingCost,
                    'tax_amount' => 0.00, // Assuming 0 tax for simplicity
                    'status' => 'Pending',
                    'payment_method' => $request->payment_method,
                    'payment_transaction_id' => null, // Placeholder
                ]);

                // --- C. Create Order Item(s) ---
                // For a single item flow, we create one OrderItem
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'color' => $request->product_color,
                    'size' => $request->product_size,
                    'attributes' => json_encode([
                        'size' => $request->product_size,
                        'color' => $request->product_color,
                    ]),
                ]);
                
                $shippingDetailId = null;
                $pickupDetailId = null;

                // --- A. Handle Delivery vs. Pickup Details ---
                if ($request->order_type === 'delivery') {
                    // Create Shipping Detail record
                    $shippingDetail = ShippingDetail::create([
                        'recipient_name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'address_line1' => $request->shipping_address_line1,
                        'address_line2' => $request->shipping_address_line2,
                        'city' => $request->shipping_city,
                        'zip_code' => $request->shipping_zip,
                        'tracking_number' => 'TRK-' . time(),
                        'order_id' => $newOrder->id,
                    ]);
                    $shippingDetailId = $shippingDetail->id;

                } else {
                    // Create Pickup Detail record
                    $pickupDetail = PickupDetail::create([
                        'recipient_name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'order_id' => $newOrder->id,
                    ]);
                    $pickupDetailId = $pickupDetail->id;
                }

                // If it was a pickup, update the pickup detail with the correct order ID
                if ($request->order_type === 'pickup') {
                    $newOrder->pickup_detail_id = $pickupDetailId;
                    $newOrder->save();
                } else {
                    $newOrder->shipping_detail_id = $shippingDetailId;
                    $newOrder->save();
                }
                return $newOrder;
            });

            // 5. Return success JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Order processed successfully. Thank you!',
                'redirect_url' => route('orders.thankyou', $order->id),
                'order_id' => $order->id
            ]);

        } catch (\Throwable $e) {
            Log::error('Order processing failed due to transaction error.', [
                'error_message' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);

            // Return error JSON
            return response()->json([
                'status' => 'error',
                'message' => 'A critical error occurred while processing the order. Please try again.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function thankyou(string $id)
    {
        return view('orders.thankyou', [
            'order_id' => $id,
        ]);
    }

    public function myOrders()
    {
        $user = auth()->user();

        $orders = \App\Models\Order::with(['items.product'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('orders.myorders', compact('orders'));
    }

    public function showMyOrder($id)
    {
        $order = \App\Models\Order::with(['items.product'])
                    ->where('user_id', auth()->id())
                    ->findOrFail($id);

        return view('orders.orderdetails', compact('order'));
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
