<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Type;
use App\Models\Transaction;
use App\Models\RoomStatus;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Exports\AvailabilityExport;
use App\Exports\CalendarExport;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;

class AvailabilityController extends Controller
{
    /**
     * Vue principale - Calendrier des disponibilités
     */
    public function calendar(Request $request)
    {
        try {
            // Dates par défaut
            $currentMonth = now()->format('m');
            $currentYear = now()->format('Y');
            
            $month = $request->get('month', $currentMonth);
            $year = $request->get('year', $currentYear);
            $roomType = $request->get('room_type');
            
            // Validation
            $month = (int)$month;
            $year = (int)$year;
            
            if ($month < 1 || $month > 12) $month = $currentMonth;
            if ($year < 2020 || $year > 2100) $year = $currentYear;
            
            // Dates du mois
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth()->endOfDay();
            $daysInMonth = $startDate->daysInMonth;
            
            // Types de chambres
            $roomTypes = Type::with(['rooms' => function($query) {
                $query->orderBy('number');
            }])->active()->ordered()->get();
            
            // Filtrer les chambres
            $roomsQuery = Room::with(['type', 'roomStatus']);
            if ($roomType) {
                $roomsQuery->where('type_id', $roomType);
            }
            $rooms = $roomsQuery->orderBy('number')->get();
            
            // Générer les dates
            $dates = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $dateString = $date->format('Y-m-d');
                
                $dates[$dateString] = [
                    'date' => $date,
                    'formatted' => $dateString,
                    'day_name' => $date->locale('fr')->isoFormat('ddd'),
                    'is_today' => $date->isToday(),
                    'is_weekend' => $date->isWeekend(),
                    'day_number' => $day,
                    'month_day' => $date->format('d/m')
                ];
            }
            
            // Transactions du mois
            $transactions = Transaction::where(function($query) use ($startDate, $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->where('check_in', '<=', $endDate)
                      ->where('check_out', '>=', $startDate);
                });
            })
            ->whereIn('status', ['reservation', 'active', 'checked_out'])
            ->with(['customer', 'room'])
            ->get()
            ->groupBy('room_id');
            
            // Préparer le calendrier
            $calendar = [];
            foreach ($rooms as $room) {
                $roomData = ['room' => $room, 'availability' => []];
                $roomTransactions = $transactions->get($room->id, collect());
                
                foreach ($dates as $dateString => $dateInfo) {
                    $date = $dateInfo['date'];
                    $isOccupied = false;
                    $reservations = collect();
                    
                    foreach ($roomTransactions as $transaction) {
                        if ($date->between(
                            $transaction->check_in->copy()->startOfDay(),
                            $transaction->check_out->copy()->subDay()->endOfDay()
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
                    
                    $cssClass = 'available';
                    if ($isOccupied) {
                        $cssClass = 'occupied';
                    } elseif ($room->room_status_id != 1) {
                        $cssClass = 'unavailable';
                    }
                    
                    $roomData['availability'][$dateString] = [
                        'occupied' => $isOccupied,
                        'date' => $dateString,
                        'reservations' => $reservations,
                        'reservation_count' => $reservations->count(), // AJOUT IMPORTANT
                        'css_class' => $cssClass,
                        'has_reservations' => $reservations->isNotEmpty()
                    ];
                }
                $calendar[] = $roomData;
            }
            
            // Navigation
            $prevMonth = $startDate->copy()->subMonth();
            $nextMonth = $startDate->copy()->addMonth();
            
            // Statistiques
            $today = now();
            $stats = $this->calculateCalendarStats($rooms, $transactions, $today);
            
            return view('availability.calendar', compact(
                'calendar', 'dates', 'roomTypes', 'rooms', 'startDate', 'endDate',
                'month', 'year', 'prevMonth', 'nextMonth', 'roomType', 'stats'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Calendar error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du calendrier');
        }
    }
    
    /**
     * Recherche de disponibilité
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
            
            // Recherche des chambres
            $query = Room::with(['type', 'facilities', 'roomStatus'])
                ->where('capacity', '>=', $adults);
            
            if ($roomTypeId) {
                $query->where('type_id', $roomTypeId);
            }
            
            $allRooms = $query->orderBy('type_id')->orderBy('number')->get();
            
            // Transactions conflictuelles
            $conflictingTransactions = Transaction::whereIn('room_id', $allRooms->pluck('id'))
                ->where(function($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in', '<', $checkOutDate)
                        ->where('check_out', '>', $checkInDate);
                    });
                })
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->with('customer')
                ->get()
                ->groupBy('room_id');
            
            // Séparer chambres disponibles/indisponibles
            $availableRooms = [];
            $unavailableRooms = [];
            $roomConflicts = [];
            
            foreach ($allRooms as $room) {
                $conflicts = $conflictingTransactions->get($room->id, collect());
                
                if ($conflicts->isEmpty() && $room->room_status_id == 1) {
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
            
            return view('availability.search', compact(
                'availableRooms', 'unavailableRooms', 'roomConflicts', 'roomTypes',
                'checkIn', 'checkOut', 'nights', 'adults', 'children', 'roomTypeId', 'totalGuests'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Search availability error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la recherche de disponibilité');
        }
    }
    
    /**
     * Afficher les conflits détaillés pour une chambre
     */
    public function showConflicts(Request $request, $roomId)
    {
        try {
            // Log pour debug
            \Log::info('Show conflicts called', [
                'room_id' => $roomId,
                'check_in' => $request->get('check_in'),
                'check_out' => $request->get('check_out')
            ]);
            
            // Récupérer la chambre
            $room = Room::with('type')->find($roomId);
            
            if (!$room) {
                return redirect()->route('availability.search')
                    ->with('error', 'Chambre non trouvée (ID: ' . $roomId . ')');
            }
            
            $checkIn = $request->get('check_in');
            $checkOut = $request->get('check_out');
            $adults = $request->get('adults', 1);
            $children = $request->get('children', 0);
            
            if (!$checkIn || !$checkOut) {
                return redirect()->route('availability.search')
                    ->with('error', 'Les dates de recherche sont requises');
            }
            
            $checkInDate = Carbon::parse($checkIn)->startOfDay();
            $checkOutDate = Carbon::parse($checkOut)->startOfDay();
            $nights = $checkInDate->diffInDays($checkOutDate);
            
            // Trouver les conflits
            $conflicts = Transaction::where('room_id', $room->id)
                ->whereIn('status', ['reservation', 'active'])
                ->where(function($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in', '<', $checkOutDate)
                        ->where('check_out', '>', $checkInDate);
                    });
                })
                ->with(['customer', 'room.type'])
                ->orderBy('check_in')
                ->get();
            
            // Analyser les chevauchements
            $conflictAnalysis = [];
            $totalOverlapDays = 0;
            
            foreach ($conflicts as $conflict) {
                $overlapStart = max($checkInDate, $conflict->check_in);
                $overlapEnd = min($checkOutDate, $conflict->check_out);
                $overlapDays = $overlapStart->diffInDays($overlapEnd);
                $totalOverlapDays += $overlapDays;
                
                $conflictAnalysis[] = [
                    'transaction' => $conflict,
                    'overlap_days' => $overlapDays,
                    'overlap_start' => $overlapStart->format('Y-m-d'),
                    'overlap_end' => $overlapEnd->format('Y-m-d'),
                    'overlap_period' => $overlapStart->format('d/m/Y') . ' - ' . $overlapEnd->format('d/m/Y'),
                    'remaining_days' => $conflict->check_out->diffInDays($checkInDate)
                ];
            }
            
            // Trouver des dates alternatives
            $suggestedDates = $this->findAlternativeDates($room, $checkInDate, $checkOutDate, $nights);
            
            return view('availability.conflicts', [
                'room' => $room,
                'conflicts' => $conflicts,
                'conflictAnalysis' => $conflictAnalysis,
                'totalOverlapDays' => $totalOverlapDays,
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
                'nights' => $nights,
                'adults' => $adults,
                'children' => $children,
                'suggestedDates' => $suggestedDates,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Show conflicts error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des conflits: ' . $e->getMessage());
        }
    }
    /**
     * Réserver sans conflit
     */
    public function reserveWithoutConflict(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'customer_id' => 'required|exists:customers,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1',
                'children' => 'integer|min:0',
            ]);
            
            $room = Room::findOrFail($request->room_id);
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            $nights = $checkIn->diffInDays($checkOut);
            
            // Vérifier qu'il n'y a pas de conflit
            $hasConflict = Transaction::where('room_id', $room->id)
                ->whereIn('status', ['reservation', 'active'])
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<', $checkOut)
                          ->where('check_out', '>', $checkIn);
                    });
                })
                ->exists();
            
            if ($hasConflict) {
                return redirect()->route('availability.room.conflicts', [
                    'room' => $room->id,
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d'),
                    'adults' => $request->adults,
                    'children' => $request->children
                ])->with('error', 'La chambre n\'est plus disponible pour ces dates');
            }
            
            // Créer la transaction
            $transaction = Transaction::create([
                'room_id' => $room->id,
                'customer_id' => $request->customer_id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'adults' => $request->adults,
                'children' => $request->children ?? 0,
                'status' => 'reservation',
                'reservation_number' => 'RES-' . strtoupper(uniqid()),
                'total_price' => $room->price * $nights,
                'paid_amount' => 0,
                'remaining_amount' => $room->price * $nights,
                'notes' => $request->notes ?? 'Réservation créée via disponibilité',
                'created_by' => auth()->id()
            ]);
            
            // Mettre à jour le statut de la chambre si nécessaire
            if ($room->room_status_id == 1) { // Disponible
                $room->update(['room_status_id' => 4]); // Réservée
            }
            
            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Réservation créée avec succès sans chevauchement');
                
        } catch (\Exception $e) {
            \Log::error('Reserve without conflict error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de la réservation');
        }
    }
    
    /**
     * Trouver des dates alternatives
     */
    private function findAlternativeDates($room, $originalCheckIn, $originalCheckOut, $originalNights)
    {
        $suggestions = [];
        $today = Carbon::today();
        
        // Chercher dans les 60 prochains jours
        for ($i = 0; $i < 60; $i++) {
            $startDate = $today->copy()->addDays($i);
            
            // Essayer différentes durées
            $durations = [$originalNights, $originalNights + 1, $originalNights - 1, $originalNights + 2];
            
            foreach ($durations as $nights) {
                if ($nights < 1) continue;
                
                $endDate = $startDate->copy()->addDays($nights);
                
                // Vérifier disponibilité
                $isAvailable = !Transaction::where('room_id', $room->id)
                    ->whereIn('status', ['reservation', 'active'])
                    ->where(function($query) use ($startDate, $endDate) {
                        $query->where(function($q) use ($startDate, $endDate) {
                            $q->where('check_in', '<', $endDate)
                              ->where('check_out', '>', $startDate);
                        });
                    })
                    ->exists();
                
                if ($isAvailable) {
                    $suggestions[] = [
                        'check_in' => $startDate->format('Y-m-d'),
                        'check_out' => $endDate->format('Y-m-d'),
                        'nights' => $nights,
                        'formatted_check_in' => $startDate->format('d/m/Y'),
                        'formatted_check_out' => $endDate->format('d/m/Y'),
                        'total_price' => $room->price * $nights
                    ];
                    
                    if (count($suggestions) >= 6) {
                        break 2;
                    }
                }
            }
        }
        
        return collect($suggestions);
    }
    
   public function inventory()
    {
        try {
            \Log::info('=== DÉBUT inventory() ===');
            
            // Test 1: Accès aux modèles de base
            \Log::info('Test 1: Vérification des modèles');
            $testRoom = \App\Models\Room::first();
            $testType = \App\Models\Type::first();
            \Log::info('Modèles OK - Room: ' . ($testRoom ? 'oui' : 'non') . ', Type: ' . ($testType ? 'oui' : 'non'));
            
            $today = now();
            \Log::info('Date aujourd\'hui: ' . $today);
            
            // Test 2: Récupération des types de chambres
            \Log::info('Test 2: Récupération roomTypes');
            $roomTypes = Type::with(['rooms' => function($query) {
                $query->with(['roomStatus']);
            }])->get();
            \Log::info('RoomTypes count: ' . $roomTypes->count());
            
            // Test 3: Chambres groupées par statut
            \Log::info('Test 3: Récupération roomsByStatus');
            $roomsByStatus = Room::with(['roomStatus', 'type'])
                ->orderBy('room_status_id')
                ->orderBy('number')
                ->get()
                ->groupBy('room_status_id');
            \Log::info('RoomsByStatus count: ' . $roomsByStatus->count());
            
            // Test 4: Statistiques
            \Log::info('Test 4: Calcul statistiques');
            $totalRooms = Room::count();
            $availableRooms = Room::where('room_status_id', 1)->count();
            
            $occupiedRooms = Transaction::where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->distinct('room_id')
                ->count('room_id');
            
            $stats = [
                'total_rooms' => $totalRooms,
                'available_rooms' => $availableRooms,
                'occupied_rooms' => $occupiedRooms,
                'maintenance_rooms' => Room::where('room_status_id', 2)->count(),
                'cleaning_rooms' => Room::where('room_status_id', 3)->count(),
                'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0
            ];
            
            \Log::info('Stats calculées: ' . json_encode($stats));
            
            // Test 5: Arrivées/départs
            \Log::info('Test 5: Arrivées et départs');
            $todayArrivals = Transaction::with(['room', 'customer'])
                ->where('status', 'reservation')
                ->whereDate('check_in', $today)
                ->orderBy('check_in')
                ->get();
            
            $todayDepartures = Transaction::with(['room', 'customer'])
                ->where('status', 'active')
                ->whereDate('check_out', $today)
                ->orderBy('check_out')
                ->get();
            
            \Log::info('TodayArrivals: ' . $todayArrivals->count() . ', TodayDepartures: ' . $todayDepartures->count());
            
            // Test 6: Occupation par type
            \Log::info('Test 6: Occupation par type');
            $occupancyByType = [];
            foreach ($roomTypes as $type) {
                $typeRooms = $type->rooms;
                $totalRoomsType = $typeRooms->count();
                
                // Compter les chambres occupées pour ce type
                $occupiedTypeRooms = 0;
                foreach ($typeRooms as $room) {
                    // Vérifier si la chambre est occupée aujourd'hui
                    $isOccupied = Transaction::where('room_id', $room->id)
                        ->where('check_in', '<=', $today)
                        ->where('check_out', '>=', $today)
                        ->whereIn('status', ['active', 'reservation'])
                        ->exists();
                    
                    if ($isOccupied) {
                        $occupiedTypeRooms++;
                    }
                }
                
                $occupancyByType[$type->name] = [
                    'occupied' => $occupiedTypeRooms,
                    'percentage' => $totalRoomsType > 0 ? 
                        round(($occupiedTypeRooms / $totalRoomsType) * 100, 1) : 0
                ];
            }
            
            \Log::info('OccupancyByType calculé');
            
            // Test 7: Transactions actives
            \Log::info('Test 7: Transactions actives');
            $activeTransactions = Transaction::where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->with(['room', 'customer'])
                ->get();
            
            \Log::info('ActiveTransactions: ' . $activeTransactions->count());
            
            // Préparation des données pour la vue
            \Log::info('Préparation des données pour la vue');
            $data = compact(
                'roomTypes',
                'roomsByStatus',
                'stats',
                'todayArrivals',
                'todayDepartures',
                'occupancyByType',
                'activeTransactions'
            );
            
            \Log::info('=== FIN inventory() - Tout semble OK ===');
            
            return view('availability.inventory', $data);
            
        } catch (\Exception $e) {
            \Log::error('Inventory error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            // Afficher l'erreur pour le débogage
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
    /**
     * Dashboard
     */
    public function dashboard()
    {
        try {
            $today = now();
            
            // Statistiques
            $statsQuery = DB::table('rooms')
                ->selectRaw('
                    COUNT(*) as total_rooms,
                    SUM(CASE WHEN room_status_id = 1 THEN 1 ELSE 0 END) as available_rooms,
                    SUM(CASE WHEN room_status_id = 2 THEN 1 ELSE 0 END) as maintenance_rooms,
                    SUM(CASE WHEN room_status_id = 3 THEN 1 ELSE 0 END) as cleaning_rooms
                ')
                ->first();
            
            $occupiedRooms = Transaction::where('check_in', '<=', $today)
                ->where('check_out', '>=', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->distinct('room_id')
                ->count('room_id');
            
            $stats = [
                'total_rooms' => $statsQuery->total_rooms ?? 0,
                'available_rooms' => $statsQuery->available_rooms ?? 0,
                'maintenance_rooms' => $statsQuery->maintenance_rooms ?? 0,
                'cleaning_rooms' => $statsQuery->cleaning_rooms ?? 0,
                'occupied_rooms' => $occupiedRooms,
                'occupancy_rate' => ($statsQuery->total_rooms ?? 0) > 0 ? 
                    round(($occupiedRooms / ($statsQuery->total_rooms ?? 1)) * 100, 1) : 0
            ];
            
            // Arrivées/départs prochains
            $upcomingArrivals = Transaction::with(['room.type', 'customer'])
                ->where('status', 'reservation')
                ->whereBetween('check_in', [$today, $today->copy()->addDays(3)])
                ->orderBy('check_in')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_in->format('Y-m-d');
                });
            
            $upcomingDepartures = Transaction::with(['room.type', 'customer'])
                ->where('status', 'active')
                ->whereBetween('check_out', [$today, $today->copy()->addDays(3)])
                ->orderBy('check_out')
                ->get()
                ->groupBy(function($transaction) {
                    return $transaction->check_out->format('Y-m-d');
                });
            
            // Chambres disponibles
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
            
            // Chambres indisponibles
            $unavailableRooms = Room::whereIn('room_status_id', [2, 3, 4])
                ->with(['type', 'roomStatus'])
                ->orderBy('room_status_id')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            // Occupation par type
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
                'stats', 'upcomingArrivals', 'upcomingDepartures',
                'availableNow', 'unavailableRooms', 'occupancyByType', 'today'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du dashboard');
        }
    }
    
    /**
     * Détail d'une chambre
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
            
            // Calendrier 30 jours
            $calendar = [];
            for ($i = 0; $i < 30; $i++) {
                $date = $today->copy()->addDays($i);
                $dateString = $date->format('Y-m-d');
                
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
            
            // Statistiques
            $last30DaysTransactions = Transaction::where('room_id', $room->id)
                ->where('check_in', '>=', $today->copy()->subDays(30))
                ->whereIn('status', ['active', 'checked_out', 'reservation'])
                ->get();
            
            $roomStats = $this->calculateRoomStats($room, $last30DaysTransactions);
            
            // Historique
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
                'room', 'calendar', 'roomStats', 'currentTransaction',
                'recentTransactions', 'nextReservation'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Room detail error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des détails');
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
            
            $start = max($transaction->check_in, now()->subDays(30));
            $end = min($transaction->check_out, now());
            
            if ($start <= $end) {
                $occupancyDays += $start->diffInDays($end);
            }
        }
        
        $avgStayDuration = $transactions->count() > 0 ? 
            round($totalNights / $transactions->count(), 1) : 0;
        
        $occupancyRate30d = min(100, round(($occupancyDays / 30) * 100, 1));
        
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
            'total_revenue_30d' => $totalRevenue,
            'avg_stay_duration' => $avgStayDuration,
            'avg_daily_rate' => $totalNights > 0 ? 
                round($totalRevenue / $totalNights, 0) : $room->price,
            'occupancy_rate_30d' => $occupancyRate30d,
            'next_available' => $nextAvailableDate,
            'formatted_next_available' => $nextAvailableDate->format('d/m/Y')
        ];
    }
    
    /**
     * Calculer les statistiques du calendrier
     */
    private function calculateCalendarStats($rooms, $transactions, $today)
    {
        $availableToday = 0;
        $occupiedToday = 0;
        $unavailableToday = 0;
        
        foreach ($rooms as $room) {
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
     * Vérifier disponibilité (API)
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'guests' => 'nullable|integer|min:1',
                'exclude_transaction_id' => 'nullable|exists:transactions,id'
            ]);
            
            $room = Room::with(['type', 'roomStatus'])->findOrFail($request->room_id);
            
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            
            if ($checkOut <= $checkIn) {
                return response()->json([
                    'available' => false,
                    'error' => 'La date de départ doit être après la date d\'arrivée'
                ], 400);
            }
            
            $nights = $checkIn->diffInDays($checkOut);
            
            // 1. Vérifier les chevauchements de réservations
            $hasConflict = Transaction::where('room_id', $room->id)
                ->when($request->exclude_transaction_id, function($query, $excludeId) {
                    $query->where('id', '!=', $excludeId);
                })
                ->whereIn('status', ['active', 'reservation'])
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                    });
                })
                ->exists();
            
            // 2. Vérifier le statut de la chambre (1 = disponible)
            $roomAvailable = $room->room_status_id == 1;
            
            // 3. Vérifier la capacité
            $guests = $request->get('guests', 1);
            $hasCapacity = $guests <= $room->capacity;
            
            $isAvailable = !$hasConflict && $roomAvailable && $hasCapacity;
            
            $totalPrice = $room->price * $nights;
            
            $response = [
                'available' => $isAvailable,
                'room' => [
                    'id' => $room->id,
                    'number' => $room->number,
                    'type' => $room->type->name ?? 'N/A',
                    'price' => $room->price,
                    'capacity' => $room->capacity,
                    'room_status' => $room->roomStatus->name ?? 'N/A',
                    'room_status_id' => $room->room_status_id
                ],
                'dates' => [
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d'),
                    'nights' => $nights
                ],
                'total_price' => $totalPrice,
                'checks' => [
                    'no_conflict' => !$hasConflict,
                    'room_available' => $roomAvailable,
                    'has_capacity' => $hasCapacity
                ]
            ];
            
            if (!$isAvailable) {
                $response['reasons'] = [];
                
                if ($hasConflict) {
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
                    
                    $response['conflicts'] = $conflicts;
                    $response['reasons'][] = 'Chambre déjà réservée pour cette période';
                }
                
                if (!$roomAvailable) {
                    $response['reasons'][] = 'Chambre ' . ($room->roomStatus->name ?? 'indisponible');
                }
                
                if (!$hasCapacity) {
                    $response['reasons'][] = 'Capacité insuffisante (' . $guests . ' > ' . $room->capacity . ')';
                }
                
                // Trouver la prochaine disponibilité
                $nextAvailable = $this->findNextAvailableDate($room, $checkIn);
                if ($nextAvailable) {
                    $response['next_available'] = $nextAvailable;
                }
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Check availability error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // AJOUTEZ CETTE MÉTHODE PRIVÉE :
    private function findNextAvailableDate($room, $fromDate)
    {
        $nextBooking = Transaction::where('room_id', $room->id)
            ->where('check_out', '>', $fromDate)
            ->whereIn('status', ['active', 'reservation'])
            ->orderBy('check_out')
            ->first();
        
        if ($nextBooking) {
            $nextAvailableDate = $nextBooking->check_out->copy()->addDay();
            return [
                'date' => $nextAvailableDate->format('Y-m-d'),
                'formatted' => $nextAvailableDate->format('d/m/Y'),
                'days_from_now' => now()->diffInDays($nextAvailableDate)
            ];
        }
        
        return null;
    }
    
    /**
     * Détails cellule calendrier (API)
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
            
            $transactions = Transaction::where('room_id', $room->id)
                ->where('check_in', '<=', $date)
                ->where('check_out', '>=', $date)
                ->whereIn('status', ['active', 'reservation'])
                ->with('customer')
                ->get();
            
            $isOccupied = $transactions->isNotEmpty();
            
            $response = [
                'room' => $room,
                'date' => $date->format('Y-m-d'),
                'is_occupied' => $isOccupied,
                'status' => $isOccupied ? 'Occupée' : ($room->room_status_id == 1 ? 'Disponible' : 'Indisponible')
            ];
            
            if ($isOccupied) {
                $response['reservations'] = $transactions;
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Calendar cell details error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Export des données de disponibilité
     */
    public function export(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:excel,pdf,csv',
                'export_type' => 'required|in:availability,calendar,inventory',
                'period' => 'required_if:export_type,availability|in:today,week,month,custom',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2100',
            ]);
            
            $type = $request->type;
            $exportType = $request->export_type;
            $period = $request->period;
            
            switch ($exportType) {
                case 'calendar':
                    // Export du calendrier
                    $month = $request->month ?? now()->month;
                    $year = $request->year ?? now()->year;
                    
                    // Récupérer les données du calendrier
                    $startDate = Carbon::create($year, $month, 1)->startOfDay();
                    $endDate = $startDate->copy()->endOfMonth()->endOfDay();
                    $daysInMonth = $startDate->daysInMonth;
                    
                    // Générer les dates
                    $dates = [];
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = Carbon::create($year, $month, $day);
                        $dateString = $date->format('Y-m-d');
                        
                        $dates[$dateString] = [
                            'date' => $date,
                            'day_number' => $day,
                            'day_name' => $date->locale('fr')->isoFormat('ddd'),
                            'is_today' => $date->isToday(),
                            'is_weekend' => $date->isWeekend(),
                        ];
                    }
                    
                    // Récupérer les chambres
                    $rooms = Room::with(['type', 'roomStatus', 'transactions' => function($query) use ($startDate, $endDate) {
                        $query->where(function($q) use ($startDate, $endDate) {
                            $q->where('check_in', '<=', $endDate)
                            ->where('check_out', '>=', $startDate);
                        })
                        ->whereIn('status', ['reservation', 'active']);
                    }])->orderBy('number')->get();
                    
                    // Préparer les données du calendrier
                    $calendar = [];
                    foreach ($rooms as $room) {
                        $roomData = ['room' => $room, 'availability' => []];
                        
                        foreach ($dates as $dateString => $dateInfo) {
                            $date = $dateInfo['date'];
                            $isOccupied = false;
                            
                            foreach ($room->transactions as $transaction) {
                                if ($date->between(
                                    $transaction->check_in->copy()->startOfDay(),
                                    $transaction->check_out->copy()->subDay()->endOfDay()
                                )) {
                                    $isOccupied = true;
                                    break;
                                }
                            }
                            
                            $status = 'D'; // Disponible par défaut
                            if ($isOccupied) {
                                $status = 'O';
                            } elseif ($room->room_status_id == 2) { // Maintenance
                                $status = 'M';
                            } elseif ($room->room_status_id == 3) { // Nettoyage
                                $status = 'N';
                            } elseif ($room->room_status_id != 1) { // Autre indisponibilité
                                $status = 'I';
                            }
                            
                            $roomData['availability'][$dateString] = [
                                'occupied' => $isOccupied,
                                'css_class' => $isOccupied ? 'occupied' : ($room->room_status_id == 1 ? 'available' : 'unavailable'),
                                'status' => $status
                            ];
                        }
                        
                        $calendar[] = $roomData;
                    }
                    
                    $export = new CalendarExport($calendar, $dates, $month, $year);
                    $filename = 'calendrier-disponibilite-' . $month . '-' . $year . '-' . now()->format('Y-m-d-H-i') . '.' . $type;
                    break;
                    
                case 'inventory':
                    // Export de l'inventaire
                    $today = now();
                    
                    // Récupérer les données d'inventaire
                    $roomTypes = Type::with(['rooms' => function($query) {
                        $query->with(['roomStatus', 'currentTransaction.customer']);
                    }])->get();
                    
                    $activeTransactions = Transaction::where('check_in', '<=', $today)
                        ->where('check_out', '>=', $today)
                        ->whereIn('status', ['active', 'reservation'])
                        ->get()
                        ->groupBy('room_id');
                    
                    $exportData = [];
                    foreach ($roomTypes as $type) {
                        foreach ($type->rooms as $room) {
                            $isOccupied = $activeTransactions->has($room->id);
                            $currentTransaction = $isOccupied ? $activeTransactions->get($room->id)->first() : null;
                            
                            $exportData[] = [
                                'Chambre' => $room->number,
                                'Type' => $type->name,
                                'Capacité' => $room->capacity,
                                'Prix/nuit' => $room->price,
                                'Statut' => $room->roomStatus->name ?? 'N/A',
                                'Occupation' => $isOccupied ? 'Occupée' : 'Libre',
                                'Client' => $currentTransaction ? $currentTransaction->customer->name : 'N/A',
                                'Arrivée' => $currentTransaction ? $currentTransaction->check_in->format('d/m/Y') : 'N/A',
                                'Départ' => $currentTransaction ? $currentTransaction->check_out->format('d/m/Y') : 'N/A',
                                'Durée' => $currentTransaction ? $currentTransaction->check_in->diffInDays($currentTransaction->check_out) . ' nuits' : 'N/A'
                            ];
                        }
                    }
                    
                    $export = new InventoryExport($exportData);
                    $filename = 'inventaire-chambres-' . now()->format('Y-m-d-H-i') . '.' . $type;
                    break;
                    
                default: // availability
                    // Export des disponibilités
                    $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfDay();
                    $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfDay();
                    
                    // Ajuster les dates selon la période
                    switch ($period) {
                        case 'today':
                            $startDate = now()->startOfDay();
                            $endDate = now()->endOfDay();
                            break;
                        case 'week':
                            $startDate = now()->startOfWeek();
                            $endDate = now()->endOfWeek();
                            break;
                        case 'month':
                            $startDate = now()->startOfMonth();
                            $endDate = now()->endOfMonth();
                            break;
                    }
                    
                    // Récupérer les données
                    $rooms = Room::with(['type', 'roomStatus', 'transactions' => function($query) use ($startDate, $endDate) {
                        $query->where(function($q) use ($startDate, $endDate) {
                            $q->whereBetween('check_in', [$startDate, $endDate])
                            ->orWhereBetween('check_out', [$startDate, $endDate])
                            ->orWhere(function($q2) use ($startDate, $endDate) {
                                $q2->where('check_in', '<', $startDate)
                                    ->where('check_out', '>', $endDate);
                            });
                        })
                        ->whereIn('status', ['active', 'checked_out', 'reservation']);
                    }])->get();
                    
                    // Préparer les données pour l'export
                    $exportData = [];
                    foreach ($rooms as $room) {
                        $occupancyDays = 0;
                        $totalRevenue = 0;
                        
                        foreach ($room->transactions as $transaction) {
                            // Calculer les jours d'occupation dans la période
                            $overlapStart = max($transaction->check_in, $startDate);
                            $overlapEnd = min($transaction->check_out, $endDate);
                            
                            if ($overlapStart < $overlapEnd) {
                                $days = $overlapStart->diffInDays($overlapEnd);
                                $occupancyDays += $days;
                                $totalRevenue += $transaction->total_price;
                            }
                        }
                        
                        $totalDays = $startDate->diffInDays($endDate);
                        $occupancyRate = $totalDays > 0 ? round(($occupancyDays / $totalDays) * 100, 1) : 0;
                        
                        $exportData[] = [
                            'Chambre' => $room->number,
                            'Type' => $room->type->name ?? 'N/A',
                            'Statut' => $room->roomStatus->name ?? 'N/A',
                            'Prix/nuit' => $room->price,
                            'Jours occupés' => $occupancyDays,
                            'Revenu total' => $totalRevenue,
                            'Taux occupation' => $occupancyRate . '%',
                            'Disponibilité' => $room->room_status_id == 1 ? 'Disponible' : 'Indisponible'
                        ];
                    }
                    
                    $export = new AvailabilityExport($exportData, $period, $startDate, $endDate);
                    $filename = 'disponibilite-chambres-' . $period . '-' . now()->format('Y-m-d-H-i') . '.' . $type;
                    break;
            }
            
            // Selon le type d'export
            if ($type === 'excel') {
                return Excel::download($export, $filename);
            } elseif ($type === 'csv') {
                return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::CSV);
            } else {
                // PDF export - vous devrez créer une vue pour le PDF
                $view = 'exports.' . $exportType;
                $data = [
                    'data' => $exportData ?? [],
                    'period' => $period ?? null,
                    'startDate' => isset($startDate) ? $startDate->format('d/m/Y') : null,
                    'endDate' => isset($endDate) ? $endDate->format('d/m/Y') : null,
                    'generatedAt' => now()->format('d/m/Y H:i'),
                    'month' => $month ?? null,
                    'year' => $year ?? null
                ];
                
                $pdf = PDF::loadView($view, $data);
                return $pdf->download($filename);
            }
            
        } catch (\Exception $e) {
            \Log::error('Export availability error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les périodes disponibles pour une chambre
     */
    public function getAvailablePeriods(Request $request, $roomId)
    {
        try {
            $room = Room::findOrFail($roomId);
            $startDate = $request->get('start_date', now()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->addMonths(3)->format('Y-m-d'));
            $minNights = $request->get('min_nights', 1);
            $maxNights = $request->get('max_nights', 30);
            
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            // Réservations existantes
            $bookings = Transaction::where('room_id', $room->id)
                ->whereIn('status', ['active', 'reservation'])
                ->where('check_out', '>', $start)
                ->where('check_in', '<', $end)
                ->orderBy('check_in')
                ->get(['check_in', 'check_out']);
            
            // Trouver les périodes disponibles
            $availablePeriods = [];
            $currentDate = $start->copy();
            
            while ($currentDate < $end) {
                // Trouver la prochaine réservation
                $nextBooking = null;
                foreach ($bookings as $booking) {
                    if ($booking->check_in > $currentDate) {
                        $nextBooking = $booking;
                        break;
                    }
                }
                
                if ($nextBooking) {
                    // Période disponible jusqu'à la prochaine réservation
                    $availableStart = $currentDate;
                    $availableEnd = $nextBooking->check_in->copy()->subDay()->endOfDay();
                    $availableDays = $availableStart->diffInDays($availableEnd);
                    
                    if ($availableDays >= $minNights) {
                        // Diviser en périodes de max_nuits
                        $periodStart = $availableStart;
                        while ($periodStart <= $availableEnd) {
                            $periodEnd = min(
                                $periodStart->copy()->addDays($maxNights - 1),
                                $availableEnd
                            );
                            
                            $periodDays = $periodStart->diffInDays($periodEnd) + 1;
                            
                            if ($periodDays >= $minNights) {
                                $availablePeriods[] = [
                                    'start' => $periodStart->format('Y-m-d'),
                                    'end' => $periodEnd->format('Y-m-d'),
                                    'nights' => $periodDays,
                                    'total_price' => $room->price * $periodDays,
                                    'formatted' => $periodStart->format('d/m/Y') . ' - ' . $periodEnd->format('d/m/Y')
                                ];
                            }
                            
                            $periodStart = $periodEnd->copy()->addDay();
                        }
                    }
                    
                    $currentDate = $nextBooking->check_out->copy();
                } else {
                    // Pas d'autres réservations, période disponible jusqu'à end
                    $availableDays = $currentDate->diffInDays($end);
                    
                    if ($availableDays >= $minNights) {
                        $periodEnd = min(
                            $currentDate->copy()->addDays($maxNights - 1),
                            $end->copy()->subDay()
                        );
                        
                        $periodDays = $currentDate->diffInDays($periodEnd) + 1;
                        
                        if ($periodDays >= $minNights) {
                            $availablePeriods[] = [
                                'start' => $currentDate->format('Y-m-d'),
                                'end' => $periodEnd->format('Y-m-d'),
                                'nights' => $periodDays,
                                'total_price' => $room->price * $periodDays,
                                'formatted' => $currentDate->format('d/m/Y') . ' - ' . $periodEnd->format('d/m/Y')
                            ];
                        }
                    }
                    
                    break;
                }
            }
            
            return response()->json([
                'room' => $room,
                'available_periods' => $availablePeriods,
                'period' => [
                    'start' => $start->format('Y-m-d'),
                    'end' => $end->format('Y-m-d')
                ],
                'constraints' => [
                    'min_nights' => $minNights,
                    'max_nights' => $maxNights
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get available periods error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}