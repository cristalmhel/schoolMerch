<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'color',
        'size',
        'subtotal',
        'attributes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * The 'attributes' field is cast to an array/JSON object.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attributes' => 'array',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the order that this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item (nullable link).
     * Assumes a 'Product' model exists.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}