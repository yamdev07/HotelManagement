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
        'closing_notes'
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
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'cashier_session_id');
    }
    
    public function getDurationAttribute()
    {
        if (!$this->end_time) return null;
        return $this->start_time->diff($this->end_time);
    }
    
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return 'En cours';
        
        $hours = $this->duration->h;
        $minutes = $this->duration->i;
        
        return "{$hours}h {$minutes}min";
    }
    
    public function getFormattedInitialBalanceAttribute()
    {
        return number_format($this->initial_balance, 2, ',', ' ') . ' FCFA';
    }
    
    public function getFormattedCurrentBalanceAttribute()
    {
        return number_format($this->current_balance, 2, ',', ' ') . ' FCFA';
    }
    
    public function getFormattedFinalBalanceAttribute()
    {
        return $this->final_balance 
            ? number_format($this->final_balance, 2, ',', ' ') . ' FCFA'
            : 'N/A';
    }
}