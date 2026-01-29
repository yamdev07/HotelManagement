<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionActivity extends Model
{
    protected $fillable = [
        'cashier_session_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'data',
        'ip_address',
        'user_agent'
    ];
    
    protected $casts = [
        'data' => 'array',
    ];
    
    /**
     * Relation avec la session de caisse
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(CashierSession::class, 'cashier_session_id');
    }
    
    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relation avec l'entité concernée (polymorphique)
     */
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }
    
    /**
     * Formater la date de façon lisible
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i:s');
    }
    
    /**
     * Obtenir l'icône correspondant à l'action
     */
    public function getActionIconAttribute()
    {
        $icons = [
            'payment_created' => 'fa-money-bill-wave',
            'payment_updated' => 'fa-money-bill-wave',
            'payment_deleted' => 'fa-money-bill-wave',
            'booking_created' => 'fa-calendar-plus',
            'booking_updated' => 'fa-calendar-edit',
            'booking_deleted' => 'fa-calendar-times',
            'checkin' => 'fa-sign-in-alt',
            'checkout' => 'fa-sign-out-alt',
            'room_assigned' => 'fa-door-open',
            'room_cleaned' => 'fa-broom',
            'session_started' => 'fa-play-circle',
            'session_closed' => 'fa-stop-circle',
            'transaction_created' => 'fa-exchange-alt',
            'refund_created' => 'fa-undo-alt',
            'invoice_printed' => 'fa-print',
            'report_generated' => 'fa-chart-bar',
        ];
        
        return $icons[$this->action] ?? 'fa-circle';
    }
    
    /**
     * Obtenir la couleur de l'action
     */
    public function getActionColorAttribute()
    {
        $colors = [
            'payment_created' => 'success',
            'payment_updated' => 'warning',
            'payment_deleted' => 'danger',
            'booking_created' => 'primary',
            'booking_updated' => 'info',
            'booking_deleted' => 'danger',
            'checkin' => 'success',
            'checkout' => 'warning',
            'room_assigned' => 'info',
            'room_cleaned' => 'secondary',
            'session_started' => 'success',
            'session_closed' => 'danger',
            'transaction_created' => 'primary',
            'refund_created' => 'warning',
            'invoice_printed' => 'secondary',
            'report_generated' => 'info',
        ];
        
        return $colors[$this->action] ?? 'secondary';
    }

    public function activities()
    {
        return $this->hasMany(SessionActivity::class);
    }

    public function recentActivities($limit = 20)
    {
        return $this->activities()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}