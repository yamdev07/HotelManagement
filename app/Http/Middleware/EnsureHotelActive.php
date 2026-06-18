<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'accès aux utilisateurs dont l'hôtel est suspendu ou dont
 * l'abonnement a expiré (gating SaaS « les hôtels qui ne payent pas »).
 *
 * Le Super-Admin plateforme (hotel_id null) et les routes de sortie
 * (logout, page suspendue) restent toujours accessibles.
 */
class EnsureHotelActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Invité, Super-Admin plateforme (sans hôtel) ou rôle Super : accès libre
        if (! $user || $user->hotel_id === null || (method_exists($user, 'isSuper') && $user->isSuper())) {
            return $next($request);
        }

        // Ne jamais bloquer la sortie ni la page d'information
        if ($request->routeIs('hotel.suspended', 'logout', 'logout.now') || $request->is('logout*', 'force-logout-all')) {
            return $next($request);
        }

        $hotel = $user->hotel;

        if ($hotel && ! $hotel->hasActiveAccess()) {
            return redirect()->route('hotel.suspended');
        }

        return $next($request);
    }
}
