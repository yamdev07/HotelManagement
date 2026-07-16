<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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

        // Date de naissance plausible : entre 1900 et aujourd'hui (issue #161).
        $birthdate = 'required|date|after_or_equal:1900-01-01|before_or_equal:today';

        if ($this->isMethod('put')) {
            return [
                'name' => 'required',
                'address' => 'required|max:255',
                'job' => 'required',
                'birthdate' => $birthdate,
                'gender' => 'required|in:Male,Female',
                'avatar' => 'mimes:png,jpg',
            ];
        }

        return [
            'name' => 'required',
            'address' => 'required|max:255',
            'job' => 'required',
            'birthdate' => $birthdate,
            'gender' => 'required|in:Male,Female',
            // Email non unique globalement : le même client peut exister dans
            // plusieurs hôtels (la fiche est propre à chaque établissement).
            'email' => 'required|email',
            'avatar' => 'mimes:png,jpg',
        ];
    }

    public function messages()
    {
        return [
            'birthdate.after_or_equal' => "La date de naissance n'est pas valide (au plus tôt : 01/01/1900).",
            'birthdate.before_or_equal' => 'La date de naissance ne peut pas être dans le futur.',
        ];
    }
}
