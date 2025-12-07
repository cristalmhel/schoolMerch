<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickupDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'recipient_name',
        'email',
        'phone',
        'is_picked_up', // Boolean flag
        'notes',
    ];

    // Define the inverse relationship to the Order model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
