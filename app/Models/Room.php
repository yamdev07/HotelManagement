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
        'description'
    ];

    protected $appends = [
        'first_image_url',
        'occupancy_status',
        'is_available_today',
        'next_available_date'
    ];

    // Constantes pour les statuts
    const STATUS_AVAILABLE = 1;
    const STATUS_OCCUPIED = 2;
    const STATUS_MAINTENANCE = 3;
    const STATUS_CLEANING = 4;

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function roomStatus()
    {
        return $this->belongsTo(RoomStatus::class);
    }

    public function image()
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
     * Réservations actives
     */
    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class)->where('status', 'active');
    }

    /**
     * Réservations à venir
     */
    public function upcomingTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'reservation')
            ->where('check_in', '>=', now());
    }

    /**
     * Réservations terminées récentes
     */
    public function recentCompletedTransactions()
    {
        return $this->hasMany(Transaction::class)
            ->where('status', 'completed')
            ->where('check_out', '>=', now()->subDays(30));
    }

    /**
     * Obtenir la première image
     */
    public function firstImage()
    {
        if ($this->relationLoaded('image') && $this->image && $this->image->isNotEmpty()) {
            $firstImage = $this->image->first();
            
            if (method_exists($firstImage, 'getRoomImage')) {
                return $firstImage->getRoomImage();
            }
            
            return $firstImage->url ?? asset('img/default/default-room.png');
        }
        
        $image = $this->image()->first();
        if ($image) {
            return $image->getRoomImage();
        }

        return asset('img/default/default-room.png');
    }
    
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
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function($q) use ($checkIn, $checkOut) {
                // Chevauchement de dates
                $q->where(function($innerQ) use ($checkIn, $checkOut) {
                    // Réservation commence pendant le séjour demandé
                    $innerQ->where('check_in', '>=', $checkIn)
                           ->where('check_in', '<', $checkOut);
                })
                ->orWhere(function($innerQ) use ($checkIn, $checkOut) {
                    // Réservation se termine pendant le séjour demandé
                    $innerQ->where('check_out', '>', $checkIn)
                           ->where('check_out', '<=', $checkOut);
                })
                ->orWhere(function($innerQ) use ($checkIn, $checkOut) {
                    // Réservation englobe la période demandée
                    $innerQ->where('check_in', '<=', $checkIn)
                           ->where('check_out', '>=', $checkOut);
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
            ->whereDate('check_in', '<=', $date)
            ->whereDate('check_out', '>', $date)
            ->with('customer')
            ->get();
    }

    /**
     * Obtenir les réservations pour une période
     */
    public function getReservationsForPeriod($checkIn, $checkOut)
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();
        
        return $this->transactions()
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function($sq) use ($checkIn, $checkOut) {
                      $sq->where('check_in', '<', $checkIn)
                         ->where('check_out', '>', $checkOut);
                  });
            })
            ->with(['customer', 'room.type'])
            ->orderBy('check_in')
            ->get();
    }

    /**
     * Vérifier si occupé à une date spécifique
     */
    public function isOccupiedOnDate($date)
    {
        $date = Carbon::parse($date)->startOfDay();
        
        return $this->transactions()
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->whereDate('check_in', '<=', $date)
            ->whereDate('check_out', '>', $date)
            ->exists();
    }

    /**
     * Obtenir l'occupation par jour pour un mois
     */
    public function getMonthlyOccupancy($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();
        
        $period = CarbonPeriod::create($startDate, $endDate);
        $occupancy = [];
        
        foreach ($period as $date) {
            $occupancy[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->day,
                'occupied' => $this->isOccupiedOnDate($date),
                'reservations' => $this->getReservationsForDate($date)
            ];
        }
        
        return $occupancy;
    }

    /**
     * Obtenir la prochaine date disponible
     */
    public function getNextAvailableDate($startFrom = null)
    {
        $startDate = $startFrom ? Carbon::parse($startFrom) : now();
        $startDate = $startDate->startOfDay();
        
        // Vérifier les 60 prochains jours
        for ($i = 0; $i < 60; $i++) {
            $checkDate = $startDate->copy()->addDays($i);
            
            // Vérifier si disponible ce jour-là
            if (!$this->isOccupiedOnDate($checkDate)) {
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
            if (!$this->isOccupiedOnDate($date)) {
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
                            'duration' => $duration,
                            'total_price' => $this->price * $duration
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
                    'duration' => $duration,
                    'total_price' => $this->price * $duration
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
        
        return $totalDays > 0 ? ($occupiedDays / $totalDays) * 100 : 0;
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
     * Scope: Chambres disponibles pour une période
     */
    public function scopeAvailableForPeriod($query, $checkIn, $checkOut)
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();
        
        return $query->where('room_status_id', self::STATUS_AVAILABLE)
            ->whereDoesntHave('transactions', function($q) use ($checkIn, $checkOut) {
                $q->whereNotIn('status', ['cancelled', 'no_show'])
                  ->where(function($sq) use ($checkIn, $checkOut) {
                      $sq->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function($inner) use ($checkIn, $checkOut) {
                            $inner->where('check_in', '<', $checkIn)
                                  ->where('check_out', '>', $checkOut);
                        });
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
     * Obtenir les statistiques d'occupation
     */
    public static function getOccupancyStats($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfMonth();
        
        $rooms = self::with(['transactions' => function($q) use ($startDate, $endDate) {
            $q->whereNotIn('status', ['cancelled', 'no_show'])
              ->where(function($sq) use ($startDate, $endDate) {
                  $sq->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function($inner) use ($startDate, $endDate) {
                        $inner->where('check_in', '<', $startDate)
                              ->where('check_out', '>', $endDate);
                    });
              });
        }])->get();
        
        $stats = [
            'total_rooms' => $rooms->count(),
            'available_rooms' => $rooms->where('room_status_id', self::STATUS_AVAILABLE)->count(),
            'occupied_rooms' => $rooms->where('room_status_id', self::STATUS_OCCUPIED)->count(),
            'maintenance_rooms' => $rooms->where('room_status_id', self::STATUS_MAINTENANCE)->count(),
            'cleaning_rooms' => $rooms->where('room_status_id', self::STATUS_CLEANING)->count(),
            'occupancy_rate' => 0
        ];
        
        // Calculer le taux d'occupation
        $totalRooms = $stats['total_rooms'];
        $occupiedRooms = $stats['occupied_rooms'];
        
        if ($totalRooms > 0) {
            $stats['occupancy_rate'] = ($occupiedRooms / $totalRooms) * 100;
        }
        
        return $stats;
    }

    /**
     * Formater le prix
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' CFA/nuit';
    }

    /**
     * Obtenir la description courte
     */
    public function getShortDescriptionAttribute()
    {
        return strlen($this->description) > 100 
            ? substr($this->description, 0, 100) . '...' 
            : $this->description;
    }

    /**
     * Vérifier si la chambre a un équipement spécifique
     */
    public function hasFacility($facilityId)
    {
        return $this->facilities->contains('id', $facilityId);
    }

    /**
     * Obtenir la liste des équipements
     */
    public function getFacilitiesListAttribute()
    {
        return $this->facilities->pluck('name')->join(', ');
    }
}