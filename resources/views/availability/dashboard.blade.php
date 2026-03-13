@extends('template.master')

@section('title', 'Dashboard disponibilité')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── PALETTE 3 COULEURS : VERT / BLANC / GRIS ── */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;

    --white:   #ffffff;
    --surface: #f7f9f7;

    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: var(--surface);
    color: var(--s800);
    font-family: var(--font);
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
}

/* ══════════════════════════════════════
   ① TOPBAR — sticky
══════════════════════════════════════ */
.db-topbar {
    position: sticky; top: 0; z-index: 200;
    height: 54px;
    display: flex; align-items: center; gap: 12px;
    padding: 0 24px;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(10px);
    border-bottom: 1.5px solid var(--s200);
    box-shadow: var(--shadow-xs);
}
.db-topbar__brand {
    display: flex; align-items: center; gap: 10px;
}
.db-topbar__icon {
    width: 32px; height: 32px;
    background: var(--g600); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: .85rem;
    box-shadow: 0 2px 8px rgba(46,133,64,.3);
}
.db-topbar__title {
    font-size: .9rem; font-weight: 700;
    color: var(--s900); letter-spacing: -.2px;
}
.db-topbar__sub { color: var(--s400); font-weight: 400; }
.db-topbar__clock {
    margin-left: auto;
    font-family: var(--mono);
    font-size: .78rem;
    color: var(--s400);
    white-space: nowrap;
    padding: 5px 12px;
    background: var(--s50);
    border: 1.5px solid var(--s200);
    border-radius: 100px;
}
.pulse {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--g500); flex-shrink: 0;
    animation: blink 2.2s ease-in-out infinite;
}
@keyframes blink {
    0%,100% { opacity:1; box-shadow:0 0 0 0 rgba(46,133,64,.5); }
    50%      { opacity:.4; box-shadow:0 0 0 5px rgba(46,133,64,0); }
}

/* ══════════════════════════════════════
   ② KPI STRIP — sticky
══════════════════════════════════════ */
.db-kpibar {
    position: sticky; top: 54px; z-index: 190;
    height: 88px;
    display: grid; grid-template-columns: repeat(4, 1fr);
    background: var(--white);
    border-bottom: 1.5px solid var(--s200);
    box-shadow: var(--shadow-xs);
}
.kpi {
    display: flex; align-items: center; gap: 12px;
    padding: 0 20px;
    border-right: 1.5px solid var(--s100);
    transition: var(--transition);
}
.kpi:last-child { border-right: none; }
.kpi:hover { background: var(--g50); }

.kpi__icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.kpi__label {
    font-size: .62rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--s400); white-space: nowrap;
}
.kpi__value {
    font-size: 1.5rem; font-weight: 700;
    letter-spacing: -.5px; line-height: 1;
    margin-top: 2px; color: var(--s900);
    font-family: var(--mono);
}
.kpi__bar {
    height: 2px; border-radius: 99px;
    background: var(--s100); margin-top: 7px; overflow: hidden;
}
.kpi__bar-fill { height: 100%; border-radius: 99px; }

/* ══════════════════════════════════════
   ③ QUICK ACTIONS BAR — sticky
══════════════════════════════════════ */
.db-qabar {
    position: sticky; top: 142px; z-index: 180;
    height: 52px;
    display: flex; align-items: center; gap: 6px;
    padding: 0 24px;
    background: rgba(255,255,255,.97);
    backdrop-filter: blur(10px);
    border-bottom: 1.5px solid var(--s200);
    overflow-x: auto; scrollbar-width: none;
}
.db-qabar::-webkit-scrollbar { display: none; }
.db-qabar__label {
    font-size: .62rem; font-weight: 700;
    color: var(--s400); text-transform: uppercase;
    letter-spacing: .6px; white-space: nowrap;
    margin-right: 4px; flex-shrink: 0;
}
.qa-sep { width: 1px; height: 20px; background: var(--s200); flex-shrink: 0; margin: 0 4px; }

.qa {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 13px; border-radius: var(--r);
    font-size: .75rem; font-weight: 600; white-space: nowrap;
    text-decoration: none; border: 1.5px solid var(--s200);
    background: var(--white); color: var(--s600);
    transition: var(--transition); flex-shrink: 0;
    font-family: var(--font);
}
.qa i { font-size: .7rem; }
.qa:hover {
    border-color: var(--g300); background: var(--g50);
    color: var(--g700); transform: translateY(-1px);
    box-shadow: var(--shadow-xs); text-decoration: none;
}
.qa--primary {
    border-color: var(--g200); color: var(--g700);
    background: var(--g50);
}
.qa--primary:hover { background: var(--g100); border-color: var(--g400); color: var(--g800); }

.qa--dark {
    border-color: var(--s300); color: var(--s700);
    background: var(--s50);
}
.qa--dark:hover { background: var(--s100); border-color: var(--s500); color: var(--s900); }

/* ══════════════════════════════════════
   ④ BODY
══════════════════════════════════════ */
.db-body {
    padding: 18px 24px 64px;
    max-width: 1700px;
    margin: 0 auto;
    display: flex; flex-direction: column; gap: 16px;
}

.grid-main { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
.grid-sec  { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }

/* ══════════════════════════════════════
   CARDS
══════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    animation: fadeUp .28s ease both;
}
.card:hover { border-color: var(--s200); box-shadow: var(--shadow-md); }

@keyframes fadeUp {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}

.card__head {
    display: flex; align-items: center; gap: 9px;
    padding: 13px 18px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--s50); flex-shrink: 0;
}
.card__icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; flex-shrink: 0;
}
.card__title {
    font-size: .8rem; font-weight: 700; color: var(--s800);
}
.card__badge { margin-left: auto; flex-shrink: 0; }
.card__body {
    padding: 16px 18px; overflow-y: auto; flex: 1; max-height: 320px;
}
.card__body--nogrow { max-height: none; }
.card__body::-webkit-scrollbar { width: 3px; }
.card__body::-webkit-scrollbar-thumb { background: var(--s200); border-radius: 99px; }
.card__foot {
    padding: 10px 18px; border-top: 1.5px solid var(--s100);
    display: flex; justify-content: center; flex-shrink: 0;
    background: var(--s50);
}

/* ══════════════════════════════════════
   BADGES
══════════════════════════════════════ */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 9px; border-radius: 100px;
    font-size: .68rem; font-weight: 600; white-space: nowrap;
}
.badge--green  { background: var(--g100);  color: var(--g700); }
.badge--dark   { background: var(--s100);  color: var(--s700); }
.badge--muted  { background: var(--s100);  color: var(--s500); }
.badge--soft   { background: var(--g50);   color: var(--g600); }
.badge--outline { background: transparent; color: var(--s600); border: 1.5px solid var(--s200); }

/* ══════════════════════════════════════
   MINI BUTTONS
══════════════════════════════════════ */
.btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: var(--r);
    font-size: .72rem; font-weight: 600;
    text-decoration: none; border: 1.5px solid;
    transition: var(--transition); white-space: nowrap;
    cursor: pointer; font-family: var(--font);
}
.btn--green {
    color: var(--g600); border-color: var(--g200); background: var(--g50);
}
.btn--green:hover { background: var(--g600); color: white; border-color: var(--g600); text-decoration: none; }
.btn--dark {
    color: var(--s600); border-color: var(--s200); background: var(--s50);
}
.btn--dark:hover { background: var(--s200); color: var(--s900); text-decoration: none; }
.btn--outline {
    color: var(--s500); border-color: var(--s200); background: var(--white);
}
.btn--outline:hover { background: var(--s50); color: var(--s800); text-decoration: none; }
.btn--sm { padding: 2px 8px; font-size: .65rem; }
.btn--icon { padding: 4px 9px; }

/* ══════════════════════════════════════
   TABLE
══════════════════════════════════════ */
.tbl { width: 100%; border-collapse: collapse; }
.tbl th {
    padding: 6px 10px; font-size: .62rem; font-weight: 600;
    color: var(--s400); text-transform: uppercase; letter-spacing: .5px;
    text-align: left; border-bottom: 1.5px solid var(--s100); white-space: nowrap;
    background: var(--s50);
}
.tbl td {
    padding: 10px 10px; font-size: .8rem;
    border-bottom: 1px solid var(--s100);
    vertical-align: middle; color: var(--s700);
}
.tbl tr:last-child td { border-bottom: none; }
.tbl tr:hover td { background: var(--g50); }

/* ══════════════════════════════════════
   SECTION SALE (3 colonnes)
══════════════════════════════════════ */
.dirty-card {
    border-left: 3px solid var(--g400);
}
.dirty-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
    margin-bottom: 16px;
}
.dirty-col {
    background: var(--s50); border-radius: var(--rl);
    border: 1.5px solid var(--s100); padding: 14px;
}
.dirty-col-head {
    display: flex; align-items: center; gap: 8px; margin-bottom: 12px;
}
.dirty-col-icon {
    width: 30px; height: 30px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; color: white; flex-shrink: 0;
}
.dirty-col-title { font-size: .8rem; font-weight: 700; color: var(--s800); }
.dirty-col-sub   { font-size: .68rem; color: var(--s400); margin-top: 1px; }
.dirty-inner {
    background: var(--white); border-radius: var(--r);
    border: 1.5px solid var(--s100); padding: 8px;
}
.dirty-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 6px 0; border-bottom: 1px solid var(--s100); font-size: .78rem;
}
.dirty-row:last-child { border-bottom: none; }
.dirty-row-name { font-weight: 600; color: var(--s900); }
.dirty-row-type { font-size: .65rem; color: var(--s400); margin-left: 5px; }
.dirty-empty {
    padding: 18px; text-align: center;
    font-size: .75rem; color: var(--s400);
}
.dirty-empty i { display: block; font-size: 1.3rem; color: var(--g300); margin-bottom: 6px; }
.dirty-foot {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 12px; border-top: 1.5px solid var(--s100);
    font-size: .78rem; color: var(--s500); flex-wrap: wrap; gap: 10px;
}
.dirty-foot-stats { display: flex; gap: 18px; flex-wrap: wrap; }
.dirty-foot-stat  { display: flex; align-items: center; gap: 5px; }
.dirty-foot-stat strong { color: var(--s900); }

/* ══════════════════════════════════════
   OCCUPATION PAR TYPE
══════════════════════════════════════ */
.occ-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px;
}
.occ-item {
    background: var(--s50); border: 1.5px solid var(--s100);
    border-radius: var(--rl); padding: 14px; transition: var(--transition);
}
.occ-item:hover { border-color: var(--g300); background: var(--g50); }
.occ-name { font-size: .78rem; font-weight: 700; margin-bottom: 10px; color: var(--s800); }
.occ-bar-wrap { height: 4px; border-radius: 99px; background: var(--s200); overflow: hidden; margin-bottom: 10px; }
.occ-bar-fill { height: 100%; border-radius: 99px; }
.occ-nums { display: flex; justify-content: space-between; align-items: flex-end; }
.occ-num  { text-align: center; }
.occ-num span  { display: block; font-size: 1.1rem; font-weight: 700; line-height: 1; color: var(--s900); font-family: var(--mono); }
.occ-num small { font-size: .6rem; color: var(--s400); text-transform: uppercase; letter-spacing: .3px; }

/* ══════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════ */
.empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 8px; padding: 36px 20px;
    color: var(--s400); text-align: center;
}
.empty i  { font-size: 1.6rem; color: var(--s300); }
.empty p  { font-size: .78rem; line-height: 1.6; }

/* ══════════════════════════════════════
   TOAST
══════════════════════════════════════ */
.toast-box {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    background: var(--white); color: var(--g600);
    border: 1.5px solid var(--g200); border-radius: var(--r);
    padding: 9px 16px; font-size: .75rem; font-weight: 600;
    display: flex; align-items: center; gap: 7px;
    box-shadow: var(--shadow-lg); animation: fadeUp .25s ease;
    font-family: var(--font);
}

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media(max-width:1100px) {
    .grid-main, .grid-sec { grid-template-columns: 1fr; }
    .dirty-grid { grid-template-columns: 1fr; }
}
@media(max-width:680px) {
    .db-topbar__sub { display: none; }
    .db-body { padding: 12px 14px 60px; gap: 12px; }
    .db-kpibar { grid-template-columns: repeat(2, 1fr); height: calc(88px * 2); }
    .kpi { border-right: none; border-bottom: 1.5px solid var(--s100); }
    .kpi:nth-child(odd) { border-right: 1.5px solid var(--s100); }
    .kpi:nth-child(3), .kpi:nth-child(4) { border-bottom: none; }
    .db-qabar { padding: 0 14px; }
    .db-qabar__label { display: none; }
    .qa-sep { display: none; }
    .dirty-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ① TOPBAR --}}
<div class="db-topbar">
    <div class="db-topbar__brand">
        <div class="db-topbar__icon"><i class="fas fa-hotel"></i></div>
        <span class="db-topbar__title">
            Dashboard
            <span class="db-topbar__sub"> · Disponibilité</span>
        </span>
    </div>
    <div class="pulse"></div>
    <span class="db-topbar__clock" id="live-clock">{{ now()->format('d/m/Y H:i:s') }}</span>
</div>

{{-- ② KPI STRIP --}}
<div class="db-kpibar">

    <div class="kpi">
        <div class="kpi__icon" style="background:var(--s100);color:var(--s600)">
            <i class="fas fa-bed"></i>
        </div>
        <div>
            <div class="kpi__label">Total chambres</div>
            <div class="kpi__value">{{ $stats['total_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:100%;background:var(--s400)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        @php $pA = $stats['total_rooms'] > 0 ? ($stats['available_rooms'] / $stats['total_rooms']) * 100 : 0; @endphp
        <div class="kpi__icon" style="background:var(--g100);color:var(--g600)">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="kpi__label">Disponibles</div>
            <div class="kpi__value" style="color:var(--g600)">{{ $stats['available_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $pA }}%;background:var(--g500)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        @php $pO = $stats['total_rooms'] > 0 ? ($stats['occupied_rooms'] / $stats['total_rooms']) * 100 : 0; @endphp
        <div class="kpi__icon" style="background:var(--s100);color:var(--s600)">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="kpi__label">Occupées</div>
            <div class="kpi__value">{{ $stats['occupied_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $pO }}%;background:var(--s400)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi__icon" style="background:var(--g50);color:var(--g500)">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <div class="kpi__label">Taux occupation</div>
            <div class="kpi__value" style="color:var(--g600)">{{ number_format($stats['occupancy_rate'], 1) }}%</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $stats['occupancy_rate'] }}%;background:var(--g400)"></div>
            </div>
        </div>
    </div>

</div>

{{-- ③ QUICK ACTIONS BAR --}}
<div class="db-qabar">
    <span class="db-qabar__label">Actions</span>

    <a href="{{ route('transaction.reservation.createIdentity') }}" class="qa qa--primary">
        <i class="fas fa-plus-circle"></i> Réservation
    </a>
    <a href="{{ route('checkin.index') }}" class="qa qa--dark">
        <i class="fas fa-door-open"></i> Check-in / Check-out
    </a>

    <div class="qa-sep"></div>

    <a href="{{ route('availability.calendar') }}" class="qa">
        <i class="fas fa-calendar-alt"></i> Calendrier
    </a>
    <a href="{{ route('availability.search') }}" class="qa qa--primary">
        <i class="fas fa-search"></i> Rechercher
    </a>
    <a href="{{ route('availability.inventory') }}" class="qa">
        <i class="fas fa-clipboard-list"></i> Inventaire
    </a>

    <div class="qa-sep"></div>

    <a href="{{ route('housekeeping.index') }}" class="qa">
        <i class="fas fa-broom"></i> Nettoyage
    </a>
</div>

{{-- ④ BODY --}}
<div class="db-body">

    {{-- GRILLE PRINCIPALE --}}
    <div class="grid-main">

        {{-- Chambres disponibles maintenant --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--g100);color:var(--g600)">
                    <i class="fas fa-bed"></i>
                </div>
                <span class="card__title">Chambres disponibles maintenant</span>
                <span class="card__badge">
                    <span class="badge badge--green">
                        {{ $availableNow->count() }} libre{{ $availableNow->count() > 1 ? 's' : '' }}
                    </span>
                </span>
            </div>

            <div class="card__body">
                @if($availableNow->count() > 0)
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>N°</th><th>Type</th><th>Prix / nuit</th><th>Cap.</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableNow as $room)
                        <tr>
                            <td><span class="badge badge--green">{{ $room->number }}</span></td>
                            <td style="color:var(--s500)">{{ $room->type->name ?? 'Standard' }}</td>
                            <td>
                                <span style="color:var(--g600);font-weight:700;font-family:var(--mono);font-size:.78rem">
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td style="color:var(--s400)">
                                <i class="fas fa-user" style="font-size:.65rem;margin-right:2px"></i>{{ $room->capacity }}
                            </td>
                            <td>
                                <a href="{{ route('availability.room.detail', $room->id) }}" class="btn btn--green btn--icon">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty">
                    <i class="fas fa-bed"></i>
                    <p>Aucune chambre disponible<br>Toutes les chambres sont occupées ou en maintenance</p>
                </div>
                @endif
            </div>

            @if($availableNow->count() > 0)
            <div class="card__foot">
                <a href="{{ route('availability.search') }}" class="btn btn--green">
                    <i class="fas fa-search"></i> Rechercher des disponibilités
                </a>
            </div>
            @endif
        </div>

        {{-- Maintenance / Nettoyage --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--s100);color:var(--s600)">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="card__title">Maintenance / Nettoyage</span>
                <span class="card__badge">
                    <span class="badge badge--dark">{{ $unavailableRooms->count() }}</span>
                </span>
            </div>

            <div class="card__body">
                @if($unavailableRooms->count() > 0)
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>N°</th><th>Type</th><th>Statut</th><th>Depuis</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unavailableRooms as $room)
                        <tr>
                            <td>
                                <span class="badge {{ $room->room_status_id == 2 ? 'badge--dark' : 'badge--muted' }}">
                                    {{ $room->number }}
                                </span>
                            </td>
                            <td style="color:var(--s500)">{{ $room->type->name ?? 'Standard' }}</td>
                            <td>
                                <span class="badge {{ $room->room_status_id == 2 ? 'badge--dark' : 'badge--muted' }}">
                                    <i class="fas fa-{{ $room->room_status_id == 2 ? 'tools' : 'broom' }}"></i>
                                    {{ $room->roomStatus->name ?? 'Indisponible' }}
                                </span>
                            </td>
                            <td style="color:var(--s400);font-size:.72rem">
                                {{ $room->updated_at ? $room->updated_at->diffForHumans() : 'N/A' }}
                            </td>
                            <td>
                                @if(isset($room->room_status_id) && $room->room_status_id == 3)
                                    <a href="{{ route('housekeeping.finish-cleaning', $room->id) }}" class="btn btn--green">
                                        <i class="fas fa-check"></i> OK
                                    </a>
                                @else
                                    <span style="font-size:.7rem;color:var(--s400)">En cours…</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty">
                    <i class="fas fa-check-circle" style="color:var(--g400)"></i>
                    <p>Aucune chambre en maintenance<br>Tout est opérationnel</p>
                </div>
                @endif
            </div>

            @if($unavailableRooms->count() > 0)
            <div class="card__foot">
                <a href="{{ route('housekeeping.index') }}" class="btn btn--dark">
                    <i class="fas fa-broom"></i> Gestion nettoyage
                </a>
            </div>
            @endif
        </div>

    </div>

    {{-- SECTION CHAMBRES SALES --}}
    <div class="card dirty-card">
        <div class="card__head">
            <div class="card__icon" style="background:var(--s100);color:var(--s600)">
                <i class="fas fa-broom"></i>
            </div>
            <span class="card__title">Chambres sales · Disponibilité après nettoyage</span>
            <span class="card__badge">
                <span class="badge badge--dark">{{ $stats['dirty_rooms'] ?? 0 }} sale(s)</span>
            </span>
        </div>

        <div class="card__body card__body--nogrow">
            @php
                $dirtyOccupied   = $dirtyOccupied   ?? collect();
                $dirtyUnoccupied = $dirtyUnoccupied  ?? collect();
                $roomsToBeFreed  = $roomsToBeFreed   ?? collect();
            @endphp

            <div class="dirty-grid">

                {{-- 1. Sales OCCUPÉES --}}
                <div class="dirty-col">
                    <div class="dirty-col-head">
                        <div class="dirty-col-icon" style="background:var(--s600)">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="dirty-col-title">Occupées (sales)</div>
                            <div class="dirty-col-sub">Client présent · À nettoyer après départ</div>
                        </div>
                    </div>

                    @if($dirtyOccupied->count() > 0)
                    <div class="dirty-inner">
                        @foreach($dirtyOccupied->take(3) as $room)
                        <div class="dirty-row">
                            <div>
                                <span class="dirty-row-name">Ch. {{ $room->number }}</span>
                                <span class="dirty-row-type">{{ $room->type->name ?? 'Std' }}</span>
                            </div>
                            <span class="badge badge--muted">{{ $room->capacity }}p</span>
                        </div>
                        @endforeach
                        @if($dirtyOccupied->count() > 3)
                        <div style="text-align:center;margin-top:8px">
                            <a href="{{ route('housekeeping.to-clean') }}" class="btn btn--dark btn--sm">
                                +{{ $dirtyOccupied->count()-3 }} autres
                            </a>
                        </div>
                        @endif
                    </div>
                    <div style="margin-top:8px;font-size:.7rem;color:var(--s400)">
                        <i class="fas fa-clock"></i> Nettoyage après check-out
                    </div>
                    @else
                    <div class="dirty-inner">
                        <div class="dirty-empty">
                            <i class="fas fa-check-circle" style="color:var(--g400)"></i>
                            Aucune chambre avec client présent
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 2. Sales NON OCCUPÉES --}}
                <div class="dirty-col" style="border-color:var(--g200);background:var(--g50)">
                    <div class="dirty-col-head">
                        <div class="dirty-col-icon" style="background:var(--g500)">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div>
                            <div class="dirty-col-title" style="color:var(--g700)">Non occupées (sales)</div>
                            <div class="dirty-col-sub">Client parti · À nettoyer maintenant</div>
                        </div>
                    </div>

                    @if($dirtyUnoccupied->count() > 0)
                    <div class="dirty-inner">
                        @foreach($dirtyUnoccupied->take(3) as $room)
                        <div class="dirty-row">
                            <div>
                                <span class="dirty-row-name">Ch. {{ $room->number }}</span>
                                <span class="dirty-row-type">{{ $room->type->name ?? 'Std' }}</span>
                            </div>
                            <a href="{{ route('housekeeping.start-cleaning', $room->id) }}" class="btn btn--green btn--sm">
                                <i class="fas fa-broom"></i> Nettoyer
                            </a>
                        </div>
                        @endforeach
                        @if($dirtyUnoccupied->count() > 3)
                        <div style="text-align:center;margin-top:8px">
                            <a href="{{ route('housekeeping.to-clean') }}" class="btn btn--green btn--sm">
                                +{{ $dirtyUnoccupied->count()-3 }} autres
                            </a>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="dirty-inner">
                        <div class="dirty-empty">
                            <i class="fas fa-check-circle" style="color:var(--g400)"></i>
                            Aucune chambre à nettoyer
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 3. Départs aujourd'hui --}}
                <div class="dirty-col">
                    <div class="dirty-col-head">
                        <div class="dirty-col-icon" style="background:var(--s400)">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div>
                            <div class="dirty-col-title">Départs aujourd'hui</div>
                            <div class="dirty-col-sub">Seront libres après 12h</div>
                        </div>
                    </div>

                    @if($roomsToBeFreed->count() > 0)
                    <div class="dirty-inner">
                        @foreach($roomsToBeFreed->take(3) as $room)
                        <div class="dirty-row">
                            <div>
                                <span class="dirty-row-name">Ch. {{ $room->number }}</span>
                                <span class="dirty-row-type">{{ $room->type->name ?? 'Std' }}</span>
                            </div>
                            <span class="badge badge--muted">12h</span>
                        </div>
                        @endforeach
                        @if($roomsToBeFreed->count() > 3)
                        <div style="text-align:center;margin-top:8px">
                            <a href="{{ route('checkin.index') }}" class="btn btn--outline btn--sm">
                                +{{ $roomsToBeFreed->count()-3 }} autres
                            </a>
                        </div>
                        @endif
                    </div>
                    <div style="margin-top:8px;font-size:.7rem;color:var(--s400)">
                        <i class="fas fa-clock"></i> Largesse jusqu'à 14h
                    </div>
                    @else
                    <div class="dirty-inner">
                        <div class="dirty-empty">
                            <i class="fas fa-calendar-check" style="color:var(--s300)"></i>
                            Aucun départ prévu aujourd'hui
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Récapitulatif --}}
            <div class="dirty-foot">
                <div class="dirty-foot-stats">
                    <span class="dirty-foot-stat">
                        <i class="fas fa-bed" style="color:var(--s400)"></i>
                        Total sales : <strong>{{ $stats['dirty_rooms'] ?? 0 }}</strong>
                    </span>
                    <span class="dirty-foot-stat">
                        <i class="fas fa-user" style="color:var(--s600)"></i>
                        Clients présents : <strong>{{ $dirtyOccupied->count() }}</strong>
                    </span>
                    <span class="dirty-foot-stat">
                        <i class="fas fa-door-open" style="color:var(--g500)"></i>
                        À nettoyer maintenant : <strong>{{ $dirtyUnoccupied->count() }}</strong>
                    </span>
                    <span class="dirty-foot-stat">
                        <i class="fas fa-sign-out-alt" style="color:var(--s400)"></i>
                        Départs : <strong>{{ $roomsToBeFreed->count() }}</strong>
                    </span>
                </div>
                <a href="{{ route('housekeeping.to-clean') }}" class="btn btn--dark">
                    <i class="fas fa-broom"></i> Gérer le nettoyage
                </a>
            </div>
        </div>
    </div>

    {{-- GRILLE SECONDAIRE --}}
    <div class="grid-sec">

        {{-- Occupation par type --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--g100);color:var(--g600)">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <span class="card__title">Occupation par type de chambre</span>
                <span class="card__badge">
                    @php $avgOcc = collect($occupancyByType)->avg('percentage') ?? 0; @endphp
                    <span class="badge badge--soft">Moy. {{ number_format($avgOcc, 1) }}%</span>
                </span>
            </div>

            <div class="card__body card__body--nogrow">
                @if(count($occupancyByType) > 0)
                <div class="occ-grid">
                    @foreach($occupancyByType as $type)
                    @if(is_array($type) && isset($type['type']))
                    @php
                        $pct = $type['percentage'] ?? 0;
                        $col = $pct > 80 ? 'var(--g500)' : ($pct > 50 ? 'var(--g300)' : 'var(--s300)');
                        $bdg = $pct > 80 ? 'badge--green' : ($pct > 50 ? 'badge--soft' : 'badge--muted');
                    @endphp
                    <div class="occ-item">
                        <div class="occ-name">{{ $type['type'] }}</div>
                        <div class="occ-bar-wrap">
                            <div class="occ-bar-fill" style="width:{{ $pct }}%;background:{{ $col }}"></div>
                        </div>
                        <div class="occ-nums">
                            <div class="occ-num">
                                <span style="color:{{ $col }}">{{ $type['occupied'] ?? 0 }}</span>
                                <small>Occupées</small>
                            </div>
                            <div class="occ-num">
                                <span>{{ $type['total'] ?? 0 }}</span>
                                <small>Total</small>
                            </div>
                            <span class="badge {{ $bdg }}">{{ number_format($pct, 0) }}%</span>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <div class="empty">
                    <i class="fas fa-chart-pie"></i>
                    <p>Aucune donnée d'occupation disponible</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Informations --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--s100);color:var(--s500)">
                    <i class="fas fa-info-circle"></i>
                </div>
                <span class="card__title">Informations</span>
            </div>
            <div class="card__body">
                <div class="empty">
                    <i class="fas fa-clock" style="color:var(--g400)"></i>
                    <p>Les arrivées et départs<br>sont gérés dans les sections dédiées</p>
                    <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;justify-content:center">
                        <a href="{{ route('checkin.index') }}" class="btn btn--dark">
                            <i class="fas fa-door-open"></i> Check-in / Check-out
                        </a>
                        <a href="{{ route('availability.calendar') }}" class="btn btn--green">
                            <i class="fas fa-calendar-alt"></i> Calendrier
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* Horloge temps réel */
    const clockEl = document.getElementById('live-clock');
    function tick() {
        const n = new Date();
        const p = v => String(v).padStart(2, '0');
        clockEl.textContent =
            `${p(n.getDate())}/${p(n.getMonth()+1)}/${n.getFullYear()} ` +
            `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
    }
    setInterval(tick, 1000);

    /* Refresh silencieux toutes les 30s */
    setInterval(function () {
        fetch('{{ route("availability.dashboard") }}')
            .then(r => { if (r.ok) toast(); })
            .catch(() => {});
    }, 30000);

    function toast() {
        const el = document.createElement('div');
        el.className = 'toast-box';
        el.innerHTML = '<i class="fas fa-sync-alt"></i> Données actualisées';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2500);
    }
});
</script>
@endpush