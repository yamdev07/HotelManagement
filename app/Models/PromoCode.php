<?php

namespace App\Models;

use App\Models\Concerns\BelongsToHotel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Code promo appliqué aux réservations en ligne (remise % ou montant fixe).
 */
class PromoCode extends Model
{
    use BelongsToHotel;

    protected $fillable = [
        'hotel_id', 'code', 'type', 'value', 'min_nights',
        'starts_at', 'ends_at', 'max_uses', 'used_count', 'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
        'min_nights' => 'integer',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    /** Normalise le code (majuscules, sans espaces) pour comparaison/stockage. */
    public static function normalize(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

    /**
     * Le code est-il utilisable pour un séjour de $nights nuits aujourd'hui ?
     *
     * @return array{ok:bool, reason:?string}
     */
    public function validateFor(int $nights, ?Carbon $now = null): array
    {
        $now = ($now ?? Carbon::now())->startOfDay();

        if (! $this->is_active) {
            return ['ok' => false, 'reason' => 'Ce code n\'est plus actif.'];
        }
        if ($this->starts_at && $now->lt($this->starts_at->copy()->startOfDay())) {
            return ['ok' => false, 'reason' => 'Ce code n\'est pas encore valable.'];
        }
        if ($this->ends_at && $now->gt($this->ends_at->copy()->endOfDay())) {
            return ['ok' => false, 'reason' => 'Ce code a expiré.'];
        }
        if ($nights < $this->min_nights) {
            return ['ok' => false, 'reason' => "Ce code exige au moins {$this->min_nights} nuit(s)."];
        }
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return ['ok' => false, 'reason' => 'Ce code a atteint sa limite d\'utilisation.'];
        }

        return ['ok' => true, 'reason' => null];
    }

    /** Montant de la remise pour un total donné (borné à [0, total]). */
    public function discountOn(float $total): int
    {
        $discount = $this->type === 'percent'
            ? $total * ((float) $this->value) / 100
            : (float) $this->value;

        return (int) round(max(0, min($discount, $total)));
    }

    /** Libellé lisible de la remise (ex. « -10 % » ou « -5 000 »). */
    public function label(): string
    {
        return $this->type === 'percent'
            ? '-'.rtrim(rtrim(number_format((float) $this->value, 2, ',', ' '), '0'), ',').' %'
            : '-'.number_format((float) $this->value, 0, ',', ' ');
    }
}
