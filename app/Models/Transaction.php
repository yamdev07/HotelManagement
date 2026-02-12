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

    protected $fillable = [
        'user_id',
        'customer_id',
        'room_id',           // Peut être NULL au départ
        'room_type_id',      // NOUVEAU: Référence au type de chambre
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
        'is_assigned',       // NOUVEAU: Booléen si chambre attribuée
        'assigned_at',       // NOUVEAU: Date d'attribution
        'assigned_by',       // NOUVEAU: Qui a attribué
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'actual_check_in' => 'datetime',
        'actual_check_out' => 'datetime',
        'is_assigned' => 'boolean',  // NOUVEAU
        'assigned_at' => 'datetime', // NOUVEAU
    ];

    // Constantes pour les statuts
    const STATUS_RESERVATION = 'reservation';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

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
            'room_type' => $this->roomType->name ?? 'Type #'.($this->room_type_id ?? 'N/A'),
            'room' => $this->room->number ?? ($this->is_assigned ? 'À attribuer' : 'Non attribué'),
            'check_in' => $this->check_in?->format('d/m/Y'),
            'check_out' => $this->check_out?->format('d/m/Y'),
            'status' => $this->status_label,
            'total_price' => $this->total_price ?? 0,
            'total_payment' => $this->getTotalPayment(),
            'is_assigned' => $this->is_assigned,
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
     * Relation avec la chambre (peut être null)
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relation avec le type de chambre
     */
    public function roomType()
    {
        return $this->belongsTo(Type::class, 'room_type_id');
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
     * Relation avec l'utilisateur qui a annulé
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Relation avec l'utilisateur qui a attribué la chambre
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope pour les réservations non attribuées
     */
    public function scopeUnassigned($query)
    {
        return $query->where('is_assigned', false)
                    ->whereIn('status', [self::STATUS_RESERVATION, self::STATUS_ACTIVE]);
    }

    /**
     * Scope pour les réservations à attribuer aujourd'hui
     */
    public function scopeToAssignToday($query)
    {
        return $query->where('is_assigned', false)
                    ->whereDate('check_in', '<=', now()->addDay()) // Aujourd'hui ou demain
                    ->whereIn('status', [self::STATUS_RESERVATION, self::STATUS_ACTIVE])
                    ->orderBy('check_in');
    }

    /**
     * Scope pour les réservations par type
     */
    public function scopeByRoomType($query, $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
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
     * Vérifier si une chambre a été attribuée
     */
    public function isRoomAssigned()
    {
        return $this->is_assigned && !is_null($this->room_id);
    }

    /**
     * Attribuer une chambre à cette réservation
     */
    public function assignRoom($roomId, $userId, $notes = null)
    {
        DB::beginTransaction();

        try {
            $room = Room::findOrFail($roomId);
            
            // Vérifier que la chambre est du bon type
            if ($room->type_id != $this->room_type_id) {
                throw new \Exception('La chambre sélectionnée n\'est pas du type réservé.');
            }

            // Vérifier la disponibilité
            if (!$room->isAvailableForPeriod($this->check_in, $this->check_out, $this->id)) {
                throw new \Exception('Cette chambre n\'est pas disponible pour ces dates.');
            }

            $oldRoomId = $this->room_id;

            // Mettre à jour la transaction
            $this->update([
                'room_id' => $roomId,
                'is_assigned' => true,
                'assigned_at' => now(),
                'assigned_by' => $userId,
                'notes' => $notes ? ($this->notes . "\n" . $notes) : $this->notes,
            ]);

            // Mettre à jour le statut de la chambre
            $room->update(['room_status_id' => 2]); // Occupé

            // Si ancienne chambre existe, la libérer
            if ($oldRoomId && $oldRoomId != $roomId) {
                $oldRoom = Room::find($oldRoomId);
                if ($oldRoom) {
                    $oldRoom->update(['room_status_id' => 1]); // Disponible
                }
            }

            // Logger l'attribution
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'old_room_id' => $oldRoomId,
                    'new_room_id' => $roomId,
                    'room_number' => $room->number,
                    'assigned_at' => now(),
                ])
                ->log('a attribué une chambre à la réservation');

            DB::commit();

            return [
                'success' => true,
                'message' => "Chambre {$room->number} attribuée avec succès.",
                'room' => $room,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Erreur attribution chambre transaction #{$this->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtenir les chambres disponibles pour cette réservation
     */
    public function getAvailableRooms()
    {
        if (!$this->room_type_id) {
            return collect();
        }

        return Room::where('type_id', $this->room_type_id)
            ->where('room_status_id', 1) // Disponible
            ->whereDoesntHave('transactions', function ($query) {
                $query->where('check_in', '<', $this->check_out)
                    ->where('check_out', '>', $this->check_in)
                    ->whereIn('status', ['confirmed', 'active', 'checked-in'])
                    ->where('id', '!=', $this->id);
            })
            ->orderBy('number')
            ->get();
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
     * Effectuer le check-in AVEC vérification d'attribution
     */
    public function checkIn($userId, $data = [])
    {
        // Vérifier qu'une chambre est attribuée
        if (!$this->isRoomAssigned()) {
            return [
                'success' => false,
                'error' => 'Aucune chambre attribuée. Veuillez attribuer une chambre avant le check-in.',
                'requires_assignment' => true,
            ];
        }

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

            $this->update($updateData);

            // Mettre à jour le statut de la chambre
            $this->room->update(['room_status_id' => 2]); // Occupé

            // Logger le check-in
            activity()
                ->causedBy(User::find($userId))
                ->performedOn($this)
                ->withProperties([
                    'room_id' => $this->room_id,
                    'room_number' => $this->room->number,
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

            // Libérer la chambre
            if ($this->room) {
                $this->room->update(['room_status_id' => 1]); // Disponible
            }

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
                    'room_number' => $this->room->number ?? null,
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

        if ($newStatus === self::STATUS_CANCELLED) {
            $updateData['cancelled_at'] = Carbon::now();
            $updateData['cancelled_by'] = $userId;
            $updateData['cancel_reason'] = $reason;
            
            // Libérer la chambre si attribuée
            if ($this->isRoomAssigned() && $this->room) {
                $this->room->update(['room_status_id' => 1]); // Disponible
            }
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
     * Calculer le prix total - BASÉ SUR LE TYPE DE CHAMBRE
     */
    public function getTotalPrice()
    {
        // SI le champ total_price existe et est positif, vérifier sa cohérence
        if ($this->total_price && $this->total_price > 0) {
            $calculated = $this->calculateTotalPrice();

            // Si différence > 1 CFA, corriger
            if (abs($calculated - (float) $this->total_price) > 1) {
                \Log::info("Correction automatique prix transaction #{$this->id}", [
                    'ancien' => $this->total_price,
                    'calculé' => $calculated,
                    'différence' => $calculated - $this->total_price,
                ]);

                $this->total_price = $calculated;
                $this->saveQuietly();
            }

            return (float) $this->total_price;
        }

        // Sinon, calculer et sauvegarder
        $calculated = $this->calculateTotalPrice();
        $this->total_price = $calculated;
        $this->saveQuietly();

        return (float) $calculated;
    }

    /**
     * Calcul dynamique du prix BASÉ SUR LE TYPE
     */
    private function calculateTotalPrice()
    {
        try {
            if (!$this->room_type_id) {
                return 0;
            }

            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);

            // Calculer les nuits
            $hours = $checkIn->diffInHours($checkOut);
            $nights = ceil($hours / 24);
            $nights = max(1, $nights);

            // Prix par nuit du TYPE de chambre
            $roomType = Type::find($this->room_type_id);
            $pricePerNight = $roomType->price ?? 0; // price est un accessor pour base_price

            // CORRECT : prix par chambre, pas par personne
            return $nights * $pricePerNight; // NE PAS multiplier par person_count

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
     * Annuler la transaction
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

            return (float) $totalPaid;

        } catch (\Exception $e) {
            Log::error("Erreur mise à jour statut paiement transaction #{$this->id}: ".$e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier si la transaction peut être annulée
     */
    public function canBeCancelled()
    {
        return $this->isReservation() && ! $this->isCancelled();
    }

    /**
     * Vérifier si la transaction peut être restaurée
     */
    public function canBeRestored()
    {
        return $this->isCancelled();
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
     * Vérifier si une chambre peut être attribuée
     */
    public function canBeAssigned()
    {
        return !$this->isRoomAssigned() && 
               $this->isReservation() && 
               !is_null($this->room_type_id);
    }

    /**
     * Calculer l'acompte minimum
     */
    public function getMinimumDownPayment()
    {
        $total = $this->getTotalPrice();
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
     * Obtenir le badge pour l'attribution
     */
    public function getAssignmentBadgeAttribute()
    {
        if ($this->isRoomAssigned()) {
            return '<span class="badge bg-success">Chambre '.$this->room->number.'</span>';
        } elseif ($this->room_type_id) {
            return '<span class="badge bg-warning">À attribuer ('.$this->roomType->name.')</span>';
        } else {
            return '<span class="badge bg-danger">Non configuré</span>';
        }
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
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Initialiser total_payment à 0 si null
        static::creating(function ($transaction) {
            if (! $transaction->total_payment) {
                $transaction->total_payment = 0;
            }
            
            // S'assurer que room_id est null si pas encore attribué
            if (!$transaction->is_assigned) {
                $transaction->room_id = null;
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
                    'room_type' => $transaction->roomType->name ?? 'N/A',
                    'nights' => $transaction->getNightsAttribute(),
                    'total_price' => $transaction->getTotalPrice(),
                    'is_assigned' => $transaction->is_assigned,
                ])
                ->log('a créé une nouvelle réservation');
        });

        // Après mise à jour, vérifier l'attribution
        static::updated(function ($transaction) {
            if ($transaction->wasChanged('is_assigned') && $transaction->is_assigned) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($transaction)
                    ->log('a attribué une chambre à la réservation');
            }
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
            'room_type' => $this->roomType->name ?? 'N/A',
            'room' => $this->isRoomAssigned() ? $this->room->number : 'À attribuer',
            'status' => $this->status_label,
            'check_in' => $this->check_in->format('d/m/Y'),
            'check_out' => $this->check_out->format('d/m/Y'),
            'nights' => $this->getNightsAttribute(),
            'total_price' => $this->formatted_total_price,
            'total_paid' => $this->formatted_total_payment,
            'remaining' => $this->formatted_remaining_payment,
            'payment_rate' => $this->getPaymentRate().'%',
            'is_fully_paid' => $this->isFullyPaid(),
            'is_assigned' => $this->isRoomAssigned(),
        ];
    }

    /**
     * Marquer comme no-show
     */
    public function markAsNoShow($userId, $reason = null)
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => self::STATUS_NO_SHOW,
            'cancelled_by' => $userId,
            'cancel_reason' => $reason ?? 'No-show',
            'cancelled_at' => now(),
        ]);

        // Libérer la chambre si attribuée
        if ($this->isRoomAssigned() && $this->room) {
            $this->room->update(['room_status_id' => 1]); // Disponible
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

        return true;
    }

    /**
     * Vérifier la disponibilité pour le type de chambre
     */
    public function checkTypeAvailability()
    {
        if (!$this->room_type_id) {
            return false;
        }

        $availableRooms = Room::where('type_id', $this->room_type_id)
            ->where('room_status_id', 1) // Disponible
            ->whereDoesntHave('transactions', function ($query) {
                $query->where('check_in', '<', $this->check_out)
                    ->where('check_out', '>', $this->check_in)
                    ->whereIn('status', ['confirmed', 'active', 'checked-in'])
                    ->where('id', '!=', $this->id);
            })
            ->count();

        return $availableRooms > 0;
    }
}