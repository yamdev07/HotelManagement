<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Inscription self-service (essai gratuit) : un nouvel hôtelier crée son
 * établissement et son compte administrateur, puis est connecté automatiquement.
 */
class RegisterHotelController extends Controller
{
    /** Durée de l'essai gratuit, en jours. */
    private const TRIAL_DAYS = 14;

    public function create()
    {
        return view('auth.register-hotel');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'   => ['required', 'string', 'max:255'],
            'primary_color'  => ['nullable', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
            'logo'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin = DB::transaction(function () use ($data, $request) {
            $hotel = Hotel::create([
                'name'                 => $data['company_name'],
                'slug'                 => $this->uniqueSlug($data['company_name']),
                'currency'             => 'CFA',
                'primary_color'        => $data['primary_color'] ?? '#4f46e5',
                'contact_phone'        => $data['contact_phone'] ?? null,
                'contact_email'        => $data['admin_email'],
                'is_active'            => true,
                'subscription_ends_at' => now()->addDays(self::TRIAL_DAYS),
            ]);

            if ($request->hasFile('logo')) {
                $hotel->update(['logo' => $request->file('logo')->store('hotel-logos', 'public')]);
            }

            $admin = User::create([
                'hotel_id'   => $hotel->id,
                'name'       => $data['admin_name'],
                'email'      => $data['admin_email'],
                'role'       => 'Admin',
                'password'   => Hash::make($data['admin_password']),
                'random_key' => Str::random(60),
            ]);

            $hotel->update(['owner_user_id' => $admin->id]);

            return $admin;
        });

        Auth::login($admin);

        return redirect()->route('hotel.settings.edit')
            ->with('success', 'Bienvenue ! Votre essai gratuit de '.self::TRIAL_DAYS.' jours a démarré. Personnalisez votre établissement ci-dessous.');
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
