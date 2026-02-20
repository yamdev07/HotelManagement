<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Room;
use App\Models\Type;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    // Page d'accueil du site vitrine
    public function home()
    {
        $featuredRooms = Room::with(['type', 'roomStatus', 'images', 'facilities'])
            ->where('room_status_id', 1) // Available
            ->limit(3)
            ->get();

        return view('frontend.pages.home', compact('featuredRooms'));
    }

    // Liste des chambres
    public function rooms(Request $request)
    {
        // Requête de base
        $query = Room::with(['type', 'roomStatus', 'images', 'facilities'])
            ->where('room_status_id', 1); // Available

        // Filtres
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

        // Vérifier la disponibilité si des dates sont fournies
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = Carbon::parse($request->check_in)->startOfDay();
            $checkOut = Carbon::parse($request->check_out)->startOfDay();
            
            // Exclure les chambres qui ont des réservations pendant cette période
            $bookedRoomIds = Transaction::whereIn('status', ['reservation', 'active'])
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
                })
                ->pluck('room_id')
                ->toArray();
            
            $query->whereNotIn('id', $bookedRoomIds);
        }

        $rooms = $query->paginate(9)->appends($request->all());

        // Récupérer tous les types pour le filtre
        $types = Type::withCount('rooms')->get();

        // Statistiques pour les filtres
        $roomsByCapacity = Room::select('capacity', DB::raw('count(*) as total'))
            ->groupBy('capacity')
            ->pluck('total', 'capacity')
            ->toArray();

        $priceRanges = [
            '0-50000' => Room::whereBetween('price', [0, 50000])->count(),
            '50000-100000' => Room::whereBetween('price', [50000, 100000])->count(),
            '100000-150000' => Room::whereBetween('price', [100000, 150000])->count(),
            '150000-200000' => Room::whereBetween('price', [150000, 200000])->count(),
            '200000+' => Room::where('price', '>=', 200000)->count(),
        ];

        $totalRooms = Room::count();
        $availableCount = Room::where('room_status_id', 1)->count();
        $averageCapacity = Room::avg('capacity');
        $distinctTypes = Type::count();

        return view('frontend.pages.rooms', compact(
            'rooms',
            'types',
            'roomsByCapacity',
            'priceRanges',
            'totalRooms',
            'availableCount',
            'averageCapacity',
            'distinctTypes'
        ));
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
        
        $room->is_available_today = !$isOccupied && $room->room_status_id == 1;
        
        // Prochaine date disponible
        if (!$room->is_available_today) {
            $nextTransaction = Transaction::where('room_id', $room->id)
                ->where('check_out', '>', $today)
                ->whereIn('status', ['active', 'reservation'])
                ->orderBy('check_out')
                ->first();
            $room->next_available_date = $nextTransaction ? $nextTransaction->check_out->addDay() : null;
        }

        $relatedRooms = Room::with(['type', 'roomStatus', 'images'])
            ->where('type_id', $room->type_id)
            ->where('id', '!=', $room->id)
            ->where('room_status_id', 1)
            ->limit(3)
            ->get();

        return view('frontend.pages.room-details', compact('room', 'relatedRooms'));
    }

    // Restaurant vitrine
    public function restaurant()
    {
        $menus = Menu::all();

        return view('frontend.pages.restaurant', compact('menus'));
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
            // Sauvegarder le message dans la base de données
            // $contact = \App\Models\ContactMessage::create($validated);
            
            // Envoyer un email
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
            // Sauvegarder la réservation dans la base de données
            // $reservation = \App\Models\RestaurantReservation::create($validated);
            
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

    // API pour obtenir les chambres disponibles
    public function availableRooms(Request $request)
    {
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->startOfDay();
        $adults = $request->adults ?? 1;
        
        // Chambres réservées
        $bookedRoomIds = Transaction::whereIn('status', ['reservation', 'active'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                  ->where('check_out', '>', $checkIn);
            })
            ->pluck('room_id')
            ->toArray();
        
        // Chambres disponibles
        $rooms = Room::with('type')
            ->where('room_status_id', 1)
            ->whereNotIn('id', $bookedRoomIds)
            ->where('capacity', '>=', $adults) // Capacité suffisante
            ->when($request->room_type, function($q) use ($request) {
                return $q->where('type_id', $request->room_type);
            })
            ->when($request->max_price, function($q) use ($request) {
                return $q->where('price', '<=', $request->max_price);
            })
            ->get()
            ->map(function($room) {
                return [
                    'id' => $room->id,
                    'number' => $room->number,
                    'name' => $room->name,
                    'price' => $room->price,
                    'capacity' => $room->capacity,
                    'type_id' => $room->type_id,
                    'type_name' => $room->type->name ?? 'Standard',
                ];
            });
        
        return response()->json([
            'rooms' => $rooms,
            'count' => $rooms->count(),
            'dates' => [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'nights' => $checkIn->diffInDays($checkOut)
            ]
        ]);
    }

    // Traiter une demande de réservation de chambre
    public function reservationRequest(Request $request)
    {
        // LOGS AJOUTÉS
        Log::info('=== DÉBUT RÉSERVATION ===');
        Log::info('Données reçues:', $request->all());

        // Validation avec date de naissance
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
        Log::info('Date naissance brute: ' . $validated['birthdate']);

        DB::beginTransaction();

        try {
            $room = Room::findOrFail($validated['room_id']);
            
            Log::info('Chambre trouvée:', ['room_id' => $room->id, 'price' => $room->price]);

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

            // Vérifier la disponibilité
            $isAvailable = $room->isAvailableForPeriod($checkIn, $checkOut);
            
            if (!$isAvailable) {
                Log::warning('Chambre non disponible pour ces dates');
                
                return response()->json([
                    'success' => false,
                    'message' => 'La chambre n\'est plus disponible pour les dates sélectionnées.'
                ], 422);
            }

            Log::info('Chambre disponible');

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

                // FORMATAGE CORRECT DE LA DATE DE NAISSANCE POUR MYSQL
                try {
                    $birthdate = Carbon::parse($validated['birthdate'])->format('Y-m-d');
                    Log::info('Date naissance formatée pour MySQL: ' . $birthdate);
                } catch (\Exception $e) {
                    Log::error('Erreur formatage date: ' . $e->getMessage());
                    $birthdate = now()->subYears(30)->format('Y-m-d');
                    Log::info('Date naissance par défaut: ' . $birthdate);
                }

                // CORRECTION: Conversion du genre pour correspondre à la base de données
                // La base attend 'Male' ou 'Female' (pas 'Homme' ou 'Femme')
                $genderValue = match($validated['gender']) {
                    'Homme', 'Masculin', 'M' => 'Male',
                    'Femme', 'Féminin', 'F' => 'Female',
                    default => 'Other'
                };
                
                Log::info('Genre converti: ' . $validated['gender'] . ' -> ' . $genderValue);

                // Création du client avec tous les champs
                $customerData = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'gender' => $genderValue,  // Valeur convertie
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

            Log::info('Transaction créée avec ID: ' . $transaction->id);

            DB::commit();
            
            Log::info('=== RÉSERVATION RÉUSSIE ===');
            Log::info('Résumé:', [
                'client_id' => $customer->id,
                'client_nom' => $customer->name,
                'chambre' => $room->name . ' (' . $room->number . ')',
                'dates' => $checkIn->format('d/m/Y') . ' au ' . $checkOut->format('d/m/Y'),
                'nuits' => $nights,
                'total' => number_format($totalPrice, 0, ',', ' ') . ' FCFA',
                'date_naissance' => $birthdate,
                'genre_db' => $customer->gender
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre réservation a été confirmée avec succès !',
                'transaction_id' => $transaction->id,
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
            Log::error('ERREUR DE VALIDATION:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vérifier les informations fournies.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('ERREUR SQL:', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
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
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement de votre réservation. Veuillez réessayer ou nous contacter directement.'
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
            // Envoyer un email à l'hôtel
            // Mail::to('reservations@cactushotel.com')->send(new ReservationRequestMail($validated));
            
            // Sauvegarder dans une table "reservation_requests" si vous voulez
            // ReservationRequest::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Votre demande de réservation a été envoyée avec succès. Nous vous contacterons dans les 24h pour confirmer votre séjour.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur réservation: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer ou nous appeler directement.'
            ], 500);
        }
    }
}