<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_number',
        'courier_name',
        'shipping_date',
        'delivery_date',
        'is_delivered', // Boolean flag
        'notes',
        
        'recipient_name',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'zip_code',
    ];

    // Define the inverse relationship to the Order model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
