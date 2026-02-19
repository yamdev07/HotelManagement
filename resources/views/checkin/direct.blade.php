@extends('template.master')
@section('title', 'Check-in Direct')
@section('content')

<style>
/* ══════════════════════════════════════════════
   VARIABLES (inchangées)
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

    --blue-600:  #2563eb;
    --blue-100:  #dbeafe;
    --blue-50:   #eff6ff;

    --amber-500: #f59e0b;
    --amber-100: #fef3c7;
    --amber-50:  #fffbeb;

    --red-500:   #ef4444;
    --red-100:   #fee2e2;

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

    --shadow-sm:  0 1px 3px rgba(0,0,0,.07);
    --shadow-md:  0 4px 14px rgba(0,0,0,.09);
    --shadow-lg:  0 10px 32px rgba(0,0,0,.11);
    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  18px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
}

/* ── Page ───────────────────────────────────── */
.dc-page {
    padding: 28px 28px 56px;
    background: var(--slate-50);
    min-height: 100vh;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeUp .3s ease both; }
.anim-2 { animation: fadeUp .3s .07s ease both; }
.anim-3 { animation: fadeUp .3s .14s ease both; }

/* ── Breadcrumb ─────────────────────────────── */
.dc-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .78rem; color: var(--slate-400);
    margin-bottom: 18px; flex-wrap: wrap;
}
.dc-breadcrumb a { color: var(--slate-400); text-decoration: none; transition: var(--transition); }
.dc-breadcrumb a:hover { color: var(--green-600); }
.dc-breadcrumb .sep { color: var(--slate-300); }
.dc-breadcrumb .current { color: var(--slate-600); font-weight: 500; }

/* ── Header ─────────────────────────────────── */
.dc-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 14px; margin-bottom: 28px;
}
.dc-header-title {
    display: flex; align-items: center; gap: 12px;
    font-size: 1.45rem; font-weight: 700;
    color: var(--slate-900); margin: 0; line-height: 1.2;
}
.dc-header-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(5,150,105,.3);
}
.dc-header-subtitle {
    color: var(--slate-500); font-size: .875rem;
    margin: 6px 0 0 56px;
}
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px; border-radius: var(--radius-sm);
    font-size: .84rem; font-weight: 500;
    color: var(--slate-600); background: white;
    border: 1.5px solid var(--slate-200);
    text-decoration: none; transition: var(--transition);
    box-shadow: var(--shadow-sm);
}
.btn-back:hover {
    background: var(--slate-50);
    border-color: var(--slate-300);
    color: var(--slate-900);
    transform: translateY(-1px);
    text-decoration: none;
}

/* ══════════════════════════════════════════════
   STEPPER
══════════════════════════════════════════════ */
.dc-stepper {
    display: flex; align-items: center; justify-content: center;
    position: relative; margin-bottom: 32px;
    padding: 24px 32px;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
}

.stepper-track {
    position: absolute;
    top: 50%; left: 80px; right: 80px;
    height: 2px;
    background: var(--slate-200);
    z-index: 1;
    transform: translateY(-50%);
}
.stepper-track-progress {
    height: 100%;
    background: linear-gradient(90deg, var(--green-500), var(--green-400));
    transition: width .4s cubic-bezier(.4,0,.2,1);
    border-radius: 2px;
}

.stepper-steps {
    display: flex; align-items: center;
    justify-content: space-between;
    width: 100%; max-width: 560px;
    position: relative; z-index: 2;
    gap: 0;
}

.step-item {
    display: flex; flex-direction: column; align-items: center;
    gap: 8px; flex: 1;
}
.step-bubble {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: var(--slate-100);
    border: 2.5px solid var(--slate-200);
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700;
    color: var(--slate-400);
    transition: var(--transition);
    position: relative;
    z-index: 3;
    box-shadow: 0 0 0 4px white;
}
.step-item.active .step-bubble {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    border-color: var(--green-500);
    color: white;
    box-shadow: 0 0 0 4px white, 0 4px 14px rgba(5,150,105,.35);
    transform: scale(1.08);
}
.step-item.completed .step-bubble {
    background: var(--green-500);
    border-color: var(--green-400);
    color: white;
    box-shadow: 0 0 0 4px white;
}
.step-label {
    font-size: .72rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--slate-400); white-space: nowrap;
    transition: var(--transition);
}
.step-item.active .step-label { color: var(--green-700); }
.step-item.completed .step-label { color: var(--green-600); }

/* ══════════════════════════════════════════════
   FORM CARD
══════════════════════════════════════════════ */
.dc-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-200);
    overflow: hidden;
}
.dc-card-header {
    display: flex; align-items: center; gap: 11px;
    padding: 20px 28px;
    border-bottom: 1px solid var(--slate-100);
    background: var(--slate-50);
}
.dc-card-header-icon {
    width: 36px; height: 36px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}
.dc-card-header-title {
    font-size: 1rem; font-weight: 700; color: var(--slate-800);
    margin: 0;
}
.dc-card-body { padding: 28px; }

/* ── Form tabs ──────────────────────────────── */
.dc-tab { display: none; }
.dc-tab.active { display: block; animation: fadeUp .25s ease; }

/* ── Form fields ────────────────────────────── */
.form-grid-2 {
    display: grid; grid-template-columns: 1fr 1fr; gap: 18px;
}
@media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: .8rem; font-weight: 600;
    color: var(--slate-700); text-transform: uppercase;
    letter-spacing: .4px;
}
.form-label .req { color: var(--red-500); margin-left: 2px; }
.form-control-dc {
    height: 42px; padding: 0 14px;
    border: 1.5px solid var(--slate-200);
    border-radius: var(--radius-sm);
    font-size: .875rem; color: var(--slate-900);
    background: white;
    transition: var(--transition);
    outline: none;
    width: 100%;
}
.form-control-dc:focus {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(16,185,129,.12);
}
.form-control-dc.error { border-color: var(--red-500); box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
.form-control-dc-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394a3b8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; cursor: pointer; }
.form-invalid { font-size: .75rem; color: var(--red-500); margin-top: 3px; }

/* ── Search customer ────────────────────────── */
.search-box {
    background: var(--blue-50);
    border: 1.5px solid var(--blue-100);
    border-radius: var(--radius-md);
    padding: 18px 20px;
    margin-bottom: 24px;
}
.search-box-title {
    font-size: .82rem; font-weight: 700;
    color: var(--blue-600); text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 12px;
    display: flex; align-items: center; gap: 7px;
}
.search-row {
    display: flex; gap: 10px;
}
.search-row .form-control-dc { flex: 1; }

.btn-search {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0 18px; height: 42px;
    background: var(--blue-600); color: white;
    border: none; border-radius: var(--radius-sm);
    font-size: .84rem; font-weight: 500;
    cursor: pointer; transition: var(--transition);
    white-space: nowrap; flex-shrink: 0;
}
.btn-search:hover { background: #1d4ed8; transform: translateY(-1px); }

.customer-results {
    margin-top: 10px;
    border: 1.5px solid var(--blue-100);
    border-radius: var(--radius-sm);
    overflow: hidden;
    max-height: 260px; overflow-y: auto;
}
.customer-result-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 11px 16px;
    border-bottom: 1px solid var(--slate-100);
    cursor: pointer; transition: var(--transition);
    background: white;
}
.customer-result-item:last-child { border-bottom: none; }
.customer-result-item:hover { background: var(--blue-50); }
.cri-name { font-size: .875rem; font-weight: 600; color: var(--slate-900); }
.cri-meta { font-size: .75rem; color: var(--slate-400); margin-top: 2px; }
.cri-badge {
    font-size: .68rem; font-weight: 700;
    background: var(--slate-100); color: var(--slate-600);
    padding: 2px 8px; border-radius: 5px; flex-shrink: 0;
}

/* ── Section divider ────────────────────────── */
.dc-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 20px 0;
}
.dc-divider-line { flex: 1; height: 1px; background: var(--slate-200); }
.dc-divider-label {
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--slate-400);
}

/* ── Night summary ──────────────────────────── */
.nights-summary {
    display: flex; align-items: center; gap: 16px;
    background: var(--green-50);
    border: 1.5px solid var(--green-100);
    border-radius: var(--radius-md);
    padding: 16px 22px;
    margin-top: 18px;
}
.nights-big {
    text-align: center; flex-shrink: 0;
    padding-right: 16px;
    border-right: 2px solid var(--green-200);
}
.nights-big-val {
    font-size: 2.2rem; font-weight: 800;
    color: var(--green-800); line-height: 1;
}
.nights-big-label {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--green-600); margin-top: 2px;
}
.nights-dates { flex: 1; }
.nights-route {
    display: flex; align-items: center; gap: 10px;
    font-size: .88rem; font-weight: 600; color: var(--slate-700);
    flex-wrap: wrap;
}
.nights-arrow { color: var(--green-500); font-size: .8rem; }

/* ── Room grid ──────────────────────────────── */
.rooms-filters {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
    margin-bottom: 20px;
}
@media (max-width: 640px) { .rooms-filters { grid-template-columns: 1fr; } }

.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
}

.room-card {
    border: 2px solid var(--slate-200);
    border-radius: var(--radius-md);
    overflow: hidden;
    cursor: pointer;
    transition: var(--transition);
    background: white;
    position: relative;
}
.room-card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
}
.room-card.selected {
    border-color: var(--green-500);
    box-shadow: 0 0 0 3px rgba(16,185,129,.2), var(--shadow-md);
}
.room-card.selected::after {
    content: '';
    position: absolute; top: 10px; right: 10px;
    width: 24px; height: 24px;
    background: var(--green-500); border-radius: 50%;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='9' viewBox='0 0 12 9' fill='none'%3E%3Cpath d='M1 4L4.5 7.5L11 1' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    box-shadow: 0 2px 6px rgba(16,185,129,.4);
}
.room-img {
    height: 140px;
    background: var(--slate-100);
    display: flex; align-items: center; justify-content: center;
    color: var(--slate-300); font-size: 2.5rem;
    overflow: hidden;
}
.room-img img { width: 100%; height: 100%; object-fit: cover; }
.room-body { padding: 14px; }
.room-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
.room-number { font-size: .95rem; font-weight: 700; color: var(--slate-900); }
.room-price { font-size: .95rem; font-weight: 700; color: var(--green-700); }
.room-type { font-size: .75rem; color: var(--slate-400); margin-bottom: 10px; }
.room-features { display: flex; gap: 6px; flex-wrap: wrap; }
.room-feat-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .7rem; font-weight: 600;
    background: var(--slate-100); color: var(--slate-600);
    padding: 2px 8px; border-radius: 5px;
}

/* ── Selected room info ─────────────────────── */
.selected-room-box {
    background: var(--green-50);
    border: 2px solid var(--green-200);
    border-radius: var(--radius-md);
    padding: 16px 20px;
    margin-top: 20px;
    display: none;
    animation: fadeUp .2s ease;
}
.selected-room-box-title {
    display: flex; align-items: center; gap: 8px;
    font-size: .82rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .4px;
    color: var(--green-700); margin-bottom: 12px;
}
.selected-room-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
}
@media (max-width: 480px) { .selected-room-grid { grid-template-columns: 1fr; } }
.srg-item {}
.srg-label { font-size: .7rem; color: var(--slate-400); font-weight: 600; text-transform: uppercase; letter-spacing: .3px; }
.srg-value { font-size: .875rem; font-weight: 600; color: var(--slate-800); margin-top: 2px; }
.srg-value-price { color: var(--green-700); font-size: .95rem; font-weight: 700; }

/* ── Summary boxes ──────────────────────────── */
.summary-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    margin-bottom: 16px;
}
@media (max-width: 640px) { .summary-grid { grid-template-columns: 1fr; } }

.summary-box {
    background: var(--slate-50);
    border: 1px solid var(--slate-200);
    border-radius: var(--radius-md);
    padding: 16px 18px;
}
.summary-box-title {
    display: flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .4px;
    color: var(--slate-500); margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--slate-200);
}
.summary-row {
    display: flex; justify-content: space-between; align-items: baseline;
    gap: 8px; margin-bottom: 7px;
    font-size: .84rem;
}
.summary-row:last-child { margin-bottom: 0; }
.summary-key { color: var(--slate-500); }
.summary-val { color: var(--slate-900); font-weight: 600; text-align: right; }

.deposit-box {
    background: var(--amber-50);
    border: 1.5px solid var(--amber-100);
    border-radius: var(--radius-md);
    padding: 16px 20px;
    margin-bottom: 16px;
}
.deposit-box-title {
    font-size: .78rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .4px;
    color: #92400e; margin-bottom: 12px;
    display: flex; align-items: center; gap: 7px;
}
.deposit-check-row {
    display: flex; align-items: flex-start; gap: 10px; cursor: pointer;
}
.deposit-check-row input[type="checkbox"] {
    margin-top: 2px; accent-color: var(--amber-500); flex-shrink: 0;
    width: 16px; height: 16px; cursor: pointer;
}
.deposit-check-label { font-size: .875rem; color: var(--slate-700); cursor: pointer; }
.deposit-amount-row {
    display: flex; align-items: center; gap: 8px;
    margin-top: 10px; padding-top: 10px;
    border-top: 1px solid var(--amber-100);
    font-size: .875rem; font-weight: 600; color: #92400e;
    display: none;
}

.warning-box {
    display: flex; gap: 12px; align-items: flex-start;
    background: #fffbeb; border: 1.5px solid #fde68a;
    border-radius: var(--radius-md);
    padding: 14px 18px; margin-bottom: 20px;
    font-size: .84rem; color: #78350f;
}
.warning-box i { color: var(--amber-500); margin-top: 2px; flex-shrink: 0; }

/* ── Navigation buttons ─────────────────────── */
.dc-step-nav {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 24px; margin-top: 20px;
    border-top: 1px solid var(--slate-100);
}
.btn-dc-prev {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: var(--radius-sm);
    font-size: .85rem; font-weight: 500;
    color: var(--slate-600); background: white;
    border: 1.5px solid var(--slate-200);
    cursor: pointer; transition: var(--transition); text-decoration: none;
}
.btn-dc-prev:hover { background: var(--slate-50); border-color: var(--slate-300); color: var(--slate-900); }
.btn-dc-next {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 24px; border-radius: var(--radius-sm);
    font-size: .875rem; font-weight: 600;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    color: white; border: none; cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(5,150,105,.3);
}
.btn-dc-next:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(5,150,105,.38); }
.btn-dc-next:disabled {
    opacity: .5; cursor: not-allowed; transform: none;
    box-shadow: none;
}
.btn-dc-submit {
    background: linear-gradient(135deg, #065f46, #059669);
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 28px; border-radius: var(--radius-sm);
    font-size: .9rem; font-weight: 700;
    color: white; border: none; cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 14px rgba(5,150,105,.35);
}
.btn-dc-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(5,150,105,.4); }
.btn-dc-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

/* ── Info note ──────────────────────────────── */
.dc-info {
    display: flex; gap: 10px; align-items: flex-start;
    background: var(--blue-50); border: 1px solid var(--blue-100);
    border-radius: var(--radius-sm); padding: 12px 16px;
    font-size: .82rem; color: #1e40af; margin-bottom: 18px;
}
.dc-info i { color: var(--blue-600); flex-shrink: 0; margin-top: 1px; }
</style>

<div class="dc-page">

    <!-- Breadcrumb -->
    <nav class="dc-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('checkin.index') }}">Check-in</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Check-in Direct</span>
    </nav>

    <!-- Header -->
    <div class="dc-header anim-2">
        <div>
            <h1 class="dc-header-title">
                <span class="dc-header-icon"><i class="fas fa-user-plus"></i></span>
                Check-in Direct
            </h1>
            <p class="dc-header-subtitle">Enregistrement sans réservation préalable</p>
        </div>
        <a href="{{ route('checkin.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- ═══ STEPPER ═══ -->
    <div class="dc-stepper anim-3">
        <div class="stepper-track">
            <div class="stepper-track-progress" id="stepper-progress" style="width:0%"></div>
        </div>
        <div class="stepper-steps">
            <div class="step-item active" id="step-1">
                <div class="step-bubble">1</div>
                <div class="step-label">Client</div>
            </div>
            <div class="step-item" id="step-2">
                <div class="step-bubble">2</div>
                <div class="step-label">Dates</div>
            </div>
            <div class="step-item" id="step-3">
                <div class="step-bubble">3</div>
                <div class="step-label">Chambre</div>
            </div>
            <div class="step-item" id="step-4">
                <div class="step-bubble">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('checkin.process-direct-checkin') }}" id="direct-checkin-form">
        @csrf
        <input type="hidden" name="checkin_method" value="direct">

        <!-- ════════════════ ÉTAPE 1 : CLIENT ════════════════ -->
        <div class="dc-tab active" id="tab-1">
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-header-icon" style="background:var(--blue-50);color:var(--blue-600)">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="dc-card-header-title">Informations Client</h2>
                </div>
                <div class="dc-card-body">

                    <!-- Recherche client existant -->
                    <div class="search-box">
                        <div class="search-box-title">
                            <i class="fas fa-search"></i> Rechercher un client existant
                        </div>
                        <div class="search-row">
                            <input type="text" class="form-control-dc"
                                   id="search-customer"
                                   placeholder="Nom, téléphone ou email…"
                                   autocomplete="off">
                            <button type="button" class="btn-search" onclick="searchCustomers()">
                                <i class="fas fa-search"></i> Chercher
                            </button>
                        </div>
                        <div class="customer-results" id="customer-results" style="display:none;"></div>
                    </div>

                    <div class="dc-divider">
                        <div class="dc-divider-line"></div>
                        <span class="dc-divider-label">ou créer un nouveau client</span>
                        <div class="dc-divider-line"></div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Nom complet <span class="req">*</span></label>
                            <input type="text" name="customer_name" id="name" class="form-control-dc @error('customer_name') error @enderror"
                                   value="{{ old('customer_name') }}" placeholder="Prénom Nom" required>
                            @error('customer_name')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Téléphone <span class="req">*</span></label>
                            <input type="text" name="customer_phone" id="phone" class="form-control-dc @error('customer_phone') error @enderror"
                                   value="{{ old('customer_phone') }}" placeholder="+226 xx xx xx xx" required>
                            @error('customer_phone')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="customer_email" id="email" class="form-control-dc @error('customer_email') error @enderror"
                                   value="{{ old('customer_email') }}" placeholder="email@exemple.com">
                            @error('customer_email')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="dc-step-nav">
                        <div></div>
                        <button type="button" class="btn-dc-next" onclick="nextStep(2)">
                            Continuer <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════ ÉTAPE 2 : DATES ════════════════ -->
        <div class="dc-tab" id="tab-2">
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-header-icon" style="background:var(--blue-50);color:var(--blue-600)">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h2 class="dc-card-header-title">Dates du Séjour</h2>
                </div>
                <div class="dc-card-body">
                    <div class="form-grid-2" style="margin-bottom:18px">
                        <div class="form-group">
                            <label class="form-label">Date d'arrivée <span class="req">*</span></label>
                            <input type="date" name="check_in" id="check_in"
                                   class="form-control-dc @error('check_in') error @enderror"
                                   value="{{ old('check_in', date('Y-m-d')) }}" required>
                            @error('check_in')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de départ <span class="req">*</span></label>
                            <input type="date" name="check_out" id="check_out"
                                   class="form-control-dc @error('check_out') error @enderror"
                                   value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" required>
                            @error('check_out')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nombre de personnes <span class="req">*</span></label>
                            <input type="number" name="person_count" id="person_count"
                                   class="form-control-dc @error('person_count') error @enderror"
                                   value="{{ old('person_count', 1) }}" min="1" max="10" required>
                            @error('person_count')<div class="form-invalid">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Résumé nuits -->
                    <div class="nights-summary">
                        <div class="nights-big">
                            <div class="nights-big-val" id="nights-count">0</div>
                            <div class="nights-big-label">Nuits</div>
                        </div>
                        <div class="nights-dates">
                            <div class="nights-route">
                                <span id="arrival-date" style="color:var(--slate-800)">—</span>
                                <span class="nights-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
                                <span id="departure-date" style="color:var(--slate-800)">—</span>
                            </div>
                            <div style="font-size:.75rem;color:var(--slate-400);margin-top:5px;">
                                <i class="fas fa-info-circle me-1"></i>
                                Arrivée – Départ du séjour
                            </div>
                        </div>
                    </div>

                    <div class="dc-step-nav">
                        <button type="button" class="btn-dc-prev" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="button" class="btn-dc-next" onclick="nextStep(3)">
                            Continuer <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════ ÉTAPE 3 : CHAMBRE ════════════════ -->
        <div class="dc-tab" id="tab-3">
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-header-icon" style="background:var(--green-50);color:var(--green-700)">
                        <i class="fas fa-bed"></i>
                    </div>
                    <h2 class="dc-card-header-title">Sélection de la Chambre</h2>
                </div>
                <div class="dc-card-body">
                    <div class="dc-info">
                        <i class="fas fa-info-circle"></i>
                        Cliquez sur une chambre pour la sélectionner. Les chambres affichées sont disponibles pour vos dates.
                    </div>

                    <!-- Filtres -->
                    <div class="rooms-filters">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select class="form-control-dc form-control-dc-select" id="filter-type" onchange="filterRooms()">
                                <option value="">Tous les types</option>
                                @php $roomTypes = \App\Models\Type::pluck('name', 'id'); @endphp
                                @foreach($roomTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Capacité min.</label>
                            <select class="form-control-dc form-control-dc-select" id="filter-capacity" onchange="filterRooms()">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4+ personnes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prix max.</label>
                            <select class="form-control-dc form-control-dc-select" id="filter-price" onchange="filterRooms()">
                                <option value="">Tous les prix</option>
                                <option value="50000">50 000 CFA</option>
                                <option value="100000">100 000 CFA</option>
                                <option value="150000">150 000 CFA</option>
                                <option value="200000">200 000 CFA</option>
                            </select>
                        </div>
                    </div>

                    <!-- Grille des chambres -->
                    @if($availableRooms->isEmpty())
                    <div style="text-align:center;padding:48px 24px;">
                        <div style="width:72px;height:72px;background:var(--slate-100);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--slate-300);margin:0 auto 16px;">
                            <i class="fas fa-bed"></i>
                        </div>
                        <p style="font-size:.95rem;font-weight:600;color:var(--slate-700)">Aucune chambre disponible</p>
                        <p style="font-size:.82rem;color:var(--slate-400)">Modifiez vos dates pour trouver des disponibilités</p>
                        <button type="button" class="btn-dc-prev" style="margin-top:16px;" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Modifier les dates
                        </button>
                    </div>
                    @else
                    <div class="room-grid" id="rooms-grid">
                        @foreach($availableRooms as $room)
                        <div class="room-card"
                             data-type="{{ $room->type_id }}"
                             data-capacity="{{ $room->capacity }}"
                             data-price="{{ $room->price }}"
                             onclick="selectRoom({{ $room->id }}, {{ $room->price }}, '{{ $room->number }}', '{{ $room->type->name ?? 'N/A' }}', {{ $room->capacity }})"
                             id="room-card-{{ $room->id }}">
                            <div class="room-img">
                                @if($room->first_image_url && $room->first_image_url != asset('img/default/default-room.png'))
                                    <img src="{{ $room->first_image_url }}" alt="Ch. {{ $room->number }}">
                                @else
                                    <i class="fas fa-bed"></i>
                                @endif
                            </div>
                            <div class="room-body">
                                <div class="room-header">
                                    <div class="room-number">N° {{ $room->number }}</div>
                                    <div class="room-price">{{ Helper::formatCFA($room->price) }}<span style="font-size:.68rem;font-weight:500;color:var(--slate-400)">/nuit</span></div>
                                </div>
                                <div class="room-type">{{ $room->type->name ?? 'N/A' }}</div>
                                <div class="room-features">
                                    <span class="room-feat-tag"><i class="fas fa-user"></i> {{ $room->capacity }}</span>
                                    <span class="room-feat-tag"><i class="fas fa-bath"></i></span>
                                    @if($room->facilities->where('name', 'Wifi')->first())
                                        <span class="room-feat-tag"><i class="fas fa-wifi"></i></span>
                                    @endif
                                    @if($room->facilities->where('name', 'Climatisation')->first())
                                        <span class="room-feat-tag"><i class="fas fa-snowflake"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Chambre sélectionnée -->
                    <div class="selected-room-box" id="selected-room-box">
                        <div class="selected-room-box-title">
                            <i class="fas fa-check-circle" style="color:var(--green-600)"></i>
                            Chambre sélectionnée
                        </div>
                        <div class="selected-room-grid">
                            <div class="srg-item">
                                <div class="srg-label">Chambre</div>
                                <div class="srg-value" id="sel-room-num">—</div>
                            </div>
                            <div class="srg-item">
                                <div class="srg-label">Type</div>
                                <div class="srg-value" id="sel-room-type">—</div>
                            </div>
                            <div class="srg-item">
                                <div class="srg-label">Capacité</div>
                                <div class="srg-value" id="sel-room-cap">—</div>
                            </div>
                            <div class="srg-item">
                                <div class="srg-label">Total séjour</div>
                                <div class="srg-value srg-value-price" id="sel-room-total">—</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="room_id" id="selected_room_id">

                    <div class="dc-step-nav">
                        <button type="button" class="btn-dc-prev" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="button" class="btn-dc-next" id="btn-next-step4" onclick="nextStep(4)" disabled>
                            Continuer <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ════════════════ ÉTAPE 4 : CONFIRMATION ════════════════ -->
        <div class="dc-tab" id="tab-4">
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-header-icon" style="background:var(--green-50);color:var(--green-700)">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h2 class="dc-card-header-title">Confirmation du Check-in</h2>
                </div>
                <div class="dc-card-body">

                    <div class="summary-grid">
                        <div class="summary-box">
                            <div class="summary-box-title" style="color:var(--blue-600)">
                                <i class="fas fa-user"></i> Client
                            </div>
                            <div class="summary-row"><span class="summary-key">Nom</span><span class="summary-val" id="s-name">—</span></div>
                            <div class="summary-row"><span class="summary-key">Téléphone</span><span class="summary-val" id="s-phone">—</span></div>
                            <div class="summary-row"><span class="summary-key">Email</span><span class="summary-val" id="s-email">—</span></div>
                        </div>
                        <div class="summary-box">
                            <div class="summary-box-title" style="color:var(--green-700)">
                                <i class="fas fa-bed"></i> Chambre
                            </div>
                            <div class="summary-row"><span class="summary-key">Numéro</span><span class="summary-val" id="s-room">—</span></div>
                            <div class="summary-row"><span class="summary-key">Type</span><span class="summary-val" id="s-room-type">—</span></div>
                            <div class="summary-row"><span class="summary-key">Capacité</span><span class="summary-val" id="s-room-cap">—</span></div>
                            <div class="summary-row"><span class="summary-key">Prix/nuit</span><span class="summary-val" id="s-price-night" style="color:var(--green-700)">—</span></div>
                        </div>
                        <div class="summary-box">
                            <div class="summary-box-title" style="color:var(--blue-600)">
                                <i class="fas fa-calendar-alt"></i> Séjour
                            </div>
                            <div class="summary-row"><span class="summary-key">Arrivée</span><span class="summary-val" id="s-checkin">—</span></div>
                            <div class="summary-row"><span class="summary-key">Départ</span><span class="summary-val" id="s-checkout">—</span></div>
                            <div class="summary-row"><span class="summary-key">Durée</span><span class="summary-val" id="s-nights">—</span></div>
                        </div>
                        <div class="summary-box">
                            <div class="summary-box-title" style="color:var(--green-700)">
                                <i class="fas fa-money-bill-wave"></i> Financier
                            </div>
                            <div class="summary-row"><span class="summary-key">Prix/nuit</span><span class="summary-val" id="s-price2">—</span></div>
                            <div class="summary-row"><span class="summary-key">Total</span><span class="summary-val" id="s-total" style="color:var(--green-700);font-size:1rem">—</span></div>
                            <div class="summary-row"><span class="summary-key">Méthode</span><span class="summary-val">Direct</span></div>
                        </div>
                    </div>

                    <!-- Acompte -->
                    <div class="deposit-box">
                        <div class="deposit-box-title">
                            <i class="fas fa-credit-card"></i> Acompte
                        </div>
                        <label class="deposit-check-row">
                            <input type="checkbox" id="pay-deposit" name="pay_deposit" value="1">
                            <span class="deposit-check-label">Prendre un acompte de 30% à l'arrivée</span>
                        </label>
                        <div class="deposit-amount-row" id="deposit-amount-row">
                            <i class="fas fa-coins"></i>
                            Montant de l'acompte :
                            <strong id="deposit-amount-val">—</strong>
                        </div>
                    </div>

                    <!-- Demandes spéciales -->
                    <div class="form-group" style="margin-bottom:18px">
                        <label class="form-label">Demandes spéciales / Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="form-control-dc" style="height:auto;padding:10px 14px;resize:vertical;"
                                  placeholder="Préférences ou besoins particuliers du client…"></textarea>
                    </div>

                    <!-- Warning -->
                    <div class="warning-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>
                            <strong>Important :</strong> Cette action créera une nouvelle réservation et enregistrera 
                            immédiatement le client dans la chambre. Le statut sera directement <strong>actif</strong>.
                        </span>
                    </div>

                    <div class="dc-step-nav">
                        <button type="button" class="btn-dc-prev" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="submit" class="btn-dc-submit" id="confirm-checkin">
                            <i class="fas fa-check-circle"></i> Confirmer le Check-in
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@section('footer')
<script>
let currentStep = 1;
let selectedRoomId = null;
let selectedRoomPrice = null;
let selectedRoomNumber = null;
let selectedRoomType = null;
let selectedRoomCap = null;
let nightsCount = 0;
let totalPrice = 0;

/* ── Stepper progress widths ─────────────────── */
const progressMap = { 1: '0%', 2: '33%', 3: '66%', 4: '100%' };

function updateStepper(step) {
    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById(`step-${i}`);
        const bubble = el.querySelector('.step-bubble');
        el.classList.remove('active', 'completed');
        bubble.innerHTML = i;
        if (i < step) {
            el.classList.add('completed');
            bubble.innerHTML = '<i class="fas fa-check" style="font-size:.7rem"></i>';
        } else if (i === step) {
            el.classList.add('active');
        }
    }
    document.getElementById('stepper-progress').style.width = progressMap[step] || '0%';
}

function showTab(n) {
    document.querySelectorAll('.dc-tab').forEach(t => t.classList.remove('active'));
    document.getElementById(`tab-${n}`).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextStep(next) {
    if (currentStep === 1) {
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        if (!name || !phone) {
            showAlert('Veuillez remplir tous les champs obligatoires.', 'error');
            return;
        }
    }
    if (currentStep === 2) {
        const ci = new Date(document.getElementById('check_in').value);
        const co = new Date(document.getElementById('check_out').value);
        if (isNaN(ci) || isNaN(co) || co <= ci) {
            showAlert('La date de départ doit être après la date d\'arrivée.', 'error');
            return;
        }
        const persons = parseInt(document.getElementById('person_count').value);
        if (!persons || persons < 1) {
            showAlert('Au moins 1 personne est requise.', 'error');
            return;
        }
        const diff = co - ci;
        nightsCount = Math.round(diff / 86400000);
        if (nightsCount < 1) {
            showAlert('La durée minimale du séjour est 1 nuit.', 'error');
            return;
        }
        calcNights();
        document.getElementById('filter-capacity').value = persons;
        filterRooms();
    }
    if (currentStep === 3) {
        if (!selectedRoomId) {
            showAlert('Veuillez sélectionner une chambre.', 'error');
            return;
        }
        buildSummary();
    }
    currentStep = next;
    updateStepper(currentStep);
    showTab(currentStep);
}

function prevStep(prev) {
    currentStep = prev;
    updateStepper(currentStep);
    showTab(currentStep);
}

/* ── Nights calc ─────────────────────────────── */
function calcNights() {
    const ci = new Date(document.getElementById('check_in').value);
    const co = new Date(document.getElementById('check_out').value);
    if (isNaN(ci) || isNaN(co) || co <= ci) {
        document.getElementById('nights-count').textContent = '0';
        document.getElementById('arrival-date').textContent = '—';
        document.getElementById('departure-date').textContent = '—';
        return;
    }
    const n = Math.round((co - ci) / 86400000);
    nightsCount = n;
    document.getElementById('nights-count').textContent = n;
    document.getElementById('arrival-date').textContent = ci.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' });
    document.getElementById('departure-date').textContent = co.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' });
}

/* ── Room selection ──────────────────────────── */
function selectRoom(id, price, number, type, cap) {
    document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
    document.getElementById(`room-card-${id}`).classList.add('selected');

    selectedRoomId = id;
    selectedRoomPrice = price;
    selectedRoomNumber = number;
    selectedRoomType = type;
    selectedRoomCap = cap;
    totalPrice = price * nightsCount;

    document.getElementById('selected_room_id').value = id;
    document.getElementById('sel-room-num').textContent = `Chambre ${number}`;
    document.getElementById('sel-room-type').textContent = type;
    document.getElementById('sel-room-cap').textContent = `${cap} personne${cap > 1 ? 's' : ''}`;
    document.getElementById('sel-room-total').textContent = formatCFA(totalPrice);

    const box = document.getElementById('selected-room-box');
    box.style.display = 'block';

    document.getElementById('btn-next-step4').disabled = false;
}

/* ── Filters ─────────────────────────────────── */
function filterRooms() {
    const type = document.getElementById('filter-type').value;
    const cap = parseInt(document.getElementById('filter-capacity').value);
    const price = document.getElementById('filter-price').value;

    document.querySelectorAll('.room-card').forEach(card => {
        let show = true;
        if (type && card.dataset.type !== type) show = false;
        if (cap && parseInt(card.dataset.capacity) < cap) show = false;
        if (price && parseInt(card.dataset.price) > parseInt(price)) show = false;
        card.style.display = show ? 'block' : 'none';
    });
}

/* ── Summary ─────────────────────────────────── */
function buildSummary() {
    const ci = new Date(document.getElementById('check_in').value);
    const co = new Date(document.getElementById('check_out').value);
    const fmtDate = d => d.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });

    setText('s-name', document.getElementById('name').value);
    setText('s-phone', document.getElementById('phone').value);
    setText('s-email', document.getElementById('email').value || 'Non renseigné');
    setText('s-room', `Chambre ${selectedRoomNumber}`);
    setText('s-room-type', selectedRoomType);
    setText('s-room-cap', `${selectedRoomCap} personne${selectedRoomCap > 1 ? 's' : ''}`);
    setText('s-price-night', formatCFA(selectedRoomPrice));
    setText('s-checkin', fmtDate(ci));
    setText('s-checkout', fmtDate(co));
    setText('s-nights', `${nightsCount} nuit${nightsCount > 1 ? 's' : ''}`);
    setText('s-price2', formatCFA(selectedRoomPrice));
    setText('s-total', formatCFA(totalPrice));
    setText('deposit-amount-val', formatCFA(totalPrice * 0.3));
}
function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}

/* ── Format ──────────────────────────────────── */
function formatCFA(n) {
    return new Intl.NumberFormat('fr-FR').format(n) + ' CFA';
}

/* ── Search customers ────────────────────────── */
function searchCustomers() {
    const q = document.getElementById('search-customer').value.trim();
    if (q.length < 2) { showAlert('Saisissez au moins 2 caractères.', 'info'); return; }

    const res = document.getElementById('customer-results');
    res.style.display = 'block';
    res.innerHTML = '<div style="padding:14px;text-align:center;font-size:.82rem;color:var(--slate-400)"><i class="fas fa-spinner fa-spin me-2"></i>Recherche…</div>';

    fetch(`/api/customers?search=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                res.innerHTML = '<div style="padding:14px;text-align:center;font-size:.82rem;color:var(--slate-400)">Aucun client trouvé</div>';
                return;
            }
            res.innerHTML = data.map(c => `
                <div class="customer-result-item" onclick="fillCustomer('${esc(c.name)}','${esc(c.phone)}','${esc(c.email||'')}')">
                    <div>
                        <div class="cri-name">${c.name}</div>
                        <div class="cri-meta">${c.phone}${c.email ? ' · ' + c.email : ''}</div>
                    </div>
                    <span class="cri-badge">${c.reservation_count || 0} résa</span>
                </div>
            `).join('');
        })
        .catch(() => {
            res.innerHTML = '<div style="padding:14px;text-align:center;font-size:.82rem;color:var(--red-500)">Erreur lors de la recherche</div>';
        });
}
function esc(s) { return s.replace(/'/g, "\\'"); }
function fillCustomer(name, phone, email) {
    document.getElementById('name').value = name;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;
    document.getElementById('customer-results').style.display = 'none';
}

/* ── Toast alerts ────────────────────────────── */
function showAlert(msg, type = 'error') {
    const colors = { error: 'var(--red-500)', info: 'var(--blue-600)', success: 'var(--green-500)' };
    const icons  = { error: 'fa-exclamation-circle', info: 'fa-info-circle', success: 'fa-check-circle' };
    const t = document.createElement('div');
    t.style.cssText = `
        position:fixed;top:24px;right:24px;z-index:9999;
        display:flex;align-items:center;gap:10px;
        padding:13px 18px;background:white;min-width:280px;max-width:380px;
        border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.12);
        border-left:4px solid ${colors[type]};font-size:.875rem;font-weight:500;
        animation:fadeUp .25s ease;
    `;
    t.innerHTML = `<i class="fas ${icons[type]}" style="color:${colors[type]};flex-shrink:0"></i><span>${msg}</span>`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; }, 3000);
    setTimeout(() => t.remove(), 3400);
}

/* ── Init ────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    updateStepper(1);
    calcNights();

    document.getElementById('check_in').addEventListener('change', function () {
        const ci = new Date(this.value);
        ci.setDate(ci.getDate() + 1);
        document.getElementById('check_out').min = ci.toISOString().split('T')[0];
        const co = document.getElementById('check_out');
        if (co.value && new Date(co.value) <= new Date(this.value)) {
            co.value = ci.toISOString().split('T')[0];
        }
        calcNights();
    });
    document.getElementById('check_out').addEventListener('change', calcNights);
    document.getElementById('person_count').addEventListener('change', calcNights);

    /* Deposit toggle */
    document.getElementById('pay-deposit').addEventListener('change', function () {
        document.getElementById('deposit-amount-row').style.display = this.checked ? 'flex' : 'none';
    });

    /* Prevent double submit */
    document.getElementById('direct-checkin-form').addEventListener('submit', function () {
        const btn = document.getElementById('confirm-checkin');
        if (btn.disabled) { event.preventDefault(); return false; }
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement…';
    });
});
</script>
@endsection