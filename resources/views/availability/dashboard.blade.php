@extends('template.master')

@section('title', 'Dashboard disponibilité')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   STYLES DASHBOARD DISPONIBILITÉ - Version claire
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-soft: rgba(37, 99, 235, 0.08);
    --success: #10b981;
    --success-light: rgba(16, 185, 129, 0.08);
    --warning: #f59e0b;
    --warning-light: rgba(245, 158, 11, 0.08);
    --danger: #ef4444;
    --danger-light: rgba(239, 68, 68, 0.08);
    --info: #3b82f6;
    --info-light: rgba(59, 130, 246, 0.08);
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --white: #ffffff;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background: var(--gray-50);
    color: var(--gray-700);
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
}

/* ════════════════════════════════════════
   ① TOPBAR — sticky
════════════════════════════════════════ */
.db-topbar {
    position: sticky;
    top: 0;
    z-index: 200;
    height: 52px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 22px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--gray-200);
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.db-topbar__title {
    font-size: 15px;
    font-weight: 700;
    letter-spacing: -0.3px;
    white-space: nowrap;
    color: var(--gray-800);
}
.db-topbar__sub { color: var(--gray-500); font-weight: 400; }
.db-topbar__clock {
    margin-left: auto;
    font-family: 'DM Mono', monospace;
    font-size: 12px;
    color: var(--gray-500);
    white-space: nowrap;
}
.pulse {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--success);
    flex-shrink: 0;
    animation: blink 2.2s ease-in-out infinite;
}
@keyframes blink {
    0%,100% { opacity:1; box-shadow:0 0 0 0 rgba(16,185,129,.5); }
    50%      { opacity:.4; box-shadow:0 0 0 5px rgba(16,185,129,0); }
}

/* ════════════════════════════════════════
   ② KPI STRIP — sticky
════════════════════════════════════════ */
.db-kpibar {
    position: sticky;
    top: 52px;
    z-index: 190;
    height: 84px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.kpi {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 18px;
    border-right: 1px solid var(--gray-200);
    transition: var(--transition);
    cursor: default;
}
.kpi:last-child { border-right: none; }
.kpi:hover { background: var(--gray-50); }
.kpi__icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.kpi__label {
    font-size: 10.5px;
    color: var(--gray-500);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}
.kpi__value {
    font-size: 23px;
    font-weight: 700;
    letter-spacing: -.6px;
    line-height: 1;
    margin-top: 1px;
    color: var(--gray-800);
}
.kpi__bar {
    height: 2px;
    border-radius: 99px;
    background: var(--gray-200);
    margin-top: 6px;
    overflow: hidden;
}
.kpi__bar-fill { height: 100%; border-radius: 99px; }

/* ════════════════════════════════════════
   ③ QUICK ACTIONS BAR — sticky
════════════════════════════════════════ */
.db-qabar {
    position: sticky;
    top: 136px;
    z-index: 180;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 22px;
    background: rgba(255,255,255,0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--gray-200);
    overflow-x: auto;
    scrollbar-width: none;
}
.db-qabar::-webkit-scrollbar { display: none; }
.db-qabar__label {
    font-size: 10px;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .6px;
    white-space: nowrap;
    margin-right: 4px;
    flex-shrink: 0;
}
.qa-sep { width: 1px; height: 22px; background: var(--gray-300); flex-shrink: 0; margin: 0 4px; }

.qa {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 13px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    text-decoration: none;
    border: 1px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-700);
    transition: var(--transition);
    flex-shrink: 0;
}
.qa i { font-size: 11px; }
.qa:hover { 
    border-color: var(--gray-300); 
    background: var(--gray-50); 
    color: var(--gray-800); 
    transform: translateY(-1px); 
    box-shadow: var(--shadow);
}
.qa--yellow { 
    border-color: rgba(245, 158, 11, 0.3); 
    color: #b45309;  
    background: var(--warning-light); 
}
.qa--yellow:hover { 
    background: rgba(245, 158, 11, 0.15); 
    border-color: var(--warning); 
    color: #b45309; 
}
.qa--red { 
    border-color: rgba(239, 68, 68, 0.3); 
    color: var(--danger);    
    background: var(--danger-light); 
}
.qa--red:hover { 
    background: rgba(239, 68, 68, 0.15); 
    border-color: var(--danger);    
    color: var(--danger); 
}
.qa--blue { 
    border-color: rgba(37, 99, 235, 0.3);  
    color: var(--primary);  
    background: var(--primary-soft); 
}
.qa--blue:hover { 
    background: rgba(37, 99, 235, 0.15);  
    border-color: var(--primary);  
    color: var(--primary); 
}
.qa--green { 
    border-color: rgba(16, 185, 129, 0.3);  
    color: var(--success);   
    background: var(--success-light); 
}
.qa--green:hover { 
    background: rgba(16, 185, 129, 0.15);  
    border-color: var(--success);   
    color: var(--success); 
}

/* ════════════════════════════════════════
   ④ BODY
════════════════════════════════════════ */
.db-body {
    padding: 16px 22px 60px;
    max-width: 1700px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Grille principale */
.grid-main {
    display: grid;
    grid-template-columns: 2fr 1.4fr 1.4fr;
    gap: 14px;
    align-items: start;
}

/* Grille secondaire */
.grid-sec {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    align-items: start;
}

/* ════════════════════════════════════════
   CARDS
════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
    animation: fadeUp .28s ease both;
    box-shadow: var(--shadow);
}
.card:hover { 
    border-color: var(--gray-300);
    box-shadow: var(--shadow-hover);
}

@keyframes fadeUp {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}

.card__head {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 15px;
    border-bottom: 1px solid var(--gray-200);
    flex-shrink: 0;
    background: var(--gray-50);
}
.card__icon {
    width: 26px; height: 26px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    flex-shrink: 0;
}
.card__title { 
    font-size: 12px; 
    font-weight: 700; 
    color: var(--gray-800);
}
.card__badge { margin-left: auto; flex-shrink: 0; }

.card__body {
    padding: 14px 15px;
    overflow-y: auto;
    flex: 1;
    max-height: 310px;
}
.card__body--nogrow { max-height: none; }
.card__body::-webkit-scrollbar { width: 3px; }
.card__body::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 99px; }

.card__foot {
    padding: 9px 15px;
    border-top: 1px solid var(--gray-200);
    display: flex;
    justify-content: center;
    flex-shrink: 0;
    background: var(--gray-50);
}

/* ════════════════════════════════════════
   BADGES
════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.badge--green  { background: var(--success-light);  color: #047857; }
.badge--blue   { background: var(--primary-soft); color: var(--primary); }
.badge--yellow { background: var(--warning-light); color: #b45309; }
.badge--red    { background: var(--danger-light);    color: #b91c1c; }
.badge--cyan   { background: var(--info-light);   color: #1e40af; }
.badge--muted  { background: var(--gray-100);   color: var(--gray-600); }

/* ════════════════════════════════════════
   MINI BUTTONS
════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid;
    transition: var(--transition);
    white-space: nowrap;
    cursor: pointer;
}
.btn--green  { 
    color: #047857;  
    border-color: rgba(16, 185, 129, 0.3);  
    background: var(--success-light); 
}
.btn--green:hover  { 
    background: rgba(16, 185, 129, 0.15); 
    border-color: var(--success);  
    color: #047857; 
}
.btn--blue   { 
    color: var(--primary); 
    border-color: rgba(37, 99, 235, 0.3);  
    background: var(--primary-soft); 
}
.btn--blue:hover   { 
    background: rgba(37, 99, 235, 0.15); 
    border-color: var(--primary); 
    color: var(--primary); 
}
.btn--yellow { 
    color: #b45309; 
    border-color: rgba(245, 158, 11, 0.3);  
    background: var(--warning-light); 
}
.btn--yellow:hover { 
    background: rgba(245, 158, 11, 0.15); 
    border-color: var(--warning); 
    color: #b45309; 
}
.btn--icon { padding: 4px 8px; }

/* ════════════════════════════════════════
   TABLE
════════════════════════════════════════ */
.tbl { width: 100%; border-collapse: collapse; }
.tbl th {
    padding: 5px 8px;
    font-size: 10px;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: left;
    border-bottom: 1px solid var(--gray-200);
    white-space: nowrap;
}
.tbl td {
    padding: 9px 8px;
    font-size: 12.5px;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
    color: var(--gray-700);
}
.tbl tr:last-child td { border-bottom: none; }
.tbl tr:hover td { background: var(--gray-50); }

/* ════════════════════════════════════════
   RESERVATION ROWS
════════════════════════════════════════ */
.resa-group { margin-bottom: 12px; }
.resa-group:last-child { margin-bottom: 0; }
.resa-date {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 6px;
}
.resa-date__lbl {
    font-size: 10px; font-weight: 700;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.resa-row {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    padding: 8px 12px;
    border: 1px solid var(--gray-200);
    border-radius: 6px;
    margin-bottom: 6px;
    transition: var(--transition);
}
.resa-row:hover {
    border-color: var(--primary);
    background: var(--primary-soft);
}
.resa-name { font-size: 12.5px; font-weight: 600; color: var(--gray-800); }
.resa-meta {
    font-size: 11px; color: var(--gray-500);
    margin-top: 2px;
    display: flex; flex-wrap: wrap; gap: 5px;
}
.resa-meta span { display: flex; align-items: center; gap: 3px; }
.resa-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

/* ════════════════════════════════════════
   OCCUPATION
════════════════════════════════════════ */
.occ-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 10px;
}
.occ-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 9px;
    padding: 13px;
    transition: var(--transition);
}
.occ-item:hover { 
    border-color: var(--primary);
    box-shadow: var(--shadow);
}
.occ-name { 
    font-size: 12px; 
    font-weight: 700; 
    margin-bottom: 8px;
    color: var(--gray-800);
}
.occ-bar-wrap { 
    height: 4px; 
    border-radius: 99px; 
    background: var(--gray-200); 
    overflow: hidden; 
    margin-bottom: 9px; 
}
.occ-bar-fill { height: 100%; border-radius: 99px; }
.occ-nums { display: flex; justify-content: space-between; align-items: flex-end; }
.occ-num { text-align: center; }
.occ-num span { 
    display: block; 
    font-size: 17px; 
    font-weight: 700; 
    line-height: 1; 
    color: var(--gray-800);
}
.occ-num small { 
    font-size: 10px; 
    color: var(--gray-500); 
    text-transform: uppercase; 
}

/* ════════════════════════════════════════
   EMPTY STATE
════════════════════════════════════════ */
.empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 8px; padding: 36px 20px;
    color: var(--gray-500); text-align: center;
}
.empty i { 
    font-size: 28px; 
    color: var(--gray-300);
}
.empty p { font-size: 12px; line-height: 1.6; }

/* ════════════════════════════════════════
   TOAST
════════════════════════════════════════ */
.toast-box {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    background: var(--white);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 8px;
    padding: 9px 15px;
    font-size: 12px; font-weight: 600;
    display: flex; align-items: center; gap: 7px;
    box-shadow: var(--shadow-hover);
    animation: fadeUp .25s ease;
}

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */

@media (max-width: 1100px) {
    .grid-main {
        grid-template-columns: 1fr 1fr;
    }
    .grid-main .card:first-child { grid-column: 1 / -1; }
}

@media (max-width: 680px) {
    .db-topbar__sub { display: none; }
    .db-body { padding: 12px 12px 60px; gap: 12px; }

    .db-kpibar {
        grid-template-columns: repeat(2, 1fr);
        height: calc(84px * 2);
    }
    .kpi { border-right: none; border-bottom: 1px solid var(--gray-200); }
    .kpi:nth-child(odd)  { border-right: 1px solid var(--gray-200); }
    .kpi:nth-child(3),
    .kpi:nth-child(4)    { border-bottom: none; }
    .kpi__value { font-size: 19px; }
    .kpi__icon  { width: 30px; height: 30px; font-size: 12px; }

    .db-qabar { padding: 0 12px; gap: 5px; }
    .db-qabar__label { display: none; }
    .qa-sep { display: none; }

    .grid-main,
    .grid-sec { grid-template-columns: 1fr; }
    .grid-main .card:first-child { grid-column: auto; }

    .card__body { max-height: 260px; }
}
</style>
@endpush

@section('content')
{{-- ══════════════════════════════════════
     ① TOPBAR
══════════════════════════════════════ --}}
<div class="db-topbar">
    <div class="pulse"></div>
    <span class="db-topbar__title">
        Dashboard
        <span class="db-topbar__sub"> · disponibilité</span>
    </span>
    <span class="db-topbar__clock" id="live-clock">{{ now()->format('d/m/Y H:i:s') }}</span>
</div>

{{-- ══════════════════════════════════════
     ② KPI STRIP
══════════════════════════════════════ --}}
<div class="db-kpibar">
    <div class="kpi">
        <div class="kpi__icon" style="background:var(--primary-soft);color:var(--primary)">
            <i class="fas fa-bed"></i>
        </div>
        <div>
            <div class="kpi__label">Total</div>
            <div class="kpi__value">{{ $stats['total_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:100%;background:var(--primary)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        @php $pA = $stats['total_rooms'] > 0 ? ($stats['available_rooms'] / $stats['total_rooms']) * 100 : 0; @endphp
        <div class="kpi__icon" style="background:var(--success-light);color:var(--success)">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="kpi__label">Disponibles</div>
            <div class="kpi__value" style="color:var(--success)">{{ $stats['available_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $pA }}%;background:var(--success)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        @php $pO = $stats['total_rooms'] > 0 ? ($stats['occupied_rooms'] / $stats['total_rooms']) * 100 : 0; @endphp
        <div class="kpi__icon" style="background:var(--warning-light);color:var(--warning)">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="kpi__label">Occupées</div>
            <div class="kpi__value" style="color:var(--warning)">{{ $stats['occupied_rooms'] }}</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $pO }}%;background:var(--warning)"></div>
            </div>
        </div>
    </div>

    <div class="kpi">
        <div class="kpi__icon" style="background:var(--info-light);color:var(--info)">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <div class="kpi__label">Taux d'occupation</div>
            <div class="kpi__value" style="color:var(--info)">{{ number_format($stats['occupancy_rate'], 1) }}%</div>
            <div class="kpi__bar">
                <div class="kpi__bar-fill" style="width:{{ $stats['occupancy_rate'] }}%;background:var(--info)"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     ③ QUICK ACTIONS BAR
══════════════════════════════════════ --}}
<div class="db-qabar">
    <span class="db-qabar__label">Actions</span>

    <a href="{{ route('transaction.reservation.createIdentity') }}" class="qa qa--yellow">
        <i class="fas fa-plus-circle"></i> Réservation
    </a>
    <a href="{{ route('checkin.index') }}" class="qa qa--red">
        <i class="fas fa-door-open"></i> Check-in / Check-out
    </a>

    <div class="qa-sep"></div>

    <a href="{{ route('availability.calendar') }}" class="qa qa--blue">
        <i class="fas fa-calendar-alt"></i> Calendrier
    </a>
    <a href="{{ route('availability.search') }}" class="qa qa--green">
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

{{-- ══════════════════════════════════════
     ④ BODY
══════════════════════════════════════ --}}
<div class="db-body">
    {{-- GRILLE PRINCIPALE --}}
    <div class="grid-main">
        {{-- Chambres disponibles --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--success-light);color:var(--success)">
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
                            <th>N°</th>
                            <th>Type</th>
                            <th>Prix / nuit</th>
                            <th>Cap.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableNow as $room)
                        <tr>
                            <td><span class="badge badge--green">{{ $room->number }}</span></td>
                            <td style="color:var(--gray-600)">{{ $room->type->name ?? 'Standard' }}</td>
                            <td>
                                <span style="color:var(--success);font-weight:700;font-family:'DM Mono',monospace;font-size:12px">
                                    {{ number_format($room->price, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td style="color:var(--gray-500)">
                                <i class="fas fa-user" style="font-size:10px;margin-right:2px"></i>{{ $room->capacity }}
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

        {{-- Arrivées --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--primary-soft);color:var(--primary)">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <span class="card__title">Arrivées — 3 jours</span>
                <span class="card__badge">
                    <span class="badge badge--blue">
                        {{ now()->format('d/m') }} → {{ now()->addDays(3)->format('d/m') }}
                    </span>
                </span>
            </div>

            <div class="card__body">
                @if(count($upcomingArrivals) > 0)
                    @foreach($upcomingArrivals as $date => $items)
                    <div class="resa-group">
                        <div class="resa-date">
                            <span class="resa-date__lbl">{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}</span>
                            <span class="badge badge--blue">{{ $items->count() }}</span>
                        </div>
                        @foreach($items as $arrival)
                        <div class="resa-row">
                            <div>
                                <div class="resa-name">{{ $arrival->customer->name ?? 'Client inconnu' }}</div>
                                <div class="resa-meta">
                                    <span><i class="fas fa-bed"></i> Ch.{{ $arrival->room->number ?? 'N/A' }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $arrival->check_in->format('H:i') }}</span>
                                    <span><i class="fas fa-user-friends"></i> {{ $arrival->person_count ?? 1 }}p.</span>
                                </div>
                            </div>
                            <div class="resa-right">
                                <span class="badge badge--cyan">{{ $arrival->room->type->name ?? 'N/A' }}</span>
                                <a href="{{ route('transaction.show', $arrival->id) }}" class="btn btn--blue btn--icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                @else
                <div class="empty">
                    <i class="fas fa-calendar-times"></i>
                    <p>Aucune arrivée prévue<br>dans les 3 prochains jours</p>
                </div>
                @endif
            </div>

            @if(count($upcomingArrivals) > 0)
            <div class="card__foot">
                <a href="{{ route('availability.calendar') }}" class="btn btn--blue">
                    <i class="fas fa-calendar-alt"></i> Voir le calendrier
                </a>
            </div>
            @endif
        </div>

        {{-- Départs --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--success-light);color:var(--success)">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="card__title">Départs — 3 jours</span>
                <span class="card__badge">
                    <span class="badge badge--green">
                        {{ now()->format('d/m') }} → {{ now()->addDays(3)->format('d/m') }}
                    </span>
                </span>
            </div>

            <div class="card__body">
                @if(count($upcomingDepartures) > 0)
                    @foreach($upcomingDepartures as $date => $items)
                    <div class="resa-group">
                        <div class="resa-date">
                            <span class="resa-date__lbl">{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}</span>
                            <span class="badge badge--green">{{ $items->count() }}</span>
                        </div>
                        @foreach($items as $departure)
                        <div class="resa-row">
                            <div>
                                <div class="resa-name">{{ $departure->customer->name ?? 'Client inconnu' }}</div>
                                <div class="resa-meta">
                                    <span><i class="fas fa-bed"></i> Ch.{{ $departure->room->number ?? 'N/A' }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $departure->check_out->format('H:i') }}</span>
                                    <span><i class="fas fa-user-friends"></i> {{ $departure->person_count ?? 1 }}p.</span>
                                </div>
                            </div>
                            <div class="resa-right">
                                <span class="badge badge--cyan">{{ $departure->room->type->name ?? 'N/A' }}</span>
                                <a href="{{ route('transaction.show', $departure->id) }}" class="btn btn--green btn--icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                @else
                <div class="empty">
                    <i class="fas fa-calendar-times"></i>
                    <p>Aucun départ prévu<br>dans les 3 prochains jours</p>
                </div>
                @endif
            </div>

            @if(count($upcomingDepartures) > 0)
            <div class="card__foot">
                <a href="{{ route('checkin.index') }}" class="btn btn--green">
                    <i class="fas fa-door-open"></i> Gérer les check-outs
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- GRILLE SECONDAIRE --}}
    <div class="grid-sec">
        {{-- Maintenance / Nettoyage --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--warning-light);color:var(--warning)">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="card__title">Maintenance / Nettoyage</span>
                <span class="card__badge">
                    <span class="badge badge--yellow">{{ $unavailableRooms->count() }}</span>
                </span>
            </div>

            <div class="card__body">
                @if($unavailableRooms->count() > 0)
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Depuis</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unavailableRooms as $room)
                        <tr>
                            <td>
                                <span class="badge {{ $room->room_status_id == 2 ? 'badge--red' : 'badge--cyan' }}">
                                    {{ $room->number }}
                                </span>
                            </td>
                            <td style="color:var(--gray-600)">{{ $room->type->name ?? 'Standard' }}</td>
                            <td>
                                <span class="badge {{ $room->room_status_id == 2 ? 'badge--red' : 'badge--cyan' }}">
                                    <i class="fas fa-{{ $room->room_status_id == 2 ? 'tools' : 'broom' }}"></i>
                                    {{ $room->roomStatus->name ?? 'Indisponible' }}
                                </span>
                            </td>
                            <td style="color:var(--gray-500);font-size:11px">
                                {{ $room->updated_at ? $room->updated_at->diffForHumans() : 'N/A' }}
                            </td>
                            <td>
                                @if(isset($room->room_status_id) && $room->room_status_id == 3)
                                    <a href="{{ route('housekeeping.finish-cleaning', $room->id) }}" class="btn btn--green">
                                        <i class="fas fa-check"></i> OK
                                    </a>
                                @else
                                    <span style="font-size:11px;color:var(--gray-500)">En cours…</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty">
                    <i class="fas fa-check-circle" style="color:var(--success);opacity:.55"></i>
                    <p>Aucune chambre en maintenance<br>Tout est opérationnel</p>
                </div>
                @endif
            </div>

            @if($unavailableRooms->count() > 0)
            <div class="card__foot">
                <a href="{{ route('housekeeping.index') }}" class="btn btn--yellow">
                    <i class="fas fa-broom"></i> Gestion nettoyage
                </a>
            </div>
            @endif
        </div>

        {{-- Occupation par type --}}
        <div class="card">
            <div class="card__head">
                <div class="card__icon" style="background:var(--info-light);color:var(--info)">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <span class="card__title">Occupation par type de chambre</span>
                <span class="card__badge">
                    @php $avgOcc = collect($occupancyByType)->avg('percentage') ?? 0; @endphp
                    <span class="badge badge--cyan">Moy. {{ number_format($avgOcc, 1) }}%</span>
                </span>
            </div>

            <div class="card__body card__body--nogrow">
                @if(count($occupancyByType) > 0)
                <div class="occ-grid">
                    @foreach($occupancyByType as $type)
                    @if(is_array($type) && isset($type['type']))
                    @php
                        $pct = $type['percentage'] ?? 0;
                        $col = $pct > 80 ? 'var(--success)' : ($pct > 50 ? 'var(--warning)' : 'var(--info)');
                        $bdg = $pct > 80 ? 'badge--green' : ($pct > 50 ? 'badge--yellow' : 'badge--cyan');
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* Horloge temps réel */
    const clockEl = document.getElementById('live-clock');
    function tick () {
        const n = new Date();
        const p = v => String(v).padStart(2, '0');
        clockEl.textContent =
            `${p(n.getDate())}/${p(n.getMonth()+1)}/${n.getFullYear()} ` +
            `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
    }
    setInterval(tick, 1000);

    /* Refresh silencieux toutes les 30 s */
    setInterval(function () {
        fetch('{{ route("availability.dashboard") }}')
            .then(r => { if (r.ok) toast(); })
            .catch(() => {});
    }, 30000);

    function toast () {
        const el = document.createElement('div');
        el.className = 'toast-box';
        el.innerHTML = '<i class="fas fa-sync-alt"></i> Données actualisées';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2500);
    }
});
</script>
@endpush