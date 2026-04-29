<?php

namespace App\Http\Requests;

use App\Enums\TransactionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('updateStatus', \App\Models\Transaction::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(array_column(TransactionStatus::cases(), 'value')),
            ],
            'cancel_reason' => [
                Rule::requiredIf(fn () => $this->input('status') === TransactionStatus::Cancelled->value),
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'        => 'Le statut est requis.',
            'status.in'              => 'Le statut sélectionné est invalide.',
            'cancel_reason.required' => 'Une raison est obligatoire pour l\'annulation.',
        ];
    }
}
