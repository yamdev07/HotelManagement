<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('transaction')) ?? false;
    }

    public function rules(): array
    {
        return [
            'check_in_date'  => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'room_id'        => ['required', 'integer', 'exists:rooms,id'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'check_in_date.required'  => 'La date d\'arrivée est requise.',
            'check_out_date.required' => 'La date de départ est requise.',
            'check_out_date.after'    => 'La date de départ doit être après la date d\'arrivée.',
            'room_id.required'        => 'La chambre est requise.',
            'room_id.exists'          => 'La chambre sélectionnée n\'existe pas.',
        ];
    }
}
