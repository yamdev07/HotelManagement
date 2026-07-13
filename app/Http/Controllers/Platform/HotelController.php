<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Mail\HotelCredentialsMail;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Dashboard Super-Admin : gestion de l'ensemble des hôtels de la plateforme.
 * Hors scope multi-tenant (le Super-Admin a hotel_id null).
 */
class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::orderBy('name')->get()->map(function (Hotel $hotel) {
            $hotel->users_count = User::where('hotel_id', $hotel->id)->count();
            $hotel->rooms_count = Room::forHotel($hotel->id)->count();
            $hotel->transactions_count = Transaction::forHotel($hotel->id)->count();

            return $hotel;
        });

        return view('platform.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('platform.hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'currency'       => ['nullable', 'string', 'max:10'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
            'contact_phone'  => ['nullable', 'string', 'max:50'],
            'subscription_ends_at' => ['nullable', 'date'],
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['nullable', 'string', 'min:6'],
        ]);

        // Mot de passe saisi, ou généré s'il est laissé vide
        $plainPassword = ! empty($data['admin_password'])
            ? $data['admin_password']
            : Str::password(10, true, true, false);

        [$hotel, $admin] = DB::transaction(function () use ($data, $plainPassword) {
            $hotel = Hotel::create([
                'name'                 => $data['name'],
                'slug'                 => $this->uniqueSlug($data['name']),
                'currency'             => $data['currency'] ?? 'CFA',
                'contact_email'        => $data['contact_email'] ?? $data['admin_email'],
                'contact_phone'        => $data['contact_phone'] ?? null,
                'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
                'is_active'            => true,
            ]);

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

        // Envoi des identifiants à l'email de l'administrateur (tolérant aux pannes SMTP)
        try {
            Mail::to($admin->email)->send(new HotelCredentialsMail($hotel, $admin, $plainPassword));
        } catch (\Throwable $e) {
            Log::warning('Envoi email identifiants (plateforme) échoué: '.$e->getMessage());
        }

        return redirect()->route('platform.hotels.index')
            ->with('success', "Hôtel « {$data['name']} » créé. Les identifiants ont été envoyés à {$admin->email}.");
    }

    public function edit(Hotel $hotel)
    {
        return view('platform.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'currency'             => ['nullable', 'string', 'max:10'],
            'contact_email'        => ['nullable', 'email', 'max:255'],
            'contact_phone'        => ['nullable', 'string', 'max:50'],
            'subscription_ends_at' => ['nullable', 'date'],
        ]);

        $hotel->update($data);

        return redirect()->route('platform.hotels.index')
            ->with('success', "Hôtel « {$hotel->name} » mis à jour.");
    }

    public function toggleActive(Hotel $hotel)
    {
        $hotel->update(['is_active' => ! $hotel->is_active]);

        $state = $hotel->is_active ? 'réactivé' : 'suspendu';

        return redirect()->route('platform.hotels.index')
            ->with('success', "Hôtel « {$hotel->name} » {$state}.");
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
