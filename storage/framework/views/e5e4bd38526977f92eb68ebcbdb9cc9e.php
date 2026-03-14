
<?php $__env->startSection('title', 'Profil Client - ' . $customer->name); ?>
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

.profile-page {
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
.anim-4 { animation: fadeSlide .4s .24s ease both; }

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

/* ══════════════════════════════════════════════
   ALERTS
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 12px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    width: 28px;
    height: 28px;
    border-radius: var(--r);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: .6;
    cursor: pointer;
}

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
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
.stat-footer {
    font-size: .7rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ══════════════════════════════════════════════
   PROFILE CARD
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}
.profile-header {
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    padding: 28px 24px;
    text-align: center;
}
.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid var(--white);
    box-shadow: var(--shadow-sm);
    object-fit: cover;
    margin-bottom: 16px;
}
.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 4px;
}
.profile-job {
    color: rgba(255,255,255,.9);
    font-size: .9rem;
    margin-bottom: 12px;
}
.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.2);
    border-radius: 100px;
    color: white;
    font-size: .75rem;
    font-weight: 600;
    backdrop-filter: blur(4px);
}
.profile-body {
    padding: 24px;
}
.profile-section {
    margin-bottom: 24px;
}
.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid var(--gray-200);
}
.section-title i {
    color: var(--green-600);
}
.info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-200);
}
.info-item:last-child {
    border-bottom: none;
}
.info-icon {
    width: 20px;
    color: var(--gray-400);
    font-size: .8rem;
    margin-top: 2px;
}
.info-label {
    font-size: .6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
}
.info-value {
    font-size: .8rem;
    color: var(--gray-700);
    font-weight: 500;
}
.info-value a {
    color: var(--green-600);
    text-decoration: none;
}
.info-value a:hover {
    text-decoration: underline;
}

/* ══════════════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════════════ */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.action-card {
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}
.action-card:hover {
    background: var(--white);
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.action-icon {
    width: 40px;
    height: 40px;
    background: var(--green-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    color: var(--green-600);
    font-size: 1rem;
    transition: var(--transition);
}
.action-card:hover .action-icon {
    background: var(--green-600);
    color: white;
}
.action-title {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 2px;
}
.action-subtitle {
    font-size: .65rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   RESERVATION CARD
══════════════════════════════════════════════ */
.reservations-grid {
    display: grid;
    gap: 16px;
}
.res-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    overflow: hidden;
    transition: var(--transition);
}
.res-card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-sm);
}
.res-header {
    background: var(--gray-50);
    padding: 14px 18px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.res-room {
    display: flex;
    align-items: center;
    gap: 12px;
}
.res-room-icon {
    width: 36px;
    height: 36px;
    background: var(--green-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
}
.res-room-info h6 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 2px;
}
.res-room-info small {
    color: var(--gray-500);
    font-size: .7rem;
}
.res-status {
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.status-active { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.status-upcoming { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.status-completed { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.res-body {
    padding: 18px;
}
.res-dates {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 16px;
}
.res-date {
    display: flex;
    align-items: center;
    gap: 10px;
}
.res-date-icon {
    width: 36px;
    height: 36px;
    background: var(--gray-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--green-600);
}
.res-date-info .label {
    font-size: .6rem;
    color: var(--gray-500);
    text-transform: uppercase;
}
.res-date-info .value {
    font-size: .85rem;
    font-weight: 600;
    color: var(--gray-800);
}
.res-date-info .time {
    font-size: .65rem;
    color: var(--gray-400);
}
.res-sep {
    color: var(--gray-300);
}
.res-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 12px 0;
    border-top: 1px dashed var(--gray-200);
    border-bottom: 1px dashed var(--gray-200);
    margin-bottom: 16px;
}
.res-detail-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.res-detail-label {
    font-size: .6rem;
    color: var(--gray-500);
    text-transform: uppercase;
}
.res-detail-value {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-800);
}
.res-detail-value small {
    font-size: .7rem;
    color: var(--gray-500);
}
.res-footer {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}
</style>

<div class="profile-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('customer.index')); ?>">Clients</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current"><?php echo e($customer->name); ?></span>
    </div>

    
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-user"></i></span>
                <h1><?php echo e($customer->name); ?></h1>
            </div>
            <p class="header-subtitle">Fiche client détaillée</p>
        </div>
        <a href="<?php echo e(route('customer.edit', $customer->id)); ?>" class="btn btn-gray">
            <i class="fas fa-edit"></i> Modifier
        </a>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-green">
        <div class="alert-icon"><i class="fas fa-check"></i></div>
        <span><?php echo session('success'); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    
    <?php
        $totalReservations = $customer->transactions->count();
        $activeReservations = $customer->transactions->where('check_out', '>=', now())->count();
        $completedReservations = $customer->transactions->where('check_out', '<', now())->count();
        
        $totalNights = 0;
        foreach($customer->transactions as $t) {
            $totalNights += \Carbon\Carbon::parse($t->check_in)->diffInDays($t->check_out);
        }
    ?>
    
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-number"><?php echo e($totalReservations); ?></div>
            <div class="stat-label">Réservations</div>
            <div class="stat-footer"><i class="fas fa-calendar-check"></i> totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($totalNights); ?></div>
            <div class="stat-label">Nuits passées</div>
            <div class="stat-footer"><i class="fas fa-moon"></i> au total</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($activeReservations); ?></div>
            <div class="stat-label">En cours</div>
            <div class="stat-footer"><i class="fas fa-clock"></i> actuellement</div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="profile-card anim-4">
                <div class="profile-header">
                    <?php
                        $avatarUrl = $customer->user ? $customer->user->getAvatar() : null;
                    ?>
                    <img src="<?php echo e($avatarUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&background=1e6b2e&color=fff&size=120'); ?>" 
                         alt="<?php echo e($customer->name); ?>" 
                         class="profile-avatar">
                    <h2 class="profile-name"><?php echo e($customer->name); ?></h2>
                    <?php if($customer->job): ?><div class="profile-job"><?php echo e($customer->job); ?></div><?php endif; ?>
                    <span class="profile-badge"><i class="fas fa-id-card"></i> Client #<?php echo e($customer->id); ?></span>
                </div>
                
                <div class="profile-body">
                    
                    
                    <div class="profile-section">
                        <div class="section-title">
                            <i class="fas fa-address-card"></i> Contact
                        </div>
                        
                        <?php if($customer->user && $customer->user->email): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div><span class="info-label">Email</span><div class="info-value"><a href="mailto:<?php echo e($customer->user->email); ?>"><?php echo e($customer->user->email); ?></a></div></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($customer->phone): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-phone"></i></div>
                            <div><span class="info-label">Téléphone</span><div class="info-value"><?php echo e($customer->phone); ?></div></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($customer->address): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div><span class="info-label">Adresse</span><div class="info-value"><?php echo e($customer->address); ?></div></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    
                    <div class="profile-section">
                        <div class="section-title">
                            <i class="fas fa-user-circle"></i> Personnel
                        </div>
                        
                        <?php if($customer->gender): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                            <div><span class="info-label">Genre</span><div class="info-value"><?php echo e($customer->gender); ?></div></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($customer->birthdate): ?>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-cake-candles"></i></div>
                            <div>
                                <span class="info-label">Date de naissance</span>
                                <div class="info-value"><?php echo e(\Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y')); ?> (<?php echo e(\Carbon\Carbon::parse($customer->birthdate)->age); ?> ans)</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div>
                                <span class="info-label">Client depuis</span>
                                <div class="info-value"><?php echo e($customer->created_at->format('d/m/Y')); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="profile-section">
                        <div class="section-title">
                            <i class="fas fa-bolt"></i> Actions rapides
                        </div>
                        
                        <div class="actions-grid">
                            <a href="<?php echo e(route('customer.edit', $customer->id)); ?>" class="action-card">
                                <div class="action-icon"><i class="fas fa-edit"></i></div>
                                <div class="action-title">Modifier</div>
                                <div class="action-subtitle">Mettre à jour</div>
                            </a>
                            
                            <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>?customer_id=<?php echo e($customer->id); ?>" class="action-card">
                                <div class="action-icon"><i class="fas fa-calendar-plus"></i></div>
                                <div class="action-title">Réserver</div>
                                <div class="action-subtitle">Nouveau séjour</div>
                            </a>
                            
                            <a href="<?php echo e(route('transaction.reservation.customerReservations', $customer->id)); ?>" class="action-card">
                                <div class="action-icon"><i class="fas fa-history"></i></div>
                                <div class="action-title">Historique</div>
                                <div class="action-subtitle">Toutes les réservations</div>
                            </a>
                            
                            <a href="<?php echo e(route('customer.index')); ?>" class="action-card">
                                <div class="action-icon"><i class="fas fa-arrow-left"></i></div>
                                <div class="action-title">Retour</div>
                                <div class="action-subtitle">Liste des clients</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-lg-8">
            
            <div class="profile-card anim-4">
                <div class="profile-header" style="background: linear-gradient(135deg, var(--gray-700), var(--gray-800));">
                    <h2 class="profile-name" style="font-size:1.2rem;"><i class="fas fa-calendar-check me-2"></i> Réservations en cours</h2>
                    <span class="profile-badge"><?php echo e($activeReservations); ?> active(s)</span>
                </div>
                
                <div class="profile-body">
                    <?php
                        $activeStays = $customer->transactions()->where('check_out', '>=', now())->orderBy('check_in', 'desc')->with('room')->get();
                    ?>
                    
                    <?php if($activeStays->count() > 0): ?>
                        <div class="reservations-grid">
                            <?php $__currentLoopData = $activeStays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isActive = $t->check_in <= now() && $t->check_out >= now();
                                    $checkIn = \Carbon\Carbon::parse($t->check_in);
                                    $checkOut = \Carbon\Carbon::parse($t->check_out);
                                    $nights = $checkIn->diffInDays($checkOut);
                                    $balance = $t->getTotalPrice() - $t->getTotalPayment();
                                ?>
                                
                                <div class="res-card">
                                    <div class="res-header">
                                        <div class="res-room">
                                            <div class="res-room-icon"><i class="fas fa-bed"></i></div>
                                            <div class="res-room-info">
                                                <h6>Chambre <?php echo e($t->room->number ?? 'N/A'); ?></h6>
                                                <small><?php echo e($t->room->type->name ?? 'Standard'); ?></small>
                                            </div>
                                        </div>
                                        <span class="res-status status-<?php echo e($isActive ? 'active' : 'upcoming'); ?>">
                                            <i class="fas fa-<?php echo e($isActive ? 'user-check' : 'clock'); ?>"></i>
                                            <?php echo e($isActive ? 'En cours' : 'À venir'); ?>

                                        </span>
                                    </div>
                                    
                                    <div class="res-body">
                                        <div class="res-dates">
                                            <div class="res-date">
                                                <div class="res-date-icon"><i class="fas fa-sign-in-alt"></i></div>
                                                <div class="res-date-info">
                                                    <span class="label">Arrivée</span>
                                                    <div class="value"><?php echo e($checkIn->format('d/m/Y')); ?></div>
                                                    <div class="time"><?php echo e($checkIn->format('H:i')); ?></div>
                                                </div>
                                            </div>
                                            <div class="res-sep"><i class="fas fa-arrow-right"></i></div>
                                            <div class="res-date">
                                                <div class="res-date-icon"><i class="fas fa-sign-out-alt"></i></div>
                                                <div class="res-date-info">
                                                    <span class="label">Départ</span>
                                                    <div class="value"><?php echo e($checkOut->format('d/m/Y')); ?></div>
                                                    <div class="time"><?php echo e($checkOut->format('H:i')); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="res-details">
                                            <div class="res-detail-item">
                                                <span class="res-detail-label">Nuits</span>
                                                <span class="res-detail-value"><?php echo e($nights); ?></span>
                                            </div>
                                            <div class="res-detail-item">
                                                <span class="res-detail-label">Total</span>
                                                <span class="res-detail-value"><?php echo e(number_format($t->total_price, 0, ',', ' ')); ?> <small>FCFA</small></span>
                                            </div>
                                            <?php if($balance > 0): ?>
                                            <div class="res-detail-item">
                                                <span class="res-detail-label">Solde</span>
                                                <span class="res-detail-value" style="color:var(--red-500);"><?php echo e(number_format($balance, 0, ',', ' ')); ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="res-footer">
                                            <a href="<?php echo e(route('transaction.show', $t->id)); ?>" class="btn btn-outline btn-sm">Détails</a>
                                            <?php if($balance > 0): ?>
                                            <a href="<?php echo e(route('transaction.payment.create', $t->id)); ?>" class="btn btn-green btn-sm">Payer</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x" style="color:var(--gray-300); margin-bottom:12px;"></i>
                            <h6>Aucune réservation en cours</h6>
                            <p class="text-muted small">Ce client n'a pas de réservation active.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            
            <?php
                $pastStays = $customer->transactions()->where('check_out', '<', now())->orderBy('check_out', 'desc')->take(3)->with('room')->get();
            ?>
            
            <?php if($pastStays->count() > 0): ?>
            <div class="profile-card mt-4 anim-4">
                <div class="profile-header" style="background: linear-gradient(135deg, var(--gray-600), var(--gray-700));">
                    <h2 class="profile-name" style="font-size:1.2rem;"><i class="fas fa-history me-2"></i> Historique</h2>
                    <span class="profile-badge"><?php echo e($completedReservations); ?> terminée(s)</span>
                </div>
                
                <div class="profile-body">
                    <div class="reservations-grid">
                        <?php $__currentLoopData = $pastStays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $checkIn = \Carbon\Carbon::parse($t->check_in);
                                $checkOut = \Carbon\Carbon::parse($t->check_out);
                                $nights = $checkIn->diffInDays($checkOut);
                            ?>
                            
                            <div class="res-card">
                                <div class="res-header">
                                    <div class="res-room">
                                        <div class="res-room-icon"><i class="fas fa-bed"></i></div>
                                        <div class="res-room-info">
                                            <h6>Chambre <?php echo e($t->room->number ?? 'N/A'); ?></h6>
                                            <small><?php echo e($t->room->type->name ?? 'Standard'); ?></small>
                                        </div>
                                    </div>
                                    <span class="res-status status-completed"><i class="fas fa-check-circle"></i> Terminé</span>
                                </div>
                                
                                <div class="res-body">
                                    <div class="res-dates mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted">Du</small>
                                            <span class="fw-semibold"><?php echo e($checkIn->format('d/m/Y')); ?></span>
                                            <i class="fas fa-arrow-right text-muted fa-xs"></i>
                                            <small class="text-muted">au</small>
                                            <span class="fw-semibold"><?php echo e($checkOut->format('d/m/Y')); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="res-details mb-3">
                                        <div class="res-detail-item">
                                            <span class="res-detail-label">Nuits</span>
                                            <span class="res-detail-value"><?php echo e($nights); ?></span>
                                        </div>
                                        <div class="res-detail-item">
                                            <span class="res-detail-label">Total</span>
                                            <span class="res-detail-value"><?php echo e(number_format($t->total_price, 0, ',', ' ')); ?> <small>FCFA</small></span>
                                        </div>
                                    </div>
                                    
                                    <div class="res-footer">
                                        <a href="<?php echo e(route('transaction.show', $t->id)); ?>" class="btn btn-outline btn-sm">Voir</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($completedReservations > 3): ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('transaction.reservation.customerReservations', $customer->id)); ?>" class="btn btn-outline btn-sm">
                            Voir tout l'historique
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity .5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }, 5000);
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/customer/show.blade.php ENDPATH**/ ?>