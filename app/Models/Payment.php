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
        'created_by',
        'transaction_id',
        'cashier_session_id',
        'amount',
        'status',
        'payment_method',
        'description',
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
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // Constantes pour les méthodes de paiement
    const METHOD_CASH = 'cash';
    const METHOD_CARD = 'card';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_FEDAPAY = 'fedapay';
    const METHOD_CHECK = 'check';
    const METHOD_REFUND = 'refund';

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
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'utilisateur qui a créé l'enregistrement
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a annulé le paiement
     */
    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Relation avec la session de caisse
     */
    public function cashierSession()
    {
        return $this->belongsTo(CashierSession::class, 'cashier_session_id');
    }

    /**
     * Obtenir toutes les méthodes de paiement disponibles
     */
    public static function getPaymentMethods(): array
    {
        return [
            self::METHOD_CASH => [
                'label' => 'Espèces',
                'icon' => 'fa-money-bill-wave',
                'color' => 'success',
                'description' => 'Paiement en espèces comptant',
                'requires_reference' => false,
                'fields' => []
            ],
            self::METHOD_CARD => [
                'label' => 'Carte bancaire',
                'icon' => 'fa-credit-card',
                'color' => 'primary',
                'description' => 'Paiement par carte Visa/Mastercard',
                'requires_reference' => true,
                'fields' => []
            ],
            self::METHOD_TRANSFER => [
                'label' => 'Virement bancaire',
                'icon' => 'fa-university',
                'color' => 'info',
                'description' => 'Virement bancaire ou Western Union',
                'requires_reference' => true,
                'fields' => []
            ],
            self::METHOD_MOBILE_MONEY => [
                'label' => 'Mobile Money',
                'icon' => 'fa-mobile-alt',
                'color' => 'warning',
                'description' => 'Paiement mobile (Moov, MTN, etc.)',
                'requires_reference' => true,
                'fields' => []
            ],
            self::METHOD_FEDAPAY => [
                'label' => 'Fedapay',
                'icon' => 'fa-wallet',
                'color' => 'dark',
                'description' => 'Paiement en ligne sécurisé',
                'requires_reference' => true,
                'fields' => []
            ],
            self::METHOD_CHECK => [
                'label' => 'Chèque',
                'icon' => 'fa-file-invoice-dollar',
                'color' => 'secondary',
                'description' => 'Chèque bancaire',
                'requires_reference' => true,
                'fields' => []
            ],
        ];
    }

    /**
     * Scope pour les paiements actifs
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_COMPLETED]);
    }

    /**
     * Vérifier si le paiement est annulé
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_COMPLETED => 'Complété',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_EXPIRED => 'Expiré',
            self::STATUS_FAILED => 'Échoué',
            self::STATUS_REFUNDED => 'Remboursé',
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
            self::STATUS_FAILED => 'dark',
            self::STATUS_REFUNDED => 'info',
            default => 'info'
        };
    }

    /**
     * Obtenir le label de la méthode de paiement
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $methods = self::getPaymentMethods();
        return $methods[$this->payment_method]['label'] ?? ucfirst($this->payment_method);
    }

    /**
     * Obtenir l'icône de la méthode de paiement
     */
    public function getPaymentMethodIconAttribute(): string
    {
        $methods = self::getPaymentMethods();
        return $methods[$this->payment_method]['icon'] ?? 'fa-money-bill-wave';
    }

    /**
     * Obtenir la couleur de la méthode de paiement
     */
    public function getPaymentMethodColorAttribute(): string
    {
        $methods = self::getPaymentMethods();
        return $methods[$this->payment_method]['color'] ?? 'secondary';
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
     * Vérifier si le paiement peut être annulé
     */
    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_COMPLETED || $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si le paiement peut être remboursé
     */
    public function canBeRefunded(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Marquer comme remboursé
     */
    public function markAsRefunded($userId, $reason = null): bool
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason ?? 'Remboursement'
        ]);

        return true;
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope pour filtrer par méthode de paiement
     */
    public function scopeByPaymentMethod($query, $method)
    {
        if ($method) {
            return $query->where('payment_method', $method);
        }
        return $query;
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * Scope pour les paiements de la session courante
     */
    public function scopeCurrentSession($query, $sessionId)
    {
        if ($sessionId) {
            return $query->where('cashier_session_id', $sessionId);
        }
        return $query;
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            // Générer une référence si non fournie
            if (!$payment->reference) {
                $payment->reference = 'PAY-' . strtoupper($payment->payment_method) . '-' . time();
            }
            
            // Par défaut, le statut est "pending" pour la plupart des paiements
            if (!$payment->status) {
                $payment->status = self::STATUS_PENDING;
            }

            // Si created_by n'est pas défini, utiliser l'utilisateur courant
            if (!$payment->created_by && auth()->check()) {
                $payment->created_by = auth()->id();
            }
        });

        static::updated(function ($payment) {
            // Recalculer le total de la transaction si le statut change
            if ($payment->isDirty('status') && $payment->transaction) {
                $payment->transaction->updatePaymentStatus();
            }
        });
    }

    /**
     * Obtenir le montant total des paiements pour une transaction
     */
    public static function getTotalForTransaction($transactionId)
    {
        return self::where('transaction_id', $transactionId)
            ->where('status', self::STATUS_COMPLETED)
            ->sum('amount');
    }

    /**
     * Obtenir le solde dû pour une transaction
     */
    public static function getBalanceDue($transactionId, $transactionTotal)
    {
        $totalPaid = self::getTotalForTransaction($transactionId);
        return max(0, $transactionTotal - $totalPaid);
    }

    /**
     * Vérifier si une transaction est entièrement payée
     */
    public static function isTransactionFullyPaid($transactionId, $transactionTotal)
    {
        $balanceDue = self::getBalanceDue($transactionId, $transactionTotal);
        return $balanceDue <= 0;
    }
}