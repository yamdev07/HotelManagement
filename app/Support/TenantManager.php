<?php

namespace App\Support;

use App\Models\Hotel;

/**
 * Gère l'hôtel « courant » du multi-tenant.
 *
 * Par défaut l'hôtel est résolu depuis l'utilisateur connecté (auth()->user()->hotel_id).
 * Il peut être surchargé explicitement (CLI, jobs, dashboard Super-Admin qui visite
 * un hôtel donné). Quand aucun hôtel n'est résolu (Super-Admin plateforme, console,
 * tests bruts), le scope multi-tenant ne s'applique pas.
 */
class TenantManager
{
    protected ?int $hotelId = null;

    protected bool $resolved = false;

    /**
     * Force l'hôtel courant (et marque comme résolu).
     */
    public function setHotelId(?int $hotelId): void
    {
        $this->hotelId = $hotelId;
        $this->resolved = true;
    }

    /**
     * Retourne l'id de l'hôtel courant, en le résolvant depuis l'auth si besoin.
     */
    public function getHotelId(): ?int
    {
        if (! $this->resolved) {
            if (auth()->check()) {
                $this->hotelId = auth()->user()->hotel_id;
            }
            $this->resolved = true;
        }

        return $this->hotelId;
    }

    public function hasTenant(): bool
    {
        return $this->getHotelId() !== null;
    }

    public function hotel(): ?Hotel
    {
        $id = $this->getHotelId();

        return $id ? Hotel::find($id) : null;
    }

    /**
     * Exécute un callback dans le contexte d'un hôtel donné, puis restaure l'état.
     */
    public function withHotel(?int $hotelId, callable $callback)
    {
        $previousId = $this->hotelId;
        $previousResolved = $this->resolved;

        $this->setHotelId($hotelId);

        try {
            return $callback();
        } finally {
            $this->hotelId = $previousId;
            $this->resolved = $previousResolved;
        }
    }

    /**
     * Réinitialise la résolution (utile entre deux requêtes/tests).
     */
    public function forget(): void
    {
        $this->hotelId = null;
        $this->resolved = false;
    }
}
