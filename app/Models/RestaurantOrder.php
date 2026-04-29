<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrder extends Model
{
    protected $fillable = ['customer_id', 'room_id', 'transaction_id', 'total', 'status', 'notes', 'payment_method'];

    protected $casts = [
        'total' => 'float',
    ];

    protected $appends = ['customer_name', 'customer_phone', 'room_number', 'items_count'];

    public function items()
    {
        return $this->hasMany(RestaurantOrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->name : 'Client non spécifié';
    }

    public function getCustomerPhoneAttribute()
    {
        return $this->customer ? $this->customer->phone : null;
    }

    public function getRoomNumberAttribute()
    {
        return $this->room ? $this->room->number : null;
    }

    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }
}
