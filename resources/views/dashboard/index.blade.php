@extends('template.master')
@section('title', 'Dashboard')
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   Dashboard — design épuré (maquette validée). Ne cible que les
   classes déjà présentes dans le markup : aucune donnée/route/i18n
   touchée. Accent = couleur de l'hôtel (--g*), thème clair & sombre.
   ═══════════════════════════════════════════════════════════════ */
.db-page {
  --card: #ffffff;
  --page: #f8faf9;
  --line: #e9edea;
  --line2: #dce2de;
  --ink: #181d1a;
  --ink2: #5c655f;
  --ink3: #98a19b;
  --tint: #f4f7f5;
  --ok: var(--g500);  --ok-t: #eaf3ec;
  --warn: #b7791f; --warn-t: #fbf1de;
  --bad: #b4342a;  --bad-t: #fbe9e7;
  --info: #3b6c8f; --info-t: #e7f0f6;

  --acc: var(--g600, var(--g500));
  --acc2: var(--g500, var(--g500));
  --acc-t: color-mix(in srgb, var(--g500, var(--g500)) 13%, var(--card));

  --r: 12px; --r-sm: 9px;
  --sh: 0 1px 2px rgba(20,40,30,.05);

  display: flex; flex-direction: column; gap: 18px;
  font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
  color: var(--ink);
  font-variant-numeric: tabular-nums;
}
html[data-theme="dark"] .db-page {
  --card: #161b18; --page: #0f1311; --line: #262e29; --line2: #323b35;
  --ink: #e9eeeb; --ink2: #97a29b; --ink3: #6e7872; --tint: #1b211d;
  --ok: #4fb268; --ok-t: #16241b; --warn: #d5a54a; --warn-t: #2a2314;
  --bad: #e27469; --bad-t: #2a1714; --info: #6fa8ce; --info-t: #10202a;
  --acc-t: color-mix(in srgb, var(--g500, #4fb268) 20%, var(--card));
  --sh: 0 1px 2px rgba(0,0,0,.3);
}

.db-page * { box-sizing: border-box; }

/* ── animations ── */
@keyframes dbfade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
.anim-1 { animation: dbfade .4s ease both; }
.anim-2 { animation: dbfade .4s .05s ease both; }
.anim-3 { animation: dbfade .4s .1s ease both; }
.anim-4 { animation: dbfade .4s .15s ease both; }
.anim-5 { animation: dbfade .4s .2s ease both; }
.anim-6 { animation: dbfade .4s .25s ease both; }
@media (prefers-reduced-motion: reduce) { .db-page [class^="anim-"] { animation: none; } }

.db-page a { text-decoration: none; color: inherit; }

/* ══════════ HEADER ══════════ */
.db-header {
  display: flex; align-items: center; justify-content: space-between;
  gap: 16px; flex-wrap: wrap;
}
.db-brand { display: flex; align-items: center; gap: 13px; }
.db-brand-icon {
  width: 42px; height: 42px; border-radius: 11px; flex: none;
  background: var(--acc-t); color: var(--acc);
  display: grid; place-items: center; font-size: 1.05rem;
}
.db-header-greeting { margin: 0; font-size: 1.25rem; font-weight: 680; letter-spacing: -.02em; }
.db-header-greeting em { font-style: normal; color: var(--acc); }
.db-header-sub { margin: 2px 0 0; font-size: .82rem; color: var(--ink3); }
.db-header-right { display: flex; align-items: center; gap: 10px; }
.db-clock-pill {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--card); border: 1px solid var(--line); border-radius: 20px;
  padding: 6px 13px; box-shadow: var(--sh);
}
.db-clock-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--ok); box-shadow: 0 0 0 3px var(--ok-t); }
.db-clock-time { font-weight: 680; font-size: .88rem; font-family: 'DM Mono', monospace; }
.db-clock-date { font-size: .74rem; color: var(--ink3); }
.btn-site {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--card); border: 1px solid var(--line); border-radius: 9px;
  padding: 8px 13px; font-size: .8rem; font-weight: 600; color: var(--ink2); box-shadow: var(--sh);
}
.btn-site:hover { border-color: var(--line2); color: var(--ink); }

/* ══════════ STAT CARDS ══════════ */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
@media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
  background: var(--card); border: 1px solid var(--line); border-radius: var(--r);
  padding: 16px; display: flex; flex-direction: column; gap: 9px;
  box-shadow: var(--sh); transition: border-color .15s, transform .15s;
}
.stat-card:hover { border-color: var(--line2); transform: translateY(-1px); }
.stat-card-head { display: flex; align-items: center; justify-content: space-between; }
.stat-card-icon {
  width: 34px; height: 34px; border-radius: 9px; display: grid; place-items: center;
  font-size: .95rem; background: var(--tint); color: var(--ink2);
}
.stat-card--primary   .stat-card-icon { background: var(--acc-t);  color: var(--acc); }
.stat-card--secondary .stat-card-icon { background: var(--ok-t);   color: var(--ok); }
.stat-card--muted     .stat-card-icon { background: var(--warn-t); color: var(--warn); }
.stat-card--neutral   .stat-card-icon { background: var(--bad-t);  color: var(--bad); }
.stat-card-badge {
  font-size: .64rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
  color: var(--ink3); background: var(--tint); border-radius: 20px; padding: 3px 9px;
}
.stat-card-value { font-size: 1.9rem; font-weight: 720; letter-spacing: -.02em; line-height: 1; }
.stat-card-label { font-size: .82rem; color: var(--ink2); font-weight: 500; }
.stat-card-meta { font-size: .72rem; color: var(--ink3); display: flex; align-items: center; gap: 6px; }
.stat-card--primary   .stat-card-meta i { color: var(--acc); }
.stat-card--secondary .stat-card-meta i { color: var(--ok); }
.stat-card--muted     .stat-card-meta i { color: var(--warn); }
.stat-card--neutral   .stat-card-meta i { color: var(--bad); }

/* ══════════ PANELS / CARDS ══════════ */
.db-panel, .db-card {
  background: var(--card); border: 1px solid var(--line); border-radius: var(--r);
  box-shadow: var(--sh); overflow: hidden;
}
.db-panel-header, .db-card-header {
  display: flex; align-items: center; justify-content: space-between; gap: 12px;
  padding: 15px 18px; border-bottom: 1px solid var(--line);
}
.db-panel-title, .db-card-title {
  display: flex; align-items: center; gap: 10px; margin: 0;
  font-size: .95rem; font-weight: 660; color: var(--ink);
}
.db-panel-title-icon {
  width: 28px; height: 28px; border-radius: 8px; display: grid; place-items: center;
  background: var(--acc-t); color: var(--acc); font-size: .8rem;
}
.db-card-title-dot { width: 8px; height: 8px; border-radius: 50%; flex: none; }
.db-card-subtitle { font-size: .76rem; color: var(--ink3); margin-top: 3px; }
.db-panel-body, .db-card-body { padding: 18px; }
.db-card-footer { padding: 12px 18px; border-top: 1px solid var(--line); }
.db-card-actions { display: flex; align-items: center; gap: 8px; }

.section-label {
  font-size: .68rem; text-transform: uppercase; letter-spacing: .07em;
  color: var(--ink3); font-weight: 700; margin: 4px 0 12px;
}
.db-panel-body .section-label:not(:first-child) { margin-top: 22px; }

/* ── date cards (arrivées/départs) ── */
.dates-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width: 720px) { .dates-grid { grid-template-columns: 1fr; } }
.date-card {
  display: block; background: var(--card); border: 1px solid var(--line);
  border-radius: var(--r-sm); padding: 14px; transition: border-color .15s, transform .15s;
}
.date-card:hover { border-color: var(--line2); transform: translateY(-1px); }
.date-card--today { border-color: color-mix(in srgb, var(--acc) 35%, var(--line)); background: var(--acc-t); }
.date-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.date-card-name { font-weight: 660; font-size: .88rem; }
.date-card-pill {
  font-size: .68rem; font-weight: 600; color: var(--ink3);
  background: var(--card); border: 1px solid var(--line); border-radius: 20px; padding: 2px 9px;
  font-family: 'DM Mono', monospace;
}
.date-card--today .date-card-pill { color: var(--acc); border-color: color-mix(in srgb, var(--acc) 30%, var(--line)); }
.date-card-rows { display: flex; flex-direction: column; gap: 8px; }
.date-card-row { display: flex; align-items: center; justify-content: space-between; }
.date-card-row-label { display: flex; align-items: center; gap: 8px; font-size: .8rem; color: var(--ink2); }
.row-ico {
  width: 22px; height: 22px; border-radius: 6px; display: grid; place-items: center;
  background: var(--tint); color: var(--ink3); font-size: .62rem;
}
.row-ico.green { background: var(--ok-t); color: var(--ok); }
.date-card-row-val { font-weight: 700; font-size: 1rem; font-family: 'DM Mono', monospace; }

/* ── room stats ── */
.rooms-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width: 720px) { .rooms-grid { grid-template-columns: 1fr; } }
.room-stat-card {
  display: block; background: var(--card); border: 1px solid var(--line);
  border-radius: var(--r-sm); padding: 15px; transition: border-color .15s, transform .15s;
}
.room-stat-card:hover { border-color: var(--line2); transform: translateY(-1px); }
.room-stat-label {
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
  font-size: .8rem; color: var(--ink2); font-weight: 500; margin-bottom: 10px;
}
.room-stat-badge { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; padding: 2px 8px; border-radius: 20px; }
.rsb-green { background: var(--ok-t); color: var(--ok); }
.rsb-grey { background: var(--tint); color: var(--ink2); }
.rsb-light { background: var(--acc-t); color: var(--acc); }
.room-stat-value { font-size: 1.7rem; font-weight: 720; letter-spacing: -.02em; line-height: 1; }
.room-stat-sub { font-size: .73rem; color: var(--ink3); margin-top: 6px; }
.occ-bar { margin-top: 12px; height: 7px; background: var(--tint); border-radius: 20px; overflow: hidden; }
.occ-fill { height: 100%; background: var(--acc); border-radius: 20px; transition: width .4s ease; }

/* ══════════ MAIN GRID ══════════ */
.db-main-grid { display: grid; grid-template-columns: minmax(0, 1fr) 400px; gap: 16px; align-items: start; }
@media (max-width: 1000px) { .db-main-grid { grid-template-columns: 1fr; } }

/* ── buttons ── */
.btn-db {
  display: inline-flex; align-items: center; gap: 7px; border-radius: 8px;
  padding: 7px 13px; font-size: .78rem; font-weight: 600; cursor: pointer;
  border: 1px solid transparent; font-family: inherit; transition: all .15s; white-space: nowrap;
}
.btn-db-primary { background: var(--acc); color: #fff; }
.btn-db-primary:hover { background: var(--acc2); color: #fff; }
.btn-db-ghost { background: var(--card); color: var(--ink2); border-color: var(--line); }
.btn-db-ghost:hover { background: var(--tint); color: var(--ink); border-color: var(--line2); }
.btn-db-icon, .btn-db-icon-green {
  width: 32px; height: 32px; padding: 0; justify-content: center; border-radius: 8px;
  background: var(--tint); border: 1px solid var(--line2); color: var(--ink);
}
.btn-db-icon-green { background: var(--acc-t); border-color: transparent; color: var(--acc); }
.btn-db-icon:hover { color: var(--acc); border-color: var(--acc); }
/* Contraste renforcé en mode sombre : les boutons ressortent de la carte. */
html[data-theme="dark"] .db-page .btn-db-icon {
  background: #232b26; border-color: #3a453e; color: #d3dad5;
}
html[data-theme="dark"] .db-page .btn-db-icon:hover { color: var(--acc); border-color: var(--acc); }
.action-group { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; justify-content: flex-end; }
.btn-refresh-full {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px; width: 100%;
  background: var(--card); border: 1px solid var(--line); border-radius: 9px;
  padding: 9px; font-size: .8rem; font-weight: 600; color: var(--ink2); cursor: pointer; font-family: inherit;
}
.btn-refresh-full:hover { background: var(--tint); border-color: var(--line2); color: var(--ink); }

/* ── dropdown ── */
.db-dropdown { position: relative; }
.db-dropdown-menu {
  position: absolute; right: 0; top: calc(100% + 6px); min-width: 210px; z-index: 40;
  background: var(--card); border: 1px solid var(--line); border-radius: var(--r-sm);
  box-shadow: 0 10px 30px rgba(0,0,0,.12); padding: 6px; display: none;
}
.db-dropdown.open .db-dropdown-menu, .db-dropdown-menu.show { display: block; }
.db-dropdown-item {
  display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 7px;
  font-size: .8rem; color: var(--ink2); cursor: pointer;
}
.db-dropdown-item:hover { background: var(--tint); color: var(--ink); }
.db-dropdown-item i { color: var(--ink3); width: 14px; }
.db-dropdown-item-danger { color: var(--bad); }
.db-dropdown-item-danger:hover { background: var(--bad-t); color: var(--bad); }
.db-dropdown-divider { height: 1px; background: var(--line); margin: 6px 4px; }

/* ── table ── */
.db-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.db-table th {
  text-align: left; font-size: .66rem; text-transform: uppercase; letter-spacing: .05em;
  color: var(--ink3); font-weight: 700; padding: 10px 14px; border-bottom: 1px solid var(--line);
}
.db-table td { padding: 16px 14px; border-bottom: 1px solid var(--line); vertical-align: middle; }
/* Cellule solde : badge + total + bouton bien aérés et alignés */
.balance-cell { display: inline-flex; flex-direction: column; align-items: flex-start; gap: 9px; }
.balance-cell .balance-due-amount { margin-bottom: 1px; }
.balance-cell .btn-pay-now { margin-top: 2px; }
.db-table tbody tr:last-child td { border-bottom: 0; }
.db-table tbody tr:hover { background: var(--tint); }
.guest-avatar {
  width: 34px; height: 34px; border-radius: 9px; flex: none; display: grid; place-items: center;
  background: var(--acc-t); color: var(--acc); font-weight: 700; font-size: .8rem;
}
.guest-name { font-weight: 640; }
.guest-sub, .room-type-label { font-size: .73rem; color: var(--ink3); }
.room-link { font-weight: 680; color: var(--ink); }
.date-in, .date-out { font-family: 'DM Mono', monospace; font-size: .8rem; }

/* ── status / balance chips ── */
.status-badge, .status-info, .status-key, .status-neutral, .status-normal,
.status-online, .tag-new, .checkout-today-tag, .balance-paid, .balance-due-amount {
  display: inline-flex; align-items: center; gap: 5px; font-size: .7rem; font-weight: 650;
  padding: 3px 10px; border-radius: 20px; white-space: nowrap;
}
.status-online, .status-normal { background: var(--acc-t); color: var(--acc); }
.status-info { background: var(--info-t); color: var(--info); }
.status-neutral, .status-key { background: var(--tint); color: var(--ink2); }
.tag-new { background: var(--info-t); color: var(--info); }
/* Surlignage d'une ligne « nouvelle arrivée » : reste une vraie ligne de table */
.db-table tr.row-new td { background: color-mix(in srgb, var(--info) 8%, var(--card)); }
.db-table tr.row-new td:first-child { box-shadow: inset 3px 0 0 var(--info); }
.checkout-today-tag { background: var(--warn-t); color: var(--warn); }
.balance-paid { background: var(--ok-t); color: var(--ok); }
.balance-due-amount { background: var(--bad-t); color: var(--bad); }
.balance-total { font-weight: 600; font-family: 'DM Mono', monospace; font-size: .74rem; color: var(--ink3); }
.status-list, .status-row { display: flex; align-items: center; gap: 8px; }

/* ── empty state ── */
.db-empty { text-align: center; padding: 40px 20px; color: var(--ink3); }
.db-empty-icon {
  width: 56px; height: 56px; border-radius: 50%; background: var(--tint); color: var(--ink3);
  display: grid; place-items: center; font-size: 1.3rem; margin: 0 auto 12px;
}

/* ── quick check-in / quick actions ── */
.qa-list { display: flex; flex-direction: column; gap: 8px; }
.qa-item {
  display: flex; align-items: center; gap: 11px; padding: 11px 12px;
  background: var(--card); border: 1px solid var(--line); border-radius: var(--r-sm);
  cursor: pointer; transition: border-color .15s, transform .15s;
}
.qa-item:hover { border-color: var(--line2); transform: translateY(-1px); }
.qa-item-icon {
  width: 34px; height: 34px; border-radius: 9px; flex: none; display: grid; place-items: center;
  background: var(--acc-t); color: var(--acc); font-size: .9rem;
}
.qci-form { display: flex; gap: 8px; }
.qci-input {
  flex: 1; padding: 9px 12px; border: 1px solid var(--line); border-radius: 8px;
  background: var(--card); color: var(--ink); font: inherit; font-size: .82rem;
}
.qci-input:focus { outline: none; border-color: var(--acc); box-shadow: 0 0 0 3px var(--acc-t); }
.qci-btn, .qci-cta, .btn-qci-solid {
  display: inline-flex; align-items: center; gap: 7px; background: var(--acc); color: #fff;
  border: 0; border-radius: 8px; padding: 9px 15px; font-weight: 650; font-size: .82rem; cursor: pointer; font-family: inherit;
}
.qci-btn:hover, .qci-cta:hover, .btn-qci-solid:hover { background: var(--acc2); }
.btn-qci-outline {
  display: inline-flex; align-items: center; gap: 7px; background: var(--card); color: var(--ink2);
  border: 1px solid var(--line); border-radius: 8px; padding: 9px 15px; font-weight: 600; font-size: .82rem; cursor: pointer; font-family: inherit;
}
.btn-qci-outline:hover { background: var(--tint); color: var(--ink); }
.btn-pay-now {
  display: inline-flex; align-items: center; gap: 6px; background: var(--acc); color: #fff;
  border: 0; border-radius: 8px; padding: 6px 12px; font-weight: 650; font-size: .74rem; cursor: pointer; font-family: inherit;
}
.btn-pay-now:hover { background: var(--acc2); }

/* pagination (Laravel) */
.db-page .pagination { gap: 4px; }
.db-page .page-link {
  border-radius: 8px; border: 1px solid var(--line); color: var(--ink2);
  background: var(--card); margin: 0 2px; font-size: .8rem;
}
.db-page .page-item.active .page-link { background: var(--acc); border-color: var(--acc); color: #fff; }
</style>

<div class="db-page">

    {{-- ─── HEADER ─────────────────────────────── --}}
    <div class="db-header anim-1">
        <div class="db-brand">
            <div class="db-brand-icon"><i class="fas fa-hotel"></i></div>
            <div>
                <h1 class="db-header-greeting">{{ __('dashboard.greeting') }}, <em>{{ auth()->user()->name }}</em> 👋</h1>
                <p class="db-header-sub">{{ now()->translatedFormat('l d F Y') }} · {{ __('dashboard.overview') }}</p>
            </div>
        </div>
        <div class="db-header-right">
            <div class="db-clock-pill">
                <div class="db-clock-dot"></div>
                <span class="db-clock-time" id="db-clock">{{ now()->format('H:i') }}</span>
                <span class="db-clock-date">{{ now()->translatedFormat('d M') }}</span>
            </div>
            <a href="{{ isset($currentHotel) && $currentHotel ? $currentHotel->publicUrl() : route('frontend.home') }}" target="_blank" class="btn-site">
                <i class="fas fa-external-link-alt fa-xs"></i> {{ __('dashboard.website') }}
            </a>
        </div>
    </div>

    {{-- ─── STAT CARDS ──────────────────────────── --}}
    <div class="stats-grid anim-2">

        <a href="{{ route('transaction.index') }}?status=active&date_filter=today" class="stat-card stat-card--primary">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                <span class="stat-card-badge">{{ __('dashboard.today_badge') }}</span>
            </div>
            <div class="stat-card-value">{{ $stats['activeGuests'] ?? 0 }}</div>
            <div class="stat-card-label">{{ __('dashboard.active_guests') }}</div>
            <div class="stat-card-meta">
                <i class="fas fa-arrow-up fa-xs"></i>
                {{ __('dashboard.new_arrivals', ['count' => $stats['todayArrivals'] ?? 0]) }}
            </div>
        </a>

        <a href="{{ route('transaction.index') }}?status=completed&date_filter=today" class="stat-card stat-card--secondary">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
                <span class="stat-card-badge">{{ __('dashboard.completed_badge') }}</span>
            </div>
            <div class="stat-card-value">{{ $stats['completedToday'] ?? 0 }}</div>
            <div class="stat-card-label">{{ __('dashboard.checkouts_today') }}</div>
            <div class="stat-card-meta"><i class="fas fa-check fa-xs"></i> {{ __('dashboard.payments_settled') }}</div>
        </a>

        <a href="{{ route('transaction.index') }}?payment_status=pending" class="stat-card stat-card--muted">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
                <span class="stat-card-badge">{{ __('dashboard.attention_badge') }}</span>
            </div>
            <div class="stat-card-value">{{ $stats['pendingPayments'] ?? 0 }}</div>
            <div class="stat-card-label">{{ __('dashboard.pending_payments') }}</div>
            <div class="stat-card-meta"><i class="fas fa-exclamation-circle fa-xs"></i> {{ __('dashboard.follow_up_required') }}</div>
        </a>

        <a href="{{ route('transaction.index') }}?payment_status=urgent&due_within=24h" class="stat-card stat-card--neutral">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <span class="stat-card-badge">{{ __('dashboard.urgent_badge') }}</span>
            </div>
            <div class="stat-card-value">{{ $stats['urgentPayments'] ?? 0 }}</div>
            <div class="stat-card-label">{!! __('dashboard.due_under_24h') !!}</div>
            <div class="stat-card-meta"><i class="fas fa-clock fa-xs"></i> {{ __('dashboard.immediate_action') }}</div>
        </a>

    </div>

    {{-- ─── PANEL ARRIVÉES & DÉPARTS ───────────────── --}}
    <div class="db-panel anim-3">
        <div class="db-panel-header">
            <h2 class="db-panel-title">
                <div class="db-panel-title-icon"><i class="fas fa-calendar-alt"></i></div>
                {{ __('dashboard.arrivals_departures') }}
            </h2>
        </div>
        <div class="db-panel-body">

            <div class="section-label">{{ __('dashboard.forecast') }}</div>
            <div class="dates-grid">

                <a href="{{ route('checkin.index') }}?date=today" class="date-card date-card--today">
                    <div class="date-card-head">
                        <span class="date-card-name">{{ __('dashboard.today') }}</span>
                        <span class="date-card-pill">{{ now()->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico green"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                {{ __('dashboard.arrivals') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['todayArrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                {{ __('dashboard.departures') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['todayDepartures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('checkin.index') }}?date=tomorrow" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">{{ __('dashboard.tomorrow') }}</span>
                        <span class="date-card-pill">{{ now()->addDay()->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                {{ __('dashboard.arrivals') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['tomorrowArrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                {{ __('dashboard.departures') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['tomorrowDepartures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('checkin.index') }}?date=day+2" class="date-card">
                    <div class="date-card-head">
                        <span class="date-card-name">{{ __('dashboard.day_2') }}</span>
                        <span class="date-card-pill">{{ now()->addDays(2)->format('d M') }}</span>
                    </div>
                    <div class="date-card-rows">
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-in-alt fa-xs"></i></span>
                                {{ __('dashboard.arrivals') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['day2Arrivals'] ?? 0 }}</span>
                        </div>
                        <div class="date-card-row">
                            <span class="date-card-row-label">
                                <span class="row-ico"><i class="fas fa-sign-out-alt fa-xs"></i></span>
                                {{ __('dashboard.departures') }}
                            </span>
                            <span class="date-card-row-val">{{ $stats['day2Departures'] ?? 0 }}</span>
                        </div>
                    </div>
                </a>

            </div>

            <div class="section-label">{{ __('dashboard.room_occupancy') }}</div>
            <div class="rooms-grid">

                <a href="{{ route('room.index') }}?status=available" class="room-stat-card">
                    <div class="room-stat-label">
                        {{ __('dashboard.free_rooms') }}
                        <span class="room-stat-badge rsb-green">{{ __('dashboard.vacant') }}</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['availableRooms'] ?? 0 }}</div>
                    <div class="room-stat-sub">{{ __('dashboard.out_of_rooms', ['count' => $stats['totalRooms'] ?? 0]) }}</div>
                </a>

                <a href="{{ route('room.index') }}?status=occupied" class="room-stat-card">
                    <div class="room-stat-label">
                        {{ __('dashboard.occupied_rooms') }}
                        <span class="room-stat-badge rsb-grey">{{ __('dashboard.occupied') }}</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['occupiedRooms'] ?? 0 }}</div>
                    <div class="room-stat-sub">{{ __('dashboard.right_now') }}</div>
                </a>

                <a href="{{ route('reports.index') }}" class="room-stat-card">
                    <div class="room-stat-label">
                        {{ __('dashboard.occupancy_rate') }}
                        <span class="room-stat-badge rsb-light">{{ __('dashboard.today_badge') }}</span>
                    </div>
                    <div class="room-stat-value">{{ $stats['occupancyRate'] ?? 0 }}%</div>
                    <div class="occ-bar">
                        <div class="occ-fill" style="width:{{ $stats['occupancyRate'] ?? 0 }}%"></div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    {{-- ─── MAIN GRID ──────────────────────────── --}}
    <div class="db-main-grid">

        {{-- LEFT : Table clients actifs --}}
        <div class="anim-4">
            <div class="db-card">
                <div class="db-card-header">
                    <div>
                        <h2 class="db-card-title">
                            <span class="db-card-title-dot" style="background:var(--g500)"></span>
                            {{ __('dashboard.active_guests_title') }}
                        </h2>
                        <div class="db-card-subtitle">{{ __('dashboard.guests_count', ['count' => $transactions->count()]) }}</div>
                    </div>
                    <div class="db-card-actions">
                        <button class="btn-db btn-db-ghost" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt fa-xs"></i> {{ __('dashboard.refresh') }}
                        </button>
                        <div class="db-dropdown" id="filter-dropdown">
                            <button class="btn-db btn-db-ghost" onclick="toggleDropdown('filter-dropdown')">
                                <i class="fas fa-filter fa-xs"></i> {{ __('dashboard.filter') }}
                            </button>
                            <div class="db-dropdown-menu db-filter-dropdown">
                                <a class="db-dropdown-item" href="?status=active"><i class="fas fa-user-check fa-xs"></i> {{ __('dashboard.filter_active_only') }}</a>
                                <a class="db-dropdown-item" href="?status=reservation"><i class="fas fa-calendar fa-xs"></i> {{ __('dashboard.filter_reservations') }}</a>
                                <a class="db-dropdown-item" href="?payment_status=pending"><i class="fas fa-clock fa-xs"></i> {{ __('dashboard.filter_pending_payments') }}</a>
                                <div class="db-dropdown-divider"></div>
                                <a class="db-dropdown-item" href="?date_filter=today"><i class="fas fa-sun fa-xs"></i> {{ __('dashboard.filter_today') }}</a>
                                <a class="db-dropdown-item" href="?date_filter=tomorrow"><i class="fas fa-arrow-right fa-xs"></i> {{ __('dashboard.filter_tomorrow') }}</a>
                                <a class="db-dropdown-item" href="?date_filter=this_week"><i class="fas fa-calendar-week fa-xs"></i> {{ __('dashboard.filter_this_week') }}</a>
                                <a class="db-dropdown-item" href="?date_filter=all"><i class="fas fa-list fa-xs"></i> {{ __('dashboard.filter_all_dates') }}</a>
                            </div>
                        </div>
                        <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn-db btn-db-primary">
                            <i class="fas fa-plus fa-xs"></i> {{ __('dashboard.new_client') }}
                        </a>
                    </div>
                </div>

                @if($transactions->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="db-table">
                        <thead>
                            <tr>
                                <th>{{ __('dashboard.col_guest') }}</th>
                                <th>{{ __('dashboard.col_room') }}</th>
                                <th>{{ __('dashboard.col_dates') }}</th>
                                <th>{{ __('dashboard.col_balance') }}</th>
                                <th style="text-align:right">{{ __('dashboard.col_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            @php
                                $balance    = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                $isNew      = \Carbon\Carbon::parse($transaction->check_in)->isToday();
                                $isOut      = \Carbon\Carbon::parse($transaction->check_out)->isToday();
                                $initials   = strtoupper(substr($transaction->customer->name, 0, 2));
                                $balanceFmt = number_format($balance, 0, ',', ' ') . ' CFA';
                                $totalFmt   = number_format($transaction->getTotalPrice(), 0, ',', ' ') . ' CFA';
                            @endphp
                            <tr class="{{ $isNew ? 'row-new' : '' }}">

                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="guest-avatar">{{ $initials }}</div>
                                        <div>
                                            <div>
                                                <a href="{{ route('customer.show', $transaction->customer->id) }}" class="guest-name">
                                                    {{ $transaction->customer->name }}
                                                </a>
                                                @if($isNew)<span class="tag-new"><i class="fas fa-star fa-xs"></i> {{ __('dashboard.new_tag') }}</span>@endif
                                            </div>
                                            <div class="guest-sub">{{ $transaction->customer->phone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{ route('room.show', $transaction->room->id) }}" class="room-link">N° {{ $transaction->room->number }}</a>
                                    <div class="room-type-label">{{ $transaction->room->type->name ?? 'Standard' }}</div>
                                </td>

                                <td>
                                    <div class="date-in"><i class="fas fa-sign-in-alt fa-xs"></i> {{ $transaction->check_in->format('d/m/Y') }}</div>
                                    <div class="date-out"><i class="fas fa-sign-out-alt fa-xs"></i> {{ $transaction->check_out->format('d/m/Y') }}</div>
                                    @if($isOut)
                                    <div class="checkout-today-tag"><i class="fas fa-exclamation-circle fa-xs"></i> {{ __('dashboard.departure_today') }}</div>
                                    @endif
                                </td>

                                <td>
                                    @if($balance <= 0)
                                        <span class="balance-paid"><i class="fas fa-check fa-xs"></i> {{ __('dashboard.settled') }}</span>
                                    @else
                                        <div class="balance-cell">
                                            <div class="balance-due-amount">{{ $balanceFmt }}</div>
                                            <div class="balance-total">{{ __('dashboard.total_label', ['amount' => $totalFmt]) }}</div>
                                            <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" class="btn-pay-now">
                                                <i class="fas fa-credit-card fa-xs"></i> {{ __('dashboard.collect') }}
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}"
                                           class="btn-db-icon btn-db-icon-green" title="Paiement">
                                            <i class="fas fa-money-bill-wave fa-xs"></i>
                                        </a>
                                        <a href="{{ route('transaction.show', ['transaction' => $transaction->id]) }}"
                                           class="btn-db-icon" title="Détails">
                                            <i class="fas fa-eye fa-xs"></i>
                                        </a>
                                        <div class="db-dropdown" id="row-dd-{{ $transaction->id }}">
                                            <button class="btn-db-icon" onclick="toggleDropdown('row-dd-{{ $transaction->id }}')" title="Plus">
                                                <i class="fas fa-ellipsis-v fa-xs"></i>
                                            </button>
                                            <div class="db-dropdown-menu">
                                                <a class="db-dropdown-item" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">
                                                    <i class="fas fa-edit fa-xs"></i> {{ __('dashboard.edit') }}
                                                </a>
                                                <a class="db-dropdown-item" href="{{ route('transaction.invoice', ['transaction' => $transaction->id]) }}">
                                                    <i class="fas fa-file-invoice fa-xs"></i> {{ __('dashboard.invoice') }}
                                                </a>
                                                @php
                                                    $ciHotel = auth()->user()->hotel;
                                                    $isPre = in_array($transaction->status, ['reservation','reserved_waiting']) && ! $transaction->preCheckinDone();
                                                    $ciWa = ($ciHotel && $isPre && optional($transaction->customer)->phone)
                                                        ? \App\Support\GuestMessages::link($transaction->customer->phone, \App\Support\GuestMessages::preCheckinInvite($ciHotel, $transaction)) : null;
                                                @endphp
                                                @if($isPre)
                                                <a class="db-dropdown-item" href="{{ $ciWa ?? route('transaction.show', ['transaction' => $transaction->id]) }}" @if($ciWa) target="_blank" rel="noopener" @endif>
                                                    <i class="fas fa-id-card fa-xs"></i> Pré-check-in
                                                </a>
                                                @endif
                                                @if($transaction->canBeCancelled())
                                                <div class="db-dropdown-divider"></div>
                                                <button class="db-dropdown-item db-dropdown-item-danger"
                                                        onclick="confirmCancel('{{ route('transaction.cancel', ['transaction' => $transaction->id]) }}', '{{ $transaction->customer->name }}')">
                                                    <i class="fas fa-times fa-xs"></i> {{ __('dashboard.cancel') }}
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
                <div class="db-card-footer">{{ $transactions->links() }}</div>
                @endif

                @else
                <div class="db-empty">
                    <div class="db-empty-icon"><i class="fas fa-bed"></i></div>
                    <p style="font-size:.95rem;font-weight:600;color:var(--s700);margin-bottom:6px;">{{ __('dashboard.no_active_guests') }}</p>
                    <p style="font-size:.8rem;color:var(--s400);margin-bottom:18px;">{{ __('dashboard.no_guests_recorded') }}</p>
                    <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn-db btn-db-primary">
                        <i class="fas fa-plus fa-xs"></i> {{ __('dashboard.add_guest') }}
                    </a>
                </div>
                @endif

            </div>
        </div>

        {{-- RIGHT : Sidebar --}}
        <div class="anim-5">

            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--g500)"></span>
                        {{ __('dashboard.quick_checkin') }}
                    </h2>
                </div>
                <div class="db-card-body">
                    <p style="font-size:.78rem;color:var(--s400);margin-bottom:12px;">{{ __('dashboard.check_existing') }}</p>
                    <form action="{{ route('checkin.search') }}" method="GET" class="qci-form">
                        <input type="text" class="qci-input" name="search"
                               placeholder="{{ __('dashboard.search_placeholder') }}" value="{{ request('search') }}">
                        <button type="submit" class="qci-btn"><i class="fas fa-search fa-xs"></i></button>
                    </form>
                    <div class="qci-cta">
                        <a href="{{ route('checkin.index') }}" class="btn-qci-outline">
                            <i class="fas fa-list fa-xs"></i> {{ __('dashboard.all_arrivals') }}
                        </a>
                        <a href="{{ route('checkin.direct') }}" class="btn-qci-solid">
                            <i class="fas fa-user-plus fa-xs"></i> {{ __('dashboard.direct_checkin') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="db-card anim-6">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--g300)"></span>
                        {{ __('dashboard.quick_actions') }}
                    </h2>
                </div>
                <div class="db-card-body" style="padding:12px 14px;">
                    <div class="qa-list">
                        <a href="{{ route('room.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-bed fa-xs"></i></span>
                            {{ __('dashboard.manage_rooms') }}
                        </a>
                        <a href="{{ route('customer.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-users fa-xs"></i></span>
                            {{ __('dashboard.guests_link') }}
                        </a>
                        <a href="{{ route('checkin.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-calendar-check fa-xs"></i></span>
                            {{ __('dashboard.checkin_dashboard') }}
                        </a>
                        <a href="{{ route('payments.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-money-bill-wave fa-xs"></i></span>
                            {{ __('dashboard.payments_link') }}
                        </a>
                        <a href="{{ route('reports.index') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-chart-bar fa-xs"></i></span>
                            {{ __('dashboard.reports_link') }}
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->role === 'Super')
                        <a href="{{ route('cashier.dashboard') }}" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-cash-register fa-xs"></i></span>
                            {{ __('dashboard.cashier_link') }}
                        </a>
                        @endif
                        <a href="{{ isset($currentHotel) && $currentHotel ? $currentHotel->publicUrl() : route('frontend.home') }}" target="_blank" class="qa-item">
                            <span class="qa-item-icon"><i class="fas fa-external-link-alt fa-xs"></i></span>
                            {{ __('dashboard.visit_website') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="db-card">
                <div class="db-card-header">
                    <h2 class="db-card-title">
                        <span class="db-card-title-dot" style="background:var(--s300)"></span>
                        {{ __('dashboard.system_status') }}
                    </h2>
                </div>
                <div class="db-card-body">
                    <div class="status-list">
                        <div class="status-row">
                            <span class="status-key">{{ __('dashboard.last_updated') }}</span>
                            <span class="status-badge status-neutral" id="last-updated">{{ now()->format('H:i:s') }}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">{{ __('dashboard.active_sessions') }}</span>
                            <span class="status-badge status-info">1</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">{{ __('dashboard.database') }}</span>
                            <span class="status-badge status-online">{{ __('dashboard.online') }}</span>
                        </div>
                        <div class="status-row">
                            <span class="status-key">{{ __('dashboard.memory') }}</span>
                            <span class="status-badge status-normal">{{ __('dashboard.normal') }}</span>
                        </div>
                    </div>
                    <button class="btn-refresh-full" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt fa-xs"></i> {{ __('dashboard.refresh_dashboard') }}
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
    fetch('{{ route("dashboard.stats") }}')
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
        b.innerHTML = '<i class="fas fa-spinner fa-spin fa-xs"></i> {{ __('dashboard.loading') }}';
    });
    setTimeout(() => location.reload(), 600);
}

/* ── Annulation ── */
function confirmCancel(url, name) {
    Swal.fire({
        title: '{{ __("dashboard.cancel_reservation_title") }}',
        html: `{!! __("dashboard.cancel_reservation_text", ["name" => "__NAME__"]) !!}`.replace('__NAME__', name),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '{{ __("dashboard.confirm_cancel") }}',
        cancelButtonText: '{{ __("dashboard.keep_reservation") }}',
        confirmButtonColor: '#545954',
        cancelButtonColor: 'var(--g600)',
    }).then(r => {
        if (!r.isConfirmed) return;
        const f = document.createElement('form');
        f.method = 'POST'; f.action = url; f.style.display = 'none';
        f.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                       <input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(f);
        f.submit();
    });
}
window.confirmCancel = confirmCancel;
</script>
@endsection