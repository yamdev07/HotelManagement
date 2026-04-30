<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Payment::class) ?? false;
    }

    public function rules(): array
    {
        $transaction = $this->route('transaction');
        $remaining   = $transaction ? $transaction->getRemainingPayment() : PHP_INT_MAX;

        return [
            'amount' => [
                'required',
                'numeric',
                'min:100',
                "max:{$remaining}",
            ],
            'payment_method' => [
                'required',
                Rule::in(array_column(PaymentMethod::cases(), 'value')),
            ],
            'description'            => ['nullable', 'string', 'max:500'],
            'mobile_operator'        => ['nullable', 'string', 'max:50'],
            'mobile_number'          => ['nullable', 'string', 'max:20'],
            'transaction_id'         => ['nullable', 'string', 'max:100'],
            'card_number'            => ['nullable', 'string', 'max:19'],
            'card_expiry'            => ['nullable', 'string', 'max:7'],
            'card_type'              => ['nullable', 'string', 'max:50'],
            'card_holder'            => ['nullable', 'string', 'max:100'],
            'bank_name'              => ['nullable', 'string', 'max:100'],
            'account_number'         => ['nullable', 'string', 'max:50'],
            'iban'                   => ['nullable', 'string', 'max:50'],
            'bic'                    => ['nullable', 'string', 'max:20'],
            'transfer_date'          => ['nullable', 'date'],
            'fedapay_transaction_id' => ['nullable', 'string', 'max:100'],
            'fedapay_method'         => ['nullable', 'string', 'max:50'],
            'check_number'           => ['nullable', 'string', 'max:50'],
            'issuing_bank'           => ['nullable', 'string', 'max:100'],
            'notes'                  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Le montant est requis.',
            'amount.min'      => 'Le montant minimum est de :min CFA.',
            'amount.max'      => 'Le montant ne peut pas dépasser le solde restant.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'payment_method.in'       => 'La méthode de paiement sélectionnée est invalide.',
        ];
    }
}
