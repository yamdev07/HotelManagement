@extends('template.master')
@section('title', 'Check-in — Dashboard')
@section('content')

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

.ci-page {
    padding: 28px 32px 64px;
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
@keyframes urgentPulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; background-color: #ffc107; color: #856404; }
    100% { opacity: 1; }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.ci-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.ci-brand { display: flex; align-items: center; gap: 14px; }
.ci-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.ci-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.ci-header-title em { font-style: normal; color: var(--g600); }
.ci-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.ci-header-actions { display: flex; align-items: center; gap: 10px; }

.time-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--white);
    border: 1.5px solid var(--s200);
    border-radius: 100px;
    padding: 4px 12px;
    font-size: .75rem;
    color: var(--s600);
}
.time-badge i {
    color: var(--g600);
}

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.ci-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem;
    color: var(--s400);
    margin-bottom: 20px;
}
.ci-breadcrumb a {
    color: var(--s400);
    text-decoration: none;
    transition: var(--transition);
}
.ci-breadcrumb a:hover {
    color: var(--g600);
}
.ci-breadcrumb .sep {
    color: var(--s300);
}

/* ══════════════════════════════════════════════
   ALERTS
══════════════════════════════════════════════ */
.ci-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: var(--rl);
    margin-bottom: 24px;
    border: 1.5px solid transparent;
    animation: fadeSlide .3s ease;
}
.ci-alert-success {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.ci-alert-error {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}
.ci-alert-warning {
    background: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}
.ci-alert-info {
    background: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
.ci-alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
    font-size: 1rem;
    transition: var(--transition);
}
.ci-alert-close:hover {
    opacity: 1;
}

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
@media (max-width: 1400px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: var(--white);
    border-radius: var(--rl);
    padding: 20px 22px;
    border: 1.5px solid var(--s100);
    box-shadow: var(--shadow-xs);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    transform: translateY(-3px);
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent, var(--g400));
    border-radius: 0 0 var(--rl) var(--rl);
}
.stat-card--arrivals   { --accent: var(--g400); }
.stat-card--staying    { --accent: var(--g600); }
.stat-card--departures { --accent: var(--g300); }
.stat-card--available  { --accent: var(--s400); }
.stat-card--dirty      { --accent: #ffc107; }
.stat-card--urgent     { --accent: #dc3545; }

.stat-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
}
.stat-card-label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--s400);
}
.stat-card-value {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--s900);
    line-height: 1;
    font-family: var(--mono);
    letter-spacing: -1px;
}
.stat-card-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.stat-card--arrivals .stat-card-icon   { background: var(--g50); color: var(--g600); }
.stat-card--staying .stat-card-icon    { background: var(--g100); color: var(--g700); }
.stat-card--departures .stat-card-icon { background: var(--g50); color: var(--g500); }
.stat-card--available .stat-card-icon  { background: var(--s100); color: var(--s500); }
.stat-card--dirty .stat-card-icon      { background: #fff3cd; color: #856404; }
.stat-card--urgent .stat-card-icon     { background: #fee2e2; color: #b91c1c; }

.stat-card-meta {
    font-size: .75rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 6px;
    border-top: 1px solid var(--s100);
    padding-top: 12px;
    margin-top: 12px;
}
.stat-card-meta a {
    color: var(--g600);
    text-decoration: none;
}
.stat-card-meta a:hover {
    text-decoration: underline;
}

/* ══════════════════════════════════════════════
   MAIN GRID
══════════════════════════════════════════════ */
.ci-main-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 1100px) {
    .ci-main-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.ci-card {
    background: var(--white);
    border-radius: var(--rxl);
    border: 1.5px solid var(--s100);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.ci-card:hover {
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.ci-card:last-child { margin-bottom: 0; }

.ci-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    flex-wrap: wrap;
    gap: 12px;
}
.ci-card-header.warning {
    background: #fff3cd;
    border-bottom-color: #ffc107;
}
.ci-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.ci-card-title i {
    color: var(--g600);
}
.ci-card-badge {
    background: var(--g100);
    color: var(--g700);
    font-size: .7rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 100px;
}

/* ══════════════════════════════════════════════
   DATE GROUPS (arrivées)
══════════════════════════════════════════════ */
.date-group {
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--s100);
}
.date-group:last-child { border-bottom: none; }

.date-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}
.date-group-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    font-weight: 600;
    color: var(--s600);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.date-group-label i {
    color: var(--g500);
}
.date-group-pill {
    background: var(--g50);
    color: var(--g700);
    border: 1.5px solid var(--g200);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ── Reservation row ────────────────────────── */
.res-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto auto;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: var(--rl);
    border: 1.5px solid var(--s100);
    margin-bottom: 8px;
    transition: var(--transition);
    background: var(--surface);
    position: relative;
}
.res-row:last-child { margin-bottom: 0; }
.res-row:hover {
    background: var(--white);
    border-color: var(--g200);
    transform: translateX(2px);
    box-shadow: var(--shadow-sm);
}
.res-row.dirty-room {
    border-left: 6px solid #ffc107;
    background-color: #fff9e6;
}
.res-row.dirty-room:hover {
    background-color: #fff3cd;
    border-color: #ffc107;
}
.res-row.cleaning-room {
    border-left: 6px solid #17a2b8;
    background-color: #e3f2fd;
}
.res-row.cleaning-room:hover {
    background-color: #d1ecf1;
    border-color: #17a2b8;
}

.res-guest {}
.res-guest-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}
.res-guest-phone {
    font-size: .72rem;
    color: var(--s400);
    margin-top: 3px;
}

.res-room-badge {
    background: var(--s100);
    color: var(--s700);
    font-weight: 600;
    padding: 5px 12px;
    border-radius: var(--r);
    font-size: .8rem;
    display: inline-block;
    border: 1.5px solid var(--s200);
    font-family: var(--mono);
}
.res-room-type {
    font-size: .68rem;
    color: var(--s400);
    text-align: center;
    margin-top: 3px;
}

.res-time {
    text-align: center;
    min-width: 70px;
}
.res-time-val {
    font-size: .9rem;
    font-weight: 700;
    color: var(--s800);
    display: block;
}
.res-time-label {
    font-size: .68rem;
    color: var(--s400);
}

.res-nights {
    text-align: center;
    min-width: 70px;
}
.res-nights-val {
    font-size: .85rem;
    font-weight: 600;
    color: var(--s700);
    display: block;
}
.res-nights-label {
    font-size: .68rem;
    color: var(--s400);
}

.date-indicator {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .62rem;
    font-weight: 600;
    margin-top: 3px;
}
.di-upcoming { background: var(--g100); color: var(--g700); }
.di-waiting  { background: var(--g50); color: var(--g600); }
.di-urgent {
    background: #ffc107;
    color: #856404;
    animation: urgentPulse 2s infinite;
}

/* ── Badges de statut de chambre ────────────── */
.room-status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: 0.6rem;
    font-weight: 600;
    margin-left: 5px;
}
.room-status-badge.dirty {
    background: #ffc107;
    color: #856404;
}
.room-status-badge.clean {
    background: #28a745;
    color: white;
}
.room-status-badge.occupied {
    background: #dc3545;
    color: white;
}
.room-status-badge.cleaning {
    background: #17a2b8;
    color: white;
}
.room-status-badge.maintenance {
    background: #6c757d;
    color: white;
}

/* ── Action buttons ─────────────────────────── */
.btn-res {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: var(--r);
    font-size: .75rem;
    font-weight: 500;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
}
.btn-res-checkin {
    background: var(--g600);
    color: white;
    border-color: var(--g700);
}
.btn-res-checkin:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(46,133,64,.25);
}
.btn-res-quick {
    background: var(--white);
    color: var(--g600);
    border-color: var(--g200);
}
.btn-res-quick:hover {
    background: var(--g50);
    color: var(--g700);
    border-color: var(--g300);
    transform: translateY(-1px);
}
.btn-res-view {
    background: var(--white);
    color: var(--s500);
    border-color: var(--s200);
}
.btn-res-view:hover {
    background: var(--s50);
    color: var(--s700);
    border-color: var(--s300);
    transform: translateY(-1px);
}
.btn-res-notify {
    background: #ffc107;
    color: #856404;
    border-color: #ffc107;
}
.btn-res-notify:hover {
    background: #ffca2c;
    transform: translateY(-1px);
}
.btn-res-notified {
    background: #28a745;
    color: white;
    border-color: #28a745;
    cursor: default;
}
.btn-res-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* ══════════════════════════════════════════════
   RIGHT COLUMN — Guest cards
══════════════════════════════════════════════ */
.guest-card {
    padding: 16px 18px;
    border-bottom: 1.5px solid var(--s100);
    transition: var(--transition);
}
.guest-card:last-child { border-bottom: none; }
.guest-card:hover {
    background: var(--g50);
}

.guest-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
    gap: 10px;
}
.guest-card-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}
.guest-card-meta {
    font-size: .72rem;
    color: var(--s400);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
    flex-wrap: wrap;
}
.guest-card-room-badge {
    background: var(--s100);
    color: var(--s600);
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: 600;
    font-size: .7rem;
}
.unpaid-alert {
    background: #fee2e2;
    border-left: 3px solid #b91c1c;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: .7rem;
    margin-top: 6px;
    color: #b91c1c;
}

.guest-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.guest-card-departure {
    font-size: .72rem;
    color: var(--s500);
    display: flex;
    align-items: center;
    gap: 5px;
}
.guest-card-actions {
    display: flex;
    gap: 5px;
}

.btn-ghost-sm {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    font-size: .75rem;
    transition: var(--transition);
    text-decoration: none;
    cursor: pointer;
}
.btn-ghost-sm:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-1px);
}

.tag-largesse {
    background: var(--g100);
    color: var(--g700);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.tag-urgent {
    background: #fee2e2;
    color: #b91c1c;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

/* ══════════════════════════════════════════════
   DEPARTURE ROWS
══════════════════════════════════════════════ */
.dep-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--s100);
    gap: 12px;
    transition: var(--transition);
}
.dep-row:last-child { border-bottom: none; }
.dep-row:hover {
    background: var(--g50);
}
.dep-row.urgent-cleaning {
    background: #fff3cd;
}
.dep-row.urgent-cleaning:hover {
    background: #ffe69c;
}
.dep-row-info {}
.dep-row-name {
    font-size: .88rem;
    font-weight: 600;
    color: var(--s900);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}
.dep-row-room {
    font-size: .72rem;
    color: var(--s400);
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.dep-row-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.dep-time-badge {
    background: var(--s100);
    color: var(--s700);
    font-weight: 600;
    padding: 4px 10px;
    border-radius: var(--r);
    font-size: .78rem;
    border: 1.5px solid var(--s200);
}
.dep-time-badge-largesse {
    background: var(--g100);
    color: var(--g700);
    border-color: var(--g200);
}
.dep-actions {
    display: flex;
    gap: 5px;
}

.btn-dep-invoice {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: var(--r);
    font-size: .72rem;
    font-weight: 500;
    border: 1.5px solid var(--s200);
    background: var(--white);
    color: var(--s500);
    text-decoration: none;
    transition: var(--transition);
}
.btn-dep-invoice:hover {
    background: var(--s50);
    border-color: var(--s300);
    color: var(--s700);
    transform: translateY(-1px);
}
.btn-dep-checkout {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: var(--r);
    font-size: .72rem;
    font-weight: 600;
    border: 1.5px solid var(--g200);
    background: var(--g50);
    color: var(--g700);
    text-decoration: none;
    transition: var(--transition);
}
.btn-dep-checkout:hover {
    background: var(--g600);
    border-color: var(--g600);
    color: white;
    transform: translateY(-1px);
}
.btn-dep-checkout-late {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #c2410c;
}
.btn-dep-checkout-late:hover {
    background: #c2410c;
    border-color: #c2410c;
    color: white;
}

/* ══════════════════════════════════════════════
   CARD FOOTER
══════════════════════════════════════════════ */
.ci-card-footer {
    padding: 14px 22px;
    border-top: 1.5px solid var(--s100);
    background: var(--surface);
    text-align: center;
}
.btn-ci-footer {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: var(--r);
    font-size: .78rem;
    font-weight: 500;
    color: var(--g700);
    background: var(--white);
    border: 1.5px solid var(--g200);
    text-decoration: none;
    transition: var(--transition);
}
.btn-ci-footer:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g800);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.ci-empty {
    text-align: center;
    padding: 48px 24px;
}
.ci-empty-icon {
    width: 72px;
    height: 72px;
    background: var(--g50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--g300);
    margin: 0 auto 16px;
    border: 2px solid var(--g100);
}
.ci-empty-title {
    font-size: .95rem;
    font-weight: 600;
    color: var(--s700);
    margin-bottom: 6px;
}
.ci-empty-text {
    font-size: .8rem;
    color: var(--s400);
    margin-bottom: 18px;
}

/* ══════════════════════════════════════════════
   TOAST
══════════════════════════════════════════════ */
.ci-toast-wrap {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}
.ci-toast {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    background: var(--white);
    border-radius: var(--rl);
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--g600);
    font-size: .8rem;
    font-weight: 500;
    color: var(--s800);
    pointer-events: auto;
    animation: slideIn .25s ease;
    min-width: 280px;
    max-width: 380px;
}
.ci-toast-error {
    border-left-color: #b91c1c;
}
.ci-toast-warning {
    border-left-color: #ffc107;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>

<div class="ci-page">

    {{-- ─── TOAST CONTAINER ─────────────────────── --}}
    <div class="ci-toast-wrap" id="toast-container"></div>

    {{-- ─── BREADCRUMB ─────────────────────────── --}}
    <div class="ci-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Check-in</span>
    </div>

    {{-- ─── HEADER ─────────────────────────────── --}}
    <div class="ci-header anim-2">
        <div class="ci-brand">
            <div class="ci-brand-icon"><i class="fas fa-door-open"></i></div>
            <div>
                <h1 class="ci-header-title">Gestion des <em>Check-in</em></h1>
                <div class="ci-header-sub">
                    <span>Arrivées, séjours et départs</span>
                    <span class="time-badge">
                        <i class="fas fa-clock"></i> Check-in 12h | Check-out 12h (largesse 14h)
                    </span>
                </div>
            </div>
        </div>
        <div class="ci-header-actions">
            <a href="{{ route('checkin.search') }}" class="btn-db btn-db-ghost">
                <i class="fas fa-search fa-xs"></i> Rechercher
            </a>
            <a href="{{ route('checkin.direct') }}" class="btn-db btn-db-primary">
                <i class="fas fa-user-plus fa-xs"></i> Check-in Direct
            </a>
        </div>
    </div>

    {{-- ─── ALERTS ─────────────────────────────── --}}
    @if(session('success'))
    <div class="ci-alert ci-alert-success anim-2">
        <i class="fas fa-check-circle"></i>
        <span>{!! session('success') !!}</span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div class="ci-alert ci-alert-error anim-2">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button class="ci-alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    @endif

    {{-- ALERTE URGENCE HOUSEKEEPING --}}
    @if(isset($stats['urgent_cleaning']) && $stats['urgent_cleaning'] > 0)
    <div class="ci-alert ci-alert-warning anim-2">
        <i class="fas fa-broom fa-lg"></i>
        <span>
            <strong>{{ $stats['urgent_cleaning'] }} chambre(s) sale(s) avec arrivée aujourd'hui !</strong>
            <br><small>Ces chambres doivent être nettoyées en priorité avant l'arrivée des clients.</small>
        </span>
        <a href="{{ route('housekeeping.index') }}" class="btn-db btn-db-ghost" style="margin-left:auto;background:#ffc107;color:#856404;border-color:#ffc107;padding:5px 12px;border-radius:20px;text-decoration:none;">
            <i class="fas fa-bell me-1"></i> Voir housekeeping
        </a>
    </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="stats-grid anim-3">
        <div class="stat-card stat-card--arrivals">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Arrivées aujourd'hui</div>
                    <div class="stat-card-value">{{ $stats['arrivals_today'] }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-calendar-day"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-clock fa-xs"></i>
                Prévues pour {{ $today->format('d/m/Y') }}
            </div>
        </div>

        <div class="stat-card stat-card--staying">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Séjours en cours</div>
                    <div class="stat-card-value">{{ $stats['currently_checked_in'] }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-bed"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-hotel fa-xs"></i>
                Clients dans l'hôtel
            </div>
        </div>

        <div class="stat-card stat-card--departures">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Départs aujourd'hui</div>
                    <div class="stat-card-value">{{ $stats['departures_today'] }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-sign-out-alt"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-door-open fa-xs"></i>
                Chambres à libérer
            </div>
        </div>

        <div class="stat-card stat-card--available">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Chambres disponibles</div>
                    <div class="stat-card-value">{{ $stats['available_rooms'] }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-door-closed"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-check-circle fa-xs"></i>
                Prêtes à l'accueil
            </div>
        </div>

        <div class="stat-card stat-card--dirty">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Chambres sales</div>
                    <div class="stat-card-value">{{ $stats['dirty_rooms'] ?? 0 }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-broom"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-exclamation-triangle fa-xs"></i>
                <span>Dont <strong>{{ $stats['urgent_cleaning'] ?? 0 }}</strong> avec arrivée</span>
            </div>
        </div>

        <div class="stat-card stat-card--urgent">
            <div class="stat-card-top">
                <div>
                    <div class="stat-card-label">Urgences nettoyage</div>
                    <div class="stat-card-value">{{ $stats['urgent_cleaning'] ?? 0 }}</div>
                </div>
                <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="stat-card-meta">
                <i class="fas fa-clock fa-xs"></i>
                <a href="{{ route('housekeeping.index') }}">Voir housekeeping <i class="fas fa-arrow-right fa-xs"></i></a>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="ci-main-grid">

        {{-- LEFT — Réservations à venir --}}
        <div class="anim-4">
            <div class="ci-card">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-calendar-check"></i>
                        Réservations à venir
                    </h3>
                    <span class="ci-card-badge">{{ $upcomingReservations->count() }} groupe(s)</span>
                </div>

                @if($upcomingReservations->isEmpty())
                <div class="ci-empty">
                    <div class="ci-empty-icon"><i class="fas fa-calendar-times"></i></div>
                    <p class="ci-empty-title">Aucune arrivée prévue</p>
                    <p class="ci-empty-text">Pas de réservations pour aujourd'hui ni demain</p>
                    <a href="{{ route('checkin.search') }}" class="btn-db btn-db-primary" style="margin-top:8px;">
                        <i class="fas fa-search fa-xs"></i> Chercher des réservations
                    </a>
                </div>
                @else
                    @foreach($upcomingReservations as $date => $reservations)
                    <div class="date-group">
                        <div class="date-group-header">
                            <span class="date-group-label">
                                <i class="fas fa-calendar-day"></i>
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                            </span>
                            <span class="date-group-pill">
                                <i class="fas fa-ticket-alt fa-xs"></i>
                                {{ $reservations->count() }} réservation{{ $reservations->count() > 1 ? 's' : '' }}
                            </span>
                        </div>

                        @foreach($reservations as $transaction)
                        @php
                            $now = \Carbon\Carbon::now();
                            $checkIn = \Carbon\Carbon::parse($transaction->check_in);
                            $checkInDate = $checkIn->copy()->startOfDay();
                            
                            // Statut de la chambre
                            $room = $transaction->room;
                            $roomStatusId = $room->room_status_id;
                            $isDirty = $roomStatusId == 6; // STATUS_DIRTY
                            $isCleaning = $roomStatusId == 5; // STATUS_CLEANING
                            $isAvailable = $roomStatusId == 1; // STATUS_AVAILABLE
                            
                            // Vérifications pour le check-in
                            $isToday = $now->isSameDay($checkInDate);
                            $isCheckinTime = $now->gte($checkIn->copy()->setTime(12, 0, 0));
                            $canCheckin = $transaction->status == 'reservation' && $isToday && $isCheckinTime && !$isDirty && !$isCleaning;
                            $checkinTooEarly = $transaction->status == 'reservation' && $isToday && !$isCheckinTime;
                            $checkinFuture = $transaction->status == 'reservation' && !$isToday;
                            
                            // Urgence
                            $isUrgent = $isToday && $isDirty;
                            
                            // Classes CSS
                            $rowClass = '';
                            if ($isDirty) $rowClass = 'dirty-room';
                            elseif ($isCleaning) $rowClass = 'cleaning-room';
                        @endphp
                        
                        <div class="res-row {{ $rowClass }}" id="reservation-{{ $transaction->id }}">
                            <div class="res-guest">
                                <div class="res-guest-name">
                                    {{ $transaction->customer->name }}
                                    @if($isDirty)
                                        <span class="room-status-badge dirty">
                                            <i class="fas fa-broom"></i> Sale
                                        </span>
                                    @elseif($isCleaning)
                                        <span class="room-status-badge cleaning">
                                            <i class="fas fa-spinner fa-spin"></i> Nettoyage
                                        </span>
                                    @elseif($isAvailable)
                                        <span class="room-status-badge clean">
                                            <i class="fas fa-check-circle"></i> Prête
                                        </span>
                                    @endif
                                </div>
                                <div class="res-guest-phone">
                                    <i class="fas fa-phone fa-xs" style="color:var(--s300);"></i>
                                    {{ $transaction->customer->phone }}
                                </div>
                            </div>

                            <div style="text-align:center;">
                                <span class="res-room-badge">{{ $room->number }}</span>
                                <div class="res-room-type">{{ $room->type->name ?? 'N/A' }}</div>
                            </div>

                            <div class="res-time">
                                <span class="res-time-val">12:00</span>
                                <span class="res-time-label">Arrivée</span>
                                @if($checkinFuture)
                                    <div class="date-indicator di-upcoming">J-{{ $now->diffInDays($checkInDate) }}</div>
                                @elseif($checkinTooEarly)
                                    <div class="date-indicator di-waiting">Attente 12h</div>
                                @elseif($isUrgent)
                                    <div class="date-indicator di-urgent">⚠️ Urgent</div>
                                @endif
                            </div>

                            <div class="res-nights">
                                <span class="res-nights-val">{{ $transaction->nights }}n</span>
                                <span class="res-nights-label">→ {{ $transaction->check_out->format('d/m') }}</span>
                            </div>

                            <div class="res-actions">
                                @if($canCheckin)
                                    <form action="{{ route('transaction.mark-arrived', $transaction) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-res btn-res-checkin" onclick="return confirm('Confirmer l\'arrivée de {{ $transaction->customer->name }} ?')">
                                            <i class="fas fa-door-open"></i> Check-in
                                        </button>
                                    </form>
                                @elseif($isUrgent)
                                    <button onclick="notifyHousekeeping({{ $room->id }}, this)" class="btn-res btn-res-notify">
                                        <i class="fas fa-bell"></i> Notifier
                                    </button>
                                    <a href="{{ route('checkin.show', $transaction) }}" class="btn-res btn-res-view" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @elseif($isCleaning && $isToday)
                                    <span class="btn-res btn-res-disabled" style="background:#17a2b8;color:white;">
                                        <i class="fas fa-spinner fa-spin"></i> Nettoyage
                                    </span>
                                    <a href="{{ route('checkin.show', $transaction) }}" class="btn-res btn-res-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @elseif($checkinTooEarly)
                                    <span class="btn-res btn-res-checkin btn-res-disabled" title="Check-in possible à partir de 12h">
                                        <i class="fas fa-clock"></i> Check-in
                                    </span>
                                @elseif($checkinFuture)
                                    <span class="btn-res btn-res-checkin btn-res-disabled" title="Arrivée prévue le {{ $checkInDate->format('d/m/Y') }}">
                                        <i class="fas fa-calendar"></i> Check-in
                                    </span>
                                @else
                                    <a href="{{ route('checkin.show', $transaction) }}" class="btn-res btn-res-view">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                @endif
                                
                                @if(!$isDirty && !$isCleaning && $isToday)
                                    <button onclick="quickCheckIn({{ $transaction->id }}, this)" class="btn-res btn-res-quick">
                                        <i class="fas fa-bolt"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    <div class="ci-card-footer">
                        <a href="{{ route('transaction.index') }}?status=reservation" class="btn-ci-footer">
                            <i class="fas fa-list fa-xs"></i> Voir toutes les réservations
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT column --}}
        <div class="anim-5">

            {{-- Clients dans l'hôtel --}}
            <div class="ci-card">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-users"></i>
                        Dans l'hôtel
                    </h3>
                    <span class="ci-card-badge">{{ $activeGuests->count() }} client{{ $activeGuests->count() > 1 ? 's' : '' }}</span>
                </div>

                @if($activeGuests->isEmpty())
                <div class="ci-empty" style="padding: 32px 20px;">
                    <div class="ci-empty-icon" style="width:56px;height:56px;font-size:1.4rem;"><i class="fas fa-users-slash"></i></div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun client en ce moment</p>
                </div>
                @else
                    @foreach($activeGuests as $transaction)
                    @php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        
                        $now = \Carbon\Carbon::now();
                        $checkOutTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                        $checkOutLargess = $checkOutTime->copy()->setTime(14, 0, 0);
                        $canCheckout = $isFullyPaid && $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                        $isLate = $now->gt($checkOutLargess);
                        $isInLargess = $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                        
                        $roomStatus = $transaction->room->status_label ?? 'N/A';
                        $roomStatusColor = $transaction->room->status_color ?? 'secondary';
                    @endphp
                    <div class="guest-card">
                        <div class="guest-card-top">
                            <div>
                                <div class="guest-card-name">
                                    {{ $transaction->customer->name }}
                                    <span class="badge bg-{{ $roomStatusColor }} ms-2" style="font-size:0.6rem;">
                                        {{ $roomStatus }}
                                    </span>
                                </div>
                                <div class="guest-card-meta">
                                    <span><i class="fas fa-door-closed"></i> Ch. {{ $transaction->room->number }}</span>
                                    <span class="guest-card-room-badge">{{ $transaction->room->type->name ?? 'N/A' }}</span>
                                </div>
                                @if(!$isFullyPaid)
                                <div class="unpaid-alert">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Solde: {{ number_format($remaining, 0, ',', ' ') }} FCFA
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="guest-card-footer">
                            <div class="guest-card-departure">
                                <i class="fas fa-calendar-minus" style="color:var(--g400);"></i>
                                Départ {{ $transaction->check_out->format('d/m') }} à 12h00
                                @if($isInLargess)
                                    <span class="tag-largesse"><i class="fas fa-gift fa-xs"></i> largesse</span>
                                @endif
                                @if($isLate)
                                    <span class="tag-urgent"><i class="fas fa-exclamation-triangle fa-xs"></i> Dépassé</span>
                                @endif
                            </div>
                            <div class="guest-card-actions">
                                <a href="{{ route('transaction.show', $transaction) }}" class="btn-ghost-sm" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$isFullyPaid)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-ghost-sm" title="Paiement">
                                    <i class="fas fa-money-bill-wave-alt"></i>
                                </a>
                                @endif
                                @if($canCheckout)
                                <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-ghost-sm" title="Check-out (largesse)" onclick="return confirm('Confirmer le départ de {{ $transaction->customer->name }} ?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                @elseif($isLate)
                                <span class="btn-ghost-sm" style="opacity:0.5;cursor:not-allowed;background:var(--g50);" title="Départ après 14h - Prolongation nécessaire">
                                    <i class="fas fa-hourglass-end"></i>
                                </span>
                                @else
                                <span class="btn-ghost-sm" style="opacity:0.5;cursor:not-allowed;" title="Check-out possible à partir de 12h">
                                    <i class="fas fa-clock"></i>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if($activeGuests->isNotEmpty())
                <div class="ci-card-footer">
                    <a href="{{ route('transaction.index') }}?status=active" class="btn-ci-footer">
                        <i class="fas fa-list fa-xs"></i> Voir tous les séjours
                    </a>
                </div>
                @endif
            </div>

            {{-- URGENCES HOUSEKEEPING --}}
            @if(isset($urgentCleanings) && $urgentCleanings->count() > 0)
            <div class="ci-card">
                <div class="ci-card-header warning">
                    <h3 class="ci-card-title">
                        <i class="fas fa-broom" style="color:#856404;"></i>
                        Urgences nettoyage
                    </h3>
                    <span class="ci-card-badge" style="background:#ffc107;color:#856404;">{{ $urgentCleanings->count() }}</span>
                </div>

                @foreach($urgentCleanings as $urgent)
                <div class="dep-row urgent-cleaning">
                    <div class="dep-row-info">
                        <div class="dep-row-name">
                            {{ $urgent['customer_name'] }}
                            <span class="badge bg-warning ms-2">Arrivée {{ $urgent['arrival_time_formatted'] }}</span>
                        </div>
                        <div class="dep-row-room">
                            <i class="fas fa-door-closed"></i>
                            Chambre {{ $urgent['room_number'] }}
                        </div>
                    </div>
                    <div class="dep-row-right">
                        <div class="dep-actions">
                            <button onclick="notifyHousekeeping({{ $urgent['room_id'] }}, this)" class="btn-dep-checkout" style="background:#ffc107;color:#856404;border-color:#ffc107;">
                                <i class="fas fa-bell"></i> Notifier
                            </button>
                            <a href="{{ route('checkin.show', $urgent['reservation_id']) }}" class="btn-dep-invoice">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="ci-card-footer">
                    <a href="{{ route('housekeeping.index') }}" class="btn-ci-footer">
                        <i class="fas fa-broom fa-xs"></i> Voir housekeeping
                    </a>
                </div>
            </div>
            @endif

            {{-- Départs aujourd'hui --}}
            <div class="ci-card anim-6">
                <div class="ci-card-header">
                    <h3 class="ci-card-title">
                        <i class="fas fa-sign-out-alt"></i>
                        Départs aujourd'hui
                    </h3>
                    <span class="ci-card-badge">{{ $todayDepartures->count() }}</span>
                </div>

                @if($todayDepartures->isEmpty())
                <div class="ci-empty" style="padding: 32px 20px;">
                    <div class="ci-empty-icon" style="width:56px;height:56px;font-size:1.4rem;background:var(--g50);color:var(--g400);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="ci-empty-title" style="font-size:.85rem;">Aucun départ prévu</p>
                </div>
                @else
                    @foreach($todayDepartures as $transaction)
                    @php
                        $totalPrice = $transaction->getTotalPrice();
                        $totalPayment = $transaction->getTotalPayment();
                        $remaining = $totalPrice - $totalPayment;
                        $isFullyPaid = $remaining <= 0;
                        
                        $now = \Carbon\Carbon::now();
                        $checkOutTime = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                        $checkOutLargess = $checkOutTime->copy()->setTime(14, 0, 0);
                        $canCheckout = $isFullyPaid && $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                        $isLate = $now->gt($checkOutLargess);
                        $isInLargess = $now->gte($checkOutTime) && $now->lte($checkOutLargess);
                    @endphp
                    <div class="dep-row">
                        <div class="dep-row-info">
                            <div class="dep-row-name">{{ $transaction->customer->name }}</div>
                            <div class="dep-row-room">
                                <i class="fas fa-door-closed"></i>
                                Chambre {{ $transaction->room->number }}
                                @if(!$isFullyPaid)
                                <span class="badge badge-danger" style="background:#fee2e2;color:#b91c1c;padding:2px 6px;border-radius:4px;font-size:.6rem;margin-left:5px;">Impayé</span>
                                @endif
                            </div>
                        </div>
                        <div class="dep-row-right">
                            <span class="dep-time-badge {{ $isInLargess ? 'dep-time-badge-largesse' : '' }}">12:00</span>
                            <div class="dep-actions">
                                <a href="{{ route('transaction.show', $transaction) }}" class="btn-dep-invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                @if($canCheckout)
                                <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-dep-checkout" onclick="return confirm('Confirmer le départ ?')">
                                        <i class="fas fa-sign-out-alt"></i> Out
                                    </button>
                                </form>
                                @elseif($isLate && $isFullyPaid)
                                <form action="{{ route('transaction.mark-departed', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="override" value="1">
                                    <button type="submit" class="btn-dep-checkout btn-dep-checkout-late" onclick="return confirm('Dérogation après 14h ? Confirmer le départ ?')">
                                        <i class="fas fa-gavel"></i> Dérog.
                                    </button>
                                </form>
                                @elseif(!$isFullyPaid)
                                <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn-dep-checkout" style="background:#fee2e2;color:#b91c1c;border-color:#fecaca;">
                                    <i class="fas fa-money-bill-wave-alt"></i> Payer
                                </a>
                                @else
                                <span class="btn-dep-checkout" style="opacity:0.5;cursor:not-allowed;" title="Check-out à partir de 12h">
                                    <i class="fas fa-clock"></i> 12h
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="ci-card-footer">
                        <a href="{{ route('transaction.index') }}?check_out={{ $today->format('Y-m-d') }}" class="btn-ci-footer">
                            <i class="fas fa-door-open fa-xs"></i> Gérer tous les départs
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>

@endsection

@section('footer')
<script>
/* ── Toast helper ─────────────────────────────── */
function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = 'ci-toast';
    if (type === 'error') t.classList.add('ci-toast-error');
    if (type === 'warning') t.classList.add('ci-toast-warning');
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 
                 type === 'error' ? 'fas fa-exclamation-circle' : 
                 'fas fa-exclamation-triangle';
    
    t.innerHTML = `<i class="${icon}" style="font-size:1rem;flex-shrink:0"></i><span>${msg}</span>`;
    wrap.appendChild(t);
    setTimeout(() => t.style.opacity = '0', 2800);
    setTimeout(() => t.remove(), 3100);
}

/* ── Quick check-in (rapide) ───────────────────── */
function quickCheckIn(id, btn) {
    if (!confirm('Effectuer un check-in rapide ?')) return;

    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch(`/checkin/${id}/quick`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Check-in effectué avec succès !');
            setTimeout(() => location.reload(), 1600);
        } else {
            showToast(data.error || 'Échec du check-in', 'error');
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Une erreur est survenue', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
    });
}

/* ── Notifier housekeeping ─────────────────────── */
function notifyHousekeeping(roomId, btn) {
    if (!confirm('Notifier l\'équipe housekeeping pour nettoyage urgent ?')) return;

    const orig = btn.innerHTML;
    const origClass = btn.className;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
    btn.disabled = true;

    fetch(`/checkin/notify-housekeeping/${roomId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            btn.innerHTML = '<i class="fas fa-check"></i> Notifié';
            btn.classList.remove('btn-res-notify');
            btn.classList.add('btn-res-notified');
            
            // Réinitialiser après 3 secondes
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-bell"></i> Notifier';
                btn.classList.remove('btn-res-notified');
                btn.classList.add('btn-res-notify');
                btn.disabled = false;
            }, 3000);
        } else {
            showToast('❌ ' + data.message, 'error');
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('❌ Erreur lors de la notification', 'error');
        btn.innerHTML = orig;
        btn.disabled = false;
    });
}

/* ── Auto-refresh des données toutes les 60 secondes ── */
setInterval(() => {
    // Recharger les données sans recharger la page
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Mettre à jour uniquement les sections nécessaires
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Mettre à jour les stats
        const newStats = doc.querySelector('.stats-grid');
        const currentStats = document.querySelector('.stats-grid');
        if (newStats && currentStats) {
            currentStats.innerHTML = newStats.innerHTML;
        }
        
        // Mettre à jour les réservations
        const newReservations = doc.querySelector('.ci-main-grid > div:first-child .ci-card');
        const currentReservations = document.querySelector('.ci-main-grid > div:first-child .ci-card');
        if (newReservations && currentReservations) {
            currentReservations.innerHTML = newReservations.innerHTML;
        }
    })
    .catch(error => console.error('Auto-refresh error:', error));
}, 60000); // Toutes les 60 secondes
</script>
@endsection