<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\RestaurantReservation;
use App\Models\Room;
use App\Models\Type;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\ReservationNotification;
use App\Notifications\RestaurantReservationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function __construct()
    {
        if (class_exists(\Debugbar::class)) {
            \Debugbar::disable();
        }
    }

    // Page d'accueil du site vitrine
    public function home()
    {
        // Pour la page d'accueil, on garde seulement les chambres disponibles (belles photos)
        $featuredRooms = Room::with(['type', 'roomStatus', 'images', 'facilities'])
            ->where('room_status_id', Room::STATUS_AVAILABLE) // Disponible uniquement
            ->limit(3)
            ->get();

        return view('frontend.pages.home', compact('featuredRooms'));
    }


/**
 * Liste des chambres (AVEC DÉBOGAGE COMPLET)
 */
public function rooms(Request $request)
{
    // ==================== DÉBOGAGE CHAMBRE 101 ====================
    $this->debugRoom101($request);
    // =============================================================

    // Inclure les chambres sales (STATUS_DIRTY = 6) et disponibles (STATUS_AVAILABLE = 1)
    $query = Room::with(['type', 'roomStatus', 'images', 'facilities'])
        ->whereIn('room_status_id', [
            Room::STATUS_AVAILABLE, // 1 - Disponible
            Room::STATUS_DIRTY      // 6 - Sale (mais réservable)
        ])
        ->orderBy('number', 'asc'); // 👈 SEULE MODIFICATION : FORCER LE TRI PAR NUMÉRO

    // --- DÉBOGAGE AVANT FILTRES ---
    $beforeFilterCount = $query->count();
    Log::info('📊 NOMBRE DE CHAMBRES AVANT FILTRES: ' . $beforeFilterCount);
    
    $beforeFilterNumbers = $query->pluck('number')->toArray();
    Log::info('📋 CHAMBRES AVANT FILTRES:', $beforeFilterNumbers);
    // -----------------------------

    // Filtres
    if ($request->filled('type')) {
        $query->where('type_id', $request->type);
        Log::info('🔍 FILTRE TYPE APPLIQUÉ: type_id = ' . $request->type);
    }

    if ($request->filled('capacity')) {
        $query->where('capacity', $request->capacity);
        Log::info('🔍 FILTRE CAPACITÉ APPLIQUÉ: capacity >= ' . $request->capacity);
    }

    if ($request->filled('price_range')) {
        $range = $request->price_range;
        if ($range === '200000+') {
            $query->where('price', '>=', 200000);
            Log::info('🔍 FILTRE PRIX APPLIQUÉ: price >= 200000');
        } else {
            [$min, $max] = explode('-', $range);
            $query->whereBetween('price', [(int) $min, (int) $max]);
            Log::info('🔍 FILTRE PRIX APPLIQUÉ: price entre ' . $min . ' et ' . $max);
        }
    }

    // Vérifier la disponibilité si des dates sont fournies
    if ($request->filled('check_in') && $request->filled('check_out')) {
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();
        
        Log::info('🔍 FILTRE DATES APPLIQUÉ: du ' . $checkIn->format('d/m/Y') . ' au ' . $checkOut->format('d/m/Y'));
        
        // Exclure les chambres qui ont des réservations pendant cette période
        $bookedRoomIds = Transaction::whereIn('status', ['reservation', 'active'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                  ->where('check_out', '>', $checkIn);
            })
            ->pluck('room_id')
            ->toArray();
        
        if (!empty($bookedRoomIds)) {
            Log::info('🚫 CHAMBRES RÉSERVÉES EXCLUES:', $bookedRoomIds);
            $query->whereNotIn('id', $bookedRoomIds);
        } else {
            Log::info('✅ Aucune chambre réservée pour ces dates');
        }
    }

    // --- DÉBOGAGE APRÈS FILTRES ---
    $afterFilterCount = $query->count();
    Log::info('📊 NOMBRE DE CHAMBRES APRÈS FILTRES: ' . $afterFilterCount);
    
    $afterFilterNumbers = $query->pluck('number')->toArray();
    Log::info('📋 CHAMBRES APRÈS FILTRES:', $afterFilterNumbers);
    
    // Vérification spécifique de la chambre 101
    $has101 = in_array('101', $afterFilterNumbers);
    Log::info('🔎 CHAMBRE 101 DANS LA LISTE APRÈS FILTRES? ' . ($has101 ? 'OUI ✅' : 'NON ❌'));
    
    if (!$has101) {
        Log::info('🔍 RECHERCHE DES RAISONS POUR LA CHAMBRE 101:');
        
        $room101 = Room::where('number', '101')->first();
        if ($room101) {
            Log::info('📌 CHAMBRE 101 EN BASE:', [
                'id' => $room101->id,
                'status_id' => $room101->room_status_id,
                'status_label' => $room101->status_label,
                'type_id' => $room101->type_id,
                'capacity' => $room101->capacity,
                'price' => $room101->price
            ]);
            
            // Vérifier le statut
            $statusOk = in_array($room101->room_status_id, [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY]);
            Log::info('- Statut autorisé (1 ou 6)? ' . ($statusOk ? 'OUI ✅' : 'NON ❌'));
            
            // Vérifier chaque filtre
            if ($request->filled('type')) {
                $typeOk = ($room101->type_id == $request->type);
                Log::info('- Correspond au filtre type (type_id=' . $request->type . ')? ' . ($typeOk ? 'OUI ✅' : 'NON ❌'));
            }
            
            if ($request->filled('capacity')) {
                $capacityOk = ($room101->capacity >= $request->capacity);
                Log::info('- Correspond au filtre capacité (capacity>=' . $request->capacity . ')? ' . ($capacityOk ? 'OUI ✅' : 'NON ❌'));
            }
            
            if ($request->filled('price_range')) {
                $range = $request->price_range;
                if ($range === '200000+') {
                    $priceOk = ($room101->price >= 200000);
                } else {
                    [$min, $max] = explode('-', $range);
                    $priceOk = ($room101->price >= (int)$min && $room101->price <= (int)$max);
                }
                Log::info('- Correspond au filtre prix? ' . ($priceOk ? 'OUI ✅' : 'NON ❌'));
            }
            
            if ($request->filled('check_in') && $request->filled('check_out')) {
                $checkIn = Carbon::parse($request->check_in)->startOfDay();
                $checkOut = Carbon::parse($request->check_out)->startOfDay();
                
                $hasConflict = Transaction::where('room_id', $room101->id)
                    ->whereIn('status', ['reservation', 'active'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<', $checkOut)
                          ->where('check_out', '>', $checkIn);
                    })
                    ->exists();
                
                Log::info('- Conflit de réservation sur ces dates? ' . ($hasConflict ? 'OUI ❌' : 'NON ✅'));
            }
            
            // Vérifier la relation avec le type
            $typeExists = Type::find($room101->type_id);
            Log::info('- Le type_id ' . $room101->type_id . ' existe dans la table types? ' . ($typeExists ? 'OUI ✅' : 'NON ❌'));
            
            if (!$typeExists) {
                Log::info('  ⚠️  PROBLÈME: Le type_id ' . $room101->type_id . ' n\'existe pas dans la table types!');
                Log::info('  💡 SOLUTION: Mettez à jour le type_id de la chambre 101 avec UPDATE rooms SET type_id = 1 WHERE number = \'101\';');
            }
        } else {
            Log::info('❌ CHAMBRE 101 INTROUVABLE EN BASE');
        }
    }
    // -----------------------------

    // Pagination
    $rooms = $query->paginate(9)->appends($request->all());

    // Vérification de la page actuelle
    $currentPage = $rooms->currentPage();
    $totalPages = $rooms->lastPage();
    Log::info('📄 PAGE ACTUELLE: ' . $currentPage . ' / ' . $totalPages);
    
    // Vérifier si la 101 est dans la page actuelle
    $room101InPage = $rooms->contains('number', '101');
    Log::info('🔎 CHAMBRE 101 DANS LA PAGE ACTUELLE? ' . ($room101InPage ? 'OUI ✅' : 'NON ❌'));

    // Transformer les chambres
    $rooms->getCollection()->transform(function ($room) {
        $room->loadMissing(['type', 'roomStatus', 'facilities']);
        $room->is_dirty = ($room->room_status_id == Room::STATUS_DIRTY);
        $room->is_clean = ($room->room_status_id == Room::STATUS_AVAILABLE);
        return $room;
    });

    // Récupérer tous les types pour le filtre
    $types = Type::withCount('rooms')->get();

    // Statistiques pour les filtres
    $roomsByCapacity = Room::select('capacity', DB::raw('count(*) as total'))
        ->whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
        ->groupBy('capacity')
        ->pluck('total', 'capacity')
        ->toArray();

    $priceRanges = [
        '0-50000' => Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->whereBetween('price', [0, 50000])->count(),
        '50000-100000' => Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->whereBetween('price', [50000, 100000])->count(),
        '100000-150000' => Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->whereBetween('price', [100000, 150000])->count(),
        '150000-200000' => Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->whereBetween('price', [150000, 200000])->count(),
        '200000+' => Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->where('price', '>=', 200000)->count(),
    ];

    $totalRooms = Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])->count();
    $availableCount = Room::where('room_status_id', Room::STATUS_AVAILABLE)->count();
    $dirtyCount = Room::where('room_status_id', Room::STATUS_DIRTY)->count();
    $averageCapacity = Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])->avg('capacity');
    $distinctTypes = Type::count();

    // Résumé final
    Log::info('=== RÉSUMÉ FINAL ===');
    Log::info('Total chambres dans la page: ' . $rooms->count());
    Log::info('Numéros dans cette page: ' . implode(', ', $rooms->pluck('number')->toArray()));

    return view('frontend.pages.rooms', compact(
        'rooms',
        'types',
        'roomsByCapacity',
        'priceRanges',
        'totalRooms',
        'availableCount',
        'dirtyCount',
        'averageCapacity',
        'distinctTypes'
    ));
}

    /**
     * Fonction de débogage pour la chambre 101
     */
    private function debugRoom101(Request $request)
    {
        Log::info('========== DÉBOGAGE CHAMBRE 101 ==========');
        
        // 1. Vérifier si la chambre 101 existe en base
        $room101 = Room::where('number', '101')->first();
        
        if (!$room101) {
            Log::error('❌ CHAMBRE 101 N\'EXISTE PAS EN BASE DE DONNÉES');
            session()->flash('debug_room101', [
                'exists' => false,
                'message' => 'Chambre 101 introuvable en base de données'
            ]);
            return;
        }

        Log::info('✅ CHAMBRE 101 TROUVÉE EN BASE', [
            'id' => $room101->id,
            'statut' => $room101->room_status_id,
            'statut_label' => $room101->status_label,
            'type_id' => $room101->type_id,
            'capacite' => $room101->capacity,
            'prix' => $room101->price,
            'is_available_today' => $room101->is_available_today, // Maintenant booléen
            'est_occupee' => $room101->isOccupied()
        ]);

        // 2. Vérifier si elle est dans les statuts autorisés
        $statusOk = in_array($room101->room_status_id, [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY]);
        Log::info('Statut autorisé? ' . ($statusOk ? 'OUI' : 'NON'));

        // 3. Vérifier les filtres de la requête
        $filtres = [];
        
        if ($request->filled('type')) {
            $filtres['type'] = [
                'filtre' => $request->type,
                'chambre' => $room101->type_id,
                'ok' => $room101->type_id == $request->type
            ];
        }
        
        if ($request->filled('capacity')) {
            $filtres['capacity'] = [
                'filtre' => $request->capacity,
                'chambre' => $room101->capacity,
                'ok' => $room101->capacity >= $request->capacity
            ];
        }
        
        if ($request->filled('price_range')) {
            $range = $request->price_range;
            if ($range === '200000+') {
                $ok = $room101->price >= 200000;
                $filtres['price'] = [
                    'filtre' => '200000+',
                    'chambre' => $room101->price,
                    'ok' => $ok
                ];
            } else {
                [$min, $max] = explode('-', $range);
                $ok = $room101->price >= (int)$min && $room101->price <= (int)$max;
                $filtres['price'] = [
                    'filtre' => "{$min}-{$max}",
                    'chambre' => $room101->price,
                    'ok' => $ok
                ];
            }
        }
        
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            
            $hasConflict = Transaction::where('room_id', $room101->id)
                ->whereIn('status', ['reservation', 'active'])
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
                })
                ->exists();
            
            $filtres['disponibilite'] = [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'conflit' => $hasConflict,
                'ok' => !$hasConflict
            ];
        }

        Log::info('Vérification des filtres:', $filtres);

        // 4. Compter le nombre total de chambres après filtres
        $query = Room::whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY]);
        
        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }
        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }
        if ($request->filled('price_range')) {
            $range = $request->price_range;
            if ($range === '200000+') {
                $query->where('price', '>=', 200000);
            } else {
                [$min, $max] = explode('-', $range);
                $query->whereBetween('price', [(int) $min, (int) $max]);
            }
        }
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            
            $bookedRoomIds = Transaction::whereIn('status', ['reservation', 'active'])
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
                })
                ->pluck('room_id')
                ->toArray();
            
            $query->whereNotIn('id', $bookedRoomIds);
        }

        $allRooms = $query->get();
        $room101InList = $allRooms->firstWhere('id', $room101->id);

        if ($room101InList) {
            $position = $allRooms->search(function($item) use ($room101) {
                return $item->id === $room101->id;
            });
            
            $page = floor($position / 9) + 1;
            
            Log::info('✅ CHAMBRE 101 DANS LA LISTE', [
                'position' => $position,
                'page' => $page,
                'total_chambres' => $allRooms->count()
            ]);
            
            session()->flash('debug_room101', [
                'exists' => true,
                'in_list' => true,
                'position' => $position,
                'page' => $page,
                'total' => $allRooms->count(),
                'statut' => $room101->status_label,
                'is_available_today' => $room101->is_available_today
            ]);
        } else {
            Log::warning('❌ CHAMBRE 101 ABSENTE DE LA LISTE');
            
            // Trouver la raison
            $raisons = [];
            
            if (!in_array($room101->room_status_id, [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])) {
                $raisons[] = "Statut non autorisé: {$room101->room_status_id}";
            }
            
            if ($request->filled('type') && $room101->type_id != $request->type) {
                $raisons[] = "Filtre type: chambre type {$room101->type_id} ≠ filtre {$request->type}";
            }
            
            if ($request->filled('capacity') && $room101->capacity < $request->capacity) {
                $raisons[] = "Filtre capacité: chambre {$room101->capacity} < {$request->capacity}";
            }
            
            if ($request->filled('price_range')) {
                $range = $request->price_range;
                if ($range === '200000+' && $room101->price < 200000) {
                    $raisons[] = "Filtre prix: chambre {$room101->price} < 200000";
                } elseif (strpos($range, '-') !== false) {
                    [$min, $max] = explode('-', $range);
                    if ($room101->price < (int)$min || $room101->price > (int)$max) {
                        $raisons[] = "Filtre prix: chambre {$room101->price} hors intervalle {$min}-{$max}";
                    }
                }
            }
            
            if ($request->filled('check_in') && $request->filled('check_out')) {
                $checkIn = Carbon::parse($request->check_in)->startOfDay();
                $checkOut = Carbon::parse($request->check_out)->startOfDay();
                
                $hasConflict = Transaction::where('room_id', $room101->id)
                    ->whereIn('status', ['reservation', 'active'])
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<', $checkOut)
                          ->where('check_out', '>', $checkIn);
                    })
                    ->exists();
                
                if ($hasConflict) {
                    $raisons[] = "Conflit de réservation sur ces dates";
                }
            }
            
            session()->flash('debug_room101', [
                'exists' => true,
                'in_list' => false,
                'raisons' => $raisons,
                'chambre' => [
                    'id' => $room101->id,
                    'statut' => $room101->room_status_id,
                    'statut_label' => $room101->status_label,
                    'type' => $room101->type_id,
                    'capacite' => $room101->capacity,
                    'prix' => $room101->price,
                    'is_available_today' => $room101->is_available_today
                ],
                'filtres_actifs' => [
                    'type' => $request->type,
                    'capacity' => $request->capacity,
                    'price_range' => $request->price_range,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out
                ]
            ]);
        }
        
        Log::info('========== FIN DÉBOGAGE CHAMBRE 101 ==========');
    }

    // Détails d'une chambre
    public function roomDetails($id)
    {
        $room = Room::with(['type', 'roomStatus', 'images', 'facilities'])
            ->findOrFail($id);

        // Vérifier la disponibilité en temps réel
        $today = now()->startOfDay();
        $isOccupied = Transaction::where('room_id', $room->id)
            ->where('check_in', '<=', $today)
            ->where('check_out', '>=', $today)
            ->whereIn('status', ['active', 'reservation'])
            ->exists();
        
        $room->is_available_today = !$isOccupied && in_array($room->room_status_id, [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY]);
        $room->is_dirty = ($room->room_status_id == Room::STATUS_DIRTY);
        $room->is_available = ($room->room_status_id == Room::STATUS_AVAILABLE);
        $room->can_check_in = $room->canCheckIn();
        $room->can_be_booked = $room->isAvailableForBooking();
        $room->status_label = $room->status_label;
        $room->status_color = $room->status_color;
        $room->status_icon = $room->status_icon;
        
        // Vérifier si la chambre a des réservations aujourd'hui
        $room->has_reservation_today = Transaction::where('room_id', $room->id)
            ->whereIn('status', ['reservation', 'active'])
            ->whereDate('check_in', $today)
            ->exists();
        
        // Prochaine date disponible
        if (!$room->is_available_today) {
            $nextTransaction = Transaction::where('room_id', $room->id)
                ->where('check_out', '>', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->orderBy('check_out')
                ->first();
            $room->next_available_date = $nextTransaction ? $nextTransaction->check_out->addDay() : null;
        } else {
            $room->next_available_date = $today;
        }

        // Périodes disponibles pour les 30 prochains jours
        $room->available_periods = $room->getAvailablePeriods($today, $today->copy()->addDays(30), 1);

        // Chambres similaires (incluant les chambres sales)
        $relatedRooms = Room::with(['type', 'roomStatus', 'images'])
            ->where('type_id', $room->type_id)
            ->where('id', '!=', $room->id)
            ->whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->limit(3)
            ->get()
            ->map(function($relatedRoom) {
                $relatedRoom->is_dirty = ($relatedRoom->room_status_id == Room::STATUS_DIRTY);
                $relatedRoom->status_label = $relatedRoom->status_label;
                $relatedRoom->status_color = $relatedRoom->status_color;
                return $relatedRoom;
            });

        return view('frontend.pages.room-details', compact('room', 'relatedRooms'));
    }

    // API pour obtenir les chambres disponibles
    public function availableRooms(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'room_type' => 'nullable|exists:types,id',
            'max_price' => 'nullable|numeric|min:0',
        ]);

        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();
        $adults = $request->adults ?? 1;
        $nights = $checkIn->diffInDays($checkOut);
        
        // Chambres réservées
        $bookedRoomIds = Transaction::whereIn('status', ['reservation', 'active'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                  ->where('check_out', '>', $checkIn);
            })
            ->pluck('room_id')
            ->toArray();
        
        // Chambres disponibles (incluant les chambres sales)
        $rooms = Room::with('type')
            ->whereIn('room_status_id', [Room::STATUS_AVAILABLE, Room::STATUS_DIRTY])
            ->whereNotIn('id', $bookedRoomIds)
            ->where('capacity', '>=', $adults)
            ->when($request->room_type, function($q) use ($request) {
                return $q->where('type_id', $request->room_type);
            })
            ->when($request->max_price, function($q) use ($request) {
                return $q->where('price', '<=', $request->max_price);
            })
            ->get()
            ->map(function($room) use ($nights) {
                return [
                    'id' => $room->id,
                    'number' => $room->number,
                    'name' => $room->name,
                    'price' => $room->price,
                    'total_price' => $room->price * $nights,
                    'formatted_price' => number_format($room->price, 0, ',', ' ') . ' FCFA',
                    'formatted_total' => number_format($room->price * $nights, 0, ',', ' ') . ' FCFA',
                    'capacity' => $room->capacity,
                    'type_id' => $room->type_id,
                    'type_name' => $room->type->name ?? 'Standard',
                    'is_dirty' => ($room->room_status_id == Room::STATUS_DIRTY),
                    'is_available' => ($room->room_status_id == Room::STATUS_AVAILABLE),
                    'status_label' => $room->status_label,
                    'status_color' => $room->status_color,
                    'status_icon' => $room->status_icon,
                    'can_check_in' => $room->canCheckIn(),
                    'image' => $room->first_image_url,
                ];
            });
        
        return response()->json([
            'success' => true,
            'rooms' => $rooms,
            'count' => $rooms->count(),
            'dates' => [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'nights' => $nights,
                'formatted_check_in' => $checkIn->format('d/m/Y'),
                'formatted_check_out' => $checkOut->format('d/m/Y'),
            ]
        ]);
    }

    // Traiter une demande de réservation de chambre (avec gestion des chambres sales)
    public function reservationRequest(Request $request)
    {
        Log::info('=== DÉBUT RÉSERVATION ===');
        Log::info('Données reçues:', $request->all());

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'job' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        Log::info('Validation réussie');

        DB::beginTransaction();

        try {
            $room = Room::findOrFail($validated['room_id']);
            
            Log::info('Chambre trouvée:', [
                'room_id' => $room->id, 
                'price' => $room->price,
                'status' => $room->status_label,
                'is_dirty' => ($room->room_status_id == Room::STATUS_DIRTY)
            ]);

            $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
            $checkOut = Carbon::parse($validated['check_out'])->startOfDay();
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price * $nights;

            Log::info('Calculs:', [
                'check_in' => $checkIn, 
                'check_out' => $checkOut, 
                'nights' => $nights, 
                'total' => $totalPrice
            ]);

            // Vérifier la disponibilité (utilise la méthode du modèle)
            $isAvailable = $room->isAvailableForPeriod($checkIn, $checkOut);
            
            if (!$isAvailable) {
                Log::warning('Chambre non disponible pour ces dates');
                
                return response()->json([
                    'success' => false,
                    'message' => 'La chambre n\'est plus disponible pour les dates sélectionnées.'
                ], 422);
            }

            Log::info('Chambre disponible pour réservation');

            // Vérifier si le client existe déjà
            $customer = Customer::where('email', $validated['email'])->first();
            
            if (!$customer) {
                Log::info('Création nouveau client pour email: ' . $validated['email']);
                
                // Créer un utilisateur pour le client
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'Customer',
                    'random_key' => Str::random(60),
                ]);

                Log::info('Utilisateur créé avec ID: ' . $user->id);

                // Formatage de la date de naissance
                try {
                    $birthdate = Carbon::parse($validated['birthdate'])->format('Y-m-d');
                    Log::info('Date naissance formatée: ' . $birthdate);
                } catch (\Exception $e) {
                    Log::error('Erreur formatage date: ' . $e->getMessage());
                    $birthdate = now()->subYears(30)->format('Y-m-d');
                }

                // Conversion du genre
                $genderValue = match(strtolower($validated['gender'])) {
                    'homme', 'masculin', 'm', 'male' => 'Male',
                    'femme', 'feminin', 'f', 'female' => 'Female',
                    default => 'Other'
                };
                
                Log::info('Genre converti: ' . $validated['gender'] . ' -> ' . $genderValue);

                // Création du client
                $customerData = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'gender' => $genderValue,
                    'job' => $validated['job'],
                    'birthdate' => $birthdate,
                    'user_id' => $user->id,
                ];

                Log::info('Données client:', $customerData);
                
                $customer = Customer::create($customerData);
                
                Log::info('Nouveau client créé avec ID: ' . $customer->id);
            } else {
                Log::info('Client existant trouvé avec ID: ' . $customer->id);
            }

            // Préparer les notes avec toutes les informations
            $notes = "Réservation en ligne\n" .
                    "Client: {$validated['name']}\n" .
                    "Email: {$validated['email']}\n" .
                    "Téléphone: {$validated['phone']}\n" .
                    "Adresse: {$validated['address']}\n" .
                    "Genre: {$validated['gender']}\n" .
                    "Profession: {$validated['job']}\n" .
                    "Date naissance: {$validated['birthdate']}\n" .
                    "Adultes: {$validated['adults']}\n" .
                    "Enfants: " . ($validated['children'] ?? 0) . "\n" .
                    ($validated['notes'] ?? '');

            // Ajouter une mention si la chambre est sale
            if ($room->room_status_id == Room::STATUS_DIRTY) {
                $notes .= "\n\n⚠️ NOTE: Chambre réservée alors qu'elle était sale. Le check-in nécessitera un nettoyage préalable.";
            }

            // Créer la transaction (réservation)
            $transactionData = [
                'customer_id' => $customer->id,
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_price' => $totalPrice,
                'person_count' => ($validated['adults'] ?? 1) + ($validated['children'] ?? 0),
                'status' => 'reservation',
                'notes' => $notes,
                'created_by' => null,
            ];

            Log::info('Données transaction:', $transactionData);
            
            $transaction = Transaction::create($transactionData);

            // Journaliser l'action
            activity()
                ->performedOn($room)
                ->withProperties([
                    'transaction_id' => $transaction->id,
                    'customer' => $customer->name,
                    'check_in' => $checkIn->format('d/m/Y'),
                    'check_out' => $checkOut->format('d/m/Y'),
                    'room_was_dirty' => ($room->room_status_id == Room::STATUS_DIRTY)
                ])
                ->log('réservation en ligne');

            DB::commit();

            // Notifier tout le personnel (sauf les clients)
            try {
                $transaction->load(['customer', 'room']);
                $staffUsers = User::staff()->get();
                foreach ($staffUsers as $staffUser) {
                    $staffUser->notify(new ReservationNotification($transaction));
                }
            } catch (\Exception $notifException) {
                Log::warning('Erreur envoi notification réservation: ' . $notifException->getMessage());
            }

            Log::info('=== RÉSERVATION RÉUSSIE ===');
            Log::info('Résumé:', [
                'client_id' => $customer->id,
                'client_nom' => $customer->name,
                'chambre' => $room->name . ' (' . $room->number . ')',
                'dates' => $checkIn->format('d/m/Y') . ' au ' . $checkOut->format('d/m/Y'),
                'nuits' => $nights,
                'total' => number_format($totalPrice, 0, ',', ' ') . ' FCFA',
                'chambre_sale' => ($room->room_status_id == Room::STATUS_DIRTY) ? 'OUI' : 'NON'
            ]);

            // Message personnalisé selon l'état de la chambre
            $message = 'Votre réservation a été confirmée avec succès !';
            if ($room->room_status_id == Room::STATUS_DIRTY) {
                $message = 'Votre réservation a été confirmée. La chambre sera prête après nettoyage pour votre arrivée.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'transaction_id' => $transaction->id,
                'room_was_dirty' => ($room->room_status_id == Room::STATUS_DIRTY),
                'transaction' => [
                    'id' => $transaction->id,
                    'check_in' => $checkIn->format('d/m/Y'),
                    'check_out' => $checkOut->format('d/m/Y'),
                    'nights' => $nights,
                    'total_price' => number_format($totalPrice, 0, ',', ' ') . ' FCFA',
                    'room_number' => $room->number,
                    'room_name' => $room->name,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('ERREUR DE VALIDATION:', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vérifier les informations fournies.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('ERREUR SQL:', [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur de base de données. Veuillez réessayer.'
            ], 500);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('ERREUR RÉSERVATION: ' . $e->getMessage());
            Log::error('Type: ' . get_class($e));
            Log::error('Fichier: ' . $e->getFile() . ' Ligne: ' . $e->getLine());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer ou nous contacter.'
            ], 500);
        }
    }

    // Afficher le formulaire de réservation
    public function reservationForm()
    {
        $roomTypes = Type::all();
        return view('frontend.pages.reservation', compact('roomTypes'));
    }

    // Traiter la demande de réservation (version simple)
    public function submitReservation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'room_type' => 'nullable|exists:types,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            Log::info('Demande de réservation simple reçue:', $validated);
            
            // Ici vous pouvez envoyer un email ou sauvegarder dans une table
            // Mail::to('reservations@cactushotel.com')->send(new ReservationRequestMail($validated));
            
            return response()->json([
                'success' => true,
                'message' => 'Votre demande de réservation a été envoyée avec succès. Nous vous contacterons dans les 24h pour confirmer votre séjour.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur réservation simple: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer ou nous appeler directement.'
            ], 500);
        }
    }

    // Restaurant vitrine
    public function restaurant()
    {
        $currentDay = strtolower(now()->format('D')); // mon, tue, wed, thu, fri, sat, sun
        
        $menus = Menu::with('category')->where('is_available', true)
            ->where(function ($q) use ($currentDay) {
                $q->whereJsonContains('available_days', $currentDay)
                    ->orWhereNull('available_days');
            })
            ->latest()
            ->get()
            ->map(function ($m) {
                $m->image = $m->image_url;
                return $m;
            });

        $categories = \App\Models\Category::all();

        return view('frontend.pages.restaurant', compact('menus', 'categories'));
    }


    // Services
    public function services()
    {
        return view('frontend.pages.services');
    }

    // Contact
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    // Envoyer message de contact
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'newsletter' => 'nullable|boolean',
        ]);

        try {
            Log::info('Message de contact reçu:', $validated);
            
            // Ici vous pouvez envoyer un email ou sauvegarder dans une table
            // Mail::to('contact@luxurypalace.com')->send(new ContactFormMail($validated));

            return redirect()->back()->with([
                'success' => 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.',
                'status' => 'success',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur contact: ' . $e->getMessage());
            
            return redirect()->back()->with([
                'error' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.',
                'status' => 'error',
            ])->withInput();
        }
    }

    // Réservation restaurant
    public function restaurantReservationStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'persons' => 'required|integer|min:1|max:20',
            'table_type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $reservation = RestaurantReservation::create([
                'name'             => $validated['name'],
                'phone'            => $validated['phone'],
                'reservation_date' => $validated['date'],
                'reservation_time' => $validated['time'],
                'persons'          => $validated['persons'],
                'table_type'       => $validated['table_type'] ?? null,
                'notes'            => $validated['notes'] ?? null,
                'status'           => 'pending',
            ]);

            // Notifier tout le personnel
            try {
                $staffUsers = User::staff()->get();
                foreach ($staffUsers as $staffUser) {
                    $staffUser->notify(new RestaurantReservationNotification($reservation));
                }
            } catch (\Exception $notifException) {
                Log::warning('Erreur envoi notification réservation restaurant: ' . $notifException->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Réservation envoyée avec succès ! Nous vous contacterons pour confirmer.',
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur réservation restaurant: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }
    /**
     * Vue simplifiée du menu pour scan QR Code (Tablette Restaurant)
     */
    public function menuQr()
    {
        $categories = \App\Models\Category::all();
        $menus = Menu::with('category')->latest()->get();

        return view('frontend.pages.menu-qr', compact('categories', 'menus'));
    }
}