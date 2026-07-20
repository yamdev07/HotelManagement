<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Réglages de l'établissement par son administrateur (white-label) :
 * couleurs de marque, logo et informations de contact.
 */
class HotelSettingsController extends Controller
{
    public function edit()
    {
        $hotel = $this->currentHotel();

        return view('hotel.settings', compact('hotel'));
    }

    public function update(Request $request)
    {
        $hotel = $this->currentHotel();

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'primary_color'   => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'currency'        => ['nullable', 'string', 'max:10'],
            'contact_email'   => ['nullable', 'email', 'max:255'],
            'contact_phone'   => ['nullable', 'string', 'max:50'],
            'address'         => ['nullable', 'string', 'max:255'],
            'tagline'         => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'about_title'     => ['nullable', 'string', 'max:255'],
            'about_text'      => ['nullable', 'string', 'max:2000'],
            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'cover_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'services'              => ['nullable', 'array'],
            'services.*.icon'       => ['nullable', 'string', 'max:50'],
            'services.*.title'      => ['nullable', 'string', 'max:100'],
            'services.*.description' => ['nullable', 'string', 'max:255'],
            'socials'         => ['nullable', 'array'],
            'socials.facebook'  => ['nullable', 'string', 'max:255'],
            'socials.instagram' => ['nullable', 'string', 'max:255'],
            'socials.whatsapp'  => ['nullable', 'string', 'max:255'],
            'socials.website'   => ['nullable', 'string', 'max:255'],
        ]);

        // Services : on ne garde que les lignes avec un titre
        $data['services'] = collect($request->input('services', []))
            ->filter(fn ($s) => ! empty($s['title']))
            ->map(fn ($s) => [
                'icon'        => $s['icon'] ?? 'fa-star',
                'title'       => $s['title'],
                'description' => $s['description'] ?? '',
            ])
            ->values()
            ->all();

        // Réseaux sociaux : on ne garde que les non vides
        $data['socials'] = collect($request->input('socials', []))
            ->filter(fn ($url) => ! empty($url))
            ->all();

        if ($request->hasFile('logo')) {
            if ($hotel->logo) {
                Storage::disk('public')->delete($hotel->logo);
            }
            $data['logo'] = $request->file('logo')->store('hotel-logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($hotel->cover_image) {
                Storage::disk('public')->delete($hotel->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('hotel-covers', 'public');
        }

        // Cases à cocher : absentes du payload = false
        foreach (['show_rooms', 'show_restaurant', 'show_services', 'show_contact'] as $toggle) {
            $data[$toggle] = $request->boolean($toggle);
        }

        $hotel->update($data);

        return redirect()->route('hotel.settings.edit')
            ->with('success', 'Les informations de votre établissement ont été mises à jour.');
    }

    /**
     * Clôture définitive de l'établissement par son propriétaire (issue #191).
     * Réservé au PROPRIÉTAIRE (ni Manager, ni co-admin), avec confirmation par
     * mot de passe et saisie explicite. Supprime l'hôtel et toutes ses données.
     */
    public function destroyAccount(Request $request)
    {
        $hotel = $this->currentHotel();
        $user  = auth()->user();

        abort_unless(
            $hotel->owner_user_id === $user->id,
            403,
            "Seul le propriétaire de l'établissement peut le supprimer."
        );

        $request->validate([
            'password'     => ['required', 'current_password'],
            'confirmation' => ['required', 'in:SUPPRIMER'],
        ], [
            'password.required'         => 'Veuillez saisir votre mot de passe pour confirmer.',
            'password.current_password' => 'Mot de passe incorrect.',
            'confirmation.in'           => 'Tapez SUPPRIMER (en majuscules) pour confirmer la suppression.',
        ]);

        $name = $hotel->name;

        DB::transaction(function () use ($hotel) {
            User::where('hotel_id', $hotel->id)->delete();
            $hotel->subscriptions()->delete();
            $hotel->forceDelete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')
            ->with('success', "Votre établissement « {$name} » et toutes ses données ont été supprimés. À bientôt.");
    }

    private function currentHotel(): Hotel
    {
        $hotel = auth()->user()->hotel;

        abort_unless($hotel !== null, 404, 'Aucun établissement associé à ce compte.');

        return $hotel;
    }
}
