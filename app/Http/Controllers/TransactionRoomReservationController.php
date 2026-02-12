<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\ChooseRoomRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Type;
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionRoomReservationController extends Controller
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    /**
     * Afficher le formulaire de création d'identité
     */
    public function createIdentity()
    {
        return view('transaction.reservation.createIdentity', [
            'info' => 'Same email can be used for multiple reservations. If customer exists, information will be updated.',
        ]);
    }

    /**
     * Enregistrer ou mettre à jour un client
     */
    public function storeCustomer(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'nullable|string',
            'job' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Rechercher un client avec le même email ET même nom
        $existingCustomer = Customer::where('email', $validated['email'])
            ->where('name', $validated['name'])
            ->first();

        if ($existingCustomer) {
            // Mettre à jour le client existant
            $updateData = [
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'job' => $validated['job'],
                'birthdate' => $validated['birthdate'],
            ];

            // Gérer l'avatar si fourni
            if ($request->hasFile('avatar')) {
                if ($existingCustomer->avatar && Storage::exists($existingCustomer->avatar)) {
                    Storage::delete($existingCustomer->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            $existingCustomer->update($updateData);
            $customer = $existingCustomer;

            $message = 'Informations client mises à jour : '.$customer->name;
        } else {
            // Récupérer l'utilisateur connecté
            $user = auth()->user();
            if (! $user) {
                return redirect()->route('login')
                    ->with('error', 'Vous devez être connecté pour créer un client');
            }

            // Créer un nouveau client - seulement les champs nécessaires
            $customerData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'address' => $validated['address'] ?? null,
                'job' => $validated['job'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'user_id' => $user->id,
            ];

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $customerData['avatar'] = $avatarPath;
            }

            $customer = Customer::create($customerData);
            $message = 'Nouveau client créé par '.$user->name.' : '.$customer->name;
        }

        return redirect()
            ->route('transaction.reservation.choose-type', ['customer' => $customer->id])
            ->with('success', $message);
    }

    /**
     * Afficher le formulaire pour choisir le type de chambre
     */
    public function chooseRoomType(Request $request, Customer $customer)
    {
        // Validation des dates si fournies
        if ($request->has(['check_in', 'check_out'])) {
            $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
            ]);
        }

        // Récupérer tous les types de chambre avec disponibilité
        $roomTypes = Type::withCount(['rooms as available_count' => function($query) use ($request) {
            $query->where('room_status_id', 1); // Disponible
            
            if ($request->has(['check_in', 'check_out'])) {
                $query->whereDoesntHave('transactions', function($q) use ($request) {
                    $q->where('check_in', '<', $request->check_out)
                      ->where('check_out', '>', $request->check_in)
                      ->whereIn('status', ['reservation', 'active']);
                });
            }
        }])->get();

        return view('transaction.reservation.chooseRoom', [
            'customer' => $customer,
            'roomTypes' => $roomTypes,
            'check_in' => $request->check_in ?? null,
            'check_out' => $request->check_out ?? null,
        ]);
    }

    /**
     * Afficher le formulaire pour saisir les dates de séjour (après choix du type)
     */
    public function viewCountPerson(Request $request, Customer $customer)
    {
        // Validation du type de chambre
        $request->validate([
            'room_type_id' => 'required|exists:types,id',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
        ]);

        $roomType = Type::find($request->room_type_id);
        $existingReservations = $customer->transactions()
            ->where('room_type_id', $roomType->id)
            ->count();

        return view('transaction.reservation.viewCountPerson', [
            'customer' => $customer,
            'roomType' => $roomType,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'existingReservations' => $existingReservations,
        ]);
    }

    /**
     * Confirmation de la réservation avec type (sans numéro de chambre)
     */
    public function confirmation(Request $request, Customer $customer, Type $roomType)
    {
        // Validation des dates
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
        ]);

        // Vérifier la disponibilité du type
        $availableCount = $this->getAvailableRoomCount(
            $roomType->id,
            $request->check_in,
            $request->check_out
        );

        if ($availableCount == 0) {
            return back()->withErrors([
                'error' => 'Aucune chambre de ce type disponible pour ces dates.'
            ])->withInput();
        }

        // Calcul du prix - CORRIGÉ : utiliser base_price
        $totalNights = Carbon::parse($request->check_in)
            ->diffInDays(Carbon::parse($request->check_out));
        $totalPrice = $roomType->base_price * $totalNights;
        $downPayment = $totalPrice * 0.15; // 15% d'acompte

        return view('transaction.reservation.confirmation', [
            'customer' => $customer,
            'roomType' => $roomType,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'totalNights' => $totalNights,
            'totalPrice' => $totalPrice,
            'downPayment' => $downPayment,
        ]);
    }

    /**
     * Stocker la réservation (sans attribuer de chambre)
     */
    public function storeReservation(Request $request)
    {
        \Log::info('🚀 ============ DÉBUT RÉSERVATION PAR TYPE ============');

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'room_type_id' => 'required|exists:types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
            'downPayment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:cash,card,mobile_money',
            'special_requests' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        \Log::info('📋 Données validées:', $validated);

        // DEBUG: Vérifiez le Type
        $roomType = Type::find($validated['room_type_id']);
        \Log::info('🔴 DEBUG RoomType:', [
            'id' => $roomType->id,
            'name' => $roomType->name,
            'base_price' => $roomType->base_price,
            'attributes' => $roomType->getAttributes(),
        ]);

        // Vérifier que base_price existe et n'est pas 0
        if (!$roomType->base_price || $roomType->base_price <= 0) {
            \Log::error('❌ ERREUR CRITIQUE: base_price invalide pour type ' . $roomType->id);
            return back()->with('error', 'Le prix du type de chambre n\'est pas configuré. Contactez l\'administrateur.')
                ->withInput();
        }

        // Vérifier la disponibilité
        $availableCount = $this->getAvailableRoomCount(
            $validated['room_type_id'],
            $validated['check_in'],
            $validated['check_out']
        );

        if ($availableCount == 0) {
            \Log::error('❌ Aucune chambre disponible pour ce type');
            return back()->withErrors([
                'error' => 'Plus aucune chambre disponible de ce type pour ces dates.'
            ])->withInput();
        }

        \Log::info('✅ Disponibilité vérifiée: '.$availableCount.' chambre(s) disponible(s)');

        // ============ GESTION UTILISATEUR ============
        $user = auth()->user();
        $userId = $this->getUserIdForTransaction($user);

        \Log::info('👤 Utilisateur de la transaction:', [
            'id' => $userId,
            'name' => $user->name ?? 'Système',
            'role' => $user->role ?? 'N/A'
        ]);

        // Calculer le prix total
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights == 0) $nights = 1;
        
        // Calcul des prix
        $totalPrice = $roomType->base_price * $nights * $validated['adults'];
        $downPayment = $validated['downPayment'] ?? ($totalPrice * 0.15);
        $personCount = $validated['adults'] + ($validated['children'] ?? 0);
        $paymentMethod = $validated['payment_method'] ?? 'cash';

        \Log::info('💰 Calculs financiers FINAUX:', [
            'base_price' => $roomType->base_price,
            'nights' => $nights,
            'adults' => $validated['adults'],
            'total_price_calculated' => $totalPrice,
            'down_payment' => $downPayment,
            'people' => $personCount,
            'payment_method' => $paymentMethod
        ]);

        // Vérifier que l'acompte n'excède pas le prix total
        if ($downPayment > $totalPrice) {
            \Log::error('❌ Acompte trop élevé: '.$downPayment.' > '.$totalPrice);
            return back()->with('error', 'L\'acompte ne peut pas dépasser le prix total')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // ============ CRÉATION DE LA TRANSACTION ============
            \Log::info('🔵 Création de la transaction...');

            // Préparer les données pour la transaction
            $transactionData = [
                'user_id' => $userId,
                'customer_id' => $validated['customer_id'],
                'room_type_id' => $validated['room_type_id'],
                'room_id' => null,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'person_count' => $personCount,
                'total_price' => $totalPrice,
                'total_payment' => $downPayment,
                'status' => 'reservation',
                'is_assigned' => false,
                'special_requests' => $validated['special_requests'] ?? null,
                'notes' => sprintf(
                    'Réservation par TYPE: %s | Créée par %s | %d nuit(s) | %s FCFA/nuit | Personnes: %d | À attribuer au check-in',
                    $roomType->name,
                    $user->name ?? 'Système',
                    $nights,
                    number_format($roomType->base_price, 0, ',', ' '),
                    $personCount
                ),
                'checkin_notes' => json_encode([
                    'agent' => $user->name ?? 'Système',
                    'nights' => $nights,
                    'price_per_night' => $roomType->base_price,
                    'room_type' => $roomType->name,
                    'payment_method' => $paymentMethod,
                    'down_payment' => $downPayment,
                    'total_amount' => $totalPrice,
                    'requires_assignment' => true,
                    'created_at' => now()->toDateTimeString(),
                ]),
            ];

            \Log::info('📋 Données transaction:', $transactionData);

            // Créer la transaction
            $transaction = Transaction::create($transactionData);
            \Log::info('✅ Transaction créée - ID: '.$transaction->id);

            // ============ CRÉATION DU PAIEMENT (si acompte) ============
            if ($downPayment > 0) {
                \Log::info('💰 Création du paiement: '.number_format($downPayment, 0, ',', ' ').' FCFA');

                try {
                    $paymentData = [
                        'user_id' => $userId,
                        'transaction_id' => $transaction->id,
                        'amount' => $downPayment,
                        'payment_method' => $paymentMethod,
                        'reference' => 'ACOMTE-'.$transaction->id.'-'.time(),
                        'status' => 'completed',
                        'notes' => sprintf(
                            'Acompte réservation par type | Agent: %s | Client: %s | Type: %s | Chambre à attribuer',
                            $user->name ?? 'Système',
                            $transaction->customer->name,
                            $roomType->name
                        ),
                    ];

                    $payment = \App\Models\Payment::create($paymentData);
                    \Log::info('✅ Paiement créé - ID: '.$payment->id);

                } catch (\Exception $e) {
                    \Log::warning('⚠️ Erreur création paiement: '.$e->getMessage());
                }
            }

            // ============ NOTIFICATION ============
            $this->sendAssignmentNotification($transaction, $user);

            // ============ CONFIRMATION BDD ============
            DB::commit();
            \Log::info('✅ Transaction BDD confirmée avec succès');

            // ============ MESSAGE DE SUCCÈS ============
            $successMessage = $this->buildReservationSuccessMessage($transaction, $user);

            \Log::info('🎊 RÉSERVATION PAR TYPE RÉUSSIE - ID: '.$transaction->id);
            
            // ============ DEBUG REDIRECTION ============
            \Log::info('🔴 === DEBUG REDIRECTION ===');
            \Log::info('Transaction ID: ' . $transaction->id);
            
            // Vérifier si la route existe
            $routeName = 'transaction.reservation.by-type.confirmation';
            $routeExists = \Illuminate\Support\Facades\Route::has($routeName);
            \Log::info('Route "' . $routeName . '" existe: ' . ($routeExists ? 'OUI' : 'NON'));
            
            if ($routeExists) {
                try {
                    $url = route($routeName, $transaction->id);
                    \Log::info('✅ URL générée: ' . $url);
                } catch (\Exception $e) {
                    \Log::error('❌ Erreur génération URL: ' . $e->getMessage());
                    $routeExists = false;
                }
            }
            
            \Log::info('🔴 === FIN DEBUG REDIRECTION ===');
            
            // ============ REDIRECTION ============
            if ($routeExists) {
                \Log::info('🟢 Redirection VERS route nommée');
                return redirect()->route($routeName, $transaction->id)
                    ->with('success', $successMessage)
                    ->with('transaction_id', $transaction->id)
                    ->with('agent_name', $user->name ?? 'Système')
                    ->with('requires_assignment', true);
            } else {
                // SOLUTION DE SECOURS : URL directe
                $confirmationUrl = url("/transaction/reservation/by-type/{$transaction->id}/confirmation");
                \Log::info('🟡 Redirection VERS URL directe: ' . $confirmationUrl);
                
                return redirect($confirmationUrl)
                    ->with('success', $successMessage)
                    ->with('transaction_id', $transaction->id)
                    ->with('agent_name', $user->name ?? 'Système')
                    ->with('requires_assignment', true);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Erreur création réservation: '.$e->getMessage());
            \Log::error('❌ Stack trace: '.$e->getTraceAsString());

            return back()->with('error', 'Erreur lors de la création de la réservation: '.$e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Calculer le nombre de chambres disponibles pour un type
     */
    private function getAvailableRoomCount($roomTypeId, $checkIn, $checkOut)
    {
        return Room::where('type_id', $roomTypeId)
            ->where('room_status_id', 1) // Disponible
            ->whereDoesntHave('transactions', function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn)
                    ->whereIn('status', ['reservation', 'active']);
            })
            ->count();
    }

    /**
     * Obtenir l'ID utilisateur pour la transaction
     */
    private function getUserIdForTransaction($user)
    {
        if ($user) {
            return $user->id;
        }

        \Log::warning('⚠️ Aucun utilisateur connecté, recherche d\'un admin...');

        // Rechercher un admin
        $admin = \App\Models\User::whereIn('role', ['Super', 'Admin'])->first();
        if ($admin) {
            \Log::info('✅ Admin trouvé pour substitution: '.$admin->name);
            return $admin->id;
        }

        // Prendre le premier utilisateur
        $firstUser = \App\Models\User::first();
        if ($firstUser) {
            \Log::info('✅ Premier utilisateur trouvé pour substitution: '.$firstUser->name);
            return $firstUser->id;
        }

        \Log::error('❌ AUCUN UTILISATEUR DANS LA BASE DE DONNÉES');
        throw new \Exception('Aucun utilisateur trouvé dans la base de données.');
    }

    /**
     * Envoyer une notification pour l'attribution
     */
    private function sendAssignmentNotification($transaction, $user)
    {
        // Simple log sans création de notification
        \Log::info('📢 Nouvelle réservation créée', [
            'transaction_id' => $transaction->id,
            'customer' => $transaction->customer->name,
            'room_type' => $transaction->roomType->name,
            'agent' => $user->name,
        ]);
    }
    /**
     * Construire le message de succès pour réservation par type
     */
    private function buildReservationSuccessMessage($transaction, $user)
    {
        $message = '<div class="alert alert-success border-0">';
        $message .= '<div class="d-flex align-items-center mb-3">';
        $message .= '<i class="fas fa-calendar-check fa-2x me-3 text-success"></i>';
        $message .= '<div>';
        $message .= '<h5 class="alert-heading mb-1">✅ Réservation par type confirmée !</h5>';
        $message .= '<p class="mb-0"><small>Réservée par <strong>'.$user->name.'</strong></small></p>';
        $message .= '</div>';
        $message .= '</div>';

        $message .= '<div class="row">';
        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-user me-2"></i>Client:</strong> '.$transaction->customer->name.'</p>';
        $message .= '<p><strong><i class="fas fa-tag me-2"></i>Type de chambre:</strong> '.$transaction->roomType->name.'</p>';
        $message .= '<p><strong><i class="fas fa-calendar-alt me-2"></i>Période:</strong> '.$transaction->check_in->format('d/m/Y').' → '.$transaction->check_out->format('d/m/Y').'</p>';
        $message .= '<p><strong><i class="fas fa-moon me-2"></i>Durée:</strong> '.$transaction->getNightsAttribute().' nuit'.($transaction->getNightsAttribute() > 1 ? 's' : '').'</p>';
        $message .= '</div>';

        $message .= '<div class="col-md-6">';
        $message .= '<p><strong><i class="fas fa-receipt me-2"></i>Prix total:</strong> '.$transaction->formatted_total_price.'</p>';

        if ($transaction->total_payment > 0) {
            $remaining = $transaction->getRemainingPayment();
            $message .= '<p class="text-success"><strong><i class="fas fa-money-bill-wave me-2"></i>Acompte payé:</strong> '.$transaction->formatted_total_payment.'</p>';
            if ($remaining > 0) {
                $message .= '<p class="text-warning"><strong><i class="fas fa-balance-scale me-2"></i>Solde à régler:</strong> '.$transaction->formatted_remaining_payment.'</p>';
            } else {
                $message .= '<p class="text-success"><strong><i class="fas fa-check-double me-2"></i>✅ Paiement complet</strong></p>';
            }
        } else {
            $message .= '<p class="text-info"><strong><i class="fas fa-clock me-2"></i>À régler à l\'arrivée:</strong> '.$transaction->formatted_total_price.'</p>';
        }

        $message .= '</div>';
        $message .= '</div>';

        // Section IMPORTANTE pour l'attribution
        $message .= '<div class="alert alert-warning mt-3">';
        $message .= '<h6><i class="fas fa-exclamation-triangle me-2"></i>IMPORTANT</h6>';
        $message .= '<p class="mb-2"><strong>❌ Aucune chambre attribuée pour le moment.</strong></p>';
        $message .= '<p class="mb-0">Vous devez attribuer un numéro de chambre au client lors du check-in.</p>';
        $message .= '<div class="mt-2">';
        $message .= '<a href="'.route('room-assignment.available-rooms', $transaction).'" class="btn btn-sm btn-warning me-2">';
        $message .= '<i class="fas fa-door-open"></i> Attribuer une chambre maintenant';
        $message .= '</a>';
        $message .= '<small class="text-muted">Ou attendez le jour d\'arrivée du client</small>';
        $message .= '</div>';
        $message .= '</div>';

        $message .= '<hr class="my-3">';
        $message .= '<div class="text-center">';
        $message .= '<small class="text-muted">';
        $message .= '<i class="fas fa-hashtag me-1"></i>Référence: #TRX-'.$transaction->id.' | ';
        $message .= '<i class="fas fa-user-circle me-1"></i>Agent: '.$user->name.' | ';
        $message .= '<i class="fas fa-calendar me-1"></i>Créé le: '.now()->format('d/m/Y H:i');
        $message .= '</small>';
        $message .= '</div>';
        $message .= '</div>';

        return $message;
    }

    /**
     * Rechercher un client par email (AJAX)
     */
    public function searchByEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customers = Customer::where('email', $request->email)->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'exists' => false,
                'message' => 'Aucun client trouvé avec cet email',
            ]);
        }

        $customerDetails = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'reservation_count' => $customer->transactions()->count(),
            ];
        });

        return response()->json([
            'exists' => true,
            'customers' => $customerDetails,
            'message' => 'Trouvé '.$customers->count().' client(s) avec cet email',
        ]);
    }

    /**
     * Afficher les réservations d'un client
     */
    public function showCustomerReservations(Customer $customer)
    {
        $reservations = $customer->transactions()
            ->with(['roomType', 'room', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transaction.reservation.customer-reservations', [
            'customer' => $customer,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Obtenir les IDs des chambres occupées (méthode ancienne - gardée pour compatibilité)
     */
    private function getOccupiedRoomID($stayFrom, $stayUntil)
    {
        \Log::info('🔍 === DEBUG getOccupiedRoomID SIMPLIFIÉ ===');
        \Log::info('📅 Période:', ['from' => $stayFrom, 'until' => $stayUntil]);

        // LOGIQUE CORRECTE ET SIMPLE :
        // Une chambre est occupée si sa réservation chevauche notre période
        $occupied = Transaction::where('status', '!=', 'cancelled')
            ->where(function ($query) use ($stayFrom, $stayUntil) {
                // La condition unique et correcte :
                // Réservation commence avant notre départ ET termine après notre arrivée
                $query->where('check_in', '<', $stayUntil)
                    ->where('check_out', '>', $stayFrom);
            })
            ->pluck('room_id')
            ->unique();

        \Log::info('📊 Résultat:', [
            'occupied_count' => $occupied->count(),
            'occupied_ids' => $occupied->toArray(),
        ]);

        return $occupied;
    }

    /**
     * Extraire le nom du champ à partir du message d'erreur SQL
     */
    private function extractFieldName($errorMessage)
    {
        if (preg_match("/Field '([^']+)' doesn't have a default value/", $errorMessage, $matches)) {
            return $matches[1];
        }

        return 'inconnu';
    }

    /**
     * Choisir parmi les clients existants
     */
    public function pickFromCustomer(Request $request)
    {
        $customers = Customer::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $customers->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $customers = $customers->orderBy('name')->paginate(20);
        
        return view('transaction.reservation.pick-from-customer', compact('customers'));
    }

    /**
     * Ancienne méthode - Gardée pour compatibilité
     */
    public function chooseRoom(ChooseRoomRequest $request, Customer $customer)
    {
        // Redirection vers le nouveau système par type
        return redirect()->route('transaction.reservation.choose-type', [
            'customer' => $customer->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out
        ]);
    }

    /**
     * Ancienne méthode - Gardée pour compatibilité
     */
    public function payDownPayment(
        Customer $customer,
        Room $room,
        Request $request,
        ?TransactionRepositoryInterface $transactionRepository = null,
        ?PaymentRepositoryInterface $paymentRepository = null
    ) {
        // Redirection vers le nouveau système
        return redirect()->route('transaction.reservation.choose-type', [
            'customer' => $customer->id,
            'check_in' => $request->check_in ?? now()->format('Y-m-d'),
            'check_out' => $request->check_out ?? now()->addDays(1)->format('Y-m-d')
        ])->with('info', 'Veuillez choisir un type de chambre plutôt qu\'un numéro spécifique.');
    }

    // Dans TransactionRoomReservationController.php
    public function showReservationConfirmation(Transaction $transaction)
    {
        // Charger les relations nécessaires
        $transaction->load(['customer', 'roomType', 'payments.user']);
        
        // Vérifier que c'est bien une réservation par type (pas de chambre attribuée)
        if ($transaction->room_id !== null) {
            return redirect()->route('transaction.show', $transaction)
                ->with('info', 'Cette réservation a déjà une chambre attribuée.');
        }
        
        return view('transaction.reservation.by-type-confirmation', [
            'transaction' => $transaction,
            'requires_assignment' => true,
        ]);
    }
}