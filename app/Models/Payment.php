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
        'amount',
        'status',
        'payment_method',
        'payment_method_details',
        'reference',
        'check_number',
        'card_last_four',
        'card_type',
        'mobile_money_provider',
        'mobile_money_number',
        'bank_name',
        'account_number',
        'notes',
        'cancelled_at',
        'cancelled_by',
        'cancel_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'payment_method_details' => 'array',
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

    // Fournisseurs Mobile Money
    const MOBILE_MONEY_MOOV = 'moov_money';
    const MOBILE_MONEY_MTN = 'mtn_money';
    const MOBILE_MONEY_FLOOZ = 'flooz';
    const MOBILE_MONEY_ORANGE = 'orange_money';

    // Types de cartes
    const CARD_VISA = 'visa';
    const CARD_MASTERCARD = 'mastercard';
    const CARD_AMEX = 'amex';

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
                'fields' => ['card_last_four', 'card_type']
            ],
            self::METHOD_TRANSFER => [
                'label' => 'Virement bancaire',
                'icon' => 'fa-university',
                'color' => 'info',
                'description' => 'Virement bancaire ou Western Union',
                'requires_reference' => true,
                'fields' => ['bank_name', 'account_number']
            ],
            self::METHOD_MOBILE_MONEY => [
                'label' => 'Mobile Money',
                'icon' => 'fa-mobile-alt',
                'color' => 'warning',
                'description' => 'Paiement mobile (Moov, MTN, etc.)',
                'requires_reference' => true,
                'fields' => ['mobile_money_provider', 'mobile_money_number']
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
                'fields' => ['check_number', 'bank_name']
            ],
        ];
    }

    /**
     * Obtenir les fournisseurs Mobile Money
     */
    public static function getMobileMoneyProviders(): array
    {
        return [
            self::MOBILE_MONEY_MOOV => 'Moov Money',
            self::MOBILE_MONEY_MTN => 'MTN Money',
            self::MOBILE_MONEY_FLOOZ => 'Flooz',
            self::MOBILE_MONEY_ORANGE => 'Orange Money',
        ];
    }

    /**
     * Obtenir les types de cartes
     */
    public static function getCardTypes(): array
    {
        return [
            self::CARD_VISA => 'Visa',
            self::CARD_MASTERCARD => 'Mastercard',
            self::CARD_AMEX => 'American Express',
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
     * Obtenir les détails de la méthode de paiement
     */
    public function getPaymentMethodDetailsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /**
     * Obtenir le fournisseur Mobile Money formaté
     */
    public function getMobileMoneyProviderTextAttribute(): ?string
    {
        if (!$this->mobile_money_provider) {
            return null;
        }
        
        $providers = self::getMobileMoneyProviders();
        return $providers[$this->mobile_money_provider] ?? $this->mobile_money_provider;
    }

    /**
     * Obtenir le type de carte formaté
     */
    public function getCardTypeTextAttribute(): ?string
    {
        if (!$this->card_type) {
            return null;
        }
        
        $types = self::getCardTypes();
        return $types[$this->card_type] ?? $this->card_type;
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
            
            // Par défaut, le statut est "completed" pour la plupart des paiements
            if (!$payment->status) {
                $payment->status = self::STATUS_COMPLETED;
            }
        });

        static::updated(function ($payment) {
            // Recalculer le total de la transaction si le statut change
            if ($payment->isDirty('status') && $payment->transaction) {
                $payment->transaction->updatePaymentStatus();
            }
        });
    }
}