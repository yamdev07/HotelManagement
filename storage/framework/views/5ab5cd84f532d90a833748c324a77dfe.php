

<?php $__env->startSection('title', 'Réservations Client'); ?>

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

.history-page {
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
.history-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.history-brand { display: flex; align-items: center; gap: 14px; }
.history-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.history-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.history-header-title em { font-style: normal; color: var(--g600); }
.history-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.history-header-sub i { color: var(--g500); }
.history-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
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
    width: 36px; height: 36px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; font-size: .8rem;
    background: var(--white); color: var(--s400);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
    font-family: var(--font);
}
.btn-db-icon:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200); transform: translateY(-1px);
}

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

.stat-card--total { --bar-c: var(--g500); }
.stat-card--active { --bar-c: var(--g600); }
.stat-card--completed { --bar-c: var(--g300); }
.stat-card--cancelled { --bar-c: var(--s400); }

.stat-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.stat-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-card--total .stat-card-icon { background: var(--g100); color: var(--g600); }
.stat-card--active .stat-card-icon { background: var(--g50); color: var(--g600); }
.stat-card--completed .stat-card-icon { background: var(--g50); color: var(--g500); }
.stat-card--cancelled .stat-card-icon { background: var(--s100); color: var(--s500); }

.stat-card-value {
    font-size: 2.6rem; font-weight: 700; color: var(--s900);
    line-height: 1; letter-spacing: -1px; margin-bottom: 4px;
    font-family: var(--mono);
}
.stat-card-label { font-size: .8rem; color: var(--s400); margin-bottom: 14px; }

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.history-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.history-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.history-card-header h5 {
    font-size: 1rem; font-weight: 600; color: var(--s800);
    margin: 0; display: flex; align-items: center; gap: 10px;
}
.history-card-header h5 i { color: var(--g500); }
.history-card-header .badge {
    background: var(--g100); color: var(--g700);
    font-size: .7rem; font-weight: 600; padding: 4px 10px;
    border-radius: 100px;
}
.history-card-body { padding: 0; }

/* ══════════════════════════════════════════════
   TABLEAU
══════════════════════════════════════════════ */
.history-table { width: 100%; border-collapse: collapse; }
.history-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 14px 18px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.history-table tbody tr { border-bottom: 1px solid var(--s100); transition: var(--transition); }
.history-table tbody tr:last-child { border-bottom: none; }
.history-table tbody tr:hover { background: var(--g50); }
.history-table td { padding: 16px 18px; vertical-align: middle; }

.room-badge {
    width: 40px; height: 40px; border-radius: 8px;
    background: var(--g600); color: white;
    display: flex; align-items: center; justify-content: center;
    font-weight: 600; font-size: 1rem; font-family: var(--mono);
    flex-shrink: 0;
}

.room-info {
    display: flex; align-items: center; gap: 12px;
}
.room-details {
    display: flex; flex-direction: column;
}
.room-name {
    font-size: .85rem; font-weight: 600; color: var(--s800);
    text-decoration: none; transition: var(--transition);
}
.room-name:hover { color: var(--g600); }
.room-type {
    font-size: .7rem; color: var(--s400); margin-top: 2px;
}

.date-info {
    display: flex; flex-direction: column;
}
.date-main {
    font-size: .8rem; font-weight: 600; color: var(--s800);
}
.date-range {
    font-size: .7rem; color: var(--s400); margin-top: 2px;
}
.nights-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .65rem; font-weight: 600; padding: 2px 8px;
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200); border-radius: 100px;
    margin-top: 4px;
}

.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 10px; border-radius: 100px;
    font-size: .7rem; font-weight: 600; white-space: nowrap;
}
.status-badge i { font-size: .65rem; }
.status-active { background: var(--g100); color: var(--g700); }
.status-completed { background: var(--g50); color: var(--g600); }
.status-reservation { background: var(--s100); color: var(--s600); }
.status-cancelled { background: #fee2e2; color: #b91c1c; }

.amount-info {
    display: flex; flex-direction: column;
}
.amount-total {
    font-size: .9rem; font-weight: 700; color: var(--s800);
    font-family: var(--mono);
}
.amount-down {
    font-size: .7rem; color: var(--s400); margin-top: 2px;
}

.payment-progress {
    width: 100px; display: flex; flex-direction: column; gap: 4px;
}
.progress-bar-container {
    height: 6px; background: var(--s100);
    border-radius: 3px; overflow: hidden;
}
.progress-fill {
    height: 100%; border-radius: 3px;
    transition: width .3s ease;
}
.progress-fill-success { background: var(--g500); }
.progress-fill-warning { background: var(--g300); }
.payment-text {
    font-size: .7rem; font-weight: 600; color: var(--s800);
}
.payment-detail {
    font-size: .65rem; color: var(--s400); margin-top: 2px;
}

.date-created {
    font-size: .75rem; color: var(--s500);
    font-family: var(--mono);
}

.action-group {
    display: flex; gap: 5px; justify-content: flex-end;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    display: flex; flex-direction: column; align-items: center;
    padding: 64px 24px; text-align: center;
}
.empty-icon {
    width: 80px; height: 80px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 2rem; color: var(--g300);
    margin-bottom: 20px; border: 2px solid var(--g100);
}
.empty-title {
    font-size: 1rem; font-weight: 600; color: var(--s700);
    margin-bottom: 8px;
}
.empty-text {
    font-size: .8rem; color: var(--s400);
    margin-bottom: 24px; max-width: 300px;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-wrapper {
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 12px;
    padding: 16px 24px; border-top: 1.5px solid var(--s100);
    background: var(--surface);
}
.pagination-info {
    font-size: .75rem; color: var(--s400);
}
.pagination-links { display: flex; gap: 4px; }
.pagination-links .page-link {
    border: 1.5px solid var(--s200); background: var(--white);
    color: var(--s600); padding: 5px 10px; border-radius: var(--r);
    font-size: .75rem; transition: var(--transition);
}
.pagination-links .page-link:hover {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.pagination-links .active .page-link {
    background: var(--g600); border-color: var(--g600);
    color: white;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:1100px){
    .history-page{ padding: 20px; }
    .stats-grid{ grid-template-columns:repeat(2,1fr); }
}
@media(max-width:768px){
    .stats-grid{ grid-template-columns:1fr; }
    .history-table{ display: block; overflow-x: auto; }
    .pagination-wrapper{ flex-direction: column; align-items: center; }
}
</style>

<div class="history-page">
    <!-- En-tête -->
    <div class="history-header anim-1">
        <div class="history-brand">
            <div class="history-brand-icon"><i class="fas fa-history"></i></div>
            <div>
                <h1 class="history-header-title">Historique des <em>réservations</em></h1>
                <p class="history-header-sub">
                    <i class="fas fa-user me-1"></i> <?php echo e($customer->name); ?> 
                    <i class="fas fa-circle fa-xs" style="color:var(--s300); font-size:4px;"></i>
                    <i class="fas fa-envelope me-1"></i> <?php echo e($customer->email); ?>

                    <i class="fas fa-phone me-1"></i> <?php echo e($customer->phone); ?>

                </p>
            </div>
        </div>
        <div class="history-header-actions">
            <a href="<?php echo e(route('transaction.reservation.pickFromCustomer')); ?>" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid anim-2">
        <div class="stat-card stat-card--total">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($reservations->total()); ?></div>
            <div class="stat-card-label">Total réservations</div>
        </div>

        <div class="stat-card stat-card--active">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($customer->transactions()->where('status', 'active')->count()); ?></div>
            <div class="stat-card-label">Actives</div>
        </div>

        <div class="stat-card stat-card--completed">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-flag-checkered"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($customer->transactions()->where('status', 'completed')->count()); ?></div>
            <div class="stat-card-label">Terminées</div>
        </div>

        <div class="stat-card stat-card--cancelled">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="stat-card-value"><?php echo e($customer->transactions()->where('status', 'cancelled')->count()); ?></div>
            <div class="stat-card-label">Annulées</div>
        </div>
    </div>

    <!-- Liste des réservations -->
    <div class="anim-3">
        <div class="history-card">
            <div class="history-card-header">
                <h5>
                    <i class="fas fa-list"></i>
                    Historique des réservations
                    <span class="badge ms-auto"><?php echo e($reservations->total()); ?> réservation(s)</span>
                </h5>
            </div>
            <div class="history-card-body">
                <?php if($reservations->count() > 0): ?>
                    <div style="overflow-x:auto;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Chambre</th>
                                    <th>Dates</th>
                                    <th>Statut</th>
                                    <th>Montant</th>
                                    <th>Paiement</th>
                                    <th>Créé le</th>
                                    <th style="text-align:right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $totalPaid = $reservation->getTotalPayment();
                                    $totalPrice = $reservation->getTotalPrice();
                                    $percentage = $totalPrice > 0 ? round(($totalPaid / $totalPrice) * 100) : 0;
                                    $statusClass = match($reservation->status) {
                                        'active' => 'status-active',
                                        'completed' => 'status-completed',
                                        'reservation' => 'status-reservation',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-reservation'
                                    };
                                ?>
                                <tr>
                                    <td class="fw-bold" style="font-family:var(--mono); color:var(--s800);">#<?php echo e($reservation->id); ?></td>
                                    <td>
                                        <div class="room-info">
                                            <div class="room-badge"><?php echo e($reservation->room->number); ?></div>
                                            <div class="room-details">
                                                <a href="<?php echo e(route('room.show', $reservation->room->id)); ?>" class="room-name">
                                                    <?php echo e($reservation->room->name ?? 'Chambre'); ?>

                                                </a>
                                                <span class="room-type"><?php echo e($reservation->room->type->name ?? 'Standard'); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <span class="date-main"><?php echo e($reservation->check_in->format('d/m/Y')); ?> → <?php echo e($reservation->check_out->format('d/m/Y')); ?></span>
                                            <span class="date-range"><?php echo e($reservation->check_in->format('d M')); ?> - <?php echo e($reservation->check_out->format('d M Y')); ?></span>
                                            <span class="nights-badge">
                                                <i class="fas fa-moon"></i> <?php echo e($reservation->check_in->diffInDays($reservation->check_out)); ?> nuits
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo e($statusClass); ?>">
                                            <i class="fas fa-<?php echo e($reservation->status_icon); ?>"></i>
                                            <?php echo e($reservation->status_label); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="amount-info">
                                            <span class="amount-total"><?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> FCFA</span>
                                            <?php if($reservation->down_payment > 0): ?>
                                                <span class="amount-down">Acompte: <?php echo e(number_format($reservation->down_payment, 0, ',', ' ')); ?> FCFA</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="payment-progress">
                                            <div class="progress-bar-container">
                                                <div class="progress-fill <?php echo e($percentage >= 100 ? 'progress-fill-success' : 'progress-fill-warning'); ?>" 
                                                     style="width: <?php echo e($percentage); ?>%"></div>
                                            </div>
                                            <div class="payment-text"><?php echo e($percentage); ?>%</div>
                                            <div class="payment-detail">
                                                <?php echo e(number_format($totalPaid, 0, ',', ' ')); ?> / <?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> FCFA
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="date-created"><?php echo e($reservation->created_at->format('d/m/Y H:i')); ?></span>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="<?php echo e(route('transaction.show', $reservation->id)); ?>" 
                                               class="btn-db-icon" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('transaction.invoice', $reservation->id)); ?>" 
                                               class="btn-db-icon" title="Facture">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            <?php if($reservation->status == 'active'): ?>
                                            <a href="<?php echo e(route('transaction.extend', $reservation->id)); ?>" 
                                               class="btn-db-icon" title="Prolonger">
                                                <i class="fas fa-calendar-plus"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Affichage de <?php echo e($reservations->firstItem()); ?> à <?php echo e($reservations->lastItem()); ?> 
                            sur <?php echo e($reservations->total()); ?> réservations
                        </div>
                        <div class="pagination-links">
                            <?php echo e($reservations->onEachSide(1)->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <p class="empty-title">Aucune réservation</p>
                        <p class="empty-text">Ce client n'a pas encore effectué de réservation</p>
                        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn-db btn-db-primary">
                            <i class="fas fa-plus-circle me-2"></i>
                            Créer une nouvelle réservation
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/customer-reservations.blade.php ENDPATH**/ ?>