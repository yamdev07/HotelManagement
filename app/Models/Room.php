<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'room_status_id',
        'number',
        'capacity',
        'price',
        'view',
        'description',
        'size',
        'floor',
        'floor_plan_url'
    ];

    protected $appends = [
        'first_image_url',
        'occupancy_status',
        'is_available_today',
        'next_available_date',
        'formatted_price',
        'short_description',
        'facilities_list'
    ];

    // Constantes pour les statuts - VERSION COHÉRENTE
    const STATUS_AVAILABLE = 1;      // Disponible
    const STATUS_MAINTENANCE = 2;    // En maintenance
    const STATUS_CLEANING = 3;       // À nettoyer/En nettoyage
    const STATUS_OCCUPIED = 4;       // Occupée
    const STATUS_RESERVED = 5;       // Réservée (optionnel)

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
     * Relation avec les équipements (facilities)
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_room');
    }

    /**
     * Relation avec les transactions (réservations)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Réservations actives (check-in effectué)
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
     * Transaction actuelle (en cours aujourd'hui)
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
        
        // Vérifier d'abord le statut de la chambre
        if ($this->room_status_id != self::STATUS_AVAILABLE) {
            return false;
        }

        $query = $this->transactions()
            ->whereNotIn('status', ['cancelled', 'no_show', 'completed'])
            ->where(function($q) use ($checkIn, $checkOut) {
                // Chevauchement de dates
                $q->where(function($innerQ) use ($checkIn, $checkOut) {
                    // Réservation commence pendant le séjour demandé
                    $innerQ->where('check_in', '<', $checkOut)
                           ->where('check_out', '>', $checkIn);
                });
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
        
        // Vérifier les 90 prochains jours
        for ($i = 0; $i < 90; $i++) {
            $checkDate = $startDate->copy()->addDays($i);
            
            // Vérifier si disponible ce jour-là
            if (!$this->isOccupiedOnDate($checkDate) && $this->room_status_id == self::STATUS_AVAILABLE) {
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
            $isAvailable = !$this->isOccupiedOnDate($date) && $this->room_status_id == self::STATUS_AVAILABLE;
            
            if ($isAvailable) {
                if (!$currentPeriod) {
                    $currentPeriod = [
                        'start' => $date->copy(),
                        'end' => $date->copy()
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
                            'formatted_period' => $currentPeriod['start']->format('d/m/Y') . ' - ' . $currentPeriod['end']->format('d/m/Y')
                        ];
                    }
                    $currentPeriod = null;
                }
            }
            
            $date->addDay();
        }
        
        // Ajouter la dernière période
        if ($currentPeriod) {
            $duration = $currentPeriod['start']->diffInDays($currentPeriod['end']) + 1;
            if ($duration >= $minNights) {
                $periods[] = [
                    'start' => $currentPeriod['start']->format('Y-m-d'),
                    'end' => $currentPeriod['end']->format('Y-m-d'),
                    'nights' => $duration,
                    'total_price' => $this->price * $duration,
                    'formatted_period' => $currentPeriod['start']->format('d/m/Y') . ' - ' . $currentPeriod['end']->format('d/m/Y')
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
     * Accesseur: Disponible aujourd'hui
     */
    public function getIsAvailableTodayAttribute()
    {
        return $this->room_status_id == self::STATUS_AVAILABLE && 
               !$this->isOccupiedOnDate(now());
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
        return number_format($this->price, 0, ',', ' ') . ' FCFA/nuit';
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
            ? substr($this->description, 0, 100) . '...' 
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
            ->whereDoesntHave('transactions', function($q) use ($checkIn, $checkOut) {
                $q->whereIn('status', ['active', 'reservation', 'confirmed'])
                  ->where(function($sq) use ($checkIn, $checkOut) {
                      $sq->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                  });
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
            ->whereDoesntHave('transactions', function($q) {
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
        return $query->whereHas('transactions', function($q) {
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
    public function markAsCleaned()
    {
        // Déterminer le nouveau statut
        $isOccupied = $this->isOccupiedOnDate(now());
        $newStatus = $isOccupied ? self::STATUS_OCCUPIED : self::STATUS_AVAILABLE;
        
        $this->update([
            'room_status_id' => $newStatus,
            'last_cleaned_at' => now(),
        ]);
        
        return $this;
    }

    /**
     * Marquer comme en maintenance
     */
    public function markAsMaintenance($reason = null)
    {
        $data = [
            'room_status_id' => self::STATUS_MAINTENANCE,
            'maintenance_started_at' => now(),
        ];
        
        if ($reason) {
            $data['maintenance_reason'] = $reason;
        }
        
        $this->update($data);
        
        return $this;
    }

    /**
     * Marquer comme à nettoyer
     */
    public function markAsNeedsCleaning()
    {
        $this->update([
            'room_status_id' => self::STATUS_CLEANING,
        ]);
        
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
        
        // Calculer l'occupation réelle basée sur les transactions
        $actualOccupied = self::whereHas('transactions', function($q) {
            $q->whereIn('status', ['active', 'reservation'])
              ->where('check_in', '<=', now())
              ->where('check_out', '>', now());
        })->count();
        
        $stats = [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $actualOccupied, // Utiliser l'occupation réelle
            'maintenance_rooms' => $maintenanceRooms,
            'cleaning_rooms' => $cleaningRooms,
            'occupancy_rate' => $totalRooms > 0 ? round(($actualOccupied / $totalRooms) * 100, 1) : 0
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
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in', [$startDate, $endDate])
                  ->orWhereBetween('check_out', [$startDate, $endDate])
                  ->orWhere(function($inner) use ($startDate, $endDate) {
                      $inner->where('check_in', '<', $startDate)
                            ->where('check_out', '>', $endDate);
                  });
            })
            ->get();
        
        $totalRevenue = 0;
        $totalNights = 0;
        
        foreach ($transactions as $transaction) {
            // Calculer la portion de la réservation dans la période
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
            'average_rate' => $totalNights > 0 ? $totalRevenue / $totalNights : $this->price
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
}