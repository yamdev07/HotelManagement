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
        // Les plus récemment inscrits en premier
        $hotels = Hotel::with('owner')->orderByDesc('created_at')->get()->map(function (Hotel $hotel) {
            $hotel->users_count = User::where('hotel_id', $hotel->id)->count();
            $hotel->rooms_count = Room::forHotel($hotel->id)->count();
            $hotel->transactions_count = Transaction::forHotel($hotel->id)->count();

            return $hotel;
        });

        $active = $hotels->filter->hasActiveAccess();

        $summary = [
            'total' => $hotels->count(),
            'active' => $active->count(),
            'expired' => $hotels->count() - $active->count(),
            'this_month' => Hotel::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count(),
            'revenue' => (float) \App\Models\Subscription::sum('amount'),
            'renewals' => (int) \App\Models\Subscription::where('is_renewal', true)->count(),
            'mrr' => (float) $active->sum(fn (Hotel $h) => $h->monthlyPrice()),
        ];

        // Inscriptions des 6 derniers mois (pour le graphe)
        $chart = collect(range(5, 0))->map(function ($i) {
            $d = now()->startOfMonth()->subMonths($i);

            return [
                'label' => ucfirst($d->translatedFormat('M Y')),
                'count' => Hotel::whereYear('created_at', $d->year)->whereMonth('created_at', $d->month)->count(),
            ];
        })->values();

        return view('platform.hotels.index', compact('hotels', 'summary', 'chart'));
    }

    public function create()
    {
        return view('platform.hotels.create');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['owner', 'subscriptions.createdBy']);
        $hotel->users_count = User::where('hotel_id', $hotel->id)->count();
        $hotel->rooms_count = Room::forHotel($hotel->id)->count();
        $hotel->transactions_count = Transaction::forHotel($hotel->id)->count();

        return view('platform.hotels.show', compact('hotel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'subscription_ends_at' => ['nullable', 'date'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['nullable', 'string', 'min:6'],
        ]);

        // Mot de passe saisi, ou généré s'il est laissé vide
        $plainPassword = ! empty($data['admin_password'])
            ? $data['admin_password']
            : Str::password(10, true, true, false);

        [$hotel, $admin] = DB::transaction(function () use ($data, $plainPassword) {
            $hotel = Hotel::create([
                'name' => $data['name'],
                'slug' => $this->uniqueSlug($data['name']),
                'currency' => $data['currency'] ?? 'CFA',
                'contact_email' => $data['contact_email'] ?? $data['admin_email'],
                'contact_phone' => $data['contact_phone'] ?? null,
                'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
                'is_active' => true,
            ]);

            $admin = User::create([
                'hotel_id' => $hotel->id,
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'role' => 'Admin',
                'password' => Hash::make($plainPassword),
                'random_key' => Str::random(60),
            ]);

            $hotel->update(['owner_user_id' => $admin->id]);

            // Historique : abonnement initial
            $hotel->recordSubscription([
                'status' => $hotel->subscription_ends_at ? 'active' : 'trial',
                'amount' => $hotel->subscription_ends_at ? $hotel->monthlyPrice() : 0,
                'starts_at' => now(),
                'ends_at' => $hotel->subscription_ends_at,
            ]);

            return [$hotel, $admin];
        });

        // Envoi des identifiants à l'email de l'administrateur (tolérant aux pannes SMTP)
        try {
            Mail::to($admin->email)->send(new HotelCredentialsMail($hotel, $admin, $plainPassword));
        } catch (\Throwable $e) {
            Log::warning('Envoi email identifiants (plateforme) échoué: '.$e->getMessage());
        }

        return redirect()->route('platform.hotels.index')
            ->with('success', __('flash.register_existing_account', ['plan' => $data['name']]));
    }

    public function edit(Hotel $hotel)
    {
        return view('platform.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'subscription_ends_at' => ['nullable', 'date'],
        ]);

        $hotel->update($data);

        return redirect()->route('platform.hotels.index')
            ->with('success', __('flash.hotel_settings_updated'));
    }

    public function toggleActive(Request $request, Hotel $hotel)
    {
        $nowActive = ! $hotel->is_active;

        $hotel->update([
            'is_active' => $nowActive,
            // On mémorise la raison à la suspension, on l'efface à la réactivation
            'suspension_reason' => $nowActive ? null : ($request->input('reason') ?: 'Suspension par l\'administrateur de la plateforme'),
        ]);

        $state = $nowActive ? 'réactivé' : 'suspendu';

        return redirect()->back()
            ->with('success', __('flash.hotel_settings_updated'));
    }

    /**
     * Renouvellement : prolonge l'abonnement d'un mois, réactive l'hôtel
     * et enregistre la période dans l'historique (réabonnement).
     */
    public function renew(Request $request, Hotel $hotel)
    {
        $months = (int) $request->input('months', 1) ?: 1;

        // On repart de la date de fin si elle est future, sinon d'aujourd'hui
        $start = ($hotel->subscription_ends_at && $hotel->subscription_ends_at->isFuture())
            ? $hotel->subscription_ends_at->copy()
            : now();
        $newEnd = $start->copy()->addMonths($months);

        $hotel->update(['subscription_ends_at' => $newEnd, 'is_active' => true]);

        $hotel->recordSubscription([
            'status' => 'active',
            'is_renewal' => true,
            'amount' => $hotel->monthlyPrice() * $months,
            'starts_at' => $start,
            'ends_at' => $newEnd,
        ]);

        return redirect()->route('platform.hotels.show', $hotel)
            ->with('success', __('flash.billing_confirmed', ['date' => $newEnd->format('d/m/Y')]));
    }

    /**
     * Supprime définitivement un hôtel et ses utilisateurs.
     */
    public function destroy(Hotel $hotel)
    {
        $name = $hotel->name;

        DB::transaction(function () use ($hotel) {
            User::where('hotel_id', $hotel->id)->delete();
            $hotel->subscriptions()->delete();
            $hotel->forceDelete();
        });

        return redirect()->route('platform.hotels.index')
            ->with('success', __('flash.hotel_settings_updated'));
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
