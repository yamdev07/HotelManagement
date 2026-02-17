@extends('template.master')
@section('title', 'Dashboard')
@section('content')

<style>
/* ══════════════════════════════════════════════
   VARIABLES
══════════════════════════════════════════════ */
:root {
    --green-950: #052e16;
    --green-900: #064e3b;
    --green-800: #065f46;
    --green-700: #047857;
    --green-600: #059669;
    --green-500: #10b981;
    --green-400: #34d399;
    --green-200: #a7f3d0;
    --green-100: #d1fae5;
    --green-50:  #ecfdf5;

    --blue-700:  #1d4ed8;
    --blue-600:  #2563eb;
    --blue-500:  #3b82f6;
    --blue-100:  #dbeafe;
    --blue-50:   #eff6ff;

    --amber-600: #d97706;
    --amber-500: #f59e0b;
    --amber-100: #fef3c7;
    --amber-50:  #fffbeb;

    --red-600:   #dc2626;
    --red-500:   #ef4444;
    --red-100:   #fee2e2;
    --red-50:    #fef2f2;

    --violet-500: #8b5cf6;
    --violet-100: #ede9fe;
    --violet-50:  #f5f3ff;

    --slate-900: #0f172a;
    --slate-800: #1e293b;
    --slate-700: #334155;
    --slate-600: #475569;
    --slate-500: #64748b;
    --slate-400: #94a3b8;
    --slate-300: #cbd5e1;
    --slate-200: #e2e8f0;
    --slate-100: #f1f5f9;
    --slate-50:  #f8fafc;

    --shadow-xs:  0 1px 2px rgba(0,0,0,.05);
    --shadow-sm:  0 1px 4px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
    --shadow-md:  0 4px 14px rgba(0,0,0,.09), 0 2px 6px rgba(0,0,0,.05);
    --shadow-lg:  0 10px 32px rgba(0,0,0,.11), 0 4px 12px rgba(0,0,0,.06);
    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  16px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
}

/* ── Page ───────────────────────────────────── */
.db-page {
    padding: 24px 28px 56px;
    background: var(--slate-50);
    min-height: 100vh;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeUp .3s ease both; }
.anim-2 { animation: fadeUp .3s .06s ease both; }
.anim-3 { animation: fadeUp .3s .12s ease both; }
.anim-4 { animation: fadeUp .3s .18s ease both; }
.anim-5 { animation: fadeUp .3s .24s ease both; }
.anim-6 { animation: fadeUp .3s .30s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.db-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap; gap: 16px;
    margin-bottom: 28px;
}
.db-header-greeting {
    font-size: 1.55rem; font-weight: 800;
    color: var(--slate-900); line-height: 1.2; margin: 0;
}
.db-header-greeting span { color: var(--green-700); }
.db-header-sub {
    font-size: .875rem; color: var(--slate-500); margin: 5px 0 0;
}
.db-header-right {
    display: flex; align-items: center; gap: 12px;
}
.db-time-block { text-align: right; }
.db-time-date { font-size: .75rem; color: var(--slate-400); }
.db-time-clock {
    font-size: 1.3rem; font-weight: 800;
    color: var(--green-800); letter-spacing: -.5px;
    font-variant-numeric: tabular-nums;
}
.btn-website {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 15px; border-radius: var(--radius-sm);
    font-size: .82rem; font-weight: 500;
    color: var(--slate-600); background: white;
    border: 1.5px solid var(--slate-200);
    text-decoration: none; transition: var(--transition);
    box-shadow: var(--shadow-xs);
}
.btn-website:hover {
    background: var(--slate-50); border-color: var(--slate-300);
    color: var(--slate-900); text-decoration: none;
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 24px;
}
@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: white;
    border-radius: var(--radius-md);
    padding: 20px 22px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    text-decoration: none;
    display: block;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}
.stat-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--card-accent, var(--green-500));
    border-radius: 4px 4px 0 0;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
    border-color: transparent;
}
.stat-card--blue   { --card-accent: var(--blue-500); }
.stat-card--green  { --card-accent: var(--green-500); }
.stat-card--amber  { --card-accent: var(--amber-500); }
.stat-card--red    { --card-accent: var(--red-500); }

.stat-card-top { display: flex; justify-content: space-between; align-items: flex-start; }
.stat-card-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.stat-card--blue .stat-card-icon  { background: var(--blue-50);   color: var(--blue-600); }
.stat-card--green .stat-card-icon { background: var(--green-50);  color: var(--green-700); }
.stat-card--amber .stat-card-icon { background: var(--amber-50);  color: var(--amber-600); }
.stat-card--red .stat-card-icon   { background: var(--red-50);    color: var(--red-600); }

.stat-card-badge {
    font-size: .68rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: .3px;
}
.stat-card--blue .stat-card-badge  { background: var(--blue-50);   color: var(--blue-700); }
.stat-card--green .stat-card-badge { background: var(--green-50);  color: var(--green-800); }
.stat-card--amber .stat-card-badge { background: var(--amber-50);  color: var(--amber-600); }
.stat-card--red .stat-card-badge   { background: var(--red-50);    color: var(--red-600); }

.stat-card-value {
    font-size: 2.4rem; font-weight: 900;
    color: var(--slate-900); line-height: 1;
    margin: 14px 0 4px;
    font-variant-numeric: tabular-nums;
}
.stat-card-label {
    font-size: .82rem; color: var(--slate-500);
    font-weight: 500; margin-bottom: 10px;
}
.stat-card-meta {
    display: flex; align-items: center; gap: 5px;
    font-size: .75rem;
    padding-top: 10px;
    border-top: 1px solid var(--slate-100);
}
.stat-card--blue .stat-card-meta  { color: var(--blue-600); }
.stat-card--green .stat-card-meta { color: var(--green-700); }
.stat-card--amber .stat-card-meta { color: var(--amber-600); }
.stat-card--red .stat-card-meta   { color: var(--red-600); }

/* ══════════════════════════════════════════════
   ARRIVALS / DEPARTURES PANEL
══════════════════════════════════════════════ */
.db-panel {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    overflow: hidden;
    margin-bottom: 24px;
}
.db-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--slate-100);
}
.db-panel-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 1rem; font-weight: 700; color: var(--slate-800);
    margin: 0;
}
.db-panel-title-dot {
    width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
}
.db-panel-body { padding: 20px 24px; }

/* 3-col date grid */
.dates-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
    margin-bottom: 16px;
}
@media (max-width: 640px) { .dates-grid { grid-template-columns: 1fr; } }

.date-card {
    border-radius: var(--radius-md);
    border: 1.5px solid var(--slate-200);
    padding: 14px 16px;
    text-decoration: none;
    display: block; transition: var(--transition);
}
.date-card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
    text-decoration: none;
    background: var(--green-50);
}
.date-card--today {
    border-color: var(--green-200);
    background: var(--green-50);
}
.date-card-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px;
}
.date-card-name { font-size: .82rem; font-weight: 700; color: var(--slate-700); }
.date-card-pill {
    font-size: .68rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    background: var(--slate-100); color: var(--slate-600);
}
.date-card--today .date-card-pill { background: var(--green-100); color: var(--green-800); }
.date-card-rows { display: flex; flex-direction: column; gap: 5px; }
.date-card-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .82rem;
}
.date-card-row-label { color: var(--slate-500); display: flex; align-items: center; gap: 6px; }
.date-card-row-val { font-weight: 700; color: var(--slate-900); }

/* Rooms row */
.rooms-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
}
@media (max-width: 640px) { .rooms-grid { grid-template-columns: 1fr; } }

.room-stat-card {
    border-radius: var(--radius-md);
    border: 1.5px solid var(--slate-200);
    padding: 16px;
    text-decoration: none; display: block;
    text-align: center; transition: var(--transition);
}
.room-stat-card:hover {
    border-color: var(--green-200); background: var(--green-50);
    transform: translateY(-2px); box-shadow: var(--shadow-sm);
    text-decoration: none;
}
.room-stat-card-label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .5px; color: var(--slate-500); margin-bottom: 8px;
    display: flex; align-items: center; justify-content: space-between;
}
.room-stat-badge {
    font-size: .65rem; font-weight: 700;
    padding: 2px 7px; border-radius: 10px;
}
.room-stat-value {
    font-size: 2rem; font-weight: 900; color: var(--slate-900);
    line-height: 1; margin-bottom: 4px;
}
.room-stat-sub { font-size: .72rem; color: var(--slate-400); }

.occupancy-bar {
    height: 6px; background: var(--slate-100);
    border-radius: 3px; margin-top: 10px; overflow: hidden;
}
.occupancy-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green-500), var(--green-400));
    border-radius: 3px;
    transition: width .6s ease;
}

/* ══════════════════════════════════════════════
   MAIN GRID (table + sidebar)
══════════════════════════════════════════════ */
.db-main-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px; align-items: start;
}
@media (max-width: 1100px) { .db-main-grid { grid-template-columns: 1fr; } }

/* ══════════════════════════════════════════════
   GUESTS TABLE
══════════════════════════════════════════════ */
.db-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    overflow: hidden;
    margin-bottom: 20px;
}
.db-card:last-child { margin-bottom: 0; }

.db-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1px solid var(--slate-100);
    flex-wrap: wrap; gap: 10px;
}
.db-card-title {
    display: flex; align-items: center; gap: 9px;
    font-size: .95rem; font-weight: 700; color: var(--slate-800); margin: 0;
}
.db-card-title-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.db-card-subtitle { font-size: .75rem; color: var(--slate-400); margin-top: 2px; }
.db-card-actions { display: flex; gap: 8px; flex-wrap: wrap; }

/* Buttons */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--radius-sm);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
}
.btn-db-primary {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white; box-shadow: 0 3px 10px rgba(5,150,105,.25);
}
.btn-db-primary:hover {
    box-shadow: 0 5px 14px rgba(5,150,105,.35);
    color: white; transform: translateY(-1px); text-decoration: none;
}
.btn-db-ghost {
    background: white; color: var(--slate-600);
    border: 1.5px solid var(--slate-200);
    box-shadow: var(--shadow-xs);
}
.btn-db-ghost:hover {
    background: var(--slate-50); border-color: var(--slate-300);
    color: var(--slate-900); text-decoration: none;
}
.btn-db-icon {
    width: 32px; height: 32px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 7px; font-size: .82rem;
    background: white; color: var(--slate-500);
    border: 1.5px solid var(--slate-200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
}
.btn-db-icon:hover { background: var(--slate-50); color: var(--slate-900); border-color: var(--slate-300); }
.btn-db-icon-green:hover { background: var(--green-50); color: var(--green-700); border-color: var(--green-200); }
.btn-db-icon-red:hover { background: var(--red-50); color: var(--red-600); border-color: var(--red-100); }

/* Table */
.db-table { width: 100%; border-collapse: collapse; }
.db-table thead th {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    color: var(--slate-400); padding: 11px 18px;
    background: var(--slate-50);
    border-bottom: 1px solid var(--slate-200);
    white-space: nowrap;
}
.db-table tbody tr {
    border-bottom: 1px solid var(--slate-100);
    transition: var(--transition);
}
.db-table tbody tr:last-child { border-bottom: none; }
.db-table tbody tr:hover { background: var(--slate-50); }
.db-table tbody tr.row-new { background: #f0fdf4; }
.db-table td { padding: 13px 18px; vertical-align: middle; }

.guest-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--green-100), var(--green-50));
    border: 1.5px solid var(--green-200);
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; font-weight: 700;
    color: var(--green-800); flex-shrink: 0;
}
.guest-name {
    font-size: .875rem; font-weight: 600;
    color: var(--slate-900); text-decoration: none;
    transition: var(--transition);
}
.guest-name:hover { color: var(--green-700); text-decoration: none; }
.guest-sub { font-size: .72rem; color: var(--slate-400); margin-top: 2px; }

.room-link {
    font-size: .875rem; font-weight: 600;
    color: var(--slate-800); text-decoration: none;
    transition: var(--transition);
}
.room-link:hover { color: var(--green-700); text-decoration: none; }
.room-type-label { font-size: .72rem; color: var(--slate-400); margin-top: 2px; }

.date-in  { font-size: .8rem; color: var(--green-700); font-weight: 600; display: flex; align-items: center; gap: 6px; }
.date-out { font-size: .8rem; color: var(--slate-500); display: flex; align-items: center; gap: 6px; margin-top: 4px; }
.checkout-today-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .68rem; font-weight: 700;
    background: var(--red-50); color: var(--red-600);
    border: 1px solid var(--red-100);
    padding: 2px 7px; border-radius: 5px; margin-top: 5px;
}
.tag-new {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 700;
    background: var(--blue-50); color: var(--blue-600);
    border: 1px solid var(--blue-100);
    padding: 1px 6px; border-radius: 5px; margin-left: 6px;
}

.balance-paid {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--green-50); color: var(--green-700);
    border: 1px solid var(--green-200);
    font-size: .75rem; font-weight: 700;
    padding: 4px 10px; border-radius: 6px;
}
.balance-due-amount { font-size: .9rem; font-weight: 800; color: var(--red-600); }
.balance-total { font-size: .72rem; color: var(--slate-400); margin-top: 2px; }
.btn-pay-now {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 6px; font-size: .75rem; font-weight: 600;
    background: var(--amber-50); color: var(--amber-600);
    border: 1.5px solid var(--amber-100);
    text-decoration: none; margin-top: 6px; transition: var(--transition);
}
.btn-pay-now:hover {
    background: var(--amber-500); color: white;
    border-color: var(--amber-500); text-decoration: none;
    transform: translateY(-1px);
}

.action-group { display: flex; gap: 5px; justify-content: flex-end; flex-wrap: wrap; }

/* Dropdown */
.db-dropdown { position: relative; }
.db-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px);
    background: white; border: 1px solid var(--slate-200);
    border-radius: var(--radius-md); box-shadow: var(--shadow-lg);
    min-width: 170px; z-index: 500; overflow: hidden;
    display: none; animation: fadeUp .15s ease;
}
.db-dropdown-menu.open { display: block; }
.db-dropdown-item {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 14px; font-size: .82rem; font-weight: 500;
    color: var(--slate-700); text-decoration: none;
    transition: var(--transition); cursor: pointer;
    border: none; background: none; width: 100%; text-align: left;
}
.db-dropdown-item:hover { background: var(--slate-50); color: var(--slate-900); text-decoration: none; }
.db-dropdown-item-danger { color: var(--red-600); }
.db-dropdown-item-danger:hover { background: var(--red-50); color: var(--red-700); }
.db-dropdown-divider { height: 1px; background: var(--slate-100); margin: 4px 0; }
.db-filter-dropdown { min-width: 190px; }

/* Empty state */
.db-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 56px 24px; text-align: center;
}
.db-empty-icon {
    width: 76px; height: 76px;
    background: var(--slate-100); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: var(--slate-300); margin-bottom: 18px;
}

/* ══════════════════════════════════════════════
   RIGHT SIDEBAR
══════════════════════════════════════════════ */
/* Quick check-in */
.qci-form { display: flex; gap: 8px; margin-bottom: 14px; }
.qci-input {
    flex: 1; height: 38px; padding: 0 12px;
    border: 1.5px solid var(--slate-200);
    border-radius: var(--radius-sm); font-size: .82rem;
    outline: none; transition: var(--transition);
    color: var(--slate-900);
}
.qci-input:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(16,185,129,.1); }
.qci-btn {
    width: 38px; height: 38px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white; border: none; border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .85rem;
    transition: var(--transition);
}
.qci-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(5,150,105,.3); }
.qci-cta { display: flex; flex-direction: column; gap: 8px; }
.btn-qci-outline {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: var(--radius-sm);
    font-size: .82rem; font-weight: 500;
    color: var(--green-800); background: white;
    border: 1.5px solid var(--green-200);
    text-decoration: none; transition: var(--transition);
}
.btn-qci-outline:hover { background: var(--green-50); border-color: var(--green-300); text-decoration: none; }
.btn-qci-solid {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: var(--radius-sm);
    font-size: .82rem; font-weight: 600;
    color: white;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    text-decoration: none; transition: var(--transition);
    box-shadow: 0 3px 10px rgba(5,150,105,.25);
}
.btn-qci-solid:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(5,150,105,.35); color: white; text-decoration: none; }

/* Quick actions list */
.qa-list { display: flex; flex-direction: column; gap: 4px; }
.qa-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: var(--radius-sm);
    font-size: .82rem; font-weight: 500;
    color: var(--slate-700); text-decoration: none;
    transition: var(--transition); background: var(--slate-50);
    border: 1px solid transparent;
}
.qa-item:hover {
    background: var(--green-50); color: var(--green-800);
    border-color: var(--green-100); text-decoration: none;
    padding-left: 16px;
}
.qa-item-icon {
    width: 30px; height: 30px; flex-shrink: 0;
    border-radius: 7px; display: flex; align-items: center;
    justify-content: center; font-size: .8rem;
    background: white; color: var(--slate-500);
    border: 1px solid var(--slate-200);
    transition: var(--transition);
}
.qa-item:hover .qa-item-icon { background: var(--green-100); color: var(--green-700); border-color: var(--green-200); }

/* System status */
.status-list { display: flex; flex-direction: column; gap: 0; }
.status-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid var(--slate-100);
    font-size: .82rem;
}
.status-row:last-child { border-bottom: none; }
.status-key { color: var(--slate-500); }
.status-badge {
    font-size: .68rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px;
    text-transform: uppercase; letter-spacing: .3px;
}
.status-online  { background: var(--green-100); color: var(--green-800); }
.status-normal  { background: var(--amber-100); color: var(--amber-600); }
.status-info    { background: var(--blue-100);  color: var(--blue-700); }
.status-neutral { background: var(--slate-100); color: var(--slate-700); }

.btn-refresh-full {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 8px 0; margin-top: 14px;
    border-radius: var(--radius-sm); font-size: .8rem; font-weight: 500;
    color: var(--slate-600); background: var(--slate-50);
    border: 1.5px solid var(--slate-200); cursor: pointer;
    transition: var(--transition);
}
.btn-refresh-full:hover { background: var(--slate-100); border-color: var(--slate-300); color: var(--slate-900); }

/* Separator inside sidebar card */
.db-card-body { padding: 18px 22px; }
.db-card-footer {
    padding: 12px 22px;
    border-top: 1px solid var(--slate-100);
    background: var(--slate-50);
}
</style>

<div class="db-page">

    <!-- ─── Header ─────────────────────────────── -->
    <div class="db-header anim-1">
        <div>
            <h1 class="db-header-greeting">
                Bonjour, <span>{{ auth()->user()->name }}</span> 👋
            </h1>
            <p class="db-header-sub">
                Vue d'ensemble des opérations · {{ now()->translatedFormat('l d F Y') }}
            </p>
        </div>
        <div class="db-header-right">
            <div class="db-time-block">
                <div class="db-time-date">{{ now()->translatedFormat('l d F') }}</div>
                <div class="db-time-clock" id="db-clock">{{ now()->format('H:i') }}</div>
            </div>
            <a href="{{ route('frontend.home') }}" target="_blank" class="btn-website">
                <i class="fas fa-external-link-alt fa-xs"></i> Site web
            </a>
        </div>
    </div>

    <!-- ─── Stat Cards ──────────────────────────── -->
    <div class="stats-grid anim-2">
        <a href="{{ route('transaction.index') }}?status=active&date_filter=today" class="stat-card stat-card--blue">
            <div class="stat-card-top">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                <span class="stat-card-badge">Aujourd'hui</span>
            </div>
            <div class="stat-card-value">{{ $stats['activeGuests'] ?? 0 }}</div>
            <div class="stat-card-label">Clients actifs</div>
            <div class="stat-card-meta">
                <i class="fas fa-arrow-up"></i>
                {{ $stats['todayArrivals'] ?? 0 }} nouvelle{{ ($stats['todayArrivals'] ?? 0) > 1 ? 's' : '' }} arrivée{{ ($stats['todayArrivals'] ?? 0) > 1 ? 's' : '' }}
            </div>
        </a>

        <a href="{{ route('transaction.index') }}?status=completed&date_filter=today" class="stat-card stat-card--green">
            <div class="stat-card-top">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
                <span class="stat-card-badge">Terminé</span>
            </div>
            <div class="stat-card-value">{{ $stats['completedToday'] ?? 0 }}</div>
            <div class="stat-card-label">Check-outs today</div>
            <div class="stat-card-meta">
                <i class="fas fa-check"></i> Paiements soldés
            </div>
        </a>

        <a href="{{ route('transaction.index') }}?payment_status=pending" class="stat-card stat-card--amber">
            <div class="stat-card-top">
                <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
                <span class="stat-card-badge">Attention</span>
            </div>
            <div class="stat-card-value">{{ $stats['pendingPayments'] ?? 0 }}</div>
            <div class="stat-card-label">Paiements en attente</div>
            <div class="stat-card-meta">
                <i class="fas fa-exclamation-circle"></i> Suivi requis
            </div>
        </a>

        <a href="{{ route('transaction.index') }}?payment_status=urgent&due_within=24h" class="stat-card stat-card--red">
            <div class="stat-card-top">
                <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <span class="stat-card-badge">Urgent</span>
            </div>
            <div class="stat-card-value">{{ $stats['urgentPayments'] ?? 0 }}</div>
            <div class="stat-card-label">Échéance &lt; 24h</div>
            <div class="stat-card-meta">
                <i class="fas fa-clock"></i> Action immédiate
            </div>
        </a>
    </div>

    <!-- ─── Arrivals & Departures ───────────────── -->
    <div class="db-panel anim-3">
        <div class="db-panel-header">
            <h2 class="db-panel-title">
                <span class="db-panel-title-dot" style="background:var(--blue-500)"></span>
                Arrivées & Départs
            </h2>
        </div>
        <div class="db-panel-body">
            <!-- 3 days -->
            <div class="dates-grid">
                <a href="{{ route('checkin.index') }}?date=today" class="date-card date-card--today">
                    <div class="date-card-head">
                        <span class="date-card-name">Aujourd'hui</span>
                        <span class="date-card-pill" style="background:var(--green-100);color:var(--green-800)">{{ now()->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label" style="color:var(--green-700)"><i class="fas fa-sign-in-alt fa-xs"></i> Arrivées</span>
                            <span class="date-card-row-val">{{ $stats['todayArrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label" style="color:var(--amber-600)"><i class="fas fa-sign-out-alt fa-xs"></i> Départs</span>
                            <span class="date-card-row-val">{{ $stats['todayDepartures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
                <a href="{{ route('checkin.index') }}?date=tomorrow" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">Demain</span>
                        <span class="date-card-pill">{{ now()->addDay()->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label"><i class="fas fa-sign-in-alt fa-xs"></i> Arrivées</span>
                            <span class="date-card-row-val">{{ $stats['tomorrowArrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label"><i class="fas fa-sign-out-alt fa-xs"></i> Départs</span>
                            <span class="date-card-row-val">{{ $stats['tomorrowDepartures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
                <a href="{{ route('checkin.index') }}?date=day+2" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">J+2</span>
                        <span class="date-card-pill">{{ now()->addDays(2)->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label"><i class="fas fa-sign-in-alt fa-xs"></i> Arrivées</span>
                            <span class="date-card-row-val">{{ $stats['day2Arrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label"><i class="fas fa-sign-out-alt fa-xs"></i> Départs</span>
                            <span class="date-card-row-val">{{ $stats['day2Departures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Rooms -->
            <div class="rooms-grid">
                <a href="{{ route('room.index') }}?status=available" class="room-stat-card">
                    <div class="room-stat-label">
                        Chambres libres
                        <span class="room-stat-badge" style="background:var(--green-100);color:var(--green-800)">Vacant</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['availableRooms'] ?? 0 }}</div>
                    <div class="room-stat-sub">sur {{ $stats['totalRooms'] ?? 0 }} chambres</div>
                </a>
                <a href="{{ route('room.index') }}?status=occupied" class="room-stat-card">
                    <div class="room-stat-label">
                        Chambres occupées
                        <span class="room-stat-badge" style="background:var(--blue-100);color:var(--blue-700)">Occupé</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['occupiedRooms'] ?? 0 }}</div>
                    <div class="room-stat-sub">en ce moment</div>
                </a>
                <a href="{{ route('reports.index') }}" class="room-stat-card">
                    <div class="room-stat-label">
                        Taux d'occupation
                        <span class="room-stat-badge" style="background:var(--violet-100);color:var(--violet-500)">Aujourd'hui</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['occupancyRate'] ?? 0 }}%</div>
                    <div class="occupancy-bar">
                        <div class="occupancy-bar-fill" style="width:{{ $stats['occupancyRate'] ?? 0 }}%"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- ─── Main Grid ──────────────────────────── -->
    <div class="db-main-grid">

        <!-- LEFT — Active guests table -->
        <div class="anim-4">
            <div class="db-card">
                <div class="db-card-header">
                    <div>
                        <h2 class="db-card-title">
                            <span class="db-card-title-dot" style="background:var(--green-500)"></span>
                            Clients actifs
                        </h2>
                        <div class="db-card-subtitle">{{ $transactions->count() }} client{{ $transactions->count() > 1 ? 's' : '' }} en ce moment</div>
                    </div>
                    <div class="db-card-actions">
                        <button class="btn-db btn-db-ghost" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <div class="db-dropdown" id="filter-dropdown">
                            <button class="btn-db btn-db-ghost" onclick="toggleDropdown('filter-dropdown')">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <div class="db-dropdown-menu db-filter-dropdown">
                                <a class="db-dropdown-item" href="?status=active"><i class="fas fa-user-check fa-xs"></i> Actifs seulement</a>
                                <a class="db-dropdown-item" href="?status=reservation"><i class="fas fa-calendar fa-xs"></i> Réservations</a>
                                <a class="db-dropdown-item" href="?payment_status=pending"><i class="fas fa-clock fa-xs"></i> Paiements en attente</a>
                                <div class="db-dropdown-divider"></div>
                                <a class="db-dropdown-item" href="?date_filter=today"><i class="fas fa-sun fa-xs"></i> Aujourd'hui</a>
                                <a class="db-dropdown-item" href="?date_filter=tomorrow"><i class="fas fa-arrow-right fa-xs"></i> Demain</a>
                                <a class="db-dropdown-item" href="?date_filter=this_week"><i class="fas fa-calendar-week fa-xs"></i> Cette semaine</a>
                                <a class="db-dropdown-item" href="?date_filter=all"><i class="fas fa-list fa-xs"></i> Toutes les dates</a>
                            </div>
                        </div>
                        <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn-db btn-db-primary">
                            <i class="fas fa-plus"></i> Nouveau client
                        </a>
                    </div>
                </div>

                @if($transactions->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="db-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Chambre</th>
                                <th>Dates</th>
                                <th>Solde</th>
                                <th style="text-align:right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            @php
                                $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                $isNew = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                $isOut = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                $initials = strtoupper(substr($transaction->customer->name, 0, 2));
                                $balanceFmt = number_format($balance, 0, ',', ' ') . ' CFA';
                                $totalFmt   = number_format($transaction->getTotalPrice(), 0, ',', ' ') . ' CFA';
                            @endphp
                            <tr class="{{ $isNew ? 'row-new' : '' }}">
                                <!-- Guest -->
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="guest-avatar">{{ $initials }}</div>
                                        <div>
                                            <div>
                                                <a href="{{ route('customer.show', $transaction->customer->id) }}" class="guest-name">
                                                    {{ $transaction->customer->name }}
                                                </a>
                                                @if($isNew)<span class="tag-new"><i class="fas fa-star fa-xs"></i> Nouveau</span>@endif
                                            </div>
                                            <div class="guest-sub">{{ $transaction->customer->phone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Room -->
                                <td>
                                    <a href="{{ route('room.show', $transaction->room->id) }}" class="room-link">
                                        N° {{ $transaction->room->number }}
                                    </a>
                                    <div class="room-type-label">{{ $transaction->room->type->name ?? 'Standard' }}</div>
                                </td>

                                <!-- Dates -->
                                <td>
                                    <div class="date-in">
                                        <i class="fas fa-sign-in-alt fa-xs"></i>
                                        {{ $transaction->check_in->format('d/m/Y') }}
                                    </div>
                                    <div class="date-out">
                                        <i class="fas fa-sign-out-alt fa-xs"></i>
                                        {{ $transaction->check_out->format('d/m/Y') }}
                                    </div>
                                    @if($isOut)
                                    <div class="checkout-today-tag">
                                        <i class="fas fa-exclamation-circle fa-xs"></i> Départ aujourd'hui
                                    </div>
                                    @endif
                                </td>

                                <!-- Balance -->
                                <td>
                                    @if($balance <= 0)
                                        <span class="balance-paid"><i class="fas fa-check fa-xs"></i> Soldé</span>
                                    @else
                                        <div class="balance-due-amount">{{ $balanceFmt }}</div>
                                        <div class="balance-total">Total : {{ $totalFmt }}</div>
                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" class="btn-pay-now">
                                            <i class="fas fa-credit-card fa-xs"></i> Encaisser
                                        </a>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                           class="btn-db-icon btn-db-icon-green" title="Paiement">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        <a href="{{ route('transaction.show', ['transaction' => $transaction->id]) }}"
                                           class="btn-db-icon" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="db-dropdown" id="row-dd-{{ $transaction->id }}">
                                            <button class="btn-db-icon" onclick="toggleDropdown('row-dd-{{ $transaction->id }}')" title="Plus">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="db-dropdown-menu">
                                                <a class="db-dropdown-item" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">
                                                    <i class="fas fa-edit fa-xs"></i> Modifier
                                                </a>
                                                <a class="db-dropdown-item" href="{{ route('transaction.invoice', ['transaction' => $transaction->id]) }}">
                                                    <i class="fas fa-file-invoice fa-xs"></i> Facture
                                                </a>
                                                @if($transaction->canBeCancelled())
                                                <div class="db-dropdown-divider"></div>
                                                <button class="db-dropdown-item db-dropdown-item-danger"
                                                        onclick="confirmCancel('{{ route('transaction.cancel', ['transaction' => $transaction->id]) }}', '{{ $transaction->customer->name }}')">
                                                    <i class="fas fa-times fa-xs"></i> Annuler
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($transactions, 'links') && $transactions->hasPages())
                <div class="db-card-footer">
                    {{ $transactions->links() }}
                </div>
                @endif

                @else
                <div class="db-empty">
                    <div class="db-empty-icon"><i class="fas fa-bed"></i></div>
                    <p style="font-size:.95rem;font-weight:600;color:var(--slate-700);margin-bottom:6px;">Aucun client actif</p>
                    <p style="font-size:.82rem;color:var(--slate-400);margin-bottom:18px;">Aucun client enregistré pour le moment</p>
                    <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn-db btn-db-primary">
                        <i class="fas fa-plus"></i> Ajouter un client
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- RIGHT Sidebar -->
        <div class="anim-5">

            <!-- Quick Check-in -->
            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--green-500)"></span>
                        Check-in rapide
                    </h2>
                </div>
                <div class="db-card-body">
                    <p style="font-size:.8rem;color:var(--slate-400);margin-bottom:12px;">
                        Vérifier une réservation existante
                    </p>
                    <form action="{{ route('checkin.search') }}" method="GET" class="qci-form">
                        <input type="text" class="qci-input" name="search"
                               placeholder="Nom, chambre, ID…"
                               value="{{ request('search') }}">
                        <button type="submit" class="qci-btn"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="qci-cta">
                        <a href="{{ route('checkin.index') }}" class="btn-qci-outline">
                            <i class="fas fa-list fa-xs"></i> Toutes les arrivées
                        </a>
                        <a href="{{ route('checkin.direct') }}" class="btn-qci-solid">
                            <i class="fas fa-user-plus fa-xs"></i> Check-in direct
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="db-card anim-6">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--amber-500)"></span>
                        Actions rapides
                    </h2>
                </div>
                <div class="db-card-body" style="padding:12px 14px;">
                    <div class="qa-list">
                        <a href="{{ route('room.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-bed"></i></span>
                            Gérer les chambres
                        </a>
                        <a href="{{ route('customer.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-users"></i></span>
                            Clients
                        </a>
                        <a href="{{ route('checkin.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-calendar-check"></i></span>
                            Dashboard check-in
                        </a>
                        <a href="{{ route('payments.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-money-bill-wave"></i></span>
                            Paiements
                        </a>
                        <a href="{{ route('reports.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-chart-bar"></i></span>
                            Rapports
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->role === 'Super')
                        <a href="{{ route('cashier.dashboard') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-cash-register"></i></span>
                            Caisse
                        </a>
                        @endif
                        <a href="{{ route('frontend.home') }}" target="_blank" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-external-link-alt"></i></span>
                            Visiter le site
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--red-500)"></span>
                        Statut système
                    </h2>
                </div>
                <div class="db-card-body">
                    <div class="status-list">
                        <div class="status-row">
                            <span class="status-key">Dernière mise à jour</span>
                            <span class="status-badge status-neutral" id="last-updated">{{ now()->format('H:i:s') }}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">Sessions actives</span>
                            <span class="status-badge status-info">1</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">Base de données</span>
                            <span class="status-badge status-online">En ligne</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">Mémoire</span>
                            <span class="status-badge status-normal">Normal</span>
                        </div>
                    </div>
                    <button class="btn-refresh-full" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt fa-xs"></i> Actualiser le dashboard
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ── Clock ───────────────────────────────────── */
function tickClock() {
    const now = new Date();
    const el  = document.getElementById('db-clock');
    if (el) {
        el.textContent = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }
    const lu = document.getElementById('last-updated');
    if (lu) {
        lu.textContent = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
}
setInterval(tickClock, 1000);

/* ── Auto-refresh stats ──────────────────────── */
setInterval(() => {
    fetch('{{ route("dashboard.stats") }}')
        .then(r => r.json())
        .then(d => { if (!d.success) return; /* update values here if needed */ })
        .catch(() => {});
}, 30000);

/* ── Custom dropdown ─────────────────────────── */
function toggleDropdown(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const menu = el.querySelector('.db-dropdown-menu');
    if (!menu) return;
    const isOpen = menu.classList.contains('open');
    // Close all
    document.querySelectorAll('.db-dropdown-menu.open').forEach(m => m.classList.remove('open'));
    if (!isOpen) menu.classList.add('open');
}
document.addEventListener('click', function (e) {
    if (!e.target.closest('.db-dropdown')) {
        document.querySelectorAll('.db-dropdown-menu.open').forEach(m => m.classList.remove('open'));
    }
});

/* ── Refresh ─────────────────────────────────── */
function refreshDashboard() {
    const btns = document.querySelectorAll('[onclick="refreshDashboard()"]');
    btns.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ...'; });
    setTimeout(() => location.reload(), 800);
}

/* ── Confirm cancel ──────────────────────────── */
function confirmCancel(url, name) {
    Swal.fire({
        title: 'Annuler la réservation ?',
        html: `Vous allez annuler la réservation de <strong>${name}</strong>.<br>Cette action est irréversible.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, annuler',
        cancelButtonText: 'Conserver',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#059669',
        borderRadius: '12px',
    }).then(r => {
        if (!r.isConfirmed) return;
        const f = document.createElement('form');
        f.method = 'POST'; f.action = url; f.style.display = 'none';
        f.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(f);
        f.submit();
    });
}
window.confirmCancel = confirmCancel;
</script>
@endsection