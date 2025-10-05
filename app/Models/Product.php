<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional if table name follows Laravel convention)
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_name',
        'product_id',
        'sku',
        'description',
        'price',
        'stock_quantity',
        'category',
        'department',
        'color',
        'available_sizes',
        'status',
        'is_featured',
        'is_limited',
        'available_online',
        'image_path',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_featured' => 'boolean',
        'is_limited' => 'boolean',
        'available_online' => 'boolean',
    ];

    /**
     * Accessor to get available sizes as array instead of CSV.
     */
    public function getAvailableSizesArrayAttribute()
    {
        return $this->available_sizes ? explode(',', $this->available_sizes) : [];
    }

    /**
     * Mutator to set available sizes from array.
     */
    public function setAvailableSizesAttribute($sizes)
    {
        $this->attributes['available_sizes'] = is_array($sizes) ? implode(',', $sizes) : $sizes;
    }

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
