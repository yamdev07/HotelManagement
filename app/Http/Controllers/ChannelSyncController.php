<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCalendarFeed;
use App\Services\IcalService;
use Illuminate\Http\Request;

/**
 * Gestion back-office de la synchronisation des calendriers (OTA).
 * L'hôtelier récupère le lien iCal de chaque chambre (à donner à Booking/Airbnb)
 * et colle en retour les liens iCal de ces plateformes (import anti-double-résa).
 * Le tenant courant vient de l'utilisateur connecté : tout est scopé à son hôtel.
 */
class ChannelSyncController extends Controller
{
    public function index()
    {
        $rooms = Room::with('calendarFeeds')->orderBy('number')->get();

        return view('channels.index', compact('rooms'));
    }

    public function storeFeed(Request $request, Room $room)
    {
        $data = $request->validate([
            'source' => ['required', 'string', 'max:60'],
            'url' => ['required', 'url', 'max:1000'],
        ], [], [
            'source' => 'plateforme',
            'url' => 'lien iCal',
        ]);

        RoomCalendarFeed::create([
            'hotel_id' => $room->hotel_id,
            'room_id' => $room->id,
            'source' => $data['source'],
            'url' => $data['url'],
        ]);

        return back()->with('success', __('flash.channel_feed_added') ?? 'Calendrier ajouté. La synchronisation se fera automatiquement.');
    }

    public function destroyFeed(RoomCalendarFeed $feed)
    {
        $feed->delete();

        return back()->with('success', __('flash.channel_feed_removed') ?? 'Calendrier retiré.');
    }

    public function syncNow(IcalService $ical)
    {
        $feeds = RoomCalendarFeed::all(); // scopé à l'hôtel courant
        $ok = 0;
        $ko = 0;

        foreach ($feeds as $feed) {
            $ical->syncFeed($feed)['ok'] ? $ok++ : $ko++;
        }

        return back()->with('success', "Synchronisation terminée : {$ok} calendrier(s) à jour".($ko ? ", {$ko} en erreur." : '.'));
    }
}
