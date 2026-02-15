<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReceptionistSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_code',
        'login_time',
        'logout_time',
        'login_ip',
        'login_device',
        'login_location',
        'session_status',
        'total_transactions_amount',
        'reservations_count',
        'checkins_count',
        'checkouts_count',
        'payments_count',
        'customer_creations',
        'session_summary',
        'cash_handled',
        'card_handled',
        'other_payments_handled',
        'performance_metrics',
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
        'performance_metrics' => 'array',
        'total_transactions_amount' => 'decimal:2',
        'cash_handled' => 'decimal:2',
        'card_handled' => 'decimal:2',
        'other_payments_handled' => 'decimal:2',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ReceptionistAction::class, 'session_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('session_status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('login_time', today());
    }

    // Méthodes
    public function getDuration()
    {
        if (! $this->logout_time) {
            return $this->login_time->diff(now());
        }

        return $this->login_time->diff($this->logout_time);
    }

    public function getDurationFormatted()
    {
        $duration = $this->getDuration();

        return $duration->format('%hh %im');
    }

    public function getTotalHandled()
    {
        return $this->cash_handled + $this->card_handled + $this->other_payments_handled;
    }

    public function getProductivityScore()
    {
        $totalActions = $this->reservations_count + $this->checkins_count +
                       $this->checkouts_count + $this->payments_count;

        $durationHours = $this->getDuration()->h + ($this->getDuration()->i / 60);

        if ($durationHours <= 0) {
            return 0;
        }

        return round($totalActions / $durationHours, 2);
    }

    public function generateSessionSummary()
    {
        $summary = "Session #{$this->session_code}\n";
        $summary .= "Réceptionniste: {$this->user->name}\n";
        $summary .= "Période: {$this->login_time->format('d/m/Y H:i')}";

        if ($this->logout_time) {
            $summary .= " - {$this->logout_time->format('H:i')} ";
            $summary .= "({$this->getDurationFormatted()})\n";
        } else {
            $summary .= " - En cours\n";
        }

        $summary .= "\n📊 ACTIVITÉS DE LA SESSION:\n";
        $summary .= "• Réservations: {$this->reservations_count}\n";
        $summary .= "• Check-ins: {$this->checkins_count}\n";
        $summary .= "• Check-outs: {$this->checkouts_count}\n";
        $summary .= "• Paiements traités: {$this->payments_count}\n";
        $summary .= "• Nouveaux clients: {$this->customer_creations}\n";

        $summary .= "\n💰 FINANCES:\n";
        $summary .= '• Montant total transactions: '.number_format($this->total_transactions_amount, 2, ',', ' ')." €\n";
        $summary .= '• Espèces: '.number_format($this->cash_handled, 2, ',', ' ')." €\n";
        $summary .= '• Carte: '.number_format($this->card_handled, 2, ',', ' ')." €\n";
        $summary .= '• Autres: '.number_format($this->other_payments_handled, 2, ',', ' ')." €\n";
        $summary .= '• Total encaissé: '.number_format($this->getTotalHandled(), 2, ',', ' ')." €\n";

        $summary .= "\n📈 PERFORMANCE:\n";
        $summary .= "• Score productivité: {$this->getProductivityScore()} actions/heure\n";
        $summary .= '• Valeur moyenne par transaction: '.
                   ($this->reservations_count > 0 ?
                    number_format($this->total_transactions_amount / $this->reservations_count, 2, ',', ' ').' €' :
                    '0 €')."\n";

        return $summary;
    }
}
