<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Force l'administrateur d'un hôtel fraîchement créé à passer par l'onboarding
 * (personnalisation initiale) avant d'accéder au reste de l'application.
 */
class EnsureOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Invité, Super-Admin plateforme, ou rôle non administrateur : pas de redirection
        if (! $user || $user->hotel_id === null || ! in_array($user->role, ['Admin', 'Super'], true)) {
            return $next($request);
        }

        // Pages toujours accessibles (onboarding lui-même, déconnexion, suspension)
        if ($request->routeIs('onboarding.show', 'onboarding.store', 'logout', 'logout.now', 'hotel.suspended')
            || $request->is('logout*', 'force-logout-all')) {
            return $next($request);
        }

        $hotel = $user->hotel;

        if ($hotel && $hotel->needsOnboarding()) {
            return redirect()->route('onboarding.show');
        }

        return $next($request);
    }
}
