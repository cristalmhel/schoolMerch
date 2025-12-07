<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'color',
        'size',
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
    ];

    /**
     * Get the cart that this item belongs to.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product associated with the cart item.
     * Assumes a 'Product' model exists.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}