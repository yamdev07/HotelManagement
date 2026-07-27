<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Nom "sérieux" : pas d'emoji/pictogramme ET au moins deux lettres.
 * Bloque les saisies du type « 👗)(*&^%^&*()_+ » (issues #160, #152, #166)
 * tout en acceptant les vrais noms (accents, tirets, apostrophes, chiffres).
 */
class SafeName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (preg_match(NoEmoji::EMOJI_PATTERN, $value)) {
            $fail(__('flash.validation_no_emoji', ['attribute' => $attribute]));

            return;
        }

        // Au moins deux lettres (n'importe quelle langue) quelque part dans le nom.
        if (! preg_match('/\p{L}.*\p{L}/u', $value)) {
            $fail(__('flash.validation_real_name', ['attribute' => $attribute]));
        }
    }
}
