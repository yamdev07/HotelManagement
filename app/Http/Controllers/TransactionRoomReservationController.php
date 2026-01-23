<?php

namespace App\Http\Controllers;

use App\Events\NewReservationEvent;
use App\Events\RefreshDashboardEvent;
use App\Helpers\Helper;
use App\Http\Requests\ChooseRoomRequest;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewRoomReservationDownPayment;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\PaymentRepositoryInterface;
use App\Repositories\Interface\ReservationRepositoryInterface;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionRoomReservationController extends Controller
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    /**
     * Afficher la liste des clients existants
     */
    public function pickFromCustomer(Request $request, CustomerRepositoryInterface $customerRepository)
    {
        $customers = $customerRepository->get($request);
        $customersCount = $customerRepository->count($request);

        return view('transaction.reservation.pickFromCustomer', [
            'customers' => $customers,
            'customersCount' => $customersCount,
        ]);
    }

    /**
     * Afficher le formulaire de création d'identité
     * Permet d'entrer un email qui peut être utilisé pour plusieurs réservations
     */
    public function createIdentity()
    {
        return view('transaction.reservation.createIdentity', [
            'info' => 'Same email can be used for multiple reservations. If customer exists, information will be updated.'
        ]);
    }

    /**
     * Enregistrer ou mettre à jour un client
     * Un email peut être utilisé pour plusieurs réservations
     */
    public function storeCustomer(Request $request)
    {
        // Validation SANS identity_type et identity_number
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email', // PAS unique - permet plusieurs clients avec même email
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'nullable|string',
            'job' => 'nullable|string|max:100',
            'birthdate' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Rechercher un client avec le même email ET même nom (pour éviter les doublons exacts)
        $existingCustomer = Customer::where('email', $validated['email'])
                                    ->where('name', $validated['name'])
                                    ->first();
        
        if ($existingCustomer) {
            // Client existant - mettre à jour les informations
            $updateData = [
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'job' => $validated['job'],
                'birthdate' => $validated['birthdate'],
            ];
            
            // Gérer l'avatar si fourni
            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar si existe
                if ($existingCustomer->avatar && Storage::exists($existingCustomer->avatar)) {
                    Storage::delete($existingCustomer->avatar);
                }
                
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }
            
            $existingCustomer->update($updateData);
            $customer = $existingCustomer;
            
            // Compter les réservations existantes
            $reservationCount = $customer->transactions()->count();
            $message = 'Customer information updated: ' . $customer->name . ' (already has ' . $reservationCount . ' reservation(s))';
        } else {
            // Nouveau client (même email mais nom différent)
            $customerData = $validated;
            
            // Ajouter user_id (utilisateur connecté ou valeur par défaut)
            $customerData['user_id'] = auth()->id() ?? 1; // 1 pour admin par défaut si pas connecté
            
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $customerData['avatar'] = $avatarPath;
            }
            
            $customer = Customer::create($customerData);
            $message = 'New customer created: ' . $customer->name;
        }

        return redirect()
            ->route('transaction.reservation.viewCountPerson', ['customer' => $customer->id])
            ->with('success', $message);
    }

    /**
     * Afficher le formulaire pour saisir les dates de séjour
     */
    public function viewCountPerson(Customer $customer)
    {
        // Vérifier si le client a déjà des réservations
        $existingReservations = $customer->transactions()->count();
        
        return view('transaction.reservation.viewCountPerson', [
            'customer' => $customer,
            'existingReservations' => $existingReservations,
        ]);
    }

    /**
     * Choisir une chambre disponible
     */
    public function chooseRoom(ChooseRoomRequest $request, Customer $customer)
    {
        $stayFrom = $request->check_in;
        $stayUntil = $request->check_out;

        // Vérifier les chambres occupées
        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);

        // Récupérer les chambres disponibles
        $rooms = $this->reservationRepository->getUnocuppiedroom($request, $occupiedRoomId);
        $roomsCount = $this->reservationRepository->countUnocuppiedroom($request, $occupiedRoomId);

        // Vérifier si le client a déjà une réservation pendant cette période
        $hasExistingBooking = $customer->transactions()
            ->where(function($query) use ($stayFrom, $stayUntil) {
                $query->whereBetween('check_in', [$stayFrom, $stayUntil])
                      ->orWhereBetween('check_out', [$stayFrom, $stayUntil])
                      ->orWhere(function($q) use ($stayFrom, $stayUntil) {
                          $q->where('check_in', '<=', $stayFrom)
                            ->where('check_out', '>=', $stayUntil);
                      });
            })
            ->exists();

        return view('transaction.reservation.chooseRoom', [
            'customer' => $customer,
            'rooms' => $rooms,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'roomsCount' => $roomsCount,
            'hasExistingBooking' => $hasExistingBooking,
            'occupiedRoomIds' => $occupiedRoomId,
        ]);
    }

    /**
     * Afficher la confirmation de réservation
     */
    public function confirmation(Customer $customer, Room $room, $stayFrom, $stayUntil)
    {
        // Calculer le prix
        $price = $room->price;
        $dayDifference = Helper::getDateDifference($stayFrom, $stayUntil);
        $downPayment = ($price * $dayDifference) * 0.15;
        
        // Vérifier si c'est une réservation supplémentaire
        $existingReservationsCount = $customer->transactions()->count();

        return view('transaction.reservation.confirmation', [
            'customer' => $customer,
            'room' => $room,
            'stayFrom' => $stayFrom,
            'stayUntil' => $stayUntil,
            'downPayment' => $downPayment,
            'dayDifference' => $dayDifference,
            'existingReservationsCount' => $existingReservationsCount,
        ]);
    }

    public function payDownPayment(
        Customer $customer,
        Room $room,
        Request $request,
        TransactionRepositoryInterface $transactionRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        \Log::info('🔵 =========== PAYDOWNPAYMENT START ===========');
        \Log::info('🔵 Customer: ' . $customer->id . ' - ' . $customer->name);
        \Log::info('🔵 Room: ' . $room->id . ' - ' . $room->number . ' - Prix: ' . $room->price);
        \Log::info('🔵 Request data:', $request->all());
        \Log::info('🔵 Auth user: ' . (auth()->check() ? auth()->id() . ' - ' . auth()->user()->name : 'NOT LOGGED IN'));
        
        // ⭐ CORRECTION 1 : Ajouter person_count si manquant
        if (!$request->has('person_count') || empty($request->person_count)) {
            $request->merge(['person_count' => 1]);
            \Log::info('🔵 person_count manquant, fixé à 1');
        }
        
        // ⭐ CORRECTION 2 : Vérifier que les dates existent
        if (!$request->has('check_in') || !$request->has('check_out')) {
            \Log::error('❌ Dates manquantes dans la requête');
            return redirect()->back()
                ->with('error', 'Les dates de séjour sont requises.')
                ->withInput();
        }
        
        // Calculer la durée du séjour
        $dayDifference = Helper::getDateDifference($request->check_in, $request->check_out);
        $totalPrice = $room->price * $dayDifference;
        
        \Log::info('🔵 Calcul: ' . $dayDifference . ' jours, prix total: ' . $totalPrice);
        
        // Validation
        $validated = $request->validate([
            'downPayment' => 'nullable|numeric|min:0|max:' . $totalPrice,
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'person_count' => 'required|integer|min:1',
        ]);
        
        \Log::info('🔵 Validation réussie');
        
        // Vérifier si la chambre est déjà occupée
        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);
        $occupiedRoomIdInArray = $occupiedRoomId->toArray();
        
        if (in_array($room->id, $occupiedRoomIdInArray)) {
            \Log::warning('❌ Chambre déjà occupée: ' . $room->id);
            return redirect()->back()
                ->with('failed', 'Désolé, la chambre ' . $room->number . ' est déjà occupée pour les dates sélectionnées.')
                ->withInput();
        }
        
        // Vérifier si le client a déjà une réservation aux mêmes dates
        $hasConflict = $customer->transactions()
            ->where(function($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function($q) use ($request) {
                        $q->where('check_in', '<=', $request->check_in)
                            ->where('check_out', '>=', $request->check_out);
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();
        
        if ($hasConflict) {
            \Log::warning('❌ Conflit de dates pour le client: ' . $customer->id);
            return redirect()->back()
                ->with('warning', 'Vous avez déjà une réservation pendant ces dates.')
                ->withInput();
        }
        
        try {
            \Log::info('🔵 Création de la transaction...');
            
            // ⭐ CORRECTION 3 : Préparer les données pour debug
            $transactionData = [
                'customer_id' => $customer->id,
                'room_id' => $room->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'person_count' => $request->person_count,
                'days' => $dayDifference,
                'room_price' => $room->price,
                'total_price' => $totalPrice,
            ];
            
            \Log::info('🔵 Données de transaction:', $transactionData);
            
            // Créer la transaction
            $transaction = $transactionRepository->store($request, $customer, $room);
            
            \Log::info('✅ Transaction créée avec ID: ' . $transaction->id, [
                'transaction_id' => $transaction->id,
                'customer_id' => $transaction->customer_id,
                'room_id' => $transaction->room_id,
                'check_in' => $transaction->check_in,
                'check_out' => $transaction->check_out,
                'status' => $transaction->status,
                'total_price' => $transaction->total_price,
                'person_count' => $transaction->person_count,
            ]);
            
            // Gestion du paiement
            $downPayment = $request->downPayment ?? 0;
            
            if ($downPayment > 0) {
                $status = ($downPayment == $totalPrice) ? 'Full Payment' : 'Down Payment';
                \Log::info('🔵 Création paiement: ' . $status . ' - Montant: ' . $downPayment);
                
                $payment = $paymentRepository->store($request, $transaction, $status);
                
                \Log::info('✅ Paiement créé avec ID: ' . ($payment->id ?? 'N/A'), [
                    'payment_id' => $payment->id ?? null,
                    'amount' => $downPayment,
                    'payment_method' => $payment->payment_method ?? null,
                ]);
            }
            
            // Message de succès
            $successMessage = 'Chambre ' . $room->number . ' réservée pour ' . $customer->name;
            if ($downPayment > 0) {
                $successMessage .= ' avec acompte de ' . number_format($downPayment, 0, ',', ' ') . ' FCFA';
            }
            
            \Log::info('✅ Réservation réussie! Message: ' . $successMessage);
            
            // Vérifier les routes disponibles
            \Log::info('🔵 Vérification des routes...');
            \Log::info('Route dashboard.index: ' . (\Route::has('dashboard.index') ? 'EXISTE' : 'N\'EXISTE PAS'));
            \Log::info('Route home: ' . (\Route::has('home') ? 'EXISTE' : 'N\'EXISTE PAS'));
            \Log::info('Route dashboard: ' . (\Route::has('dashboard') ? 'EXISTE' : 'N\'EXISTE PAS'));
            
            // Redirection
            if (\Route::has('dashboard.index')) {
                $redirectTo = route('dashboard.index');
                \Log::info('🔵 Redirection vers dashboard.index: ' . $redirectTo);
            } elseif (\Route::has('dashboard')) {
                $redirectTo = route('dashboard');
                \Log::info('🔵 Redirection vers dashboard: ' . $redirectTo);
            } elseif (\Route::has('home')) {
                $redirectTo = route('home');
                \Log::info('🔵 Redirection vers home: ' . $redirectTo);
            } else {
                $redirectTo = '/dashboard';
                \Log::info('🔵 Redirection vers /dashboard (URL directe)');
            }
            
            // Déclencher les événements
            try {
                event(new NewReservationEvent($transaction));
                event(new RefreshDashboardEvent());
                \Log::info('✅ Événements déclenchés avec succès');
            } catch (\Exception $e) {
                \Log::warning('⚠️ Impossible de déclencher les événements: ' . $e->getMessage());
            }
            
            \Log::info('🔵 =========== PAYDOWNPAYMENT SUCCESS ===========');
            
            return redirect($redirectTo)
                ->with('success', $successMessage)
                ->with('transaction_id', $transaction->id);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Erreur de validation: ' . json_encode($e->errors()));
            throw $e;
            
        } catch (\Illuminate\Database\QueryException $qe) {
            \Log::error('❌ Erreur de base de données: ' . $qe->getMessage());
            \Log::error('❌ SQL: ' . $qe->getSql());
            \Log::error('❌ Bindings: ' . json_encode($qe->getBindings()));
            
            return redirect()->back()
                ->with('error', 'Erreur de base de données: ' . $qe->getMessage())
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('❌ Erreur de réservation: ' . $e->getMessage());
            \Log::error('❌ File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du traitement de la réservation: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Obtenir les IDs des chambres occupées pour une période donnée
     */
    private function getOccupiedRoomID($stayFrom, $stayUntil)
    {
        return Transaction::where(function($query) use ($stayFrom, $stayUntil) {
                $query->where([['check_in', '<=', $stayFrom], ['check_out', '>=', $stayUntil]])
                      ->orWhere([['check_in', '>=', $stayFrom], ['check_in', '<=', $stayUntil]])
                      ->orWhere([['check_out', '>=', $stayFrom], ['check_out', '<=', $stayUntil]]);
            })
            ->where('status', '!=', 'cancelled') // Exclure les réservations annulées
            ->pluck('room_id')
            ->unique();
    }

    /**
     * Rechercher un client par email (API pour AJAX)
     */
    public function searchByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Rechercher TOUS les clients avec cet email
        $customers = Customer::where('email', $request->email)->get();
        
        if ($customers->count() > 0) {
            // Calculer le total des réservations
            $totalReservations = 0;
            $customerDetails = [];
            
            foreach ($customers as $customer) {
                $reservationCount = $customer->transactions()->count();
                $totalReservations += $reservationCount;
                
                $customerDetails[] = [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'reservation_count' => $reservationCount,
                ];
            }
            
            return response()->json([
                'exists' => true,
                'customers_count' => $customers->count(),
                'total_reservations' => $totalReservations,
                'customers' => $customerDetails,
                'message' => 'Found ' . $customers->count() . ' customer(s) with this email'
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'No customer found with this email'
        ]);
    }

    /**
     * Afficher les réservations existantes d'un client
     */
    public function showCustomerReservations(Customer $customer)
    {
        $reservations = $customer->transactions()
            ->with(['room', 'room.type'])
            ->orderBy('check_in', 'desc')
            ->get();

        return view('transaction.reservation.customerReservations', [
            'customer' => $customer,
            'reservations' => $reservations,
        ]);
    }
}