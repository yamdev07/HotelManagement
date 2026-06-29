<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Menu;
use App\Models\Room;
use App\Support\TenantManager;

/**
 * Vitrine publique multi-pages d'un hôtel (/h/{slug}, /h/{slug}/chambres, ...).
 * Chaque page définit l'hôtel comme tenant courant pour scoper les données.
 */
class PublicSiteController extends Controller
{
    /** Résout l'hôtel par slug, vérifie l'accès et fixe le tenant. */
    private function resolve(string $slug): Hotel|\Illuminate\Http\Response
    {
        $hotel = Hotel::where('slug', $slug)->firstOrFail();

        if (! $hotel->hasActiveAccess()) {
            return response()->view('public.unavailable', ['hotel' => $hotel], 503);
        }

        app(TenantManager::class)->setHotelId($hotel->id);

        return $hotel;
    }

    public function show(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) return $hotel;

        $rooms = Room::with(['type', 'images'])
            ->where('room_status_id', Room::STATUS_AVAILABLE)
            ->limit(3)->get();

        return view('public.pages.home', compact('hotel', 'rooms'));
    }

    public function rooms(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) return $hotel;
        abort_unless($hotel->show_rooms, 404);

        $rooms = Room::with(['type', 'images'])
            ->where('room_status_id', Room::STATUS_AVAILABLE)
            ->get();

        return view('public.pages.rooms', compact('hotel', 'rooms'));
    }

    public function restaurant(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) return $hotel;
        abort_unless($hotel->show_restaurant, 404);

        $menus = Menu::limit(12)->get();

        return view('public.pages.restaurant', compact('hotel', 'menus'));
    }

    public function services(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) return $hotel;
        abort_unless($hotel->show_services, 404);

        return view('public.pages.services', compact('hotel'));
    }

    public function contact(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) return $hotel;
        abort_unless($hotel->show_contact, 404);

        return view('public.pages.contact', compact('hotel'));
    }
}
