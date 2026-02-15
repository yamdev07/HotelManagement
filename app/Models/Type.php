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
        'base_price',
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

    // SCOPES UTILES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ACCESSORS (GETTERS)
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
}
