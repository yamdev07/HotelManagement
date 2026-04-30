<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'url',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getRoomImage(): string
    {
        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }

        // Images are stored at public/img/room/{room_number}/{filename}
        $roomNumber = $this->room?->number;
        if ($roomNumber) {
            $path = 'img/room/' . $roomNumber . '/' . ltrim($this->url, '/');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return asset('img/default/default-room.png');
    }

    public function getImageUrl(): string
    {
        return $this->getRoomImage();
    }
}
