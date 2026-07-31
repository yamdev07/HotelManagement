<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\User;
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
    /** Acompte demandé à la réservation en ligne (15 % du séjour, comme le back-office). */
    private const DEPOSIT_RATE = 0.15;

    /** Une chambre est-elle libre pour la période (pas de chevauchement, pas en maintenance) ? */
    private function roomIsAvailable(Room $room, Carbon $ci, Carbon $co): bool
    {
        $outOfService = RoomStatus::whereIn('code', ['MNT'])->pluck('id')->all();
        if (in_array($room->room_status_id, $outOfService, true)) {
            return false;
        }

        return ! Transaction::where('room_id', $room->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('check_in', '<', $co->format('Y-m-d'))
            ->whereDate('check_out', '>', $ci->format('Y-m-d'))
            ->exists();
    }

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

    /**
     * LOT 2 — Récapitulatif de réservation d'une chambre (dates, nuits, total, acompte)
     * + formulaire des coordonnées du voyageur.
     */
    public function booking(Request $request, string $slug, $room)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        // Résolu APRÈS le tenant -> scopé à cet hôtel (pas de fuite inter-hôtel).
        $roomModel = Room::with(['type', 'images'])->findOrFail($room);

        $checkIn  = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $guests   = max(1, (int) $request->query('guests', 1));

        $v = Validator::make(
            ['check_in' => $checkIn, 'check_out' => $checkOut],
            ['check_in' => ['required', 'date', 'after_or_equal:today'], 'check_out' => ['required', 'date', 'after:check_in']]
        );
        if ($v->fails()) {
            return redirect()->route('public.hotel.availability', $hotel->slug);
        }

        $ci = Carbon::parse($checkIn)->startOfDay();
        $co = Carbon::parse($checkOut)->startOfDay();

        if ($guests > $roomModel->capacity || ! $this->roomIsAvailable($roomModel, $ci, $co)) {
            return redirect()
                ->route('public.hotel.availability', ['slug' => $hotel->slug, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests])
                ->with('booking_error', "Cette chambre n'est plus disponible pour ces dates.");
        }

        $nights  = max(1, $ci->diffInDays($co));
        $total   = $roomModel->price * $nights;
        $deposit = (int) round($total * self::DEPOSIT_RATE);

        return view('public.pages.booking', [
            'hotel' => $hotel, 'roomModel' => $roomModel,
            'checkIn' => $ci->format('Y-m-d'), 'checkOut' => $co->format('Y-m-d'),
            'guests' => $guests, 'nights' => $nights, 'total' => $total, 'deposit' => $deposit,
        ]);
    }

    /** LOT 2 — Enregistre la réservation (statut "reservation", en attente de paiement). */
    public function storeBooking(Request $request, string $slug, $room)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $roomModel = Room::findOrFail($room);

        $data = $request->validate([
            'check_in'  => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests'    => ['required', 'integer', 'min:1', 'max:'.$roomModel->capacity],
            'name'      => ['required', 'string', 'max:255', new \App\Rules\SafeName],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['required', 'string', 'regex:/^[0-9+\s().\-]{6,20}$/'],
        ], [
            'name.required'  => 'Votre nom est requis.',
            'email.required' => 'Votre email est requis.',
            'phone.required' => 'Votre téléphone est requis.',
            'phone.regex'    => 'Le téléphone ne doit contenir que des chiffres, espaces, +, -, ( ).',
            'guests.max'     => 'Cette chambre accueille au maximum :max voyageur(s).',
        ], ['name' => 'nom', 'email' => 'email', 'phone' => 'téléphone']);

        $ci = Carbon::parse($data['check_in'])->startOfDay();
        $co = Carbon::parse($data['check_out'])->startOfDay();

        if (! $this->roomIsAvailable($roomModel, $ci, $co)) {
            return back()->withInput()->with('booking_error', "Désolé, cette chambre vient d'être réservée pour ces dates. Choisissez-en une autre.");
        }

        $nights = max(1, $ci->diffInDays($co));
        $total  = $roomModel->price * $nights;

        // "Agent" propriétaire de la ligne (pas d'utilisateur connecté côté public).
        $agentId = $hotel->owner_user_id
            ?? User::where('hotel_id', $hotel->id)->whereIn('role', ['Admin', 'Super'])->value('id')
            ?? User::where('hotel_id', $hotel->id)->value('id');

        $customer = Customer::where('email', $data['email'])->first();
        if (! $customer) {
            $customer = Customer::create([
                'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'],
                'gender' => 'Other', 'user_id' => $agentId,
            ]);
        }

        $tx = Transaction::create([
            'user_id' => $agentId, 'customer_id' => $customer->id, 'room_id' => $roomModel->id,
            'check_in' => $ci->format('Y-m-d'), 'check_out' => $co->format('Y-m-d'),
            'status' => 'reservation', 'person_count' => $data['guests'], 'total_price' => $total,
            'notes' => 'Réservation en ligne depuis la vitrine.',
        ]);

        return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id]);
    }

    /** LOT 2 — Page de confirmation (référence + prochaines étapes). */
    public function bookingConfirmed(string $slug, $transaction)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $tx = Transaction::with(['room.type', 'customer'])->findOrFail($transaction); // scopé hôtel
        $deposit = (int) round(($tx->total_price ?? 0) * self::DEPOSIT_RATE);

        return view('public.pages.booking-confirmed', compact('hotel', 'tx', 'deposit'));
    }
}
