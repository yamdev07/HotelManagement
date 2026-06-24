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
            'plans'        => config('plans.tiers'),
            'selectedPlan' => $plan,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'  => ['required', 'string', 'max:255'],
            'plan'          => ['nullable', 'string', 'in:'.implode(',', array_keys(config('plans.tiers')))],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'logo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'admin_name'    => ['required', 'string', 'max:255'],
            'admin_email'   => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $plan = $data['plan'] ?? config('plans.default', 'starter');
        $tier = config('plans.tiers')[$plan];

        // Mot de passe généré : ce sont les identifiants envoyés par email
        $plainPassword = Str::password(10, true, true, false);

        [$hotel, $admin] = DB::transaction(function () use ($data, $request, $plan, $tier, $plainPassword) {
            $hotel = Hotel::create([
                'name'                 => $data['company_name'],
                'slug'                 => $this->uniqueSlug($data['company_name']),
                'currency'             => $tier['currency'] ?? 'CFA',
                'contact_phone'        => $data['contact_phone'] ?? null,
                'contact_email'        => $data['admin_email'],
                'plan'                 => $plan,
                'room_limit'           => $tier['room_limit'],
                'is_active'            => true,
                'subscription_ends_at' => now()->addDays(config('plans.trial_days', 14)),
            ]);

            if ($request->hasFile('logo')) {
                $hotel->update(['logo' => $request->file('logo')->store('hotel-logos', 'public')]);
            }

            $admin = User::create([
                'hotel_id'   => $hotel->id,
                'name'       => $data['admin_name'],
                'email'      => $data['admin_email'],
                'role'       => 'Admin',
                'password'   => Hash::make($plainPassword),
                'random_key' => Str::random(60),
            ]);

            $hotel->update(['owner_user_id' => $admin->id]);

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
            ->with('success', 'Bienvenue ! Votre essai gratuit de '.config('plans.trial_days', 14).' jours a démarré. Vos identifiants vous ont été envoyés par email.');
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
