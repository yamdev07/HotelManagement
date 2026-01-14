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
        // Récupérer les transactions ACTIVES (pas annulées, pas terminées)
        $transactions = $this->transactionRepository->getTransaction($request);
        
        // Récupérer les transactions EXPIRÉES ou ANCIENNES (incluant les annulées)
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
        // Récupérer les paiements
        try {
            $payments = $transaction->payment()->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            $payments = $transaction->payment()->orderBy('created_at', 'desc')->get();
        }
        
        // Calculer le nombre de nuits
        $checkIn = Carbon::parse($transaction->check_in);
        $checkOut = Carbon::parse($transaction->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        
        // Calculer les totaux
        $totalPrice = $transaction->getTotalPrice();
        $totalPayment = $transaction->getTotalPayment();
        $remaining = $totalPrice - $totalPayment;
        
        // Déterminer le statut (depuis la base de données)
        $status = $transaction->status;
        
        // Calculer les états pour information
        $checkOutDate = Carbon::parse($transaction->check_out);
        $checkInDate = Carbon::parse($transaction->check_in);
        $isExpired = $checkOutDate->isPast();
        $isFullyPaid = $remaining <= 0;
        
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
     * Mettre à jour le statut d'une transaction
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // Vérifier les permissions
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            abort(403, 'Accès non autorisé. Seuls le personnel peut changer les statuts.');
        }
        
        // Validation
        $request->validate([
            'status' => 'required|in:reservation,active,completed,cancelled,no_show',
            'cancel_reason' => 'nullable|string|max:500',
        ]);
        
        $oldStatus = $transaction->status;
        $newStatus = $request->status;
        
        try {
            DB::beginTransaction();
            
            // Préparer les données de mise à jour
            $updateData = ['status' => $newStatus];
            
            // Gérer l'annulation
            if ($newStatus === 'cancelled') {
                $updateData['cancelled_at'] = Carbon::now();
                $updateData['cancelled_by'] = auth()->id();
                $updateData['cancel_reason'] = $request->cancel_reason;
                
                // Libérer la chambre si elle est occupée
                if ($transaction->room && $transaction->room->room_status_id == 2) {
                    $transaction->room->update(['room_status_id' => 1]); // Available
                }
            } 
            // Si on réactive une réservation annulée
            elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $updateData['cancelled_at'] = null;
                $updateData['cancelled_by'] = null;
                $updateData['cancel_reason'] = null;
            }
            
            // Gérer le passage de réservation à actif (arrivée du client)
            if ($oldStatus === 'reservation' && $newStatus === 'active') {
                // Marquer la chambre comme occupée
                if ($transaction->room) {
                    $transaction->room->update(['room_status_id' => 2]); // Occupied
                }
            }
            
            // Gérer le passage d'actif à terminé (départ du client)
            if ($oldStatus === 'active' && $newStatus === 'completed') {
                // Libérer la chambre
                if ($transaction->room) {
                    $transaction->room->update(['room_status_id' => 1]); // Available
                }
            }
            
            // Mettre à jour la transaction
            $transaction->update($updateData);
            
            // Journaliser l'action
            \Log::info('Statut de réservation modifié', [
                'transaction_id' => $transaction->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->id(),
                'changed_by_name' => auth()->user()->name,
                'customer_id' => $transaction->customer_id,
                'room_id' => $transaction->room_id,
                'reason' => $request->cancel_reason,
            ]);
            
            DB::commit();
            
            // Réponse pour AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Statut mis à jour avec succès',
                    'new_status' => $newStatus,
                    'new_status_label' => $this->getStatusLabel($newStatus),
                    'transaction_id' => $transaction->id
                ]);
            }
            
            // Message personnalisé selon le changement
            $message = $this->getStatusChangeMessage($oldStatus, $newStatus);
            
            if ($newStatus === 'cancelled' && $request->cancel_reason) {
                $message .= " - Raison : " . $request->cancel_reason;
            }
            
            return redirect()->back()
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur mise à jour statut: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Erreur lors de la mise à jour'], 500);
            }
            
            return redirect()->back()
                ->with('failed', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le message de changement de statut
     */
    private function getStatusChangeMessage($oldStatus, $newStatus)
    {
        $messages = [
            'reservation' => [
                'active' => 'Client marqué comme arrivé à l\'hôtel',
                'cancelled' => 'Réservation annulée',
                'no_show' => 'Client marqué comme No Show',
            ],
            'active' => [
                'completed' => 'Client marqué comme parti (séjour terminé)',
                'cancelled' => 'Séjour annulé pendant le séjour',
                'reservation' => 'Retour à l\'état réservation',
            ],
            'completed' => [
                'active' => 'Séjour réactivé',
                'cancelled' => 'Séjour marqué comme annulé',
                'reservation' => 'Séjour changé en réservation',
            ],
            'cancelled' => [
                'active' => 'Réservation réactivée (client arrivé)',
                'reservation' => 'Réservation réactivée',
                'completed' => 'Réservation marquée comme terminée',
            ],
        ];
        
        return $messages[$oldStatus][$newStatus] 
            ?? "Statut changé de '{$this->getStatusLabel($oldStatus)}' à '{$this->getStatusLabel($newStatus)}'";
    }

    /**
     * Obtenir le label d'un statut
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'reservation' => 'Réservation',
            'active' => 'Dans l\'hôtel',
            'completed' => 'Séjour terminé',
            'cancelled' => 'Annulée',
            'no_show' => 'No Show',
        ];
        
        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Action rapide : Marquer comme arrivé
     */
    public function markAsArrived(Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])) {
            abort(403, 'Accès non autorisé');
        }
        
        try {
            // Vérifier que c'est bien une réservation
            if ($transaction->status !== 'reservation') {
                return redirect()->back()
                    ->with('failed', 'Seule une réservation peut être marquée comme arrivée');
            }
            
            DB::beginTransaction();
            
            // Marquer la chambre comme occupée
            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => 2]); // Occupied
            }
            
            // Mettre à jour le statut
            $transaction->update([
                'status' => 'active',
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Client marqué comme arrivé à l\'hôtel');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur marquage arrivée: ' . $e->getMessage());
            return redirect()->back()
                ->with('failed', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Action rapide : Marquer comme parti
     */
    public function markAsDeparted(Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])) {
            abort(403, 'Accès non autorisé');
        }
        
        try {
            // Vérifier que le client est dans l'hôtel
            if ($transaction->status !== 'active') {
                return redirect()->back()
                    ->with('failed', 'Seul un client dans l\'hôtel peut être marqué comme parti');
            }
            
            DB::beginTransaction();
            
            // Libérer la chambre
            if ($transaction->room) {
                $transaction->room->update(['room_status_id' => 1]); // Available
            }
            
            // Mettre à jour le statut
            $transaction->update([
                'status' => 'completed',
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Client marqué comme parti (séjour terminé)');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur marquage départ: ' . $e->getMessage());
            return redirect()->back()
                ->with('failed', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation (ancienne méthode - gardée pour compatibilité)
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            // Vérifier les permissions
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                return redirect()->route('transaction.index')
                    ->with('failed', 'Accès non autorisé. Seuls les administrateurs peuvent annuler des réservations.');
            }
            
            // Vérifier si la réservation peut être annulée
            if (!$this->canCancelReservation($transaction)) {
                return redirect()->route('transaction.index')
                    ->with('failed', 'Cette réservation ne peut pas être annulée.');
            }
            
            // Validation de la raison si fournie
            if ($request->has('cancel_reason') && strlen($request->cancel_reason) > 500) {
                return redirect()->route('transaction.index')
                    ->with('failed', 'La raison de l\'annulation ne doit pas dépasser 500 caractères.');
            }
            
            DB::beginTransaction();
            
            // Marquer la transaction comme annulée
            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => $request->cancel_reason ?? null,
            ]);
            
            // Libérer la chambre si occupée
            $room = $transaction->room;
            if ($room && $room->room_status_id == 2) { // Occupied
                $room->update(['room_status_id' => 1]); // Available
            }
            
            // Créer un remboursement si des paiements ont été effectués
            $totalPaid = $transaction->getTotalPayment();
            if ($totalPaid > 0) {
                $existingRefund = Payment::where('transaction_id', $transaction->id)
                    ->where('payment_method', 'refund')
                    ->first();
                
                if (!$existingRefund) {
                    Payment::create([
                        'transaction_id' => $transaction->id,
                        'price' => -$totalPaid,
                        'payment_method' => 'refund',
                        'reference' => 'REFUND-' . $transaction->id . '-' . time(),
                        'status' => 'completed',
                        'notes' => 'Remboursement suite à annulation' . 
                                ($request->cancel_reason ? " - Raison: " . $request->cancel_reason : ''),
                        'created_by' => auth()->id(),
                    ]);
                }
            }
            
            DB::commit();
            
            // Journalisation
            \Log::info('Réservation annulée', [
                'transaction_id' => $transaction->id,
                'cancelled_by' => auth()->id(),
                'customer_id' => $transaction->customer_id,
                'room_id' => $transaction->room_id,
                'total_refunded' => $totalPaid,
                'reason' => $request->cancel_reason,
            ]);
            
            $successMessage = "Réservation #{$transaction->id} annulée avec succès.";
            
            if ($request->cancel_reason) {
                $successMessage .= " Raison: " . $request->cancel_reason;
            }
            
            return redirect()->route('transaction.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de l\'annulation: ' . $e->getMessage());
            return redirect()->route('transaction.index')
                ->with('failed', 'Erreur lors de l\'annulation.');
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
     */
    public function invoice(Transaction $transaction)
    {
        $payments = $transaction->payment()->orderBy('created_at')->get();
        
        if ($payments->isEmpty()) {
            return redirect()->route('transaction.payment.create', $transaction)
                ->with('error', 'Aucun paiement trouvé. Veuillez d\'abord effectuer un paiement pour générer une facture.');
        }
        
        $lastPayment = $payments->last();
        
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
     * Restaurer une réservation annulée
     */
    public function restore(Transaction $transaction)
    {
        try {
            if (!in_array(auth()->user()->role, ['Super', 'Admin'])) {
                abort(403, 'Accès non autorisé');
            }
            
            if ($transaction->status != 'cancelled') {
                return redirect()->back()
                    ->with('failed', 'Cette réservation n\'est pas annulée.');
            }
            
            DB::beginTransaction();
            
            $transaction->update([
                'status' => 'reservation',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancel_reason' => null,
            ]);
            
            // Annuler le remboursement s'il existe
            Payment::where('transaction_id', $transaction->id)
                ->where('payment_method', 'refund')
                ->delete();
            
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
        
        return redirect()->route('transaction.index')
            ->with('info', 'Fonction d\'exportation à implémenter');
    }
}