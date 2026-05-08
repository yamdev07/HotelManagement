<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    // Spatie auto-logging disabled — manual activity() calls in methods provide richer context
    protected static $recordEvents = [];

    protected $fillable = [
        'user_id',
        'customer_id',
        'room_id',
        'check_in',
        'check_out',
        'status',
        'person_count',
        'total_price',
        'total_payment',
        'cancelled_at',
        'cancelled_by',
        'cancel_reason',
        'notes',
        'actual_check_in',
        'actual_check_out',
        'special_requests',
        'id_type',
        'id_number',
        'nationality',
        'late_checkout_fee',
        'late_checkout',
        'early_checkout',
        'early_checkout_refund',
        'early_checkout_reason',
        'expected_checkout_time',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime',
    ];

    // Constantes pour les statuts
    const STATUS_RESERVATION = 'reservation';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';
    const STATUS_PENDING_CHECKOUT = 'pending_checkout'; 
    const STATUS_RESERVED_WAITING = 'reserved_waiting'; 

    // Types de pièces d'identité
    const ID_TYPE_PASSPORT = 'passeport';
    const ID_TYPE_CNI = 'cni';
    const ID_TYPE_DRIVER_LICENSE = 'permis';
    const ID_TYPE_OTHER = 'autre';

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
                    'created' => 'a créé une réservation',
                    'updated' => 'a modifié une réservation',
                    'deleted' => 'a supprimé une réservation',
                    'restored' => 'a restauré une réservation',
                    default => "a {$eventName} une réservation",
                };
            });
    }

    /**
     * Créer un snapshot pour le journal d'activité
     */
    public function getLogSnapshot(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->name ?? 'Client #'.($this->customer_id ?? 'N/A'),
            'room' => $this->room->number ?? 'Chambre #'.($this->room_id ?? 'N/A'),
            'check_in' => $this->check_in?->format('d/m/Y'),
            'check_out' => $this->check_out?->format('d/m/Y'),
            'status' => $this->status_label,
            'total_price' => $this->total_price ?? 0,
            'total_payment' => $this->getTotalPayment(),
        ];
    }

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
    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relation avec les paiements COMPLÉTÉS seulement
     */
    public function completedPayments()
    {
        return $this->hasMany(Payment::class)->where('status', 'completed');
    }

    /**
     * Relation avec les commandes restaurant
     */
    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class);
    }

    /**
     * Relation avec les extras (minibar, services, etc.)
     */
    public function extras()
    {
        return $this->hasMany(TransactionExtra::class)->orderBy('created_at', 'desc');
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
     * Scope pour les transactions d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('check_in', '<=', Carbon::today())
            ->whereDate('check_out', '>=', Carbon::today())
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_NO_SHOW]);
    }

    /**
     * Scope pour les transactions de la semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('check_in', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Scope pour les transactions du mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('check_in', Carbon::now()->month)
            ->whereYear('check_in', Carbon::now()->year);
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
     * Vérifie si la transaction est en attente de check-out
     */
    public function isPendingCheckout()
    {
        return $this->status === self::STATUS_PENDING_CHECKOUT;
    }

    /**
     * Vérifie si la transaction est une réservation en attente
     */
    public function isReservedWaiting()
    {
        return $this->status === self::STATUS_RESERVED_WAITING;
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
     * Vérifier si le check-in a été effectué
     */
    public function isCheckedIn()
    {
        return ! is_null($this->actual_check_in);
    }

    /**
     * Vérifier si le check-out a été effectué
     */
    public function isCheckedOut()
    {
        return ! is_null($this->actual_check_out);
    }

    /**
     * Vérifier si la transaction peut être annulée
     */
    public function canBeCancelled()
    {
        // 1. Déjà annulée ? Non
        if ($this->isCancelled()) {
            return false;
        }
        
        // 2. Déjà no show ? Non
        if ($this->isNoShow()) {
            return false;
        }
        
        // 3. Seulement les réservations peuvent être annulées
        if (!$this->isReservation()) {
            return false;
        }
        
        // 4. Vérifier la date d'arrivée
        $checkIn = Carbon::parse($this->check_in);
        
        // Si la date d'arrivée est passée de plus de 24h
        if ($checkIn->isPast() && $checkIn->diffInHours(now()) > 24) {
            return false; // Trop tard pour annuler, utiliser No Show
        }
        
        return true;
    }

    /**
     * Obtenir la raison pour laquelle l'annulation est impossible
     */
    public function getCannotCancelReason()
    {
        if ($this->isCancelled()) {
            return 'Cette réservation est déjà annulée.';
        }
        
        if ($this->isNoShow()) {
            return 'Cette réservation est déjà marquée comme "No Show".';
        }
        
        if (!$this->isReservation()) {
            if ($this->isActive()) {
                return 'Impossible d\'annuler un client déjà installé.';
            }
            if ($this->isCompleted()) {
                return 'Impossible d\'annuler un séjour terminé.';
            }
            return 'Cette réservation ne peut pas être annulée.';
        }
        
        $checkIn = Carbon::parse($this->check_in);
        
        if ($checkIn->isPast()) {
            $hoursPassed = $checkIn->diffInHours(now());
            if ($hoursPassed > 24) {
                return 'La date d\'arrivée est dépassée depuis plus de 24h. Utilisez "No Show" à la place.';
            }
            return 'La date d\'arrivée est dépassée. Vous pouvez encore annuler ou utiliser "No Show".';
        }
        
        if ($this->completedPayments()->exists()) {
            return 'Des paiements ont été effectués. Vous devez procéder à un remboursement.';
        }
        
        return null;
    }

    /**
     * Vérifier si la transaction peut être marquée comme No Show
     */
    public function canBeNoShow()
    {
        // 1. Déjà annulée ou no show ? Non
        if ($this->isCancelled() || $this->isNoShow()) {
            return false;
        }
        
        // 2. Seulement les réservations
        if (!$this->isReservation()) {
            return false;
        }
        
        // 3. La date d'arrivée doit être passée
        $checkIn = Carbon::parse($this->check_in);
        if (!$checkIn->isPast()) {
            return false;
        }
        
        return true;
    }

    /**
     * Vérifier si la transaction peut être restaurée
     */
    public function canBeRestored()
    {
        return $this->isCancelled() || $this->isNoShow();
    }

    /**
     * Vérifier si la transaction peut être checkée-in
     */
    public function canBeCheckedIn()
    {
        return $this->isReservation() && ! $this->isCancelled() && ! $this->isNoShow();
    }

    /**
     * Vérifier si la transaction peut être checkée-out
     */
    public function canBeCheckedOut()
    {
        return $this->isActive() && $this->isFullyPaid();
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
            $oldStatus = $this->status;
            $this->update(['status' => $newStatus]);

            // Logger le changement de statut
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'reason' => 'mise à jour automatique',
                ])
                ->log('a changé le statut de la réservation');
        }

        return $newStatus;
    }

    /**
     * Effectuer le check-in
     */
    public function checkIn($userId, $data = [])
    {
        DB::beginTransaction();

        try {
            $oldData = $this->getOriginal();

            $updateData = [
                'status' => self::STATUS_ACTIVE,
                'actual_check_in' => now(),
                'special_requests' => $data['special_requests'] ?? $this->special_requests,
                'id_type' => $data['id_type'] ?? $this->id_type,
                'id_number' => $data['id_number'] ?? $this->id_number,
                'nationality' => $data['nationality'] ?? $this->nationality,
                'person_count' => $data['person_count'] ?? $this->person_count ?? 1,
            ];

            if (isset($data['new_room_id']) && $data['new_room_id'] != $this->room_id) {
                $updateData['room_id'] = $data['new_room_id'];
            }

            $this->update($updateData);

            // Logger le check-in
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'old_room_id' => $oldData['room_id'] ?? null,
                    'new_room_id' => $data['new_room_id'] ?? $this->room_id,
                    'check_in_time' => now(),
                    'person_count' => $data['person_count'] ?? $this->person_count,
                ])
                ->log('a effectué le check-in');

            DB::commit();

            return [
                'success' => true,
                'transaction' => $this->fresh(),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            // Logger l'erreur
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'error' => $e->getMessage(),
                    'data' => $data,
                ])
                ->log('erreur lors du check-in');

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Effectuer le check-out
     */
    public function checkOut($userId)
    {
        Log::info("Check-out transaction #{$this->id}", [
            'transaction_id' => $this->id,
            'total_price' => $this->total_price,
            'total_payment' => $this->total_payment,
            'remaining' => $this->getRemainingPayment(),
        ]);

        // Vérifier si le séjour est entièrement payé
        $remaining = $this->getRemainingPayment();
        if ($remaining > 100) {
            return [
                'success' => false,
                'error' => 'Solde restant: '.number_format($remaining, 0, ',', ' ').' CFA',
            ];
        }

        DB::beginTransaction();

        try {
            $oldStatus = $this->status;

            $this->update([
                'status' => self::STATUS_COMPLETED,
                'actual_check_out' => now(),
            ]);

            // Logger le check-out
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => self::STATUS_COMPLETED,
                    'check_out_time' => now(),
                    'total_paid' => $this->getTotalPayment(),
                    'remaining' => $remaining,
                ])
                ->log('a effectué le check-out');

            DB::commit();

            return [
                'success' => true,
                'transaction' => $this->fresh(),
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            // Logger l'erreur
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'error' => $e->getMessage(),
                ])
                ->log('erreur lors du check-out');

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Changer manuellement le statut
     */
    public function changeStatus($newStatus, $userId = null, $reason = null)
    {
        $oldStatus = $this->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === self::STATUS_CANCELLED || $newStatus === self::STATUS_NO_SHOW) {
            $updateData['cancelled_at'] = Carbon::now();
            $updateData['cancelled_by'] = $userId;
            $updateData['cancel_reason'] = $reason;
        } elseif ($oldStatus === self::STATUS_CANCELLED && $newStatus !== self::STATUS_CANCELLED) {
            $updateData['cancelled_at'] = null;
            $updateData['cancelled_by'] = null;
            $updateData['cancel_reason'] = null;
        }

        $this->update($updateData);

        // Logger le changement de statut
        $user = $userId ? User::find($userId) : null;
        activity()
            ->causedBy($user)
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
                'cancelled_by' => $userId,
            ])
            ->log('a changé le statut de la réservation');

        return [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_at' => Carbon::now(),
        ];
    }

    /**
     * Annuler la transaction
     */
    public function cancel($userId, $reason = null)
    {
        return $this->changeStatus(self::STATUS_CANCELLED, $userId, $reason);
    }

    /**
     * Marquer comme No Show (client non présenté)
     */
    public function markAsNoShow($userId, $reason = null)
    {
        if (!$this->canBeNoShow()) {
            throw new \Exception('Cette réservation ne peut pas être marquée comme No Show.');
        }
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $this->status;
            
            $this->update([
                'status' => self::STATUS_NO_SHOW,
                'cancelled_by' => $userId,
                'cancel_reason' => $reason ?? 'Client non présenté',
                'cancelled_at' => now(),
            ]);
            
            // Libérer la chambre
            if ($this->room) {
                $this->room->update(['room_status_id' => Room::STATUS_AVAILABLE]);
            }
            
            // Logger le no-show
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => self::STATUS_NO_SHOW,
                    'reason' => $reason,
                ])
                ->log('a marqué comme no-show');
            
            DB::commit();
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Restaurer une transaction annulée ou no show
     */
    public function restoreTransaction()
    {
        if (!$this->canBeRestored()) {
            throw new \Exception('Cette transaction ne peut pas être restaurée.');
        }
        
        $oldStatus = $this->status;

        $this->update([
            'status' => self::STATUS_RESERVATION,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancel_reason' => null,
        ]);

        // Logger la restauration
        activity()
            ->performedOn($this)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => self::STATUS_RESERVATION,
            ])
            ->log('a restauré la réservation annulée');

        return true;
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
            self::STATUS_PENDING_CHECKOUT => 'En attente de départ',
            self::STATUS_RESERVED_WAITING => 'En attente de chambre',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_RESERVATION => 'warning',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_COMPLETED => 'info',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_NO_SHOW => 'secondary',
            self::STATUS_PENDING_CHECKOUT => 'primary',
            self::STATUS_RESERVED_WAITING => 'dark',
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
            self::STATUS_PENDING_CHECKOUT => 'fa-clock',
            self::STATUS_RESERVED_WAITING => 'fa-hourglass-half',
        ];

        return $icons[$this->status] ?? 'fa-circle';
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
            self::STATUS_PENDING_CHECKOUT => 'badge bg-primary',
            self::STATUS_RESERVED_WAITING => 'badge bg-dark',
        ];

        return $classes[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Obtenir le label du type de pièce d'identité
     */
    public function getIdTypeLabelAttribute()
    {
        $labels = [
            self::ID_TYPE_PASSPORT => 'Passeport',
            self::ID_TYPE_CNI => 'Carte Nationale d\'Identité',
            self::ID_TYPE_DRIVER_LICENSE => 'Permis de Conduire',
            self::ID_TYPE_OTHER => 'Autre',
        ];

        return $labels[$this->id_type] ?? $this->id_type;
    }

    /**
     * Obtenir la liste des types de pièces d'identité
     */
    public static function getIdTypeOptions()
    {
        return [
            self::ID_TYPE_PASSPORT => 'Passeport',
            self::ID_TYPE_CNI => 'Carte Nationale d\'Identité',
            self::ID_TYPE_DRIVER_LICENSE => 'Permis de Conduire',
            self::ID_TYPE_OTHER => 'Autre',
        ];
    }

    /**
     * Calculer le prix total - Inclut le supplément late checkout et les commandes restaurant
     */
    public function getTotalPrice()
    {
        // 1. Calculer le prix de base (nuits * prix chambre)
        $basePrice = $this->calculateTotalPrice();
        
        // 2. Vérifier s'il y a un supplément late checkout
        $hasLateCheckout = $this->late_checkout ?? false;
        $lateFee = (float) ($this->late_checkout_fee ?? 0);
        
        // 3. Calculer le total des commandes restaurant sur facture chambre (uniquement livrées)
        // Les commandes en attente/préparation ne sont pas encore sur la facture.
        // Les commandes payées directement (non room_charge) ne s'ajoutent pas non plus.
        $restaurantTotal = $this->restaurantOrders()
            ->where('payment_method', 'room_charge')
            ->where('status', 'delivered')
            ->sum('total');

        // 4. Calculer le total des extras (minibar, lessive, services)
        $extrasTotal = $this->extras()->sum(\Illuminate\Support\Facades\DB::raw('amount * quantity'));

        // 5. Prix total = base + restaurant + extras
        $totalWithExtras = $basePrice + $restaurantTotal + $extrasTotal;
        if ($hasLateCheckout && $lateFee > 0) {
            $totalWithExtras += $lateFee;
        }

        // 6. Si le champ total_price existe et est différent, le mettre à jour
        if ($this->total_price && abs($totalWithExtras - (float) $this->total_price) > 1) {
            \Log::info("Correction prix transaction #{$this->id}", [
                'base_price' => $basePrice,
                'restaurant_total' => $restaurantTotal,
                'has_late_checkout' => $hasLateCheckout,
                'late_fee' => $lateFee,
                'ancien' => $this->total_price,
                'nouveau' => $totalWithExtras,
            ]);
            
            $this->total_price = $totalWithExtras;
            $this->saveQuietly();
        }
        
        return (float) $totalWithExtras;
    }

    /**
     * Calcul dynamique du prix
     */
    private function calculateTotalPrice()
    {
        try {
            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);

            // Calculer les nuits (arrondi supérieur si plus de 12h)
            $hours = $checkIn->diffInHours($checkOut);
            $nights = ceil($hours / 24);

            // Au moins une nuit
            $nights = max(1, $nights);

            // Prix par nuit
            $pricePerNight = $this->room->price ?? 0;

            return $nights * $pricePerNight;

        } catch (\Exception $e) {
            \Log::error("Erreur calcul prix transaction #{$this->id}: ".$e->getMessage());

            return 0;
        }
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
     * Obtenir le nombre total de personnes
     */
    public function getTotalPersonsAttribute()
    {
        return $this->person_count ?? 1;
    }

    /**
     * Obtenir la durée du séjour en heures
     */
    public function getStayDurationHours()
    {
        if ($this->actual_check_in && $this->actual_check_out) {
            return $this->actual_check_in->diffInHours($this->actual_check_out);
        }

        if ($this->actual_check_in) {
            return $this->actual_check_in->diffInHours(now());
        }

        return 0;
    }

    /**
     * Calculer le total des paiements COMPLÉTÉS
     */
    public function getTotalPayment()
    {
        try {
            // TOUJOURS calculer depuis la base pour garantir l'exactitude
            $total = $this->completedPayments()->sum('amount');

            // Si la valeur stockée est différente, la mettre à jour
            if (abs($total - (float) ($this->total_payment ?? 0)) > 0.01) {
                $oldTotal = $this->total_payment;
                $this->total_payment = $total;
                $this->saveQuietly();

                // Logger la mise à jour
                if ($oldTotal != $total) {
                    activity()
                        ->performedOn($this)
                        ->withProperties([
                            'old_total' => $oldTotal,
                            'new_total' => $total,
                        ])
                        ->log('a mis à jour le total des paiements');
                }

                Log::info("Total paiement mis à jour pour transaction #{$this->id}", [
                    'ancien' => $oldTotal,
                    'nouveau' => $total,
                ]);
            }

            return (float) $total;

        } catch (\Exception $e) {
            Log::error("Erreur calcul total paiement transaction #{$this->id}: ".$e->getMessage());

            return (float) ($this->total_payment ?? 0);
        }
    }

    /**
     * Calculer le montant restant à payer
     */
    public function getRemainingPayment()
    {
        try {
            $totalPrice = $this->getTotalPrice();
            $totalPaid = $this->getTotalPayment();

            $remaining = $totalPrice - $totalPaid;

            // Assurer que le résultat est positif ou nul
            $result = max(0, $remaining);

            return (float) $result;

        } catch (\Exception $e) {
            Log::error("Erreur calcul solde transaction #{$this->id}: ".$e->getMessage());

            return 0;
        }
    }

    /**
     * Calculer le taux de paiement
     */
    public function getPaymentRate()
    {
        $totalPrice = $this->getTotalPrice();

        if ($totalPrice > 0) {
            $rate = ($this->getTotalPayment() / $totalPrice) * 100;

            return min(100, max(0, round($rate, 1)));
        }

        return 0;
    }

    /**
     * Vérifier si la transaction est complètement payée
     */
    public function isFullyPaid()
    {
        $remaining = $this->getRemainingPayment();

        return $remaining <= 100; // Tolérance de 100 CFA
    }

    /**
     * Recalculer et mettre à jour le statut des paiements
     */
    public function updatePaymentStatus()
    {
        try {
            Log::info("Mise à jour statut paiement transaction #{$this->id}");

            // Calculer le total des paiements COMPLÉTÉS
            $totalPaid = $this->completedPayments()->sum('amount');

            // Vérifier les incohérences
            $oldTotal = (float) ($this->total_payment ?? 0);
            $diff = abs($totalPaid - $oldTotal);

            if ($diff > 1) {
                Log::warning("Incohérence détectée transaction #{$this->id}", [
                    'ancien_total' => $oldTotal,
                    'nouveau_total' => $totalPaid,
                    'différence' => $diff,
                ]);
            }

            // Mettre à jour
            $this->total_payment = $totalPaid;
            $this->save();

            // Logger la mise à jour
            if ($diff > 0.01) {
                activity()
                    ->performedOn($this)
                    ->withProperties([
                        'old_total' => $oldTotal,
                        'new_total' => $totalPaid,
                        'difference' => $diff,
                    ])
                    ->log('a recalculé le total des paiements');
            }

            // Forcer le rafraîchissement
            $this->refresh();

            Log::info("Transaction #{$this->id} mise à jour", [
                'total_payment' => $totalPaid,
                'remaining' => $this->getRemainingPayment(),
            ]);

            // Auto-marquer les commandes restaurant "room_charge" livrées comme "payées"
            // si la transaction est entièrement soldée (solde restant <= 0)
            if ($this->getRemainingPayment() <= 0) {
                $this->restaurantOrders()
                    ->where('payment_method', 'room_charge')
                    ->where('status', 'delivered')
                    ->update(['status' => 'paid']);

                Log::info("Commandes restaurant de la transaction #{$this->id} marquées comme payées.");
            } else {
                // Si le solde redevient positif (ex: annulation de paiement), 
                // on remet les commandes "room_charge" en "delivered"
                $updatedCount = $this->restaurantOrders()
                    ->where('payment_method', 'room_charge')
                    ->where('status', 'paid')
                    ->update(['status' => 'delivered']);

                if ($updatedCount > 0) {
                    Log::info("{$updatedCount} commandes restaurant de la transaction #{$this->id} remises en 'Livré' (Solde positif).");
                }
            }

            return (float) $totalPaid;

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour statut paiement transaction #{$this->id}: ".$e->getMessage());
            throw $e;
        }
    }

    /**
     * Calculer l'acompte minimum
     */
    public function getMinimumDownPayment()
    {
        $dayDifference = Helper::getDateDifference($this->check_in, $this->check_out);
        $total = $this->room->price * $dayDifference;

        return $total * 0.15;
    }

    /**
     * Formater le prix total
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->getTotalPrice(), 0, ',', ' ').' CFA';
    }

    /**
     * Formater le total payé
     */
    public function getFormattedTotalPaymentAttribute()
    {
        return number_format($this->getTotalPayment(), 0, ',', ' ').' CFA';
    }

    /**
     * Formater le montant restant
     */
    public function getFormattedRemainingPaymentAttribute()
    {
        return number_format($this->getRemainingPayment(), 0, ',', ' ').' CFA';
    }

    /**
     * Formater la date de check-in réel
     */
    public function getFormattedActualCheckInAttribute()
    {
        return $this->actual_check_in ?
            $this->actual_check_in->format('d/m/Y H:i') :
            'Non checkée-in';
    }

    /**
     * Formater la date de check-out réel
     */
    public function getFormattedActualCheckOutAttribute()
    {
        return $this->actual_check_out ?
            $this->actual_check_out->format('d/m/Y H:i') :
            'Non checkée-out';
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
            self::STATUS_PENDING_CHECKOUT => 'En attente de départ',
            self::STATUS_RESERVED_WAITING => 'En attente de chambre',
        ];
    }

    /**
     * Synchroniser manuellement les totaux
     */
    public function syncPaymentTotals()
    {
        DB::beginTransaction();

        try {
            Log::info("Synchronisation manuelle transaction #{$this->id}");

            // Recalculer tous les totaux
            $totalPrice = $this->getTotalPrice();
            $totalPayment = $this->completedPayments()->sum('amount');

            // Sauvegarder les anciennes valeurs
            $oldTotalPrice = $this->total_price;
            $oldTotalPayment = $this->total_payment;

            // Mettre à jour
            $this->total_price = $totalPrice;
            $this->total_payment = $totalPayment;
            $this->save();

            // Logger la synchronisation
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this)
                ->withProperties([
                    'old_price' => $oldTotalPrice,
                    'new_price' => $totalPrice,
                    'old_payment' => $oldTotalPayment,
                    'new_payment' => $totalPayment,
                ])
                ->log('a synchronisé les totaux de la réservation');

            // Rafraîchir
            $this->refresh();

            $result = [
                'success' => true,
                'message' => 'Transaction synchronisée avec succès',
                'total_price' => [
                    'old' => (float) $oldTotalPrice,
                    'new' => (float) $totalPrice,
                    'changed' => $totalPrice != $oldTotalPrice,
                ],
                'total_payment' => [
                    'old' => (float) $oldTotalPayment,
                    'new' => (float) $totalPayment,
                    'changed' => $totalPayment != $oldTotalPayment,
                ],
                'remaining' => $this->getRemainingPayment(),
                'payment_rate' => $this->getPaymentRate(),
                'is_fully_paid' => $this->isFullyPaid(),
            ];

            Log::info('Synchronisation terminée', $result);

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur synchronisation transaction #{$this->id}: ".$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $this->id,
            ];
        }
    }

    /**
     * Obtenir les activités de la transaction
     */
    public function transactionActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Obtenir toutes les activités liées à cette transaction
     */
    public function getAllActivitiesAttribute()
    {
        return \Spatie\Activitylog\Models\Activity::where('subject_type', self::class)
            ->where('subject_id', $this->id)
            ->orWhere(function ($query) {
                $query->where('properties', 'LIKE', '%"transaction_id":'.$this->id.'%')
                    ->orWhere('properties', 'LIKE', '%"transaction_id":"'.$this->id.'"%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Enregistrer un paiement
     */
    public function logPayment($payment, $user)
    {
        activity()
            ->causedBy($user)
            ->performedOn($this)
            ->withProperties([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'method' => $payment->payment_method,
                'status' => $payment->status,
            ])
            ->log('a enregistré un paiement');
    }

    /**
     * Boot du modèle - VERSION SIMPLIFIÉE
     */
    protected static function boot()
    {
        parent::boot();

        // Initialiser total_payment à 0 si null
        static::creating(function ($transaction) {
            if (! $transaction->total_payment) {
                $transaction->total_payment = 0;
            }
        });

        // Après création, forcer le calcul
        static::created(function ($transaction) {
            $transaction->updatePaymentStatus();

            // Logger la création
            activity()
                ->causedBy(auth()->user())
                ->performedOn($transaction)
                ->withProperties([
                    'customer' => $transaction->customer->name ?? 'N/A',
                    'room' => $transaction->room->number ?? 'N/A',
                    'nights' => $transaction->getNightsAttribute(),
                    'total_price' => $transaction->getTotalPrice(),
                ])
                ->log('a créé une nouvelle réservation');
        });
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
     * Obtenir le résumé de la transaction
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->name ?? 'N/A',
            'room' => $this->room->number ?? 'N/A',
            'status' => $this->status_label,
            'check_in' => $this->check_in->format('d/m/Y'),
            'check_out' => $this->check_out->format('d/m/Y'),
            'nights' => $this->getNightsAttribute(),
            'total_price' => $this->formatted_total_price,
            'total_paid' => $this->formatted_total_payment,
            'remaining' => $this->formatted_remaining_payment,
            'payment_rate' => $this->getPaymentRate().'%',
            'is_fully_paid' => $this->isFullyPaid(),
        ];
    }

    /**
     * Créer une réservation pour une chambre qui sera libérée aujourd'hui
     */
    public static function createWaitingReservation($data)
    {
        DB::beginTransaction();
        
        try {
            $transaction = self::create([
                'user_id' => $data['user_id'],
                'customer_id' => $data['customer_id'],
                'room_id' => $data['room_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'check_in_time' => $data['check_in_time'] ?? '14:00:00',
                'check_out_time' => $data['check_out_time'] ?? '12:00:00',
                'status' => self::STATUS_RESERVED_WAITING,
                'person_count' => $data['person_count'] ?? 1,
                'total_price' => $data['total_price'],
                'total_payment' => 0,
                'notes' => $data['notes'] . ' | En attente du check-out du client actuel',
                'special_requests' => $data['special_requests'] ?? null,
                'id_type' => $data['id_type'] ?? null,
                'id_number' => $data['id_number'] ?? null,
                'nationality' => $data['nationality'] ?? null,
            ]);
            
            DB::commit();
            
            return $transaction;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Confirmer la réservation après le check-out
     */
    public function confirmAfterCheckout()
    {
        if ($this->status !== self::STATUS_RESERVED_WAITING) {
            return false;
        }
        
        $this->status = self::STATUS_RESERVATION;
        $this->notes = $this->notes . ' | Confirmée après check-out';
        $this->save();
        
        activity()
            ->performedOn($this)
            ->log('Réservation confirmée après libération de la chambre');
        
        return true;
    }

    /**
     * Vérifier si la chambre sera disponible pour la période demandée
     */
    public static function isRoomAvailableForPeriod($roomId, $checkIn, $checkOut, $excludeTransactionId = null)
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();
        
        // Vérifier les transactions qui pourraient bloquer
        $conflictingTransactions = self::where('room_id', $roomId)
            ->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_NO_SHOW, self::STATUS_COMPLETED])
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });
        
        if ($excludeTransactionId) {
            $conflictingTransactions->where('id', '!=', $excludeTransactionId);
        }
        
        $conflicts = $conflictingTransactions->get();
        
        foreach ($conflicts as $conflict) {
            // Si la transaction conflictuelle se termine le jour du check-in
            if ($conflict->check_out->format('Y-m-d') == $checkIn->format('Y-m-d')) {
                // OK - le client part le jour de l'arrivée
                continue;
            }
            
            // Si la transaction conflictuelle commence le jour du check-out
            if ($conflict->check_in->format('Y-m-d') == $checkOut->format('Y-m-d')) {
                // OK - le nouveau client part le jour où l'autre arrive
                continue;
            }
            
            // Autre conflit → non disponible
            return false;
        }
        
        return true;
    }

    /**
     * Vérifier si le supplément late checkout est payé
     */
    public function isLateCheckoutPaid(): bool
    {
        // Si pas de late checkout ou pas de frais, considéré comme payé
        if (!$this->late_checkout || !$this->late_checkout_fee || $this->late_checkout_fee <= 0) {
            return true;
        }
        
        try {
            // Chercher un paiement complété pour ce late checkout
            $latePayment = $this->completedPayments()
                ->where(function($query) {
                    $query->where('reference', 'like', 'LATE-' . $this->id . '%')
                        ->orWhere('description', 'like', '%Late checkout%')
                        ->orWhere('description', 'like', '%late checkout%');
                })
                ->where('amount', '>=', $this->late_checkout_fee)
                ->first();
            
            $isPaid = !is_null($latePayment);
            
            \Log::info("Vérification paiement late checkout #{$this->id}", [
                'late_checkout' => $this->late_checkout,
                'late_fee' => $this->late_checkout_fee,
                'payment_found' => $isPaid,
                'payment_id' => $latePayment?->id,
                'payment_amount' => $latePayment?->amount,
                'payment_status' => $latePayment?->status,
            ]);
            
            return $isPaid;
            
        } catch (\Exception $e) {
            \Log::error("Erreur vérification late checkout #{$this->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir le statut détaillé du paiement late checkout
     */
    public function getLateCheckoutPaymentStatus(): array
    {
        if (!$this->late_checkout || !$this->late_checkout_fee) {
            return [
                'has_late_checkout' => false,
                'is_paid' => true,
                'message' => 'Pas de late checkout',
            ];
        }
        
        $latePayment = $this->completedPayments()
            ->where(function($query) {
                $query->where('reference', 'like', 'LATE-' . $this->id . '%')
                    ->orWhere('description', 'like', '%Late checkout%');
            })
            ->first();
        
        if ($latePayment) {
            return [
                'has_late_checkout' => true,
                'is_paid' => true,
                'payment_id' => $latePayment->id,
                'amount' => $latePayment->amount,
                'payment_method' => $latePayment->payment_method,
                'paid_at' => $latePayment->created_at,
                'message' => 'Late checkout payé',
            ];
        }
        
        // Chercher un paiement en attente
        $pendingPayment = $this->payments()
            ->where(function($query) {
                $query->where('reference', 'like', 'LATE-' . $this->id . '%')
                    ->orWhere('description', 'like', '%Late checkout%');
            })
            ->where('status', 'pending')
            ->first();
        
        if ($pendingPayment) {
            return [
                'has_late_checkout' => true,
                'is_paid' => false,
                'is_pending' => true,
                'pending_payment_id' => $pendingPayment->id,
                'amount' => $pendingPayment->amount,
                'message' => 'Late checkout en attente de paiement',
            ];
        }
        
        return [
            'has_late_checkout' => true,
            'is_paid' => false,
            'amount_due' => $this->late_checkout_fee,
            'message' => 'Late checkout non payé',
        ];
    }
}