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
            --bg:#070b16; --bg2:#0b1122; --panel:rgba(255,255,255,.045); --panel2:rgba(255,255,255,.07);
            --border:rgba(255,255,255,.10); --txt:#e8ecf6; --muted:#94a1bd;
            --brand:#7c83ff; --brand2:#b06bff; --accent:#29e0c8; --sidew:260px;
        }
        *{ font-family:'Inter',system-ui,sans-serif; box-sizing:border-box; }
        body{ margin:0; background:var(--bg); color:var(--txt); }
        body::before{ content:''; position:fixed; inset:0; z-index:-1; background:
            radial-gradient(820px 420px at 82% -6%, rgba(124,131,255,.16), transparent 60%),
            radial-gradient(720px 420px at 6% 6%, rgba(176,107,255,.12), transparent 55%),
            linear-gradient(180deg,var(--bg),var(--bg2)); }
        a{ text-decoration:none; }

        /* ===== Sidebar ===== */
        .side{ position:fixed; top:0; left:0; bottom:0; width:var(--sidew); background:rgba(9,13,26,.78); backdrop-filter:blur(16px); border-right:1px solid var(--border); color:#cbd2e0; display:flex; flex-direction:column; z-index:40; transition:transform .3s; }
        .side .brand{ display:flex; align-items:center; gap:.7rem; padding:1.4rem 1.4rem; font-weight:800; font-size:1.25rem; color:#fff; }
        .side .brand .logo{ width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--brand),var(--brand2));display:grid;place-items:center; }
        .side .sect{ font-size:.68rem; letter-spacing:.18em; text-transform:uppercase; color:#5b6480; padding:1.2rem 1.5rem .4rem; }
        .side .nav-i{ display:flex; align-items:center; gap:.85rem; padding:.7rem 1.4rem; margin:.1rem .7rem; border-radius:12px; color:#aab2c5; font-weight:500; transition:.2s; }
        .side .nav-i i{ width:20px; text-align:center; font-size:1rem; }
        .side .nav-i:hover{ background:var(--panel2); color:#fff; }
        .side .nav-i.active{ background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; box-shadow:0 12px 24px -12px var(--brand); }
        .side .foot{ margin-top:auto; padding:1rem; border-top:1px solid var(--border); }
        .side .usr{ display:flex; align-items:center; gap:.6rem; padding:.5rem; }
        .side .usr .av{ width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand2));display:grid;place-items:center;color:#fff;font-weight:700; }

        /* ===== Main ===== */
        .main{ margin-left:var(--sidew); min-height:100vh; }
        .topbar{ position:sticky; top:0; z-index:30; background:rgba(7,11,22,.72); backdrop-filter:blur(12px); border-bottom:1px solid var(--border); padding:.9rem 1.6rem; display:flex; align-items:center; justify-content:space-between; }
        .topbar h1{ font-size:1.15rem; font-weight:700; margin:0; color:#fff; }
        .content{ padding:1.6rem; }

        /* ===== Overrides Bootstrap (dark premium cohérent) ===== */
        .card{ background:var(--panel)!important; border:1px solid var(--border)!important; color:var(--txt)!important; border-radius:16px!important; backdrop-filter:blur(8px); box-shadow:none!important; }
        .card-header, .card-footer{ background:transparent!important; border-color:var(--border)!important; color:#fff!important; }
        h1,h2,h3,h4,h5,h6{ color:#fff; }
        .text-muted, .text-secondary{ color:var(--muted)!important; }
        .text-dark{ color:var(--txt)!important; }
        hr{ border-color:var(--border); opacity:1; }
        small, .small{ color:inherit; }

        /* Tables */
        .table{ --bs-table-bg:transparent; --bs-table-color:var(--txt); color:var(--txt); margin-bottom:0; }
        .table > :not(caption) > * > *{ background:transparent; color:var(--txt); border-color:var(--border); }
        .table thead th, .table > thead.table-light th{ background:var(--white, #fff)!important; color:var(--muted)!important; text-transform:uppercase; font-size:.68rem; letter-spacing:.5px; border-color:var(--border)!important; }
        .table-hover > tbody > tr:hover > *{ background:var(--white, #fff)!important; }

        /* Formulaires */
        .form-label{ color:#dfe4f2!important; font-weight:500; }
        .form-text{ color:var(--muted)!important; }
        .form-control, .form-select{ background:var(--panel2); border:1px solid var(--border); color:var(--txt); }
        .form-control:focus, .form-select:focus{ background:var(--panel2); color:var(--txt); border-color:var(--brand); box-shadow:0 0 0 .2rem rgba(124,131,255,.2); }
        .form-control::placeholder{ color:#6b768f; }
        .form-select option{ background:#0d1426; color:var(--txt); }
        .input-group-text{ background:var(--panel2); border-color:var(--border); color:var(--muted); }
        .form-check-input{ background-color:var(--panel2); border-color:var(--border); }
        .form-check-input:checked{ background-color:var(--brand); border-color:var(--brand); }

        /* Badges */
        .badge.bg-light, .badge.bg-light.text-dark{ background:var(--panel2)!important; color:var(--txt)!important; border:1px solid var(--border)!important; }
        .badge.bg-success-subtle{ background:rgba(41,224,200,.14)!important; } .badge.text-success, .text-success{ color:var(--accent)!important; }
        .badge.bg-danger-subtle{ background:rgba(251,113,133,.16)!important; } .badge.text-danger, .text-danger{ color:#fb7185!important; }
        .badge.bg-warning-subtle{ background:rgba(251,191,36,.14)!important; } .badge.text-warning, .text-warning{ color:#fbbf24!important; }
        .badge.bg-info{ background:rgba(56,189,248,.16)!important; color:#7dd3fc!important; }
        .badge.bg-secondary{ background:var(--panel2)!important; color:var(--muted)!important; }
        .badge.bg-primary{ background:linear-gradient(135deg,var(--brand),var(--brand2))!important; }
        .badge.bg-success{ background:rgba(41,224,200,.9)!important; color:#04201c!important; }

        /* Alerts */
        .alert-success{ background:rgba(41,224,200,.1); border:1px solid rgba(41,224,200,.3); color:#cdeee7; }
        .alert-danger{ background:rgba(251,113,133,.1); border:1px solid rgba(251,113,133,.3); color:#fecdd3; }
        .alert-info{ background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.3); color:#cdeafe; }
        .alert-warning{ background:rgba(251,191,36,.1); border:1px solid rgba(251,191,36,.3); color:#f6e6bd; }
        .btn-close{ filter:invert(1) brightness(2); }

        /* Boutons */
        .btn-primary{ background:linear-gradient(90deg,var(--brand),var(--brand2)); border:none; color:#fff; }
        .btn-primary:hover{ filter:brightness(1.08); color:#fff; }
        .btn-secondary{ background:var(--panel2); border:1px solid var(--border); color:var(--txt); }
        .btn-outline-primary{ color:#a9b0ff; border-color:rgba(124,131,255,.5); }
        .btn-outline-primary:hover{ background:var(--brand); border-color:var(--brand); color:#fff; }
        .btn-outline-secondary{ color:var(--muted); border-color:var(--border); }
        .btn-outline-secondary:hover{ background:var(--panel2); color:#fff; }
        .btn-outline-warning{ color:#fbbf24; border-color:rgba(251,191,36,.5); }
        .btn-outline-warning:hover{ background:#fbbf24; color:#1a1204; }
        .btn-outline-success{ color:var(--accent); border-color:rgba(41,224,200,.5); }
        .btn-outline-success:hover{ background:var(--accent); color:#04201c; }
        .btn-outline-danger{ color:#fb7185; border-color:rgba(251,113,133,.5); }
        .btn-outline-danger:hover{ background:#fb7185; color:#2a0710; }
        .text-primary{ color:var(--brand)!important; }
        .btn-toggle{ display:none; background:none; border:none; font-size:1.3rem; color:#fff; }
        .backdrop{ display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:35; }

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
            <button class="btn btn-sm w-100 text-start" style="color:#aab2c5;background:var(--white, #fff);border:none;border-radius:10px;padding:.55rem .8rem;">
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
@include('partials.swal-helpers')
</body>
</html>
