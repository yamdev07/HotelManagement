<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Refuse les emojis et pictogrammes dans un champ texte (nom d'entreprise,
 * nom de personne…). Laisse passer lettres accentuées, chiffres et ponctuation.
 * Issue #140.
 */
class NoEmoji implements ValidationRule
{
    /** Plages Unicode couvrant l'essentiel des emojis / pictogrammes. */
    public const EMOJI_PATTERN = '/[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}\x{2300}-\x{23FF}\x{2B00}-\x{2BFF}\x{FE00}-\x{FE0F}\x{1F1E6}-\x{1F1FF}\x{200D}\x{2028}-\x{2029}]/u';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && preg_match(self::EMOJI_PATTERN, $value)) {
            $fail(__('flash.validation_no_emoji', ['attribute' => $attribute]));
        }
    }
}
