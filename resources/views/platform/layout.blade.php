<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plateforme · @yield('title', 'Tableau de bord')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --brand:#4f46e5; --brand2:#7c3aed; --ink:#0f172a; --muted:#64748b;
            --side:#0f1222; --side2:#171a2e; --sidew:260px;
        }
        *{ font-family:'Inter',system-ui,sans-serif; box-sizing:border-box; }
        body{ margin:0; background:#f4f5fb; color:var(--ink); }
        a{ text-decoration:none; }

        /* ===== Sidebar ===== */
        .side{ position:fixed; top:0; left:0; bottom:0; width:var(--sidew); background:linear-gradient(180deg,var(--side),var(--side2)); color:#cbd2e0; display:flex; flex-direction:column; z-index:40; transition:transform .3s; }
        .side .brand{ display:flex; align-items:center; gap:.7rem; padding:1.4rem 1.4rem; font-weight:800; font-size:1.25rem; color:#fff; }
        .side .brand .logo{ width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--brand),var(--brand2));display:grid;place-items:center; }
        .side .sect{ font-size:.68rem; letter-spacing:.18em; text-transform:uppercase; color:#5b6480; padding:1.2rem 1.5rem .4rem; }
        .side .nav-i{ display:flex; align-items:center; gap:.85rem; padding:.7rem 1.4rem; margin:.1rem .7rem; border-radius:12px; color:#aab2c5; font-weight:500; transition:.2s; }
        .side .nav-i i{ width:20px; text-align:center; font-size:1rem; }
        .side .nav-i:hover{ background:rgba(255,255,255,.06); color:#fff; }
        .side .nav-i.active{ background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; box-shadow:0 12px 24px -12px var(--brand); }
        .side .foot{ margin-top:auto; padding:1rem; border-top:1px solid rgba(255,255,255,.06); }
        .side .usr{ display:flex; align-items:center; gap:.6rem; padding:.5rem; }
        .side .usr .av{ width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand2));display:grid;place-items:center;color:#fff;font-weight:700; }

        /* ===== Main ===== */
        .main{ margin-left:var(--sidew); min-height:100vh; }
        .topbar{ position:sticky; top:0; z-index:30; background:rgba(255,255,255,.85); backdrop-filter:blur(10px); border-bottom:1px solid #e8eaf3; padding:.9rem 1.6rem; display:flex; align-items:center; justify-content:space-between; }
        .topbar h1{ font-size:1.15rem; font-weight:700; margin:0; }
        .content{ padding:1.6rem; }

        .btn-primary{ background:var(--brand); border-color:var(--brand); }
        .btn-primary:hover{ background:#4338ca; border-color:#4338ca; }
        .text-primary{ color:var(--brand)!important; }
        .btn-toggle{ display:none; background:none; border:none; font-size:1.3rem; color:var(--ink); }
        .backdrop{ display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:35; }

        @media (max-width:992px){
            .side{ transform:translateX(-100%); } .side.open{ transform:none; }
            .main{ margin-left:0; } .btn-toggle{ display:inline-block; }
            .backdrop.show{ display:block; }
        }
    </style>
</head>
<body>

@php $u = auth()->user(); $ini = strtoupper(mb_substr($u->name ?? 'S', 0, 1)); @endphp

<!-- SIDEBAR -->
<aside class="side" id="side">
    <div class="brand">
        <span class="logo"><i class="fas fa-hotel text-white"></i></span>
        {{ config('app.name', 'checkinHub') }}
    </div>

    <div class="sect">Plateforme</div>
    <a href="{{ route('platform.hotels.index') }}" class="nav-i {{ request()->routeIs('platform.hotels.index') || request()->routeIs('platform.hotels.show') || request()->routeIs('platform.hotels.edit') ? 'active' : '' }}">
        <i class="fas fa-gauge-high"></i> Tableau de bord
    </a>
    <a href="{{ route('platform.hotels.index') }}" class="nav-i">
        <i class="fas fa-hotel"></i> Hôtels
    </a>
    <a href="{{ route('platform.hotels.create') }}" class="nav-i {{ request()->routeIs('platform.hotels.create') ? 'active' : '' }}">
        <i class="fas fa-plus-circle"></i> Nouvel hôtel
    </a>

    <div class="sect">Compte</div>
    <a href="{{ url('/') }}" target="_blank" class="nav-i"><i class="fas fa-globe"></i> Voir le site</a>

    <div class="foot">
        <div class="usr">
            <span class="av">{{ $ini }}</span>
            <div style="min-width:0;">
                <div class="text-white fw-semibold text-truncate" style="font-size:.9rem;">{{ $u->name ?? 'Super Admin' }}</div>
                <div class="text-truncate" style="font-size:.75rem;color:#7b84a0;">{{ $u->email ?? '' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="btn btn-sm w-100 text-start" style="color:#aab2c5;background:rgba(255,255,255,.05);border:none;border-radius:10px;padding:.55rem .8rem;">
                <i class="fas fa-arrow-right-from-bracket me-2"></i> Déconnexion
            </button>
        </form>
    </div>
</aside>

<div class="backdrop" id="backdrop"></div>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn-toggle" id="toggle"><i class="fas fa-bars"></i></button>
            <h1>@yield('title', 'Tableau de bord')</h1>
        </div>
        <a href="{{ route('platform.hotels.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Nouvel hôtel</a>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const side=document.getElementById('side'), bd=document.getElementById('backdrop');
    document.getElementById('toggle').onclick=()=>{ side.classList.toggle('open'); bd.classList.toggle('show'); };
    bd.onclick=()=>{ side.classList.remove('open'); bd.classList.remove('show'); };
</script>
@stack('scripts')
</body>
</html>
