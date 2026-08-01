@php $cover = $hotel->coverOrDefault(); @endphp
@push('head')
<style>
    /* Barre de réservation dans le hero */
    .hero-search {
        margin: 2.4rem auto 0; max-width: 860px;
        background: rgba(255,255,255,.14); backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,.28); border-radius: 16px;
        padding: 14px; display: grid; grid-template-columns: 1fr 1fr .9fr auto; gap: 12px; align-items: end;
        box-shadow: 0 30px 70px -30px rgba(0,0,0,.55);
    }
    .hero-search .hs-field { text-align: left; }
    .hero-search label { display:block; font-size:.68rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:#fff; opacity:.9; margin-bottom:6px; }
    .hero-search input, .hero-search select {
        width:100%; padding:12px 13px; border:0; border-radius:10px; font-family:var(--sans);
        font-size:.95rem; color:var(--ink); background:#fff;
    }
    .hero-search input:focus, .hero-search select:focus { outline:2px solid var(--c); }
    .hs-btn {
        border:0; border-radius:10px; background:var(--c); color:#fff; padding:12px 22px; height:46px;
        font-weight:600; letter-spacing:.03em; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:8px; transition:.25s;
    }
    .hs-btn:hover { filter:brightness(1.08); transform:translateY(-1px); }
    @media (max-width:780px){ .hero-search { grid-template-columns:1fr 1fr; } .hs-btn{ grid-column:1/-1; justify-content:center; } }
    @media (max-width:480px){ .hero-search { grid-template-columns:1fr; } }
    .hero-quicklinks { margin-top:1.6rem; display:flex; gap:1.6rem; justify-content:center; flex-wrap:wrap; }
    .hero-quicklinks a { color:rgba(255,255,255,.9); font-size:.9rem; letter-spacing:.04em; border-bottom:1px solid transparent; padding-bottom:2px; transition:.25s; }
    .hero-quicklinks a:hover { color:#fff; border-color:rgba(255,255,255,.7); }
</style>
@endpush

<section class="hero-lux" id="accueil">
    <div class="hero-bg" style="background-image: url('{{ $cover }}');"></div>
    <div class="hero-overlay" style="background:linear-gradient(180deg, rgba(0,0,0,.38) 0%, rgba(0,0,0,.22) 32%, color-mix(in srgb, var(--d) 78%, rgba(0,0,0,.72)) 100%);"></div>

    <div class="hero-content" style="max-width:960px;">
        <div class="eyebrow" data-aos="fade-down" style="color:#fff;opacity:.85;">
            @if($hotel->address)<i class="fas fa-location-dot me-2"></i>{{ $hotel->address }}@else Bienvenue @endif
        </div>
        <h1 class="display-serif" data-aos="fade-up" data-aos-delay="100">{{ $hotel->name }}</h1>
        <div class="hero-divider" data-aos="fade" data-aos-delay="250"></div>
        @if ($hotel->tagline)
            <p class="lead" data-aos="fade-up" data-aos-delay="300" style="font-weight:300;font-size:1.25rem;max-width:600px;margin:0 auto;">{{ $hotel->tagline }}</p>
        @endif

        {{-- Recherche de disponibilité (réserver en direct) --}}
        @if ($hotel->show_rooms)
        <form class="hero-search" method="GET" action="{{ route('public.hotel.availability', $hotel->slug) }}" data-aos="fade-up" data-aos-delay="380">
            <div class="hs-field">
                <label><i class="fas fa-calendar-day me-1"></i> Arrivée</label>
                <input type="date" name="check_in" min="{{ now()->format('Y-m-d') }}" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="hs-field">
                <label><i class="fas fa-calendar-check me-1"></i> Départ</label>
                <input type="date" name="check_out" min="{{ now()->addDay()->format('Y-m-d') }}" value="{{ now()->addDay()->format('Y-m-d') }}" required>
            </div>
            <div class="hs-field">
                <label><i class="fas fa-user-group me-1"></i> Voyageurs</label>
                <select name="guests">
                    @for ($i = 1; $i <= 8; $i++)<option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'personnes' : 'personne' }}</option>@endfor
                </select>
            </div>
            <button type="submit" class="hs-btn"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        @endif

        <div class="hero-quicklinks" data-aos="fade" data-aos-delay="480">
            @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}">Voir les chambres</a>@endif
            <a href="#apropos">Découvrir l'hôtel</a>
            @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}">Nous contacter</a>@endif
        </div>
    </div>

    <a href="#apropos" class="scroll-ind"><i class="fas fa-chevron-down fa-lg"></i></a>
</section>
