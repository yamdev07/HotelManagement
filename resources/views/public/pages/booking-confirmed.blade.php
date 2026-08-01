@extends('public.layout')
@section('title', 'Réservation confirmée')

@push('head')
<style>
    .cf-wrap { max-width: 640px; margin: 40px auto 0; text-align:center; }
    .cf-ic { width:84px; height:84px; border-radius:50%; margin:0 auto 18px; display:grid; place-items:center; font-size:2.1rem;
             background:color-mix(in srgb, var(--c) 14%, #fff); color:var(--c); }
    .cf-ref { display:inline-block; margin:10px 0 4px; font-family:'DM Mono',monospace; font-weight:700; background:#f3f4f8; color:#374151; padding:6px 16px; border-radius:20px; letter-spacing:.05em; }
    .cf-card { text-align:left; background:#fff; border:1px solid #ececf2; border-radius:18px; box-shadow:0 18px 48px -30px rgba(20,30,50,.35); margin-top:24px; overflow:hidden; }
    .cf-card .hd { padding:15px 20px; border-bottom:1px solid #f1f2f6; font-weight:800; color:#1f2733; }
    .cf-body { padding:18px 20px; }
    .cf-line { display:flex; justify-content:space-between; gap:12px; padding:7px 0; font-size:.92rem; color:#4b5563; }
    .cf-line strong { color:#1f2733; }
    .cf-total { margin-top:8px; padding-top:12px; border-top:1px dashed #e5e7eb; }
    .cf-next { margin-top:22px; background:color-mix(in srgb, var(--c) 7%, #fff); border:1px solid color-mix(in srgb, var(--c) 20%, #fff); border-radius:14px; padding:16px 18px; text-align:left; color:#374151; font-size:.9rem; }
    .cf-actions { margin-top:20px; display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
    .cf-btn { display:inline-flex; align-items:center; gap:8px; border-radius:12px; padding:12px 20px; font-weight:700; text-decoration:none; font-size:.92rem; }
    .cf-btn.wa { background:#25d366; color:#fff; }
    .cf-btn.ghost { background:#fff; border:1.5px solid #e3e6ee; color:#374151; }
    .cf-btn:hover { filter:brightness(1.04); }

    /* Paiement en ligne (Lot 3) */
    .cf-pay { margin-top:22px; background:#fff; border:1px solid #ececf2; border-radius:16px; box-shadow:0 18px 48px -30px rgba(20,30,50,.35); padding:20px; text-align:center; }
    .cf-pay h3 { margin:0 0 4px; font-size:1.05rem; color:#1f2733; }
    .cf-pay p { margin:0 0 14px; color:#6b7280; font-size:.9rem; }
    .cf-pay .amt { font-size:1.5rem; font-weight:900; color:var(--c); margin:2px 0 12px; }
    .cf-paybtn { display:inline-flex; align-items:center; gap:9px; background:var(--c); color:#fff; border:0; border-radius:12px; padding:14px 26px; font-weight:800; font-size:1rem; cursor:pointer; text-decoration:none; }
    .cf-paybtn:hover { filter:brightness(1.06); color:#fff; }
    .cf-secure { margin-top:10px; font-size:.76rem; color:#9aa1ad; }
    .cf-paid { margin-top:22px; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:14px; padding:16px 18px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:10px; }
    .cf-flash { border-radius:12px; padding:12px 16px; margin:18px 0 0; font-size:.9rem; }
    .cf-flash.err { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }
    .cf-flash.ok { background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; }
</style>
@endpush

@section('content')
    @php
        $waRaw = $hotel->socials['whatsapp'] ?? $hotel->contact_phone ?? '';
        $wa = preg_replace('/\D+/', '', (string) $waRaw);
        $ref = 'RES-' . str_pad((string) $tx->id, 5, '0', STR_PAD_LEFT);
        $ci = \Carbon\Carbon::parse($tx->check_in);
        $co = \Carbon\Carbon::parse($tx->check_out);
    @endphp

    <section class="section" style="padding-top:120px;">
        <div class="container cf-wrap">
            <div class="cf-ic"><i class="fas fa-check"></i></div>
            <h1 class="display-serif" style="font-size:clamp(1.9rem,5vw,2.7rem);">Réservation enregistrée !</h1>
            <p style="color:#6b7280; max-width:440px; margin:8px auto 0;">
                Merci {{ $tx->customer->name ?? '' }}. Votre demande a bien été transmise à {{ $hotel->name }}.
            </p>
            <div class="cf-ref">{{ $ref }}</div>

            <div class="cf-card">
                <div class="hd">Détails</div>
                <div class="cf-body">
                    <div class="cf-line"><span>Chambre</span> <strong>{{ $tx->room->type->name ?? 'Chambre' }} · n°{{ $tx->room->number ?? '—' }}</strong></div>
                    <div class="cf-line"><span>Arrivée</span> <strong>{{ $ci->translatedFormat('D d M Y') }}</strong></div>
                    <div class="cf-line"><span>Départ</span> <strong>{{ $co->translatedFormat('D d M Y') }}</strong></div>
                    <div class="cf-line"><span>Voyageurs</span> <strong>{{ $tx->person_count }}</strong></div>
                    <div class="cf-line cf-total"><span>Total du séjour</span> <strong>{{ number_format($tx->total_price, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                    <div class="cf-line"><span>Acompte suggéré (15 %)</span> <strong style="color:var(--c)">{{ number_format($deposit, 0, ',', ' ') }} {{ $hotel->currency }}</strong></div>
                </div>
            </div>

            @if (session('payment_success'))
                <div class="cf-flash ok"><i class="fas fa-circle-check"></i> Paiement reçu, merci ! Votre acompte a bien été enregistré.</div>
            @endif
            @if (session('payment_error'))
                <div class="cf-flash err"><i class="fas fa-triangle-exclamation"></i> {{ session('payment_error') }}</div>
            @endif

            @if ($depositPaid)
                {{-- Acompte déjà réglé --}}
                <div class="cf-paid">
                    <i class="fas fa-circle-check"></i>
                    Acompte de {{ number_format($deposit, 0, ',', ' ') }} {{ $hotel->currency }} payé · réservation confirmée
                </div>
                <div class="cf-next">
                    <strong><i class="fas fa-circle-info"></i> Prochaine étape</strong><br>
                    Le solde ({{ number_format(max(0, $tx->total_price - $deposit), 0, ',', ' ') }} {{ $hotel->currency }}) se règle à l'arrivée. À bientôt !
                </div>
            @elseif ($canPayOnline)
                {{-- Paiement en ligne de l'acompte --}}
                <div class="cf-pay">
                    <h3><i class="fas fa-lock" style="color:var(--c)"></i> Sécurisez votre réservation</h3>
                    <p>Réglez l'acompte en ligne pour confirmer immédiatement votre chambre.</p>
                    <div class="amt">{{ number_format($deposit, 0, ',', ' ') }} {{ $hotel->currency }}</div>
                    <form method="POST" action="{{ route('public.hotel.payment.pay', [$hotel->slug, $tx->id]) }}">
                        @csrf
                        <button type="submit" class="cf-paybtn"><i class="fas fa-credit-card"></i> Payer l'acompte</button>
                    </form>
                    <div class="cf-secure"><i class="fas fa-shield-halved"></i> Paiement sécurisé par FedaPay · carte & Mobile Money</div>
                </div>
                <div class="cf-next">
                    Le solde ({{ number_format(max(0, $tx->total_price - $deposit), 0, ',', ' ') }} {{ $hotel->currency }}) se règle à l'arrivée.
                </div>
            @else
                {{-- Repli : pas de paiement en ligne activé --}}
                <div class="cf-next">
                    <strong><i class="fas fa-circle-info"></i> Prochaine étape</strong><br>
                    L'hôtel va vous contacter pour confirmer et régler l'acompte de <strong>{{ number_format($deposit, 0, ',', ' ') }} {{ $hotel->currency }}</strong>.
                </div>
            @endif

            @if (! $tx->preCheckinDone())
                <a href="{{ route('public.checkin.show', $tx->checkinToken()) }}"
                   style="display:flex;align-items:center;gap:14px;margin-top:22px;padding:16px 18px;border-radius:14px;text-decoration:none;background:color-mix(in srgb, var(--c) 10%, #fff);border:1px solid color-mix(in srgb, var(--c) 30%, #fff);">
                    <span style="width:44px;height:44px;border-radius:11px;background:var(--c);color:#fff;display:grid;place-items:center;font-size:1.15rem;flex:none;"><i class="fas fa-id-card"></i></span>
                    <span style="flex:1;text-align:left;">
                        <strong style="display:block;color:#1f2733;">Pré-enregistrez-vous en 1 minute</strong>
                        <small style="color:#6b7280;">Renseignez vos infos maintenant → check-in express à l'arrivée</small>
                    </span>
                    <i class="fas fa-arrow-right" style="color:var(--c);"></i>
                </a>
            @endif

            <div class="cf-actions">
                @if ($wa)
                    <a class="cf-btn wa" target="_blank" rel="noopener"
                       href="https://wa.me/{{ $wa }}?text={{ urlencode("Bonjour {$hotel->name}, je confirme ma réservation {$ref} (chambre ".($tx->room->number ?? '')." du ".$ci->format('d/m/Y')." au ".$co->format('d/m/Y').").") }}">
                        <i class="fab fa-whatsapp"></i> Contacter l'hôtel sur WhatsApp
                    </a>
                @endif
                <a class="cf-btn ghost" href="{{ route('public.hotel', $hotel->slug) }}"><i class="fas fa-house"></i> Retour au site</a>
            </div>
        </div>
    </section>
@endsection
