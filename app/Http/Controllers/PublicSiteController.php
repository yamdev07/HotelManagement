<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Menu;
use App\Models\Room;
use App\Support\TenantManager;

/**
 * Vitrine publique d'un hôtel, accessible par son slug (/h/{slug}).
 * Définit l'hôtel comme tenant courant pour que les données affichées
 * (chambres, menu...) soient automatiquement celles de cet hôtel.
 */
class PublicSiteController extends Controller
{
    public function show(string $slug)
    {
        $hotel = Hotel::where('slug', $slug)->firstOrFail();

        // Hôtel suspendu ou abonnement expiré : vitrine indisponible
        if (! $hotel->hasActiveAccess()) {
            return response()->view('public.unavailable', ['hotel' => $hotel], 503);
        }

        // Contexte tenant : tout ce qui est scopé renverra les données de cet hôtel
        app(TenantManager::class)->setHotelId($hotel->id);

        $rooms = Room::with(['type', 'images'])
            ->where('room_status_id', Room::STATUS_AVAILABLE)
            ->get();

        $menus = $hotel->show_restaurant
            ? Menu::limit(8)->get()
            : collect();

        return view('public.hotel', compact('hotel', 'rooms', 'menus'));
    }
}
