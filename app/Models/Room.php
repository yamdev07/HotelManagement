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
        'description' // Ajoutez cette ligne si la colonne existe
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

    public function firstImage()
    {
        // Vérifiez si la relation est chargée et non vide
        if ($this->relationLoaded('image') && $this->image && $this->image->isNotEmpty()) {
            $firstImage = $this->image->first();
            
            // Utilisez getRoomImage() ou retournez simplement l'URL
            if (method_exists($firstImage, 'getRoomImage')) {
                return $firstImage->getRoomImage();
            }
            
            // Fallback : retournez l'URL brute
            return $firstImage->url ?? asset('img/default/default-room.png');
        }
        
        // Si la relation n'est pas chargée, essayez de charger une image
        $image = $this->image()->first();
        if ($image) {
            return $image->getRoomImage();
        }

        // Image par défaut
        return asset('img/default/default-room.png');
    }
    
    // Ajoutez un accesseur pour la première image
    public function getFirstImageUrlAttribute()
    {
        return $this->firstImage();
    }
}