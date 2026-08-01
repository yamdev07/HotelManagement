@php $cover = $hotel->coverOrDefault(); @endphp
@push('head')
<style>
    .hero-ed { padding:9rem 0 3.5rem; position:relative; overflow:hidden; }
    .hero-ed::before { content:''; position:absolute; top:-10%; right:-8%; width:44vw; height:44vw; max-width:620px; max-height:620px;
        background:radial-gradient(circle, color-mix(in srgb, var(--c) 22%, transparent), transparent 68%); z-index:0; }
    .hero-ed-grid { display:grid; grid-template-columns:1.05fr 1fr; gap:3.2rem; align-items:center; position:relative; z-index:1; }
    .hero-ed-eyebrow { display:inline-flex; align-items:center; gap:.5rem; }
    .hero-ed-title { font-size:clamp(3rem, 7.2vw, 6.2rem); font-weight:800; letter-spacing:-.03em; line-height:.98; margin:1.1rem 0 1.3rem; }
    .hero-ed-title .accent { color:var(--c); }
    .hero-ed-tag { font-size:1.2rem; color:var(--ink2); max-width:460px; line-height:1.6; margin-bottom:2rem; }
    .hero-ed-cta { display:flex; gap:.9rem; flex-wrap:wrap; }
    .hero-ed-media { position:relative; }
    .hero-ed-img { aspect-ratio:4/5; border-radius:26px; background-size:cover; background-position:center; box-shadow:0 50px 90px -50px rgba(0,0,0,.5);
        transform:scale(1.02); animation:heroReveal 1.1s cubic-bezier(.2,.7,.2,1) forwards; }
    @keyframes heroReveal { from { opacity:0; transform:scale(1.08) translateY(14px); } to { opacity:1; transform:scale(1) translateY(0); } }
    .hero-ed-badge { position:absolute; left:-18px; bottom:26px; background:#fff; border-radius:18px; padding:14px 18px; box-shadow:0 24px 50px -24px rgba(0,0,0,.35); display:flex; align-items:center; gap:12px; }
    .hero-ed-badge .n { font-family:var(--disp); font-weight:800; font-size:1.5rem; color:var(--c); line-height:1; }
    .hero-ed-badge small { color:var(--ink2); }

    /* Barre de réservation */
    .hero-search { margin:2.8rem auto 0; background:#fff; border:1px solid var(--line); border-radius:20px;
        padding:16px; display:grid; grid-template-columns:1fr 1fr .9fr auto; gap:14px; align-items:end; box-shadow:0 30px 60px -40px rgba(0,0,0,.3); position:relative; z-index:1; }
    .hero-search .hs-field { text-align:left; }
    .hero-search label { display:block; font-size:.66rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--ink2); margin-bottom:7px; }
    .hero-search input, .hero-search select { width:100%; padding:12px 13px; border:1.5px solid var(--line); border-radius:12px; font-family:var(--sans); font-size:.95rem; color:var(--ink); background:var(--paper); }
    .hero-search input:focus, .hero-search select:focus { outline:none; border-color:var(--c); box-shadow:0 0 0 3px color-mix(in srgb, var(--c) 16%, transparent); }
    .hs-btn { border:0; border-radius:12px; background:var(--c); color:#fff; padding:13px 24px; height:48px; font-weight:600; cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:8px; transition:.25s; }
    .hs-btn:hover { filter:brightness(1.08); transform:translateY(-1px); }

    @media (max-width:900px){ .hero-ed-grid { grid-template-columns:1fr; gap:2.2rem; } .hero-ed-media { order:-1; } .hero-ed-img{ aspect-ratio:16/10; } }
    @media (max-width:780px){ .hero-search { grid-template-columns:1fr 1fr; } .hs-btn{ grid-column:1/-1; justify-content:center; } }
    @media (max-width:480px){ .hero-search { grid-template-columns:1fr; } }
</style>
@endpush

<section class="hero-ed" id="accueil">
    <div class="container">
        <div class="hero-ed-grid">
            <div class="hero-ed-left" data-aos="fade-up">
                <span class="eyebrow hero-ed-eyebrow">
                    @if($hotel->address)<i class="fas fa-location-dot"></i> {{ $hotel->address }}@else <i class="fas fa-star"></i> Bienvenue @endif
                </span>
                <h1 class="hero-ed-title">{{ $hotel->name }}</h1>
                @if ($hotel->tagline)
                    <p class="hero-ed-tag">{{ $hotel->tagline }}</p>
                @endif
                <div class="hero-ed-cta">
                    @if ($hotel->show_rooms)<a href="{{ route('public.hotel.availability', $hotel->slug) }}" class="btn-c"><i class="fas fa-calendar-check"></i> Réserver un séjour</a>@endif
                    @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="btn-ghost">Voir les chambres</a>@endif
                </div>
            </div>

            <div class="hero-ed-media" data-aos="fade-left" data-aos-delay="120">
                <div class="hero-ed-img" style="background-image:url('{{ $cover }}');"></div>
                @if ($hotel->contact_phone)
                    <div class="hero-ed-badge">
                        <span class="n"><i class="fas fa-phone" style="font-size:1.1rem;"></i></span>
                        <div><small style="display:block;font-size:.7rem;">Réservez par téléphone</small><strong style="font-size:.92rem;">{{ $hotel->contact_phone }}</strong></div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recherche de disponibilité --}}
        @if ($hotel->show_rooms)
        <form class="hero-search" method="GET" action="{{ route('public.hotel.availability', $hotel->slug) }}" data-aos="fade-up" data-aos-delay="200">
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
    </div>
</section>
