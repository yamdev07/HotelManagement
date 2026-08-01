<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Avis client d'un hôtel (note + commentaire), modéré par l'hôtelier.
 * Scopé automatiquement à l'hôtel courant via BelongsToHotel.
 */
class Review extends Model
{
    use BelongsToHotel, HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'hotel_id', 'transaction_id', 'author_name', 'author_city',
        'rating', 'comment', 'status', 'reply', 'replied_at', 'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'replied_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /** Initiale de l'auteur pour l'avatar. */
    public function initial(): string
    {
        return mb_strtoupper(mb_substr(trim($this->author_name), 0, 1)) ?: '?';
    }
}
