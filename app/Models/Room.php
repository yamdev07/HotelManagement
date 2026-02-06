<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Room extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'type_id',
        'room_status_id',
        'number',
        'name',
        'capacity',
        'price',
        'view',
        'description',
        'size',
        'last_cleaned_at',
        'maintenance_started_at',
        'maintenance_ended_at',
        'maintenance_reason',
    ];

    protected $appends = [
        'first_image_url',
        'occupancy_status',
        'is_available_today',
        'next_available_date',
        'formatted_price',
        'short_description',
        'facilities_list',
        'status_label',
        'status_color',
        'status_icon',
        'display_name',
        'full_name',
    ];

    protected $casts = [
        'last_cleaned_at' => 'datetime',
        'maintenance_started_at' => 'datetime',
        'maintenance_ended_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    // Constantes pour les statuts
    const STATUS_AVAILABLE = 1;

    const STATUS_MAINTENANCE = 2;

    const STATUS_CLEANING = 3;

    const STATUS_OCCUPIED = 4;

    const STATUS_RESERVED = 5;

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
                    'created' => 'a créé une chambre',
                    'updated' => 'a modifié une chambre',
                    'deleted' => 'a supprimé une chambre',
                    default => "a {$eventName} une chambre",
                };
            });
    }

    /**
     * Créer un snapshot pour le journal d'activité
     */
    public function getLogSnapshot(): array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'type' => $this->type->name ?? $this->type_id ?? 'N/A',
            'status' => $this->status_label,
            'price' => $this->price,
            'capacity' => $this->capacity,
        ];
    }

    /**
     * Relation avec le type de chambre
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Relation avec le statut de la chambre
     */
    public function roomStatus()
    {
        return $this->belongsTo(RoomStatus::class);
    }

    /**
     * Relation avec les images
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Relation avec les équipements
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_room');
    }

    /**
     * Relation avec les transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Réservations actives
     */
    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'active')
            ->where('check_in', '<=', now())
            ->where('check_out', '>=', now());
    }

    /**
     * Réservations confirmées à venir
     */
    public function upcomingTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->whereIn('status', ['reservation', 'confirmed'])
            ->where('check_in', '>', now());
    }

    /**
     * Transaction actuelle
     */
    public function currentTransaction()
    {
        return $this->hasOne(Transaction::class)
            ->whereIn('status', ['active', 'reservation'])
            ->where('check_in', '<=', now())
            ->where('check_out', '>=', now())
            ->latest();
    }

    /**
     * Réservations terminées récentes
     */
    public function recentCompletedTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'completed')
            ->where('check_out', '>=', now()->subDays(30))
            ->latest('check_out');
    }

    /**
     * Obtenir la première image
     */
    public function firstImage()
    {
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $firstImage = $this->images->first();

            if (method_exists($firstImage, 'getRoomImage')) {
                return $firstImage->getRoomImage();
            }

            return $firstImage->url ?? asset('img/default/default-room.png');
        }

        $image = $this->images()->first();
        if ($image) {
            return $image->getRoomImage();
        }

        return asset('img/default/default-room.png');
    }

    /**
     * Accesseur: URL de la première image
     */
    public function getFirstImageUrlAttribute()
    {
        return $this->firstImage();
    }

    /**
     * Vérifier la disponibilité pour une période donnée
     */
    public function isAvailableForPeriod($checkIn, $checkOut, $excludeTransactionId = null)
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();

        if ($this->room_status_id != self::STATUS_AVAILABLE) {
            return false;
        }

        $query = $this->transactions()
            ->whereNotIn('status', ['cancelled', 'no_show', 'completed'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });

        if ($excludeTransactionId) {
            $query->where('id', '!=', $excludeTransactionId);
        }

        return $query->count() === 0;
    }

    /**
     * Obtenir les réservations pour une date spécifique
     */
    public function getReservationsForDate($date)
    {
        $date = Carbon::parse($date)->startOfDay();

        return $this->transactions()
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where('check_in', '<=', $date)
            ->where('check_out', '>', $date)
            ->with('customer')
            ->get();
    }

    /**
     * Vérifier si occupé à une date spécifique
     */
    public function isOccupiedOnDate($date)
    {
        $date = Carbon::parse($date)->startOfDay();

        return $this->transactions()
            ->whereIn('status', ['active', 'reservation', 'confirmed'])
            ->where('check_in', '<=', $date)
            ->where('check_out', '>', $date)
            ->exists();
    }

    /**
     * Obtenir la prochaine date disponible
     */
    public function getNextAvailableDate($startFrom = null)
    {
        $startDate = $startFrom ? Carbon::parse($startFrom) : now();
        $startDate = $startDate->startOfDay();

        for ($i = 0; $i < 90; $i++) {
            $checkDate = $startDate->copy()->addDays($i);

            if (! $this->isOccupiedOnDate($checkDate) && $this->room_status_id == self::STATUS_AVAILABLE) {
                return $checkDate;
            }
        }

        return null;
    }

    /**
     * Obtenir les périodes disponibles
     */
    public function getAvailablePeriods($startDate = null, $endDate = null, $minNights = 1)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->addDays(90);

        $periods = [];
        $currentPeriod = null;

        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            $isAvailable = ! $this->isOccupiedOnDate($date) && $this->room_status_id == self::STATUS_AVAILABLE;

            if ($isAvailable) {
                if (! $currentPeriod) {
                    $currentPeriod = [
                        'start' => $date->copy(),
                        'end' => $date->copy(),
                    ];
                } else {
                    $currentPeriod['end'] = $date->copy();
                }
            } else {
                if ($currentPeriod) {
                    $duration = $currentPeriod['start']->diffInDays($currentPeriod['end']) + 1;
                    if ($duration >= $minNights) {
                        $periods[] = [
                            'start' => $currentPeriod['start']->format('Y-m-d'),
                            'end' => $currentPeriod['end']->format('Y-m-d'),
                            'nights' => $duration,
                            'total_price' => $this->price * $duration,
                            'formatted_period' => $currentPeriod['start']->format('d/m/Y').' - '.$currentPeriod['end']->format('d/m/Y'),
                        ];
                    }
                    $currentPeriod = null;
                }
            }

            $date->addDay();
        }

        if ($currentPeriod) {
            $duration = $currentPeriod['start']->diffInDays($currentPeriod['end']) + 1;
            if ($duration >= $minNights) {
                $periods[] = [
                    'start' => $currentPeriod['start']->format('Y-m-d'),
                    'end' => $currentPeriod['end']->format('Y-m-d'),
                    'nights' => $duration,
                    'total_price' => $this->price * $duration,
                    'formatted_period' => $currentPeriod['start']->format('d/m/Y').' - '.$currentPeriod['end']->format('d/m/Y'),
                ];
            }
        }

        return $periods;
    }

    /**
     * Obtenir le taux d'occupation
     */
    public function getOccupancyRate($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $occupiedDays = 0;

        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            if ($this->isOccupiedOnDate($date)) {
                $occupiedDays++;
            }
            $date->addDay();
        }

        return $totalDays > 0 ? round(($occupiedDays / $totalDays) * 100, 1) : 0;
    }

    /**
     * Accesseur: Statut d'occupation
     */
    public function getOccupancyStatusAttribute()
    {
        if ($this->room_status_id == self::STATUS_MAINTENANCE) {
            return 'maintenance';
        }

        if ($this->room_status_id == self::STATUS_CLEANING) {
            return 'cleaning';
        }

        if ($this->room_status_id == self::STATUS_OCCUPIED) {
            return 'occupied';
        }

        return $this->isOccupiedOnDate(now()) ? 'occupied' : 'available';
    }

    /**
     * Accesseur: Label du statut
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_AVAILABLE => 'Disponible',
            self::STATUS_MAINTENANCE => 'En maintenance',
            self::STATUS_CLEANING => 'À nettoyer',
            self::STATUS_OCCUPIED => 'Occupée',
            self::STATUS_RESERVED => 'Réservée',
        ];

        return $labels[$this->room_status_id] ?? 'Inconnu';
    }

    /**
     * Accesseur: Couleur du statut
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_MAINTENANCE => 'warning',
            self::STATUS_CLEANING => 'info',
            self::STATUS_OCCUPIED => 'danger',
            self::STATUS_RESERVED => 'primary',
        ];

        return $colors[$this->room_status_id] ?? 'secondary';
    }

    /**
     * Accesseur: Icône du statut
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            self::STATUS_AVAILABLE => 'fa-door-open',
            self::STATUS_MAINTENANCE => 'fa-tools',
            self::STATUS_CLEANING => 'fa-broom',
            self::STATUS_OCCUPIED => 'fa-bed',
            self::STATUS_RESERVED => 'fa-calendar-check',
        ];

        return $icons[$this->room_status_id] ?? 'fa-door-closed';
    }

    /**
     * Accesseur: Nom d'affichage
     */
    public function getDisplayNameAttribute()
    {
        if (! empty($this->name)) {
            return $this->name;
        }

        $typeName = $this->type ? $this->type->name : 'Room';

        return "{$typeName} #{$this->number}";
    }

    /**
     * Accesseur: Nom complet
     */
    public function getFullNameAttribute()
    {
        if (! empty($this->name)) {
            return "{$this->name} (#{$this->number})";
        }

        return "Room #{$this->number}";
    }

    /**
     * Accesseur: Disponible aujourd'hui
     */
    public function getIsAvailableTodayAttribute()
    {
        return $this->room_status_id == self::STATUS_AVAILABLE &&
               ! $this->isOccupiedOnDate(now());
    }

    /**
     * Accesseur: Prochaine date disponible
     */
    public function getNextAvailableDateAttribute()
    {
        if ($this->is_available_today) {
            return now()->format('Y-m-d');
        }

        $nextDate = $this->getNextAvailableDate();

        return $nextDate ? $nextDate->format('Y-m-d') : null;
    }

    /**
     * Accesseur: Prix formaté
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ').' FCFA/nuit';
    }

    /**
     * Accesseur: Description courte
     */
    public function getShortDescriptionAttribute()
    {
        if (empty($this->description)) {
            return '';
        }

        return strlen($this->description) > 100
            ? substr($this->description, 0, 100).'...'
            : $this->description;
    }

    /**
     * Accesseur: Liste des équipements
     */
    public function getFacilitiesListAttribute()
    {
        return $this->facilities->pluck('name')->join(', ');
    }

    /**
     * Scope: Chambres disponibles pour une période
     */
    public function scopeAvailableForPeriod($query, $checkIn, $checkOut)
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();

        return $query->where('room_status_id', self::STATUS_AVAILABLE)
            ->whereDoesntHave('transactions', function ($q) use ($checkIn, $checkOut) {
                $q->whereIn('status', ['active', 'reservation', 'confirmed'])
                    ->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });
    }

    /**
     * Scope: Chambres par type
     */
    public function scopeByType($query, $typeId)
    {
        return $query->where('type_id', $typeId);
    }

    /**
     * Scope: Chambres par statut
     */
    public function scopeByStatus($query, $statusId)
    {
        return $query->where('room_status_id', $statusId);
    }

    /**
     * Scope: Chambres avec capacité minimum
     */
    public function scopeWithMinCapacity($query, $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    /**
     * Scope: Chambres dans une fourchette de prix
     */
    public function scopeWithPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope: Chambres disponibles aujourd'hui
     */
    public function scopeAvailableToday($query)
    {
        return $query->where('room_status_id', self::STATUS_AVAILABLE)
            ->whereDoesntHave('transactions', function ($q) {
                $q->whereIn('status', ['active', 'reservation', 'confirmed'])
                    ->where('check_in', '<=', now())
                    ->where('check_out', '>', now());
            });
    }

    /**
     * Scope: Chambres occupées aujourd'hui
     */
    public function scopeOccupiedToday($query)
    {
        return $query->whereHas('transactions', function ($q) {
            $q->whereIn('status', ['active', 'reservation', 'confirmed'])
                ->where('check_in', '<=', now())
                ->where('check_out', '>', now());
        });
    }

    /**
     * Scope: Chambres en maintenance
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('room_status_id', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope: Chambres à nettoyer
     */
    public function scopeNeedsCleaning($query)
    {
        return $query->where('room_status_id', self::STATUS_CLEANING);
    }

    /**
     * Vérifier si la chambre a un équipement spécifique
     */
    public function hasFacility($facilityId)
    {
        return $this->facilities->contains('id', $facilityId);
    }

    /**
     * Marquer comme nettoyée
     */
    public function markAsCleaned($user = null)
    {
        $oldStatus = $this->room_status_id;

        $isOccupied = $this->isOccupiedOnDate(now());
        $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;

        $this->update([
            'room_status_id' => $newStatus,
            'last_cleaned_at' => now(),
        ]);

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'cleaned_at' => now(),
                ])
                ->log('a nettoyé la chambre');
        }

        return $this;
    }

    /**
     * Marquer comme en maintenance
     */
    public function markAsMaintenance($user = null, $reason = null)
    {
        $oldStatus = $this->room_status_id;

        $data = [
            'room_status_id' => self::STATUS_MAINTENANCE,
            'maintenance_started_at' => now(),
        ];

        if ($reason) {
            $data['maintenance_reason'] = $reason;
        }

        $this->update($data);

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => self::STATUS_MAINTENANCE,
                    'reason' => $reason,
                    'started_at' => now(),
                ])
                ->log('a mis la chambre en maintenance');
        }

        return $this;
    }

    /**
     * Terminer la maintenance
     */
    public function endMaintenance($user = null)
    {
        $oldStatus = $this->room_status_id;

        $newStatus = $this->isOccupiedOnDate(now()) ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;

        $this->update([
            'room_status_id' => $newStatus,
            'maintenance_ended_at' => now(),
        ]);

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'ended_at' => now(),
                    'duration' => $this->maintenance_started_at ?
                        $this->maintenance_started_at->diffInHours(now()).' heures' : 'N/A',
                ])
                ->log('a terminé la maintenance de la chambre');
        }

        return $this;
    }

    /**
     * Marquer comme à nettoyer
     */
    public function markAsNeedsCleaning($user = null)
    {
        $oldStatus = $this->room_status_id;

        $this->update([
            'room_status_id' => self::STATUS_CLEANING,
        ]);

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => self::STATUS_CLEANING,
                ])
                ->log('a marqué la chambre comme à nettoyer');
        }

        return $this;
    }

    /**
     * Obtenir les statistiques d'occupation
     */
    public static function getOccupancyStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();

        $totalRooms = self::count();
        $availableRooms = self::where('room_status_id', self::STATUS_AVAILABLE)->count();
        $occupiedRooms = self::where('room_status_id', self::STATUS_OCCUPIED)->count();
        $maintenanceRooms = self::where('room_status_id', self::STATUS_MAINTENANCE)->count();
        $cleaningRooms = self::where('room_status_id', self::STATUS_CLEANING)->count();

        $actualOccupied = self::whereHas('transactions', function ($q) {
            $q->whereIn('status', ['active', 'reservation'])
                ->where('check_in', '<=', now())
                ->where('check_out', '>', now());
        })->count();

        $stats = [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $actualOccupied,
            'maintenance_rooms' => $maintenanceRooms,
            'cleaning_rooms' => $cleaningRooms,
            'occupancy_rate' => $totalRooms > 0 ? round(($actualOccupied / $totalRooms) * 100, 1) : 0,
        ];

        return $stats;
    }

    /**
     * Obtenir les revenus pour une période
     */
    public function getRevenueForPeriod($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();

        $transactions = $this->transactions()
            ->whereIn('status', ['completed', 'active'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($inner) use ($startDate, $endDate) {
                        $inner->where('check_in', '<', $startDate)
                            ->where('check_out', '>', $endDate);
                    });
            })
            ->get();

        $totalRevenue = 0;
        $totalNights = 0;

        foreach ($transactions as $transaction) {
            $overlapStart = max($transaction->check_in, $startDate);
            $overlapEnd = min($transaction->check_out, $endDate);

            if ($overlapStart < $overlapEnd) {
                $overlapNights = $overlapStart->diffInDays($overlapEnd);
                $nightlyRate = $transaction->total_price / $transaction->check_in->diffInDays($transaction->check_out);
                $revenueForPeriod = $overlapNights * $nightlyRate;

                $totalRevenue += $revenueForPeriod;
                $totalNights += $overlapNights;
            }
        }

        return [
            'revenue' => $totalRevenue,
            'nights' => $totalNights,
            'average_rate' => $totalNights > 0 ? $totalRevenue / $totalNights : $this->price,
        ];
    }

    /**
     * Obtenir le temps moyen d'occupation
     */
    public function getAverageStayDuration()
    {
        $completedTransactions = $this->transactions()
            ->whereIn('status', ['completed'])
            ->where('check_out', '>=', now()->subYear())
            ->get();

        if ($completedTransactions->isEmpty()) {
            return 0;
        }

        $totalNights = 0;
        foreach ($completedTransactions as $transaction) {
            $totalNights += $transaction->check_in->diffInDays($transaction->check_out);
        }

        return round($totalNights / $completedTransactions->count(), 1);
    }

    /**
     * Obtenir les activités de la chambre
     */
    public function roomActivities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'subject_id')
            ->where('subject_type', self::class);
    }

    /**
     * Obtenir l'historique complet de la chambre
     */
    public function getHistoryAttribute()
    {
        $activities = $this->roomActivities()->orderBy('created_at', 'desc')->get();
        $transactions = $this->transactions()->orderBy('check_in', 'desc')->get();

        return [
            'activities' => $activities,
            'transactions' => $transactions,
            'stats' => [
                'total_transactions' => $transactions->count(),
                'completed_transactions' => $transactions->where('status', 'completed')->count(),
                'revenue_total' => $transactions->where('status', 'completed')->sum('total_price'),
                'average_stay' => $this->getAverageStayDuration(),
                'occupancy_rate' => $this->getOccupancyRate(now()->subMonth(), now()),
            ],
        ];
    }

    /**
     * Enregistrer une réservation
     */
    public function logBooking($transaction, $user)
    {
        activity()
            ->causedBy($user)
            ->performedOn($this)
            ->withProperties([
                'transaction_id' => $transaction->id,
                'customer' => $transaction->customer->name ?? 'N/A',
                'check_in' => $transaction->check_in->format('d/m/Y'),
                'check_out' => $transaction->check_out->format('d/m/Y'),
                'nights' => $transaction->getNightsAttribute(),
                'total_price' => $transaction->getTotalPrice(),
            ])
            ->log('a été réservée');
    }

    /**
     * Enregistrer le check-in
     */
    public function logCheckIn($transaction, $user)
    {
        activity()
            ->causedBy($user)
            ->performedOn($this)
            ->withProperties([
                'transaction_id' => $transaction->id,
                'customer' => $transaction->customer->name ?? 'N/A',
                'check_in_time' => $transaction->actual_check_in->format('d/m/Y H:i'),
                'person_count' => $transaction->person_count,
            ])
            ->log('a reçu un check-in');
    }

    /**
     * Enregistrer le check-out
     */
    public function logCheckOut($transaction, $user)
    {
        activity()
            ->causedBy($user)
            ->performedOn($this)
            ->withProperties([
                'transaction_id' => $transaction->id,
                'customer' => $transaction->customer->name ?? 'N/A',
                'check_out_time' => $transaction->actual_check_out->format('d/m/Y H:i'),
                'total_paid' => $transaction->getTotalPayment(),
                'revenue' => $transaction->getTotalPrice(),
            ])
            ->log('a reçu un check-out');
    }

    protected static function boot()
    {
        parent::boot();

        // Le logging est géré automatiquement par Spatie Activitylog
        // Pas besoin de code supplémentaire
    }

    /**
     * Obtenir les options de statut
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Disponible',
            self::STATUS_MAINTENANCE => 'En maintenance',
            self::STATUS_CLEANING => 'À nettoyer',
            self::STATUS_OCCUPIED => 'Occupée',
            self::STATUS_RESERVED => 'Réservée',
        ];
    }

    /**
     * Obtenir les statistiques résumées
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'type' => $this->type->name ?? 'N/A',
            'status' => $this->status_label,
            'status_color' => $this->status_color,
            'price' => $this->formatted_price,
            'capacity' => $this->capacity,
            'available_today' => $this->is_available_today,
            'next_available' => $this->next_available_date,
            'occupancy_rate' => $this->getOccupancyRate(now()->subMonth(), now()).'%',
            'average_stay' => $this->getAverageStayDuration().' nuits',
            'facilities' => $this->facilities->count(),
        ];
    }

    /**
     * Méthode helper pour obtenir le nom d'affichage
     */
    public function getNameOrNumber()
    {
        return $this->name ?? "Room #{$this->number}";
    }

    /**
     * Méthode pour mettre à jour le nom de la chambre
     */
    public function updateName($name, $user = null)
    {
        $oldName = $this->name;

        $this->update(['name' => $name]);

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($this)
                ->withProperties([
                    'old_name' => $oldName,
                    'new_name' => $name,
                ])
                ->log('a renommé la chambre');
        }

        return $this;
    }

    /**
     * Méthode pour obtenir les prochaines réservations
     */
    public function upcomingReservations($limit = 5)
    {
        return $this->transactions()
            ->whereIn('status', ['reservation', 'confirmed'])
            ->where('check_in', '>=', now())
            ->orderBy('check_in', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Méthode pour vérifier si la chambre est en maintenance
     */
    public function isUnderMaintenance()
    {
        return $this->room_status_id == self::STATUS_MAINTENANCE;
    }

    /**
     * Méthode pour vérifier si la chambre a besoin de nettoyage
     */
    public function needsCleaning()
    {
        return $this->room_status_id == self::STATUS_CLEANING;
    }

    /**
     * Méthode pour obtenir la durée depuis le dernier nettoyage
     */
    public function getDaysSinceLastCleaning()
    {
        if (! $this->last_cleaned_at) {
            return null;
        }

        return $this->last_cleaned_at->diffInDays(now());
    }

    /**
     * Méthode pour obtenir la durée de la maintenance en cours
     */
    public function getMaintenanceDuration()
    {
        if (! $this->maintenance_started_at) {
            return null;
        }

        return $this->maintenance_started_at->diffInHours(now());
    }

    /**
     * Méthode pour obtenir le type avec fallback
     */
    public function getTypeWithFallback()
    {
        return $this->type ?? (object) ['name' => 'Type inconnu', 'description' => ''];
    }

    /**
     * Méthode pour obtenir les images avec fallback
     */
    public function getImagesWithFallback()
    {
        if ($this->images->isEmpty()) {
            return [['url' => asset('img/default/default-room.png'), 'alt' => $this->display_name]];
        }

        return $this->images->map(function ($image) {
            return [
                'url' => $image->getRoomImage(),
                'alt' => $image->alt_text ?? $this->display_name,
            ];
        });
    }
}
