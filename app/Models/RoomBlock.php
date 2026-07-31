<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Model;

/**
 * Période d'indisponibilité importée d'un calendrier OTA (end_date exclusif).
 */
class RoomBlock extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'room_id', 'feed_id', 'source', 'external_uid',
        'start_date', 'end_date', 'summary',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /** Chambres indisponibles (bloquées OTA) qui chevauchent [$ci, $co[. */
    public function scopeOverlapping($query, string $checkIn, string $checkOut)
    {
        return $query->whereDate('start_date', '<', $checkOut)
            ->whereDate('end_date', '>', $checkIn);
    }
}
