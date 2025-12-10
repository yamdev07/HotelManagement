<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'room_status_id',
        'number',
        'capacity',
        'price',
        'view',
        'description'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function roomStatus()
    {
        return $this->belongsTo(RoomStatus::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Relation avec les équipements (facilities)
     * Votre table pivot s'appelle 'facility_room_table'
     */
    public function facilities()
    {
        // Utilisez 'facility_room' comme nom de table pivot
        return $this->belongsToMany(Facility::class, 'facility_room');
    }

    public function firstImage()
    {
        if ($this->relationLoaded('image') && $this->image && $this->image->isNotEmpty()) {
            $firstImage = $this->image->first();
            
            if (method_exists($firstImage, 'getRoomImage')) {
                return $firstImage->getRoomImage();
            }
            
            return $firstImage->url ?? asset('img/default/default-room.png');
        }
        
        $image = $this->image()->first();
        if ($image) {
            return $image->getRoomImage();
        }

        return asset('img/default/default-room.png');
    }
    
    public function getFirstImageUrlAttribute()
    {
        return $this->firstImage();
    }
}