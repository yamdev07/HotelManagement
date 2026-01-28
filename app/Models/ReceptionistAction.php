<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReceptionistAction extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'action_type',
        'action_subtype',
        'actionable_type',
        'actionable_id',
        'action_data',
        'before_state',
        'after_state',
        'ip_address',
        'user_agent',
        'notes',
        'performed_at'
    ];

    protected $casts = [
        'action_data' => 'array',
        'before_state' => 'array',
        'after_state' => 'array',
        'performed_at' => 'datetime'
    ];

    // Relations
    public function session(): BelongsTo
    {
        return $this->belongsTo(ReceptionistSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeType($query, $type)
    {
        return $query->where('action_type', $type);
    }

    public function scopeInSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('performed_at', '>=', now()->subHours($hours));
    }

    // Méthodes
    public function getActionDescription()
    {
        $descriptions = [
            'reservation' => [
                'create' => 'Création réservation',
                'update' => 'Modification réservation',
                'cancel' => 'Annulation réservation',
                'confirm' => 'Confirmation réservation'
            ],
            'checkin' => [
                'create' => 'Check-in client',
                'update' => 'Modification check-in',
                'early' => 'Check-in anticipé',
                'late' => 'Check-in tardif'
            ],
            'checkout' => [
                'create' => 'Check-out client',
                'update' => 'Modification check-out',
                'early' => 'Check-out anticipé',
                'late' => 'Check-out tardif'
            ],
            'payment' => [
                'create' => 'Enregistrement paiement',
                'update' => 'Modification paiement',
                'refund' => 'Remboursement',
                'partial' => 'Paiement partiel'
            ],
            'customer' => [
                'create' => 'Création client',
                'update' => 'Modification client',
                'delete' => 'Suppression client'
            ],
            'room' => [
                'status_change' => 'Changement statut chambre',
                'assignment' => 'Assignation chambre',
                'transfer' => 'Transfert chambre'
            ]
        ];

        $type = $descriptions[$this->action_type] ?? [];
        return $type[$this->action_subtype] ?? "{$this->action_type} - {$this->action_subtype}";
    }

    public function getDetails()
    {
        $details = $this->getActionDescription();
        
        if ($this->actionable) {
            $details .= " (ID: {$this->actionable_id})";
        }
        
        if ($this->notes) {
            $details .= " - {$this->notes}";
        }
        
        return $details;
    }
}