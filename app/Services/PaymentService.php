<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Exceptions\PaymentException;
use App\Models\CashierSession;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Enregistrer un paiement pour une transaction.
     *
     * @throws PaymentException
     */
    public function store(Transaction $transaction, array $data): Payment
    {
        $this->assertCanReceivePayment($transaction);

        $activeSession = $this->requireActiveSession();

        $amount = (float) $data['amount'];
        $remaining = $transaction->getRemainingPayment();

        if ($amount > $remaining + 100) {
            throw PaymentException::amountExceedsBalance($remaining);
        }

        return DB::transaction(function () use ($transaction, $data, $amount, $activeSession) {
            $method    = PaymentMethod::from($data['payment_method']);
            $reference = $this->generateReference($method, $transaction);
            $description = $this->buildDescription($data);

            $payment = Payment::create([
                'transaction_id'      => $transaction->id,
                'cashier_session_id'  => $activeSession->id,
                'user_id'             => Auth::id(),
                'created_by'          => Auth::id(),
                'amount'              => $amount,
                'status'              => PaymentStatus::Completed->value,
                'payment_method'      => $method->value,
                'reference'           => $reference,
                'description'         => $description,
                'payment_date'        => now(),
                'notes'               => $data['notes'] ?? null,
            ]);

            if ($method->requiresSessionBalance()) {
                $activeSession->increment('current_balance', $amount);
            }

            $transaction->updatePaymentStatus();

            $transaction->logPayment($payment, Auth::user());

            Log::info("Paiement #{$payment->id} créé pour transaction #{$transaction->id}", [
                'amount'  => $amount,
                'method'  => $method->value,
                'session' => $activeSession->id,
            ]);

            return $payment;
        });
    }

    /**
     * Annuler un paiement.
     *
     * @throws PaymentException
     */
    public function cancel(Payment $payment, string $reason): Payment
    {
        if (! $payment->status === PaymentStatus::Completed->value && Auth::user()?->role !== 'Super') {
            throw PaymentException::cannotCancelCompletedPayment();
        }

        return DB::transaction(function () use ($payment, $reason) {
            $payment->update([
                'status'        => PaymentStatus::Cancelled->value,
                'cancelled_at'  => now(),
                'cancelled_by'  => Auth::id(),
                'cancel_reason' => $reason,
            ]);

            $payment->transaction?->updatePaymentStatus();

            Log::info("Paiement #{$payment->id} annulé", [
                'by'     => Auth::id(),
                'reason' => $reason,
            ]);

            return $payment->refresh();
        });
    }

    // -----------------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------------

    private function assertCanReceivePayment(Transaction $transaction): void
    {
        $blockedStatuses = [
            TransactionStatus::Cancelled->value,
            TransactionStatus::NoShow->value,
            TransactionStatus::Completed->value,
        ];

        if (in_array($transaction->status, $blockedStatuses)) {
            throw PaymentException::transactionAlreadyPaid();
        }
    }

    private function requireActiveSession(): CashierSession
    {
        $session = CashierSession::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $session) {
            throw PaymentException::noActiveSession();
        }

        return $session;
    }

    private function generateReference(PaymentMethod $method, Transaction $transaction): string
    {
        $prefix = strtoupper(substr($method->value, 0, 3));
        return "{$prefix}-{$transaction->id}-" . now()->format('YmdHis') . '-' . random_int(1000, 9999);
    }

    private function buildDescription(array $data): string
    {
        $base    = $data['description'] ?? '';
        $details = [];

        switch ($data['payment_method']) {
            case 'mobile_money':
                if (! empty($data['mobile_operator'])) $details[] = "Opérateur: {$data['mobile_operator']}";
                if (! empty($data['mobile_number']))   $details[] = "Tél: {$data['mobile_number']}";
                if (! empty($data['transaction_id']))  $details[] = "ID: {$data['transaction_id']}";
                break;

            case 'card':
                if (! empty($data['card_number']))  $details[] = "Carte: **** " . substr($data['card_number'], -4);
                if (! empty($data['card_type']))    $details[] = "Type: {$data['card_type']}";
                if (! empty($data['card_holder']))  $details[] = "Titulaire: {$data['card_holder']}";
                break;

            case 'transfer':
                if (! empty($data['bank_name']))    $details[] = "Banque: {$data['bank_name']}";
                if (! empty($data['iban']))         $details[] = "IBAN: {$data['iban']}";
                if (! empty($data['transfer_date'])) $details[] = "Date: " . Carbon::parse($data['transfer_date'])->format('d/m/Y');
                break;

            case 'fedapay':
                if (! empty($data['fedapay_transaction_id'])) $details[] = "Réf FedaPay: {$data['fedapay_transaction_id']}";
                if (! empty($data['fedapay_method']))         $details[] = "Via: {$data['fedapay_method']}";
                break;

            case 'check':
                if (! empty($data['check_number'])) $details[] = "Chèque n°{$data['check_number']}";
                if (! empty($data['issuing_bank']))  $details[] = "Banque: {$data['issuing_bank']}";
                break;
        }

        return $base . (! empty($details) ? ' | ' . implode(' | ', $details) : '');
    }
}
