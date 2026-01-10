<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Payment;
use App\Repositories\Interface\TransactionRepositoryInterface;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        return redirect()->route('transaction.index');
    }

    /**
     * Afficher les détails d'une transaction
     */
    public function show(Transaction $transaction)
    {
        // Récupérer les paiements (corriger la relation)
        try {
            $payments = $transaction->payment()->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            // Si la relation s'appelle 'payment' au singulier
            $payments = $transaction->payment()->orderBy('created_at', 'desc')->get();
        }
        
        // Calculer le nombre de nuits
        $checkIn = \Carbon\Carbon::parse($transaction->check_in);
        $checkOut = \Carbon\Carbon::parse($transaction->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Calculer les totaux
        $totalPrice = $transaction->getTotalPrice();
        $totalPayment = $transaction->getTotalPayment();
        $remaining = $totalPrice - $totalPayment;
        
        // Déterminer le statut
        $checkOutDate = \Carbon\Carbon::parse($transaction->check_out);
        $checkInDate = \Carbon\Carbon::parse($transaction->check_in);
        $isExpired = $checkOutDate->isPast();
        $isFullyPaid = $remaining <= 0;
        
        // Utiliser le statut de la transaction s'il existe, sinon calculer
        $status = $transaction->status ?? ($isFullyPaid ? 'paid' : ($isExpired ? 'expired' : 'active'));
        
        // Vérifier si la réservation peut être annulée
        $canCancel = $this->canCancelReservation($transaction);
        
        return view('transaction.show', [
            'transaction' => $transaction,
            'payments' => $payments,
            'nights' => $nights,
            'totalPrice' => $totalPrice,
            'totalPayment' => $totalPayment,
            'remaining' => $remaining,
            'isExpired' => $isExpired,
            'isFullyPaid' => $isFullyPaid,
            'status' => $status,
            'canCancel' => $canCancel
        ]);
    }

    /**
     * Afficher le formulaire d'édition d'une transaction
     */
    public function edit(Transaction $transaction)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
            abort(403, 'Accès non autorisé. Seuls les Super Admins et Admins peuvent modifier les réservations.');
        }
        
        // Vérifier si la transaction peut être modifiée
        $checkOutDate = Carbon::parse($transaction->check_out);
        $isExpired = $checkOutDate->isPast();
        
        if ($isExpired || $transaction->status == 'cancelled') {
            return redirect()->route('transaction.show', $transaction)
                ->with('failed', 'Impossible de modifier une réservation expirée ou annulée.');
        }
        
        // Charger les relations nécessaires
        $transaction->load(['customer.user', 'room.type', 'room.roomStatus']);
        
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
        
        if ($isExpired || $transaction->status == 'cancelled') {
            return redirect()->route('transaction.show', $transaction)
                ->with('failed', 'Impossible de modifier une réservation expirée ou annulée.');
        }
        
        // Validation
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
        
        // Vérifier les conflits de réservation
        $conflictingTransaction = Transaction::where('room_id', $transaction->room_id)
            ->where('id', '!=', $transaction->id)
            ->where('status', '!=', 'cancelled')
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
        
        // Sauvegarder les anciennes valeurs
        $oldCheckIn = $transaction->check_in;
        $oldCheckOut = $transaction->check_out;
        $oldPrice = $transaction->getTotalPrice();
        
        // Mettre à jour
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
        
        return redirect()->route('transaction.show', $transaction)
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
            
            DB::beginTransaction();
            
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
            
            DB::commit();
            
            return redirect()->route('transaction.index')
                ->with('success', "Réservation #{$transactionId} pour {$customerName} supprimée avec succès !");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur suppression transaction: ' . $e->getMessage());
            return redirect()->route('transaction.index')
                ->with('failed', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                return redirect()->route('transaction.show', $transaction)
                    ->with('failed', 'Accès non autorisé. Seuls les administrateurs peuvent annuler des réservations.');
            }
            
            // Vérifier si la réservation peut être annulée
            if (!$this->canCancelReservation($transaction)) {
                return redirect()->route('transaction.show', $transaction)
                    ->with('failed', 'Cette réservation ne peut pas être annulée.');
            }
            
            DB::beginTransaction();
            
            // Marquer la transaction comme annulée
            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $request->cancel_reason ?? null,
            ]);
            
            // Libérer la chambre
            $room = $transaction->room;
            if ($room) {
                // Vérifier s'il y a d'autres réservations actives pour cette chambre
                $hasOtherActiveReservations = Transaction::where('room_id', $room->id)
                    ->where('id', '!=', $transaction->id)
                    ->where('status', '!=', 'cancelled')
                    ->where(function($query) {
                        $query->whereNull('check_out')
                            ->orWhere('check_out', '>', Carbon::now());
                    })
                    ->exists();
                
                if (!$hasOtherActiveReservations && $room->room_status_id == 2) { // Occupied
                    $room->update(['room_status_id' => 1]); // Available
                }
            }
            
            // Optionnel: Créer un remboursement si des paiements ont été effectués
            $totalPaid = $transaction->getTotalPayment();
            if ($totalPaid > 0) {
                Payment::create([
                    'transaction_id' => $transaction->id,
                    'amount' => -$totalPaid,
                    'payment_method' => 'refund',
                    'reference' => 'REFUND-' . $transaction->id . '-' . time(),
                    'status' => 'completed',
                    'notes' => 'Remboursement suite à annulation' . 
                              ($request->cancel_reason ? " - Raison: " . $request->cancel_reason : ''),
                    'created_by' => auth()->id(),
                ]);
            }
            
            DB::commit();
            
            // Journalisation de l'action
            \Log::info('Réservation annulée', [
                'transaction_id' => $transaction->id,
                'cancelled_by' => auth()->id(),
                'customer_id' => $transaction->customer_id,
                'room_id' => $transaction->room_id,
                'total_refunded' => $totalPaid,
                'reason' => $request->cancel_reason
            ]);
            
            return redirect()->route('transaction.show', $transaction)
                ->with('success', 'Réservation #' . $transaction->id . ' annulée avec succès' . 
                       ($totalPaid > 0 ? ' (remboursement effectué)' : ''));
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de l\'annulation de la réservation: ' . $e->getMessage());
            
            return redirect()->route('transaction.show', $transaction)
                ->with('failed', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si une réservation peut être annulée
     */
    private function canCancelReservation(Transaction $transaction)
    {
        // Vérifier si déjà annulée
        if ($transaction->status == 'cancelled') {
            return false;
        }
        
        // Vérifier la date d'arrivée
        $checkInDate = Carbon::parse($transaction->check_in);
        $now = Carbon::now();
        
        // Règle: Pas d'annulation si la date d'arrivée est passée
        if ($checkInDate->isPast()) {
            return false;
        }
        
        // Règle: Pas d'annulation moins de 2 heures avant l'arrivée
        $hoursBeforeCheckIn = $now->diffInHours($checkInDate, false);
        
        if ($hoursBeforeCheckIn < 2 && $hoursBeforeCheckIn > 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Générer une facture pour une transaction
     * Redirige vers la facture du paiement
     */
    public function invoice(Transaction $transaction)
    {
        // Récupérer tous les paiements
        $payments = $transaction->payment()->orderBy('created_at')->get();
        
        // Si aucun paiement, rediriger vers la création de paiement
        if ($payments->isEmpty()) {
            return redirect()->route('transaction.payment.create', $transaction)
                ->with('error', 'Aucun paiement trouvé. Veuillez d\'abord effectuer un paiement pour générer une facture.');
        }
        
        // Prendre le dernier paiement pour afficher sa facture
        $lastPayment = $payments->last();
        
        // Rediriger vers la facture du paiement
        return redirect()->route('payment.invoice', $lastPayment->id);
    }

    /**
     * Afficher l'historique des modifications d'une transaction
     */
    public function history(Transaction $transaction)
    {
        return view('transaction.history', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Afficher les réservations d'un client
     */
    public function myReservations(Request $request)
    {
        // Si l'utilisateur est un client
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
            // Pour les admins
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
            ->where('status', '!=', 'cancelled')
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

    /**
     * Restaurer une réservation annulée
     */
    public function restore(Transaction $transaction)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                abort(403, 'Accès non autorisé');
            }
            
            // Vérifier si la transaction est annulée
            if ($transaction->status != 'cancelled') {
                return redirect()->back()
                    ->with('failed', 'Cette réservation n\'est pas annulée.');
            }
            
            DB::beginTransaction();
            
            // Restaurer la transaction
            $transaction->update([
                'status' => 'active',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancellation_reason' => null,
            ]);
            
            // Annuler le remboursement s'il existe
            Payment::where('transaction_id', $transaction->id)
                ->where('payment_method', 'refund')
                ->delete();
            
            // Marquer la chambre comme occupée si la réservation est en cours
            $room = $transaction->room;
            $checkIn = Carbon::parse($transaction->check_in);
            $checkOut = Carbon::parse($transaction->check_out);
            $now = Carbon::now();
            
            if ($room && $checkIn <= $now && $checkOut > $now) {
                $room->update(['room_status_id' => 2]); // Occupied
            }
            
            DB::commit();
            
            return redirect()->route('transaction.show', $transaction)
                ->with('success', 'Réservation #' . $transaction->id . ' restaurée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la restauration: ' . $e->getMessage());
            return redirect()->back()
                ->with('failed', 'Erreur lors de la restauration : ' . $e->getMessage());
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
        return redirect()->route('transaction.index')
            ->with('info', 'Fonction d\'exportation à implémenter');
    }
}