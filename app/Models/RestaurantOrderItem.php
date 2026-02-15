<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrderItem extends Model
{
    protected $fillable = ['order_id', 'menu_id', 'quantity', 'price'];
}
