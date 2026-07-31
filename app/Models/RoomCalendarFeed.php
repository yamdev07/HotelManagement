<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

/**
 * Flux iCal externe (OTA) à importer pour une chambre.
 */
class RoomCalendarFeed extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'room_id', 'source', 'url', 'last_synced_at', 'last_error',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function blocks()
    {
        return $this->hasMany(RoomBlock::class, 'feed_id');
    }
}
