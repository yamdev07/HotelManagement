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
    /**
     * Prix dynamique selon la catégorie
     */
    public function getFormattedPriceAttribute()
    {
        // Barème : Standard = 80 000, Supérior = 100 000, Deluxe = 150 000, Suite = 200 000, etc.
        $base = 80000;
        $name = strtolower($this->name);
        if (str_contains($name, 'sup')) {
            $base = 100000;
        } elseif (str_contains($name, 'deluxe')) {
            $base = 150000;
        } elseif (str_contains($name, 'suite')) {
            $base = 200000;
        }
        return number_format($base, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Description haut de gamme en français
     */
    public function getDescriptionFrAttribute()
    {
        $name = strtolower($this->name);
        if (str_contains($name, 'standard')) {
            return "Chambre Standard : Offrez-vous un confort moderne dans un espace élégant, équipé d’un lit queen-size, d’une salle de bain raffinée, climatisation, TV connectée, Wi-Fi haut débit et vue sur la ville. Idéale pour un séjour d’affaires ou de détente.";
        } elseif (str_contains($name, 'sup')) {
            return "Chambre Supérior : Profitez d’un espace généreux, d’une literie premium, d’un coin salon raffiné, salle de bain luxueuse avec douche à l’italienne, produits d’accueil de prestige, et balcon privatif avec vue panoramique.";
        } elseif (str_contains($name, 'deluxe')) {
            return "Deluxe Room : Un écrin de luxe avec lit king-size, salon séparé, salle de bain en marbre avec baignoire, machine à café Nespresso, peignoirs et chaussons, et service de conciergerie personnalisé. Ambiance feutrée et prestations haut de gamme.";
        } elseif (str_contains($name, 'suite')) {
            return "Suite Présidentielle : Vivez l’exception avec un vaste salon, chambre indépendante, salle de bain spa, terrasse privée, jacuzzi, service majordome, et prestations exclusives. Le summum du raffinement pour une expérience inoubliable.";
        }
        return "Chambre élégante et parfaitement équipée pour un séjour d’exception à l’hôtel.";
    }

    public function getAmenitiesListAttribute()
    {
        if (! $this->amenities) {
            return [];
        }

        return is_array($this->amenities) ? $this->amenities : json_decode($this->amenities, true);
    }
}
