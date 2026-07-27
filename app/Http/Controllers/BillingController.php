<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Services\FedaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Paiement en ligne des abonnements (côté hôtelier) via FedaPay.
 *
 * Accessible même quand l'abonnement a expiré : c'est ici que l'hôtelier
 * régularise pour réactiver son espace (voir EnsureHotelActive).
 */
class BillingController extends Controller
{
    private const MONTHS = [1, 3, 6, 12];

    private const SESSION_KEY = 'billing.pending';

    public function __construct(private FedaPayService $fedapay)
    {
        // La facturation/abonnement est réservée au propriétaire (Admin) et au Super.
        // La Direction (Manager) a accès à tout le reste, mais pas à l'argent de l'abonnement.
        $this->middleware(function ($request, $next) {
            abort_if(
                $request->user()?->roleEnum === \App\Enums\UserRole::Manager,
                403,
                "La gestion de l'abonnement est réservée à l'administrateur de l'établissement."
            );

            return $next($request);
        });
    }

    /** Page « Mon abonnement » : état courant + formules payables. */
    public function show(Request $request)
    {
        $hotel = $request->user()->hotel;
        abort_unless($hotel, 403);

        return view('billing.show', [
            'hotel' => $hotel,
            'tiers' => config('plans.tiers'),
            'months' => self::MONTHS,
            'configured' => $this->fedapay->isConfigured(),
            // Suspendu manuellement par l'admin (≠ simple expiration) : paiement bloqué.
            'suspendedByAdmin' => ! $hotel->is_active,
        ]);
    }

    /** Crée la transaction FedaPay et redirige vers la page de paiement. */
    public function checkout(Request $request)
    {
        $hotel = $request->user()->hotel;
        abort_unless($hotel, 403);

        $data = $request->validate([
            'plan' => ['required', 'string', 'in:'.implode(',', array_keys(config('plans.tiers')))],
            'months' => ['required', 'integer', 'in:'.implode(',', self::MONTHS)],
        ]);

        // Un compte suspendu par l'admin ne se réactive pas en payant.
        if (! $hotel->is_active) {
            return back()->with('error', __('flash.billing_suspended'));
        }

        if (! $this->fedapay->isConfigured()) {
            return back()->with('error', __('flash.billing_not_configured'));
        }

        $months = (int) $data['months'];
        $plan = $data['plan'];
        $unit = Hotel::priceFor($plan, $hotel->country);
        $amount = $unit * $months;
        $currency = $hotel->displayCurrency();

        $user = $request->user();

        try {
            $checkout = $this->fedapay->createCheckout(
                hotel: $hotel,
                plan: $plan,
                amount: $amount,
                currency: $currency,
                description: config('app.name', 'checkinHub').' · '.config('plans.tiers.'.$plan.'.name').' × '.$months.' mois ('.$hotel->name.')',
                callbackUrl: route('billing.callback'),
                customerEmail: $hotel->contact_email ?: $user->email,
                customerName: $user->name,
            );
        } catch (\Throwable $e) {
            Log::error('FedaPay checkout: '.$e->getMessage());

            return back()->with('error', __('flash.billing_error'));
        }

        $request->session()->put(self::SESSION_KEY, [
            'transaction_id' => $checkout['transaction_id'],
            'plan' => $plan,
            'months' => $months,
            'amount' => $amount,
            'hotel_id' => $hotel->id,
        ]);

        return redirect()->away($checkout['url']);
    }

    /** Retour depuis FedaPay : on vérifie le paiement côté serveur. */
    public function callback(Request $request)
    {
        $hotel = $request->user()->hotel;
        abort_unless($hotel, 403);

        $pending = $request->session()->pull(self::SESSION_KEY);

        if (! $pending || (int) ($pending['hotel_id'] ?? 0) !== $hotel->id) {
            return redirect()->route('billing.show')
                ->with('error', __('flash.billing_session_not_found'));
        }

        $transactionId = (int) $pending['transaction_id'];

        try {
            $approved = $this->fedapay->isApproved($transactionId);
        } catch (\Throwable $e) {
            Log::error('FedaPay verify: '.$e->getMessage());
            $approved = false;
        }

        if (! $approved) {
            return redirect()->route('billing.show')
                ->with('error', __('flash.billing_payment_failed'));
        }

        $sub = $hotel->applyRenewal(
            months: (int) $pending['months'],
            amount: (float) $pending['amount'],
            plan: $pending['plan'],
            extra: [
                'currency' => $hotel->displayCurrency(),
                'status' => 'active',
            ],
        );

        return redirect()->route('billing.show')->with(
            'success',
            __('flash.billing_confirmed', ['date' => $sub->ends_at->format('d/m/Y')])
        );
    }
}
