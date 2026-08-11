<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

class HousekeepingNote extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id',
        'report_date',
        'observations',
        'suggestions',
        'user_id',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];
}
