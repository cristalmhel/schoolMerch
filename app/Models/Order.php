<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'user_id',
        'order_number',
        'order_type',
        'shipping_detail_id',
        'pickup_detail_id',
        'total_amount',
        'shipping_cost',
        'tax_amount',
        'status',
        'payment_method',
        'payment_transaction_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * The 'shipping_address' field is cast to an array/JSON object.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];


    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items included in the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship for the new ShippingDetail model
    public function shippingDetail()
    {
        return $this->hasOne(ShippingDetail::class);
    }

    // Relationship for the new PickupDetail model
    public function pickupDetail()
    {
        return $this->hasOne(PickupDetail::class);
    }
}