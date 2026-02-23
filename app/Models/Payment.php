<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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
        'cancel_reason',
        'payment_date',
        'verified_by',
        'verified_at',
        'payment_gateway_response',
        'currency',
        'exchange_rate',
        'fees',
        'tax',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
        'fees' => 'decimal:2',
        'tax' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'payment_gateway_response' => 'array',
    ];

    protected $appends = [
        'status_text',
        'status_class',
        'payment_method_label',
        'payment_method_icon',
        'payment_method_color',
        'formatted_amount',
        'formatted_date',
        'formatted_payment_date',
        'total_amount',
        'formatted_total_amount',
        'can_be_cancelled',
        'can_be_refunded',
    ];

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_EXPIRED = 'expired';

    const STATUS_FAILED = 'failed';

    const STATUS_REFUNDED = 'refunded';

    const STATUS_PROCESSING = 'processing';

    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    const METHOD_MIXTE = 'mixte'; 


    // Constantes pour les méthodes de paiement
    const METHOD_CASH = 'cash';

    const METHOD_CARD = 'card';

    const METHOD_TRANSFER = 'transfer';

    const METHOD_MOBILE_MONEY = 'mobile_money';

    const METHOD_FEDAPAY = 'fedapay';

    const METHOD_CHECK = 'check';

    const METHOD_REFUND = 'refund';

    /**
     * Configuration du logging d'activité
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => 'a créé un paiement',
                    'updated' => 'a modifié un paiement',
                    'deleted' => 'a supprimé un paiement',
                    'restored' => 'a restauré un paiement',
                    default => "a {$eventName} un paiement",
                };
            });
    }

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
     * Relation avec l'utilisateur qui a vérifié le paiement
     */
    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
                'fields' => [],
            ],
            self::METHOD_CARD => [
                'label' => 'Carte bancaire',
                'icon' => 'fa-credit-card',
                'color' => 'primary',
                'description' => 'Paiement par carte Visa/Mastercard',
                'requires_reference' => true,
                'fields' => [],
            ],
            self::METHOD_TRANSFER => [
                'label' => 'Virement bancaire',
                'icon' => 'fa-university',
                'color' => 'info',
                'description' => 'Virement bancaire ou Western Union',
                'requires_reference' => true,
                'fields' => [],
            ],
            self::METHOD_MOBILE_MONEY => [
                'label' => 'Mobile Money',
                'icon' => 'fa-mobile-alt',
                'color' => 'warning',
                'description' => 'Paiement mobile (Moov, MTN, etc.)',
                'requires_reference' => true,
                'fields' => [],
            ],
            self::METHOD_FEDAPAY => [
                'label' => 'Fedapay',
                'icon' => 'fa-wallet',
                'color' => 'dark',
                'description' => 'Paiement en ligne sécurisé',
                'requires_reference' => true,
                'fields' => [],
            ],
            self::METHOD_CHECK => [
                'label' => 'Chèque',
                'icon' => 'fa-file-invoice-dollar',
                'color' => 'secondary',
                'description' => 'Chèque bancaire',
                'requires_reference' => true,
                'fields' => [],
            ],
            self::METHOD_REFUND => [
                'label' => 'Remboursement',
                'icon' => 'fa-undo-alt',
                'color' => 'danger',
                'description' => 'Remboursement au client',
                'requires_reference' => true,
                'fields' => [],
            ],

            self::METHOD_MIXTE => [
                'label' => 'Mixte (plusieurs moyens)',
                'icon' => 'fa-random',
                'color' => 'purple',
                'description' => 'Combinaison de plusieurs modes de paiement',
                'requires_reference' => true,
                'fields' => [],
            ],
        ];
    }

    /**
     * Obtenir les statuts disponibles avec leurs labels
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_PROCESSING => 'En traitement',
            self::STATUS_COMPLETED => 'Complété',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_EXPIRED => 'Expiré',
            self::STATUS_FAILED => 'Échoué',
            self::STATUS_REFUNDED => 'Remboursé',
            self::STATUS_PARTIALLY_REFUNDED => 'Partiellement remboursé',
        ];
    }

    /**
     * Scope pour les paiements actifs
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_PROCESSING]);
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
     * Scope pour les paiements annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope pour les paiements remboursés
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }

    /**
     * Vérifier si le paiement est annulé
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
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
     * Vérifier si le paiement est remboursé
     */
    public function isRefunded(): bool
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED]);
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusTextAttribute(): string
    {
        $statuses = self::getStatusOptions();

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Obtenir la classe CSS pour le statut
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_EXPIRED => 'secondary',
            self::STATUS_FAILED => 'dark',
            self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED => 'info',
            default => 'info'
        };
    }

    /**
     * Obtenir l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'fa-clock',
            self::STATUS_PROCESSING => 'fa-sync-alt',
            self::STATUS_COMPLETED => 'fa-check-circle',
            self::STATUS_CANCELLED => 'fa-times-circle',
            self::STATUS_EXPIRED => 'fa-hourglass-end',
            self::STATUS_FAILED => 'fa-exclamation-circle',
            self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED => 'fa-undo-alt',
            default => 'fa-circle'
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
        return number_format($this->amount, 0, ',', ' ').' CFA';
    }

    /**
     * Obtenir le montant total (montant + frais + taxes)
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->amount + ($this->fees ?? 0) + ($this->tax ?? 0);
    }

    /**
     * Obtenir le montant total formaté
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', ' ').' CFA';
    }

    /**
     * Obtenir la date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    /**
     * Obtenir la date de paiement formatée
     */
    public function getFormattedPaymentDateAttribute(): string
    {
        if (! $this->payment_date) {
            return 'Non défini';
        }

        return $this->payment_date->format('d/m/Y');
    }

    /**
     * Vérifier si le paiement peut être annulé
     */
    public function getCanBeCancelledAttribute(): bool
    {
        return $this->status === self::STATUS_COMPLETED || $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si le paiement peut être remboursé
     */
    public function getCanBeRefundedAttribute(): bool
    {
        return $this->status === self::STATUS_COMPLETED && $this->payment_method !== self::METHOD_REFUND;
    }

    /**
     * Annuler le paiement
     */
    public function cancel($userId, $reason = null): bool
    {
        if (! $this->can_be_cancelled) {
            return false;
        }

        $oldStatus = $this->status;

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason,
        ]);

        // Logger l'annulation
        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_CANCELLED,
                'amount' => $this->amount,
                'reason' => $reason,
                'transaction_id' => $this->transaction_id,
            ])
            ->log('a annulé le paiement');

        // Mettre à jour le statut de la transaction
        if ($this->transaction) {
            $this->transaction->updatePaymentStatus();
        }

        return true;
    }

    /**
     * Rembourser le paiement
     */
    public function refund($userId, $amount = null, $reason = null): bool
    {
        if (! $this->can_be_refunded) {
            return false;
        }

        $refundAmount = $amount ?? $this->amount;
        $oldStatus = $this->status;

        if ($refundAmount >= $this->amount) {
            // Remboursement complet
            $newStatus = self::STATUS_REFUNDED;
        } else {
            // Remboursement partiel
            $newStatus = self::STATUS_PARTIALLY_REFUNDED;
        }

        $this->update([
            'status' => $newStatus,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason ?? 'Remboursement',
        ]);

        // Créer un paiement de remboursement
        if ($refundAmount > 0) {
            $refundPayment = self::create([
                'transaction_id' => $this->transaction_id,
                'user_id' => $this->user_id,
                'created_by' => $userId,
                'cashier_session_id' => $this->cashier_session_id,
                'amount' => $refundAmount,
                'status' => self::STATUS_COMPLETED,
                'payment_method' => self::METHOD_REFUND,
                'description' => "Remboursement du paiement #{$this->reference}",
                'reference' => 'REFUND-'.$this->reference,
                'notes' => $reason,
            ]);

            // Logger le remboursement
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'refund_amount' => $refundAmount,
                    'original_amount' => $this->amount,
                    'refund_payment_id' => $refundPayment->id,
                    'reason' => $reason,
                ])
                ->log('a remboursé le paiement');
        }

        // Mettre à jour le statut de la transaction
        if ($this->transaction) {
            $this->transaction->updatePaymentStatus();
        }

        return true;
    }

    /**
     * Marquer comme complété
     */
    public function markAsCompleted($userId = null): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $oldStatus = $this->status;

        $this->update([
            'status' => self::STATUS_COMPLETED,
            'verified_by' => $userId ?? auth()->id(),
            'verified_at' => now(),
            'payment_date' => now(),
        ]);

        // Logger la validation
        $user = $userId ? User::find($userId) : auth()->user();
        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => self::STATUS_COMPLETED,
                    'amount' => $this->amount,
                    'transaction_id' => $this->transaction_id,
                    'verified_by' => $user->name,
                ])
                ->log('a validé le paiement');
        }

        // Mettre à jour le statut de la transaction
        if ($this->transaction) {
            $this->transaction->updatePaymentStatus();
        }

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
     * Scope pour les paiements d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope pour les paiements de la semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Scope pour les paiements du mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            // Générer une référence si non fournie
            if (! $payment->reference) {
                $payment->reference = 'PAY-'.strtoupper($payment->payment_method).'-'.time().'-'.rand(1000, 9999);
            }

            // Par défaut, le statut est "pending" pour la plupart des paiements
            if (! $payment->status) {
                $payment->status = self::STATUS_PENDING;
            }

            // Si created_by n'est pas défini, utiliser l'utilisateur courant
            if (! $payment->created_by && auth()->check()) {
                $payment->created_by = auth()->id();
            }

            // Si user_id n'est pas défini, utiliser l'utilisateur courant
            if (! $payment->user_id && auth()->check()) {
                $payment->user_id = auth()->id();
            }
        });

        static::created(function ($payment) {
            // Logger la création
            activity()
                ->causedBy(User::find($payment->created_by))
                ->performedOn($payment)
                ->withProperties([
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                    'reference' => $payment->reference,
                ])
                ->log('a créé un nouveau paiement');

            // Si le paiement est immédiatement complété, logger aussi
            if ($payment->status === self::STATUS_COMPLETED) {
                activity()
                    ->causedBy(User::find($payment->created_by))
                    ->performedOn($payment)
                    ->withProperties([
                        'amount' => $payment->amount,
                        'verified_at' => $payment->verified_at,
                    ])
                    ->log('a créé un paiement complété');
            }
        });

        static::updated(function ($payment) {
            // Logger les changements de statut
            if ($payment->isDirty('status')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($payment)
                    ->withProperties([
                        'old_status' => $payment->getOriginal('status'),
                        'new_status' => $payment->status,
                        'amount' => $payment->amount,
                    ])
                    ->log('a changé le statut du paiement');
            }

            // Recalculer le total de la transaction si le statut change
            if ($payment->isDirty('status') && $payment->transaction) {
                $payment->transaction->updatePaymentStatus();
            }
        });

        static::deleted(function ($payment) {
            // Logger la suppression
            activity()
                ->causedBy(auth()->user())
                ->performedOn($payment)
                ->withProperties([
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                    'reference' => $payment->reference,
                ])
                ->log('a supprimé le paiement');
        });

        static::restored(function ($payment) {
            // Logger la restauration
            activity()
                ->causedBy(auth()->user())
                ->performedOn($payment)
                ->withProperties([
                    'amount' => $payment->amount,
                    'method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                    'reference' => $payment->reference,
                ])
                ->log('a restauré le paiement');
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

        return $balanceDue <= 100; // Tolérance de 100 CFA
    }

    /**
     * Obtenir les statistiques des paiements
     */
    public function getStatsAttribute()
    {
        return [
            'total_amount' => $this->amount,
            'total_with_fees' => $this->total_amount,
            'fees' => $this->fees ?? 0,
            'tax' => $this->tax ?? 0,
            'net_amount' => $this->amount - ($this->fees ?? 0),
            'is_verified' => ! is_null($this->verified_at),
            'verification_time' => $this->verified_at ? $this->created_at->diffInMinutes($this->verified_at).' minutes' : null,
            'age' => $this->created_at->diffForHumans(),
        ];
    }

    /**
     * Obtenir les activités du paiement
     */
    public function paymentActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Obtenir l'historique complet du paiement
     */
    public function getHistoryAttribute()
    {
        $activities = $this->paymentActivities()->orderBy('created_at', 'desc')->get();

        return [
            'activities' => $activities,
            'transaction' => $this->transaction,
            'created_by_user' => $this->createdBy,
            'verified_by_user' => $this->verifiedByUser,
            'cancelled_by_user' => $this->cancelledByUser,
            'cashier_session' => $this->cashierSession,
            'stats' => $this->stats,
        ];
    }

    /**
     * Sauvegarde sans déclencher d'événements
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    /**
     * Obtenir le résumé du paiement
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => $this->formatted_amount,
            'status' => $this->status_text,
            'status_color' => $this->status_class,
            'payment_method' => $this->payment_method_label,
            'method_color' => $this->payment_method_color,
            'transaction' => $this->transaction ? '#'.$this->transaction->id : 'N/A',
            'customer' => $this->transaction && $this->transaction->customer ?
                $this->transaction->customer->name : 'N/A',
            'date' => $this->formatted_date,
            'payment_date' => $this->formatted_payment_date,
            'created_by' => $this->createdBy ? $this->createdBy->name : 'Système',
            'verified_by' => $this->verifiedByUser ? $this->verifiedByUser->name : 'Non vérifié',
            'can_cancel' => $this->can_be_cancelled,
            'can_refund' => $this->can_be_refunded,
        ];
    }

    
}
