<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'room_id',
        'check_in',
        'check_out',
        'status', // 'reservation', 'active', 'completed', 'cancelled', 'no_show'
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

    // Constantes pour les statuts (NOUVEAUX)
    const STATUS_RESERVATION = 'reservation';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

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
     * Relation avec les paiements
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relation avec les paiements (alias)
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
     * Scope pour les réservations (futures)
     */
    public function scopeReservation($query)
    {
        return $query->where('status', self::STATUS_RESERVATION);
    }

    /**
     * Scope pour les transactions actives (dans l'hôtel)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope pour les transactions terminées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope pour les transactions annulées
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope pour les no show
     */
    public function scopeNoShow($query)
    {
        return $query->where('status', self::STATUS_NO_SHOW);
    }

    /**
     * Vérifier si c'est une réservation (pas encore arrivé)
     */
    public function isReservation()
    {
        return $this->status === self::STATUS_RESERVATION;
    }

    /**
     * Vérifier si le client est dans l'hôtel
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Vérifier si le séjour est terminé
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifier si annulé
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si no show
     */
    public function isNoShow()
    {
        return $this->status === self::STATUS_NO_SHOW;
    }

    /**
     * Vérifier si le séjour est en cours (dates actuelles)
     */
    public function isOngoing()
    {
        $now = Carbon::now();
        return $now->between(
            Carbon::parse($this->check_in), 
            Carbon::parse($this->check_out)
        ) && $this->isActive();
    }

    /**
     * Vérifier si le séjour est à venir
     */
    public function isUpcoming()
    {
        return Carbon::parse($this->check_in)->isFuture() && 
               ($this->isReservation() || $this->isActive());
    }

    /**
     * Vérifier si le séjour est passé
     */
    public function isPast()
    {
        return Carbon::parse($this->check_out)->isPast() && 
               ($this->isActive() || $this->isReservation());
    }

    /**
     * Mettre à jour le statut automatiquement selon les dates
     */
    public function autoUpdateStatus()
    {
        $now = Carbon::now();
        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        // Ne pas toucher aux statuts annulés ou no_show
        if ($this->isCancelled() || $this->isNoShow()) {
            return $this->status;
        }

        $newStatus = $this->status;

        if ($checkOut->isPast()) {
            $newStatus = self::STATUS_COMPLETED;
        } elseif ($checkIn->isPast() && $checkOut->isFuture()) {
            $newStatus = self::STATUS_ACTIVE;
        } elseif ($checkIn->isFuture()) {
            $newStatus = self::STATUS_RESERVATION;
        }

        if ($newStatus !== $this->status) {
            $this->update(['status' => $newStatus]);
            
            // Gérer l'état de la chambre
            $this->updateRoomStatus($newStatus);
        }

        return $newStatus;
    }

    /**
     * Changer manuellement le statut avec logique métier
     */
    public function changeStatus($newStatus, $userId = null, $reason = null)
    {
        $oldStatus = $this->status;
        
        // Préparer les données de mise à jour
        $updateData = ['status' => $newStatus];
        
        // Gérer l'annulation
        if ($newStatus === self::STATUS_CANCELLED) {
            $updateData['cancelled_at'] = Carbon::now();
            $updateData['cancelled_by'] = $userId;
            $updateData['cancel_reason'] = $reason;
        } 
        // Si on réactive une réservation annulée
        elseif ($oldStatus === self::STATUS_CANCELLED && $newStatus !== self::STATUS_CANCELLED) {
            $updateData['cancelled_at'] = null;
            $updateData['cancelled_by'] = null;
            $updateData['cancel_reason'] = null;
        }
        
        // Mettre à jour
        $this->update($updateData);
        
        // Mettre à jour l'état de la chambre
        $this->updateRoomStatus($newStatus);
        
        return [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_at' => Carbon::now()
        ];
    }

    /**
     * Mettre à jour l'état de la chambre selon le statut
     */
    private function updateRoomStatus($status)
    {
        if (!$this->room) {
            return;
        }

        switch ($status) {
            case self::STATUS_ACTIVE:
                // Chambre occupée
                $this->room->update(['room_status_id' => 2]); // Occupied
                break;
                
            case self::STATUS_COMPLETED:
            case self::STATUS_CANCELLED:
            case self::STATUS_NO_SHOW:
            case self::STATUS_RESERVATION:
                // Chambre disponible (sauf si d'autres réservations)
                $hasOtherActiveReservations = Transaction::where('room_id', $this->room_id)
                    ->where('id', '!=', $this->id)
                    ->where('status', self::STATUS_ACTIVE)
                    ->exists();
                
                if (!$hasOtherActiveReservations) {
                    $this->room->update(['room_status_id' => 1]); // Available
                }
                break;
        }
    }

    /**
     * Obtenir le label du statut pour l'affichage
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_RESERVATION => 'Réservation',
            self::STATUS_ACTIVE => 'Dans l\'hôtel',
            self::STATUS_COMPLETED => 'Séjour terminé',
            self::STATUS_CANCELLED => 'Annulée',
            self::STATUS_NO_SHOW => 'No Show',
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_RESERVATION => 'warning',    // Jaune/orange
            self::STATUS_ACTIVE => 'success',         // Vert
            self::STATUS_COMPLETED => 'info',         // Bleu
            self::STATUS_CANCELLED => 'danger',       // Rouge
            self::STATUS_NO_SHOW => 'secondary',      // Gris
        ];
        
        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Obtenir l'icône du statut
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            self::STATUS_RESERVATION => 'fa-calendar-check',
            self::STATUS_ACTIVE => 'fa-bed',
            self::STATUS_COMPLETED => 'fa-check-circle',
            self::STATUS_CANCELLED => 'fa-times-circle',
            self::STATUS_NO_SHOW => 'fa-user-slash',
        ];
        
        return $icons[$this->status] ?? 'fa-circle';
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
     * Annuler la transaction (méthode héritée)
     */
    public function cancel($userId, $reason = null)
    {
        return $this->changeStatus(self::STATUS_CANCELLED, $userId, $reason);
    }

    /**
     * Restaurer une transaction annulée
     */
    public function restoreTransaction()
    {
        $this->update([
            'status' => self::STATUS_RESERVATION,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancel_reason' => null
        ]);

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
     * Vérifier si la transaction peut être annulée
     */
    public function canBeCancelled()
    {
        // Seulement les réservations peuvent être annulées
        return $this->isReservation() && !$this->isCancelled();
    }

    /**
     * Vérifier si la transaction peut être restaurée
     */
    public function canBeRestored()
    {
        return $this->isCancelled();
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
     * Obtenir les statuts disponibles avec leurs labels
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_RESERVATION => 'Réservation (pas encore arrivé)',
            self::STATUS_ACTIVE => 'Dans l\'hôtel (séjour en cours)',
            self::STATUS_COMPLETED => 'Séjour terminé (est parti)',
            self::STATUS_CANCELLED => 'Annulée',
            self::STATUS_NO_SHOW => 'No Show (pas venu)',
        ];
    }

    /**
     * Obtenir la classe CSS pour le badge
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_RESERVATION => 'badge bg-warning',
            self::STATUS_ACTIVE => 'badge bg-success',
            self::STATUS_COMPLETED => 'badge bg-info',
            self::STATUS_CANCELLED => 'badge bg-danger',
            self::STATUS_NO_SHOW => 'badge bg-secondary',
        ];
        
        return $classes[$this->status] ?? 'badge bg-secondary';
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
            
            // Définir le statut par défaut (réservation si check_in est futur)
            if (!$transaction->status) {
                $checkIn = Carbon::parse($transaction->check_in);
                $transaction->status = $checkIn->isFuture() 
                    ? self::STATUS_RESERVATION 
                    : self::STATUS_ACTIVE;
            }
        });

        // Mettre à jour automatiquement le statut selon les dates
        static::saving(function ($transaction) {
            // Ne pas mettre à jour automatiquement si c'est une annulation ou no show
            if ($transaction->isCancelled() || $transaction->isNoShow()) {
                return;
            }
            
            $transaction->autoUpdateStatus();
        });
    }
}