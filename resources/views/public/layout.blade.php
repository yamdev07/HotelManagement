<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $hotel->name)@hasSection('title') · {{ $hotel->name }}@endif</title>
    <meta name="description" content="{{ $hotel->tagline ?? $hotel->name }}">
    <link rel="icon" href="{{ $hotel->logoUrl() ?? asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --c: {{ $hotel->primaryColor() }};
            --d: {{ $hotel->secondaryColor() }};
            --ink: #17140f; --ink2: #5f584d;
            --paper: #f6f3ee; --paper2: #efe8dd; --line: #e5ddce;
            --disp: 'Bricolage Grotesque', system-ui, sans-serif;
            --sans: 'Jost', system-ui, sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin:0; font-family:var(--sans); color:var(--ink); background:var(--paper); overflow-x:hidden; -webkit-font-smoothing:antialiased; }
        h1,h2,h3,h4,.serif,.display-serif { font-family:var(--disp); font-weight:700; letter-spacing:-.025em; line-height:1.02; }
        .display-serif { font-weight:800; }
        a { text-decoration:none; color:inherit; }
        .text-c { color:var(--c) !important; }
        .bg-c { background:var(--c); }
        .text-secondary { color:var(--ink2) !important; }
        .section { padding:6.5rem 0; }
        .eyebrow { font-family:var(--sans); letter-spacing:.24em; text-transform:uppercase; font-size:.72rem; font-weight:600; color:var(--c); }
        .hero-divider { width:52px; height:3px; background:var(--c); margin:1.3rem auto; border-radius:3px; }

        /* Boutons */
        .btn-c { background:var(--ink); color:var(--paper); border:none; border-radius:100px; padding:.85rem 1.9rem; font-weight:600; font-size:.94rem; letter-spacing:.01em; transition:.25s; display:inline-flex; align-items:center; gap:.5rem; }
        .btn-c:hover { background:var(--c); color:#fff; transform:translateY(-2px); box-shadow:0 16px 34px -16px var(--c); }
        .btn-ghost { background:transparent; color:var(--ink); border:1.6px solid var(--ink); border-radius:100px; padding:.85rem 1.9rem; font-weight:600; transition:.25s; display:inline-flex; align-items:center; gap:.5rem; }
        .btn-ghost:hover { background:var(--ink); color:var(--paper); }
        .dark-sec .btn-c, .page-head .btn-c { background:#fff; color:var(--ink); }
        .dark-sec .btn-c:hover, .page-head .btn-c:hover { background:var(--c); color:#fff; }

        /* Navbar */
        .nav-lux { position:fixed; top:0; left:0; right:0; z-index:50; padding:1.3rem 0; transition:.4s; }
        .nav-lux.solid, .nav-lux.scrolled { background:rgba(246,243,238,.92); backdrop-filter:blur(10px); box-shadow:0 1px 0 var(--line); padding:.75rem 0; }
        .nav-lux .brand { font-family:var(--disp); font-size:1.5rem; font-weight:800; color:#fff; letter-spacing:-.02em; transition:.4s; display:flex; align-items:center; gap:.6rem; }
        .nav-lux.solid .brand, .nav-lux.scrolled .brand { color:var(--ink); }
        .nav-lux .nav-link2 { color:rgba(255,255,255,.9); margin:0 .95rem; font-weight:500; letter-spacing:.02em; font-size:.92rem; position:relative; transition:.3s; }
        .nav-lux.solid .nav-link2, .nav-lux.scrolled .nav-link2 { color:var(--ink); }
        .nav-lux .nav-link2::after { content:''; position:absolute; left:0; bottom:-5px; width:0; height:2px; background:var(--c); transition:.3s; border-radius:2px; }
        .nav-lux .nav-link2:hover::after, .nav-lux .nav-link2.active::after { width:100%; }
        .nav-lux .nav-link2:hover, .nav-lux .nav-link2.active { color:var(--c); }
        .nav-lux .btn-nav { background:var(--c); color:#fff; border-radius:100px; padding:.55rem 1.4rem; font-weight:600; font-size:.9rem; transition:.25s; margin-left:1rem; }
        .nav-lux .btn-nav:hover { filter:brightness(1.08); transform:translateY(-1px); }

        /* En-tête des pages internes */
        .page-head { padding:10rem 0 4.5rem; text-align:center; color:#fff; position:relative; background:var(--ink); }
        .page-head.has-img { background-size:cover; background-position:center; }
        .page-head .ov { position:absolute; inset:0; background:linear-gradient(180deg, color-mix(in srgb, var(--ink) 55%, transparent), color-mix(in srgb, var(--ink) 80%, transparent)); }
        .page-head > .container { position:relative; z-index:2; }
        .page-head .eyebrow { color:#fff; opacity:.85; }

        /* Cartes */
        .lift { transition:transform .4s, box-shadow .4s; }
        .lift:hover { transform:translateY(-8px); }
        .svc-card { padding:2rem 1.5rem; text-align:center; border-radius:18px; transition:.35s; background:#fff; border:1px solid var(--line); }
        .svc-card:hover { background:var(--ink); color:#fff; transform:translateY(-6px); box-shadow:0 30px 60px -30px rgba(0,0,0,.35); border-color:var(--ink); }
        .svc-card:hover .svc-ico, .svc-card:hover .text-secondary { color:#fff !important; }
        .svc-ico { font-size:2rem; color:var(--c); transition:.35s; }
        .room-card { border:1px solid var(--line); border-radius:20px; overflow:hidden; background:#fff; transition:transform .4s, box-shadow .4s; }
        .room-card:hover { transform:translateY(-8px); box-shadow:0 34px 64px -34px rgba(0,0,0,.32); }
        .room-media { height:230px; overflow:hidden; position:relative; }
        .room-media .img { position:absolute; inset:0; background-size:cover; background-position:center; transition:transform .8s; }
        .room-card:hover .room-media .img { transform:scale(1.08); }
        .room-price { position:absolute; bottom:12px; right:12px; background:#fff; color:var(--ink); padding:.4rem .9rem; font-weight:700; border-radius:100px; box-shadow:0 8px 20px -8px rgba(0,0,0,.3); }
        .dark-sec { background:var(--ink); color:#fff; border-radius:28px; margin:0 1rem; }
        .dark-sec .eyebrow { color:#fff; opacity:.75; }

        /* Galerie */
        .gallery-grid { display:grid; grid-template-columns:repeat(4,1fr); grid-auto-rows:200px; gap:14px; }
        .gallery-item { position:relative; border-radius:16px; overflow:hidden; background-size:cover; background-position:center; display:block; transition:transform .5s; }
        .gallery-item::after { content:''; position:absolute; inset:0; background:rgba(0,0,0,0); transition:.4s; }
        .gallery-item:hover { transform:scale(.98); } .gallery-item:hover::after { background:rgba(0,0,0,.2); }
        .gallery-item.tall { grid-row:span 2; } .gallery-item.wide { grid-column:span 2; }
        .gallery-ov { position:absolute; inset:0; display:grid; place-items:center; color:#fff; opacity:0; transition:.4s; z-index:2; font-size:1.4rem; }
        .gallery-item:hover .gallery-ov { opacity:1; }
        @media (max-width:768px){ .gallery-grid{ grid-template-columns:repeat(2,1fr); grid-auto-rows:150px; } .gallery-item.wide{ grid-column:span 1 } }

        /* Témoignages */
        .review { background:#fff; border:1px solid var(--line); border-radius:20px; padding:2rem; transition:.35s; }
        .review:hover { transform:translateY(-6px); box-shadow:0 26px 54px -30px rgba(0,0,0,.25); }
        .rev-ava { width:44px; height:44px; border-radius:50%; background:var(--c); color:#fff; display:grid; place-items:center; font-weight:700; }

        /* FAQ */
        .accordion-item { border:1px solid var(--line); border-radius:16px !important; overflow:hidden; margin-bottom:12px; background:#fff; }
        .accordion-button { border-radius:0 !important; font-family:var(--disp); font-weight:600; }
        .accordion-button:not(.collapsed){ color:var(--c) !important; background:var(--paper2); }
        .accordion-button::after{ filter:grayscale(1); }

        footer.foot { background:var(--ink); color:#ccc4b6; padding:5rem 0 2rem; margin-top:2rem; }
        footer.foot a { color:#ccc4b6; } footer.foot a:hover { color:#fff; }
        footer.foot .h3 { font-family:var(--disp); }
        #toTop { position:fixed; right:24px; bottom:24px; width:48px; height:48px; border:none; border-radius:50%; background:var(--c); color:#fff; opacity:0; pointer-events:none; transition:.3s; z-index:60; }
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; transition:none!important } [data-aos]{ opacity:1!important; transform:none!important } }
        html { scroll-behavior:smooth; }
    </style>
    @stack('head')
</head>
<body>

<nav class="nav-lux {{ $solidNav ?? false ? 'solid' : '' }}" id="nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('public.hotel', $hotel->slug) }}" class="brand">
            @if ($hotel->logoUrl())<img src="{{ $hotel->logoUrl() }}" alt="" style="height:38px;border-radius:8px;">@endif
            <span>{{ $hotel->name }}</span>
        </a>
        <div class="d-none d-lg-flex align-items-center">
            <a href="{{ route('public.hotel', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel') ? 'active' : '' }}">Accueil</a>
            @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.rooms') ? 'active' : '' }}">Chambres</a>@endif
            @if ($hotel->show_restaurant)<a href="{{ route('public.hotel.restaurant', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.restaurant') ? 'active' : '' }}">Restaurant</a>@endif
            @if ($hotel->show_services)<a href="{{ route('public.hotel.services', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.services') ? 'active' : '' }}">Services</a>@endif
            @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.contact') ? 'active' : '' }}">Contact</a>@endif
            <a href="{{ route('public.hotel.availability', $hotel->slug) }}" class="btn-nav">Réserver</a>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="foot">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="h3 text-white mb-2" style="font-size:1.6rem;">{{ $hotel->name }}</div>
                <p class="small" style="max-width:320px;opacity:.75;">{{ $hotel->tagline ?? __('flash.default_tagline') }}</p>
                @php $icons = ['facebook'=>'fab fa-facebook-f','instagram'=>'fab fa-instagram','whatsapp'=>'fab fa-whatsapp','website'=>'fas fa-globe']; @endphp
                @if ($hotel->socialLinks())
                    <div class="d-flex gap-2 mt-3">
                        @foreach ($hotel->socialLinks() as $key => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $key }}"
                               style="width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.08);"><i class="{{ $icons[$key] ?? 'fas fa-link' }}"></i></a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="eyebrow mb-3" style="color:#fff;opacity:.5;">Navigation</div>
                <div class="d-flex flex-column gap-2 small">
                    <a href="{{ route('public.hotel', $hotel->slug) }}">Accueil</a>
                    @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}">Chambres</a>@endif
                    @if ($hotel->show_restaurant)<a href="{{ route('public.hotel.restaurant', $hotel->slug) }}">Restaurant</a>@endif
                    @if ($hotel->show_services)<a href="{{ route('public.hotel.services', $hotel->slug) }}">Services</a>@endif
                    @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}">Contact</a>@endif
                </div>
            </div>
            <div class="col-lg-3 text-lg-end">
                @if ($hotel->contact_phone)<p class="small mb-2"><i class="fas fa-phone me-2 text-c"></i>{{ $hotel->contact_phone }}</p>@endif
                @if ($hotel->contact_email)<p class="small mb-2"><i class="fas fa-envelope me-2 text-c"></i>{{ $hotel->contact_email }}</p>@endif
                <p class="small mb-0 mt-3" style="opacity:.5;">© {{ date('Y') }} {{ $hotel->name }}</p>
                <p class="small" style="opacity:.35;">Propulsé par {{ config('app.name', 'checkinHub') }}</p>
            </div>
        </div>
    </div>
</footer>

<button id="toTop" aria-label="Haut"><i class="fas fa-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 850, once: true, easing: 'ease-out-cubic', offset: 90 });
    const nav = document.getElementById('nav'), toTop = document.getElementById('toTop');
    const solid = nav.classList.contains('solid');
    const onScroll = () => {
        if (!solid) nav.classList.toggle('scrolled', scrollY > 60);
        const s = scrollY > 500; toTop.style.opacity = s ? 1 : 0; toTop.style.pointerEvents = s ? 'auto' : 'none';
    };
    addEventListener('scroll', onScroll, { passive:true }); onScroll();
    toTop.onclick = () => scrollTo({ top:0, behavior:'smooth' });
</script>
</body>
</html>
