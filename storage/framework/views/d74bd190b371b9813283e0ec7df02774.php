

<?php $__env->startSection('title', 'Chambre ' . $room->number); ?>

<?php $__env->startPush('styles'); ?>
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
    /* COULEURS DE STATUT */
    --blue: #3b82f6;
    --blue-light: #dbeafe;
    --amber: #f59e0b;
    --amber-light: #fef3c7;
    --red: #b91c1c;
    --red-light: #fee2e2;
    --purple: #8b5cf6;
    --purple-light: #ede9fe;

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

.room-page {
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
.room-topbar {
    background: var(--white);
    border-bottom: 1.5px solid var(--s100);
    padding: 20px 28px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-xs);
}
.room-topbar__inner {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.room-topbar__title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--s900);
    margin-bottom: 8px;
    letter-spacing: -.5px;
}
.room-topbar__title h1 em {
    font-style: normal;
    color: var(--g600);
}
.room-topbar__meta {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.room-topbar__meta span {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .8rem;
    color: var(--s500);
}
.room-topbar__actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-success { background: var(--g50); color: var(--g700); border: 1.5px solid var(--g200); }
.badge-warning { background: var(--amber-light); color: var(--amber); border: 1.5px solid var(--amber); }
.badge-danger { background: var(--red-light); color: var(--red); border: 1.5px solid #fecaca; }
.badge-info { background: var(--blue-light); color: var(--blue); border: 1.5px solid var(--blue-light); }
.badge-light { background: var(--s100); color: var(--s600); border: 1.5px solid var(--s200); }

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
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
    box-shadow: 0 2px 10px rgba(46,133,64,.25);
}
.btn-db-primary:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.3);
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
.btn-db-success {
    background: var(--g600);
    color: white;
    border: 1.5px solid var(--g700);
}
.btn-db-success:hover {
    background: var(--g700);
    transform: translateY(-1px);
    color: white;
}
.btn-db-warning {
    background: var(--amber);
    color: white;
    border: 1.5px solid #b45309;
}
.btn-db-warning:hover {
    background: #b45309;
    transform: translateY(-1px);
    color: white;
}
.btn-db-outline {
    background: transparent;
    border: 1.5px solid var(--s200);
    color: var(--s600);
}
.btn-db-outline:hover {
    background: var(--s50);
    border-color: var(--s300);
    color: var(--s800);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   MAIN CONTAINER
══════════════════════════════════════════════ */
.room-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 28px 48px;
}
.room-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 24px;
}
@media (max-width: 1200px) {
    .room-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.card:hover {
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}
.card:last-child { margin-bottom: 0; }

.card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--s100);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    font-size: .9rem;
    color: var(--s800);
    background: var(--white);
}
.card-header i {
    color: var(--g600);
}
.card-header-primary {
    background: linear-gradient(135deg, var(--g600), var(--g500));
    color: white;
}
.card-header-primary i {
    color: rgba(255,255,255,.9);
}
.card-header-success {
    background: linear-gradient(135deg, var(--g600), var(--g500));
    color: white;
}
.card-header-warning {
    background: linear-gradient(135deg, var(--amber), #b45309);
    color: white;
}
.card-header-info {
    background: linear-gradient(135deg, var(--blue), #2563eb);
    color: white;
}
.card-header-dark {
    background: linear-gradient(135deg, var(--s700), var(--s800));
    color: white;
}
.card-badge {
    margin-left: auto;
    flex-shrink: 0;
}
.card-body {
    padding: 22px;
}

/* ══════════════════════════════════════════════
   INFO ROWS
══════════════════════════════════════════════ */
.info-row {
    margin-bottom: 18px;
}
.info-row:last-child {
    margin-bottom: 0;
}
.info-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--s400);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.info-value {
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
}
.info-value-lg {
    font-size: 1.8rem;
    font-weight: 700;
    font-family: var(--mono);
    line-height: 1;
    color: var(--s900);
    letter-spacing: -1px;
}
.info-value-price {
    font-family: var(--mono);
    color: var(--g600);
}
.info-value-sm {
    font-size: .75rem;
    color: var(--s500);
}

/* ══════════════════════════════════════════════
   STATS GRID
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
.stat-item {
    text-align: center;
    padding: 14px;
    background: var(--surface);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    transition: var(--transition);
}
.stat-item:hover {
    border-color: var(--g200);
    background: var(--white);
}
.stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--s900);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--s400);
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ══════════════════════════════════════════════
   FACILITIES
══════════════════════════════════════════════ */
.facilities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.facility-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: var(--surface);
    border: 1.5px solid var(--s200);
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 500;
    color: var(--s700);
    transition: var(--transition);
}
.facility-badge:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
}
.facility-badge i {
    color: var(--g600);
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   GUEST CARD (current occupant)
══════════════════════════════════════════════ */
.guest-card {
    display: flex;
    align-items: center;
    gap: 18px;
}
.guest-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--g600), var(--g500));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(46,133,64,.2);
}
.guest-info {
    flex: 1;
}
.guest-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--s900);
    margin-bottom: 4px;
}
.guest-meta {
    font-size: .75rem;
    color: var(--s500);
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 6px;
}
.guest-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.guest-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════
   AVAILABLE STATE
══════════════════════════════════════════════ */
.available-state {
    text-align: center;
    padding: 40px 20px;
}
.available-state i {
    font-size: 3.5rem;
    color: var(--g500);
    margin-bottom: 16px;
    opacity: .8;
}
.available-state h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--s800);
    margin-bottom: 8px;
}
.available-state p {
    color: var(--s500);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════════════
   CALENDAR
══════════════════════════════════════════════ */
.legend {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.legend-sq {
    width: 18px;
    height: 18px;
    border-radius: 4px;
}
.legend-sq-avail { background: var(--g50); border: 1.5px solid var(--g300); }
.legend-sq-occ { background: var(--red-light); border: 1.5px solid #fecaca; }
.legend-sq-unavail { background: var(--s100); border: 1.5px solid var(--s200); }
.legend-sq-today { background: var(--g50); border: 2px solid var(--g500); }
.legend-item span {
    font-size: .8rem;
    color: var(--s600);
}

.cal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.cal-table thead th {
    background: var(--surface);
    border: 1.5px solid var(--s100);
    padding: 12px 8px;
    text-align: center;
    font-size: .7rem;
    font-weight: 600;
    color: var(--s500);
    text-transform: uppercase;
    letter-spacing: .4px;
}
.cal-table tbody td {
    border: 1.5px solid var(--s100);
    padding: 0;
    text-align: center;
    position: relative;
}
.cal-day {
    min-height: 70px;
    padding: 10px 8px;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.cal-day:hover {
    transform: scale(1.05);
    z-index: 10;
    box-shadow: 0 0 0 2px var(--g500);
    border-radius: var(--r);
}
.cal-day__num {
    font-size: .9rem;
    font-weight: 700;
    color: var(--s800);
    margin-bottom: 2px;
}
.cal-day__month {
    font-size: .6rem;
    color: var(--s400);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.cal-day__icon i {
    font-size: .9rem;
}

.cal-day--avail {
    background: var(--g50);
    border-left: 2px solid var(--g300);
}
.cal-day--avail .cal-day__icon i {
    color: var(--g600);
}
.cal-day--avail:hover {
    background: var(--g100);
}

.cal-day--occ {
    background: var(--red-light);
    border-left: 2px solid #fecaca;
}
.cal-day--occ .cal-day__icon i {
    color: var(--red);
}
.cal-day--occ:hover {
    background: #fecaca;
}

.cal-day--unavail {
    background: var(--s100);
    border-left: 2px solid var(--s200);
    cursor: not-allowed;
}
.cal-day--unavail .cal-day__icon i {
    color: var(--s400);
    opacity: .5;
}
.cal-day--unavail:hover {
    transform: none;
    box-shadow: none;
}

.cal-day--today {
    background: var(--g50) !important;
    border: 2px solid var(--g500) !important;
    font-weight: 700;
}

.conflict-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: var(--red);
    color: white;
    font-size: .6rem;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 4px;
}

.cal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1.5px solid var(--s100);
}
.cal-footer__info {
    font-size: .75rem;
    color: var(--s500);
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ══════════════════════════════════════════════
   NEXT RESERVATION
══════════════════════════════════════════════ */
.next-res {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.next-res__info h6 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--s800);
    margin-bottom: 4px;
}
.next-res__meta {
    font-size: .75rem;
    color: var(--s500);
}

/* ══════════════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════════════ */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
@media (max-width: 768px) {
    .actions-grid { grid-template-columns: repeat(2, 1fr); }
}
.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 12px;
    border-radius: var(--rl);
    border: 1.5px solid var(--s100);
    background: var(--white);
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
    min-height: 120px;
}
.action-btn:hover:not(:disabled) {
    background: var(--g50);
    border-color: var(--g300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.action-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
    background: var(--s50);
}
.action-btn__icon {
    font-size: 1.8rem;
    margin-bottom: 10px;
}
.action-btn--primary .action-btn__icon { color: var(--g600); }
.action-btn--success .action-btn__icon { color: var(--g600); }
.action-btn--warning .action-btn__icon { color: var(--amber); }
.action-btn--info .action-btn__icon { color: var(--blue); }
.action-btn__title {
    font-size: .8rem;
    font-weight: 600;
    color: var(--s800);
    margin-bottom: 4px;
}
.action-btn__desc {
    font-size: .65rem;
    color: var(--s500);
    text-align: center;
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $roomStats = array_merge([
        'total_transactions' => 0,
        'total_revenue' => 0,
        'total_revenue_30d' => 0,
        'avg_stay_duration' => 0,
        'avg_daily_rate' => $room->price ?? 0,
        'occupancy_rate_30d' => 0,
        'next_available' => null,
        'formatted_next_available' => 'Immédiate',
        'last_30_days_revenue' => 0
    ], $roomStats ?? []);
    
    \Carbon\Carbon::setLocale('fr');
    
    $user = auth()->user();
    $canCheckOut = in_array($user->role ?? '', ['Super', 'Admin', 'Receptionist']);
?>


<div class="room-topbar anim-1">
    <div class="room-topbar__inner">
        <div class="room-topbar__title">
            <h1>Chambre <em><?php echo e($room->number); ?></em></h1>
            <div class="room-topbar__meta">
                <span class="badge badge-<?php echo e($room->room_status_id == 1 ? 'success' : ($room->room_status_id == 2 ? 'danger' : 'warning')); ?>">
                    <i class="fas fa-<?php echo e($room->room_status_id == 1 ? 'check' : ($room->room_status_id == 2 ? 'tools' : 'broom')); ?>"></i>
                    <?php echo e($room->roomStatus->name ?? 'Statut inconnu'); ?>

                </span>
                <span>
                    <i class="fas fa-bed" style="color:var(--g500);"></i>
                    <?php echo e($room->type->name ?? 'Type inconnu'); ?>

                </span>
                <span>
                    <i class="fas fa-users" style="color:var(--g500);"></i>
                    <?php echo e($room->capacity); ?> personnes
                </span>
                <span>
                    <i class="fas fa-tag" style="color:var(--g500);"></i>
                    <?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA/nuit
                </span>
            </div>
        </div>
        <div class="room-topbar__actions">
            <a href="<?php echo e(route('availability.calendar')); ?>?room_number=<?php echo e($room->number); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-calendar-alt"></i>
                Calendrier
            </a>
            <a href="<?php echo e(route('availability.inventory')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-clipboard-list"></i>
                Inventaire
            </a>
            <a href="<?php echo e(route('room.edit', $room->id)); ?>" class="btn-db btn-db-primary">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
        </div>
    </div>
</div>


<div class="room-container">
    <div class="room-grid">

        
        <div class="anim-2">
            
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-info-circle"></i>
                    Informations générales
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Numéro de chambre</div>
                        <div class="info-value info-value-lg"><?php echo e($room->number); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Type</div>
                        <div class="info-value"><?php echo e($room->type->name ?? 'Type inconnu'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Prix par nuit</div>
                        <div class="info-value info-value-lg info-value-price">
                            <?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA
                        </div>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:16px 0;">
                        <div class="info-row">
                            <div class="info-label">Capacité</div>
                            <div class="info-value">
                                <i class="fas fa-users" style="color:var(--g500); margin-right:4px;"></i>
                                <?php echo e($room->capacity); ?>

                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Étage</div>
                            <div class="info-value"><?php echo e($room->floor ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Surface</div>
                            <div class="info-value"><?php echo e($room->size ?? 'N/A'); ?> m²</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Vue</div>
                            <div class="info-value"><?php echo e($room->view ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Dernière mise à jour</div>
                        <div class="info-value info-value-sm">
                            <i class="fas fa-clock" style="color:var(--g500); margin-right:4px;"></i>
                            <?php echo e($room->updated_at ? $room->updated_at->format('d/m/Y H:i') : 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($room->facilities && $room->facilities->count() > 0): ?>
            <div class="card">
                <div class="card-header card-header-success">
                    <i class="fas fa-wifi"></i>
                    Équipements
                </div>
                <div class="card-body">
                    <div class="facilities">
                        <?php $__currentLoopData = $room->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="facility-badge">
                            <i class="fas fa-<?php echo e($facility->icon ?? 'check'); ?>"></i>
                            <?php echo e($facility->name); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card">
                <div class="card-header card-header-info">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques (30 jours)
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($roomStats['total_transactions']); ?></div>
                            <div class="stat-label">Réservations</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(number_format($roomStats['occupancy_rate_30d'], 1)); ?>%</div>
                            <div class="stat-label">Taux d'occ.</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(number_format($roomStats['avg_stay_duration'], 1)); ?></div>
                            <div class="stat-label">Nuits moy.</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(number_format($roomStats['avg_daily_rate'], 0)); ?></div>
                            <div class="stat-label">Prix moy.</div>
                        </div>
                    </div>
                    
                    <div class="stat-item" style="margin-top:8px;background:var(--g50);border-color:var(--g200);">
                        <div class="stat-value" style="color:var(--g600);">
                            <?php echo e(number_format($roomStats['total_revenue_30d'], 0, ',', ' ')); ?> FCFA
                        </div>
                        <div class="stat-label">Revenu total (30j)</div>
                    </div>
                    
                    <?php if($roomStats['next_available'] && $roomStats['next_available'] instanceof \Carbon\Carbon): ?>
                    <div style="margin-top:16px;padding:12px;background:var(--amber-light);border:1.5px solid var(--amber);border-radius:var(--rl);text-align:center;">
                        <div style="font-size:.65rem;color:var(--s500);margin-bottom:4px;text-transform:uppercase;">Prochaine disponibilité</div>
                        <div style="font-weight:700;color:var(--amber);"><?php echo e($roomStats['next_available']->format('d/m/Y')); ?></div>
                    </div>
                    <?php elseif(isset($roomStats['formatted_next_available']) && $roomStats['formatted_next_available'] != 'Immédiate'): ?>
                    <div style="margin-top:16px;padding:12px;background:var(--g50);border:1.5px solid var(--g200);border-radius:var(--rl);text-align:center;">
                        <div style="font-size:.65rem;color:var(--s500);margin-bottom:4px;text-transform:uppercase;">Disponible</div>
                        <div style="font-weight:700;color:var(--g600);">Immédiatement</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="anim-3">
            
            <?php if($currentTransaction): ?>
            <div class="card" style="border-color:var(--amber);">
                <div class="card-header card-header-warning">
                    <i class="fas fa-user-check"></i>
                    Client actuel
                    <span class="card-badge">
                        <span class="badge badge-light">
                            <?php echo e($currentTransaction->check_in->format('d/m/Y')); ?> - <?php echo e($currentTransaction->check_out->format('d/m/Y')); ?>

                        </span>
                    </span>
                </div>
                <div class="card-body">
                    <div class="guest-card">
                        <div class="guest-avatar">
                            <?php echo e(strtoupper(substr($currentTransaction->customer->name ?? 'C', 0, 1))); ?>

                        </div>
                        <div class="guest-info">
                            <div class="guest-name"><?php echo e($currentTransaction->customer->name ?? 'Client inconnu'); ?></div>
                            <div class="guest-meta">
                                <?php if($currentTransaction->customer->email ?? false): ?>
                                <span><i class="fas fa-envelope" style="color:var(--g500);"></i> <?php echo e($currentTransaction->customer->email); ?></span>
                                <?php endif; ?>
                                <?php if($currentTransaction->customer->phone ?? false): ?>
                                <span><i class="fas fa-phone" style="color:var(--g500);"></i> <?php echo e($currentTransaction->customer->phone); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="guest-badges">
                                <span class="badge badge-info"><?php echo e($currentTransaction->nights ?? 1); ?> nuit(s)</span>
                                <span class="badge badge-success"><?php echo e(number_format($currentTransaction->total_price ?? 0, 0, ',', ' ')); ?> FCFA</span>
                                <span class="badge badge-<?php echo e(($currentTransaction->status ?? '') == 'active' ? 'warning' : 'info'); ?>">
                                    <?php echo e($currentTransaction->status_label ?? 'Réservation'); ?>

                                </span>
                            </div>
                        </div>
                        <div class="guest-actions">
                            <a href="<?php echo e(route('transaction.show', ['transaction' => $currentTransaction->id])); ?>" class="btn-db btn-db-ghost">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                            <?php if(($currentTransaction->status ?? '') == 'active'): ?>
                                <?php if($canCheckOut): ?>
                                <a href="<?php echo e(route('transaction.mark-departed', ['transaction' => $currentTransaction->id])); ?>" 
                                   class="btn-db btn-db-warning"
                                   onclick="return confirm('Êtes-vous sûr de vouloir faire le check-out ?');">
                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                </a>
                                <?php else: ?>
                                <button class="btn-db btn-db-ghost" disabled title="Seul le personnel de réception peut faire le check-out">
                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card" style="border-color:var(--g600);">
                <div class="card-header card-header-success">
                    <i class="fas fa-door-open"></i>
                    Chambre disponible
                    <span class="card-badge">
                        <span class="badge badge-light"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                    </span>
                </div>
                <div class="card-body">
                    <div class="available-state">
                        <i class="fas fa-check-circle"></i>
                        <h5>Chambre libre</h5>
                        <p>Cette chambre est actuellement disponible pour une nouvelle réservation.</p>
                        <?php if($room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>?room_id=<?php echo e($room->id); ?>" class="btn-db btn-db-success">
                            <i class="fas fa-plus-circle"></i>
                            Créer une réservation
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card">
                <div class="card-header card-header-dark">
                    <i class="fas fa-calendar-alt"></i>
                    Disponibilité (30 prochains jours)
                    <span class="card-badge">
                        <small style="color:rgba(255,255,255,.7);"><?php echo e(now()->format('d/m')); ?> → <?php echo e(now()->addDays(30)->format('d/m')); ?></small>
                    </span>
                </div>
                <div class="card-body">
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-sq legend-sq-avail"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq-occ"></div>
                            <span>Occupée</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq-unavail"></div>
                            <span>Indisponible</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq-today"></div>
                            <span>Aujourd'hui</span>
                        </div>
                    </div>
                    
                    <div style="overflow-x:auto">
                        <table class="cal-table">
                            <thead>
                                <tr>
                                    <?php for($i = 0; $i < 7; $i++): ?>
                                        <?php $day = now()->addDays($i); ?>
                                        <th>
                                            <div style="font-weight:700;"><?php echo e($day->isoFormat('dd')); ?></div>
                                            <div style="font-size:.6rem;color:var(--s400);"><?php echo e($day->format('d')); ?></div>
                                        </th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($week = 0; $week < 5; $week++): ?>
                                    <tr>
                                        <?php for($day = 0; $day < 7; $day++): ?>
                                            <?php
                                                $dateIndex = $week * 7 + $day;
                                                if ($dateIndex >= 30) break;
                                                
                                                $date = now()->addDays($dateIndex);
                                                $dateKey = $date->format('Y-m-d');
                                                $availability = $calendar[$dateKey] ?? null;
                                                
                                                $isOccupied = false;
                                                $reservationInfo = '';
                                                
                                                if ($availability && isset($availability['occupied'])) {
                                                    $isOccupied = $availability['occupied'];
                                                    if (isset($availability['reservation_count']) && $availability['reservation_count'] > 0) {
                                                        $reservationInfo = $availability['reservation_count'] . ' réservation(s)';
                                                    }
                                                }
                                                
                                                $cssClass = 'cal-day--avail';
                                                $icon = 'fas fa-check';
                                                $tooltipText = 'Disponible - ' . number_format($room->price, 0, ',', ' ') . ' FCFA/nuit';
                                                
                                                if ($isOccupied) {
                                                    $cssClass = 'cal-day--occ';
                                                    $icon = 'fas fa-user';
                                                    $tooltipText = 'Occupée';
                                                    if ($reservationInfo) {
                                                        $tooltipText .= ' - ' . $reservationInfo;
                                                    }
                                                } elseif ($room->room_status_id != 1) {
                                                    $cssClass = 'cal-day--unavail';
                                                    $icon = 'fas fa-times';
                                                    $tooltipText = 'Indisponible - ' . ($room->roomStatus->name ?? 'Maintenance');
                                                }
                                                
                                                $isToday = $date->isToday();
                                                if ($isToday) {
                                                    $cssClass .= ' cal-day--today';
                                                    $tooltipText .= ' - Aujourd\'hui';
                                                }
                                            ?>
                                            <td>
                                                <div class="cal-day <?php echo e($cssClass); ?>"
                                                    data-date="<?php echo e($dateKey); ?>"
                                                    data-is-occupied="<?php echo e($isOccupied ? 'true' : 'false'); ?>"
                                                    data-room-id="<?php echo e($room->id); ?>"
                                                    data-room-number="<?php echo e($room->number); ?>"
                                                    onclick="handleCalendarDayClick(this)"
                                                    title="<?php echo e($tooltipText); ?>">
                                                    <div class="cal-day__num"><?php echo e($date->format('d')); ?></div>
                                                    <div class="cal-day__month"><?php echo e($date->isoFormat('MMM')); ?></div>
                                                    <div class="cal-day__icon">
                                                        <i class="<?php echo e($icon); ?>"></i>
                                                    </div>
                                                    <?php if($isOccupied && isset($availability['reservation_count']) && $availability['reservation_count'] > 1): ?>
                                                    <div class="conflict-badge">
                                                        <?php echo e($availability['reservation_count']); ?>

                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="cal-footer">
                        <div class="cal-footer__info">
                            <i class="fas fa-info-circle" style="color:var(--g500);"></i>
                            Cliquez sur une date pour voir/réserver
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button class="btn-db btn-db-ghost" style="font-size:.7rem;padding:6px 12px;" onclick="scrollToTodayInCalendar()">
                                <i class="fas fa-calendar-day"></i> Aujourd'hui
                            </button>
                            <a href="<?php echo e(route('availability.calendar')); ?>?room_number=<?php echo e($room->number); ?>" 
                               class="btn-db btn-db-ghost" style="font-size:.7rem;padding:6px 12px;">
                                <i class="fas fa-expand-alt"></i> Calendrier complet
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($nextReservation): ?>
            <div class="card">
                <div class="card-header card-header-info">
                    <i class="fas fa-calendar-plus"></i>
                    Prochaine réservation
                    <span class="card-badge">
                        <span class="badge badge-light"><?php echo e($nextReservation->check_in->format('d/m/Y')); ?></span>
                    </span>
                </div>
                <div class="card-body">
                    <div class="next-res">
                        <div class="next-res__info">
                            <h6><?php echo e($nextReservation->customer->name ?? 'Client inconnu'); ?></h6>
                            <div class="next-res__meta">
                                <i class="fas fa-calendar" style="color:var(--g500);"></i>
                                <?php echo e($nextReservation->check_in->format('d/m/Y')); ?> → <?php echo e($nextReservation->check_out->format('d/m/Y')); ?>

                                &nbsp;•&nbsp;
                                <i class="fas fa-moon" style="color:var(--g500);"></i>
                                <?php echo e($nextReservation->nights); ?> nuit(s)
                                &nbsp;•&nbsp;
                                <i class="fas fa-users" style="color:var(--g500);"></i>
                                <?php echo e($nextReservation->person_count ?? 1); ?> pers.
                            </div>
                        </div>
                        <span class="badge badge-warning">Confirmée</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-bolt"></i>
                    Actions rapides
                </div>
                <div class="card-body">
                    <div class="actions-grid">
                        <a href="<?php echo e(route('availability.search', ['room_type_id' => $room->type_id, 'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDays(1)->format('Y-m-d')])); ?>" 
                           class="action-btn action-btn--primary">
                            <div class="action-btn__icon"><i class="fas fa-search"></i></div>
                            <div class="action-btn__title">Rechercher</div>
                            <div class="action-btn__desc">Voir disponibilités</div>
                        </a>
                        
                        <?php if($room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('transaction.reservation.createIdentity', ['room_id' => $room->id])); ?>" 
                           class="action-btn action-btn--success">
                            <div class="action-btn__icon"><i class="fas fa-book"></i></div>
                            <div class="action-btn__title">Réserver</div>
                            <div class="action-btn__desc">Nouvelle réservation</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled>
                            <div class="action-btn__icon" style="color:var(--s400);"><i class="fas fa-ban"></i></div>
                            <div class="action-btn__title">Indisponible</div>
                            <div class="action-btn__desc"><?php echo e($room->roomStatus->name ?? 'Non disponible'); ?></div>
                        </button>
                        <?php endif; ?>
                        
                        <?php
                            $canMaintenance = in_array($user->role ?? '', ['Super', 'Admin', 'Receptionist', 'Housekeeping']);
                        ?>
                        <?php if($canMaintenance && $room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('housekeeping.mark-maintenance', $room->id)); ?>" class="action-btn action-btn--warning">
                            <div class="action-btn__icon"><i class="fas fa-tools"></i></div>
                            <div class="action-btn__title">Maintenance</div>
                            <div class="action-btn__desc">Marquer en maintenance</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled title="<?php echo e(!$canMaintenance ? 'Non autorisé' : 'Chambre déjà en maintenance'); ?>">
                            <div class="action-btn__icon" style="color:var(--s400);"><i class="fas fa-tools"></i></div>
                            <div class="action-btn__title">Maintenance</div>
                            <div class="action-btn__desc"><?php echo e(!$canMaintenance ? 'Non autorisé' : 'Indisponible'); ?></div>
                        </button>
                        <?php endif; ?>
                        
                        <?php
                            $canClean = in_array($user->role ?? '', ['Super', 'Admin', 'Housekeeping']);
                            $isDirty = $room->room_status_id == 3;
                        ?>
                        <?php if($canClean && $isDirty): ?>
                        <a href="<?php echo e(route('housekeeping.mark-cleaned', $room->id)); ?>" class="action-btn action-btn--info">
                            <div class="action-btn__icon"><i class="fas fa-broom"></i></div>
                            <div class="action-btn__title">Nettoyée</div>
                            <div class="action-btn__desc">Marquer comme nettoyée</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled title="<?php echo e(!$canClean ? 'Non autorisé' : 'Chambre déjà nettoyée'); ?>">
                            <div class="action-btn__icon" style="color:var(--s400);"><i class="fas fa-broom"></i></div>
                            <div class="action-btn__title">Nettoyée</div>
                            <div class="action-btn__desc"><?php echo e(!$canClean ? 'Non autorisé' : 'Indisponible'); ?></div>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="roomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Contenu dynamique injecté par JS -->
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(el) {
        return new bootstrap.Tooltip(el, {
            placement: 'top',
            delay: { show: 100, hide: 100 }
        });
    });
});

function handleCalendarDayClick(cell) {
    const date = cell.getAttribute('data-date');
    const isOccupied = cell.getAttribute('data-is-occupied') === 'true';
    const roomId = cell.getAttribute('data-room-id');
    const roomNumber = cell.getAttribute('data-room-number');
    
    if (isOccupied) {
        showOccupancyDetailsModal(roomId, date, roomNumber);
    } else {
        showReservationModal(roomId, roomNumber, date);
    }
}

function showOccupancyDetailsModal(roomId, date, roomNumber) {
    const formattedDate = new Date(date).toLocaleDateString('fr-FR');
    const content = `
        <div class="modal-header" style="background:linear-gradient(135deg, #b91c1c, #991b1b); color:white;">
            <h5 class="modal-title"><i class="fas fa-calendar-times me-2"></i>Chambre Occupée</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning" style="background:var(--red-light); border-color:#fecaca; color:var(--red); padding:16px; border-radius:var(--rl); margin-bottom:16px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                La chambre <strong>${roomNumber}</strong> est occupée le <strong>${formattedDate}</strong>.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <div class="info-label">DATE</div>
                    <div class="info-value">${formattedDate}</div>
                </div>
                <div>
                    <div class="info-label">STATUT</div>
                    <span class="badge badge-danger">Occupée</span>
                </div>
            </div>
            <div class="alert alert-info" style="background:var(--g50); border-color:var(--g200); color:var(--g700); padding:16px; border-radius:var(--rl);">
                <i class="fas fa-info-circle me-2" style="color:var(--g600);"></i>
                Pour voir les détails, consultez la liste des transactions.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-db btn-db-ghost" data-bs-dismiss="modal">Fermer</button>
            <a href="/transaction" class="btn-db btn-db-primary"><i class="fas fa-list me-2"></i>Voir transactions</a>
        </div>
    `;
    showModal(content);
}

function showReservationModal(roomId, roomNumber, date) {
    const checkInDate = new Date(date);
    const checkOutDate = new Date(checkInDate);
    checkOutDate.setDate(checkOutDate.getDate() + 1);
    
    const checkInStr = checkInDate.toISOString().split('T')[0];
    const checkOutStr = checkOutDate.toISOString().split('T')[0];
    const formattedDate = checkInDate.toLocaleDateString('fr-FR');
    
    const content = `
        <div class="modal-header" style="background:linear-gradient(135deg, var(--g600), var(--g500)); color:white;">
            <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Réserver la chambre</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success" style="background:var(--g50); border-color:var(--g200); color:var(--g700); padding:16px; border-radius:var(--rl); margin-bottom:16px;">
                <i class="fas fa-check-circle me-2" style="color:var(--g600);"></i>
                La chambre <strong>${roomNumber}</strong> est disponible pour le <strong>${formattedDate}</strong>.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <div class="info-label">CHAMBRE</div>
                    <div class="info-value">${roomNumber}</div>
                </div>
                <div>
                    <div class="info-label">DATE D'ARRIVÉE</div>
                    <div class="info-value">${formattedDate}</div>
                </div>
            </div>
            <div class="alert alert-info" style="background:var(--g50); border-color:var(--g200); color:var(--g700); padding:16px; border-radius:var(--rl);">
                <i class="fas fa-info-circle me-2" style="color:var(--g600);"></i>
                Vous serez redirigé vers le formulaire de réservation.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-db btn-db-ghost" data-bs-dismiss="modal">Annuler</button>
            <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>?room_id=${roomId}&check_in=${checkInStr}&check_out=${checkOutStr}" 
               class="btn-db btn-db-success"><i class="fas fa-book me-2"></i>Continuer</a>
        </div>
    `;
    showModal(content);
}

function showModal(content) {
    const modal = document.getElementById('roomModal');
    const modalContent = modal.querySelector('.modal-content');
    modalContent.innerHTML = content;
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function scrollToTodayInCalendar() {
    const today = document.querySelector('.cal-day--today');
    if (today) {
        today.scrollIntoView({ behavior: 'smooth', block: 'center' });
        today.style.boxShadow = '0 0 20px var(--g500)';
        setTimeout(() => { today.style.boxShadow = ''; }, 1500);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/availability/room-detail.blade.php ENDPATH**/ ?>