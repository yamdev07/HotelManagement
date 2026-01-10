<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Payment;
use App\Repositories\Interface\TransactionRepositoryInterface;
use App\Helpers\Helper; // Ajout de l'import Helper
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

    /**
     * Afficher les détails d'une transaction
     */
    // public function show(Transaction $transaction)
    // {
    //     // Récupérer le paiement associé (au singulier - hasOne)
    //     $payment = $transaction->payment; // Pas besoin de () pour hasOne
        
    //     // Pour avoir une collection (même si c'est hasOne)
    //     $payments = $transaction->payment ? collect([$transaction->payment]) : collect();
        
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
    //         'payment' => $payment, // ← variable au singulier
    //         'payments' => $payments, // ← collection
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
        // Vérifier les permissions (Super/Admin seulement)
        if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
            abort(403, 'Accès non autorisé. Seuls les Super Admins et Admins peuvent modifier les réservations.');
        }
        
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired) {
            return redirect()->route('transaction.index')
                ->with('failed', 'Impossible de modifier une réservation expirée.');
        }
        
        // Charger les relations nécessaires pour la vue d'édition
        $transaction->load(['customer.user', 'room.type', 'room.roomStatus', 'payment']);
        
        // Retourner simplement la transaction
        return view('transaction.edit', compact('transaction'));
    }

    /**
     * Mettre à jour une transaction existante
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
            abort(403, 'Accès non autorisé');
        }
        
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired) {
            return redirect()->route('transaction.index')
                ->with('failed', 'Impossible de modifier une réservation expirée.');
        }
        
        // Validation - SEULEMENT les dates et notes
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'notes' => 'nullable|string|max:500',
        ], [
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
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        
        // Vérifier les conflits de réservation (sauf la transaction courante)
        $conflictingTransaction = Transaction::where('room_id', $transaction->room_id)
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
        
        // Sauvegarder les anciennes valeurs pour le message de confirmation
        $oldCheckIn = $transaction->check_in;
        $oldCheckOut = $transaction->check_out;
        $oldPrice = $transaction->getTotalPrice();
        
        // Mettre à jour UNIQUEMENT les dates et notes
        $transaction->update([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'notes' => $request->notes ?? $transaction->notes,
        ]);
        
        // Calculer le nouveau prix
        $newPrice = $transaction->getTotalPrice();
        
        // Préparer le message de succès
        $message = "Réservation #{$transaction->id} mise à jour avec succès.";
        
        if ($oldCheckIn != $checkIn || $oldCheckOut != $checkOut) {
            $message .= " Dates modifiées de " . 
                       Carbon::parse($oldCheckIn)->format('d/m/Y') . " - " . 
                       Carbon::parse($oldCheckOut)->format('d/m/Y') . " à " . 
                       Carbon::parse($checkIn)->format('d/m/Y') . " - " . 
                       Carbon::parse($checkOut)->format('d/m/Y') . ".";
            
            if ($oldPrice != $newPrice) {
                // Utiliser Helper si disponible, sinon formater manuellement
                $oldPriceFormatted = class_exists('App\Helpers\Helper') ? 
                    Helper::formatCFA($oldPrice) : 
                    number_format($oldPrice, 0, ',', ' ') . ' CFA';
                    
                $newPriceFormatted = class_exists('App\Helpers\Helper') ? 
                    Helper::formatCFA($newPrice) : 
                    number_format($newPrice, 0, ',', ' ') . ' CFA';
                    
                $message .= " Nouveau total: " . $newPriceFormatted . 
                            " (ancien: " . $oldPriceFormatted . ")";
            }
        }
        
        return redirect()->route('transaction.index')
            ->with('success', $message);
    }

    /**
     * Supprimer une transaction
     */
    public function destroy(Transaction $transaction)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                abort(403, 'Accès non autorisé');
            }
            
            $transactionId = $transaction->id;
            $roomId = $transaction->room_id;
            $customerName = $transaction->customer->name;
            
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
                ->with('success', "Réservation #{$transactionId} pour {$customerName} supprimée avec succès !");
                
        } catch (\Exception $e) {
            \Log::error('Erreur suppression transaction: ' . $e->getMessage());
            return redirect()->route('transaction.index')
                ->with('failed', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation
     */
    public function cancel(Transaction $transaction)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                abort(403, 'Accès non autorisé');
            }
            
            // Vérifier si la transaction peut être annulée
            $checkInDate = Carbon::parse($transaction->check_in);
            if ($checkInDate->isPast()) {
                return redirect()->route('transaction.index')
                    ->with('failed', 'Impossible d\'annuler une réservation déjà commencée.');
            }
            
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
        // Récupérer le premier paiement ou créer une facture à partir de la transaction
        $payment = $transaction->payments()->first();
        
        if ($payment) {
            return redirect()->route('payment.invoice', ['payment' => $payment]);
        } else {
            // Si aucun paiement, afficher une facture vierge
            $totalPrice = $transaction->getTotalPrice();
            $customer = $transaction->customer;
            $room = $transaction->room;
            
            return view('transaction.invoice', [
                'transaction' => $transaction,
                'totalPrice' => $totalPrice,
                'customer' => $customer,
                'room' => $room,
            ]);
        }
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
                ->with(['room', 'room.type', 'room.roomStatus', 'payments'])
                ->orderBy('check_in', 'desc')
                ->paginate(10);
                
            $transactionsExpired = Transaction::where('customer_id', $customer->id)
                ->where('check_out', '<', Carbon::now())
                ->with(['room', 'room.type', 'room.roomStatus', 'payments'])
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

    /**
     * Afficher les détails d'une transaction en format compact
     */
    public function showDetails(Request $request, $id)
    {
        $transaction = Transaction::with(['customer.user', 'room.type', 'payments'])
            ->findOrFail($id);
            
        return view('transaction.details-modal', compact('transaction'));
    }

    /**
     * Vérifier la disponibilité d'une chambre pour modification
     */
    public function checkAvailability(Request $request, Transaction $transaction)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);
        
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        
        // Vérifier les conflits de réservation
        $conflictingTransaction = Transaction::where('room_id', $transaction->room_id)
            ->where('id', '!=', $transaction->id)
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in', '<', $checkIn)
                            ->where('check_out', '>', $checkOut);
                      });
            })
            ->exists();
        
        return response()->json([
            'available' => !$conflictingTransaction,
            'message' => $conflictingTransaction ? 
                'Chambre non disponible pour ces dates' : 
                'Chambre disponible'
        ]);
    }

    /**
     * Mettre à jour le statut d'une transaction
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $request->validate([
            'status' => 'required|in:active,cancelled,completed',
        ]);
        
        // Vérifier si le statut peut être changé
        if ($request->status == 'cancelled') {
            $checkInDate = Carbon::parse($transaction->check_in);
            if ($checkInDate->isPast()) {
                return redirect()->back()
                    ->with('failed', 'Impossible d\'annuler une réservation déjà commencée.');
            }
            
            // Libérer la chambre si annulation
            $room = $transaction->room;
            if ($room) {
                $room->update(['room_status_id' => 1]); // Available
            }
        }
        
        $transaction->update(['status' => $request->status]);
        
        return redirect()->back()
            ->with('success', 'Statut de la réservation mis à jour avec succès.');
    }
}