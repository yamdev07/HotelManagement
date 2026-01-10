<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount', // Changé de 'price' à 'amount' pour cohérence
        'status', // 'pending', 'completed', 'cancelled', 'expired'
        'payment_method', // 'cash', 'card', 'transfer', 'mobile_money'
        'notes',
        'reference',
        'cancelled_at',
        'cancelled_by',
        'cancel_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Constantes pour les méthodes de paiement
    const METHOD_CASH = 'cash';
    const METHOD_CARD = 'card';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_MOBILE_MONEY = 'mobile_money';

    /**
     * Relation avec la transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé le paiement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'utilisateur qui a annulé le paiement
     */
    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Scope pour les paiements actifs (non annulés/non expirés)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_COMPLETED]);
    }

    /**
     * Scope pour les paiements annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope pour les paiements expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope pour les paiements complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Vérifier si le paiement est annulé
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si le paiement est expiré
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Vérifier si le paiement est actif (non annulé/non expiré)
     */
    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_COMPLETED]);
    }

    /**
     * Vérifier si le paiement est complété
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifier si le paiement est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Annuler le paiement
     */
    public function cancel($userId, $reason = null): bool
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason
        ]);

        return true;
    }

    /**
     * Marquer comme expiré
     */
    public function markAsExpired($userId): bool
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => 'Paiement expiré automatiquement'
        ]);

        return true;
    }

    /**
     * Restaurer un paiement annulé/expiré
     */
    public function restorePayment(): bool
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancel_reason' => null
        ]);

        return true;
    }

    /**
     * Obtenir le statut sous forme textuelle
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_COMPLETED => 'Complété',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_EXPIRED => 'Expiré',
            default => $this->status
        };
    }

    /**
     * Obtenir la classe CSS pour le statut
     */
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_EXPIRED => 'secondary',
            default => 'info'
        };
    }

    /**
     * Obtenir le montant formaté
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' CFA';
    }

    /**
     * Obtenir la date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    /**
     * Obtenir la date d'annulation formatée
     */
    public function getFormattedCancelledDateAttribute(): ?string
    {
        return $this->cancelled_at ? $this->cancelled_at->format('d/m/Y à H:i') : null;
    }

    /**
     * Obtenir la méthode de paiement formatée
     */
    public function getFormattedMethodAttribute(): string
    {
        return match($this->payment_method) {
            self::METHOD_CASH => 'Espèces',
            self::METHOD_CARD => 'Carte bancaire',
            self::METHOD_TRANSFER => 'Virement',
            self::METHOD_MOBILE_MONEY => 'Mobile Money',
            default => ucfirst($this->payment_method)
        };
    }

    /**
     * Vérifier si le paiement peut être annulé
     */
    public function canBeCancelled(): bool
    {
        // Un paiement peut être annulé s'il est actif
        return $this->isActive() && !$this->isCancelled() && !$this->isExpired();
    }

    /**
     * Vérifier si le paiement peut être restauré
     */
    public function canBeRestored(): bool
    {
        // Un paiement peut être restauré s'il est annulé ou expiré
        return $this->isCancelled() || $this->isExpired();
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Lorsqu'un paiement est annulé, recalculer le total de la transaction
        static::updated(function ($payment) {
            if ($payment->isDirty('status') && 
                ($payment->isCancelled() || $payment->isExpired())) {
                $payment->transaction->updatePaymentStatus();
            }
        });

        // Lorsqu'un paiement est restauré, recalculer le total de la transaction
        static::updated(function ($payment) {
            if ($payment->isDirty('status') && $payment->isCompleted()) {
                $payment->transaction->updatePaymentStatus();
            }
        });
    }
}