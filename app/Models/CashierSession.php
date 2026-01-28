<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashierSession extends Model
{
    use HasFactory, SoftDeletes;
    
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
        'shift_type'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'final_balance' => 'decimal:2',
        'theoretical_balance' => 'decimal:2',
        'balance_difference' => 'decimal:2',
    ];
    
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_VERIFIED = 'verified';
    
    const SHIFT_MORNING = 'morning';
    const SHIFT_EVENING = 'evening';
    const SHIFT_NIGHT = 'night';
    const SHIFT_FULL_DAY = 'full_day';
    
    // Relations
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
    
    // Méthodes pratiques
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }
    
    public function calculateTheoreticalBalance()
    {
        $totalPayments = $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
            
        return $this->initial_balance + $totalPayments;
    }
    
    public function getTotalRevenue()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');
    }
    
    public function getPaymentCount()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->count();
    }
    
    public function getTransactionCount()
    {
        return $this->transactions()->count();
    }
    
    // Scopes
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
}