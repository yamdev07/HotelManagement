<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrderItem extends Model
{
    protected $fillable = ['order_id', 'menu_id', 'quantity', 'price'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function order()
    {
        return $this->belongsTo(RestaurantOrder::class, 'order_id');
    }
}
