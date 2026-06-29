<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantReservation extends Model
{
    use \App\Models\Concerns\BelongsToHotel;
    protected $fillable = [
        'name', 'phone', 'reservation_date', 'reservation_time',
        'persons', 'table_type', 'notes', 'status',
    ];
}
