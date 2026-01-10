<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\Interface\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Afficher la liste des paiements avec filtres
     */
    public function index(Request $request)
    {
        $query = Payment::with(['transaction.customer', 'transaction.room.type', 'user', 'cancelledByUser'])
            ->orderBy('id', 'DESC');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            switch ($request->type) {
                case 'active': 
                    $query->whereIn('status', ['pending', 'completed']);
                    break;
                case 'cancelled': 
                    $query->where('status', 'cancelled');
                    break;
                case 'expired': 
                    $query->where('status', 'expired');
                    break;
                case 'completed': 
                    $query->where('status', 'completed');
                    break;
                case 'pending': 
                    $query->where('status', 'pending');
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%")
                  ->orWhereHas('transaction.customer', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('transaction.room', function($q) use ($search) {
                      $q->where('number', 'LIKE', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Payment::count(),
            'active' => Payment::whereIn('status', ['pending', 'completed'])->count(),
            'cancelled' => Payment::where('status', 'cancelled')->count(),
            'expired' => Payment::where('status', 'expired')->count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
        ];

        return view('payment.index', [
            'payments' => $payments,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher le formulaire de création de paiement
     */
    public function create(Transaction $transaction)
    {
        return view('transaction.payment.create', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Enregistrer un nouveau paiement
     */
    public function store(Transaction $transaction, Request $request)
    {
        $insufficient = $transaction->getTotalPrice() - $transaction->getTotalPayment();
        
        $request->validate([
            'payment' => 'required|numeric|lte:'.$insufficient,
            'payment_method' => 'required|in:cash,card,transfer,mobile_money',
            'notes' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Créer le paiement via le repository
            $payment = $this->paymentRepository->store($request, $transaction, 'Payment');
            
            // Mettre à jour le statut du paiement
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'reference' => $request->reference,
                ]);
            }

            DB::commit();

            return redirect()->route('transaction.index')
                ->with('success', 'Paiement de ' . Helpers::convertToRupiah($request->payment) . ' enregistré avec succès pour la chambre ' . $transaction->room->number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement du paiement : ' . $e->getMessage());
        }
    }

    /**
     * Annuler un paiement
     */
    public function cancel(Request $request, Payment $payment)
    {
        $request->validate([
            'cancel_reason' => 'nullable|string|max:500'
        ]);

        // Vérifier si le paiement peut être annulé
        if ($payment->status === 'cancelled') {
            return redirect()->back()->with('error', 'Ce paiement est déjà annulé.');
        }

        if ($payment->status === 'expired') {
            return redirect()->back()->with('error', 'Ce paiement est expiré.');
        }

        try {
            DB::beginTransaction();

            // Mettre à jour le statut du paiement
            $payment->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => $request->cancel_reason
            ]);

            // Recalculer le total payé pour la transaction
            $transaction = $payment->transaction;
            if ($transaction) {
                $transaction->updatePaymentStatus();
            }

            // Log d'activité
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties(['cancel_reason' => $request->cancel_reason])
                ->log('Paiement annulé');

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement annulé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un paiement annulé/expiré
     */
    public function restore(Payment $payment)
    {
        // Vérifier si le paiement peut être restauré
        if (!in_array($payment->status, ['cancelled', 'expired'])) {
            return redirect()->back()->with('error', 'Seuls les paiements annulés ou expirés peuvent être restaurés.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $payment->status;

            $payment->update([
                'status' => 'completed',
                'cancelled_at' => null,
                'cancelled_by' => null,
                'cancel_reason' => null
            ]);

            // Recalculer le total payé
            $transaction = $payment->transaction;
            if ($transaction) {
                $transaction->updatePaymentStatus();
            }

            // Log d'activité
            activity()
                ->performedOn($payment)
                ->causedBy(auth()->user())
                ->withProperties(['old_status' => $oldStatus])
                ->log('Paiement restauré');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Paiement restauré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    /**
     * Marquer un paiement comme expiré (API)
     */
    public function markAsExpired(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les paiements en attente peuvent être marqués comme expirés.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'expired',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancel_reason' => 'Paiement expiré automatiquement'
            ]);

            // Recalculer le total payé
            $transaction = $payment->transaction;
            if ($transaction) {
                $transaction->updatePaymentStatus();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paiement marqué comme expiré.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer une facture/reçu pour un paiement
     */
    public function invoice(Payment $payment)
    {
        try {
            // Charger toutes les relations nécessaires pour la facture
            $payment->load([
                'transaction' => function($query) {
                    $query->with([
                        'customer',
                        'room.type',
                        'payments' => function($q) {
                            $q->orderBy('created_at', 'asc');
                        }
                    ]);
                },
                'user',
                'cancelledByUser'
            ]);
            
            // Vérifier que toutes les relations existent
            if (!$payment->transaction) {
                return redirect()->back()->with('error', 'Transaction non trouvée pour ce paiement.');
            }
            
            if (!$payment->transaction->customer) {
                return redirect()->back()->with('error', 'Client non trouvé pour cette transaction.');
            }
            
            if (!$payment->transaction->room) {
                return redirect()->back()->with('error', 'Chambre non trouvée pour cette transaction.');
            }
            
            // Calculer les totaux
            $totalPrice = $payment->transaction->getTotalPrice();
            $totalPayment = $payment->transaction->getTotalPayment();
            $remaining = $totalPrice - $totalPayment;
            
            return view('payment.invoice', [
                'payment' => $payment,
                'totalPrice' => $totalPrice,
                'totalPayment' => $totalPayment,
                'remaining' => $remaining
            ]);
            
        } catch (\Exception $e) {
            // Log l'erreur pour debugging
            \Log::error('Erreur génération facture: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture : ' . $e->getMessage());
        }
    }

    /**
     * Marquer automatiquement les paiements en attente comme expirés
     * (À exécuter via une tâche cron)
     */
    public function expirePendingPayments()
    {
        try {
            // Récupérer les paiements en attente depuis plus de 24h
            $expiredPayments = Payment::where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24))
                ->get();

            $count = 0;
            
            foreach ($expiredPayments as $payment) {
                DB::beginTransaction();
                
                $payment->update([
                    'status' => 'expired',
                    'cancelled_at' => now(),
                    'cancelled_by' => 1, // ID de l'utilisateur système
                    'cancel_reason' => 'Paiement expiré automatiquement (délai dépassé)'
                ]);
                
                // Recalculer le total payé
                $transaction = $payment->transaction;
                if ($transaction) {
                    $transaction->updatePaymentStatus();
                }
                
                DB::commit();
                $count++;
            }

            return response()->json([
                'success' => true,
                'message' => $count . ' paiement(s) marqué(s) comme expiré(s).'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }
}