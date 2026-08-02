@php $cover = $hotel->coverOrDefault(); @endphp
@push('head')
<style>
    .hero-cine { height:100vh; min-height:660px; position:relative; display:flex; align-items:center; justify-content:center; text-align:center; color:#fff; overflow:hidden; }
    .hero-cine-bg { position:absolute; inset:-4%; background-size:cover; background-position:center; will-change:transform;
        animation: kenburns 30s ease-in-out infinite alternate; }
    @keyframes kenburns {
        0%   { transform: scale(1.12) translate(0, 0); }
        50%  { transform: scale(1.18) translate(-1.5%, -1%); }
        100% { transform: scale(1.24) translate(1.5%, -2%); }
    }
    .hero-cine-ov { position:absolute; inset:0; background:
        radial-gradient(120% 90% at 50% 10%, transparent, rgba(0,0,0,.35) 60%),
        linear-gradient(180deg, rgba(11,13,17,.55) 0%, rgba(11,13,17,.25) 35%, color-mix(in srgb, var(--bg) 92%, transparent) 100%); }
    .hero-cine-glow { position:absolute; width:60vw; max-width:800px; height:60vw; max-height:800px; left:50%; top:38%; transform:translate(-50%,-50%);
        background:radial-gradient(circle, color-mix(in srgb, var(--c) 40%, transparent), transparent 62%); filter:blur(40px); opacity:.55; z-index:1; }
    .hero-cine-inner { position:relative; z-index:2; max-width:940px; padding:0 1.4rem; }
    .hero-cine h1 { font-size:clamp(3rem, 8vw, 6.4rem); font-weight:800; letter-spacing:-.03em; margin:.6rem 0 1rem; text-shadow:0 6px 40px rgba(0,0,0,.4); }
    .hero-cine .tag { font-weight:300; font-size:1.25rem; max-width:600px; margin:0 auto; opacity:.92; }
    .hero-badge { display:inline-flex; align-items:center; gap:.5rem; background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.2); backdrop-filter:blur(8px); padding:.45rem 1.1rem; border-radius:100px; font-size:.82rem; letter-spacing:.06em; }

    /* Barre de réservation, verre dépoli */
    .hero-search { margin:2.6rem auto 0; max-width:880px; background:rgba(255,255,255,.09); backdrop-filter:blur(18px);
        border:1px solid rgba(255,255,255,.22); border-radius:18px; padding:15px;
        display:grid; grid-template-columns:1fr 1fr .9fr auto; gap:12px; align-items:end; box-shadow:0 40px 90px -40px rgba(0,0,0,.7); }
    .hero-search .hs-field { text-align:left; }
    .hero-search label { display:block; font-size:.66rem; font-weight:500; letter-spacing:.14em; text-transform:uppercase; color:#fff; opacity:.85; margin-bottom:7px; }
    .hero-search input, .hero-search select { width:100%; padding:12px 13px; border:0; border-radius:11px; font-family:var(--sans); font-size:.95rem; color:var(--ink); background:rgba(255,255,255,.94); }
    .hero-search input:focus, .hero-search select:focus { outline:2px solid var(--c); }
    .hs-btn { border:0; border-radius:11px; background:var(--c); color:#fff; padding:12px 24px; height:47px; font-weight:600; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:8px; transition:.25s; box-shadow:0 12px 30px -10px var(--c); }
    .hs-btn:hover { filter:brightness(1.1); transform:translateY(-1px); }
    @media (max-width:780px){ .hero-search { grid-template-columns:1fr 1fr; } .hs-btn{ grid-column:1/-1; justify-content:center; } }
    @media (max-width:480px){ .hero-search { grid-template-columns:1fr; } }

    .hero-quicklinks { margin-top:1.6rem; display:flex; gap:1.6rem; justify-content:center; flex-wrap:wrap; }
    .hero-quicklinks a { color:rgba(255,255,255,.85); font-size:.9rem; letter-spacing:.04em; border-bottom:1px solid transparent; padding-bottom:2px; transition:.25s; }
    .hero-quicklinks a:hover { color:#fff; border-color:rgba(255,255,255,.6); }
    .scroll-ind { position:absolute; bottom:26px; left:50%; transform:translateX(-50%); z-index:2; color:#fff; opacity:.8; animation:bob 2s infinite; }
    @keyframes bob { 0%,100%{ transform:translate(-50%,0) } 50%{ transform:translate(-50%,10px) } }
</style>
@endpush

<section class="hero-cine" id="accueil">
    <div class="hero-cine-bg" style="background-image:url('{{ $cover }}');"></div>
    <div class="hero-cine-ov"></div>
    <div class="hero-cine-glow"></div>

    <div class="hero-cine-inner">
        <div class="hero-badge" data-aos="fade-down">
            @if($hotel->address)<i class="fas fa-location-dot"></i> {{ $hotel->address }}@else <i class="fas fa-star"></i> Bienvenue @endif
        </div>
        <h1 class="display-serif" data-aos="fade-up" data-aos-delay="100">{{ $hotel->name }}</h1>
        <div class="hero-divider" data-aos="fade" data-aos-delay="250" style="background:#fff;box-shadow:0 0 18px rgba(255,255,255,.6);"></div>
        @if ($hotel->tagline)
            <p class="tag" data-aos="fade-up" data-aos-delay="300">{{ $hotel->tagline }}</p>
        @endif

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
