<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrder extends Model
{
    protected $fillable = ['customer_id', 'room_id', 'total', 'status'];

    public function items()
    {
        return $this->hasMany(RestaurantOrderItem::class, 'order_id');
    }
}

