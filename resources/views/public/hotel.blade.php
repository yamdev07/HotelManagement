<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $hotel->name }}@if($hotel->tagline) — {{ $hotel->tagline }}@endif</title>
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

        /* Navbar */
        .nav-lux { position:fixed; top:0; left:0; right:0; z-index:50; padding:1.4rem 0; transition:.4s; }
        .nav-lux.scrolled { background:#fff; box-shadow:0 10px 30px -18px rgba(0,0,0,.25); padding:.8rem 0; }
        .nav-lux .brand { font-family:var(--serif); font-size:1.6rem; font-weight:700; color:#fff; transition:.4s; display:flex; align-items:center; gap:.6rem; }
        .nav-lux.scrolled .brand { color:var(--ink); }
        .nav-lux .nav-link2 { color:rgba(255,255,255,.9); margin:0 1rem; font-weight:400; letter-spacing:.05em; font-size:.95rem; position:relative; transition:.3s; }
        .nav-lux.scrolled .nav-link2 { color:var(--ink); }
        .nav-lux .nav-link2::after { content:''; position:absolute; left:0; bottom:-4px; width:0; height:1px; background:var(--c); transition:.3s; }
        .nav-lux .nav-link2:hover::after { width:100%; }
        .nav-lux .nav-link2:hover { color:var(--c); }

        /* Hero */
        .hero-lux { height:100vh; min-height:640px; position:relative; display:flex; align-items:center; justify-content:center; text-align:center; color:#fff; overflow:hidden; }
        .hero-bg { position:absolute; inset:0; background-size:cover; background-position:center; transform:scale(1.08); animation:zoomSlow 18s ease-out forwards; }
        @keyframes zoomSlow { to { transform:scale(1); } }
        .hero-overlay { position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,.45) 0%, rgba(0,0,0,.25) 40%, rgba(0,0,0,.65) 100%); }
        .hero-content { position:relative; z-index:2; max-width:900px; padding:0 1.5rem; }
        .hero-content h1 { font-size:clamp(3rem,8vw,6rem); margin:.5rem 0 1.2rem; text-shadow:0 4px 30px rgba(0,0,0,.3); }
        .hero-divider { width:60px; height:1px; background:rgba(255,255,255,.6); margin:1.4rem auto; }
        .scroll-ind { position:absolute; bottom:28px; left:50%; transform:translateX(-50%); z-index:2; color:#fff; animation:bob 2s infinite; }
        @keyframes bob { 0%,100%{ transform:translate(-50%,0) } 50%{ transform:translate(-50%,10px) } }

        /* Reveal helpers via AOS */
        .lift { transition:transform .4s, box-shadow .4s; }
        .lift:hover { transform:translateY(-8px); box-shadow:0 30px 60px -30px rgba(0,0,0,.4); }

        /* Cartes chambres */
        .room-card { border:none; border-radius:4px; overflow:hidden; background:#fff; box-shadow:0 10px 40px -24px rgba(0,0,0,.35); }
        .room-media { height:230px; overflow:hidden; position:relative; }
        .room-media .img { position:absolute; inset:0; background-size:cover; background-position:center; transition:transform .8s; }
        .room-card:hover .room-media .img { transform:scale(1.1); }
        .room-price { position:absolute; bottom:0; right:0; background:var(--c); color:#fff; padding:.5rem 1rem; font-weight:600; }

        .svc-card { padding:2.5rem 1.5rem; text-align:center; border-radius:4px; transition:.35s; background:#fff; }
        .svc-card:hover { background:var(--c); color:#fff; transform:translateY(-6px); box-shadow:0 30px 60px -30px var(--c); }
        .svc-card:hover .svc-ico { color:#fff; }
        .svc-ico { font-size:2.4rem; color:var(--c); transition:.35s; }

        .dark-sec { background:var(--d); color:#fff; }
        .dark-sec .eyebrow { color:#fff; opacity:.8; }

        footer.foot { background:#0f0f0f; color:#cfcfcf; padding:5rem 0 2rem; }
        footer.foot a { color:#cfcfcf; } footer.foot a:hover { color:#fff; }

        #toTop { position:fixed; right:24px; bottom:24px; width:48px; height:48px; border:none; border-radius:50%; background:var(--c); color:#fff; opacity:0; pointer-events:none; transition:.3s; z-index:60; }
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; transition:none!important } .hero-bg{ transform:none } [data-aos]{ opacity:1!important; transform:none!important } }
        html { scroll-behavior:smooth; }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="nav-lux" id="nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ $hotel->publicUrl() }}" class="brand">
            @if ($hotel->logoUrl())<img src="{{ $hotel->logoUrl() }}" alt="" style="height:38px;border-radius:4px;">@endif
            <span>{{ $hotel->name }}</span>
        </a>
        <div class="d-none d-lg-flex align-items-center">
            @if ($hotel->show_rooms)<a href="#chambres" class="nav-link2">Chambres</a>@endif
            @if ($hotel->show_restaurant)<a href="#restaurant" class="nav-link2">Restaurant</a>@endif
            @if ($hotel->show_services)<a href="#services" class="nav-link2">Services</a>@endif
            @if ($hotel->show_contact)<a href="#contact" class="nav-link2">Contact</a>@endif
            @if ($hotel->show_rooms)<a href="#chambres" class="btn-c ms-3" style="padding:.5rem 1.4rem;">Réserver</a>@endif
        </div>
    </div>
</nav>

<main>
    @include('public.sections.hero')
    @include('public.sections.about')
    @if ($hotel->show_rooms)     @include('public.sections.rooms')      @endif
    @if ($hotel->show_services)  @include('public.sections.services')   @endif
    @if ($hotel->show_restaurant)@include('public.sections.restaurant') @endif
    @if ($hotel->show_contact)   @include('public.sections.contact')    @endif
</main>

<footer class="foot">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="serif h3 text-white mb-2">{{ $hotel->name }}</div>
                <p class="small" style="max-width:320px;opacity:.8;">{{ $hotel->tagline ?? 'Votre séjour, notre passion.' }}</p>
            </div>
            <div class="col-lg-4">
                @if ($hotel->address)<p class="small mb-2"><i class="fas fa-location-dot me-2 text-c"></i>{{ $hotel->address }}</p>@endif
                @if ($hotel->contact_phone)<p class="small mb-2"><i class="fas fa-phone me-2 text-c"></i>{{ $hotel->contact_phone }}</p>@endif
                @if ($hotel->contact_email)<p class="small mb-2"><i class="fas fa-envelope me-2 text-c"></i>{{ $hotel->contact_email }}</p>@endif
            </div>
            <div class="col-lg-3 text-lg-end">
                <p class="small mb-0" style="opacity:.6;">© {{ date('Y') }} {{ $hotel->name }}</p>
                <p class="small" style="opacity:.4;">Propulsé par {{ config('app.name', 'MyHotel') }}</p>
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
    const onScroll = () => {
        nav.classList.toggle('scrolled', scrollY > 60);
        const s = scrollY > 500; toTop.style.opacity = s ? 1 : 0; toTop.style.pointerEvents = s ? 'auto' : 'none';
    };
    addEventListener('scroll', onScroll, { passive:true }); onScroll();
    toTop.onclick = () => scrollTo({ top:0, behavior:'smooth' });
</script>
</body>
</html>
