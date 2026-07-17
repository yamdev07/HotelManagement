<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChooseRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'count_person' => 'required|numeric',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ];
    }

    /**
     * Messages clairs (issue #174) : l'utilisateur choisit librement ses dates,
     * et on lui explique précisément le problème à la validation.
     */
    public function messages()
    {
        return [
            'check_in.required' => "La date d'arrivée est obligatoire.",
            'check_in.date' => "La date d'arrivée n'est pas une date valide.",
            'check_in.after_or_equal' => "La date d'arrivée ne peut pas être dans le passé (au plus tôt : aujourd'hui).",
            'check_out.required' => 'La date de départ est obligatoire.',
            'check_out.date' => "La date de départ n'est pas une date valide.",
            'check_out.after' => "La date de départ doit être après la date d'arrivée (au moins une nuit).",
            'count_person.required' => 'Le nombre de personnes est obligatoire.',
        ];
    }
}
