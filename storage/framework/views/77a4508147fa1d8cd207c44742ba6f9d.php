
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>

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

.db-page {
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
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.db-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.db-brand { display: flex; align-items: center; gap: 14px; }
.db-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.db-header-greeting {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.db-header-greeting em { font-style: normal; color: var(--g600); }
.db-header-sub { font-size: .8rem; color: var(--s400); margin-top: 3px; }
.db-header-right { display: flex; align-items: center; gap: 10px; }

.db-clock-pill {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 16px; background: var(--white);
    border: 1.5px solid var(--s200); border-radius: 100px;
    box-shadow: var(--shadow-xs);
}
.db-clock-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--g500); animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.5; transform:scale(.8); }
}
.db-clock-time {
    font-family: var(--mono); font-size: .9rem; font-weight: 500;
    color: var(--s800); letter-spacing: -.3px;
}
.db-clock-date {
    font-size: .72rem; color: var(--s400);
    padding-left: 8px; border-left: 1px solid var(--s200);
}
.btn-site {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 100px;
    font-size: .8rem; font-weight: 500; color: var(--s600);
    background: var(--white); border: 1.5px solid var(--s200);
    text-decoration: none; transition: var(--transition);
    box-shadow: var(--shadow-xs); font-family: var(--font);
}
.btn-site:hover { background: var(--g50); border-color: var(--g300); color: var(--g700); text-decoration: none; }

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 14px; margin-bottom: 24px;
}
@media(max-width:1100px){ .stats-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:560px) { .stats-grid{ grid-template-columns:1fr; } }

.stat-card {
    background: var(--white); border-radius: var(--rl);
    padding: 22px 20px 18px;
    border: 1.5px solid var(--s100);
    text-decoration: none; display: block;
    position: relative; overflow: hidden;
    transition: var(--transition); box-shadow: var(--shadow-xs);
}
.stat-card:hover {
    transform: translateY(-3px); box-shadow: var(--shadow-md);
    border-color: var(--g200); text-decoration: none;
}
.stat-card::after {
    content: ''; position: absolute;
    bottom: 0; left: 0; right: 0; height: 3px;
    background: var(--bar-c, var(--g400));
    border-radius: 0 0 var(--rl) var(--rl);
}
.stat-card--primary   { --bar-c: var(--g600); }
.stat-card--secondary { --bar-c: var(--g400); }
.stat-card--muted     { --bar-c: var(--g200); }
.stat-card--neutral   { --bar-c: var(--s300); }

.stat-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.stat-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-card--primary .stat-card-icon,
.stat-card--secondary .stat-card-icon { background: var(--g50); color: var(--g600); }
.stat-card--muted .stat-card-icon,
.stat-card--neutral .stat-card-icon   { background: var(--s100); color: var(--s500); }

.stat-card-badge {
    font-size: .65rem; font-weight: 600; padding: 3px 9px;
    border-radius: 100px; text-transform: uppercase; letter-spacing: .4px;
}
.stat-card--primary .stat-card-badge,
.stat-card--secondary .stat-card-badge { background: var(--g100); color: var(--g700); }
.stat-card--muted .stat-card-badge,
.stat-card--neutral .stat-card-badge   { background: var(--s100); color: var(--s600); }

.stat-card-value {
    font-size: 2.6rem; font-weight: 700; color: var(--s900);
    line-height: 1; letter-spacing: -1px; margin-bottom: 4px;
    font-family: var(--mono);
}
.stat-card-label { font-size: .8rem; color: var(--s400); margin-bottom: 14px; }
.stat-card-meta {
    display: flex; align-items: center; gap: 5px;
    font-size: .72rem; padding-top: 12px;
    border-top: 1px solid var(--s100); color: var(--s400);
}
.stat-card--primary .stat-card-meta,
.stat-card--secondary .stat-card-meta { color: var(--g600); }

/* ══════════════════════════════════════════════
   PANEL ARRIVÉES / DÉPARTS
══════════════════════════════════════════════ */
.db-panel {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 24px; box-shadow: var(--shadow-sm);
}
.db-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1.5px solid var(--s100);
}
.db-panel-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.db-panel-title-icon {
    width: 30px; height: 30px; background: var(--g50);
    border-radius: 7px; display: flex; align-items: center;
    justify-content: center; color: var(--g600); font-size: .8rem;
}
.db-panel-body { padding: 20px 24px; }

.section-label {
    font-size: .65rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .8px; color: var(--s400); margin-bottom: 10px; padding: 0 2px;
}

.dates-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 12px; margin-bottom: 16px;
}
@media(max-width:640px){ .dates-grid{ grid-template-columns:1fr; } }

.date-card {
    border-radius: var(--rl); border: 1.5px solid var(--s100);
    padding: 16px; text-decoration: none; display: block;
    transition: var(--transition); background: var(--white);
}
.date-card:hover {
    border-color: var(--g300); background: var(--g50);
    transform: translateY(-2px); box-shadow: var(--shadow-sm);
    text-decoration: none;
}
.date-card--today {
    border-color: var(--g300);
    background: linear-gradient(135deg, var(--g50) 0%, #f8fdf8 100%);
    box-shadow: 0 0 0 3px rgba(46,133,64,.07);
}
.date-card-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.date-card-name { font-size: .82rem; font-weight: 600; color: var(--s700); }
.date-card--today .date-card-name { color: var(--g700); }
.date-card-pill {
    font-size: .65rem; font-weight: 600; padding: 2px 8px;
    border-radius: 100px; background: var(--s100); color: var(--s500);
}
.date-card--today .date-card-pill { background: var(--g100); color: var(--g700); }
.date-card-rows { display: flex; flex-direction: column; gap: 8px; }
.date-card-row {
    display: flex; justify-content: space-between;
    align-items: center; font-size: .8rem;
}
.date-card-row-label { display: flex; align-items: center; gap: 7px; color: var(--s400); }
.row-ico {
    width: 22px; height: 22px; border-radius: 5px;
    background: var(--s100); display: flex; align-items: center;
    justify-content: center; font-size: .7rem; color: var(--s500);
}
.row-ico.green { background: var(--g100); color: var(--g600); }
.date-card-row-val {
    font-weight: 700; color: var(--s900);
    font-family: var(--mono); font-size: .85rem;
}

.rooms-grid {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 12px;
}
@media(max-width:640px){ .rooms-grid{ grid-template-columns:1fr; } }

.room-stat-card {
    border-radius: var(--rl); border: 1.5px solid var(--s100);
    padding: 16px; text-decoration: none; display: block;
    transition: var(--transition); background: var(--white);
}
.room-stat-card:hover {
    border-color: var(--g200); background: var(--g50);
    transform: translateY(-2px); box-shadow: var(--shadow-sm);
    text-decoration: none;
}
.room-stat-label {
    display: flex; align-items: center; justify-content: space-between;
    font-size: .7rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .5px; color: var(--s400); margin-bottom: 10px;
}
.room-stat-badge { font-size: .62rem; font-weight: 600; padding: 2px 7px; border-radius: 100px; }
.rsb-green { background: var(--g100); color: var(--g700); }
.rsb-grey  { background: var(--s100); color: var(--s600); }
.rsb-light { background: var(--g50);  color: var(--g600); }
.room-stat-value {
    font-size: 2.1rem; font-weight: 700; color: var(--s900);
    line-height: 1; margin-bottom: 3px;
    font-family: var(--mono); letter-spacing: -.5px;
}
.room-stat-sub { font-size: .72rem; color: var(--s400); }
.occ-bar {
    height: 5px; background: var(--s100);
    border-radius: 3px; margin-top: 12px; overflow: hidden;
}
.occ-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--g600), var(--g400));
    border-radius: 3px; transition: width .8s cubic-bezier(.4,0,.2,1);
}

/* ══════════════════════════════════════════════
   MAIN GRID
══════════════════════════════════════════════ */
.db-main-grid {
    display: grid; grid-template-columns: 1fr 320px;
    gap: 20px; align-items: start;
}
@media(max-width:1100px){ .db-main-grid{ grid-template-columns:1fr; } }

/* ── Cards ── */
.db-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 18px; box-shadow: var(--shadow-sm);
}
.db-card:last-child { margin-bottom: 0; }
.db-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px; border-bottom: 1.5px solid var(--s100);
    flex-wrap: wrap; gap: 10px;
}
.db-card-title {
    display: flex; align-items: center; gap: 9px;
    font-size: .9rem; font-weight: 600; color: var(--s800); margin: 0;
}
.db-card-title-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.db-card-subtitle  { font-size: .72rem; color: var(--s400); margin-top: 2px; }
.db-card-actions   { display: flex; gap: 7px; flex-wrap: wrap; }
.db-card-body      { padding: 18px 22px; }
.db-card-footer    { padding: 12px 22px; border-top: 1.5px solid var(--s100); background: var(--surface); }

/* ── Buttons ── */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--r);
    font-size: .78rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}
.btn-db-icon {
    width: 30px; height: 30px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 7px; font-size: .78rem;
    background: var(--white); color: var(--s400);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
    font-family: var(--font);
}
.btn-db-icon:hover         { background: var(--s50);  color: var(--s900); border-color: var(--s300); text-decoration: none; }
.btn-db-icon-green:hover   { background: var(--g50);  color: var(--g600); border-color: var(--g200); }

/* ── Table ── */
.db-table { width: 100%; border-collapse: collapse; }
.db-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 10px 18px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.db-table tbody tr { border-bottom: 1px solid var(--s100); transition: var(--transition); }
.db-table tbody tr:last-child { border-bottom: none; }
.db-table tbody tr:hover  { background: var(--g50); }
.db-table tbody tr.row-new{ background: #f4fbf4; }
.db-table td { padding: 13px 18px; vertical-align: middle; }

.guest-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--g100); border: 2px solid var(--g200);
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; color: var(--g700); flex-shrink: 0;
    font-family: var(--mono);
}
.guest-name {
    font-size: .85rem; font-weight: 600; color: var(--s900);
    text-decoration: none; transition: var(--transition);
}
.guest-name:hover { color: var(--g600); text-decoration: none; }
.guest-sub { font-size: .7rem; color: var(--s400); margin-top: 2px; }

.room-link {
    font-size: .85rem; font-weight: 600; color: var(--s800);
    text-decoration: none; transition: var(--transition);
    font-family: var(--mono);
}
.room-link:hover { color: var(--g600); text-decoration: none; }
.room-type-label { font-size: .7rem; color: var(--s400); margin-top: 2px; }

.date-in {
    font-size: .78rem; color: var(--g600); font-weight: 600;
    display: flex; align-items: center; gap: 5px;
}
.date-out {
    font-size: .78rem; color: var(--s400);
    display: flex; align-items: center; gap: 5px; margin-top: 4px;
}
.checkout-today-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 600;
    background: var(--s100); color: var(--s600);
    border: 1px solid var(--s200); padding: 2px 7px;
    border-radius: 5px; margin-top: 5px;
}
.tag-new {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .62rem; font-weight: 600;
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200); padding: 1px 6px;
    border-radius: 4px; margin-left: 6px; vertical-align: middle;
}

.balance-paid {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--g50); color: var(--g600);
    border: 1.5px solid var(--g200); font-size: .72rem; font-weight: 600;
    padding: 4px 10px; border-radius: 7px;
}
.balance-due-amount {
    font-size: .9rem; font-weight: 700; color: var(--s800); font-family: var(--mono);
}
.balance-total { font-size: .7rem; color: var(--s400); margin-top: 2px; }
.btn-pay-now {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 10px; border-radius: 6px; font-size: .72rem; font-weight: 600;
    background: var(--g50); color: var(--g600); border: 1.5px solid var(--g200);
    text-decoration: none; margin-top: 5px; transition: var(--transition);
    font-family: var(--font);
}
.btn-pay-now:hover { background: var(--g600); color: white; border-color: var(--g600); text-decoration: none; }

.action-group { display: flex; gap: 4px; justify-content: flex-end; }

/* ── Dropdown ── */
.db-dropdown { position: relative; }
.db-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px);
    background: var(--white); border: 1.5px solid var(--s200);
    border-radius: var(--rl); box-shadow: var(--shadow-lg);
    min-width: 170px; z-index: 500; overflow: hidden;
    display: none; animation: scaleIn .15s ease; transform-origin: top right;
}
.db-dropdown-menu.open { display: block; }
.db-dropdown-item {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 14px; font-size: .8rem; font-weight: 500;
    color: var(--s700); text-decoration: none; transition: var(--transition);
    cursor: pointer; border: none; background: none;
    width: 100%; text-align: left; font-family: var(--font);
}
.db-dropdown-item:hover { background: var(--s50); color: var(--s900); text-decoration: none; }
.db-dropdown-item-danger       { color: var(--s500); }
.db-dropdown-item-danger:hover { background: var(--s50); color: var(--s700); }
.db-dropdown-divider { height: 1px; background: var(--s100); margin: 4px 0; }
.db-filter-dropdown { min-width: 190px; }

/* Empty state */
.db-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 56px 24px; text-align: center;
}
.db-empty-icon {
    width: 72px; height: 72px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 1.6rem; color: var(--g300);
    margin-bottom: 16px; border: 2px solid var(--g100);
}

/* ══════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════ */
.qci-form { display: flex; gap: 7px; margin-bottom: 12px; }
.qci-input {
    flex: 1; height: 36px; padding: 0 12px;
    border: 1.5px solid var(--s200); border-radius: var(--r);
    font-size: .8rem; outline: none; transition: var(--transition);
    color: var(--s900); background: var(--surface); font-family: var(--font);
}
.qci-input:focus {
    border-color: var(--g400); background: var(--white);
    box-shadow: 0 0 0 3px rgba(46,133,64,.08);
}
.qci-btn {
    width: 36px; height: 36px; flex-shrink: 0;
    background: var(--g600); color: white; border: none;
    border-radius: var(--r); display: flex; align-items: center;
    justify-content: center; cursor: pointer; font-size: .82rem;
    transition: var(--transition);
}
.qci-btn:hover { background: var(--g700); transform: translateY(-1px); }
.qci-cta { display: flex; flex-direction: column; gap: 7px; }

.btn-qci-outline {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: var(--r); font-size: .8rem; font-weight: 500;
    color: var(--g700); background: var(--white); border: 1.5px solid var(--g200);
    text-decoration: none; transition: var(--transition); font-family: var(--font);
}
.btn-qci-outline:hover { background: var(--g50); border-color: var(--g300); text-decoration: none; }

.btn-qci-solid {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: var(--r); font-size: .8rem; font-weight: 600;
    color: white; background: var(--g600); text-decoration: none;
    transition: var(--transition); box-shadow: 0 2px 10px rgba(46,133,64,.25);
    font-family: var(--font);
}
.btn-qci-solid:hover {
    background: var(--g700); color: white; text-decoration: none;
    transform: translateY(-1px); box-shadow: 0 4px 14px rgba(46,133,64,.3);
}

.qa-list { display: flex; flex-direction: column; gap: 3px; }
.qa-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 11px; border-radius: var(--r); font-size: .8rem; font-weight: 500;
    color: var(--s600); text-decoration: none; transition: var(--transition);
    background: var(--surface);
}
.qa-item:hover { background: var(--g50); color: var(--g700); padding-left: 15px; text-decoration: none; }
.qa-item-icon {
    width: 28px; height: 28px; flex-shrink: 0; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; background: var(--white); color: var(--s400);
    border: 1px solid var(--s200); transition: var(--transition);
}
.qa-item:hover .qa-item-icon { background: var(--g100); color: var(--g600); border-color: var(--g200); }

.status-list { display: flex; flex-direction: column; }
.status-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid var(--s100); font-size: .8rem;
}
.status-row:last-child { border-bottom: none; }
.status-key { color: var(--s400); }
.status-badge {
    font-size: .65rem; font-weight: 600; padding: 3px 9px;
    border-radius: 100px; text-transform: uppercase; letter-spacing: .3px;
}
.status-online  { background: var(--g100); color: var(--g700); }
.status-normal  { background: var(--s100); color: var(--s600); }
.status-info    { background: var(--g50);  color: var(--g600); }
.status-neutral { background: var(--s100); color: var(--s600); }

.btn-refresh-full {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; padding: 8px 0; margin-top: 12px;
    border-radius: var(--r); font-size: .78rem; font-weight: 500;
    color: var(--s500); background: var(--surface);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); font-family: var(--font);
}
.btn-refresh-full:hover { background: var(--s100); border-color: var(--s300); color: var(--s800); }
</style>

<div class="db-page">

    
    <div class="db-header anim-1">
        <div class="db-brand">
            <div class="db-brand-icon"><i class="fas fa-hotel"></i></div>
            <div>
                <h1 class="db-header-greeting">Bonjour, <em><?php echo e(auth()->user()->name); ?></em> 👋</h1>
                <p class="db-header-sub"><?php echo e(now()->translatedFormat('l d F Y')); ?> · Vue d'ensemble des opérations</p>
            </div>
        </div>
        <div class="db-header-right">
            <div class="db-clock-pill">
                <div class="db-clock-dot"></div>
                <span class="db-clock-time" id="db-clock"><?php echo e(now()->format('H:i')); ?></span>
                <span class="db-clock-date"><?php echo e(now()->translatedFormat('d M')); ?></span>
            </div>
            <a href="<?php echo e(route('frontend.home')); ?>" target="_blank" class="btn-site">
                <i class="fas fa-external-link-alt fa-xs"></i> Site web
            </a>
        </div>
    </div>

    
    <div class="stats-grid anim-2">

        <a href="<?php echo e(route('transaction.index')); ?>?status=active&date_filter=today" class="stat-card stat-card--primary">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                <span class="stat-card-badge">Aujourd'hui</span>
            </div>
            <div class="stat-card-value"><?php echo e($stats['activeGuests'] ?? 0); ?></div>
            <div class="stat-card-label">Clients actifs</div>
            <div class="stat-card-meta">
                <i class="fas fa-arrow-up fa-xs"></i>
                <?php echo e($stats['todayArrivals'] ?? 0); ?> nouvelle<?php echo e(($stats['todayArrivals'] ?? 0) > 1 ? 's' : ''); ?> arrivée<?php echo e(($stats['todayArrivals'] ?? 0) > 1 ? 's' : ''); ?>

            </div>
        </a>

        <a href="<?php echo e(route('transaction.index')); ?>?status=completed&date_filter=today" class="stat-card stat-card--secondary">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
                <span class="stat-card-badge">Terminé</span>
            </div>
            <div class="stat-card-value"><?php echo e($stats['completedToday'] ?? 0); ?></div>
            <div class="stat-card-label">Check-outs aujourd'hui</div>
            <div class="stat-card-meta"><i class="fas fa-check fa-xs"></i> Paiements soldés</div>
        </a>

        <a href="<?php echo e(route('transaction.index')); ?>?payment_status=pending" class="stat-card stat-card--muted">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
                <span class="stat-card-badge">Attention</span>
            </div>
            <div class="stat-card-value"><?php echo e($stats['pendingPayments'] ?? 0); ?></div>
            <div class="stat-card-label">Paiements en attente</div>
            <div class="stat-card-meta"><i class="fas fa-exclamation-circle fa-xs"></i> Suivi requis</div>
        </a>

        <a href="<?php echo e(route('transaction.index')); ?>?payment_status=urgent&due_within=24h" class="stat-card stat-card--neutral">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <span class="stat-card-badge">Urgent</span>
            </div>
            <div class="stat-card-value"><?php echo e($stats['urgentPayments'] ?? 0); ?></div>
            <div class="stat-card-label">Échéance &lt; 24h</div>
            <div class="stat-card-meta"><i class="fas fa-clock fa-xs"></i> Action immédiate</div>
        </a>

    </div>

    
    <div class="db-panel anim-3">
        <div class="db-panel-header">
            <h2 class="db-panel-title">
                <div class="db-panel-title-icon"><i class="fas fa-calendar-alt"></i></div>
                Arrivées &amp; Départs
            </h2>
        </div>
        <div class="db-panel-body">

            <div class="section-label">Prévisions</div>
            <div class="dates-grid">

                <a href="<?php echo e(route('checkin.index')); ?>?date=today" class="date-card date-card--today">
                    <div class="date-card-head">
                        <span class="date-card-name">Aujourd'hui</span>
                        <span class="date-card-pill"><?php echo e(now()->format('d M')); ?></span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico green"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                Arrivées
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['todayArrivals'] ?? 0); ?></span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                Départs
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['todayDepartures'] ?? 0); ?></span>
                        </div>
                    </div>
                </a>

                <a href="<?php echo e(route('checkin.index')); ?>?date=tomorrow" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">Demain</span>
                        <span class="date-card-pill"><?php echo e(now()->addDay()->format('d M')); ?></span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                Arrivées
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['tomorrowArrivals'] ?? 0); ?></span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                Départs
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['tomorrowDepartures'] ?? 0); ?></span>
                        </div>
                    </div>
                </a>

                <a href="<?php echo e(route('checkin.index')); ?>?date=day+2" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">J+2</span>
                        <span class="date-card-pill"><?php echo e(now()->addDays(2)->format('d M')); ?></span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                Arrivées
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['day2Arrivals'] ?? 0); ?></span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                Départs
                            </span>
                            <span class="date-card-row-val"><?php echo e($stats['day2Departures'] ?? 0); ?></span>
                        </div>
                    </div>
                </a>

            </div>

            <div class="section-label">Occupation des chambres</div>
            <div class="rooms-grid">

                <a href="<?php echo e(route('room.index')); ?>?status=available" class="room-stat-card">
                    <div class="room-stat-label">
                        Chambres libres
                        <span class="room-stat-badge rsb-green">Vacant</span>
                    </div>
                    <div class="room-stat-value"><?php echo e($stats['availableRooms'] ?? 0); ?></div>
                    <div class="room-stat-sub">sur <?php echo e($stats['totalRooms'] ?? 0); ?> chambres</div>
                </a>

                <a href="<?php echo e(route('room.index')); ?>?status=occupied" class="room-stat-card">
                    <div class="room-stat-label">
                        Chambres occupées
                        <span class="room-stat-badge rsb-grey">Occupé</span>
                    </div>
                    <div class="room-stat-value"><?php echo e($stats['occupiedRooms'] ?? 0); ?></div>
                    <div class="room-stat-sub">en ce moment</div>
                </a>

                <a href="<?php echo e(route('reports.index')); ?>" class="room-stat-card">
                    <div class="room-stat-label">
                        Taux d'occupation
                        <span class="room-stat-badge rsb-light">Aujourd'hui</span>
                    </div>
                    <div class="room-stat-value"><?php echo e($stats['occupancyRate'] ?? 0); ?>%</div>
                    <div class="occ-bar">
                        <div class="occ-fill" style="width:<?php echo e($stats['occupancyRate'] ?? 0); ?>%"></div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    
    <div class="db-main-grid">

        
        <div class="anim-4">
            <div class="db-card">
                <div class="db-card-header">
                    <div>
                        <h2 class="db-card-title">
                            <span class="db-card-title-dot" style="background:var(--g500)"></span>
                            Clients actifs
                        </h2>
                        <div class="db-card-subtitle"><?php echo e($transactions->count()); ?> client<?php echo e($transactions->count() > 1 ? 's' : ''); ?> en ce moment</div>
                    </div>
                    <div class="db-card-actions">
                        <button class="btn-db btn-db-ghost" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt fa-xs"></i> Actualiser
                        </button>
                        <div class="db-dropdown" id="filter-dropdown">
                            <button class="btn-db btn-db-ghost" onclick="toggleDropdown('filter-dropdown')">
                                <i class="fas fa-filter fa-xs"></i> Filtrer
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
                        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn-db btn-db-primary">
                            <i class="fas fa-plus fa-xs"></i> Nouveau client
                        </a>
                    </div>
                </div>

                <?php if($transactions->count() > 0): ?>
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
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $balance    = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                $isNew      = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                $isOut      = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                $initials   = strtoupper(substr($transaction->customer->name, 0, 2));
                                $balanceFmt = number_format($balance, 0, ',', ' ') . ' CFA';
                                $totalFmt   = number_format($transaction->getTotalPrice(), 0, ',', ' ') . ' CFA';
                            ?>
                            <tr class="<?php echo e($isNew ? 'row-new' : ''); ?>">

                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="guest-avatar"><?php echo e($initials); ?></div>
                                        <div>
                                            <div>
                                                <a href="<?php echo e(route('customer.show', $transaction->customer->id)); ?>" class="guest-name">
                                                    <?php echo e($transaction->customer->name); ?>

                                                </a>
                                                <?php if($isNew): ?><span class="tag-new"><i class="fas fa-star fa-xs"></i> Nouveau</span><?php endif; ?>
                                            </div>
                                            <div class="guest-sub"><?php echo e($transaction->customer->phone ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a href="<?php echo e(route('room.show', $transaction->room->id)); ?>" class="room-link">N° <?php echo e($transaction->room->number); ?></a>
                                    <div class="room-type-label"><?php echo e($transaction->room->type->name ?? 'Standard'); ?></div>
                                </td>

                                <td>
                                    <div class="date-in"><i class="fas fa-sign-in-alt fa-xs"></i> <?php echo e($transaction->check_in->format('d/m/Y')); ?></div>
                                    <div class="date-out"><i class="fas fa-sign-out-alt fa-xs"></i> <?php echo e($transaction->check_out->format('d/m/Y')); ?></div>
                                    <?php if($isOut): ?>
                                    <div class="checkout-today-tag"><i class="fas fa-exclamation-circle fa-xs"></i> Départ aujourd'hui</div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($balance <= 0): ?>
                                        <span class="balance-paid"><i class="fas fa-check fa-xs"></i> Soldé</span>
                                    <?php else: ?>
                                        <div class="balance-due-amount"><?php echo e($balanceFmt); ?></div>
                                        <div class="balance-total">Total : <?php echo e($totalFmt); ?></div>
                                        <a href="<?php echo e(route('transaction.payment.create', ['transaction' => $transaction->id])); ?>" class="btn-pay-now">
                                            <i class="fas fa-credit-card fa-xs"></i> Encaisser
                                        </a>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a href="<?php echo e(route('transaction.payment.create', ['transaction' => $transaction->id])); ?>"
                                           class="btn-db-icon btn-db-icon-green" title="Paiement">
                                            <i class="fas fa-money-bill-wave fa-xs"></i>
                                        </a>
                                        <a href="<?php echo e(route('transaction.show', ['transaction' => $transaction->id])); ?>"
                                           class="btn-db-icon" title="Détails">
                                            <i class="fas fa-eye fa-xs"></i>
                                        </a>
                                        <div class="db-dropdown" id="row-dd-<?php echo e($transaction->id); ?>">
                                            <button class="btn-db-icon" onclick="toggleDropdown('row-dd-<?php echo e($transaction->id); ?>')" title="Plus">
                                                <i class="fas fa-ellipsis-v fa-xs"></i>
                                            </button>
                                            <div class="db-dropdown-menu">
                                                <a class="db-dropdown-item" href="<?php echo e(route('transaction.edit', ['transaction' => $transaction->id])); ?>">
                                                    <i class="fas fa-edit fa-xs"></i> Modifier
                                                </a>
                                                <a class="db-dropdown-item" href="<?php echo e(route('transaction.invoice', ['transaction' => $transaction->id])); ?>">
                                                    <i class="fas fa-file-invoice fa-xs"></i> Facture
                                                </a>
                                                <?php if($transaction->canBeCancelled()): ?>
                                                <div class="db-dropdown-divider"></div>
                                                <button class="db-dropdown-item db-dropdown-item-danger"
                                                        onclick="confirmCancel('<?php echo e(route('transaction.cancel', ['transaction' => $transaction->id])); ?>', '<?php echo e($transaction->customer->name); ?>')">
                                                    <i class="fas fa-times fa-xs"></i> Annuler
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if(method_exists($transactions, 'links') && $transactions->hasPages()): ?>
                <div class="db-card-footer"><?php echo e($transactions->links()); ?></div>
                <?php endif; ?>

                <?php else: ?>
                <div class="db-empty">
                    <div class="db-empty-icon"><i class="fas fa-bed"></i></div>
                    <p style="font-size:.95rem;font-weight:600;color:var(--s700);margin-bottom:6px;">Aucun client actif</p>
                    <p style="font-size:.8rem;color:var(--s400);margin-bottom:18px;">Aucun client enregistré pour le moment</p>
                    <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn-db btn-db-primary">
                        <i class="fas fa-plus fa-xs"></i> Ajouter un client
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>

        
        <div class="anim-5">

            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--g500)"></span>
                        Check-in rapide
                    </h2>
                </div>
                <div class="db-card-body">
                    <p style="font-size:.78rem;color:var(--s400);margin-bottom:12px;">Vérifier une réservation existante</p>
                    <form action="<?php echo e(route('checkin.search')); ?>" method="GET" class="qci-form">
                        <input type="text" class="qci-input" name="search"
                               placeholder="Nom, chambre, ID…" value="<?php echo e(request('search')); ?>">
                        <button type="submit" class="qci-btn"><i class="fas fa-search fa-xs"></i></button>
                    </form>
                    <div class="qci-cta">
                        <a href="<?php echo e(route('checkin.index')); ?>" class="btn-qci-outline">
                            <i class="fas fa-list fa-xs"></i> Toutes les arrivées
                        </a>
                        <a href="<?php echo e(route('checkin.direct')); ?>" class="btn-qci-solid">
                            <i class="fas fa-user-plus fa-xs"></i> Check-in direct
                        </a>
                    </div>
                </div>
            </div>

            <div class="db-card anim-6">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--g300)"></span>
                        Actions rapides
                    </h2>
                </div>
                <div class="db-card-body" style="padding:12px 14px;">
                    <div class="qa-list">
                        <a href="<?php echo e(route('room.index')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-bed fa-xs"></i></span>
                            Gérer les chambres
                        </a>
                        <a href="<?php echo e(route('customer.index')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-users fa-xs"></i></span>
                            Clients
                        </a>
                        <a href="<?php echo e(route('checkin.index')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-calendar-check fa-xs"></i></span>
                            Dashboard check-in
                        </a>
                        <a href="<?php echo e(route('payments.index')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-money-bill-wave fa-xs"></i></span>
                            Paiements
                        </a>
                        <a href="<?php echo e(route('reports.index')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-chart-bar fa-xs"></i></span>
                            Rapports
                        </a>
                        <?php if(auth()->user()->isAdmin() || auth()->user()->role === 'Super'): ?>
                        <a href="<?php echo e(route('cashier.dashboard')); ?>" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-cash-register fa-xs"></i></span>
                            Caisse
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('frontend.home')); ?>" target="_blank" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-external-link-alt fa-xs"></i></span>
                            Visiter le site
                        </a>
                    </div>
                </div>
            </div>

            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--s300)"></span>
                        Statut système
                    </h2>
                </div>
                <div class="db-card-body">
                    <div class="status-list">
                        <div class="status-row">
                            <span class="status-key">Dernière mise à jour</span>
                            <span class="status-badge status-neutral" id="last-updated"><?php echo e(now()->format('H:i:s')); ?></span>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ── Horloge ── */
function tickClock() {
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    const el = document.getElementById('db-clock');
    if (el) el.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes());
    const lu = document.getElementById('last-updated');
    if (lu) lu.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
}
setInterval(tickClock, 1000);

/* ── Auto-refresh stats 30s ── */
setInterval(() => {
    fetch('<?php echo e(route("dashboard.stats")); ?>')
        .then(r => r.json())
        .then(d => { if (!d.success) return; })
        .catch(() => {});
}, 30000);

/* ── Dropdown ── */
function toggleDropdown(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const menu = el.querySelector('.db-dropdown-menu');
    if (!menu) return;
    const isOpen = menu.classList.contains('open');
    document.querySelectorAll('.db-dropdown-menu.open').forEach(m => m.classList.remove('open'));
    if (!isOpen) menu.classList.add('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.db-dropdown'))
        document.querySelectorAll('.db-dropdown-menu.open').forEach(m => m.classList.remove('open'));
});

/* ── Refresh ── */
function refreshDashboard() {
    document.querySelectorAll('[onclick="refreshDashboard()"]').forEach(b => {
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-spinner fa-spin fa-xs"></i> Chargement…';
    });
    setTimeout(() => location.reload(), 600);
}

/* ── Annulation ── */
function confirmCancel(url, name) {
    Swal.fire({
        title: 'Annuler la réservation ?',
        html: `Annuler la réservation de <strong>${name}</strong>.<br><small style="color:#9ba09b">Cette action est irréversible.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, annuler',
        cancelButtonText: 'Conserver',
        confirmButtonColor: '#545954',
        cancelButtonColor: '#1e6b2e',
    }).then(r => {
        if (!r.isConfirmed) return;
        const f = document.createElement('form');
        f.method = 'POST'; f.action = url; f.style.display = 'none';
        f.innerHTML = `<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                       <input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(f);
        f.submit();
    });
}
window.confirmCancel = confirmCancel;
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/dashboard/index.blade.php ENDPATH**/ ?>