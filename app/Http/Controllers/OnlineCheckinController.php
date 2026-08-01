<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Transaction;
use App\Rules\SafeName;
use App\Support\TenantManager;
use Illuminate\Http\Request;

/**
 * Pré-check-in en ligne : le voyageur complète ses informations avant l'arrivée
 * via un lien/QR (accès par jeton, sans compte). Accélère l'accueil.
 */
class OnlineCheckinController extends Controller
{
    /** Résout la réservation par jeton et fixe le tenant sur son hôtel. */
    private function resolve(string $token): array
    {
        // Contexte public sans tenant : le HotelScope ne filtre pas.
        $tx = Transaction::where('checkin_token', $token)->firstOrFail();
        app(TenantManager::class)->setHotelId($tx->hotel_id);
        $tx->load(['room.type', 'customer']);
        $hotel = Hotel::find($tx->hotel_id);

        abort_unless($hotel, 404);

        return [$tx, $hotel];
    }

    public function show(string $token)
    {
        [$tx, $hotel] = $this->resolve($token);

        return view('public.pages.checkin', compact('tx', 'hotel'));
    }

    public function store(Request $request, string $token)
    {
        [$tx, $hotel] = $this->resolve($token);

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255', new SafeName],
            'email'   => ['nullable', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'regex:/^[0-9+\s().\-]{6,20}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'id_type' => ['required', 'in:CNI,Passeport,Permis,Autre'],
            'id_number'     => ['required', 'string', 'max:60'],
            'arrival_time'  => ['nullable', 'string', 'max:20'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required'  => 'Votre nom est requis.',
            'phone.required' => 'Votre téléphone est requis.',
            'phone.regex'    => 'Téléphone invalide.',
            'id_type.required'   => 'Indiquez votre type de pièce.',
            'id_number.required' => 'Le numéro de pièce est requis.',
        ]);

        // Met à jour la fiche client (scopée à l'hôtel).
        if ($tx->customer) {
            $tx->customer->update(array_filter([
                'name'    => $data['name'],
                'email'   => $data['email'] ?? $tx->customer->email,
                'phone'   => $data['phone'],
                'address' => $data['address'] ?? $tx->customer->address,
            ]));
        }

        $tx->update([
            'pre_checkin' => [
                'address' => $data['address'] ?? null,
                'id_type' => $data['id_type'],
                'id_number' => $data['id_number'],
                'arrival_time' => $data['arrival_time'] ?? null,
                'special_requests' => $data['special_requests'] ?? null,
            ],
            // Renseigne aussi les colonnes de check-in -> pièce d'identité pré-remplie
            // à l'accueil (le "NIC/ID" de la fiche se remplit).
            'id_type' => $data['id_type'],
            'id_number' => $data['id_number'],
            'special_requests' => $data['special_requests'] ?? $tx->special_requests,
            'pre_checkin_completed_at' => now(),
        ]);

        return redirect()->route('public.checkin.show', $token)->with('checkin_done', true);
    }
}
