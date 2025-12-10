<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail',
        'icon',
    ];

    /**
     * Relation avec les chambres
     * Votre table pivot s'appelle 'facility_room_table'
     */
    public function rooms()
    {
        // Utilisez 'facility_room' comme nom de table pivot
        return $this->belongsToMany(Room::class, 'facility_room');
    }
}