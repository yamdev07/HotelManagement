<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // PROTECTION CONTRE L'ASSIGNATION EN MASSE
    protected $fillable = [
        'name',
        'information',
        'base_price', // C'est le champ réel dans la base
        'capacity',
        'amenities',
        'bed_type',
        'bed_count',
        'size',
        'sort_order',
        'is_active',
    ];

    // CASTING DES TYPES
    protected $casts = [
        'amenities' => 'array',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // RELATIONS
    public function rooms()
    {
        return $this->hasMany(Room::class, 'type_id');
    }

    // AJOUTEZ CES ACCESSORS POUR UTILISER "price" DANS LES VUES
    public function getPriceAttribute()
    {
        return $this->base_price;
    }
    
    public function setPriceAttribute($value)
    {
        $this->attributes['base_price'] = $value;
    }

    // ACCESSORS (GETTERS) EXISTANTS
    public function getFormattedPriceAttribute()
    {
        if (! $this->base_price) {
            return 'N/A';
        }

        return number_format($this->base_price, 0, ',', ' ').' FCFA';
    }

    public function getAmenitiesListAttribute()
    {
        if (! $this->amenities) {
            return [];
        }

        return is_array($this->amenities) ? $this->amenities : json_decode($this->amenities, true);
    }

    // SCOPES UTILES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Vérifier si le type est disponible pour une période
     */
    public function isAvailableForPeriod($checkIn, $checkOut)
    {
        $availableRooms = $this->rooms()
            ->where('room_status_id', 1) // Disponible
            ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn)
                    ->whereIn('status', ['reservation', 'active']);
            })
            ->count();
            
        return $availableRooms > 0;
    }
}