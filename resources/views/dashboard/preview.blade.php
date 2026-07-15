<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord — {{ config('app.name', 'checkinHub') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root{
            --bg:#070b16; --bg2:#0b1122; --panel:rgba(255,255,255,.035); --panel2:rgba(255,255,255,.055);
            --border:rgba(255,255,255,.09); --txt:#e8ecf6; --muted:#93a0bd;
            --brand:#7c83ff; --brand2:#b06bff; --accent:#29e0c8; --amber:#fbbf24; --rose:#fb7185;
            --sw:250px;
        }
        *{box-sizing:border-box; font-family:'Inter',system-ui,sans-serif;}
        body{margin:0; background:var(--bg); color:var(--txt); min-height:100vh;}
        h1,h2,h3,h4,.dfont{font-family:'Space Grotesk',sans-serif; letter-spacing:-.3px;}
        a{text-decoration:none; color:inherit;}
        .cosmos{position:fixed; inset:0; z-index:-1; background:
            radial-gradient(800px 400px at 78% -5%, rgba(124,131,255,.18), transparent 60%),
            radial-gradient(700px 400px at 10% 8%, rgba(176,107,255,.14), transparent 55%),
            linear-gradient(180deg,var(--bg) 0%, var(--bg2) 100%);}
        .glass{background:var(--panel); border:1px solid var(--border); border-radius:16px; backdrop-filter:blur(10px);}

        /* Sidebar */
        .side{position:fixed; top:0; left:0; bottom:0; width:var(--sw); padding:18px 14px; overflow-y:auto;
            border-right:1px solid var(--border); background:rgba(9,13,26,.6); backdrop-filter:blur(14px); z-index:20;}
        .logo{font-family:'Space Grotesk'; font-weight:700; font-size:1.25rem; color:#fff; padding:6px 10px 18px; display:flex; align-items:center; gap:8px;}
        .logo i{color:var(--brand);}
        .logo span{background:linear-gradient(90deg,var(--brand),var(--brand2)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;}
        .navsec{font-size:.62rem; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin:14px 12px 6px;}
        .nav{display:flex; align-items:center; gap:11px; padding:9px 12px; border-radius:11px; color:var(--muted);
            font-size:.85rem; font-weight:500; margin-bottom:3px; transition:.18s; cursor:pointer;}
        .nav i{width:18px; text-align:center;}
        .nav:hover{background:var(--panel2); color:#fff;}
        .nav.active{background:linear-gradient(90deg,rgba(124,131,255,.28),rgba(176,107,255,.12)); color:#fff;}
        .nav .badge{margin-left:auto; background:var(--brand); color:#fff; font-size:.62rem; border-radius:999px; padding:1px 7px;}

        /* Main */
        .main{margin-left:var(--sw); padding:20px 26px 40px;}
        .top{display:flex; align-items:center; gap:14px; margin-bottom:22px;}
        .search{flex:1; max-width:460px; display:flex; align-items:center; gap:10px; padding:10px 14px; color:var(--muted); font-size:.85rem;}
        .top .ico{width:40px; height:40px; border-radius:11px; display:grid; place-items:center; color:var(--muted); cursor:pointer; position:relative;}
        .top .ico:hover{color:#fff;}
        .dot{position:absolute; top:9px; right:10px; width:7px; height:7px; border-radius:50%; background:var(--rose);}
        .prof{display:flex; align-items:center; gap:9px; padding:6px 12px 6px 6px;}
        .ava{width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; display:grid; place-items:center; font-weight:700; font-size:.8rem;}
        .pname{font-size:.82rem; font-weight:600; color:#fff; line-height:1;}
        .prole{font-size:.68rem; color:var(--muted);}

        .hi{margin-bottom:20px;}
        .hi h1{font-size:1.5rem; margin:0 0 4px;}
        .hi p{color:var(--muted); margin:0; font-size:.9rem;}

        /* KPI */
        .kpis{display:grid; grid-template-columns:repeat(6,1fr); gap:14px; margin-bottom:20px;}
        .kpi{padding:16px; position:relative; overflow:hidden;}
        .kpi .kico{width:38px; height:38px; border-radius:11px; display:grid; place-items:center; font-size:1rem; margin-bottom:12px;}
        .kpi .kval{font-family:'Space Grotesk'; font-weight:700; font-size:1.5rem; line-height:1; color:#fff;}
        .kpi .kval small{font-size:.7rem; color:var(--muted); font-weight:500;}
        .kpi .klab{font-size:.74rem; color:var(--muted); margin-top:5px;}
        .kpi .ktrend{font-size:.66rem; margin-top:8px;}
        .up{color:var(--accent);} .down{color:var(--rose);}

        .grid{display:grid; gap:16px;}
        .g-2{grid-template-columns:1.6fr 1fr;}
        .g-3{grid-template-columns:1.4fr 1fr 1fr;}
        .card{padding:18px;}
        .card h3{font-size:.98rem; margin:0 0 2px;}
        .card .sub{font-size:.75rem; color:var(--muted); margin-bottom:14px;}
        .card-head{display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;}
        .btn{border:none; border-radius:10px; padding:8px 14px; font-size:.8rem; font-weight:600; cursor:pointer; color:#fff;
            background:linear-gradient(90deg,var(--brand),var(--brand2));}
        .btn-ghost{background:transparent; border:1px solid var(--border); color:var(--muted);}

        table{width:100%; border-collapse:collapse;}
        th{font-size:.68rem; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); text-align:left; padding:8px 10px; font-weight:600;}
        td{padding:11px 10px; font-size:.82rem; border-top:1px solid var(--border);}
        .cust{display:flex; align-items:center; gap:9px;}
        .cav{width:30px; height:30px; border-radius:50%; background:var(--panel2); border:1px solid var(--border); display:grid; place-items:center; font-size:.72rem; color:#fff; font-weight:600;}
        .pill{font-size:.68rem; font-weight:600; padding:3px 10px; border-radius:999px; display:inline-block;}
        .p-green{background:rgba(41,224,200,.14); color:var(--accent);}
        .p-blue{background:rgba(124,131,255,.16); color:#a9b0ff;}
        .p-amber{background:rgba(251,191,36,.14); color:var(--amber);}
        .p-gray{background:rgba(255,255,255,.06); color:var(--muted);}
        .qa{color:var(--muted); cursor:pointer; padding:5px;} .qa:hover{color:#fff;}

        .hk{display:flex; align-items:center; gap:12px; padding:11px 0; border-top:1px solid var(--border);}
        .hk:first-of-type{border-top:none;}
        .hk .rn{width:40px; height:40px; border-radius:11px; background:var(--panel2); display:grid; place-items:center; font-family:'Space Grotesk'; font-weight:700; color:#fff; font-size:.85rem;}
        .act{display:flex; gap:11px; padding:10px 0; border-top:1px solid var(--border); font-size:.8rem;}
        .act:first-of-type{border-top:none;}
        .act .aico{width:30px; height:30px; border-radius:9px; background:var(--panel2); display:grid; place-items:center; color:var(--brand); font-size:.75rem; flex-shrink:0;}
        .act .atime{color:var(--muted); font-size:.68rem;}
        .empty{color:var(--muted); font-size:.82rem; text-align:center; padding:22px 0;}

        .backbar{position:fixed; bottom:16px; left:calc(var(--sw) + 26px); z-index:30; font-size:.8rem;}
        .backbar a{background:rgba(11,17,34,.9); border:1px solid var(--border); border-radius:999px; padding:8px 16px; color:var(--brand); backdrop-filter:blur(8px);}

        @media (max-width:1200px){ .kpis{grid-template-columns:repeat(3,1fr);} .g-3{grid-template-columns:1fr 1fr;} }
        @media (max-width:900px){ .side{transform:translateX(-100%); transition:.25s;} .side.open{transform:none;} .main{margin-left:0;}
            .g-2,.g-3{grid-template-columns:1fr;} .backbar{left:16px;} .burger{display:grid!important;} }
        @media (max-width:560px){ .kpis{grid-template-columns:repeat(2,1fr);} }
        .burger{display:none; width:40px; height:40px; border-radius:11px; place-items:center; color:#fff; background:var(--panel2); border:1px solid var(--border);}
    </style>
</head>
<body>
<div class="cosmos"></div>
@php
    $u = auth()->user();
    $initial = strtoupper(mb_substr($u->name ?? 'U', 0, 1));
    $nav = [
        ['Général', [
            ['fa-gauge-high','Dashboard','dashboard.index',true],
            ['fa-calendar-check','Réservations','transaction.index',false],
            ['fa-bed','Chambres','room.index',false],
            ['fa-users','Clients','customer.index',false],
        ]],
        ['Opérations', [
            ['fa-broom','Housekeeping','housekeeping.index',false],
            ['fa-cash-register','Caisse','cashier.dashboard',false],
            ['fa-file-invoice','Factures',null,false],
            ['fa-user-tie','Personnel','user.index',false],
        ]],
        ['Pilotage', [
            ['fa-chart-line','Rapports','reports.index',false],
            ['fa-gear','Paramètres','hotel.settings.edit',false],
        ]],
    ];
    $fmt = fn ($n) => number_format((float) $n, 0, ',', ' ');
@endphp

<!-- SIDEBAR -->
<aside class="side" id="side">
    <div class="logo"><i class="fas fa-location-dot"></i> check<span>inHub</span></div>
    @foreach ($nav as [$section, $items])
        <div class="navsec">{{ $section }}</div>
        @foreach ($items as [$icon, $label, $route, $active])
            <a class="nav {{ $active ? 'active' : '' }}" href="{{ $route && \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#' }}">
                <i class="fas {{ $icon }}"></i> {{ $label }}
                @if ($label === 'Housekeeping' && $housekeeping->count())<span class="badge">{{ $housekeeping->count() }}</span>@endif
            </a>
        @endforeach
    @endforeach
</aside>

<!-- MAIN -->
<div class="main">
    <div class="top">
        <div class="burger" onclick="document.getElementById('side').classList.toggle('open')"><i class="fas fa-bars"></i></div>
        <div class="search glass"><i class="fas fa-magnifying-glass"></i> Rechercher une réservation, un client…</div>
        <div style="flex:1"></div>
        <div class="ico glass"><i class="fas fa-bell"></i><span class="dot"></span></div>
        <div class="ico glass"><i class="fas fa-globe"></i></div>
        <div class="prof glass">
            <div class="ava">{{ $initial }}</div>
            <div><div class="pname">{{ $u->name }}</div><div class="prole">{{ $u->role }}</div></div>
        </div>
    </div>

    <div class="hi">
        <h1>Bonjour {{ \Illuminate\Support\Str::of($u->name)->explode(' ')->first() }} 👋</h1>
        <p>Voici l'activité de votre établissement aujourd'hui, {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}.</p>
    </div>

    <!-- KPI -->
    <div class="kpis">
        @php
            $cards = [
                ['fa-chart-pie', 'rgba(124,131,255,.18)', 'var(--brand)', $stats['occupancyRate'].'%', 'Taux d\'occupation', $stats['occupiedRooms'].'/'.$stats['totalRooms'].' chambres'],
                ['fa-right-to-bracket', 'rgba(41,224,200,.16)', 'var(--accent)', $stats['todayArrivals'], 'Arrivées du jour', 'check-in attendus'],
                ['fa-right-from-bracket', 'rgba(251,113,133,.16)', 'var(--rose)', $stats['todayDepartures'], 'Départs du jour', 'check-out attendus'],
                ['fa-door-open', 'rgba(176,107,255,.16)', 'var(--brand2)', $stats['availableRooms'], 'Chambres disponibles', 'prêtes à réserver'],
                ['fa-coins', 'rgba(251,191,36,.16)', 'var(--amber)', $fmt($revenueToday), 'Chiffre d\'affaires', 'encaissé aujourd\'hui', 'FCFA'],
                ['fa-user-check', 'rgba(124,131,255,.18)', 'var(--brand)', $stats['activeGuests'], 'Clients présents', 'séjours en cours'],
            ];
        @endphp
        @foreach ($cards as $c)
            <div class="kpi glass">
                <div class="kico" style="background:{{ $c[1] }}; color:{{ $c[2] }}"><i class="fas {{ $c[0] }}"></i></div>
                <div class="kval">{{ $c[3] }}@if (!empty($c[6]))<small> {{ $c[6] }}</small>@endif</div>
                <div class="klab">{{ $c[4] }}</div>
                <div class="ktrend up"><i class="fas fa-circle" style="font-size:.4rem; vertical-align:middle"></i> {{ $c[5] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Charts row -->
    <div class="grid g-3" style="margin-bottom:16px;">
        <div class="card glass">
            <div class="card-head"><div><h3>Évolution des réservations</h3><div class="sub">7 derniers jours</div></div></div>
            <canvas id="chTrend" height="150"></canvas>
        </div>
        <div class="card glass">
            <div class="card-head"><div><h3>Revenus mensuels</h3><div class="sub">6 derniers mois</div></div></div>
            <canvas id="chRev" height="150"></canvas>
        </div>
        <div class="card glass">
            <div class="card-head"><div><h3>Répartition des chambres</h3><div class="sub">temps réel</div></div></div>
            <canvas id="chRooms" height="150"></canvas>
        </div>
    </div>

    <!-- Table + side widgets -->
    <div class="grid g-2">
        <div class="card glass">
            <div class="card-head">
                <div><h3>Réservations du jour</h3><div class="sub">{{ $transactions->count() }} séjour(s) actif(s)</div></div>
                <a href="{{ \Illuminate\Support\Facades\Route::has('transaction.index') ? route('transaction.index') : '#' }}" class="btn btn-ghost">Tout voir</a>
            </div>
            <div style="overflow-x:auto;">
            <table>
                <thead><tr><th>Client</th><th>Chambre</th><th>Arrivée</th><th>Départ</th><th>Statut</th><th></th></tr></thead>
                <tbody>
                @forelse ($transactions as $t)
                    @php
                        $st = $t->status;
                        $pill = ['active'=>'p-green','reservation'=>'p-blue','completed'=>'p-gray','pending'=>'p-amber'][$st] ?? 'p-gray';
                        $stLabel = ['active'=>'En cours','reservation'=>'Réservé','completed'=>'Terminé','pending'=>'En attente'][$st] ?? ucfirst($st);
                    @endphp
                    <tr>
                        <td><div class="cust"><div class="cav">{{ strtoupper(mb_substr($t->customer->name ?? '?',0,1)) }}</div>{{ $t->customer->name ?? '—' }}</div></td>
                        <td>{{ $t->room->number ?? '—' }}</td>
                        <td>{{ optional($t->check_in)->locale('fr')->isoFormat('D MMM') }}</td>
                        <td>{{ optional($t->check_out)->locale('fr')->isoFormat('D MMM') }}</td>
                        <td><span class="pill {{ $pill }}">{{ $stLabel }}</span></td>
                        <td><i class="fas fa-ellipsis qa"></i></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Aucune réservation active aujourd'hui.</td></tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:16px;">
            <!-- Housekeeping -->
            <div class="card glass">
                <div class="card-head"><div><h3>Housekeeping</h3><div class="sub">chambres à traiter</div></div>
                    <a href="{{ \Illuminate\Support\Facades\Route::has('housekeeping.index') ? route('housekeeping.index') : '#' }}" class="btn btn-ghost">Gérer</a></div>
                @forelse ($housekeeping as $r)
                    <div class="hk">
                        <div class="rn">{{ $r->number }}</div>
                        <div style="flex:1"><div style="color:#fff; font-size:.85rem; font-weight:600;">Chambre {{ $r->number }}</div>
                            <div style="color:var(--muted); font-size:.72rem;">{{ optional($r->roomStatus)->name ?? 'À nettoyer' }}</div></div>
                        <span class="pill {{ $r->room_status_id == \App\Enums\RoomStatus::Cleaning->value ? 'p-blue' : 'p-amber' }}">
                            {{ $r->room_status_id == \App\Enums\RoomStatus::Cleaning->value ? 'En nettoyage' : 'À nettoyer' }}
                        </span>
                    </div>
                @empty
                    <div class="empty"><i class="fas fa-circle-check" style="color:var(--accent)"></i> Toutes les chambres sont propres.</div>
                @endforelse
            </div>

            <!-- Activités -->
            <div class="card glass">
                <div class="card-head"><div><h3>Dernières activités</h3><div class="sub">check-in, check-out, paiements</div></div></div>
                @forelse ($activities as $a)
                    <div class="act">
                        <div class="aico"><i class="fas fa-wave-square"></i></div>
                        <div style="flex:1">
                            <div style="color:#fff;">{{ \Illuminate\Support\Str::limit($a->description, 60) }}</div>
                            <div class="atime">{{ optional($a->causer)->name ? $a->causer->name.' · ' : '' }}{{ $a->created_at->locale('fr')->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty">Aucune activité récente.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="backbar"><a href="{{ route('dashboard.index') }}"><i class="fas fa-arrow-left"></i> Revenir au tableau de bord actuel</a></div>

<script>
    const CSS = getComputedStyle(document.documentElement);
    const brand = CSS.getPropertyValue('--brand').trim() || '#7c83ff';
    const brand2 = CSS.getPropertyValue('--brand2').trim() || '#b06bff';
    const accent = CSS.getPropertyValue('--accent').trim() || '#29e0c8';
    const muted = CSS.getPropertyValue('--muted').trim() || '#93a0bd';
    const grid = 'rgba(255,255,255,.06)';
    Chart.defaults.color = muted;
    Chart.defaults.font.family = 'Inter';
    Chart.defaults.borderColor = grid;

    const trend = @json($trend);
    const revenue = @json($revenue);
    const roomDist = @json($roomDist);

    // Évolution réservations (ligne)
    (function(){
        const ctx = document.getElementById('chTrend'); if(!ctx) return;
        const g = ctx.getContext('2d').createLinearGradient(0,0,0,160);
        g.addColorStop(0,'rgba(124,131,255,.35)'); g.addColorStop(1,'rgba(124,131,255,0)');
        new Chart(ctx,{type:'line',data:{labels:trend.map(t=>t.label),datasets:[{data:trend.map(t=>t.count),
            borderColor:brand,backgroundColor:g,fill:true,tension:.4,pointRadius:3,pointBackgroundColor:brand,borderWidth:2.5}]},
            options:{plugins:{legend:{display:false}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,grid:{color:grid},ticks:{precision:0}}}}});
    })();
    // Revenus mensuels (barres)
    (function(){
        const ctx = document.getElementById('chRev'); if(!ctx) return;
        new Chart(ctx,{type:'bar',data:{labels:revenue.map(r=>r.label),datasets:[{data:revenue.map(r=>r.total),
            backgroundColor:'rgba(176,107,255,.55)',hoverBackgroundColor:brand2,borderRadius:6,maxBarThickness:26}]},
            options:{plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>new Intl.NumberFormat('fr-FR').format(c.raw)+' FCFA'}}},
            scales:{x:{grid:{display:false}},y:{beginAtZero:true,grid:{color:grid},ticks:{callback:v=>v>=1000?(v/1000)+'k':v}}}}});
    })();
    // Répartition chambres (donut)
    (function(){
        const ctx = document.getElementById('chRooms'); if(!ctx) return;
        new Chart(ctx,{type:'doughnut',data:{labels:['Occupées','Disponibles','Autres'],
            datasets:[{data:[roomDist.occupied,roomDist.available,roomDist.other],
            backgroundColor:[brand,accent,brand2],borderWidth:0,hoverOffset:6}]},
            options:{cutout:'68%',plugins:{legend:{position:'bottom',labels:{boxWidth:10,padding:14,font:{size:11}}}}}});
    })();
</script>
</body>
</html>
