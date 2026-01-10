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

    /**
     * Payer l'acompte et finaliser la réservation
     */
    /**
 * Finaliser la réservation avec ou sans acompte
 */
    /**
 * Finaliser la réservation avec ou sans acompte
 */
    public function payDownPayment(
        Customer $customer,
        Room $room,
        Request $request,
        TransactionRepositoryInterface $transactionRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        // Calculer la durée du séjour
        $dayDifference = Helper::getDateDifference($request->check_in, $request->check_out);
        $totalPrice = $room->price * $dayDifference;
        
        // Validation - downPayment peut être 0 pour réservation sans acompte
        $request->validate([
            'downPayment' => 'nullable|numeric|min:0|max:' . $totalPrice,
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Vérifier si la chambre est déjà occupée
        $occupiedRoomId = $this->getOccupiedRoomID($request->check_in, $request->check_out);
        $occupiedRoomIdInArray = $occupiedRoomId->toArray();

        if (in_array($room->id, $occupiedRoomIdInArray)) {
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
            ->exists();

        if ($hasConflict) {
            return redirect()->back()
                ->with('warning', 'Vous avez déjà une réservation pendant ces dates.')
                ->withInput();
        }

        try {
            // Créer la transaction
            $transaction = $transactionRepository->store($request, $customer, $room);
            
            // Récupérer le montant de l'acompte (peut être 0)
            $downPayment = $request->downPayment ?? 0;
            
            // Créer un paiement SEULEMENT si downPayment > 0
            if ($downPayment > 0) {
                $status = ($downPayment == $totalPrice) ? 'Full Payment' : 'Down Payment';
                $payment = $paymentRepository->store($request, $transaction, $status);
            }

            // Notifier les administrateurs
            $superAdmins = User::where('role', 'Super')->get();
            
            // Calculer le nombre total de réservations après l'ajout
            $totalReservations = $customer->transactions()->count();
            
            // Message personnalisé selon le type de paiement
            if ($downPayment == 0) {
                $notificationMessage = 'Nouvelle réservation sans acompte par ' . $customer->name;
            } elseif ($downPayment == $totalPrice) {
                $notificationMessage = 'Réservation entièrement payée par ' . $customer->name;
            } else {
                $notificationMessage = 'Nouvelle réservation avec acompte de ' . number_format($downPayment, 0, ',', ' ') . ' FCFA par ' . $customer->name;
            }
            $notificationMessage .= ' (Client a maintenant ' . $totalReservations . ' réservation(s))';

            foreach ($superAdmins as $superAdmin) {
                // Gestion des événements avec try-catch
                try {
                    event(new NewReservationEvent($notificationMessage, $superAdmin));
                } catch (\Illuminate\Broadcasting\BroadcastException $e) {
                    Log::warning('Erreur de diffusion pour la réservation: ' . $e->getMessage());
                }
                
                // Notification par email seulement si paiement effectué
                if ($downPayment > 0 && isset($payment)) {
                    $superAdmin->notify(new NewRoomReservationDownPayment($transaction, $payment));
                }
            }

            // Événement de rafraîchissement du dashboard
            try {
                event(new RefreshDashboardEvent('Nouvelle réservation ajoutée'));
            } catch (\Illuminate\Broadcasting\BroadcastException $e) {
                Log::warning('Erreur de diffusion RefreshDashboardEvent: ' . $e->getMessage());
            }

            // Message de succès personnalisé
            if ($downPayment == 0) {
                $successMessage = 'Chambre ' . $room->number . ' réservée pour ' . $customer->name . ' (sans acompte)';
            } elseif ($downPayment == $totalPrice) {
                $successMessage = 'Chambre ' . $room->number . ' entièrement payée et réservée pour ' . $customer->name;
            } else {
                $successMessage = 'Chambre ' . $room->number . ' réservée pour ' . $customer->name . ' avec un acompte de ' . number_format($downPayment, 0, ',', ' ') . ' FCFA';
            }
            
            // Ajouter une note si c'est une réservation supplémentaire
            if ($totalReservations > 1) {
                $successMessage .= ' (Client a maintenant ' . $totalReservations . ' réservation(s))';
            }

            // REDIRECTION VERS LE DASHBOARD
            return redirect()->route('dashboard.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Erreur de réservation: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du traitement de la réservation. Veuillez réessayer.')
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