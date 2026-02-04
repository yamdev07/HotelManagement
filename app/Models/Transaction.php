<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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

    // Types de pièces d'identité
    const ID_TYPE_PASSPORT = 'passeport';
    const ID_TYPE_CNI = 'cni';
    const ID_TYPE_DRIVER_LICENSE = 'permis';
    const ID_TYPE_OTHER = 'autre';

    /**
     * Configuration du logging d'activité
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
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
     * Cela évite "Objet supprimé" quand la transaction est supprimée
     */
    public function getLogSnapshot(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->name ?? 'Client #' . ($this->customer_id ?? 'N/A'),
            'room' => $this->room->number ?? 'Chambre #' . ($this->room_id ?? 'N/A'),
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
            Carbon::now()->endOfWeek()
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
        return !is_null($this->actual_check_in);
    }

    /**
     * Vérifier si le check-out a été effectué
     */
    public function isCheckedOut()
    {
        return !is_null($this->actual_check_out);
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
                'transaction' => $this->fresh()
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
                'error' => $e->getMessage()
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
                'error' => 'Solde restant: ' . number_format($remaining, 0, ',', ' ') . ' CFA'
            ];
        }

        DB::beginTransaction();
        
        try {
            $oldStatus = $this->status;
            
            $this->update([
                'status' => self::STATUS_COMPLETED,
                'actual_check_out' => now()
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
                'transaction' => $this->fresh()
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
                'error' => $e->getMessage()
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
            'changed_at' => Carbon::now()
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
     * Calculer le prix total - MÉTHODE CORRECTE
     */
    public function getTotalPrice()
    {
        // SI le champ total_price existe et est positif, vérifier sa cohérence
        if ($this->total_price && $this->total_price > 0) {
            $calculated = $this->calculateTotalPrice();
            
            // Si différence > 1 CFA, corriger
            if (abs($calculated - (float)$this->total_price) > 1) {
                \Log::info("Correction automatique prix transaction #{$this->id}", [
                    'ancien' => $this->total_price,
                    'calculé' => $calculated,
                    'différence' => $calculated - $this->total_price
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
            \Log::error("Erreur calcul prix transaction #{$this->id}: " . $e->getMessage());
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
     * Calculer le total des paiements COMPLÉTÉS - MÉTHODE CRITIQUE CORRIGÉE
     */
    public function getTotalPayment()
    {
        try {
            // TOUJOURS calculer depuis la base pour garantir l'exactitude
            $total = $this->completedPayments()->sum('amount');
            
            // Si la valeur stockée est différente, la mettre à jour
            if (abs($total - (float)($this->total_payment ?? 0)) > 0.01) {
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
                    'nouveau' => $total
                ]);
            }
            
            return (float) $total;
            
        } catch (\Exception $e) {
            Log::error("Erreur calcul total paiement transaction #{$this->id}: " . $e->getMessage());
            return (float) ($this->total_payment ?? 0);
        }
    }

    /**
     * Calculer le montant restant à payer - MÉTHODE CORRIGÉE
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
            Log::error("Erreur calcul solde transaction #{$this->id}: " . $e->getMessage());
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
     * Vérifier si la transaction est complètement payée - MÉTHODE CORRIGÉE
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
            'cancel_reason' => null
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
     * Recalculer et mettre à jour le statut des paiements - MÉTHODE CRITIQUE
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
                'remaining' => $this->getRemainingPayment()
            ]);
            
            return (float) $totalPaid;
            
        } catch (\Exception $e) {
            Log::error("Erreur mise à jour statut paiement transaction #{$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier si la transaction peut être annulée
     */
    public function canBeCancelled()
    {
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
     * Vérifier si la transaction peut être checkée-in
     */
    public function canBeCheckedIn()
    {
        return $this->isReservation() && !$this->isCancelled() && !$this->isNoShow();
    }

    /**
     * Vérifier si la transaction peut être checkée-out
     */
    public function canBeCheckedOut()
    {
        return $this->isActive() && $this->isFullyPaid();
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
     * Synchroniser manuellement les totaux - MÉTHODE IMPORTANTE
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
                    'changed' => $totalPrice != $oldTotalPrice
                ],
                'total_payment' => [
                    'old' => (float) $oldTotalPayment,
                    'new' => (float) $totalPayment,
                    'changed' => $totalPayment != $oldTotalPayment
                ],
                'remaining' => $this->getRemainingPayment(),
                'payment_rate' => $this->getPaymentRate(),
                'is_fully_paid' => $this->isFullyPaid()
            ];
            
            Log::info("Synchronisation terminée", $result);
            
            DB::commit();
            
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur synchronisation transaction #{$this->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $this->id
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
            ->orWhere(function($query) {
                $query->where('properties', 'LIKE', '%"transaction_id":' . $this->id . '%')
                    ->orWhere('properties', 'LIKE', '%"transaction_id":"' . $this->id . '"%');
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
            if (!$transaction->total_payment) {
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
            'payment_rate' => $this->getPaymentRate() . '%',
            'is_fully_paid' => $this->isFullyPaid(),
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
}