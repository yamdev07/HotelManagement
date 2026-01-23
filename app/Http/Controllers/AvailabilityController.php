<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Type;
use App\Models\Transaction;
use App\Models\RoomStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityController extends Controller
{
    /**
     * Vue principale - Calendrier des disponibilités
     */
    public function calendar(Request $request)
    {
        try {
            // Dates par défaut
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $roomType = $request->get('room_type');
            
            // Validation des dates
            if (!is_numeric($month) || $month < 1 || $month > 12) {
                $month = date('m');
            }
            if (!is_numeric($year) || $year < 2020 || $year > 2100) {
                $year = date('Y');
            }
            
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
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
            
            // Générer le calendrier
            $calendar = [];
            $dates = [];
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $dates[$day] = [
                    'date' => $date,
                    'formatted' => $date->format('Y-m-d'),
                    'day_name' => $date->format('D'),
                    'is_today' => $date->isToday(),
                    'is_weekend' => $date->isWeekend(),
                    'day_number' => $day
                ];
            }
            
            // Récupérer toutes les transactions pour le mois
            $transactions = Transaction::where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('check_in', '<', $startDate)
                            ->where('check_out', '>', $endDate);
                    });
            })
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->with('customer')
            ->get();
            
            foreach ($rooms as $room) {
                $roomData = [
                    'room' => $room,
                    'availability' => []
                ];
                
                foreach ($dates as $day => $dateInfo) {
                    $date = $dateInfo['date'];
                    
                    // Vérifier si la chambre est occupée à cette date
                    $isOccupied = false;
                    $reservations = collect();
                    
                    // Filtrer les transactions pour cette chambre et cette date
                    $roomTransactions = $transactions->filter(function($transaction) use ($room, $date) {
                        return $transaction->room_id == $room->id &&
                            $date->between(
                                Carbon::parse($transaction->check_in),
                                Carbon::parse($transaction->check_out)->subDay() // -1 jour car départ à midi
                            );
                    });
                    
                    if ($roomTransactions->isNotEmpty()) {
                        $isOccupied = true;
                        $reservations = $roomTransactions->map(function($transaction) {
                            return [
                                'customer' => $transaction->customer->name,
                                'check_in' => $transaction->check_in->format('d/m/Y'),
                                'check_out' => $transaction->check_out->format('d/m/Y'),
                                'status' => $transaction->status,
                                'transaction_id' => $transaction->id
                            ];
                        });
                    }
                    
                    // Déterminer la classe CSS
                    $cssClass = 'available';
                    if ($isOccupied) {
                        $cssClass = 'occupied';
                    } elseif ($room->room_status_id != 1) { // STATUT_AVAILABLE = 1
                        $cssClass = 'unavailable';
                    }
                    
                    $roomData['availability'][$day] = [
                        'occupied' => $isOccupied,
                        'date' => $dateInfo['formatted'],
                        'date_obj' => $date,
                        'reservations' => $reservations,
                        'css_class' => $cssClass,
                        'has_reservations' => $reservations->isNotEmpty()
                    ];
                }
                
                $calendar[] = $roomData;
            }
            
            // Navigation
            $prevMonth = $startDate->copy()->subMonth();
            $nextMonth = $startDate->copy()->addMonth();
            
            // Statistiques - CORRECTION ICI
            $stats = [
                'total_rooms' => $rooms->count(),
                'available_today' => $rooms->where('room_status_id', 1)->count(),
                'occupied_today' => $transactions->where('check_in', '<=', today())
                                                ->where('check_out', '>=', today())
                                                ->count(),
                'unavailable_today' => $rooms->where('room_status_id', '!=', 1)->count(),
                'total_reservations' => $transactions->count(),
                'arrivals_this_month' => $transactions->where('check_in', '>=', $startDate)
                                                    ->where('check_in', '<=', $endDate)
                                                    ->count(),
                'departures_this_month' => $transactions->where('check_out', '>=', $startDate)
                                                    ->where('check_out', '<=', $endDate)
                                                    ->count(),
            ];
            
            return view('availability.calendar', compact(
                'calendar',
                'dates',
                'roomTypes',
                'rooms',
                'startDate',
                'endDate',
                'month',
                'year',
                'prevMonth',
                'nextMonth',
                'roomType',
                'stats',
                'transactions'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Calendar error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors du chargement du calendrier: ' . $e->getMessage());
        }
    }
    
    /**
     * Recherche de disponibilité
     */
    public function search(Request $request)
    {
        // Valeurs par défaut
        $checkIn = $request->get('check_in', now()->format('Y-m-d'));
        $checkOut = $request->get('check_out', now()->addDays(1)->format('Y-m-d'));
        $roomTypeId = $request->get('room_type_id');
        $adults = $request->get('adults', 1);
        $children = $request->get('children', 0);
        
        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate);
        
        // Validation
        if ($checkInDate->greaterThanOrEqualTo($checkOutDate)) {
            return back()->with('error', 'La date de départ doit être après la date d\'arrivée');
        }
        
        // Recherche des chambres disponibles
        $query = Room::with(['type', 'facilities'])
            ->where('room_status_id', 1) // STATUS_AVAILABLE
            ->where('capacity', '>=', ($adults + $children));
        
        if ($roomTypeId) {
            $query->where('type_id', $roomTypeId);
        }
        
        $allRooms = $query->get();
        $availableRooms = [];
        
        foreach ($allRooms as $room) {
            if ($room->isAvailableForPeriod($checkIn, $checkOut)) {
                $availableRooms[] = [
                    'room' => $room,
                    'total_price' => $room->price * $nights,
                    'available' => true,
                    'price_per_night' => $room->price
                ];
            }
        }
        
        // Chambres non disponibles (pour information)
        $unavailableRooms = $allRooms->filter(function($room) use ($checkIn, $checkOut) {
            return !$room->isAvailableForPeriod($checkIn, $checkOut);
        });
        
        // Obtenir les conflits pour les chambres non disponibles
        $roomConflicts = [];
        foreach ($unavailableRooms as $room) {
            $conflicts = Transaction::where('room_id', $room->id)
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->where(function($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('check_in', [$checkIn, $checkOut])
                          ->orWhereBetween('check_out', [$checkIn, $checkOut])
                          ->orWhere(function($q2) use ($checkIn, $checkOut) {
                              $q2->where('check_in', '<', $checkIn)
                                 ->where('check_out', '>', $checkOut);
                          });
                    });
                })
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->with('customer')
                ->get();
                
            if ($conflicts->isNotEmpty()) {
                $roomConflicts[$room->id] = $conflicts;
            }
        }
        
        $roomTypes = Type::all();
        
        return view('availability.search', compact(
            'availableRooms',
            'unavailableRooms',
            'roomConflicts',
            'roomTypes',
            'checkIn',
            'checkOut',
            'nights',
            'adults',
            'children',
            'roomTypeId'
        ));
    }
    
    /**
     * Inventaire des chambres
     */
    public function inventory()
    {
        $roomTypes = Type::with(['rooms' => function($query) {
            $query->with(['roomStatus', 'type']);
        }])->get();
        
        // Statistiques globales
        $totalRooms = Room::count();
        $availableRooms = Room::where('room_status_id', 1)->count();
        $occupiedRooms = Transaction::where('check_in', '<=', today())
                                    ->where('check_out', '>=', today())
                                    ->whereNotIn('status', ['cancelled', 'no_show'])
                                    ->count();
        
        $stats = [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0
        ];
        
        // Arrivées du jour
        $todayArrivals = Transaction::with(['room', 'customer'])
            ->where('status', 'reservation')
            ->whereDate('check_in', today())
            ->orderBy('check_in')
            ->get();
        
        // Départs du jour
        $todayDepartures = Transaction::with(['room', 'customer'])
            ->where('status', 'active')
            ->whereDate('check_out', today())
            ->orderBy('check_out')
            ->get();
        
        // Chambres par statut
        $roomsByStatus = Room::with('roomStatus')
            ->get()
            ->groupBy('room_status_id');
        
        // Occupation par type
        $occupancyByType = [];
        foreach ($roomTypes as $type) {
            $totalRooms = $type->rooms->count();
            $occupiedRooms = Transaction::whereIn('room_id', $type->rooms->pluck('id'))
                ->where('check_in', '<=', today())
                ->where('check_out', '>=', today())
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->count();
            
            $occupancyByType[$type->name] = [
                'total' => $totalRooms,
                'occupied' => $occupiedRooms,
                'percentage' => $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0
            ];
        }
        
        return view('availability.inventory', compact(
            'roomTypes',
            'stats',
            'todayArrivals',
            'todayDepartures',
            'roomsByStatus',
            'occupancyByType'
        ));
    }
    
    /**
     * Détail d'une chambre
     */
    public function roomDetail(Room $room)
    {
        // Charger les relations
        $room->load([
            'type', 
            'roomStatus', 
            'facilities'
        ]);
        
        // Transactions en cours
        $currentTransaction = Transaction::where('room_id', $room->id)
            ->where('check_in', '<=', today())
            ->where('check_out', '>=', today())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->with('customer')
            ->first();
        
        // Calendrier des 30 prochains jours
        $calendar = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->addDays($i);
            $isOccupied = Transaction::where('room_id', $room->id)
                ->where('check_in', '<=', $date)
                ->where('check_out', '>=', $date)
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->exists();
            
            $calendar[$date->format('Y-m-d')] = [
                'date' => $date,
                'formatted' => $date->format('d/m'),
                'day_name' => $date->format('D'),
                'occupied' => $isOccupied,
                'css_class' => $isOccupied ? 'occupied' : ($room->room_status_id == 1 ? 'available' : 'unavailable')
            ];
        }
        
        // Statistiques de la chambre
        $last30DaysTransactions = Transaction::where('room_id', $room->id)
            ->where('check_in', '>=', now()->subDays(30))
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get();
        
        $roomStats = [
            'occupancy_rate_30d' => $last30DaysTransactions->count() > 0 ? 
                ($last30DaysTransactions->filter(function($t) {
                    return $t->check_in <= now() && $t->check_out >= now();
                })->count() / 30) * 100 : 0,
            'avg_stay_duration' => $last30DaysTransactions->avg(function($transaction) {
                return Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out);
            }) ?? 0,
            'total_revenue_30d' => $last30DaysTransactions->sum('total_price'),
            'next_available' => $room->getNextAvailableDate()
        ];
        
        return view('availability.room-detail', compact(
            'room',
            'calendar',
            'roomStats',
            'currentTransaction'
        ));
    }
    
    /**
     * Dashboard de disponibilité
     */
    public function dashboard()
    {
        // Statistiques globales
        $totalRooms = Room::count();
        $availableRooms = Room::where('room_status_id', 1)->count();
        $occupiedRooms = Transaction::where('check_in', '<=', today())
                                    ->where('check_out', '>=', today())
                                    ->whereNotIn('status', ['cancelled', 'no_show'])
                                    ->count();
        
        $stats = [
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0
        ];
        
        // Chambres par statut
        $roomsByStatus = Room::with('roomStatus')
            ->get()
            ->groupBy('room_status_id');
        
        // Occupation par type
        $roomTypes = Type::with(['rooms'])->get();
        $occupancyByType = [];
        
        foreach ($roomTypes as $type) {
            $totalRooms = $type->rooms->count();
            $occupiedRooms = Transaction::whereIn('room_id', $type->rooms->pluck('id'))
                ->where('check_in', '<=', today())
                ->where('check_out', '>=', today())
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->count();
            
            $occupancyByType[] = [
                'type' => $type->name,
                'total' => $totalRooms,
                'occupied' => $occupiedRooms,
                'percentage' => $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0
            ];
        }
        
        // Arrivées et départs des 7 prochains jours
        $upcomingArrivals = Transaction::with(['room', 'customer'])
            ->where('status', 'reservation')
            ->whereBetween('check_in', [now(), now()->addDays(7)])
            ->orderBy('check_in')
            ->get();
        
        $upcomingDepartures = Transaction::with(['room', 'customer'])
            ->where('status', 'active')
            ->whereBetween('check_out', [now(), now()->addDays(7)])
            ->orderBy('check_out')
            ->get();
        
        // Chambres disponibles maintenant - CORRECTION ICI
        $availableNow = Room::where('room_status_id', 1)
            ->whereDoesntHave('transactions', function($query) {
                $query->where('check_in', '<=', today())
                      ->where('check_out', '>=', today())
                      ->whereNotIn('status', ['cancelled', 'no_show']);
            })
            ->with('type')
            ->orderBy('number')
            ->limit(10)
            ->get();
        
        // Chambres en maintenance/nettoyage
        $unavailableRooms = Room::whereIn('room_status_id', [2, 3]) // MAINTENANCE et CLEANING
            ->with(['type', 'roomStatus'])
            ->orderBy('room_status_id')
            ->get();
        
        return view('availability.dashboard', compact(
            'stats',
            'roomsByStatus',
            'occupancyByType',
            'upcomingArrivals',
            'upcomingDepartures',
            'availableNow',
            'unavailableRooms'
        ));
    }
    
    /**
     * API: Vérifier disponibilité (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'exclude_transaction_id' => 'nullable|exists:transactions,id'
        ]);
        
        $room = Room::findOrFail($request->room_id);
        
        // Vérifier la disponibilité manuellement
        $isAvailable = !Transaction::where('room_id', $room->id)
            ->where('id', '!=', $request->exclude_transaction_id)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                      ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                      ->orWhere(function($q2) use ($request) {
                          $q2->where('check_in', '<', $request->check_in)
                             ->where('check_out', '>', $request->check_out);
                      });
                });
            })
            ->exists();
        
        $response = [
            'available' => $isAvailable,
            'room' => [
                'id' => $room->id,
                'number' => $room->number,
                'type' => $room->type->name ?? 'N/A',
                'price' => $room->price,
                'capacity' => $room->capacity
            ],
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nights' => Carbon::parse($request->check_in)->diffInDays($request->check_out),
            'total_price' => $room->price * Carbon::parse($request->check_in)->diffInDays($request->check_out)
        ];
        
        if (!$isAvailable) {
            // Obtenir les conflits
            $conflicts = Transaction::where('room_id', $room->id)
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->whereBetween('check_in', [$request->check_in, $request->check_out])
                          ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                          ->orWhere(function($q2) use ($request) {
                              $q2->where('check_in', '<', $request->check_in)
                                 ->where('check_out', '>', $request->check_out);
                          });
                    });
                })
                ->with('customer')
                ->get();
            
            $response['conflicts'] = $conflicts->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'customer' => $transaction->customer->name,
                    'check_in' => $transaction->check_in->format('Y-m-d'),
                    'check_out' => $transaction->check_out->format('Y-m-d'),
                    'status' => $transaction->status
                ];
            });
            
            // Proposer la prochaine date disponible
            $nextAvailable = Transaction::where('room_id', $room->id)
                ->where('check_out', '>', $request->check_out)
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->orderBy('check_out')
                ->first();
            
            if ($nextAvailable) {
                $nextDate = Carbon::parse($nextAvailable->check_out)->addDay();
                $response['next_available'] = $nextDate->format('Y-m-d');
                $response['suggestion'] = "Disponible à partir du " . $nextDate->format('d/m/Y');
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Export des disponibilités (PDF/Excel)
     */
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'in:pdf,excel'
        ]);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $rooms = Room::with(['type', 'roomStatus'])
            ->orderBy('type_id')
            ->orderBy('number')
            ->get();
        
        $data = [];
        $date = $startDate->copy();
        
        while ($date->lte($endDate)) {
            foreach ($rooms as $room) {
                $isOccupied = Transaction::where('room_id', $room->id)
                    ->where('check_in', '<=', $date)
                    ->where('check_out', '>=', $date)
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->exists();
                
                $currentTransaction = $isOccupied ? 
                    Transaction::where('room_id', $room->id)
                        ->where('check_in', '<=', $date)
                        ->where('check_out', '>=', $date)
                        ->whereNotIn('status', ['cancelled', 'no_show'])
                        ->with('customer')
                        ->first() : null;
                
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'room' => $room->number,
                    'type' => $room->type->name ?? 'N/A',
                    'status' => $room->roomStatus->name ?? 'N/A',
                    'occupied' => $isOccupied ? 'Oui' : 'Non',
                    'customer' => $currentTransaction ? $currentTransaction->customer->name : '-',
                    'check_in' => $currentTransaction ? $currentTransaction->check_in->format('d/m/Y') : '-',
                    'check_out' => $currentTransaction ? $currentTransaction->check_out->format('d/m/Y') : '-'
                ];
            }
            $date->addDay();
        }
        
        if ($request->format == 'pdf') {
            // Générer PDF
            return $this->generatePDF($data, $startDate, $endDate);
        } else {
            // Générer Excel
            return $this->generateExcel($data, $startDate, $endDate);
        }
    }
    
    private function generatePDF($data, $startDate, $endDate)
    {
        // À implémenter avec DomPDF
        return response()->json(['message' => 'PDF export à implémenter']);
    }
    
    private function generateExcel($data, $startDate, $endDate)
    {
        // À implémenter avec Maatwebsite/Excel
        return response()->json(['message' => 'Excel export à implémenter']);
    }
}