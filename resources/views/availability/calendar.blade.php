@extends('template.master')

@section('title', 'Calendrier des disponibilités')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --bg:       #f8fafc;
    --surf:     #ffffff;
    --surf2:    #f1f5f9;
    --surf3:    #e2e8f0;
    --brd:      #e2e8f0;
    --brd2:     #cbd5e1;
    --txt:      #0f172a;
    --txt2:     #334155;
    --txt3:     #64748b;
    --acc:      #3b82f6;
    --grn:      #10b981;
    --yel:      #f59e0b;
    --red:      #ef4444;
    --ora:      #f97316;
    --r:        10px;
}

* { box-sizing: border-box; margin:0; padding:0; }
body {
    background: var(--bg);
    color: var(--txt);
    font-family: 'Manrope', sans-serif;
    font-size: 14px;
    line-height: 1.5;
}

/* ══════════════════════════════════════
   TOPBAR
══════════════════════════════════════ */
.cal-topbar {
    position: sticky;
    top: 0;
    z-index: 300;
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--brd);
    padding: 14px 24px;
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
    font-size: 18px;
    font-weight: 800;
    letter-spacing: -.4px;
    margin-bottom: 2px;
    color: var(--txt);
}
.cal-topbar__title p {
    font-size: 12px;
    color: var(--txt3);
}
.cal-topbar__actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
.cal-nav {
    display: flex;
    align-items: center;
    gap: 2px;
    background: var(--surf2);
    border: 1px solid var(--brd);
    border-radius: 8px;
    padding: 2px;
}
.cal-nav__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    background: transparent;
    border: none;
    color: var(--txt2);
    cursor: pointer;
    transition: all .14s;
    text-decoration: none;
}
.cal-nav__btn:hover {
    background: var(--surf3);
    color: var(--txt);
}
.cal-nav__btn.active {
    background: var(--acc);
    color: white;
    pointer-events: none;
}
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid;
    text-decoration: none;
    transition: all .14s;
    cursor: pointer;
    white-space: nowrap;
}
.btn--primary {
    background: var(--acc);
    border-color: var(--acc);
    color: white;
}
.btn--primary:hover {
    background: #2563eb;
    color: white;
}
.btn--outline {
    background: transparent;
    border-color: var(--brd2);
    color: var(--txt2);
}
.btn--outline:hover {
    background: var(--surf3);
    border-color: var(--brd2);
    color: var(--txt);
}

/* ══════════════════════════════════════
   KPIs
══════════════════════════════════════ */
.cal-kpis {
    max-width: 1800px;
    margin: 20px auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
.kpi-card {
    background: var(--surf);
    border: 1px solid var(--brd);
    border-radius: var(--r);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: border-color .2s;
}
.kpi-card:hover { border-color: var(--brd2); }
.kpi-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.kpi-label {
    font-size: 11px;
    color: var(--txt3);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.kpi-value {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.6px;
    line-height: 1;
    margin-top: 2px;
    color: var(--txt);
}

/* ══════════════════════════════════════
   FILTERS
══════════════════════════════════════ */
.cal-filters {
    max-width: 1800px;
    margin: 0 auto 20px;
    padding: 0 24px;
}
.filter-card {
    background: var(--surf);
    border: 1px solid var(--brd);
    border-radius: var(--r);
    padding: 16px 20px;
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    align-items: end;
}
.form-group label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--txt3);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 6px;
}
.form-control,
.form-select {
    width: 100%;
    padding: 8px 12px;
    border-radius: 7px;
    border: 1px solid var(--brd2);
    background: white;
    color: var(--txt);
    font-size: 13px;
    font-family: 'Manrope', sans-serif;
    transition: all .15s;
}
.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--acc);
    box-shadow: 0 0 0 3px rgba(59,130,246,.15);
}
.filter-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

/* ══════════════════════════════════════
   LEGEND
══════════════════════════════════════ */
.cal-legend {
    max-width: 1800px;
    margin: 0 auto 16px;
    padding: 0 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.legend-sq {
    width: 18px;
    height: 18px;
    border-radius: 4px;
}
.legend-sq.avail { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.3); }
.legend-sq.occ   { background: rgba(239,68,68,.15); border: 1px solid rgba(239,68,68,.3); }
.legend-sq.unavail { background: rgba(100,116,139,.15); border: 1px solid rgba(100,116,139,.3); }
.legend-sq.today { background: rgba(245,158,11,.2); border: 2px solid var(--yel); }
.legend-item span {
    font-size: 12px;
    color: var(--txt2);
}
.legend-tip {
    margin-left: auto;
    font-size: 12px;
    color: var(--txt3);
}

/* ══════════════════════════════════════
   CALENDAR TABLE
══════════════════════════════════════ */
.cal-wrap {
    max-width: 1800px;
    margin: 0 auto;
    padding: 0 24px 60px;
}
.cal-container {
    background: var(--surf);
    border: 1px solid var(--brd);
    border-radius: var(--r);
    overflow: hidden;
}
.cal-scroll {
    overflow-x: auto;
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--brd2) transparent;
}
.cal-scroll::-webkit-scrollbar {
    height: 8px;
}
.cal-scroll::-webkit-scrollbar-track {
    background: var(--surf2);
}
.cal-scroll::-webkit-scrollbar-thumb {
    background: var(--brd2);
    border-radius: 99px;
}
.cal-scroll::-webkit-scrollbar-thumb:hover {
    background: var(--brd2);
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
    background: var(--surf2);
    border-bottom: 1px solid var(--brd);
    padding: 10px 12px;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--txt3);
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}
.cal-table th.room-col {
    position: sticky;
    left: 0;
    z-index: 210;
    text-align: left;
    min-width: 260px;
    background: var(--surf2);
    box-shadow: 2px 0 6px rgba(0,0,0,.05);
}
.cal-table th.date-col {
    min-width: 70px;
    cursor: pointer;
    transition: background .15s;
}
.cal-table th.date-col:hover {
    background: var(--surf3);
}
.date-day { font-size: 15px; font-weight: 800; }
.date-name { font-size: 10px; color: var(--txt3); margin-top: 2px; }
.th-today {
    background: rgba(245,158,11,.1) !important;
    border-left: 2px solid var(--yel) !important;
    border-right: 2px solid var(--yel) !important;
    color: #b45309 !important;
}
.th-weekend {
    background: rgba(0,0,0,.02) !important;
}

.cal-table tbody tr {
    border-bottom: 1px solid var(--brd);
}
.cal-table td {
    padding: 0;
    border-right: 1px solid var(--brd);
}
.cal-table td:last-child {
    border-right: none;
}

/* Room info cell */
.room-cell {
    position: sticky;
    left: 0;
    z-index: 100;
    background: var(--surf);
    min-width: 260px;
    padding: 12px 14px;
    box-shadow: 2px 0 6px rgba(0,0,0,.05);
    border-right: 1px solid var(--brd) !important;
}
.room-cell__inner {
    display: flex;
    align-items: center;
    gap: 10px;
}
.room-badge {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--acc), #2563eb);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    flex-shrink: 0;
}
.room-info {
    flex: 1;
}
.room-type {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 2px;
    color: var(--txt);
}
.room-meta {
    font-size: 11px;
    color: var(--txt3);
    display: flex;
    gap: 8px;
}
.room-meta i {
    font-size: 9px;
}
.room-price {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: var(--txt2);
    margin-top: 2px;
}
.room-status-badge {
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    background: var(--surf3);
    color: var(--txt2);
    margin-top: 3px;
    display: inline-block;
}
.room-actions {
    flex-shrink: 0;
}
.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: 1px solid var(--brd2);
    background: transparent;
    color: var(--txt3);
    cursor: pointer;
    transition: all .14s;
    text-decoration: none;
}
.btn-icon:hover {
    background: var(--surf3);
    border-color: var(--acc);
    color: var(--acc);
}

/* Availability cells */
.avail-cell {
    min-width: 70px;
    height: 60px;
    padding: 8px;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: all .14s;
}
.avail-cell i {
    font-size: 14px;
}
.avail-cell:hover {
    transform: scale(1.05);
    z-index: 10;
    box-shadow: 0 0 0 2px var(--acc) inset;
}

/* States */
.avail-cell.available {
    background: rgba(16,185,129,.08);
    border-left: 1px solid rgba(16,185,129,.15);
}
.avail-cell.available i { color: var(--grn); }
.avail-cell.available:hover {
    background: rgba(16,185,129,.15);
}

.avail-cell.occupied {
    background: rgba(239,68,68,.08);
    border-left: 1px solid rgba(239,68,68,.15);
}
.avail-cell.occupied i { color: var(--red); }
.avail-cell.occupied:hover {
    background: rgba(239,68,68,.15);
}

.avail-cell.unavailable {
    background: rgba(100,116,139,.05);
    border-left: 1px solid rgba(100,116,139,.1);
    cursor: not-allowed;
}
.avail-cell.unavailable i { color: var(--txt3); opacity: .5; }
.avail-cell.unavailable:hover {
    transform: none;
    box-shadow: none;
}

.avail-cell.today {
    background: rgba(245,158,11,.12) !important;
    border-left: 2px solid var(--yel) !important;
    border-right: 2px solid var(--yel) !important;
}

.avail-cell.weekend {
    background: rgba(0,0,0,.02);
}

/* Conflict badge */
.conflict-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: var(--red);
    color: white;
    font-size: 9px;
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
    color: var(--txt3);
}
.empty-state i {
    font-size: 48px;
    opacity: .3;
    margin-bottom: 16px;
}
.empty-state h5 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 6px;
    color: var(--txt2);
}
.empty-state p {
    font-size: 13px;
}

/* ══════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════ */
.cal-actions {
    max-width: 1800px;
    margin: 20px auto 0;
    padding: 0 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.btn-group {
    display: flex;
    gap: 6px;
}

/* ══════════════════════════════════════
   MODAL
══════════════════════════════════════ */
.modal-content {
    background: var(--surf);
    border: 1px solid var(--brd);
    color: var(--txt);
}
.modal-header {
    border-bottom: 1px solid var(--brd);
}
.modal-title {
    font-weight: 700;
    font-size: 16px;
}
.modal-footer {
    border-top: 1px solid var(--brd);
}
.card {
    background: var(--surf2);
    border: 1px solid var(--brd);
    border-radius: 8px;
}
.card-body h6 {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--txt3);
    margin-bottom: 10px;
}
.alert {
    border-radius: 8px;
    border: 1px solid;
}
.alert-info {
    background: rgba(59,130,246,.1);
    border-color: rgba(59,130,246,.3);
    color: var(--acc);
}
.alert-warning {
    background: rgba(245,158,11,.1);
    border-color: rgba(245,158,11,.3);
    color: #b45309;
}
.alert-danger {
    background: rgba(239,68,68,.1);
    border-color: rgba(239,68,68,.3);
    color: var(--red);
}
.alert-success {
    background: rgba(16,185,129,.1);
    border-color: rgba(16,185,129,.3);
    color: var(--grn);
}
.badge {
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
}
.badge.bg-success {
    background: rgba(16,185,129,.15) !important;
    color: var(--grn) !important;
}
.badge.bg-warning {
    background: rgba(245,158,11,.15) !important;
    color: #b45309 !important;
}
.badge.bg-danger {
    background: rgba(239,68,68,.15) !important;
    color: var(--red) !important;
}
.table {
    color: var(--txt);
}
.table thead {
    background: var(--surf2);
}
.table th {
    border-color: var(--brd);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: var(--txt3);
}
.table td {
    border-color: var(--brd);
}
.table-hover tbody tr:hover {
    background: rgba(0,0,0,.02);
}

/* ══════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════ */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.cal-kpis .kpi-card { animation: fadeIn .3s ease both; }
.cal-kpis .kpi-card:nth-child(1) { animation-delay: .05s; }
.cal-kpis .kpi-card:nth-child(2) { animation-delay: .10s; }
.cal-kpis .kpi-card:nth-child(3) { animation-delay: .15s; }
.cal-kpis .kpi-card:nth-child(4) { animation-delay: .20s; }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 1200px) {
    .cal-kpis { grid-template-columns: repeat(2, 1fr); }
    .filter-grid { grid-template-columns: 1fr 1fr; }
    .filter-actions { grid-column: 1 / -1; }
}

@media (max-width: 768px) {
    .cal-topbar__inner { flex-direction: column; align-items: flex-start; }
    .cal-topbar__actions { width: 100%; justify-content: space-between; }
    .cal-kpis { grid-template-columns: 1fr; gap: 8px; padding: 0 16px; margin: 16px auto; }
    .kpi-card { padding: 12px 14px; }
    .kpi-value { font-size: 20px; }
    .cal-filters { padding: 0 16px; }
    .filter-grid { grid-template-columns: 1fr; }
    .cal-legend { padding: 0 16px; font-size: 11px; }
    .cal-wrap { padding: 0 16px 40px; }
    .room-cell { min-width: 200px; padding: 10px; }
    .room-badge { width: 30px; height: 30px; font-size: 11px; }
    .avail-cell { min-width: 56px; height: 50px; }
    .cal-actions { padding: 0 16px; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════
     TOPBAR
══════════════════════════════════════ --}}
<div class="cal-topbar">
    <div class="cal-topbar__inner">
        <div class="cal-topbar__title">
            <h1>Calendrier des disponibilités</h1>
            <p>Visualisez les réservations et disponibilités des chambres</p>
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
            <a href="{{ route('availability.search') }}" class="btn btn--primary">
                <i class="fas fa-search"></i>
                Rechercher
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     KPIs
══════════════════════════════════════ --}}
<div class="cal-kpis">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(59,130,246,.1);color:var(--acc)">
            <i class="fas fa-bed"></i>
        </div>
        <div>
            <div class="kpi-label">Chambres totales</div>
            <div class="kpi-value">{{ $stats['total_rooms'] }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(16,185,129,.1);color:var(--grn)">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="kpi-label">Disponibles</div>
            <div class="kpi-value" style="color:var(--grn)">{{ $stats['available_today'] }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(245,158,11,.1);color:var(--yel)">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="kpi-label">Occupées</div>
            <div class="kpi-value" style="color:#b45309">{{ $stats['occupied_today'] }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(239,68,68,.1);color:var(--red)">
            <i class="fas fa-times-circle"></i>
        </div>
        <div>
            <div class="kpi-label">Indisponibles</div>
            <div class="kpi-value" style="color:var(--red)">{{ $stats['unavailable_today'] }}</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     FILTERS
══════════════════════════════════════ --}}
<div class="cal-filters">
    <div class="filter-card">
        <form method="GET" id="calendarFilterForm">
            <div class="filter-grid">
                <div class="form-group">
                    <label>Type de chambre</label>
                    <select name="room_type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Numéro de chambre</label>
                    <input type="text" name="room_number" class="form-control" 
                           value="{{ request('room_number') }}"
                           placeholder="Ex: 101, 102...">
                </div>
                <div class="form-group">
                    <label>Mois</label>
                    <input type="month" name="month_year" class="form-control" 
                           value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                           onchange="this.form.submit()">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn--primary">
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('availability.calendar') }}" class="btn btn--outline">
                        <i class="fas fa-times"></i>
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════
     LEGEND
══════════════════════════════════════ --}}
<div class="cal-legend">
    <div class="legend-item">
        <div class="legend-sq avail"></div>
        <span>Disponible</span>
    </div>
    <div class="legend-item">
        <div class="legend-sq occ"></div>
        <span>Occupée</span>
    </div>
    <div class="legend-item">
        <div class="legend-sq unavail"></div>
        <span>Indisponible</span>
    </div>
    <div class="legend-item">
        <div class="legend-sq today"></div>
        <span>Aujourd'hui</span>
    </div>
    <div class="legend-item">
        <div class="badge bg-danger">2+</div>
        <span>Conflit</span>
    </div>
    <span class="legend-tip">
        <i class="fas fa-mouse-pointer" style="margin-right:4px"></i>
        Cliquez sur une cellule pour les détails
    </span>
</div>

{{-- ══════════════════════════════════════
     CALENDAR
══════════════════════════════════════ --}}
<div class="cal-wrap">
    <div class="cal-container">
        <div class="cal-scroll">
            <table class="cal-table">
                <thead>
                    <tr>
                        <th class="room-col">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span>Chambre / Date</span>
                                <button type="button" class="btn-icon" onclick="scrollToToday()" title="Aller à aujourd'hui">
                                    <i class="fas fa-calendar-day"></i>
                                </button>
                            </div>
                        </th>
                        @foreach($dates as $dateString => $dateInfo)
                            <th class="date-col {{ $dateInfo['is_today'] ? 'th-today' : '' }} {{ $dateInfo['is_weekend'] ? 'th-weekend' : '' }}"
                                data-date="{{ $dateString }}"
                                onclick="scrollToDate('{{ $dateString }}')"
                                title="Cliquez pour centrer">
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
                                    <h5>Aucune chambre trouvée</h5>
                                    <p>Aucune chambre ne correspond aux filtres sélectionnés</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($calendar as $roomData)
                            <tr class="room-row" data-room-number="{{ $roomData['room']->number }}">
                                <td class="room-cell">
                                    <div class="room-cell__inner">
                                        <div class="room-badge">{{ $roomData['room']->number }}</div>
                                        <div class="room-info">
                                            <div class="room-type">{{ $roomData['room']->type->name ?? 'Type inconnu' }}</div>
                                            <div class="room-meta">
                                                <span><i class="fas fa-users"></i> {{ $roomData['room']->capacity }} pers.</span>
                                            </div>
                                            <div class="room-price">{{ number_format($roomData['room']->price, 0, ',', ' ') }} FCFA/nuit</div>
                                            @if($roomData['room']->room_status_id != 1)
                                                <span class="room-status-badge">
                                                    {{ $roomData['room']->roomStatus->name ?? 'Indisponible' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="room-actions">
                                            <a href="{{ route('availability.room.detail', $roomData['room']->id) }}" 
                                               class="btn-icon"
                                               title="Voir détails">
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
                                        title="{{ $dateInfo['date']->format('d/m/Y') }} - Chambre {{ $roomData['room']->number }} - {{ $isOccupied ? 'Occupée' : 'Disponible' }}">
                                        @if($isOccupied)
                                            <i class="fas fa-user"></i>
                                            @if($reservationCount > 1)
                                                <div class="conflict-badge">
                                                    {{ $reservationCount }}
                                                    @if($reservationCount > 2)
                                                        <i class="fas fa-exclamation"></i>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <i class="fas fa-check"></i>
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

{{-- ══════════════════════════════════════
     ACTIONS
══════════════════════════════════════ --}}
<div class="cal-actions">
    <div class="btn-group">
        <button type="button" class="btn btn--outline" onclick="selectDateRange()">
            <i class="fas fa-calendar-range"></i>
            Sélectionner période
        </button>
        <button type="button" class="btn btn--outline" onclick="checkAllAvailability()">
            <i class="fas fa-search"></i>
            Vérifier disponibilité
        </button>
    </div>
    <div class="btn-group">
        <button class="btn btn--outline" onclick="window.print()">
            <i class="fas fa-print"></i>
            Imprimer
        </button>
        <a href="{{ route('availability.export', [
            'type' => 'excel',
            'export_type' => 'calendar',
            'month' => $month,
            'year' => $year
        ]) }}" class="btn btn--outline" style="color:var(--grn);border-color:rgba(16,185,129,.3)">
            <i class="fas fa-file-excel"></i>
            Exporter Excel
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (el) {
        return new bootstrap.Tooltip(el, { trigger: 'hover', placement: 'top' });
    });
    
    // Variables globales
    window.selectedCells = [];
    window.selectionMode = false;
    window.selectionStart = null;
    
    // Gérer les clics sur les cellules
    document.querySelectorAll('.avail-cell').forEach(function(cell) {
        cell.addEventListener('click', function(e) {
            if (window.selectionMode) {
                handlePeriodSelection(this);
            } else {
                const roomId = this.getAttribute('data-room-id');
                const date = this.getAttribute('data-date');
                const isOccupied = this.getAttribute('data-is-occupied') === 'true';
                
                if (!roomId || !date) return;
                
                if (isOccupied) {
                    showOccupancyDetails(roomId, date);
                } else {
                    showAvailabilityDetails(roomId, date);
                }
            }
        });
    });
    
    // Filtrage par numéro
    const roomNumberInput = document.querySelector('input[name="room_number"]');
    if (roomNumberInput) {
        roomNumberInput.addEventListener('input', function() {
            const rows = document.querySelectorAll('.room-row');
            const searchText = this.value.toLowerCase().trim();
            
            rows.forEach(row => {
                const roomNum = row.getAttribute('data-room-number');
                if (searchText === '' || roomNum.toLowerCase().includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

function scrollToDate(dateString) {
    const targetCell = document.querySelector(`.avail-cell[data-date="${dateString}"]`);
    if (targetCell) {
        targetCell.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

function scrollToToday() {
    const today = new Date().toISOString().split('T')[0];
    scrollToDate(today);
}

function showOccupancyDetails(roomId, date) {
    fetch(`/availability/calendar-cell-details?room_id=${roomId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            let content = `
                <div class="p-3">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-calendar-times me-2" style="color:var(--red)"></i>
                        Chambre Occupée
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card" style="border-color:rgba(239,68,68,.3)">
                                <div class="card-body">
                                    <h6>Informations Chambre</h6>
                                    <div class="mb-2"><small style="color:var(--txt3)">Numéro:</small> <strong>${data.room.number}</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Type:</small> <strong>${data.room.type}</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Capacité:</small> <strong>${data.room.capacity} pers.</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Prix/nuit:</small> <strong>${data.room.price} FCFA</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Date:</small> <strong>${new Date(date).toLocaleDateString('fr-FR')}</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Cette chambre n'est pas disponible pour cette date.
                            </div>
                        </div>
                    </div>
            `;
            
            if (data.reservations && data.reservations.length > 0) {
                content += `<h6 class="mb-3">Réservations (${data.reservations.length})</h6><div class="table-responsive"><table class="table table-sm table-hover"><thead><tr><th>Client</th><th>Arrivée</th><th>Départ</th><th>Statut</th><th>Actions</th></tr></thead><tbody>`;
                
                data.reservations.forEach(r => {
                    content += `<tr>
                        <td><div class="fw-bold">${r.customer.name || 'Client'}</div><small style="color:var(--txt3)">${r.customer.email || ''}</small></td>
                        <td>${new Date(r.check_in).toLocaleDateString('fr-FR')}</td>
                        <td>${new Date(r.check_out).toLocaleDateString('fr-FR')}</td>
                        <td><span class="badge ${r.status === 'active' ? 'bg-success' : 'bg-warning'}">${r.status === 'active' ? 'En séjour' : 'Réservée'}</span></td>
                        <td><a href="/transactions/${r.id}" class="btn btn--outline btn-sm" target="_blank"><i class="fas fa-external-link-alt"></i></a></td>
                    </tr>`;
                });
                
                content += `</tbody></table></div>`;
                
                if (data.reservations.length > 1) {
                    content += `<div class="alert alert-danger mt-3"><i class="fas fa-exclamation-triangle me-2"></i><strong>ALERTE:</strong> ${data.reservations.length} réservations!</div>`;
                }
            }
            
            content += `<div class="mt-4 d-grid gap-2">
                <a href="/availability/search?room_type_id=${data.room.type_id}" class="btn btn--primary"><i class="fas fa-search me-2"></i>Chercher une autre chambre</a>
            </div></div>`;
            
            showModal('Détails d\'occupation', content);
        })
        .catch(error => {
            showModal('Erreur', `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erreur: ${error.message}</div>`);
        });
}

function showAvailabilityDetails(roomId, date) {
    fetch(`/availability/check-availability?room_id=${roomId}&check_in=${date}&check_out=${date}`)
        .then(response => response.json())
        .then(data => {
            let content = `
                <div class="p-3">
                    <h5 class="fw-bold mb-3"><i class="fas fa-calendar-check me-2" style="color:var(--grn)"></i>Chambre Disponible</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card" style="border-color:rgba(16,185,129,.3)">
                                <div class="card-body">
                                    <h6>Informations Chambre</h6>
                                    <div class="mb-2"><small style="color:var(--txt3)">Numéro:</small> <strong>${data.room.number}</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Type:</small> <strong>${data.room.type}</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Capacité:</small> <strong>${data.room.capacity} pers.</strong></div>
                                    <div class="mb-2"><small style="color:var(--txt3)">Prix/nuit:</small> <strong>${data.room.price.toLocaleString()} FCFA</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="border-color:rgba(59,130,246,.3)">
                                <div class="card-body">
                                    <h6>Réservation</h6>
                                    <div class="mb-2"><small style="color:var(--txt3)">Date:</small> <strong>${new Date(date).toLocaleDateString('fr-FR')}</strong></div>
                                    <div class="mb-3"><small style="color:var(--txt3)">Prix total:</small> <div class="fs-4 fw-bold" style="color:var(--grn)">${data.total_price.toLocaleString()} FCFA</div></div>
                                    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Chambre disponible</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${date}&check_out=${date}" class="btn btn--primary btn-lg"><i class="fas fa-plus me-2"></i>Réserver cette chambre</a>
                        <button type="button" class="btn btn--outline" onclick="selectDateRangeFromCell('${roomId}', '${date}')"><i class="fas fa-calendar-range me-2"></i>Sélectionner une période</button>
                    </div>
                </div>
            `;
            showModal('Chambre disponible', content);
        })
        .catch(error => {
            showModal('Erreur', `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erreur: ${error.message}</div>`);
        });
}

function selectDateRange() {
    window.selectionMode = true;
    window.selectedCells = [];
    showModal('Sélection de période', `
        <div class="p-3">
            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-range me-2"></i>Sélectionner une période</h5>
            <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Mode sélection activé. Cliquez sur la première date, puis sur la dernière.</div>
            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date d'arrivée</label>
                    <input type="date" id="checkInDate" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de départ</label>
                    <input type="date" id="checkOutDate" class="form-control">
                </div>
            </div>
            <button class="btn btn--primary w-100" onclick="applyDateSelection()"><i class="fas fa-check me-2"></i>Appliquer</button>
        </div>
    `);
}

function selectDateRangeFromCell(roomId, startDate) {
    window.selectionMode = true;
    window.selectionStart = { roomId, date: startDate };
    showModal('Sélection de période', `
        <div class="p-3">
            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-range me-2"></i>Sélectionner la date de départ</h5>
            <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Arrivée: <strong>${new Date(startDate).toLocaleDateString('fr-FR')}</strong><br>Cliquez sur la date de départ dans le calendrier.</div>
            <button class="btn btn--outline w-100" onclick="cancelSelection()"><i class="fas fa-times me-2"></i>Annuler</button>
        </div>
    `);
}

function handlePeriodSelection(cell) {
    if (!window.selectionStart) {
        window.selectionStart = {
            roomId: cell.getAttribute('data-room-id'),
            date: cell.getAttribute('data-date'),
            element: cell
        };
        cell.style.outline = '2px solid var(--acc)';
        showModal('Sélection de période', `
            <div class="p-3">
                <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Arrivée: <strong>${new Date(window.selectionStart.date).toLocaleDateString('fr-FR')}</strong><br>Chambre: <strong>${cell.getAttribute('data-room-number')}</strong><br>Cliquez sur la date de départ.</div>
            </div>
        `);
    } else {
        const roomId = cell.getAttribute('data-room-id');
        const endDate = cell.getAttribute('data-date');
        
        if (roomId !== window.selectionStart.roomId) {
            alert('Sélectionnez la même chambre');
            return;
        }
        
        const startDate = new Date(window.selectionStart.date);
        const endDateObj = new Date(endDate);
        
        if (endDateObj <= startDate) {
            alert('La date de départ doit être après l\'arrivée');
            resetSelection();
            return;
        }
        
        const nights = Math.ceil((endDateObj - startDate) / (1000 * 60 * 60 * 24));
        const price = parseInt(cell.getAttribute('data-room-price'));
        
        showModal('Période sélectionnée', `
            <div class="p-3">
                <h5 class="fw-bold mb-3"><i class="fas fa-calendar-check me-2"></i>Période sélectionnée</h5>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Détails</h6>
                                <div class="mb-2"><small style="color:var(--txt3)">Chambre:</small> <strong>${cell.getAttribute('data-room-number')}</strong></div>
                                <div class="mb-2"><small style="color:var(--txt3)">Type:</small> <strong>${cell.getAttribute('data-room-type')}</strong></div>
                                <div class="mb-2"><small style="color:var(--txt3)">Prix/nuit:</small> <strong>${price.toLocaleString()} FCFA</strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Période</h6>
                                <div class="mb-2"><small style="color:var(--txt3)">Arrivée:</small> <strong>${startDate.toLocaleDateString('fr-FR')}</strong></div>
                                <div class="mb-2"><small style="color:var(--txt3)">Départ:</small> <strong>${endDateObj.toLocaleDateString('fr-FR')}</strong></div>
                                <div class="mb-2"><small style="color:var(--txt3)">Durée:</small> <strong>${nights} nuit(s)</strong></div>
                                <div class="mb-2"><small style="color:var(--txt3)">Total:</small> <div class="fs-4 fw-bold" style="color:var(--grn)">${(price * nights).toLocaleString()} FCFA</div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="/transaction/reservation/createIdentity?room_id=${roomId}&check_in=${window.selectionStart.date}&check_out=${endDate}" class="btn btn--primary btn-lg"><i class="fas fa-plus me-2"></i>Réserver cette période</a>
                    <button class="btn btn--outline" onclick="resetSelection()"><i class="fas fa-redo me-2"></i>Sélectionner une autre période</button>
                </div>
            </div>
        `);
        
        resetSelection();
    }
}

function applyDateSelection() {
    const checkIn = document.getElementById('checkInDate').value;
    const checkOut = document.getElementById('checkOutDate').value;
    
    if (!checkIn || !checkOut) {
        alert('Sélectionnez les deux dates');
        return;
    }
    
    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('La date de départ doit être après l\'arrivée');
        return;
    }
    
    window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
}

function cancelSelection() {
    resetSelection();
    const modal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
    if (modal) modal.hide();
}

function resetSelection() {
    window.selectionMode = false;
    window.selectionStart = null;
    window.selectedCells = [];
    document.querySelectorAll('.avail-cell').forEach(el => {
        el.style.outline = '';
    });
}

function checkAllAvailability() {
    const checkIn = prompt('Date d\'arrivée (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    if (!checkIn) return;
    
    const checkOut = prompt('Date de départ (YYYY-MM-DD):', new Date(Date.now() + 86400000).toISOString().split('T')[0]);
    if (!checkOut) return;
    
    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('La date de départ doit être après l\'arrivée');
        return;
    }
    
    window.location.href = `/availability/search?check_in=${checkIn}&check_out=${checkOut}`;
}

function showModal(title, content) {
    let modal = document.getElementById('detailsModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'detailsModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detailsModalBody"></div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('detailsModalBody').innerHTML = content;
    modal.querySelector('.modal-title').textContent = title;
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>
@endpush