<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Payment;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    /**
     * Afficher la liste des transactions
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionRepository->getTransaction($request);
        $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);

        return view('transaction.index', [
            'transactions' => $transactions,
            'transactionsExpired' => $transactionsExpired,
        ]);
    }

    /**
     * Afficher le formulaire de création d'une transaction
     */
    public function create()
    {
        // Cette méthode est gérée par TransactionRoomReservationController
        return redirect()->route('transaction.reservation.createIdentity');
    }

    /**
     * Enregistrer une nouvelle transaction
     */
    public function store(Request $request)
    {
        // La création est gérée par TransactionRoomReservationController
        // via les étapes: createIdentity → pickFromCustomer → storeCustomer → etc.
        return redirect()->route('transaction.index');
    }

    // /**
    //  * Afficher les détails d'une transaction
    //  */
    // public function show(Transaction $transaction)
    // {
    //     // Récupérer les paiements associés
    //     $payments = $transaction->payments()->orderBy('created_at', 'desc')->get();
        
    //     // Calculer les totaux
    //     $totalPrice = $transaction->getTotalPrice();
    //     $totalPayment = $transaction->getTotalPayment();
    //     $remaining = $totalPrice - $totalPayment;
        
    //     // Déterminer le statut
    //     $checkOutDate = Carbon::parse($transaction->check_out);
    //     $isExpired = $checkOutDate->isPast();
    //     $isFullyPaid = $remaining <= 0;
        
    //     $status = $isFullyPaid ? 'paid' : ($isExpired ? 'expired' : 'active');

    //     return view('transaction.show', [
    //         'transaction' => $transaction,
    //         'payments' => $payments,
    //         'totalPrice' => $totalPrice,
    //         'totalPayment' => $totalPayment,
    //         'remaining' => $remaining,
    //         'status' => $status,
    //         'customer' => $transaction->customer,
    //         'room' => $transaction->room,
    //     ]);
    // }

    /**
     * Afficher le formulaire d'édition d'une transaction
     */
    public function edit(Transaction $transaction)
    {
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired) {
            return redirect()->route('transaction.index')
                ->with('failed', 'Impossible de modifier une réservation expirée.');
        }
        
        // Récupérer les chambres disponibles
        $rooms = Room::where('room_status_id', 1)->get(); // Chambres avec statut "Available"
        
        // Récupérer tous les clients
        $customers = Customer::with('user')->get();
        
        return view('transaction.edit', [
            'transaction' => $transaction,
            'rooms' => $rooms,
            'customers' => $customers,
        ]);
    }

    /**
     * Mettre à jour une transaction existante
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired) {
            return redirect()->route('transaction.index')
                ->with('failed', 'Impossible de modifier une réservation expirée.');
        }
        
        // Validation
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ], [
            'customer_id.required' => 'Veuillez sélectionner un client',
            'customer_id.exists' => 'Le client sélectionné n\'existe pas',
            'room_id.required' => 'Veuillez sélectionner une chambre',
            'room_id.exists' => 'La chambre sélectionnée n\'existe pas',
            'check_in.required' => 'La date d\'arrivée est requise',
            'check_in.date' => 'La date d\'arrivée doit être une date valide',
            'check_out.required' => 'La date de départ est requise',
            'check_out.date' => 'La date de départ doit être une date valide',
            'check_out.after' => 'La date de départ doit être après la date d\'arrivée',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Vérifier si la chambre est disponible pour les nouvelles dates
        $roomId = $request->room_id;
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        
        // Vérifier les conflits de réservation (sauf la transaction courante)
        $conflictingTransaction = Transaction::where('room_id', $roomId)
            ->where('id', '!=', $transaction->id)
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in', '<', $checkIn)
                            ->where('check_out', '>', $checkOut);
                      });
            })
            ->first();
        
        if ($conflictingTransaction) {
            return redirect()->back()
                ->with('failed', 'Cette chambre est déjà réservée pour les dates sélectionnées.')
                ->withInput();
        }
        
        // Mettre à jour la transaction
        $transaction->update($validator->validated());
        
        // Mettre à jour le statut de la chambre si nécessaire
        $room = Room::find($roomId);
        if ($room && $room->room_status_id == 1) { // Si disponible
            $room->update(['room_status_id' => 2]); // Passer à "Occupied"
        }
        
        // Si l'ancienne chambre est différente, la marquer comme disponible
        if ($transaction->room_id != $roomId) {
            $oldRoom = Room::find($transaction->room_id);
            if ($oldRoom) {
                $oldRoom->update(['room_status_id' => 1]); // "Available"
            }
        }
        
        return redirect()->route('transaction.index')
            ->with('success', 'Réservation #' . $transaction->id . ' modifiée avec succès !');
    }

    /**
     * Supprimer une transaction
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $transactionId = $transaction->id;
            $roomId = $transaction->room_id;
            
            // Supprimer tous les paiements associés
            Payment::where('transaction_id', $transaction->id)->delete();
            
            // Supprimer la transaction
            $transaction->delete();
            
            // Si la chambre est occupée par cette transaction, la marquer comme disponible
            $room = Room::find($roomId);
            if ($room && $room->room_status_id == 2) { // Occupied
                // Vérifier s'il y a d'autres transactions pour cette chambre
                $otherTransactions = Transaction::where('room_id', $roomId)
                    ->where('check_out', '>', Carbon::now())
                    ->exists();
                
                if (!$otherTransactions) {
                    $room->update(['room_status_id' => 1]); // Available
                }
            }
            
            return redirect()->route('transaction.index')
                ->with('success', 'Réservation #' . $transactionId . ' supprimée avec succès !');
                
        } catch (\Exception $e) {
            \Log::error('Erreur suppression transaction: ' . $e->getMessage());
            return redirect()->route('transaction.index')
                ->with('failed', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation (soft delete optionnel)
     */
    public function cancel(Transaction $transaction)
    {
        try {
            // Marquer comme annulée (si vous avez un champ status)
            if (in_array('status', $transaction->getFillable())) {
                $transaction->update(['status' => 'cancelled']);
                
                // Libérer la chambre
                $room = $transaction->room;
                if ($room) {
                    $room->update(['room_status_id' => 1]); // Available
                }
                
                return redirect()->route('transaction.index')
                    ->with('success', 'Réservation #' . $transaction->id . ' annulée avec succès !');
            } else {
                // Sinon, supprimer complètement
                return $this->destroy($transaction);
            }
                
        } catch (\Exception $e) {
            return redirect()->route('transaction.index')
                ->with('failed', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Exporter les transactions (PDF/Excel)
     */
    public function export(Request $request, $type = 'pdf')
    {
        $transactions = $this->transactionRepository->getTransaction($request);
        $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);
        
        // Logique d'exportation ici
        // Vous pouvez utiliser Laravel Excel ou DomPDF
        
        return redirect()->route('transaction.index')
            ->with('info', 'Fonction d\'exportation à implémenter');
    }

    /**
     * Générer une facture pour une transaction
     */
    public function invoice(Transaction $transaction)
    {
        // Cette méthode peut rediriger vers PaymentController::invoice
        // ou gérer directement la génération de facture
        
        return redirect()->route('payment.invoice', ['payment' => $transaction->payments()->first()])
            ->with('info', 'Redirection vers la facture');
    }

    /**
     * Afficher l'historique des modifications d'une transaction
     */
    public function history(Transaction $transaction)
    {
        // Si vous utilisez un package d'audit comme spatie/laravel-activitylog
        // $activities = Activity::where('subject_id', $transaction->id)
        //     ->where('subject_type', Transaction::class)
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        
        return view('transaction.history', [
            'transaction' => $transaction,
            // 'activities' => $activities ?? collect(),
        ]);
    }

    /**
 * Afficher les réservations d'un client (pour les clients)
 */
    public function myReservations(Request $request)
    {
        // Si l'utilisateur est un client, ne montrer que ses réservations
        if (auth()->user()->role === 'Customer') {
            $customer = Customer::where('user_id', auth()->id())->first();
            
            if (!$customer) {
                return redirect()->route('dashboard.index')
                    ->with('failed', 'Profil client non trouvé.');
            }
            
            $transactions = Transaction::where('customer_id', $customer->id)
                ->with(['room', 'room.type', 'room.roomStatus'])
                ->orderBy('check_in', 'desc')
                ->paginate(10);
                
            $transactionsExpired = Transaction::where('customer_id', $customer->id)
                ->where('check_out', '<', Carbon::now())
                ->with(['room', 'room.type', 'room.roomStatus'])
                ->orderBy('check_out', 'desc')
                ->paginate(10);
        } else {
            // Pour les admins, utiliser le repository
            $transactions = $this->transactionRepository->getTransaction($request);
            $transactionsExpired = $this->transactionRepository->getTransactionExpired($request);
        }
        
        return view('transaction.my-reservations', [
            'transactions' => $transactions,
            'transactionsExpired' => $transactionsExpired,
            'isCustomer' => auth()->user()->role === 'Customer',
        ]);
    }

}