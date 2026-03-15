

<?php $__env->startSection('title', 'Dashboard Caissier'); ?>

<?php $__env->startPush('styles'); ?>
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
}

* { box-sizing: border-box; }

.dash-page {
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
   HEADER
══════════════════════════════════════════════ */
.dash-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 20px 0;
    margin-bottom: 24px;
}
.header-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title i {
    color: var(--green-600);
    margin-right: 10px;
}
.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    color: var(--gray-500);
}
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.role-admin { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.role-cashier { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.role-receptionist { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .active {
    color: var(--gray-600);
    font-weight: 500;
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
    transform: translateY(-1px);
}
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    color: white;
}
.btn-warning {
    background: var(--red-500);
    color: white;
}
.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   ALERTE PERMISSION
══════════════════════════════════════════════ */
.permission-alert {
    background: var(--gray-100);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.permission-icon {
    width: 40px;
    height: 40px;
    background: var(--white);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
    font-size: 1.2rem;
}
.permission-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}
.permission-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: 100px;
    font-size: .7rem;
}
.permission-badge i {
    color: var(--green-600);
}
.permission-badge i.fa-times {
    color: var(--red-500);
}

/* ══════════════════════════════════════════════
   SESSION CARDS
══════════════════════════════════════════════ */
.session-card {
    border-radius: var(--rxl);
    padding: 24px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.session-card.active {
    background: var(--green-50);
    border: 2px solid var(--green-600);
}
.session-card.inactive {
    background: var(--gray-100);
    border: 2px dashed var(--gray-400);
}
.session-icon {
    width: 50px;
    height: 50px;
    background: var(--white);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--green-600);
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}
.session-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    background: var(--white);
    border-radius: var(--r);
    font-size: .85rem;
    font-weight: 600;
    box-shadow: var(--shadow-xs);
}
.pulse {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { opacity:1; }
    50% { opacity:.6; }
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
    padding: 20px;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}
.stat-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 8px;
}
.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1;
}
.stat-subtitle {
    font-size: .7rem;
    color: var(--gray-400);
    margin-top: 4px;
}

/* ══════════════════════════════════════════════
   TABS
══════════════════════════════════════════════ */
.nav-tabs {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 6px;
    margin-bottom: 20px;
    display: flex;
    gap: 4px;
}
.nav-tab {
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-600);
    background: transparent;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}
.nav-tab:hover {
    background: var(--green-50);
    color: var(--green-700);
}
.nav-tab.active {
    background: var(--green-600);
    color: white;
}
.nav-tab .badge {
    margin-left: 6px;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .65rem;
    background: rgba(255,255,255,.2);
    color: white;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ── Payment badges ── */
.payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
}
.payment-cash { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.payment-card { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.payment-mobile { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
}
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
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.table tfoot {
    background: var(--gray-50);
    font-weight: 600;
}

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.user-avatar-sm {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    background: var(--gray-100);
    color: var(--gray-500);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    width: 72px;
    height: 72px;
    background: var(--gray-100);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: 16px;
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
    padding: 18px 24px;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 24px;
}
.alert-warning {
    background: var(--red-50);
    border: 1.5px solid var(--red-100);
    color: var(--red-500);
    padding: 14px 18px;
    border-radius: var(--rl);
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.summary-card {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    margin-bottom: 20px;
}
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed var(--gray-200);
}
.summary-item:last-child {
    border-bottom: none;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
}
.form-control:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dash-page">

    
    <div class="dash-header anim-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-cash-register"></i>
                    Dashboard Caissier
                </h1>
                <div class="user-badge mt-2">
                    Bonjour, <strong class="mx-1"><?php echo e(auth()->user()->name); ?></strong>
                    <span class="role-badge role-<?php echo e($isAdmin ? 'admin' : ($isCashier ? 'cashier' : 'receptionist')); ?>">
                        <i class="fas <?php echo e($isAdmin ? 'fa-crown' : ($isCashier ? 'fa-cash-register' : 'fa-user')); ?>"></i>
                        <?php echo e(auth()->user()->role); ?>

                    </span>
                </div>
            </div>
            
            <?php if($isAdmin && $canStartSession && !$activeSession): ?>
            <div>
                <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-green">
                    <i class="fas fa-plus"></i> Nouvelle session
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="breadcrumb">
            <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Accueil</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <span class="active">Caissier</span>
        </div>
    </div>

    
    <?php if(!$isAdmin): ?>
    <div class="permission-alert anim-2">
        <div class="permission-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">
                <?php if($isCashier): ?> Mode Caissier <?php else: ?> Mode Lecture Seule <?php endif; ?>
            </h6>
            <p class="small mb-2">
                <?php if($isCashier): ?>
                Vous pouvez gérer votre session et les paiements.
                <?php else: ?>
                Vous pouvez consulter les données mais seuls les administrateurs peuvent effectuer des modifications.
                <?php endif; ?>
            </p>
            <div class="permission-badges">
                <span class="permission-badge"><i class="fas fa-check" style="color:var(--green-600);"></i> Visualisation</span>
                <?php if($isCashier): ?>
                <span class="permission-badge"><i class="fas fa-check" style="color:var(--green-600);"></i> Paiements</span>
                <span class="permission-badge"><i class="fas fa-times" style="color:var(--red-500);"></i> Administration</span>
                <?php else: ?>
                <span class="permission-badge"><i class="fas fa-times" style="color:var(--red-500);"></i> Modification</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($activeSession): ?>
    <div class="session-card active anim-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon pulse">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-2">
                        Session Active #<?php echo e($activeSession->id); ?>

                        <?php if($activeSession->user_id != auth()->id()): ?>
                        <span class="badge badge-gray"><?php echo e($activeSession->user->name); ?></span>
                        <?php endif; ?>
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="session-badge"><i class="fas fa-user"></i> <?php echo e($activeSession->user->name); ?></div>
                        <div class="session-badge"><i class="fas fa-clock"></i> <?php echo e($activeSession->start_time->format('d/m/Y H:i')); ?></div>
                        <div class="session-badge"><i class="fas fa-hourglass-half"></i> <?php echo e($activeSession->start_time->diffForHumans(now(), true)); ?></div>
                        <div class="session-badge"><strong style="color:var(--green-600);"><?php echo e(number_format($activeSession->current_balance, 0, ',', ' ')); ?> FCFA</strong></div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('cashier.sessions.show', $activeSession)); ?>" class="btn btn-gray"><i class="fas fa-eye"></i> Détails</a>
                <?php if(($isAdmin && $activeSession->user_id == auth()->id()) || $isCashier || $isReceptionist): ?>
                <button class="btn btn-red" data-bs-toggle="modal" data-bs-target="#closeModal"><i class="fas fa-lock"></i> Clôturer</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="session-card inactive anim-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="session-icon" style="color:var(--red-500);">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Aucune session active</h5>
                    <p class="mb-0 text-muted">
                        <?php if($isAdmin || $isCashier): ?> Démarrez une nouvelle session pour commencer
                        <?php else: ?> Contactez un administrateur pour démarrer une session <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php if(($isAdmin || $isCashier) && $canStartSession): ?>
            <a href="<?php echo e(route('cashier.sessions.create')); ?>" class="btn btn-green"><i class="fas fa-play"></i> Démarrer</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-label">Réservations</div>
            <div class="stat-value"><?php echo e($todayStats['totalBookings']); ?></div>
            <div class="stat-subtitle">Aujourd'hui</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Chiffre d'affaires</div>
            <div class="stat-value"><?php echo e(number_format($todayStats['revenue'], 0, ',', ' ')); ?></div>
            <div class="stat-subtitle">FCFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Check-ins</div>
            <div class="stat-value"><?php echo e($todayStats['checkins']); ?></div>
            <div class="stat-subtitle">Aujourd'hui</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">En attente</div>
            <div class="stat-value"><?php echo e($todayStats['pendingPayments']); ?></div>
            <div class="stat-subtitle">Paiements</div>
        </div>
    </div>

    
    <div class="nav-tabs">
        <button class="nav-tab active" onclick="switchTab('pending')"><i class="fas fa-clock me-1"></i> Paiements <span class="badge"><?php echo e($pendingPayments->count()); ?></span></button>
        <button class="nav-tab" onclick="switchTab('sessions')"><i class="fas fa-history me-1"></i> Mes sessions <span class="badge"><?php echo e($recentSessions->count()); ?></span></button>
        <?php if($isAdmin): ?>
        <button class="nav-tab" onclick="switchTab('all-sessions')"><i class="fas fa-users me-1"></i> Toutes les sessions <span class="badge"><?php echo e($allSessionsCount ?? 0); ?></span></button>
        <?php endif; ?>
    </div>

    
    <div id="pending" class="tab-content active">
        <div class="table-card">
            <?php if($activeSession && $activeSession->payments && $activeSession->payments->count() > 0): ?>
                <?php
                    $totalEncaissements = $activeSession->payments->where('amount', '>', 0)->sum('amount');
                    $totalRemboursements = abs($activeSession->payments->where('amount', '<', 0)->sum('amount'));
                    $netTotal = $totalEncaissements - $totalRemboursements;
                ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Montant</th>
                            <th>Client</th>
                            <th>Méthode</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $activeSession->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong>#<?php echo e($payment->reference); ?></strong></td>
                            <td>
                                <?php if($payment->amount < 0): ?>
                                    <span class="badge badge-red">
                                        - <?php echo e(number_format(abs($payment->amount), 0, ',', ' ')); ?> FCFA
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-green">
                                        + <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($payment->transaction->customer->name ?? 'N/A'); ?></td>
                            <td>
                                <span class="payment-badge payment-cash">
                                    <i class="fas <?php echo e($payment->payment_method == 'cash' ? 'fa-money-bill-wave' : ($payment->payment_method == 'card' ? 'fa-credit-card' : 'fa-mobile-alt')); ?>"></i>
                                    <?php echo e($payment->payment_method_label); ?>

                                </span>
                            </td>
                            <td><small><?php echo e($payment->created_at->format('d/m H:i')); ?></small></td>
                            <td>
                                <button class="btn-icon" onclick="showPayment(<?php echo e($payment->id); ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <small class="text-muted">Encaissements:</small>
                                    <span class="badge badge-green"><?php echo e(number_format($totalEncaissements, 0, ',', ' ')); ?> FCFA</span>
                                    
                                    <small class="text-muted">Remboursements:</small>
                                    <span class="badge badge-red"><?php echo e(number_format($totalRemboursements, 0, ',', ' ')); ?> FCFA</span>
                                    
                                    <small class="text-muted">Net:</small>
                                    <strong class="badge <?php echo e($netTotal >= 0 ? 'badge-green' : 'badge-red'); ?>" style="font-size:.9rem; padding:6px 16px;">
                                        <?php echo e(number_format($netTotal, 0, ',', ' ')); ?> FCFA
                                    </strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h5 class="fw-bold mb-2">Aucun paiement</h5>
                <p class="text-muted">
                    <?php if($activeSession): ?> Aucun paiement pendant cette session 
                    <?php else: ?> Démarrez une session <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="sessions" class="tab-content">
        <div class="table-card">
            <?php if($recentSessions->count() > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Session</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>Initial</th>
                            <th>Final</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong>#<?php echo e($session->id); ?></strong></td>
                            <td><?php echo e($session->start_time->format('d/m H:i')); ?></td>
                            <td>
                                <?php if($session->end_time): ?>
                                    <?php echo e($session->end_time->format('d/m H:i')); ?>

                                <?php else: ?>
                                    <span class="badge badge-green">En cours</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $minutes = $session->start_time->diffInMinutes($session->end_time ?? now());
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                ?>
                                <span class="badge badge-gray"><?php echo e($hours); ?>h <?php echo e($mins); ?>min</span>
                            </td>
                            <td><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></td>
                            <td><?php echo e(number_format($session->final_balance ?? 0, 0, ',', ' ')); ?></td>
                            <td>
                                <span class="badge <?php echo e($session->status == 'active' ? 'badge-green' : 'badge-gray'); ?>">
                                    <?php echo e($session->status == 'active' ? 'Active' : 'Terminée'); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" class="btn-icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-history"></i></div>
                <h5 class="fw-bold mb-2">Aucune session</h5>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($isAdmin): ?>
    <div id="all-sessions" class="tab-content">
        <div class="table-card">
            <?php if(isset($allSessions) && $allSessions->count() > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Session</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>Initial</th>
                            <th>Final</th>
                            <th>Diff</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $allSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $diff = ($session->final_balance ?? 0) - $session->initial_balance; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-sm"><i class="fas fa-user"></i></div>
                                    <?php echo e($session->user->name ?? 'N/A'); ?>

                                </div>
                            </td>
                            <td><strong>#<?php echo e($session->id); ?></strong></td>
                            <td><?php echo e($session->start_time->format('d/m H:i')); ?></td>
                            <td>
                                <?php if($session->end_time): ?>
                                    <?php echo e($session->end_time->format('d/m H:i')); ?>

                                <?php else: ?>
                                    <span class="badge badge-green">En cours</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $minutes = $session->start_time->diffInMinutes($session->end_time ?? now());
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                ?>
                                <span class="badge badge-gray"><?php echo e($hours); ?>h <?php echo e($mins); ?>min</span>
                            </td>
                            <td><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></td>
                            <td><?php echo e(number_format($session->final_balance ?? 0, 0, ',', ' ')); ?></td>
                            <td>
                                <span class="badge <?php echo e($diff >= 0 ? 'badge-green' : 'badge-red'); ?>">
                                    <?php echo e($diff >= 0 ? '+' : ''); ?><?php echo e(number_format($diff, 0, ',', ' ')); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($session->status == 'active' ? 'badge-green' : 'badge-gray'); ?>">
                                    <?php echo e($session->status == 'active' ? 'Active' : 'Terminée'); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" class="btn-icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-users"></i></div>
                <h5 class="fw-bold mb-2">Aucune session</h5>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>


<?php if($activeSession && ($isAdmin || $isCashier || $isReceptionist) && $activeSession->user_id == auth()->id()): ?>
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock text-danger me-2"></i> Clôturer #<?php echo e($activeSession->id); ?>

                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('cashier.sessions.destroy', $activeSession)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    <div class="alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Action irréversible. Vérifiez le solde.
                    </div>
                    <div class="summary-card">
                        <h6 class="fw-bold mb-2">Récapitulatif</h6>
                        <div class="summary-item">
                            <span>Début</span>
                            <span><?php echo e($activeSession->start_time->format('d/m/Y H:i')); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Durée</span>
                            <span><?php echo e($activeSession->start_time->diffForHumans(now(), true)); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Solde théorique</span>
                            <span class="text-success"><?php echo e(number_format($activeSession->current_balance, 0, ',', ' ')); ?> FCFA</span>
                        </div>
                        <div class="summary-item">
                            <span>Paiements</span>
                            <span><?php echo e($activeSession->payments->count()); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Encaissements</span>
                            <span class="badge badge-green"><?php echo e(number_format($activeSession->payments->where('amount', '>', 0)->sum('amount'), 0, ',', ' ')); ?> FCFA</span>
                        </div>
                        <div class="summary-item">
                            <span>Remboursements</span>
                            <span class="badge badge-red"><?php echo e(number_format(abs($activeSession->payments->where('amount', '<', 0)->sum('amount')), 0, ',', ' ')); ?> FCFA</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Solde final réel</label>
                        <input type="number" name="final_balance" class="form-control" value="<?php echo e($activeSession->current_balance); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="closing_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-red">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.target.closest('.nav-tab').classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function showPayment(id) {
    alert('Détails paiement #' + id);
    // À implémenter: modal avec détails du paiement
}
</script>

<style>
.tab-content { display: none; }
.tab-content.active { display: block; }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/dashboard.blade.php ENDPATH**/ ?>