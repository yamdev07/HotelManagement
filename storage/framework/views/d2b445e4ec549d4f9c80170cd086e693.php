
<?php $__env->startSection('title', 'Gestion des Paiements'); ?>
<?php $__env->startSection('content'); ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.payments-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
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
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
    transition: var(--transition);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   DROPDOWN
══════════════════════════════════════════════ */
.dropdown-menu {
    border-radius: var(--rl);
    border: 1.5px solid var(--gray-200);
    padding: 6px;
    box-shadow: var(--shadow-sm);
}
.dropdown-item {
    border-radius: var(--r);
    padding: 6px 12px;
    font-size: .75rem;
    transition: var(--transition);
}
.dropdown-item:hover {
    background: var(--green-50);
    color: var(--green-700);
}
.dropdown-item i {
    width: 18px;
    color: var(--green-600);
}
.dropdown-divider {
    border-top: 1.5px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--r);
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-content {
    flex: 1;
}
.stat-number {
    font-size: 1.6rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-badge {
    display: inline-block;
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: var(--white);
}
.card-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}
.card-header h5 i {
    color: var(--green-600);
}
.card-body {
    padding: 0;
}

/* ══════════════════════════════════════════════
   SEARCH
══════════════════════════════════════════════ */
.search-wrapper {
    position: relative;
    width: 280px;
}
.search-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
}
.search-wrapper input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    transition: var(--transition);
}
.search-wrapper input:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 14px 18px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table tbody tr.today {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.badge-blue { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-orange { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }

/* ── Reference badge ── */
.ref-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: var(--gray-100);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .65rem;
    font-family: var(--mono);
    color: var(--gray-600);
}

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.avatar-sm {
    width: 30px;
    height: 30px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   ACTIONS
══════════════════════════════════════════════ */
.actions-group {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-modern {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}
.pagination-modern .page-item {
    list-style: none;
}
.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-600);
    font-size: .7rem;
    text-decoration: none;
    transition: var(--transition);
}
.pagination-modern .page-link:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.pagination-modern .active .page-link {
    background: var(--green-600);
    border-color: var(--green-600);
    color: white;
}
.pagination-modern .disabled .page-link {
    opacity: .5;
    pointer-events: none;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 22px;
}
.modal-title i {
    color: var(--green-600);
}
.modal-body {
    padding: 22px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 22px;
}
.modal-card {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    margin-bottom: 16px;
}
.modal-card-title {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 8px;
}
</style>

<div class="payments-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Paiements</span>
    </div>

    
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-money-bill-wave"></i></span>
                <h1>Gestion des <em>paiements</em></h1>
            </div>
            <p class="header-subtitle"><?php echo e($payments->total()); ?> paiement(s) enregistré(s)</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?status=all"><i class="fas fa-list"></i> Tous</a></li>
                    <li><a class="dropdown-item" href="?status=completed"><i class="fas fa-check-circle" style="color:var(--green-600);"></i> Complétés</a></li>
                    <li><a class="dropdown-item" href="?status=pending"><i class="fas fa-clock" style="color:var(--red-500);"></i> En attente</a></li>
                    <li><a class="dropdown-item" href="?period=today"><i class="fas fa-calendar-day"></i> Aujourd'hui</a></li>
                </ul>
            </div>
            <button class="btn btn-outline" onclick="exportPayments()"><i class="fas fa-file-export"></i> Exporter</button>
            <button class="btn btn-outline" onclick="location.reload()"><i class="fas fa-sync-alt"></i></button>
        </div>
    </div>

    
    <?php
        $total = $payments->sum('amount');
        $today = $payments->where('created_at', '>=', now()->startOfDay())->sum('amount');
    ?>
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e(number_format($total, 0, ',', ' ')); ?></div>
                <div class="stat-label">Total</div>
                <span class="stat-badge">Global</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e(number_format($today, 0, ',', ' ')); ?></div>
                <div class="stat-label">Aujourd'hui</div>
                <span class="stat-badge"><?php echo e(now()->format('d/m')); ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($payments->unique('transaction.customer_id')->count()); ?></div>
                <div class="stat-label">Clients</div>
                <span class="stat-badge">Uniques</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-bed"></i></div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($payments->unique('transaction.room_id')->count()); ?></div>
                <div class="stat-label">Chambres</div>
                <span class="stat-badge">Réservations</span>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-credit-card"></i> Historique des paiements</h5>
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher..." onkeyup="filterPayments()">
            </div>
        </div>
        <div class="card-body">
            <?php if($payments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client & Chambre</th>
                                <th>Détails</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Par</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $badge = match($payment->status) {
                                    'completed' => 'badge-green',
                                    'pending' => 'badge-orange',
                                    'failed' => 'badge-red',
                                    'refunded' => 'badge-blue',
                                    default => 'badge-gray'
                                };
                                $text = match($payment->status) {
                                    'completed' => 'Complété',
                                    'pending' => 'En attente',
                                    'failed' => 'Échoué',
                                    'refunded' => 'Remboursé',
                                    default => $payment->status
                                };
                                $isToday = $payment->created_at->isToday();
                            ?>
                            <tr class="<?php echo e($isToday ? 'today' : ''); ?>">
                                <td><span class="badge badge-gray">#<?php echo e($payment->id); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar"><i class="fas fa-user"></i></div>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($payment->transaction->customer->name ?? 'N/A'); ?></div>
                                            <small class="text-muted"><i class="fas fa-bed"></i> Ch. <?php echo e($payment->transaction->room->number ?? 'N/A'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold" style="color:var(--green-600);"><?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA</div>
                                    <div class="ref-badge mt-1"><i class="fas fa-fingerprint"></i> <?php echo e($payment->reference ?? 'N/A'); ?></div>
                                    <?php if($payment->payment_method): ?>
                                    <small class="text-muted d-block mt-1"><i class="fas fa-credit-card"></i> <?php echo e($payment->payment_method); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div><?php echo e($payment->created_at->format('d/m/Y')); ?></div>
                                    <small class="text-muted"><?php echo e($payment->created_at->format('H:i')); ?></small>
                                </td>
                                <td><span class="badge <?php echo e($badge); ?>"><i class="fas fa-<?php echo e($payment->status == 'completed' ? 'check-circle' : ($payment->status == 'pending' ? 'clock' : 'times')); ?>"></i> <?php echo e($text); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm"><i class="fas fa-user-tie"></i></div>
                                        <span><?php echo e($payment->user->name ?? 'Système'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions-group">
                                        <a href="<?php echo e(route('payment.invoice', $payment->id)); ?>" class="btn-icon" title="Facture"><i class="fas fa-file-invoice"></i></a>
                                        <a href="<?php echo e(route('transaction.show', $payment->transaction_id)); ?>" class="btn-icon" title="Transaction"><i class="fas fa-external-link-alt"></i></a>
                                        <button class="btn-icon" onclick="showPaymentDetails(<?php echo e($payment->id); ?>)"><i class="fas fa-info-circle"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if($payments->hasPages()): ?>
                <div class="p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted"><?php echo e($payments->firstItem()); ?>-<?php echo e($payments->lastItem()); ?> sur <?php echo e($payments->total()); ?></small>
                        <ul class="pagination-modern">
                            <?php if($payments->onFirstPage()): ?>
                                <li class="page-item disabled"><span class="page-link">‹</span></li>
                            <?php else: ?>
                                <li class="page-item"><a class="page-link" href="<?php echo e($payments->previousPageUrl()); ?>">‹</a></li>
                            <?php endif; ?>
                            <?php for($i=1; $i<=$payments->lastPage(); $i++): ?>
                                <li class="page-item <?php echo e($i == $payments->currentPage() ? 'active' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($payments->url($i)); ?>"><?php echo e($i); ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if($payments->hasMorePages()): ?>
                                <li class="page-item"><a class="page-link" href="<?php echo e($payments->nextPageUrl()); ?>">›</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">›</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h3>Aucun paiement</h3>
                    <p>Aucun paiement trouvé dans la base</p>
                    <a href="<?php echo e(route('dashboard.index')); ?>" class="btn btn-gray">Retour</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>


<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle" style="color:var(--green-600);"></i> Détails du paiement</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button class="btn btn-gray" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function filterPayments() {
    const term = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#paymentsTable tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
}

function exportPayments() {
    if (confirm('Exporter en CSV ?')) window.location.href = '<?php echo e(route("transaction.export", "payments")); ?>';
}

function showPaymentDetails(id) {
    fetch(`/payments/${id}/details`)
        .then(r => r.json())
        .then(d => {
            if (!d.success) return alert('Erreur');
            const p = d.payment;
            document.getElementById('modalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-card">
                            <div class="modal-card-title">Paiement</div>
                            <div class="fs-3 fw-bold" style="color:var(--green-600);">${p.amount_formatted}</div>
                            <div class="ref-badge mt-2"><i class="fas fa-fingerprint"></i> ${p.reference}</div>
                            <div class="mt-2"><span class="badge ${p.status_color == 'success' ? 'badge-green' : 'badge-orange'}">${p.status}</span></div>
                            <div class="mt-2"><small class="text-muted">Méthode: ${p.method}</small></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-card">
                            <div class="modal-card-title">Client & Chambre</div>
                            <div class="fw-semibold">${p.guest_name}</div>
                            <div class="mt-2"><span class="badge badge-green">Chambre ${p.room_number}</span></div>
                            <div class="mt-2"><small class="text-muted">Transaction #${p.transaction_id}</small></div>
                        </div>
                    </div>
                </div>
                <div class="modal-card">
                    <div class="modal-card-title">Traitement</div>
                    <div class="row">
                        <div class="col-sm-6"><small class="text-muted">Date: </small>${p.date_formatted}</div>
                        <div class="col-sm-6"><small class="text-muted">Par: </small>${p.processed_by}</div>
                        <div class="col-sm-6 mt-2"><small class="text-muted">Créé: </small>${p.created_at}</div>
                    </div>
                </div>
                ${p.notes ? `<div class="modal-card"><div class="modal-card-title">Notes</div>${p.notes}</div>` : ''}
            `;
            new bootstrap.Modal(document.getElementById('paymentModal')).show();
        });
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/payment/index.blade.php ENDPATH**/ ?>