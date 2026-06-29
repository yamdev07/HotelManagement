<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Onboarding : personnalisation initiale du site juste après l'inscription
 * (couleurs, nom du site, logo). Une fois validé, tout est appliqué à l'hôtel.
 */
class OnboardingController extends Controller
{
    public function show()
    {
        $hotel = $this->currentHotel();

        return view('onboarding.wizard', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->currentHotel();

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'tagline'         => ['nullable', 'string', 'max:255'],
            'primary_color'   => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($hotel->logo) {
                Storage::disk('public')->delete($hotel->logo);
            }
            $data['logo'] = $request->file('logo')->store('hotel-logos', 'public');
        }

        $data['onboarding_completed_at'] = now();

        $hotel->update($data);

        return redirect('/home')
            ->with('success', 'Votre site est configuré ! Bienvenue dans votre espace.');
    }

    private function currentHotel(): Hotel
    {
        $hotel = auth()->user()->hotel;

        abort_unless($hotel !== null, 404, 'Aucun établissement associé à ce compte.');

        return $hotel;
    }
}
