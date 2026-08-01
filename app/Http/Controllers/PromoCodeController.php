<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Gestion des codes promo (Admin/Direction). Tout est scopé à l'hôtel courant.
 */
class PromoCodeController extends Controller
{
    public function index()
    {
        $codes = PromoCode::orderByDesc('is_active')->orderByDesc('created_at')->get();

        return view('promo.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $hotel = auth()->user()->hotel;
        abort_unless($hotel, 403);

        $request->merge(['code' => PromoCode::normalize($request->input('code'))]);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'regex:/^[A-Z0-9\-]{3,40}$/',
                Rule::unique('promo_codes', 'code')->where('hotel_id', $hotel->id)],
            'type' => ['required', Rule::in(['percent', 'fixed'])],
            'value' => ['required', 'numeric', 'gt:0', $request->input('type') === 'percent' ? 'max:100' : 'max:99999999'],
            'min_nights' => ['nullable', 'integer', 'min:1', 'max:60'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'max_uses' => ['nullable', 'integer', 'min:1', 'max:1000000'],
        ], [
            'code.regex' => 'Le code doit contenir 3 à 40 caractères (lettres, chiffres, tirets).',
            'code.unique' => 'Ce code existe déjà pour votre établissement.',
            'value.max' => 'Une réduction en pourcentage ne peut pas dépasser 100.',
        ]);

        PromoCode::create([
            'hotel_id' => $hotel->id,
            'code' => $data['code'],
            'type' => $data['type'],
            'value' => $data['value'],
            'min_nights' => $data['min_nights'] ?? 1,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'max_uses' => $data['max_uses'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Code promo « '.$data['code'].' » créé.');
    }

    public function toggle(PromoCode $promoCode)
    {
        $promoCode->update(['is_active' => ! $promoCode->is_active]);

        return back()->with('success', $promoCode->is_active ? 'Code activé.' : 'Code désactivé.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return back()->with('success', 'Code promo supprimé.');
    }
}
