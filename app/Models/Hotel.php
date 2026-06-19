<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'currency',
        'timezone',
        'logo',
        'primary_color',
        'secondary_color',
        'contact_email',
        'contact_phone',
        'address',
        'is_active',
        'subscription_ends_at',
        'owner_user_id',
        'metadata',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'subscription_ends_at' => 'datetime',
        'metadata'             => 'array',
    ];

    /**
     * Utilisateurs (staff) rattachés à cet hôtel.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Propriétaire / admin principal de l'hôtel.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * L'hôtel a-t-il actuellement accès à la plateforme ?
     * (actif ET abonnement non expiré)
     */
    public function hasActiveAccess(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->subscription_ends_at !== null && $this->subscription_ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->subscription_ends_at !== null && $this->subscription_ends_at->isPast();
    }

    /**
     * Couleur principale (fallback sur la couleur par défaut de la plateforme).
     */
    public function primaryColor(): string
    {
        return $this->primary_color ?: '#4f46e5';
    }

    public function secondaryColor(): string
    {
        return $this->secondary_color ?: '#0f172a';
    }

    /**
     * URL du logo de l'hôtel, ou null si aucun logo n'est défini.
     */
    public function logoUrl(): ?string
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }
}
