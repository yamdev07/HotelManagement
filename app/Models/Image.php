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

    // CORRIGEZ CETTE MÉTHODE :
    public function getRoomImage()
    {
        // Si l'URL commence par http:// ou https://, utilisez-la directement
        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }
        
        // Sinon, supposez que c'est un chemin dans storage
        // Enlève le 'storage/' s'il est déjà présent au début
        $url = ltrim($this->url, '/');
        if (str_starts_with($url, 'storage/')) {
            return asset($url);
        }
        
        // Par défaut, ajoute 'storage/'
        return asset('storage/' . $url);
    }
    
    // Méthode alternative plus simple
    public function getImageUrl()
    {
        return $this->getRoomImage(); // Utilise la même logique
    }
}