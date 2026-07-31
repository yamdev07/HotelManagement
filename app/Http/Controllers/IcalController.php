<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\IcalService;
use Illuminate\Http\Response;

/**
 * Flux iCal public d'une chambre (export vers Booking.com / Airbnb).
 * Accès par jeton non devinable ; aucune donnée client n'est exposée.
 */
class IcalController extends Controller
{
    public function export(string $token, IcalService $ical): Response
    {
        // Contexte public sans tenant : le HotelScope ne filtre pas, on retrouve
        // la chambre par son jeton unique quel que soit l'hôtel.
        $room = Room::where('ical_token', $token)->firstOrFail();

        return response($ical->buildRoomFeed($room), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="chambre-'.$room->id.'.ics"',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }
}
