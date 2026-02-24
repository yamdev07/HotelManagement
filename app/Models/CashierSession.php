<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CashierSession extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'initial_balance',
        'current_balance',
        'final_balance',
        'theoretical_balance',
        'balance_difference',
        'start_time',
        'end_time',
        'status',
        'notes',
        'closing_notes',
        'closed_by',
        'verified_by',
        'terminal_id',
        'shift_type',
        'total_cash',
        'total_card',
        'total_transfer',
        'total_mobile_money',
        'total_other',
        'total_refunds',
        'opening_note',
        'expected_balance',
        'shortage_amount',
        'excess_amount',
        'declared_amount',
        'actual_amount',
        'verified_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'final_balance' => 'decimal:2',
        'theoretical_balance' => 'decimal:2',
        'balance_difference' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_transfer' => 'decimal:2',
        'total_mobile_money' => 'decimal:2',
        'total_other' => 'decimal:2',
        'total_refunds' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'shortage_amount' => 'decimal:2',
        'excess_amount' => 'decimal:2',
        'declared_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'duration',
        'formatted_start_time',
        'formatted_end_time',
        'formatted_initial_balance',
        'formatted_final_balance',
        'formatted_current_balance',
        'formatted_theoretical_balance',
        'formatted_balance_difference',
        'formatted_total_revenue',
        'formatted_expected_balance',
        'formatted_shortage_amount',
        'formatted_excess_amount',
        'status_label',
        'status_color',
        'status_icon',
        'shift_label',
        'shift_color',
        'is_balanced',
        'has_discrepancy',
        'discrepancy_amount',
        'formatted_discrepancy_amount',
        'total_transactions',
        'total_payments',
        'user_name',
        'closing_user_name',
        'verification_user_name',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_VERIFIED = 'verified';
    const STATUS_ABANDONED = 'abandoned';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_LOCKED = 'locked';

    const SHIFT_MORNING = 'morning';
    const SHIFT_EVENING = 'evening';
    const SHIFT_NIGHT = 'night';
    const SHIFT_FULL_DAY = 'full_day';
    const SHIFT_CUSTOM = 'custom';

    const ALLOWED_DIFFERENCE = 1000; // Tolérance de 1000 CFA

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
                    'created' => 'a ouvert une session de caisse',
                    'updated' => 'a modifié une session de caisse',
                    'deleted' => 'a supprimé une session de caisse',
                    'restored' => 'a restauré une session de caisse',
                    default => "a {$eventName} une session de caisse",
                };
            });
    }

    // ==================== RELATIONS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'cashier_session_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cashier_session_id');
    }

    public function sessionActivities()
    {
        return $this->hasMany(SessionActivity::class, 'cashier_session_id');
    }

    // ==================== MÉTHODES DE VÉRIFICATION ====================

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isPendingReview()
    {
        return $this->status === self::STATUS_PENDING_REVIEW;
    }

    public function isVerified()
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isAbandoned()
    {
        return $this->status === self::STATUS_ABANDONED;
    }

    public function isSuspended()
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function canBeClosed()
    {
        return $this->isActive() || $this->isSuspended();
    }

    public function canBeVerified()
    {
        return $this->isClosed() || $this->isPendingReview();
    }

    public function canBeReopened()
    {
        return $this->isClosed() && ! $this->isVerified();
    }

    // ==================== MÉTHODES DE CALCUL ====================

    public function calculateTheoreticalBalance()
    {
        $totalPayments = $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');

        $totalRefunds = $this->payments()
            ->whereIn('status', [Payment::STATUS_REFUNDED, Payment::STATUS_PARTIALLY_REFUNDED])
            ->sum('amount');

        return $this->initial_balance + $totalPayments - $totalRefunds;
    }

    public function calculateTotalsByMethod()
    {
        $methods = Payment::getPaymentMethods();
        $totals = [];

        foreach ($methods as $method => $details) {
            $totals[$method] = $this->payments()
                ->where('status', Payment::STATUS_COMPLETED)
                ->where('payment_method', $method)
                ->sum('amount');
        }

        return $totals;
    }

    public function calculateCurrentBalance()
    {
        $theoretical = $this->calculateTheoreticalBalance();
        $this->current_balance = $theoretical;
        $this->saveQuietly();

        return $theoretical;
    }

    public function updateBalance()
    {
        $oldBalance = $this->current_balance;
        $newBalance = $this->calculateTheoreticalBalance();

        $this->current_balance = $newBalance;
        $this->saveQuietly();

        if ($oldBalance != $newBalance) {
            activity()
                ->performedOn($this)
                ->withProperties([
                    'old_balance' => $oldBalance,
                    'new_balance' => $newBalance,
                    'difference' => $newBalance - $oldBalance,
                ])
                ->log('a recalculé le solde de la session');
        }

        return $newBalance;
    }

    public function getTotalRevenue()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }

    public function getTotalRefunds()
    {
        return $this->payments()
            ->whereIn('status', [Payment::STATUS_REFUNDED, Payment::STATUS_PARTIALLY_REFUNDED])
            ->sum('amount');
    }

    public function getTotalByMethod($method)
    {
        return $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->where('payment_method', $method)
            ->sum('amount');
    }

    public function getPaymentCount()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->count();
    }

    public function getRefundCount()
    {
        return $this->payments()
            ->whereIn('status', [Payment::STATUS_REFUNDED, Payment::STATUS_PARTIALLY_REFUNDED])
            ->count();
    }

    public function getTransactionCount()
    {
        return $this->transactions()->count();
    }

    public function logActivity($action, $description, $entity = null, $data = [])
    {
        return SessionActivity::create([
            'cashier_session_id' => $this->id,
            'user_id' => auth()->id() ?? $this->user_id,
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity ? $entity->id : null,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // ==================== ACTIONS ====================

    public function openSession($userId, $initialBalance = 0, $notes = '')
    {
        $this->user_id = $userId;
        $this->initial_balance = $initialBalance;
        $this->current_balance = $initialBalance;
        $this->status = self::STATUS_ACTIVE;
        $this->start_time = now();
        $this->notes = $notes;
        $this->shift_type = $this->determineShiftType();
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'initial_balance' => $initialBalance,
                'shift' => $this->shift_label,
                'terminal_id' => $this->terminal_id,
                'notes' => $notes,
            ])
            ->log('a ouvert une session de caisse');

        $this->logActivity('session_started', 'Session ouverte', $this, [
            'initial_balance' => $initialBalance,
            'shift' => $this->shift_label,
        ]);

        return $this;
    }

    public function closeSession($userId, $finalBalance = null, $closingNotes = '')
    {
        if (! $this->canBeClosed()) {
            return false;
        }

        $finalBalance = $finalBalance ?? $this->current_balance;
        $theoreticalBalance = $this->calculateTheoreticalBalance();
        $difference = $finalBalance - $theoreticalBalance;

        $this->final_balance = $finalBalance;
        $this->theoretical_balance = $theoreticalBalance;
        $this->balance_difference = $difference;
        $this->status = self::STATUS_CLOSED;
        $this->end_time = now();
        $this->closed_by = $userId;
        $this->closing_notes = $closingNotes;

        // Calculer les totaux par méthode
        $this->calculatePaymentTotals();

        // Déterminer s'il y a un écart
        if (abs($difference) > self::ALLOWED_DIFFERENCE) {
            if ($difference > 0) {
                $this->excess_amount = $difference;
            } else {
                $this->shortage_amount = abs($difference);
            }
        }

        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'final_balance' => $finalBalance,
                'theoretical_balance' => $theoreticalBalance,
                'difference' => $difference,
                'total_revenue' => $this->getTotalRevenue(),
                'payment_count' => $this->getPaymentCount(),
                'transaction_count' => $this->getTransactionCount(),
                'closing_notes' => $closingNotes,
            ])
            ->log('a fermé une session de caisse');

        $this->logActivity('session_closed', 'Session fermée', $this, [
            'final_balance' => $finalBalance,
            'difference' => $difference,
            'duration' => $this->duration ? $this->duration->format('%h heures %i minutes') : 'N/A',
        ]);

        return $this;
    }

    public function verifySession($userId, $notes = '')
    {
        if (! $this->canBeVerified()) {
            return false;
        }

        $oldStatus = $this->status;

        $this->status = self::STATUS_VERIFIED;
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_VERIFIED,
                'balance_difference' => $this->balance_difference,
                'is_balanced' => $this->is_balanced,
                'verification_notes' => $notes,
            ])
            ->log('a vérifié une session de caisse');

        $this->logActivity('session_verified', 'Session vérifiée', $this, [
            'verified_by' => $this->verification_user_name,
            'balance_status' => $this->is_balanced ? 'Équilibrée' : 'Déséquilibrée',
        ]);

        return $this;
    }

    public function suspendSession($userId, $reason = '')
    {
        if (! $this->isActive()) {
            return false;
        }

        $oldStatus = $this->status;

        $this->status = self::STATUS_SUSPENDED;
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_SUSPENDED,
                'reason' => $reason,
                'suspended_at' => now(),
            ])
            ->log('a suspendu une session de caisse');

        $this->logActivity('session_suspended', 'Session suspendue', $this, [
            'reason' => $reason,
        ]);

        return $this;
    }

    public function reopenSession($userId, $reason = '')
    {
        if (! $this->canBeReopened()) {
            return false;
        }

        $oldStatus = $this->status;

        $this->status = self::STATUS_ACTIVE;
        $this->end_time = null;
        $this->closed_by = null;
        $this->closing_notes = null;
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_ACTIVE,
                'reason' => $reason,
            ])
            ->log('a rouvert une session de caisse');

        $this->logActivity('session_reopened', 'Session rouverte', $this, [
            'reason' => $reason,
        ]);

        return $this;
    }

    public function abandonSession($userId, $reason = '')
    {
        if (! $this->isActive() && ! $this->isSuspended()) {
            return false;
        }

        $oldStatus = $this->status;

        $this->status = self::STATUS_ABANDONED;
        $this->end_time = now();
        $this->closed_by = $userId;
        $this->closing_notes = 'Session abandonnée: '.$reason;
        $this->save();

        activity()
            ->causedBy(User::find($userId))
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_ABANDONED,
                'reason' => $reason,
                'abandoned_at' => now(),
            ])
            ->log('a abandonné une session de caisse');

        $this->logActivity('session_abandoned', 'Session abandonnée', $this, [
            'reason' => $reason,
        ]);

        return $this;
    }

    private function determineShiftType()
    {
        $hour = now()->hour;

        if ($hour >= 6 && $hour < 14) {
            return self::SHIFT_MORNING;
        } elseif ($hour >= 14 && $hour < 22) {
            return self::SHIFT_EVENING;
        } else {
            return self::SHIFT_NIGHT;
        }
    }

    private function calculatePaymentTotals()
    {
        $methods = Payment::getPaymentMethods();

        foreach ($methods as $method => $details) {
            $total = $this->payments()
                ->where('status', Payment::STATUS_COMPLETED)
                ->where('payment_method', $method)
                ->sum('amount');

            $field = 'total_'.strtolower(str_replace('_', '', $method));

            if (in_array($field, $this->fillable)) {
                $this->$field = $total;
            }
        }
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeByShift($query, $shift)
    {
        if ($shift) {
            return $query->where('shift_type', $shift);
        }

        return $query;
    }

    public function scopeWithDiscrepancy($query)
    {
        return $query->whereRaw('ABS(balance_difference) > ?', [self::ALLOWED_DIFFERENCE]);
    }

    public function scopeBalanced($query)
    {
        return $query->whereRaw('ABS(balance_difference) <= ?', [self::ALLOWED_DIFFERENCE]);
    }

    public function scopeUnverified($query)
    {
        return $query->where('status', self::STATUS_CLOSED)
            ->orWhere('status', self::STATUS_PENDING_REVIEW);
    }

    // ==================== ACCESSORS ====================

    public function getDurationAttribute()
    {
        if (! $this->start_time) {
            return null;
        }

        $endTime = $this->end_time ?? now();

        return $this->start_time->diff($endTime);
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('d/m/Y H:i') : 'Non démarrée';
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('d/m/Y H:i') : 'En cours';
    }

    public function getFormattedInitialBalanceAttribute()
    {
        return number_format($this->initial_balance, 0, ',', ' ').' CFA';
    }

    public function getFormattedFinalBalanceAttribute()
    {
        return number_format($this->final_balance ?? 0, 0, ',', ' ').' CFA';
    }

    public function getFormattedCurrentBalanceAttribute()
    {
        return number_format($this->current_balance ?? 0, 0, ',', ' ').' CFA';
    }

    public function getFormattedTheoreticalBalanceAttribute()
    {
        $balance = $this->theoretical_balance ?? $this->calculateTheoreticalBalance();

        return number_format($balance, 0, ',', ' ').' CFA';
    }

    public function getFormattedBalanceDifferenceAttribute()
    {
        $difference = $this->balance_difference ?? 0;

        return number_format($difference, 0, ',', ' ').' CFA';
    }

    public function getFormattedTotalRevenueAttribute()
    {
        return number_format($this->getTotalRevenue(), 0, ',', ' ').' CFA';
    }

    public function getFormattedExpectedBalanceAttribute()
    {
        return number_format($this->expected_balance ?? 0, 0, ',', ' ').' CFA';
    }

    public function getFormattedShortageAmountAttribute()
    {
        return number_format($this->shortage_amount ?? 0, 0, ',', ' ').' CFA';
    }

    public function getFormattedExcessAmountAttribute()
    {
        return number_format($this->excess_amount ?? 0, 0, ',', ' ').' CFA';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Fermée',
            self::STATUS_PENDING_REVIEW => 'En attente de vérification',
            self::STATUS_VERIFIED => 'Vérifiée',
            self::STATUS_ABANDONED => 'Abandonnée',
            self::STATUS_SUSPENDED => 'Suspendue',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_ACTIVE => 'success',
            self::STATUS_CLOSED => 'danger',
            self::STATUS_PENDING_REVIEW => 'warning',
            self::STATUS_VERIFIED => 'info',
            self::STATUS_ABANDONED => 'secondary',
            self::STATUS_SUSPENDED => 'dark',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusIconAttribute()
    {
        $icons = [
            self::STATUS_ACTIVE => 'fa-play-circle',
            self::STATUS_CLOSED => 'fa-stop-circle',
            self::STATUS_PENDING_REVIEW => 'fa-clock',
            self::STATUS_VERIFIED => 'fa-check-circle',
            self::STATUS_ABANDONED => 'fa-times-circle',
            self::STATUS_SUSPENDED => 'fa-pause-circle',
        ];

        return $icons[$this->status] ?? 'fa-circle';
    }

    public function getShiftLabelAttribute()
    {
        $shifts = [
            self::SHIFT_MORNING => 'Matin',
            self::SHIFT_EVENING => 'Soir',
            self::SHIFT_NIGHT => 'Nuit',
            self::SHIFT_FULL_DAY => 'Journée complète',
            self::SHIFT_CUSTOM => 'Personnalisé',
        ];

        return $shifts[$this->shift_type] ?? ucfirst($this->shift_type);
    }

    public function getShiftColorAttribute()
    {
        $colors = [
            self::SHIFT_MORNING => 'info',
            self::SHIFT_EVENING => 'warning',
            self::SHIFT_NIGHT => 'dark',
            self::SHIFT_FULL_DAY => 'success',
            self::SHIFT_CUSTOM => 'secondary',
        ];

        return $colors[$this->shift_type] ?? 'secondary';
    }

    public function getIsBalancedAttribute()
    {
        $difference = abs($this->balance_difference ?? 0);

        return $difference <= self::ALLOWED_DIFFERENCE;
    }

    public function getHasDiscrepancyAttribute()
    {
        return ! $this->is_balanced;
    }

    public function getDiscrepancyAmountAttribute()
    {
        return $this->balance_difference ?? 0;
    }

    public function getFormattedDiscrepancyAmountAttribute()
    {
        $amount = abs($this->discrepancy_amount);

        return number_format($amount, 0, ',', ' ').' CFA';
    }

    public function getTotalTransactionsAttribute()
    {
        return $this->getTransactionCount();
    }

    public function getTotalPaymentsAttribute()
    {
        return $this->getPaymentCount();
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'Utilisateur inconnu';
    }

    public function getClosingUserNameAttribute()
    {
        return $this->closedByUser ? $this->closedByUser->name : 'Non fermée';
    }

    public function getVerificationUserNameAttribute()
    {
        return $this->verifiedByUser ? $this->verifiedByUser->name : 'Non vérifiée';
    }

    public function getStatsAttribute()
    {
        return [
            'revenue_total' => $this->getTotalRevenue(),
            'revenue_formatted' => $this->formatted_total_revenue,
            'payment_count' => $this->getPaymentCount(),
            'refund_count' => $this->getRefundCount(),
            'refund_total' => $this->getTotalRefunds(),
            'transaction_count' => $this->getTransactionCount(),
            'duration' => $this->duration ?
                $this->duration->format('%h heures %i minutes') : 'En cours',
            'average_payment' => $this->getPaymentCount() > 0 ?
                $this->getTotalRevenue() / $this->getPaymentCount() : 0,
            'balance_status' => $this->is_balanced ? 'Équilibrée' : 'Déséquilibrée',
            'discrepancy' => $this->formatted_discrepancy_amount,
            'payment_methods' => $this->calculateTotalsByMethod(),
        ];
    }

    public function getHistoryAttribute()
    {
        $activities = $this->cashierActivities()->orderBy('created_at', 'desc')->get();
        $sessionActivities = $this->sessionActivities()->orderBy('created_at', 'desc')->get();
        $payments = $this->payments()->with('transaction.customer')->get();

        return [
            'activities' => $activities,
            'session_activities' => $sessionActivities,
            'payments' => $payments,
            'transactions' => $this->transactions()->with('customer', 'room')->get(),
            'stats' => $this->stats,
        ];
    }

    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'user' => $this->user_name,
            'status' => $this->status_label,
            'status_color' => $this->status_color,
            'shift' => $this->shift_label,
            'start_time' => $this->formatted_start_time,
            'end_time' => $this->formatted_end_time,
            'duration' => $this->duration ?
                $this->duration->format('%h heures %i minutes') : 'En cours',
            'initial_balance' => $this->formatted_initial_balance,
            'final_balance' => $this->formatted_final_balance,
            'theoretical_balance' => $this->formatted_theoretical_balance,
            'balance_difference' => $this->formatted_balance_difference,
            'revenue' => $this->formatted_total_revenue,
            'payment_count' => $this->payment_count,
            'transaction_count' => $this->transaction_count,
            'is_balanced' => $this->is_balanced ? 'Oui' : 'Non',
            'closing_user' => $this->closing_user_name,
            'verification_user' => $this->verification_user_name,
            'terminal' => $this->terminal_id ?? 'N/A',
        ];
    }

    // ==================== PROTECTION CONTRE LA MODIFICATION DE START_TIME ====================

    /**
     * Surcharge de la méthode save pour protéger start_time contre les modifications
     */
    public function save(array $options = [])
    {
        // Si c'est une mise à jour (session existe déjà)
        if ($this->exists) {
            $original = $this->getOriginal('start_time');
            
            // Vérifier si quelqu'un essaie de modifier start_time
            if ($original && $this->start_time != $original) {
                \Log::warning('🚨 TENTATIVE DE MODIFICATION DE START_TIME BLOQUÉE', [
                    'session_id' => $this->id,
                    'original' => $original->format('Y-m-d H:i:s'),
                    'tentative' => $this->start_time ? $this->start_time->format('Y-m-d H:i:s') : 'null',
                    'user_id' => auth()->id(),
                    'url' => request()->fullUrl()
                ]);
                
                // Restaurer la valeur originale
                $this->start_time = $original;
            }
        }
        
        return parent::save($options);
    }

    /**
     * Surcharge de la méthode update pour empêcher l'écrasement de start_time
     */
    public function update(array $attributes = [], array $options = [])
    {
        // Si on essaie de mettre à jour start_time, on le retire du tableau
        if (isset($attributes['start_time'])) {
            \Log::warning('🚨 TENTATIVE DE MODIFICATION DE START_TIME VIA update()', [
                'session_id' => $this->id,
                'start_time_proposed' => $attributes['start_time'],
                'user_id' => auth()->id()
            ]);
            
            unset($attributes['start_time']);
        }
        
        return parent::update($attributes, $options);
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

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (! $session->status) {
                $session->status = self::STATUS_ACTIVE;
            }

            if (! $session->start_time) {
                $session->start_time = now(); // ✅ Défini UNE SEULE fois à la création
            }

            if (! $session->shift_type) {
                $session->shift_type = (new self)->determineShiftType();
            }
        });

        static::created(function ($session) {
            activity()
                ->causedBy($session->user)
                ->performedOn($session)
                ->withProperties([
                    'session_id' => $session->id,
                    'initial_balance' => $session->initial_balance,
                    'user' => $session->user_name,
                    'shift' => $session->shift_label,
                ])
                ->log('a créé une nouvelle session de caisse');
        });

        static::updating(function ($session) {
            $dirty = $session->getDirty();
            $original = $session->getOriginal();
            
            // Log de toutes les modifications (pour débogage)
            if (!empty($dirty)) {
                \Log::info('🔄 Mise à jour session', [
                    'session_id' => $session->id,
                    'modified_fields' => array_keys($dirty),
                    'old_start_time' => isset($original['start_time']) ? $original['start_time']->format('Y-m-d H:i:s') : null,
                    'new_start_time' => isset($dirty['start_time']) ? $session->start_time->format('Y-m-d H:i:s') : 'non modifié',
                    'old_balance' => $original['current_balance'] ?? null,
                    'new_balance' => $dirty['current_balance'] ?? 'non modifié'
                ]);
            }
        });

        static::updated(function ($session) {
            if ($session->isDirty('status')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($session)
                    ->withProperties([
                        'old_status' => $session->getOriginal('status'),
                        'new_status' => $session->status,
                        'session_id' => $session->id,
                    ])
                    ->log('a changé le statut de la session');
            }
        });
    }
}