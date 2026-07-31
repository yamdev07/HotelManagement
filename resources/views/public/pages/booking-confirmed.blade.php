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

            <div class="cf-next">
                <strong><i class="fas fa-circle-info"></i> Prochaine étape</strong><br>
                L'hôtel va vous contacter pour confirmer et régler l'acompte. Le <strong>paiement en ligne sécurisé</strong> arrive très bientôt.
            </div>

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
