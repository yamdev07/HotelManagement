<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            // Identifiant : email OU numéro de téléphone (issue #165). Le champ
            // s'appelle toujours "email" côté formulaire pour compatibilité.
            'email' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
