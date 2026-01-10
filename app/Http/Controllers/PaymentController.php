<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\Interface\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    public function index()
    {
        $payments = Payment::orderBy('id', 'DESC')->paginate(5);

        return view('payment.index', ['payments' => $payments]);
    }

    public function create(Transaction $transaction)
    {
        return view('transaction.payment.create', [
            'transaction' => $transaction,
        ]);
    }

    public function store(Transaction $transaction, Request $request)
    {
        $insufficient = $transaction->getTotalPrice() - $transaction->getTotalPayment();
        $request->validate([
            'payment' => 'required|numeric|lte:'.$insufficient,
        ]);

        $this->paymentRepository->store($request, $transaction, 'Payment');

        return redirect()->route('transaction.index')->with('success', 'Transaction room '.$transaction->room->number.' success, '.Helpers::convertToRupiah($request->payment).' paid');
    }

    public function invoice(Payment $payment)
    {
        try {
            // Charger toutes les relations nécessaires pour la facture
            $payment->load([
                'transaction' => function($query) {
                    $query->with([
                        'customer',
                        'room.type',
                        'payment' => function($q) { // CHANGÉ : payment() au singulier
                            $q->orderBy('created_at', 'asc');
                        }
                    ]);
                }
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
            
            // Utiliser payment.invoice
            return view('payment.invoice', compact('payment'));
            
        } catch (\Exception $e) {
            // Log l'erreur pour debugging
            \Log::error('Erreur génération facture: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération de la facture : ' . $e->getMessage());
        }
    }
}