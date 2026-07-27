<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use \App\Models\Concerns\BelongsToHotel, HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'date',
        'time',
        'status',
    ];
}
