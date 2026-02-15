<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CashierTransaction extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'cashier_session_id',
        'transaction_id',
        'payment_id',
        'type',
        'amount',
        'description',
        'reference',
        'status',
        'notes',
        'created_by',
        'verified_by',
        'verified_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    const TYPE_CASH_PAYMENT = 'cash_payment';

    const TYPE_CARD_PAYMENT = 'card_payment';

    const TYPE_TRANSFER_PAYMENT = 'transfer_payment';

    const TYPE_CHECK_PAYMENT = 'check_payment';

    const TYPE_MOBILE_PAYMENT = 'mobile_payment';

    const TYPE_CASH_REFUND = 'cash_refund';

    const TYPE_CARD_REFUND = 'card_refund';

    const TYPE_TRANSFER_REFUND = 'transfer_refund';

    const TYPE_CHECK_REFUND = 'check_refund';

    const TYPE_MOBILE_REFUND = 'mobile_refund';

    const TYPE_CASH_DEPOSIT = 'cash_deposit';

    const TYPE_CASH_WITHDRAWAL = 'cash_withdrawal';

    const TYPE_ADJUSTMENT = 'adjustment';

    const TYPE_CORRECTION = 'correction';

    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';

    const STATUS_VERIFIED = 'verified';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_REVERSED = 'reversed';

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
                    'created' => 'a créé une transaction de caisse',
                    'updated' => 'a modifié une transaction de caisse',
                    'deleted' => 'a supprimé une transaction de caisse',
                    'restored' => 'a restauré une transaction de caisse',
                    default => "a {$eventName} une transaction de caisse",
                };
            });
    }

    // Relations
    public function cashierSession()
    {
        return $this->belongsTo(CashierSession::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // Accesseurs
    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_CASH_PAYMENT => 'Paiement en espèces',
            self::TYPE_CARD_PAYMENT => 'Paiement par carte',
            self::TYPE_TRANSFER_PAYMENT => 'Paiement par virement',
            self::TYPE_CHECK_PAYMENT => 'Paiement par chèque',
            self::TYPE_MOBILE_PAYMENT => 'Paiement mobile',
            self::TYPE_CASH_REFUND => 'Remboursement en espèces',
            self::TYPE_CARD_REFUND => 'Remboursement par carte',
            self::TYPE_TRANSFER_REFUND => 'Remboursement par virement',
            self::TYPE_CHECK_REFUND => 'Remboursement par chèque',
            self::TYPE_MOBILE_REFUND => 'Remboursement mobile',
            self::TYPE_CASH_DEPOSIT => 'Dépôt en caisse',
            self::TYPE_CASH_WITHDRAWAL => 'Retrait de caisse',
            self::TYPE_ADJUSTMENT => 'Ajustement',
            self::TYPE_CORRECTION => 'Correction',
        ];

        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_COMPLETED => 'Complétée',
            self::STATUS_VERIFIED => 'Vérifiée',
            self::STATUS_CANCELLED => 'Annulée',
            self::STATUS_REVERSED => 'Inversée',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_COMPLETED => 'info',
            self::STATUS_VERIFIED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REVERSED => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getFormattedAmountAttribute()
    {
        $prefix = str_contains($this->type, 'refund') ? '-' : '';

        return $prefix.number_format($this->amount, 0, ',', ' ').' CFA';
    }

    public function getIsPaymentAttribute()
    {
        return str_contains($this->type, 'payment');
    }

    public function getIsRefundAttribute()
    {
        return str_contains($this->type, 'refund');
    }

    public function getIsDepositAttribute()
    {
        return str_contains($this->type, 'deposit');
    }

    public function getIsWithdrawalAttribute()
    {
        return str_contains($this->type, 'withdrawal');
    }

    public function getPaymentMethodAttribute()
    {
        if (str_contains($this->type, 'cash')) {
            return 'cash';
        }
        if (str_contains($this->type, 'card')) {
            return 'card';
        }
        if (str_contains($this->type, 'transfer')) {
            return 'transfer';
        }
        if (str_contains($this->type, 'check')) {
            return 'check';
        }
        if (str_contains($this->type, 'mobile')) {
            return 'mobile';
        }

        return 'other';
    }

    // Scopes
    public function scopePayments($query)
    {
        return $query->where('type', 'like', '%_payment');
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'like', '%_refund');
    }

    public function scopeDeposits($query)
    {
        return $query->where('type', 'like', '%_deposit');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'like', '%_withdrawal');
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('cashier_session_id', $sessionId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('type', 'like', $method.'_%');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Méthodes
    public function verify($userId, $notes = null)
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            return false;
        }

        $this->status = self::STATUS_VERIFIED;
        $this->verified_by = $userId;
        $this->verified_at = now();

        if ($notes) {
            $this->notes = ($this->notes ? $this->notes."\n" : '').
                'Vérifié le '.now()->format('d/m/Y H:i').': '.$notes;
        }

        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'transaction_id' => $this->id,
                'amount' => $this->amount,
                'type' => $this->type_label,
                'cashier_session_id' => $this->cashier_session_id,
            ])
            ->log('a vérifié une transaction de caisse');

        return true;
    }

    public function cancel($userId, $reason)
    {
        if (! in_array($this->status, [self::STATUS_PENDING, self::STATUS_COMPLETED])) {
            return false;
        }

        $oldStatus = $this->status;

        $this->status = self::STATUS_CANCELLED;
        $this->cancelled_by = $userId;
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_CANCELLED,
                'reason' => $reason,
                'amount' => $this->amount,
                'type' => $this->type,
            ])
            ->log('a annulé une transaction de caisse');

        return true;
    }

    public function reverse($userId, $reason)
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            return false;
        }

        // Créer une transaction inverse
        $reverseTransaction = self::create([
            'cashier_session_id' => $this->cashier_session_id,
            'transaction_id' => $this->transaction_id,
            'payment_id' => $this->payment_id,
            'type' => $this->type.'_reversed',
            'amount' => -$this->amount,
            'description' => 'Inversion: '.$this->description,
            'reference' => 'REV-'.$this->reference,
            'status' => self::STATUS_COMPLETED,
            'notes' => $reason,
            'created_by' => $userId,
            'metadata' => [
                'original_transaction_id' => $this->id,
                'reversal_reason' => $reason,
                'reversed_at' => now()->toDateTimeString(),
            ],
        ]);

        // Marquer l'original comme inversée
        $this->status = self::STATUS_REVERSED;
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'original_transaction' => $this->id,
                'reverse_transaction' => $reverseTransaction->id,
                'reason' => $reason,
                'amount' => $this->amount,
            ])
            ->log('a inversé une transaction de caisse');

        return $reverseTransaction;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cashierTransaction) {
            if (! $cashierTransaction->reference) {
                $cashierTransaction->reference = 'CTX-'.strtoupper(Str::random(8)).'-'.time();
            }

            if (! $cashierTransaction->status) {
                $cashierTransaction->status = self::STATUS_COMPLETED;
            }

            if (auth()->check() && ! $cashierTransaction->created_by) {
                $cashierTransaction->created_by = auth()->id();
            }
        });

        static::created(function ($cashierTransaction) {
            activity()
                ->causedBy(User::find($cashierTransaction->created_by))
                ->performedOn($cashierTransaction)
                ->withProperties([
                    'amount' => $cashierTransaction->amount,
                    'type' => $cashierTransaction->type_label,
                    'cashier_session_id' => $cashierTransaction->cashier_session_id,
                    'transaction_id' => $cashierTransaction->transaction_id,
                ])
                ->log('a créé une nouvelle transaction de caisse');
        });
    }

    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'type' => $this->type_label,
            'amount' => $this->formatted_amount,
            'status' => $this->status_label,
            'status_color' => $this->status_color,
            'description' => $this->description,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'created_by' => $this->createdBy->name ?? 'N/A',
            'session_code' => $this->cashierSession->session_code ?? 'N/A',
            'transaction_id' => $this->transaction_id,
            'payment_id' => $this->payment_id,
            'is_payment' => $this->is_payment ? 'Oui' : 'Non',
            'is_refund' => $this->is_refund ? 'Oui' : 'Non',
            'payment_method' => $this->payment_method,
            'metadata' => $this->metadata,
        ];
    }
}
