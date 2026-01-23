<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Type;
use App\Models\Transaction;
use App\Models\RoomStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AvailabilityController extends Controller
{
    /**
     * Vue principale - Calendrier des disponibilités
     */
    public function calendar(Request $request)
    {
        try {
            // Dates par défaut - version améliorée
            $currentMonth = now()->format('m');
            $currentYear = now()->format('Y');
            
            $month = $request->get('month', $currentMonth);
            $year = $request->get('year', $currentYear);
            $roomType = $request->get('room_type');
            
            // Validation et conversion des dates
            $month = (int)$month;
            $year = (int)$year;
            
            if ($month < 1 || $month > 12) {
                $month = $currentMonth;
            }
            if ($year < 2020 || $year > 2100) {
                $year = $currentYear;
            }
            
            // Créer les dates avec validation
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth()->endOfDay();
            $daysInMonth = $startDate->daysInMonth;
            
            // Types de chambres
            $roomTypes = Type::with(['rooms' => function($query) {
                $query->orderBy('number');
            }])->active()->ordered()->get();
            
            // Filtrer les chambres par type si spécifié
            $roomsQuery = Room::with(['type', 'roomStatus']);
            
            if ($roomType) {
                $roomsQuery->where('type_id', $roomType);
            }
            
            $rooms = $roomsQuery->orderBy('number')->get();
            
            // Générer TOUTES les dates du mois avec un format cohérent
            $dates = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $dateString = $date->format('Y-m-d');
                
                $dates[$dateString] = [
                    'date' => $date,
                    'formatted' => $dateString,
                    'day_name' => $date->locale('fr')->isoFormat('ddd'), // En français
                    'day_name_short' => $date->format('D'),
                    'is_today' => $date->isToday(),
                    'is_weekend' => $date->isWeekend(),
                    'day_number' => $day,
                    'month_day' => $date->format('d/m')
                ];
            }
            
            // Récupérer toutes les transactions pour la période en une seule requête optimisée
            $transactions = Transaction::where(function($query) use ($startDate, $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    // Chevauchement des dates
                    $q->where('check_in', '<=', $endDate)
                      ->where('check_out', '>=', $startDate);
                });
            })
            ->whereIn('status', ['reservation', 'active', 'checked_out'])
            ->with(['customer', 'room'])
            ->get()
            ->groupBy('room_id'); // Grouper par chambre pour optimisation
            
            // Préparer les données du calendrier
            $calendar = [];
            
            foreach ($rooms as $room) {
                $roomData = [
                    'room' => $room,
                    'availability' => []
                ];
                
                // Récupérer les transactions de cette chambre
                $roomTransactions = $transactions->get($room->id, collect());
                
                foreach ($dates as $dateString => $dateInfo) {
                    $date = $dateInfo['date'];
                    
                    // Vérifier si la chambre est occupée à cette date
                    $isOccupied = false;
                    $reservations = collect();
                    
                    foreach ($roomTransactions as $transaction) {
                        // Vérifier si la date est dans la période de réservation
                        if ($date->between(
                            $transaction->check_in->copy()->startOfDay(),
                            $transaction->check_out->copy()->subDay()->endOfDay() // -1 jour pour départ
                        )) {
                            $isOccupied = true;
                            $reservations->push([
                                'customer' => $transaction->customer->name ?? 'Client inconnu',
                                'check_in' => $transaction->check_in->format('d/m/Y'),
                                'check_out' => $transaction->check_out->format('d/m/Y'),
                                'status' => $transaction->status,
                                'transaction_id' => $transaction->id
                            ]);
                        }
                    }
                    
                    // Déterminer la classe CSS
                    $cssClass = 'available';
                    if ($isOccupied) {
                        $cssClass = 'occupied';
                    } elseif ($room->room_status_id != 1) { // STATUT_AVAILABLE = 1
                        $cssClass = 'unavailable';
                    }
                    
                    $roomData['availability'][$dateString] = [
                        'occupied' => $isOccupied,
                        'date' => $dateString,
                        'date_obj' => $date,
                        'reservations' => $reservations,
                        'css_class' => $cssClass,
                        'has_reservations' => $reservations->isNotEmpty(),
                        'reservation_count' => $reservations->count()
                    ];
                }
                
                $calendar[] = $roomData;
            }
            
            // Navigation
            $prevMonth = $startDate->copy()->subMonth();
            $nextMonth = $startDate->copy()->addMonth();
            
            // Statistiques améliorées
            $today = now();
            $stats = $this->calculateCalendarStats($rooms, $transactions, $today);
            
            // Pour les vues qui ont besoin de month_year
            $monthYear = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            
            return view('availability.calendar', compact(
                'calendar',
                'dates',
                'roomTypes',
                'rooms',
                'startDate',
                'endDate',
                'month',
                'year',
                'monthYear',
                'prevMonth',
                'nextMonth',
                'roomType',
                'stats',
                'transactions'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Calendar error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return back()->with('error', 'Erreur lors du chargement du calendrier: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculer les statistiques du calendrier
     */
    private function calculateCalendarStats($rooms, $transactions, $today)
    {
        // Chambres disponibles aujourd'hui
        $availableToday = 0;
        $occupiedToday = 0;
        $unavailableToday = 0;
        
        foreach ($rooms as $room) {
            // Vérifier si la chambre est occupée aujourd'hui
            $isOccupiedToday = false;
            $roomTrans = $transactions->get($room->id, collect());
            
            foreach ($roomTrans as $transaction) {
                if ($today->between(
                    $transaction->check_in->copy()->startOfDay(),
                    $transaction->check_out->copy()->subDay()->endOfDay()
                )) {
                    $isOccupiedToday = true;
                    break;
                }
            }
            
            if ($isOccupiedToday) {
                $occupiedToday++;
            } elseif ($room->room_status_id == 1) {
                $availableToday++;
            } else {
                $unavailableToday++;
            }
        }
        
        // Compter les arrivées et départs
        $arrivalsCount = 0;
        $departuresCount = 0;
        
        foreach ($transactions->flatten() as $transaction) {
            if ($transaction->check_in->isToday()) {
                $arrivalsCount++;
            }
            if ($transaction->check_out->isToday()) {
                $departuresCount++;
            }
        }
        
        return [
            'total_rooms' => $rooms->count(),
            'available_today' => $availableToday,
            'occupied_today' => $occupiedToday,
            'unavailable_today' => $unavailableToday,
            'today_arrivals' => $arrivalsCount,
            'today_departures' => $departuresCount,
            'occupancy_rate' => $rooms->count() > 0 ? 
                round(($occupiedToday / $rooms->count()) * 100, 1) : 0
        ];
    }
    
    /**
     * Recherche de disponibilité avec améliorations
     */
    public function search(Request $request)
    {
        try {
            // Valeurs par défaut
            $defaultCheckIn = now()->format('Y-m-d');
            $defaultCheckOut = now()->addDays(2)->format('Y-m-d');
            
            $checkIn = $request->get('check_in', $defaultCheckIn);
            $checkOut = $request->get('check_out', $defaultCheckOut);
            $roomTypeId = $request->get('room_type_id');
            $adults = (int)$request->get('adults', 1);
            $children = (int)$request->get('children', 0);
            $totalGuests = $adults + $children;
            
            // Validation des dates
            $checkInDate = Carbon::parse($checkIn)->startOfDay();
            $checkOutDate = Carbon::parse($checkOut)->startOfDay();
            
            if ($checkInDate->greaterThanOrEqualTo($checkOutDate)) {
                return back()->with('error', 'La date de départ doit être après la date d\'arrivée');
            }
            
            $nights = $checkInDate->diffInDays($checkOutDate);
            
            if ($nights > 30) {
                return back()->with('warning', 'La recherche est limitée à 30 nuits maximum');
            }
            
            // Recherche optimisée des chambres disponibles
            $query = Room::with(['type', 'facilities', 'roomStatus'])
                ->where('capacity', '>=', $totalGuests);
            
            if ($roomTypeId) {
                $query->where('type_id', $roomTypeId);
            }
            
            $allRooms = $query->orderBy('type_id')->orderBy('number')->get();
            
            // Récupérer les transactions conflictuelles en une seule requête
            $conflictingTransactions = Transaction::whereIn('room_id', $allRooms->pluck('id'))
                ->where(function($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function($q) use ($checkInDate, $checkOutDate) {
                        // Chevauchement de dates
                        $q->where('check_in', '<', $checkOutDate)
                          ->where('check_out', '>', $checkInDate);
                    });
                })
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->with('customer')
                ->get()
                ->groupBy('room_id');
            
            // Séparer les chambres disponibles et indisponibles
            $availableRooms = [];
            $unavailableRooms = [];
            $roomConflicts = [];
            
            foreach ($allRooms as $room) {
                $conflicts = $conflictingTransactions->get($room->id, collect());
                
                if ($conflicts->isEmpty() && $room->room_status_id == 1) {
                    // Chambre disponible
                    $totalPrice = $room->price * $nights;
                    $availableRooms[] = [
                        'room' => $room,
                        'total_price' => $totalPrice,
                        'available' => true,
                        'price_per_night' => $room->price,
                        'nights' => $nights,
                        'formatted_price' => number_format($totalPrice, 0, ',', ' ') . ' CFA'
                    ];
                } else {
                    // Chambre indisponible
                    $unavailableRooms[] = $room;
                    
                    if ($conflicts->isNotEmpty()) {
                        $roomConflicts[$room->id] = $conflicts->map(function($transaction) {
                            return [
                                'id' => $transaction->id,
                                'customer' => $transaction->customer->name ?? 'Client inconnu',
                                'check_in' => $transaction->check_in->format('d/m/Y'),
                                'check_out' => $transaction->check_out->format('d/m/Y'),
                                'status' => $transaction->status_label ?? $transaction->status,
                                'status_class' => $this->getStatusClass($transaction->status)
                            ];
                        });
                    }
                }
            }
            
            $roomTypes = Type::orderBy('name')->get();
            
            // Données pour la vue
            $searchData = [
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'nights' => $nights,
                'adults' => $adults,
                'children' => $children,
                'total_guests' => $totalGuests,
                'room_type_id' => $roomTypeId,
                'available_count' => count($availableRooms),
                'unavailable_count' => count($unavailableRooms)
            ];
            
            return view('availability.search', compact(
                'availableRooms',
                'unavailableRooms',
                'roomConflicts',
                'roomTypes',
                'searchData'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Search availability error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la recherche de disponibilité');
        }
    }
    
    /**
     * Classe CSS pour le statut
     */
    private function getStatusClass($status)
    {
        $classes = [
            'reservation' => 'warning',
            'active' => 'success',
            'checked_out' => 'info',
            'cancelled' => 'danger',
            'no_show' => 'secondary'
        ];
        
        return $classes[$status] ?? 'secondary';
    }
    
    /**
     * Inventaire des chambres amélioré
     */
    public function inventory()
    {
        try {
            $today = now();
            
            // Types avec statistiques
            $roomTypes = Type::with(['rooms' => function($query) {
                $query->with(['roomStatus', 'currentTransaction.customer']);
            }])->get();
            
            // Compter les transactions en cours
            $activeTransactions = Transaction::where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->with(['room', 'customer'])
                ->get()
                ->groupBy('room_id');
            
            // Statistiques globales
            $totalRooms = Room::count();
            $occupiedRooms = $activeTransactions->count();
            $availableRooms = Room::where('room_status_id', 1)
                ->whereNotIn('id', $activeTransactions->keys())
                ->count();
            
            $stats = [
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'occupied_rooms' => $occupiedRooms,
                'maintenance_rooms' => Room::where('room_status_id', 2)->count(), // Maintenance
                'cleaning_rooms' => Room::where('room_status_id', 3)->count(),   // Nettoyage
                'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0
            ];
            
            // Arrivées du jour
            $todayArrivals = Transaction::with(['room', 'customer'])
                ->where('status', 'reservation')
                ->whereDate('check_in', $today)
                ->orderBy('check_in')
                ->get();
            
            // Départs du jour
            $todayDepartures = Transaction::with(['room', 'customer'])
                ->where('status', 'active')
                ->whereDate('check_out', $today)
                ->orderBy('check_out')
                ->get();
            
            // Chambres par statut avec détails
            $roomsByStatus = Room::with(['roomStatus', 'type'])
                ->get()
                ->groupBy('room_status_id');
            
            // Occupation par type
            $occupancyByType = [];
            foreach ($roomTypes as $type) {
                $typeRooms = $type->rooms;
                $totalRoomsType = $typeRooms->count();
                
                // Compter les chambres occupées de ce type
                $occupiedTypeRooms = 0;
                foreach ($typeRooms as $room) {
                    if ($activeTransactions->has($room->id)) {
                        $occupiedTypeRooms++;
                    }
                }
                
                $occupancyByType[$type->name] = [
                    'total' => $totalRoomsType,
                    'occupied' => $occupiedTypeRooms,
                    'available' => $totalRoomsType - $occupiedTypeRooms,
                    'percentage' => $totalRoomsType > 0 ? 
                        round(($occupiedTypeRooms / $totalRoomsType) * 100, 1) : 0,
                    'type' => $type
                ];
            }
            
            // Chambres nécessitant un nettoyage
            $roomsNeedingCleaning = Room::where('room_status_id', 3) // CLEANING
                ->with('type')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            // Chambres en maintenance
            $roomsInMaintenance = Room::where('room_status_id', 2) // MAINTENANCE
                ->with('type')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            return view('availability.inventory', compact(
                'roomTypes',
                'stats',
                'todayArrivals',
                'todayDepartures',
                'roomsByStatus',
                'occupancyByType',
                'roomsNeedingCleaning',
                'roomsInMaintenance',
                'activeTransactions'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Inventory error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de l\'inventaire');
        }
    }
    
    /**
     * Détail d'une chambre avec données enrichies
     */
    public function roomDetail(Room $room)
    {
        try {
            $room->load(['type', 'roomStatus', 'facilities', 'transactions.customer']);
            
            $today = now();
            
            // Transaction en cours
            $currentTransaction = Transaction::where('room_id', $room->id)
                ->where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->with('customer')
                ->first();
            
            // Calendrier des 30 prochains jours avec occupation
            $calendar = [];
            for ($i = 0; $i < 30; $i++) {
                $date = $today->copy()->addDays($i);
                $dateString = $date->format('Y-m-d');
                
                // Vérifier l'occupation
                $isOccupied = Transaction::where('room_id', $room->id)
                    ->where('check_in', '<=', $date)
                    ->where('check_out', '>=', $date)
                    ->whereIn('status', ['active', 'reservation'])
                    ->exists();
                
                $calendar[$dateString] = [
                    'date' => $date,
                    'formatted' => $date->format('d/m'),
                    'day_name' => $date->locale('fr')->isoFormat('ddd'),
                    'occupied' => $isOccupied,
                    'css_class' => $isOccupied ? 'occupied' : ($room->room_status_id == 1 ? 'available' : 'unavailable'),
                    'is_today' => $i == 0,
                    'is_weekend' => $date->isWeekend()
                ];
            }
            
            // Statistiques de la chambre
            $last30DaysTransactions = Transaction::where('room_id', $room->id)
                ->where('check_in', '>=', $today->copy()->subDays(30))
                ->whereIn('status', ['active', 'checked_out', 'reservation'])
                ->get();
            
            $roomStats = $this->calculateRoomStats($room, $last30DaysTransactions);
            
            // Historique des transactions récentes
            $recentTransactions = Transaction::where('room_id', $room->id)
                ->with('customer')
                ->orderBy('check_in', 'desc')
                ->limit(10)
                ->get();
            
            // Prochaine réservation
            $nextReservation = Transaction::where('room_id', $room->id)
                ->where('check_in', '>', $today)
                ->where('status', 'reservation')
                ->with('customer')
                ->orderBy('check_in')
                ->first();
            
            return view('availability.room-detail', compact(
                'room',
                'calendar',
                'roomStats',
                'currentTransaction',
                'recentTransactions',
                'nextReservation'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Room detail error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des détails de la chambre');
        }
    }
    
    /**
     * Calculer les statistiques d'une chambre
     */
    private function calculateRoomStats($room, $transactions)
    {
        $totalNights = 0;
        $totalRevenue = 0;
        $occupancyDays = 0;
        
        foreach ($transactions as $transaction) {
            $nights = $transaction->check_in->diffInDays($transaction->check_out);
            $totalNights += $nights;
            $totalRevenue += $transaction->total_price;
            
            // Compter les jours d'occupation dans les 30 derniers jours
            $start = max($transaction->check_in, now()->subDays(30));
            $end = min($transaction->check_out, now());
            
            if ($start <= $end) {
                $occupancyDays += $start->diffInDays($end);
            }
        }
        
        $avgStayDuration = $transactions->count() > 0 ? 
            round($totalNights / $transactions->count(), 1) : 0;
        
        $occupancyRate30d = min(100, round(($occupancyDays / 30) * 100, 1));
        
        // Prochaine date disponible
        $nextAvailable = Transaction::where('room_id', $room->id)
            ->where('check_out', '>', now())
            ->whereIn('status', ['active', 'reservation'])
            ->orderBy('check_out')
            ->first();
        
        $nextAvailableDate = $nextAvailable ? 
            $nextAvailable->check_out->copy()->addDay() : now();
        
        return [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $totalRevenue,
            'avg_stay_duration' => $avgStayDuration,
            'avg_daily_rate' => $totalNights > 0 ? 
                round($totalRevenue / $totalNights, 0) : $room->price,
            'occupancy_rate_30d' => $occupancyRate30d,
            'next_available' => $nextAvailableDate,
            'formatted_next_available' => $nextAvailableDate->format('d/m/Y'),
            'last_30_days_revenue' => $totalRevenue
        ];
    }
    
    /**
     * Dashboard de disponibilité optimisé
     */
    public function dashboard()
    {
        try {
            $today = now();
            $tomorrow = $today->copy()->addDay();
            
            // Statistiques globales en une seule requête
            $stats = DB::table('rooms')
                ->selectRaw('
                    COUNT(*) as total_rooms,
                    SUM(CASE WHEN room_status_id = 1 THEN 1 ELSE 0 END) as available_rooms,
                    SUM(CASE WHEN room_status_id = 2 THEN 1 ELSE 0 END) as maintenance_rooms,
                    SUM(CASE WHEN room_status_id = 3 THEN 1 ELSE 0 END) as cleaning_rooms
                ')
                ->first();
            
            // Compter les chambres occupées
            $occupiedRooms = Transaction::where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->distinct('room_id')
                ->count('room_id');
            
            $stats->occupied_rooms = $occupiedRooms;
            $stats->occupancy_rate = $stats->total_rooms > 0 ? 
                round(($occupiedRooms / $stats->total_rooms) * 100, 1) : 0;
            
            // Arrivées des 3 prochains jours
            $upcomingArrivals = Transaction::with(['room.type', 'customer'])
                ->where('status', 'reservation')
                ->whereBetween('check_in', [$today, $today->copy()->addDays(3)])
                ->orderBy('check_in')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_in->format('Y-m-d');
                });
            
            // Départs des 3 prochains jours
            $upcomingDepartures = Transaction::with(['room.type', 'customer'])
                ->where('status', 'active')
                ->whereBetween('check_out', [$today, $today->copy()->addDays(3)])
                ->orderBy('check_out')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_out->format('Y-m-d');
                });
            
            // Chambres disponibles maintenant
            $availableNow = Room::where('room_status_id', 1)
                ->whereNotIn('id', function($query) use ($today) {
                    $query->select('room_id')
                          ->from('transactions')
                          ->where('check_in', '<=', $today)
                          ->where('check_out', '>=', $today)
                          ->whereIn('status', ['active', 'reservation']);
                })
                ->with('type')
                ->orderBy('type_id')
                ->orderBy('number')
                ->limit(15)
                ->get();
            
            // Chambres nécessitant attention
            $attentionRooms = Room::whereIn('room_status_id', [2, 3, 4]) // Maintenance, Cleaning, Out of Service
                ->with(['type', 'roomStatus'])
                ->orderBy('room_status_id')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            // Occupation par type aujourd'hui
            $occupancyByType = Type::with(['rooms' => function($query) {
                $query->withCount(['transactions' => function($q) {
                    $q->where('check_in', '<=', now())
                      ->where('check_out', '>=', now())
                      ->whereIn('status', ['active', 'reservation']);
                }]);
            }])->get()->map(function($type) {
                $totalRooms = $type->rooms->count();
                $occupiedRooms = $type->rooms->sum('transactions_count');
                
                return [
                    'type' => $type->name,
                    'total' => $totalRooms,
                    'occupied' => $occupiedRooms,
                    'available' => $totalRooms - $occupiedRooms,
                    'percentage' => $totalRooms > 0 ? 
                        round(($occupiedRooms / $totalRooms) * 100, 1) : 0,
                    'type_id' => $type->id
                ];
            });
            
            return view('availability.dashboard', compact(
                'stats',
                'upcomingArrivals',
                'upcomingDepartures',
                'availableNow',
                'attentionRooms',
                'occupancyByType',
                'today',
                'tomorrow'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du dashboard');
        }
    }
    
    /**
     * API: Vérifier disponibilité optimisée (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'exclude_transaction_id' => 'nullable|exists:transactions,id'
            ]);
            
            $room = Room::with('type')->findOrFail($request->room_id);
            
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            
            // Vérifier la disponibilité
            $isAvailable = !Transaction::where('room_id', $room->id)
                ->when($request->exclude_transaction_id, function($query, $excludeId) {
                    $query->where('id', '!=', $excludeId);
                })
                ->whereIn('status', ['active', 'reservation', 'checked_out'])
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where(function($q) use ($checkIn, $checkOut) {
                        // Chevauchement
                        $q->where('check_in', '<', $checkOut)
                          ->where('check_out', '>', $checkIn);
                    });
                })
                ->exists();
            
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price * $nights;
            
            $response = [
                'available' => $isAvailable,
                'room' => [
                    'id' => $room->id,
                    'number' => $room->number,
                    'type' => $room->type->name ?? 'N/A',
                    'price' => $room->price,
                    'capacity' => $room->capacity,
                    'formatted_price' => number_format($room->price, 0, ',', ' ') . ' CFA/nuit'
                ],
                'dates' => [
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d'),
                    'formatted_check_in' => $checkIn->format('d/m/Y'),
                    'formatted_check_out' => $checkOut->format('d/m/Y')
                ],
                'nights' => $nights,
                'pricing' => [
                    'total_price' => $totalPrice,
                    'formatted_total_price' => number_format($totalPrice, 0, ',', ' ') . ' CFA'
                ]
            ];
            
            if (!$isAvailable) {
                // Obtenir les conflits
                $conflicts = Transaction::where('room_id', $room->id)
                    ->whereIn('status', ['active', 'reservation'])
                    ->where(function($query) use ($checkIn, $checkOut) {
                        $query->where(function($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<', $checkOut)
                              ->where('check_out', '>', $checkIn);
                        });
                    })
                    ->with('customer')
                    ->get();
                
                $response['conflicts'] = $conflicts->map(function($transaction) {
                    return [
                        'id' => $transaction->id,
                        'customer' => $transaction->customer->name ?? 'Client inconnu',
                        'check_in' => $transaction->check_in->format('d/m/Y'),
                        'check_out' => $transaction->check_out->format('d/m/Y'),
                        'status' => $transaction->status,
                        'status_label' => $transaction->status_label ?? $transaction->status
                    ];
                });
                
                // Proposer la prochaine date disponible
                $nextBooking = Transaction::where('room_id', $room->id)
                    ->where('check_out', '>', $checkOut)
                    ->whereIn('status', ['active', 'reservation'])
                    ->orderBy('check_out')
                    ->first();
                
                if ($nextBooking) {
                    $nextAvailableDate = $nextBooking->check_out->copy()->addDay();
                    $response['suggestions'] = [
                        'next_available' => $nextAvailableDate->format('Y-m-d'),
                        'formatted_next_available' => $nextAvailableDate->format('d/m/Y'),
                        'message' => "Disponible à partir du " . $nextAvailableDate->format('d/m/Y')
                    ];
                }
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Check availability API error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la vérification de disponibilité',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Détails d'une cellule du calendrier
     */
    public function calendarCellDetails(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'date' => 'required|date'
            ]);
            
            $room = Room::with('type')->findOrFail($request->room_id);
            $date = Carbon::parse($request->date)->startOfDay();
            
            // Réservations pour cette date
            $transactions = Transaction::where('room_id', $room->id)
                ->where('check_in', '<=', $date)
                ->where('check_out', '>=', $date)
                ->whereIn('status', ['active', 'reservation'])
                ->with('customer')
                ->get();
            
            $isOccupied = $transactions->isNotEmpty();
            
            $response = [
                'room' => [
                    'id' => $room->id,
                    'number' => $room->number,
                    'type' => $room->type->name ?? 'N/A',
                    'price' => $room->price,
                    'capacity' => $room->capacity
                ],
                'date' => [
                    'iso' => $date->format('Y-m-d'),
                    'formatted' => $date->format('d/m/Y'),
                    'day_name' => $date->locale('fr')->isoFormat('dddd')
                ],
                'is_occupied' => $isOccupied,
                'status' => $isOccupied ? 'Occupée' : ($room->room_status_id == 1 ? 'Disponible' : 'Indisponible'),
                'status_class' => $isOccupied ? 'occupied' : ($room->room_status_id == 1 ? 'available' : 'unavailable')
            ];
            
            if ($isOccupied) {
                $response['reservations'] = $transactions->map(function($transaction) {
                    return [
                        'id' => $transaction->id,
                        'customer' => $transaction->customer->name ?? 'Client inconnu',
                        'customer_id' => $transaction->customer_id,
                        'check_in' => $transaction->check_in->format('d/m/Y'),
                        'check_out' => $transaction->check_out->format('d/m/Y'),
                        'status' => $transaction->status,
                        'status_label' => $transaction->status_label ?? $transaction->status,
                        'guests' => $transaction->person_count ?? 1,
                        'total_price' => $transaction->total_price,
                        'formatted_total_price' => number_format($transaction->total_price, 0, ',', ' ') . ' CFA'
                    ];
                });
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Calendar cell details error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la récupération des détails',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}