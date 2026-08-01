<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\PromoCode;
use App\Models\Room;
use App\Models\RoomBlock;
use App\Models\RoomStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FedaPayService;
use App\Services\WhatsAppService;
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

    public function __construct(
        private FedaPayService $fedapay,
        private WhatsAppService $whatsapp,
    ) {}

    /** Référence lisible d'une réservation (RES-00042). */
    private function bookingRef(Transaction $tx): string
    {
        return 'RES-'.str_pad((string) $tx->id, 5, '0', STR_PAD_LEFT);
    }

    /** Numéro WhatsApp de l'hôtel (réseaux sociaux en priorité, sinon téléphone). */
    private function hotelWhatsApp(Hotel $hotel): ?string
    {
        return $hotel->socials['whatsapp'] ?? $hotel->contact_phone ?? null;
    }

    /**
     * Notifie le client + l'hôtelier qu'une réservation vient d'être créée.
     * Silencieux si WhatsApp n'est pas configuré ; n'interrompt jamais le tunnel.
     */
    private function notifyBookingCreated(Hotel $hotel, Transaction $tx): void
    {
        if (! $this->whatsapp->isConfigured()) {
            return;
        }

        $ref = $this->bookingRef($tx);
        $ci = Carbon::parse($tx->check_in)->format('d/m/Y');
        $co = Carbon::parse($tx->check_out)->format('d/m/Y');
        $room = $tx->room->number ?? '';
        $currency = $hotel->currency ?: 'XOF';
        $total = number_format((float) $tx->total_price, 0, ',', ' ');

        // Au client
        $this->whatsapp->sendText(
            $tx->customer->phone ?? null,
            "Bonjour {$tx->customer->name},\n\n".
            "Votre réservation *{$ref}* chez *{$hotel->name}* est bien enregistrée ✅\n\n".
            "🛏️ Chambre {$room}\n📅 Du {$ci} au {$co}\n💰 Total : {$total} {$currency}\n\n".
            'Nous revenons vers vous très vite. Merci !'
        );

        // À l'hôtelier
        $this->whatsapp->sendText(
            $this->hotelWhatsApp($hotel),
            "🔔 Nouvelle réservation en ligne *{$ref}*\n\n".
            "👤 {$tx->customer->name} ({$tx->customer->phone})\n🛏️ Chambre {$room}\n".
            "📅 {$ci} → {$co}\n💰 {$total} {$currency}"
        );
    }

    /** Notifie le client que l'acompte a été reçu et la réservation confirmée. */
    private function notifyDepositPaid(Hotel $hotel, Transaction $tx, float $amount): void
    {
        if (! $this->whatsapp->isConfigured()) {
            return;
        }

        $ref = $this->bookingRef($tx);
        $currency = $hotel->currency ?: 'XOF';
        $paid = number_format($amount, 0, ',', ' ');
        $balance = number_format(max(0, (float) $tx->total_price - $amount), 0, ',', ' ');

        $this->whatsapp->sendText(
            $tx->customer->phone ?? null,
            "Merci {$tx->customer->name} ! 🎉\n\n".
            "Nous avons bien reçu votre acompte de *{$paid} {$currency}* pour la réservation *{$ref}*.\n".
            "Votre chambre est confirmée ✅\n\n".
            "Solde à régler à l'arrivée : {$balance} {$currency}.\n".
            "À très bientôt chez *{$hotel->name}* !"
        );
    }

    /** Montant de l'acompte (15 %) pour une réservation. */
    private function depositFor(Transaction $tx): int
    {
        return (int) round(((float) ($tx->total_price ?? 0)) * self::DEPOSIT_RATE);
    }

    /** L'acompte de cette réservation est-il déjà couvert par les paiements enregistrés ? */
    private function depositPaid(Transaction $tx): bool
    {
        $deposit = $this->depositFor($tx);

        return $deposit > 0 && (float) ($tx->total_payment ?? 0) >= $deposit - 1;
    }

    /**
     * Cherche un code promo valide pour l'hôtel courant et un séjour de N nuits.
     *
     * @return array{promo:?PromoCode, error:?string}
     */
    private function findValidPromo(?string $rawCode, int $nights): array
    {
        $code = PromoCode::normalize($rawCode);
        if ($code === '') {
            return ['promo' => null, 'error' => null];
        }

        $promo = PromoCode::where('code', $code)->first(); // scopé à l'hôtel (tenant)
        if (! $promo) {
            return ['promo' => null, 'error' => 'Code promo inconnu.'];
        }

        $check = $promo->validateFor($nights);

        return $check['ok']
            ? ['promo' => $promo, 'error' => null]
            : ['promo' => null, 'error' => $check['reason']];
    }

    /** Une chambre est-elle libre pour la période (pas de chevauchement, pas en maintenance) ? */
    private function roomIsAvailable(Room $room, Carbon $ci, Carbon $co): bool
    {
        $outOfService = RoomStatus::whereIn('code', ['MNT'])->pluck('id')->all();
        if (in_array($room->room_status_id, $outOfService, true)) {
            return false;
        }

        // Bloquée par un calendrier OTA (Booking/Airbnb) importé ?
        $blockedByOta = RoomBlock::where('room_id', $room->id)
            ->overlapping($ci->format('Y-m-d'), $co->format('Y-m-d'))
            ->exists();
        if ($blockedByOta) {
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

        // Galerie réelle : photos des chambres uploadées par l'hôtelier.
        $gallery = collect();
        foreach (Room::with('images')->get() as $r) {
            foreach ($r->images as $img) {
                $img->setRelation('room', $r);
                $gallery->push($img->getRoomImage());
            }
        }
        $gallery = $gallery->filter()->unique()->take(10)->values();

        return view('public.pages.home', compact('hotel', 'rooms', 'gallery'));
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

                // Chambres bloquées par un calendrier OTA (Booking/Airbnb) sur la période.
                $blocked = RoomBlock::overlapping($ci->format('Y-m-d'), $co->format('Y-m-d'))
                    ->pluck('room_id')->unique()->filter()->all();

                $excluded = array_values(array_unique(array_merge($occupied, $blocked)));

                $rooms = Room::with(['type', 'images'])
                    ->where('capacity', '>=', $guests)
                    ->when($excluded, fn ($q) => $q->whereNotIn('id', $excluded))
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

        // Code promo éventuel (saisi par le voyageur).
        $promoRaw = $request->query('promo');
        ['promo' => $promo, 'error' => $promoError] = $this->findValidPromo($promoRaw, $nights);
        $discount = $promo ? $promo->discountOn($total) : 0;
        $finalTotal = $total - $discount;
        $deposit = (int) round($finalTotal * self::DEPOSIT_RATE);

        return view('public.pages.booking', [
            'hotel' => $hotel, 'roomModel' => $roomModel,
            'checkIn' => $ci->format('Y-m-d'), 'checkOut' => $co->format('Y-m-d'),
            'guests' => $guests, 'nights' => $nights, 'total' => $total,
            'discount' => $discount, 'finalTotal' => $finalTotal, 'deposit' => $deposit,
            'promo' => $promo, 'promoRaw' => $promoRaw, 'promoError' => $promoError,
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
            'promo_code' => ['nullable', 'string', 'max:40'],
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

        // Code promo : re-validé côté serveur (on ne fait jamais confiance au client).
        ['promo' => $promo] = $this->findValidPromo($data['promo_code'] ?? null, $nights);
        $discount = $promo ? $promo->discountOn($total) : 0;
        $finalTotal = $total - $discount;

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
            'status' => 'reservation', 'person_count' => $data['guests'], 'total_price' => $finalTotal,
            'promo_code' => $promo?->code, 'discount_amount' => $discount,
            'notes' => 'Réservation en ligne depuis la vitrine.'.($promo ? ' Code promo : '.$promo->code.' (-'.number_format($discount, 0, ',', ' ').').' : ''),
        ]);

        if ($promo) {
            $promo->increment('used_count');
        }

        $tx->load(['room', 'customer']);
        $this->notifyBookingCreated($hotel, $tx);

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
        $deposit = $this->depositFor($tx);
        $canPayOnline = $this->fedapay->isConfigured();
        $depositPaid = $this->depositPaid($tx);

        return view('public.pages.booking-confirmed', compact('hotel', 'tx', 'deposit', 'canPayOnline', 'depositPaid'));
    }

    /**
     * LOT 3 — Initie le paiement en ligne de l'acompte (FedaPay) et redirige
     * vers la page de paiement hébergée.
     */
    public function payDeposit(Request $request, string $slug, $transaction)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $tx = Transaction::with('customer')->findOrFail($transaction); // scopé hôtel

        if (! $this->fedapay->isConfigured()) {
            return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id])
                ->with('payment_error', "Le paiement en ligne n'est pas encore activé pour cet établissement.");
        }

        if ($this->depositPaid($tx)) {
            return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id]);
        }

        $deposit = $this->depositFor($tx);
        $ref = 'RES-'.str_pad((string) $tx->id, 5, '0', STR_PAD_LEFT);

        try {
            $checkout = $this->fedapay->createCheckout(
                hotel: $hotel,
                plan: 'reservation-deposit',
                amount: $deposit,
                currency: $hotel->currency ?: 'XOF',
                description: "Acompte réservation {$ref} — {$hotel->name}",
                callbackUrl: route('public.hotel.payment.return', [$hotel->slug, $tx->id]),
                customerEmail: $tx->customer->email ?? 'client@example.com',
                customerName: $tx->customer->name ?? 'Client',
            );
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id])
                ->with('payment_error', "Le paiement n'a pas pu être initié. Réessayez dans un instant.");
        }

        // Mémorise la transaction FedaPay pour la vérifier au retour (anti-fraude).
        $request->session()->put('online_pay.'.$tx->id, [
            'fedapay_id' => $checkout['transaction_id'],
            'amount' => $deposit,
        ]);

        return redirect()->away($checkout['url']);
    }

    /**
     * LOT 3 — Retour de FedaPay : vérifie le statut ('approved') et enregistre
     * l'acompte comme paiement en ligne (hors caisse) sur la réservation.
     */
    public function paymentReturn(Request $request, string $slug, $transaction)
    {
        $hotel = $this->resolve($slug);
        if (! $hotel instanceof Hotel) {
            return $hotel;
        }

        $tx = Transaction::with('customer')->findOrFail($transaction); // scopé hôtel

        // Déjà réglé (double retour / rechargement) : idempotent.
        if ($this->depositPaid($tx)) {
            return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id])
                ->with('payment_success', true);
        }

        $pending = $request->session()->pull('online_pay.'.$tx->id, []);
        $fedapayId = (int) ($pending['fedapay_id'] ?? $request->query('id', 0));

        $approved = false;
        if ($fedapayId) {
            try {
                $approved = $this->fedapay->isApproved($fedapayId);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (! $approved) {
            return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id])
                ->with('payment_error', "Le paiement n'a pas été confirmé. Vous pouvez réessayer.");
        }

        $amount = (float) ($pending['amount'] ?? $this->depositFor($tx));
        $agentId = $tx->user_id ?? $hotel->owner_user_id;

        // Paiement en ligne : rattaché à l'hôtelier, sans session de caisse.
        Payment::create([
            'transaction_id' => $tx->id,
            'user_id' => $agentId,
            'created_by' => $agentId,
            'cashier_session_id' => null,
            'amount' => $amount,
            'status' => Payment::STATUS_COMPLETED,
            'payment_method' => 'fedapay',
            'reference' => 'ONLINE-'.$tx->id.'-'.$fedapayId,
            'payment_date' => now(),
            'currency' => $hotel->currency ?: 'XOF',
            'payment_gateway_response' => ['fedapay_transaction_id' => $fedapayId, 'source' => 'public_vitrine'],
            'notes' => "Acompte réglé en ligne (FedaPay #{$fedapayId}) depuis la vitrine.",
        ]);

        $tx->updatePaymentStatus();

        $tx->load(['room', 'customer']);
        $this->notifyDepositPaid($hotel, $tx, $amount);

        return redirect()->route('public.hotel.booking.confirmed', [$hotel->slug, $tx->id])
            ->with('payment_success', true);
    }
}
