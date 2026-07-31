<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Support\TenantManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $rooms = Room::with(['type', 'images'])
            ->where('room_status_id', Room::STATUS_AVAILABLE)
            ->limit(3)->get();

        return view('public.pages.home', compact('hotel', 'rooms'));
    }

    public function rooms(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }
        abort_unless($hotel->show_rooms, 404);

        $rooms = Room::with(['type', 'images'])
            ->where('room_status_id', Room::STATUS_AVAILABLE)
            ->get();

        return view('public.pages.rooms', compact('hotel', 'rooms'));
    }

    public function restaurant(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }
        abort_unless($hotel->show_restaurant, 404);

        $menus = Menu::limit(12)->get();

        return view('public.pages.restaurant', compact('hotel', 'menus'));
    }

    public function services(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }
        abort_unless($hotel->show_services, 404);

        return view('public.pages.services', compact('hotel'));
    }

    public function contact(string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }
        abort_unless($hotel->show_contact, 404);

        return view('public.pages.contact', compact('hotel'));
    }

    /**
     * LOT 1 — Recherche de disponibilité publique (voyageur sans compte).
     * Le tenant est fixé sur l'hôtel (resolve) : Room/Transaction sont scopés.
     */
    public function availability(Request $request, string $slug)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $checkIn  = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $guests   = max(1, (int) $request->query('guests', 1));

        $rooms = null;
        $nights = null;
        $searched = false;
        $errors = [];

        if ($checkIn || $checkOut) {
            $v = Validator::make(
                ['check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests],
                [
                    'check_in'  => ['required', 'date', 'after_or_equal:today'],
                    'check_out' => ['required', 'date', 'after:check_in'],
                    'guests'    => ['required', 'integer', 'min:1', 'max:20'],
                ],
                [
                    'check_in.required'        => "Indiquez la date d'arrivée.",
                    'check_in.after_or_equal'  => "La date d'arrivée ne peut pas être dans le passé.",
                    'check_out.required'       => 'Indiquez la date de départ.',
                    'check_out.after'          => 'Le départ doit être après l\'arrivée.',
                ]
            );

            if ($v->fails()) {
                $errors = $v->errors()->all();
            } else {
                $searched = true;
                $ci = Carbon::parse($checkIn)->startOfDay();
                $co = Carbon::parse($checkOut)->startOfDay();
                $nights = max(1, $ci->diffInDays($co));

                // Chambres occupées sur la période (chevauchement de dates), scopé hôtel.
                $occupied = Transaction::where('status', '!=', 'cancelled')
                    ->whereDate('check_in', '<', $co->format('Y-m-d'))
                    ->whereDate('check_out', '>', $ci->format('Y-m-d'))
                    ->pluck('room_id')->unique()->filter()->all();

                // Statuts hors service (maintenance) exclus (comme le back-office).
                $outOfService = RoomStatus::whereIn('code', ['MNT'])->pluck('id')->all();

                $rooms = Room::with(['type', 'images'])
                    ->where('capacity', '>=', $guests)
                    ->when($occupied, fn ($q) => $q->whereNotIn('id', $occupied))
                    ->when($outOfService, fn ($q) => $q->whereNotIn('room_status_id', $outOfService))
                    ->orderBy('price')
                    ->get();
            }
        }

        return view('public.pages.availability', compact(
            'hotel', 'rooms', 'nights', 'searched', 'errors', 'checkIn', 'checkOut', 'guests'
        ));
    }
}
