<?php

namespace App\Http\Controllers;

use App\Mail\HotelCredentialsMail;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Inscription self-service (essai gratuit) : l'hôtelier choisit son plan et
 * renseigne ses infos. Un mot de passe est généré et envoyé par email
 * (ses identifiants administrateur). Il est connecté automatiquement puis
 * dirigé vers la personnalisation de son site.
 */
class RegisterHotelController extends Controller
{
    public function create(Request $request)
    {
        $plan = $request->query('plan');
        if (! array_key_exists($plan, config('plans.tiers'))) {
            $plan = config('plans.default', 'starter');
        }

        return view('auth.register-hotel', [
            'plans' => config('plans.tiers'),
            'selectedPlan' => $plan,
            'countries' => config('plans.countries'),
            'defaultCountry' => config('plans.default_country', 'BJ'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255', new \App\Rules\NoEmoji],
            'plan' => ['nullable', 'string', 'in:'.implode(',', array_keys(config('plans.tiers')))],
            'country' => ['nullable', 'string', 'in:'.implode(',', array_keys(config('plans.countries')))],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            // 'file' plutôt que 'image' : 'image' (getimagesize) rejette les SVG.
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'admin_name' => ['required', 'string', 'max:255', new \App\Rules\NoEmoji],
            'admin_email' => ['required', 'email', 'max:255'],
        ], [
            'logo.mimes' => 'Le logo doit être une image JPG, PNG, WEBP ou SVG.',
            'logo.max' => 'Le logo est trop lourd (4 Mo maximum). Réduisez sa taille et réessayez.',
            'logo.file' => "Le logo n'a pas pu être lu. Réessayez avec une image JPG ou PNG.",
        ], [
            'company_name' => "nom de l'établissement",
            'admin_name' => 'nom complet',
        ]);

        // Si l'email existe déjà (l'utilisateur revient en arrière pour changer de plan),
        // on met à jour l'hôtel avec les nouvelles infos et on le reconnecte.
        $existingUser = User::where('email', $data['admin_email'])->first();
        if ($existingUser) {
            $plan = $data['plan'] ?? config('plans.default', 'starter');
            $tier = config('plans.tiers')[$plan];
            $country = $data['country'] ?? config('plans.default_country', 'BJ');
            $currency = config('plans.countries.'.$country.'.currency', 'XOF');

            if ($existingUser->hotel) {
                $existingUser->hotel->update([
                    'name' => $data['company_name'],
                    'country' => $country,
                    'currency' => $currency,
                    'plan' => $plan,
                    'room_limit' => $tier['room_limit'],
                ]);

                if ($request->hasFile('logo')) {
                    $existingUser->hotel->update([
                        'logo' => $request->file('logo')->store('hotel-logos', 'public'),
                    ]);
                }
            }

            Auth::login($existingUser);

            return redirect()->route('onboarding.show')
                ->with('success', 'Compte existant détecté. Plan mis à jour : '.$tier['name'].'.')
                ->with('credentials_email', $existingUser->email);
        }

        $plan = $data['plan'] ?? config('plans.default', 'starter');
        $tier = config('plans.tiers')[$plan];
        $country = $data['country'] ?? config('plans.default_country', 'BJ');
        $currency = config('plans.countries.'.$country.'.currency', 'XOF');

        // Mot de passe généré : ce sont les identifiants envoyés par email
        $plainPassword = Str::password(10, true, true, false);

        [$hotel, $admin] = DB::transaction(function () use ($data, $request, $plan, $tier, $country, $currency, $plainPassword) {
            $hotel = Hotel::create([
                'name' => $data['company_name'],
                'slug' => $this->uniqueSlug($data['company_name']),
                'country' => $country,
                'currency' => $currency,
                'contact_phone' => $data['contact_phone'] ?? null,
                'contact_email' => $data['admin_email'],
                'plan' => $plan,
                'room_limit' => $tier['room_limit'],
                'is_active' => true,
                'subscription_ends_at' => now()->addDays(config('plans.trial_days', 14)),
            ]);

            if ($request->hasFile('logo')) {
                $hotel->update(['logo' => $request->file('logo')->store('hotel-logos', 'public')]);
            }

            $admin = User::create([
                'hotel_id' => $hotel->id,
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'phone' => $data['contact_phone'] ?? null,
                'role' => 'Admin',
                'password' => Hash::make($plainPassword),
                'random_key' => Str::random(60),
            ]);

            $hotel->update(['owner_user_id' => $admin->id]);

            // Historique : période d'essai gratuit
            $hotel->recordSubscription([
                'status' => 'trial',
                'amount' => 0,
                'starts_at' => now(),
                'ends_at' => $hotel->subscription_ends_at,
                'created_by' => $admin->id,
            ]);

            return [$hotel, $admin];
        });

        // Envoi des identifiants (tolérant aux pannes SMTP : ne casse pas l'inscription)
        try {
            Mail::to($admin->email)->send(new HotelCredentialsMail($hotel, $admin, $plainPassword));
        } catch (\Throwable $e) {
            Log::warning('Envoi email identifiants échoué: '.$e->getMessage());
        }

        Auth::login($admin);

        // Direction l'onboarding : choix des couleurs, du nom du site et du logo
        return redirect()->route('onboarding.show')
            ->with('success', 'Bienvenue ! Votre essai gratuit de '.config('plans.trial_days', 14).' jours a démarré.')
            ->with('credentials_email', $admin->email);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'hotel';
        $slug = $base;
        $i = 2;

        while (Hotel::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
