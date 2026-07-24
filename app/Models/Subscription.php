<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Une période d'abonnement souscrite par un hôtel (essai, souscription, renouvellement).
 * Globale (non scopée) : le Super-Admin en a besoin pour toute la plateforme.
 */
class Subscription extends Model
{
    protected $fillable = [
        'hotel_id',
        'plan',
        'amount',
        'currency',
        'status',
        'is_renewal',
        'starts_at',
        'ends_at',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_renewal' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
