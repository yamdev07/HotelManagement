<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'room_id',
        'check_in',
        'check_out',
        'status', // 'active', 'completed', 'cancelled', 'expired'
        'person_count',
        'total_price',
        'total_payment',
        'cancelled_at',
        'cancelled_by',
        'cancel_reason',
        'notes'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2',
        'total_payment' => 'decimal:2',
    ];

    // Constantes pour les statuts
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Relation avec l'utilisateur (créateur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le client
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relation avec la chambre
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relation avec les paiements (AU SINGULIER - garder pour compatibilité)
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation avec les paiements (AU PLURIEL - nouvelle relation)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation avec l'utilisateur qui a annulé
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Scope pour les transactions actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope pour les transactions annulées
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope pour les transactions expirées
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope pour les transactions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Calculer le prix total
     */
    public function getTotalPrice()
    {
        // Si le prix total est déjà stocké, l'utiliser
        if ($this->total_price) {
            return $this->total_price;
        }

        // Sinon calculer
        $day = Helper::getDateDifference($this->check_in, $this->check_out);
        $room_price = $this->room->price;

        $total = $room_price * $day;
        
        // Stocker pour éviter de recalculer
        $this->total_price = $total;
        $this->save();

        return $total;
    }

    /**
     * Obtenir la différence de dates avec pluriel
     */
    public function getDateDifferenceWithPlural()
    {
        $day = Helper::getDateDifference($this->check_in, $this->check_out);
        $plural = Str::plural('Day', $day);

        return $day.' '.$plural;
    }

    /**
     * Obtenir le nombre de nuits
     */
    public function getNightsAttribute()
    {
        return Helper::getDateDifference($this->check_in, $this->check_out);
    }

    /**
     * Calculer le total des paiements
     */
    public function getTotalPayment()
    {
        // Si le total des paiements est déjà stocké, l'utiliser
        if ($this->total_payment) {
            return $this->total_payment;
        }

        // Calculer le total des paiements COMPLÉTÉS uniquement
        $totalPayment = $this->payments()
            ->where('status', Payment::STATUS_COMPLETED)
            ->sum('amount');

        // Stocker pour éviter de recalculer
        $this->total_payment = $totalPayment;
        $this->save();

        return $totalPayment;
    }

    /**
     * Calculer le montant restant à payer
     */
    public function getRemainingPayment()
    {
        return $this->getTotalPrice() - $this->getTotalPayment();
    }

    /**
     * Calculer le taux de paiement
     */
    public function getPaymentRate()
    {
        $totalPrice = $this->getTotalPrice();
        
        if ($totalPrice > 0) {
            return ($this->getTotalPayment() / $totalPrice) * 100;
        }
        
        return 0;
    }

    /**
     * Vérifier si la transaction est complètement payée
     */
    public function isFullyPaid()
    {
        return $this->getRemainingPayment() <= 0;
    }

    /**
     * Vérifier si la transaction est annulée
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si la transaction est expirée
     */
    public function isExpired()
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }
        
        // Vérifier si la date de check_out est passée
        return now()->greaterThan($this->check_out);
    }

    /**
     * Vérifier si la transaction est active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }

    /**
     * Annuler la transaction
     */
    public function cancel($userId, $reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => $userId,
            'cancel_reason' => $reason
        ]);

        // Libérer la chambre
        $this->room()->update(['status' => 'available']);

        // Annuler tous les paiements en attente
        $this->payments()
            ->where('status', Payment::STATUS_PENDING)
            ->update([
                'status' => Payment::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => $userId,
                'cancel_reason' => 'Transaction annulée: ' . ($reason ?? 'Non spécifié')
            ]);

        return true;
    }

    /**
     * Restaurer une transaction annulée
     */
    public function restoreTransaction()
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancel_reason' => null
        ]);

        // Réserver la chambre
        $this->room()->update(['status' => 'occupied']);

        return true;
    }

    /**
     * Marquer comme expirée
     */
    public function markAsExpired()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
            'cancelled_at' => now()
        ]);

        // Libérer la chambre
        $this->room()->update(['status' => 'available']);

        return true;
    }

    /**
     * Recalculer et mettre à jour le statut des paiements
     */
    public function updatePaymentStatus()
    {
        $totalPaid = $this->getTotalPayment();
        $this->total_payment = $totalPaid;
        $this->save();

        return $totalPaid;
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusTextAttribute()
    {
        if ($this->isCancelled()) {
            return 'Annulée';
        } elseif ($this->isExpired()) {
            return 'Expirée';
        } elseif ($this->isFullyPaid()) {
            return 'Payée';
        } else {
            return 'Active';
        }
    }

    /**
     * Obtenir la classe CSS pour le statut
     */
    public function getStatusClassAttribute()
    {
        if ($this->isCancelled()) {
            return 'danger';
        } elseif ($this->isExpired()) {
            return 'secondary';
        } elseif ($this->isFullyPaid()) {
            return 'success';
        } else {
            return 'primary';
        }
    }

    /**
     * Vérifier si la transaction peut être annulée
     */
    public function canBeCancelled()
    {
        return $this->isActive() && !$this->isCancelled() && !$this->isExpired();
    }

    /**
     * Vérifier si la transaction peut être restaurée
     */
    public function canBeRestored()
    {
        return $this->isCancelled() && !$this->isExpired();
    }

    /**
     * Calculer l'acompte minimum
     */
    public function getMinimumDownPayment()
    {
        $dayDifference = Helper::getDateDifference($this->check_in, $this->check_out);

        return ($this->room->price * $dayDifference) * 0.15;
    }

    /**
     * Formater le prix total
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->getTotalPrice(), 0, ',', ' ') . ' CFA';
    }

    /**
     * Formater le total payé
     */
    public function getFormattedTotalPaymentAttribute()
    {
        return number_format($this->getTotalPayment(), 0, ',', ' ') . ' CFA';
    }

    /**
     * Formater le montant restant
     */
    public function getFormattedRemainingPaymentAttribute()
    {
        return number_format($this->getRemainingPayment(), 0, ',', ' ') . ' CFA';
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Lors de la création, calculer et stocker le prix total
        static::creating(function ($transaction) {
            if (!$transaction->total_price) {
                $day = Helper::getDateDifference($transaction->check_in, $transaction->check_out);
                $room_price = $transaction->room->price ?? 0;
                $transaction->total_price = $room_price * $day;
            }
            
            // Définir le statut par défaut
            if (!$transaction->status) {
                $transaction->status = self::STATUS_ACTIVE;
            }
        });

        // Lors de la sauvegarde, réserver la chambre
        static::saved(function ($transaction) {
            if ($transaction->isActive()) {
                $transaction->room()->update(['status' => 'occupied']);
            }
        });

        // Lorsqu'une transaction est annulée ou expirée, libérer la chambre
        static::updated(function ($transaction) {
            if ($transaction->isDirty('status') && 
                ($transaction->isCancelled() || $transaction->isExpired())) {
                $transaction->room()->update(['status' => 'available']);
            }
        });
    }
}