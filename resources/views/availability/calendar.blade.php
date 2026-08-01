@extends('template.master')

@section('title', __('messages.calendar_title'))

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── Palette : 3 couleurs uniquement ── */
    /* VERT */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    /* BLANC / SURFACE */
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    /* GRIS */
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
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.cal-page {
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(.96); }
    to   { opacity: 1; transform: scale(1); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════════ */
.cal-topbar {
    position: sticky;
    top: 0;
    z-index: 300;
    background: var(--white, #fff);
    backdrop-filter: blur(20px);
    border-bottom: 1.5px solid var(--s100);
    padding: 16px 28px;
}
.cal-topbar__inner {
    max-width: 1800px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.cal-topbar__title h1 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--s900);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cal-topbar__title p {
    font-size: .8rem;
    color: var(--s400);
}
.time-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--g50);
    border: 1.5px solid var(--g200);
    border-radius: 100px;
    padding: 4px 12px;
    font-size: .75rem;
    font-weight: 500;
    color: var(--g700);
}
.cal-topbar__actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ── Navigation ─────────────────────────────── */
.cal-nav {
    display: flex;
    align-items: center;
    gap: 2px;
    background: var(--surface);
    border: 1.5px solid var(--s200);
    border-radius: var(--r);
    padding: 2px;
}
.cal-nav__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: .8rem;
    font-weight: 600;
    background: transparent;
    border: none;
    color: var(--s600);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.cal-nav__btn:hover {
    background: var(--s50);
    color: var(--s900);
}
.cal-nav__btn.active {
    background: var(--g600);
    color: white;
    pointer-events: none;
}

/* ── Boutons ─────────────────────────────────── */
.btn-db {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
}
.btn-db-primary {
    background: var(--g600);
    color: white;
    box-shadow: 0 2px 10px rgb(from var(--g500) r g b / .25);
}
.btn-db-primary:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .3);
    color: white;
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white);
    color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-1px);
    text-decoration: none;
}

/* ══════════════════════════════════════════════
   KPIS (rendus cliquables)
══════════════════════════════════════════════ */
.cal-kpis {
    max-width: 1800px;
    margin: 24px auto 16px;
    padding: 0 28px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
@media (max-width: 1100px) { .cal-kpis { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .cal-kpis { grid-template-columns: 1fr; } }

.kpi-card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition);
    cursor: pointer;
    box-shadow: var(--shadow-xs);
}
.kpi-card:hover { 
    border-color: var(--g300);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}
.kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.kpi-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--s400);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
}
.kpi-value {
    font-size: 1.8rem;
    font-weight: 700;
    font-family: var(--mono);
    line-height: 1;
    letter-spacing: -.5px;
    color: var(--s900);
}

/* ══════════════════════════════════════════════
   FILTERS
══════════════════════════════════════════════ */
.cal-filters {
    max-width: 1800px;
    margin: 0 auto 20px;
    padding: 0 28px;
}
.filter-card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    padding: 20px 24px;
    box-shadow: var(--shadow-sm);
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    align-items: end;
}
@media (max-width: 1200px) {
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .filter-actions { grid-column: 1 / -1; }
}
@media (max-width: 768px) {
    .filter-grid { grid-template-columns: 1fr; }
}
.form-group label {
    display: block;
    font-size: .7rem;
    font-weight: 600;
    color: var(--s400);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 6px;
}
.form-control,
.form-select {
    width: 100%;
    padding: 10px 14px;
    border-radius: var(--r);
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s800);
    font-size: .85rem;
    font-family: var(--font);
    transition: var(--transition);
}
.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--g400);
    box-shadow: 0 0 0 3px rgb(from var(--g500) r g b / .1);
}
.filter-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* ══════════════════════════════════════════════
   LEGEND (rendue cliquable)
══════════════════════════════════════════════ */
.cal-legend {
    max-width: 1800px;
    margin: 0 auto 20px;
    padding: 0 28px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: var(--r);
    transition: var(--transition);
}
.legend-item:hover {
    background: var(--g50);
}
.legend-sq {
    width: 18px;
    height: 18px;
    border-radius: 4px;
}
.legend-sq.available { background: var(--g50); border: 1.5px solid var(--g300); }
.legend-sq.reserved { background: #fee2e2; border: 1.5px solid #fecaca; }
.legend-sq.unavailable { background: var(--s100); border: 1.5px solid var(--s200); }
.legend-sq.today { background: var(--g100); border: 2px solid var(--g500); }
.legend-item span {
    font-size: .8rem;
    color: var(--s600);
}
.legend-tip {
    margin-left: auto;
    font-size: .8rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 6px;
}
.legend-tip i {
    color: var(--g500);
}

/* ══════════════════════════════════════════════
   CALENDAR TABLE
══════════════════════════════════════════════ */
.cal-wrap {
    max-width: 1800px;
    margin: 0 auto;
    padding: 0 28px 40px;
}
.cal-container {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.cal-scroll {
    overflow-x: auto;
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--s300) var(--s100);
}
.cal-scroll::-webkit-scrollbar {
    height: 8px;
}
.cal-scroll::-webkit-scrollbar-track {
    background: var(--s100);
}
.cal-scroll::-webkit-scrollbar-thumb {
    background: var(--s300);
    border-radius: 99px;
}
.cal-scroll::-webkit-scrollbar-thumb:hover {
    background: var(--s400);
}

.cal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: max-content;
}
.cal-table thead {
    position: sticky;
    top: 0;
    z-index: 200;
}
.cal-table th {
    background: var(--surface);
    border-bottom: 1.5px solid var(--s100);
    padding: 12px 14px;
    text-align: center;
    font-size: .7rem;
    font-weight: 600;
    color: var(--s500);
    text-transform: uppercase;
    letter-spacing: .4px;
    white-space: nowrap;
}
.cal-table th.room-col {
    position: sticky;
    left: 0;
    z-index: 210;
    text-align: left;
    min-width: 280px;
    background: var(--surface);
    box-shadow: 2px 0 6px rgba(0,0,0,.02);
}
.cal-table th.date-col {
    min-width: 70px;
    cursor: pointer;
    transition: background .15s;
}
.cal-table th.date-col:hover {
    background: var(--g50);
}
.date-day { 
    font-size: 1rem; 
    font-weight: 700; 
    color: var(--s700);
}
.date-name { 
    font-size: .65rem; 
    color: var(--s400); 
    margin-top: 2px; 
}
.th-today {
    background: var(--g50) !important;
    border-left: 2px solid var(--g500) !important;
    border-right: 2px solid var(--g500) !important;
    color: var(--g700) !important;
}
.th-weekend {
    background: rgba(0,0,0,.02) !important;
}

.cal-table tbody tr {
    border-bottom: 1.5px solid var(--s100);
}
.cal-table td {
    padding: 0;
    border-right: 1.5px solid var(--s100);
}
.cal-table td:last-child {
    border-right: none;
}

/* Room info cell */
.room-cell {
    position: sticky;
    left: 0;
    z-index: 100;
    background: var(--white);
    min-width: 280px;
    padding: 16px 18px;
    box-shadow: 2px 0 6px rgba(0,0,0,.02);
    border-right: 1.5px solid var(--s100) !important;
}
.room-cell__inner {
    display: flex;
    align-items: center;
    gap: 14px;
}
.room-badge {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: var(--g600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    font-weight: 700;
    font-family: var(--mono);
    flex-shrink: 0;
}
.room-info {
    flex: 1;
}
.room-type {
    font-size: .85rem;
    font-weight: 600;
    margin-bottom: 3px;
    color: var(--s800);
}
.room-meta {
    font-size: .7rem;
    color: var(--s400);
    display: flex;
    gap: 8px;
    margin-bottom: 2px;
}
.room-price {
    font-family: var(--mono);
    font-size: .7rem;
    color: var(--s500);
}
.room-status-badge {
    font-size: .65rem;
    padding: 2px 8px;
    border-radius: 100px;
    background: var(--s100);
    color: var(--s600);
    margin-top: 4px;
    display: inline-block;
}
.room-actions {
    flex-shrink: 0;
}
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-icon:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g600);
}

/* Availability cells */
.avail-cell {
    min-width: 70px;
    height: 65px;
    padding: 8px;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: var(--transition);
    font-size: 1rem;
}
.avail-cell i {
    font-size: 1.2rem;
}
.avail-cell:hover {
    transform: scale(1.05);
    z-index: 10;
    box-shadow: 0 0 0 2px var(--g500) inset;
}

/* States */
.avail-cell.available {
    background: var(--g50);
    border-left: 1px solid var(--g200);
}
.avail-cell.available i { color: var(--g600); }
.avail-cell.available:hover {
    background: var(--g100);
}

.avail-cell.reserved {
    background: #fee2e2;
    border-left: 1px solid #fecaca;
}
.avail-cell.reserved i { color: #b91c1c; }
.avail-cell.reserved:hover {
    background: #fecaca;
}

.avail-cell.unavailable {
    background: var(--s100);
    border-left: 1px solid var(--s200);
    cursor: not-allowed;
}
.avail-cell.unavailable i { color: var(--s400); opacity: .5; }
.avail-cell.unavailable:hover {
    transform: none;
    box-shadow: none;
}

.avail-cell.today {
    background: var(--g50) !important;
    border-left: 2px solid var(--g500) !important;
    border-right: 2px solid var(--g500) !important;
    box-shadow: 0 0 0 1px var(--g300) inset;
}

.avail-cell.weekend {
    background: rgba(0,0,0,.02);
}

/* Conflict badge */
.conflict-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #b91c1c;
    color: white;
    font-size: .6rem;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 2px;
}

/* Empty state */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    color: var(--s400);
}
.empty-state i {
    font-size: 3rem;
    opacity: .3;
    margin-bottom: 16px;
    color: var(--s300);
}
.empty-state h5 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--s600);
}
.empty-state p {
    font-size: .85rem;
    color: var(--s400);
}

/* ══════════════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════════════ */
.cal-actions {
    max-width: 1800px;
    margin: 20px auto 0;
    padding: 0 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.btn-group {
    display: flex;
    gap: 8px;
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    box-shadow: var(--shadow-lg);
}
.modal-header {
    border-bottom: 1.5px solid var(--s100);
    padding: 18px 24px;
}
.modal-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--s800);
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1.5px solid var(--s100);
    padding: 16px 24px;
}
.card {
    background: var(--surface);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    padding: 16px;
    margin-bottom: 16px;
}
.card h6 {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--s400);
    margin-bottom: 12px;
}
.alert {
    border-radius: var(--r);
    border: 1.5px solid;
    padding: 12px 16px;
    font-size: .85rem;
}
.alert-info {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.alert-warning {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #c2410c;
}
.alert-danger {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}
.alert-success {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.badge {
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.badge.bg-success {
    background: var(--g100) !important;
    color: var(--g700) !important;
}
.badge.bg-warning {
    background: #fff7ed !important;
    color: #c2410c !important;
}
.badge.bg-danger {
    background: #fee2e2 !important;
    color: #b91c1c !important;
}
.table {
    width: 100%;
    border-collapse: collapse;
    color: var(--s700);
}
.table thead {
    background: var(--surface);
}
.table th {
    border-bottom: 1.5px solid var(--s100);
    padding: 10px 12px;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--s400);
}
.table td {
    border-bottom: 1px solid var(--s100);
    padding: 10px 12px;
    font-size: .8rem;
}
.table-hover tbody tr:hover {
    background: var(--g50);
}
</style>
@endpush

@section('content')

<div class="cal-page">

    {{-- ══════════════════════════════════════════════
         TOPBAR
    ═══════════════════════════════════════════════ --}}
    <div class="cal-topbar anim-1">
        <div class="cal-topbar__inner">
            <div class="cal-topbar__title">
                <h1>
                    <i class="fas fa-calendar-alt" style="color:var(--g600)"></i>
                    {{ __('messages.calendar_title') }}
                    <span class="time-badge">
                        <i class="fas fa-clock"></i> {{ __('messages.checkin_12h_checkout_12h') }}
                    </span>
                </h1>
                <p>{{ __('messages.visualize_reservations') }}</p>
            </div>
            <div class="cal-topbar__actions">
                <div class="cal-nav">
                    <a href="{{ route('availability.calendar', ['month' => $prevMonth->format('m'), 'year' => $prevMonth->format('Y')]) }}" 
                       class="cal-nav__btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <button class="cal-nav__btn active">
                        {{ $startDate->translatedFormat('F Y') }}
                    </button>
                    <a href="{{ route('availability.calendar', ['month' => $nextMonth->format('m'), 'year' => $nextMonth->format('Y')]) }}" 
                       class="cal-nav__btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <a href="{{ route('availability.search') }}" class="btn-db btn-db-primary">
                    <i class="fas fa-search"></i>
                    {{ __('messages.search') }}
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         KPIS (rendus cliquables)
    ═══════════════════════════════════════════════ --}}
    <div class="cal-kpis anim-2">
        <div class="kpi-card" onclick="filterByStatus('all')" title="Voir toutes les chambres">
            <div class="kpi-icon" style="background:var(--g50);color:var(--g600)">
                <i class="fas fa-bed"></i>
            </div>
            <div>
                <div class="kpi-label">{{ __('messages.total_rooms_label') }}</div>
                <div class="kpi-value">{{ $stats['total_rooms'] }}</div>
            </div>
        </div>
        <div class="kpi-card" onclick="filterByStatus('available')" title="{{ __('messages.available') }}">
            <div class="kpi-icon" style="background:var(--g50);color:var(--g600)">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="kpi-label">{{ __('messages.available_rooms') }}</div>
                <div class="kpi-value" style="color:var(--g600)">{{ $stats['available_today'] }}</div>
            </div>
        </div>
        <div class="kpi-card" onclick="filterByStatus('reserved')" title="{{ __('messages.reserved') }}">
            <div class="kpi-icon" style="background:#fee2e2;color:#b91c1c">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="kpi-label">{{ __('messages.reserved_label') }}</div>
                <div class="kpi-value" style="color:#b91c1c">{{ $stats['occupied_today'] }}</div>
            </div>
        </div>
        <div class="kpi-card" onclick="filterByStatus('unavailable')" title="{{ __('messages.unavailable') }}">
            <div class="kpi-icon" style="background:var(--s100);color:var(--s500)">
                <i class="fas fa-times-circle"></i>
            </div>
            <div>
                <div class="kpi-label">{{ __('messages.unavailable_label') }}</div>
                <div class="kpi-value" style="color:var(--s500)">{{ $stats['unavailable_today'] }}</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════════════ --}}
    <div class="cal-filters anim-3">
        <div class="filter-card">
            <form method="GET" id="calendarFilterForm">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>{{ __('messages.room_type') }}</label>
                        <select name="room_type" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_types') }}</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.room_number') }}</label>
                        <input type="text" name="room_number" class="form-control" 
                               value="{{ request('room_number') }}"
                               placeholder="Ex: 101, 102..."
                               onkeyup="filterByRoomNumber(this.value)">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.month') }}</label>
                        <input type="month" name="month_year" class="form-control" 
                               value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                               onchange="this.form.submit()">
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn-db btn-db-primary">
                            <i class="fas fa-filter"></i>
                            {{ __('messages.filter') }}
                        </button>
                        <a href="{{ route('availability.calendar') }}" class="btn-db btn-db-ghost">
                            <i class="fas fa-times"></i>
                            {{ __('messages.reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         LEGEND (rendue cliquable)
    ═══════════════════════════════════════════════ --}}
    <div class="cal-legend anim-4">
        <div class="legend-item" onclick="filterByStatus('available')">
            <div class="legend-sq available"></div>
            <span>{{ __('messages.available') }}</span>
        </div>
        <div class="legend-item" onclick="filterByStatus('reserved')">
            <div class="legend-sq reserved"></div>
            <span>{{ __('messages.reserved') }}</span>
        </div>
        <div class="legend-item" onclick="filterByStatus('unavailable')">
            <div class="legend-sq unavailable"></div>
            <span>{{ __('messages.unavailable') }}</span>
        </div>
        <div class="legend-item" onclick="window.scrollToToday()">
            <div class="legend-sq today"></div>
            <span>{{ __('messages.today') }}</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-danger" style="background:#b91c1c;color:white;">2+</div>
            <span>{{ __('messages.conflict') }}</span>
        </div>
        <div class="legend-tip">
            <i class="fas fa-info-circle"></i>
            {{ __('messages.click_cell_details') }}
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         CALENDAR
    ═══════════════════════════════════════════════ --}}
    <div class="cal-wrap anim-5">
        <div class="cal-container">
            <div class="cal-scroll">
                <table class="cal-table">
                    <thead>
                        <tr>
                            <th class="room-col">
                                <div style="display:flex;justify-content:space-between;align-items:center">
                                    <span>{{ __('messages.room_date') }}</span>
                                    <button type="button" class="btn-icon" onclick="window.scrollToToday()" title="{{ __('messages.go_to_today') }}" id="todayButton">
                                        <i class="fas fa-calendar-day"></i>
                                    </button>
                                </div>
                            </th>
                            @foreach($dates as $dateString => $dateInfo)
                                <th class="date-col {{ $dateInfo['is_today'] ? 'th-today' : '' }} {{ $dateInfo['is_weekend'] ? 'th-weekend' : '' }}"
                                    data-date="{{ $dateString }}"
                                    onclick="window.scrollToDate('{{ $dateString }}')"
                                    title="{{ __('messages.click_to_center') }}">
                                    <div class="date-day">{{ $dateInfo['date']->format('d') }}</div>
                                    <div class="date-name">{{ $dateInfo['day_name'] }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if($rooms->isEmpty())
                            <tr>
                                <td colspan="{{ count($dates) + 1 }}">
                                    <div class="empty-state">
                                        <i class="fas fa-bed"></i>
                                        <h5>{{ __('messages.no_room_found') }}</h5>
                                        <p>{{ __('messages.no_room_matches_filters') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($calendar as $roomData)
                                <tr class="room-row" data-room-number="{{ $roomData['room']->number }}" data-room-status="{{ $roomData['room']->room_status_id }}">
                                    <td class="room-cell">
                                        <div class="room-cell__inner">
                                            <div class="room-badge">{{ $roomData['room']->number }}</div>
                                            <div class="room-info">
                                                <div class="room-type">{{ $roomData['room']->type->name ?? __('messages.type_unknown') }}</div>
                                                <div class="room-meta">
                                                    <span><i class="fas fa-users"></i> {{ $roomData['room']->capacity }} pers.</span>
                                                </div>
                                                <div class="room-price">{{ number_format($roomData['room']->price, 0, ',', ' ') }} FCFA/nuit</div>
                                                @if($roomData['room']->room_status_id != 1)
                                                    <span class="room-status-badge">
                                                        {{ $roomData['room']->roomStatus->name ?? __('messages.unavailable') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="room-actions">
                                                <a href="{{ route('availability.room.detail', $roomData['room']->id) }}" 
                                                   class="btn-icon"
                                                   title="{{ __('messages.details') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($dates as $dateString => $dateInfo)
                                        @php
                                            $availability = $roomData['availability'][$dateString] ?? [
                                                'occupied' => false,
                                                'available' => true,
                                                'css_class' => 'available',
                                                'reservation_count' => 0,
                                                'can_reserve' => false,
                                                'has_reservations' => false
                                            ];
                                            $canReserve = $availability['can_reserve'] ?? false;
                                            $cssClass = $availability['css_class'] ?? 'available';
                                            // Remplacer 'occupied' par 'reserved' pour la classe CSS
                                            if ($cssClass === 'occupied') {
                                                $cssClass = 'reserved';
                                            }
                                            $reservationCount = $availability['reservation_count'] ?? 0;
                                            $isOccupied = $availability['occupied'] ?? false;
                                        @endphp
                                        <td class="avail-cell {{ $cssClass }} {{ $dateInfo['is_today'] ? 'today' : '' }} {{ $dateInfo['is_weekend'] ? 'weekend' : '' }}"
                                            data-room-id="{{ $roomData['room']->id }}"
                                            data-room-number="{{ $roomData['room']->number }}"
                                            data-room-type="{{ $roomData['room']->type->name ?? '' }}"
                                            data-room-price="{{ $roomData['room']->price }}"
                                            data-date="{{ $dateString }}"
                                            data-formatted-date="{{ $dateInfo['date']->format('d/m/Y') }}"
                                            data-is-occupied="{{ $isOccupied ? 'true' : 'false' }}"
                                            data-reservation-count="{{ $reservationCount }}"
                                            data-can-reserve="{{ $canReserve ? 'true' : 'false' }}"
                                            title="{{ $dateInfo['date']->format('d/m/Y') }} - Chambre {{ $roomData['room']->number }} - {{ $isOccupied ? __('messages.reserved') : __('messages.available') }}">
                                            @if($isOccupied)
                                                <i class="fas fa-calendar-check" style="color:#b91c1c;"></i>
                                                @if($reservationCount > 1)
                                                    <div class="conflict-badge">
                                                        {{ $reservationCount }}
                                                        @if($reservationCount > 2)
                                                            <i class="fas fa-exclamation"></i>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <i class="fas fa-check" style="color:var(--g600);"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
         ACTIONS
    ═══════════════════════════════════════════════ --}}
    <div class="cal-actions anim-6">
        <div class="btn-group">
            <button type="button" class="btn-db btn-db-ghost" onclick="window.selectDateRange()">
                <i class="fas fa-calendar-range"></i>
                {{ __('messages.select_period') }}
            </button>
            <button type="button" class="btn-db btn-db-ghost" onclick="window.checkAllAvailability()">
                <i class="fas fa-search"></i>
                {{ __('messages.check_availability') }}
            </button>
        </div>
        <div class="btn-group">
            <button class="btn-db btn-db-ghost" onclick="window.print()">
                <i class="fas fa-print"></i>
                {{ __('messages.print') }}
            </button>
            <a href="{{ route('availability.export', [
                'type' => 'excel',
                'export_type' => 'calendar',
                'month' => $month,
                'year' => $year
            ]) }}" class="btn-db btn-db-ghost" style="color:var(--g600);border-color:var(--g200);">
                <i class="fas fa-file-excel"></i>
                {{ __('messages.export_excel') }}
            </a>
        </div>
    </div>

</div>

{{-- Modal pour les détails --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalTitle">Détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-db btn-db-ghost" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ============ FONCTIONS GLOBALES ============

/**
 * Scroll vers la date d'aujourd'hui
 */
window.scrollToToday = function() {
    console.log('📅 scrollToToday appelé');
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const dateString = `${year}-${month}-${day}`;
    
    window.scrollToDate(dateString);
    
    // Mettre en évidence la colonne d'aujourd'hui
    document.querySelectorAll('.th-today').forEach(el => {
        el.style.backgroundColor = 'var(--g100)';
        el.style.outline = '2px solid var(--g500)';
        setTimeout(() => {
            el.style.backgroundColor = '';
            el.style.outline = '';
        }, 1500);
    });
};

/**
 * Scroll vers une date spécifique (YYYY-MM-DD)
 */
window.scrollToDate = function(dateString) {
    console.log('📅 scrollToDate appelé avec:', dateString);
    
    // Méthode 1: Chercher une cellule de données
    const targetCell = document.querySelector(`.avail-cell[data-date="${dateString}"]`);
    
    if (targetCell) {
        targetCell.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest', 
            inline: 'center' 
        });
        
        targetCell.style.outline = '2px solid var(--g500)';
        targetCell.style.backgroundColor = 'var(--g50)';
        
        setTimeout(() => {
            targetCell.style.outline = '';
            targetCell.style.backgroundColor = '';
        }, 1500);
        
        return;
    }
    
    // Méthode 2: Chercher l'en-tête de colonne
    const headerCell = document.querySelector(`th.date-col[data-date="${dateString}"]`);
    if (headerCell) {
        headerCell.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest', 
            inline: 'center' 
        });
        
        headerCell.style.backgroundColor = 'var(--g50)';
        
        setTimeout(() => {
            headerCell.style.backgroundColor = '';
        }, 1500);
    }
};

/**
 * Afficher les détails d'une chambre réservée
 */
window.showOccupancyDetails = function(roomId, date) {
    fetch(`/availability/calendar-cell-details?room_id=${roomId}&date=${date}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            let content = `
                <div class="p-2">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <h6>Informations Chambre</h6>
                                <div class="mb-2"><small style="color:var(--s400)">Numéro:</small> <strong class="fs-5">${data.room.number}</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Type:</small> <strong>${data.room.type}</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Capacité:</small> <strong>${data.room.capacity} pers.</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Prix/nuit:</small> <strong>${new Intl.NumberFormat('fr-FR').format(data.room.price)} FCFA</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Date:</small> <strong>${new Date(date).toLocaleDateString('fr-FR')}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Cette chambre est réservée pour cette date.
                            </div>
                        </div>
                    </div>
            `;
            
            if (data.reservations && data.reservations.length > 0) {
                content += `<h6 class="mb-3">Réservations (${data.reservations.length})</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Arrivée</th>
                                <th>Départ</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                data.reservations.forEach(r => {
                    const statusClass = r.status === 'active' ? 'bg-success' : 'bg-warning';
                    const statusText = r.status === 'active' ? 'En séjour' : 'Réservée';
                    
                    content += `<tr>
                        <td>
                            <div class="fw-bold">${r.customer.name || 'Client'}</div>
                            <small style="color:var(--s400)">${r.customer.email || ''}</small>
                        </td>
                        <td>${new Date(r.check_in).toLocaleDateString('fr-FR')}</td>
                        <td>${new Date(r.check_out).toLocaleDateString('fr-FR')}</td>
                        <td><span class="badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <a href="/transaction/${r.id}" class="btn-icon" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>`;
                });
                
                content += `</tbody></table></div>`;
                
                if (data.reservations.length > 1) {
                    content += `<div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>ALERTE:</strong> ${data.reservations.length} réservations en conflit pour cette date !
                    </div>`;
                }
            }
            
            content += `<div class="mt-4 d-grid gap-2">
                <a href="/availability/search?room_type_id=${data.room.type_id}" class="btn-db btn-db-primary">
                    <i class="fas fa-search me-2"></i>Chercher une autre chambre
                </a>
            </div></div>`;
            
            window.showModal('Détails de réservation', content);
        })
        .catch(error => {
            console.error('Erreur:', error);
            window.showModal('Erreur', `<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur: ${error.message}
            </div>`);
        });
};

/**
 * Afficher les détails d'une chambre disponible
 */
window.showAvailabilityDetails = function(roomId, date) {
    fetch(`/availability/check-availability?room_id=${roomId}&check_in=${date}&check_out=${date}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            const formattedDate = new Date(date).toLocaleDateString('fr-FR');
            const totalPrice = new Intl.NumberFormat('fr-FR').format(data.total_price);
            
            let content = `
                <div class="p-2">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <h6>Informations Chambre</h6>
                                <div class="mb-2"><small style="color:var(--s400)">Numéro:</small> <strong class="fs-5">${data.room.number}</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Type:</small> <strong>${data.room.type}</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Capacité:</small> <strong>${data.room.capacity} pers.</strong></div>
                                <div class="mb-2"><small style="color:var(--s400)">Prix/nuit:</small> <strong>${new Intl.NumberFormat('fr-FR').format(data.room.price)} FCFA</strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <h6>Réservation</h6>
                                <div class="mb-2"><small style="color:var(--s400)">Date:</small> <strong>${formattedDate}</strong></div>
                                <div class="mb-3">
                                    <small style="color:var(--s400)">Prix total:</small>
                                    <div class="fs-3 fw-bold" style="color:var(--g600)">${totalPrice} FCFA</div>
                                </div>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Chambre disponible
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${date}&check_out=${date}" 
                           class="btn-db btn-db-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Réserver cette chambre
                        </a>
                        <button type="button" class="btn-db btn-db-ghost" onclick="window.selectDateRangeFromCell('${roomId}', '${date}')">
                            <i class="fas fa-calendar-range me-2"></i>Sélectionner une période
                        </button>
                    </div>
                </div>
            `;
            
            window.showModal('Chambre disponible', content);
        })
        .catch(error => {
            console.error('Erreur:', error);
            window.showModal('Erreur', `<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur: ${error.message}
            </div>`);
        });
};

/**
 * Sélectionner une période
 */
window.selectDateRange = function() {
    window.selectionMode = true;
    window.selectedCells = [];
    
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    
    window.showModal('Sélection de période', `
        <div class="p-2">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Mode sélection activé. Cliquez sur la première date, puis sur la dernière.
            </div>
            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date d'arrivée</label>
                    <input type="date" id="checkInDate" class="form-control" value="${today}" min="${today}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date de départ</label>
                    <input type="date" id="checkOutDate" class="form-control" value="${tomorrow}" min="${tomorrow}">
                </div>
            </div>
            <button class="btn-db btn-db-primary w-100" onclick="window.applyDateSelection()">
                <i class="fas fa-check me-2"></i>Appliquer
            </button>
        </div>
    `);
};

/**
 * Sélectionner une période à partir d'une cellule
 */
window.selectDateRangeFromCell = function(roomId, startDate) {
    window.selectionMode = true;
    window.selectionStart = { roomId, date: startDate };
    
    const formattedDate = new Date(startDate).toLocaleDateString('fr-FR');
    
    window.showModal('Sélection de période', `
        <div class="p-2">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Arrivée: <strong>${formattedDate}</strong><br>
                Cliquez sur la date de départ dans le calendrier.
            </div>
            <button class="btn-db btn-db-ghost w-100" onclick="window.cancelSelection()">
                <i class="fas fa-times me-2"></i>Annuler
            </button>
        </div>
    `);
};

/**
 * Gérer la sélection de période
 */
window.handlePeriodSelection = function(cell) {
    if (!window.selectionStart) {
        // Première sélection (date d'arrivée)
        window.selectionStart = {
            roomId: cell.getAttribute('data-room-id'),
            date: cell.getAttribute('data-date'),
            element: cell
        };
        cell.style.outline = '2px solid var(--g500)';
        
        const formattedDate = new Date(window.selectionStart.date).toLocaleDateString('fr-FR');
        
        window.showModal('Sélection de période', `
            <div class="p-2">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Arrivée: <strong>${formattedDate}</strong><br>
                    Chambre: <strong>${cell.getAttribute('data-room-number')}</strong><br>
                    Cliquez sur la date de départ.
                </div>
            </div>
        `);
    } else {
        // Deuxième sélection (date de départ)
        const roomId = cell.getAttribute('data-room-id');
        const endDate = cell.getAttribute('data-date');
        
        if (roomId !== window.selectionStart.roomId) {
            alert('❌ Sélectionnez la même chambre');
            return;
        }
        
        const startDate = new Date(window.selectionStart.date);
        const endDateObj = new Date(endDate);
        
        if (endDateObj <= startDate) {
            alert('❌ La date de départ doit être après l\'arrivée');
            window.resetSelection();
            return;
        }
        
        const nights = Math.ceil((endDateObj - startDate) / (1000 * 60 * 60 * 24));
        const price = parseInt(cell.getAttribute('data-room-price'));
        const totalPrice = price * nights;
        
        const formattedStart = startDate.toLocaleDateString('fr-FR');
        const formattedEnd = endDateObj.toLocaleDateString('fr-FR');
        const formattedPrice = new Intl.NumberFormat('fr-FR').format(price);
        const formattedTotal = new Intl.NumberFormat('fr-FR').format(totalPrice);
        
        window.showModal('Période sélectionnée', `
            <div class="p-2">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <h6>Détails</h6>
                            <div class="mb-2"><small style="color:var(--s400)">Chambre:</small> <strong>${cell.getAttribute('data-room-number')}</strong></div>
                            <div class="mb-2"><small style="color:var(--s400)">Type:</small> <strong>${cell.getAttribute('data-room-type')}</strong></div>
                            <div class="mb-2"><small style="color:var(--s400)">Prix/nuit:</small> <strong>${formattedPrice} FCFA</strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <h6>Période</h6>
                            <div class="mb-2"><small style="color:var(--s400)">Arrivée:</small> <strong>${formattedStart}</strong></div>
                            <div class="mb-2"><small style="color:var(--s400)">Départ:</small> <strong>${formattedEnd}</strong></div>
                            <div class="mb-2"><small style="color:var(--s400)">Durée:</small> <strong>${nights} nuit(s)</strong></div>
                            <div class="mb-2">
                                <small style="color:var(--s400)">Total:</small>
                                <div class="fs-3 fw-bold" style="color:var(--g600)">${formattedTotal} FCFA</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${window.selectionStart.date}&check_out=${endDate}" 
                       class="btn-db btn-db-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Réserver cette période
                    </a>
                    <button class="btn-db btn-db-ghost" onclick="window.resetSelection()">
                        <i class="fas fa-redo me-2"></i>Nouvelle sélection
                    </button>
                </div>
            </div>
        `);
        
        window.resetSelection();
    }
};

/**
 * Appliquer la sélection de dates
 */
window.applyDateSelection = function() {
    const checkIn = document.getElementById('checkInDate')?.value;
    const checkOut = document.getElementById('checkOutDate')?.value;
    
    if (!checkIn || !checkOut) {
        alert('❌ Sélectionnez les deux dates');
        return;
    }
    
    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('❌ La date de départ doit être après l\'arrivée');
        return;
    }
    
    window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
};

/**
 * Annuler la sélection
 */
window.cancelSelection = function() {
    window.resetSelection();
    const modal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
    if (modal) modal.hide();
};

/**
 * Réinitialiser la sélection
 */
window.resetSelection = function() {
    window.selectionMode = false;
    window.selectionStart = null;
    window.selectedCells = [];
    document.querySelectorAll('.avail-cell').forEach(el => {
        el.style.outline = '';
    });
};

/**
 * Vérifier toutes les disponibilités
 */
window.checkAllAvailability = function() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    
    window.promptAction('Date d\'arrivée', function (checkIn) {
        if (!checkIn) return;
        window.promptAction('Date de départ', function (checkOut) {
            if (!checkOut) return;
            if (new Date(checkOut) <= new Date(checkIn)) {
                alert('❌ La date de départ doit être après l\'arrivée');
                return;
            }
            window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
        }, { input: 'date', default: tomorrow });
    }, { input: 'date', default: today });
};

/**
 * Afficher une modale
 */
window.showModal = function(title, content) {
    const modal = document.getElementById('detailsModal');
    const modalTitle = document.getElementById('detailsModalTitle');
    const modalBody = document.getElementById('detailsModalBody');
    
    modalTitle.textContent = title;
    modalBody.innerHTML = content;
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
};

// ============ INITIALISATION ============
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM chargé, initialisation...');
    
    // Variables globales
    window.selectedCells = [];
    window.selectionMode = false;
    window.selectionStart = null;
    
    // Gérer les clics sur les cellules
    document.querySelectorAll('.avail-cell').forEach(function(cell) {
        cell.addEventListener('click', function(e) {
            if (window.selectionMode) {
                window.handlePeriodSelection(this);
            } else {
                const roomId = this.getAttribute('data-room-id');
                const date = this.getAttribute('data-date');
                const isOccupied = this.getAttribute('data-is-occupied') === 'true';
                
                if (!roomId || !date) return;
                
                if (isOccupied) {
                    window.showOccupancyDetails(roomId, date);
                } else {
                    window.showAvailabilityDetails(roomId, date);
                }
            }
        });
    });
    
    // Filtrer par numéro de chambre
    window.filterByRoomNumber = function(searchText) {
        const rows = document.querySelectorAll('.room-row');
        searchText = searchText.toLowerCase().trim();
        
        rows.forEach(row => {
            const roomNum = row.getAttribute('data-room-number');
            if (searchText === '' || roomNum.toLowerCase().includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    };
    
    // Filtrer par statut
    window.filterByStatus = function(status) {
        const rows = document.querySelectorAll('.room-row');
        
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
                return;
            }
            
            const roomStatus = row.getAttribute('data-room-status');
            
            if (status === 'available' && roomStatus == 1) {
                row.style.display = '';
            } else if (status === 'reserved' && roomStatus == 2) {
                row.style.display = '';
            } else if (status === 'unavailable' && roomStatus > 2) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    };
    
    // Écouter le champ de recherche
    const roomNumberInput = document.querySelector('input[name="room_number"]');
    if (roomNumberInput) {
        roomNumberInput.addEventListener('input', function() {
            window.filterByRoomNumber(this.value);
        });
    }
    
    console.log('✅ Initialisation terminée');
});

// Gestionnaire d'erreurs global
window.addEventListener('error', function(e) {
    console.error('❌ Erreur capturée:', e.error?.message || e.message);
});
</script>
@endpush