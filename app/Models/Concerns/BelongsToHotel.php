<?php

namespace App\Models\Concerns;

use App\Models\Hotel;
use App\Models\Scopes\HotelScope;
use App\Support\TenantManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * À appliquer sur tout modèle opérationnel possédant une colonne hotel_id.
 *
 * - Filtre automatiquement les requêtes sur l'hôtel courant (HotelScope).
 * - Renseigne hotel_id à la création si absent.
 * - Expose hotel() et un scope pour requêter hors tenant.
 */
trait BelongsToHotel
{
    public static function bootBelongsToHotel(): void
    {
        static::addGlobalScope(new HotelScope());

        static::creating(function ($model) {
            if (empty($model->hotel_id)) {
                $hotelId = app(TenantManager::class)->getHotelId();
                if ($hotelId !== null) {
                    $model->hotel_id = $hotelId;
                }
            }
        });
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Requête sans le filtre multi-tenant (toutes les données, tous hôtels).
     */
    public function scopeWithoutHotelScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(HotelScope::class);
    }

    /**
     * Requête forcée sur un hôtel précis.
     */
    public function scopeForHotel(Builder $query, int $hotelId): Builder
    {
        return $query->withoutGlobalScope(HotelScope::class)->where($this->getTable().'.hotel_id', $hotelId);
    }
}
