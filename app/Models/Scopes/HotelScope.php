<?php

namespace App\Models\Scopes;

use App\Support\TenantManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Filtre automatiquement les requêtes sur l'hôtel courant.
 * Si aucun hôtel n'est résolu (Super-Admin plateforme, console), aucun filtre
 * n'est appliqué — toutes les données restent accessibles.
 */
class HotelScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $hotelId = app(TenantManager::class)->getHotelId();

        if ($hotelId !== null) {
            $builder->where($model->getTable().'.hotel_id', $hotelId);
        }
    }
}
