<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $hotel->name)@hasSection('title') — {{ $hotel->name }}@endif</title>
    <meta name="description" content="{{ $hotel->tagline ?? $hotel->name }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --c: {{ $hotel->primaryColor() }};
            --d: {{ $hotel->secondaryColor() }};
            --ink: #1a1a1a;
            --serif: 'Cormorant Garamond', Georgia, serif;
            --sans: 'Jost', system-ui, sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: var(--sans); color: var(--ink); overflow-x: hidden; background: #fff; }
        h1,h2,h3,h4,.serif { font-family: var(--serif); }
        a { text-decoration: none; }
        .text-c { color: var(--c) !important; }
        .bg-c { background: var(--c); }
        .btn-c { background: var(--c); color:#fff; border:none; border-radius:2px; padding:.85rem 2rem; font-weight:500; letter-spacing:.04em; transition:.3s; display:inline-block; }
        .btn-c:hover { color:#fff; filter:brightness(.9); transform: translateY(-2px); box-shadow:0 14px 30px -12px var(--c); }
        .btn-ghost { background:transparent; color:#fff; border:1px solid rgba(255,255,255,.7); border-radius:2px; padding:.85rem 2rem; font-weight:500; letter-spacing:.04em; transition:.3s; display:inline-block; }
        .btn-ghost:hover { background:#fff; color:var(--ink); }
        .eyebrow { font-family:var(--sans); letter-spacing:.35em; text-transform:uppercase; font-size:.72rem; font-weight:500; color:var(--c); }
        .section { padding: 7rem 0; }
        .display-serif { font-family:var(--serif); font-weight:600; line-height:1.04; letter-spacing:-.01em; }
        .hero-divider { width:60px; height:1px; background:rgba(255,255,255,.6); margin:1.4rem auto; }

        /* Navbar */
        .nav-lux { position:fixed; top:0; left:0; right:0; z-index:50; padding:1.4rem 0; transition:.4s; }
        .nav-lux.solid, .nav-lux.scrolled { background:#fff; box-shadow:0 10px 30px -18px rgba(0,0,0,.25); padding:.8rem 0; }
        .nav-lux .brand { font-family:var(--serif); font-size:1.6rem; font-weight:700; color:#fff; transition:.4s; display:flex; align-items:center; gap:.6rem; }
        .nav-lux.solid .brand, .nav-lux.scrolled .brand { color:var(--ink); }
        .nav-lux .nav-link2 { color:rgba(255,255,255,.92); margin:0 1rem; font-weight:400; letter-spacing:.05em; font-size:.95rem; position:relative; transition:.3s; }
        .nav-lux.solid .nav-link2, .nav-lux.scrolled .nav-link2 { color:var(--ink); }
        .nav-lux .nav-link2::after { content:''; position:absolute; left:0; bottom:-4px; width:0; height:1px; background:var(--c); transition:.3s; }
        .nav-lux .nav-link2:hover::after, .nav-lux .nav-link2.active::after { width:100%; }
        .nav-lux .nav-link2:hover, .nav-lux .nav-link2.active { color:var(--c); }

        .page-head { padding:11rem 0 5rem; text-align:center; color:#fff; position:relative; background:linear-gradient(135deg,var(--c),var(--d)); }
        .page-head.has-img { background-size:cover; background-position:center; }
        .page-head .ov { position:absolute; inset:0; background:rgba(0,0,0,.45); }
        .page-head > .container { position:relative; z-index:2; }

        .lift { transition:transform .4s, box-shadow .4s; }
        .lift:hover { transform:translateY(-8px); box-shadow:0 30px 60px -30px rgba(0,0,0,.4); }
        .svc-card { padding:2.5rem 1.5rem; text-align:center; border-radius:4px; transition:.35s; background:#fff; }
        .svc-card:hover { background:var(--c); color:#fff; transform:translateY(-6px); box-shadow:0 30px 60px -30px var(--c); }
        .svc-card:hover .svc-ico { color:#fff; }
        .svc-ico { font-size:2.4rem; color:var(--c); transition:.35s; }
        .room-card { border:none; border-radius:4px; overflow:hidden; background:#fff; box-shadow:0 10px 40px -24px rgba(0,0,0,.35); }
        .room-media { height:230px; overflow:hidden; position:relative; }
        .room-media .img { position:absolute; inset:0; background-size:cover; background-position:center; transition:transform .8s; }
        .room-card:hover .room-media .img { transform:scale(1.1); }
        .room-price { position:absolute; bottom:0; right:0; background:var(--c); color:#fff; padding:.5rem 1rem; font-weight:600; }
        .dark-sec { background:var(--d); color:#fff; }
        .dark-sec .eyebrow { color:#fff; opacity:.8; }

        footer.foot { background:#0f0f0f; color:#cfcfcf; padding:5rem 0 2rem; }
        footer.foot a { color:#cfcfcf; } footer.foot a:hover { color:#fff; }
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
            @if ($hotel->logoUrl())<img src="{{ $hotel->logoUrl() }}" alt="" style="height:38px;border-radius:4px;">@endif
            <span>{{ $hotel->name }}</span>
        </a>
        <div class="d-none d-lg-flex align-items-center">
            <a href="{{ route('public.hotel', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel') ? 'active' : '' }}">Accueil</a>
            @if ($hotel->show_rooms)<a href="{{ route('public.hotel.rooms', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.rooms') ? 'active' : '' }}">Chambres</a>@endif
            @if ($hotel->show_restaurant)<a href="{{ route('public.hotel.restaurant', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.restaurant') ? 'active' : '' }}">Restaurant</a>@endif
            @if ($hotel->show_services)<a href="{{ route('public.hotel.services', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.services') ? 'active' : '' }}">Services</a>@endif
            @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="nav-link2 {{ request()->routeIs('public.hotel.contact') ? 'active' : '' }}">Contact</a>@endif
            @if ($hotel->show_contact)<a href="{{ route('public.hotel.contact', $hotel->slug) }}" class="btn-c ms-3" style="padding:.5rem 1.4rem;">Réserver</a>@endif
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
                <div class="serif h3 text-white mb-2">{{ $hotel->name }}</div>
                <p class="small" style="max-width:320px;opacity:.8;">{{ $hotel->tagline ?? 'Votre séjour, notre passion.' }}</p>
                @php $icons = ['facebook'=>'fab fa-facebook-f','instagram'=>'fab fa-instagram','whatsapp'=>'fab fa-whatsapp','website'=>'fas fa-globe']; @endphp
                @if ($hotel->socialLinks())
                    <div class="d-flex gap-2 mt-3">
                        @foreach ($hotel->socialLinks() as $key => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $key }}"
                               style="width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:rgba(255,255,255,.08);"><i class="{{ $icons[$key] ?? 'fas fa-link' }}"></i></a>
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
                <p class="small" style="opacity:.35;">Propulsé par {{ config('app.name', 'MyHotel') }}</p>
            </div>
        </div>
    </div>
</footer>

<button id="toTop" aria-label="Haut"><i class="fas fa-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, once: true, easing: 'ease-out-cubic', offset: 90 });
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
