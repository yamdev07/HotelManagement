<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Politique de mot de passe (issue #164) : au moins 8 caractères,
 * au moins une lettre et au moins un chiffre. Messages en français.
 */
class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (mb_strlen($value) < 8) {
            $fail('Le mot de passe doit contenir au moins 8 caractères.');

            return;
        }

        if (! preg_match('/\p{L}/u', $value)) {
            $fail('Le mot de passe doit contenir au moins une lettre.');

            return;
        }

        if (! preg_match('/\d/', $value)) {
            $fail('Le mot de passe doit contenir au moins un chiffre.');
        }
    }
}
