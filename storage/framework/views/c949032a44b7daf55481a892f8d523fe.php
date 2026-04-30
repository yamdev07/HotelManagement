
<?php $__env->startSection('title', 'Gestion des Clients'); ?>
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

.customers-page {
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
    box-shadow: var(--shadow-sm);
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
   ACTION BAR
══════════════════════════════════════════════ */
.action-bar {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.action-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.action-right {
    flex: 1;
    max-width: 400px;
}

/* ── Filter badges ── */
.filter-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
    text-decoration: none;
    transition: var(--transition);
}
.filter-badge:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.filter-badge.active {
    background: var(--green-50);
    color: var(--green-700);
    border-color: var(--green-200);
}
.badge-count {
    background: var(--white);
    padding: 2px 6px;
    border-radius: 100px;
    font-size: .6rem;
    color: var(--gray-600);
}

/* ── Search ── */
.search-container {
    position: relative;
    width: 100%;
}
.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    z-index: 2;
}
.search-input {
    width: 100%;
    padding: 10px 16px 10px 42px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    transition: var(--transition);
    background: var(--white);
}
.search-input:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.search-clear {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-400);
    cursor: pointer;
    padding: 4px;
    border-radius: var(--r);
}
.search-clear:hover {
    background: var(--gray-100);
    color: var(--gray-600);
}
.search-result-badge {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--green-50);
    color: var(--green-700);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    border: 1.5px solid var(--green-200);
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
.alert-blue {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
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
.alert-close:hover {
    opacity: 1;
}

/* ══════════════════════════════════════════════
   CUSTOMER GRID
══════════════════════════════════════════════ */
.customer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}
.customer-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    transition: var(--transition);
    cursor: pointer;
}
.customer-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* ── Header ── */
.customer-header {
    position: relative;
    height: 110px;
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    padding: 16px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}
.customer-badge {
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.2);
    padding: 4px 12px;
    border-radius: 100px;
    color: white;
    font-size: .65rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    backdrop-filter: blur(4px);
}
.customer-number {
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.2);
    width: 32px;
    height: 32px;
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: .8rem;
    backdrop-filter: blur(4px);
}
.customer-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-sm);
    object-fit: cover;
    position: absolute;
    bottom: -40px;
    left: 16px;
}

/* ── Body ── */
.customer-body {
    padding: 48px 18px 18px;
}
.customer-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.customer-name a {
    color: var(--gray-800);
    text-decoration: none;
}
.customer-name a:hover {
    color: var(--green-600);
}
.info-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 6px 0;
    border-bottom: 1px solid var(--gray-200);
}
.info-item:last-child {
    border-bottom: none;
}
.info-icon {
    width: 18px;
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
    font-size: .75rem;
    color: var(--gray-700);
    font-weight: 500;
}
.info-badge {
    background: var(--green-50);
    color: var(--green-700);
    border: 1.5px solid var(--green-200);
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}

/* ── Footer ── */
.customer-footer {
    display: flex;
    gap: 8px;
    padding: 14px 18px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--gray-50);
}
.footer-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 6px 10px;
    border-radius: var(--r);
    font-size: .7rem;
    font-weight: 600;
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-600);
    text-decoration: none;
    transition: var(--transition);
}
.footer-btn:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.footer-btn.green {
    background: var(--green-50);
    color: var(--green-700);
    border-color: var(--green-200);
}
.footer-btn.green:hover {
    background: var(--green-600);
    color: white;
    border-color: var(--green-600);
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
    border-top: 1px solid var(--gray-200);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
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
    font-size: .8rem;
    margin-bottom: 20px;
}
.search-term {
    background: var(--gray-100);
    border: 1.5px solid var(--gray-200);
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    display: inline-block;
    margin-bottom: 12px;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination {
    display: flex;
    gap: 4px;
    justify-content: center;
    margin-top: 24px;
}
.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-600);
    font-size: .75rem;
    transition: var(--transition);
}
.pagination .page-link:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.pagination .active .page-link {
    background: var(--green-600);
    border-color: var(--green-600);
    color: white;
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
    margin-right: 6px;
}
.modal-body {
    padding: 22px;
}
</style>

<div class="customers-page">

    
    <div class="breadcrumb anim-1">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Clients</span>
    </div>

    
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-users"></i></span>
                <h1>Gestion des <em>Clients</em></h1>
            </div>
            <p class="header-subtitle">Gérez votre base de données clients</p>
        </div>
    </div>

    
    <?php
        $totalClients = $customers->total();
        $resultCount = $customers->count();
    ?>
    
    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-number"><?php echo e($totalClients); ?></div>
            <div class="stat-label">Clients totaux</div>
            <div class="stat-footer"><i class="fas fa-user"></i> enregistrés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($resultCount); ?></div>
            <div class="stat-label">Sur cette page</div>
            <div class="stat-footer">Page <?php echo e($customers->currentPage()); ?>/<?php echo e($customers->lastPage()); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo e($customers->lastPage()); ?></div>
            <div class="stat-label">Pages totales</div>
            <div class="stat-footer"><?php echo e($customers->perPage()); ?> par page</div>
        </div>
    </div>

    
    <div class="action-bar anim-4">
        <div class="action-left">
            <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="fas fa-plus-circle"></i> Nouveau client
            </button>
            
            <div class="filter-badges">
                <a href="<?php echo e(route('customer.index')); ?>" class="filter-badge <?php echo e(!request('search') ? 'active' : ''); ?>">
                    <i class="fas fa-users"></i> Tous
                    <span class="badge-count"><?php echo e($totalClients); ?></span>
                </a>
                <?php if(request('search')): ?>
                <a href="<?php echo e(route('customer.index')); ?>" class="filter-badge">
                    <i class="fas fa-times"></i> Effacer
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="<?php echo e(route('customer.index')); ?>" id="search-form">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Nom, email, téléphone..." 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           autocomplete="off">
                    <?php if(request('search')): ?>
                    <button type="button" class="search-clear" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="search-result-badge">
                        <i class="fas fa-check-circle fa-xs"></i> <?php echo e($customers->total()); ?>

                    </span>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="alert alert-green">
        <div class="alert-icon"><i class="fas fa-check"></i></div>
        <span><?php echo session('success'); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-red">
        <div class="alert-icon"><i class="fas fa-exclamation"></i></div>
        <span><?php echo e(session('error')); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php endif; ?>

    <?php if(request('search')): ?>
    <div class="alert alert-blue">
        <div class="alert-icon"><i class="fas fa-search"></i></div>
        <span><strong><?php echo e($customers->total()); ?></strong> résultat(s) pour "<strong><?php echo e(request('search')); ?></strong>"</span>
        <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-sm btn-outline ms-auto">Effacer</a>
    </div>
    <?php endif; ?>

    
    <?php if($customers->count() > 0): ?>
    <div class="customer-grid">
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $index = ($customers->currentPage() - 1) * $customers->perPage() + $loop->index + 1;
            $reservationsCount = $customer->transactions()->count();
        ?>
        <div class="customer-card" style="animation: fadeSlide .3s ease <?php echo e($loop->index * 0.03); ?>s both;">
            <div class="customer-header">
                <span class="customer-badge"><i class="fas fa-star"></i> Client #<?php echo e($index); ?></span>
                <span class="customer-number"><?php echo e($index); ?></span>
                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($customer->name)); ?>&background=1e6b2e&color=fff&size=80" 
                     alt="<?php echo e($customer->name); ?>" 
                     class="customer-avatar">
            </div>
            
            <div class="customer-body">
                <div class="customer-name">
                    <a href="<?php echo e(route('customer.show', $customer->id)); ?>"><?php echo e($customer->name); ?></a>
                    <div class="dropdown">
                        <button class="btn btn-sm p-0" data-bs-toggle="dropdown" style="color:var(--gray-400);">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.show', $customer->id)); ?>"><i class="fas fa-eye"></i> Voir</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('transaction.reservation.customerReservations', $customer->id)); ?>"><i class="fas fa-calendar-check"></i> Réservations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.edit', $customer->id)); ?>"><i class="fas fa-edit"></i> Modifier</a></li>
                            <li><button class="dropdown-item" style="color:var(--red-500);" onclick="confirmDelete('<?php echo e($customer->name); ?>', <?php echo e($customer->id); ?>)"><i class="fas fa-trash"></i> Supprimer</button></li>
                        </ul>
                    </div>
                </div>
                
                <?php if($customer->user && $customer->user->email): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div><div class="info-label">Email</div><div class="info-value"><?php echo e($customer->user->email); ?></div></div>
                </div>
                <?php endif; ?>
                
                <?php if($customer->phone): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                    <div><div class="info-label">Téléphone</div><div class="info-value"><?php echo e($customer->phone); ?></div></div>
                </div>
                <?php endif; ?>
                
                <?php if($customer->job): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-briefcase"></i></div>
                    <div><div class="info-label">Profession</div><div class="info-value"><?php echo e($customer->job); ?></div></div>
                </div>
                <?php endif; ?>
                
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="info-label">Réservations</div>
                        <div class="info-value"><span class="info-badge"><?php echo e($reservationsCount); ?></span></div>
                    </div>
                </div>
            </div>
            
            <div class="customer-footer">
                <a href="<?php echo e(route('customer.show', $customer->id)); ?>" class="footer-btn green">
                    <i class="fas fa-user"></i> Profil
                </a>
                <a href="<?php echo e(route('transaction.reservation.customerReservations', $customer->id)); ?>" class="footer-btn">
                    <i class="fas fa-calendar-check"></i> Réservations
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    
    <?php if($customers->hasPages()): ?>
    <div class="pagination">
        <?php echo e($customers->onEachSide(2)->links('pagination::bootstrap-5')); ?>

    </div>
    <?php endif; ?>
    
    <?php elseif(request('search')): ?>
    
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-search"></i></div>
        <span class="search-term">"<?php echo e(request('search')); ?>"</span>
        <h3>Aucun client trouvé</h3>
        <p>Essayez d'autres termes ou ajoutez un nouveau client.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-gray">Effacer</a>
            <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Ajouter</button>
        </div>
    </div>
    <?php else: ?>
    
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-users"></i></div>
        <h3>Aucun client</h3>
        <p>Commencez par ajouter votre premier client.</p>
        <button class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Ajouter</button>
    </div>
    <?php endif; ?>

    
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nouveau client</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div style="font-size: 3rem; color:var(--gray-300); margin-bottom:16px;"><i class="fas fa-user-circle"></i></div>
                    <p class="mb-4">Choisissez comment ajouter un client</p>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('customer.create')); ?>" class="btn btn-green">
                            <i class="fas fa-user-plus"></i> Créer un compte
                        </a>
                        <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <form id="delete-form" method="POST" style="display:none;"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>

</div>

<script>
function clearSearch() {
    document.getElementById('search-form').reset();
    document.getElementById('search-form').submit();
}

function confirmDelete(name, id) {
    Swal.fire({
        title: 'Confirmer',
        html: `Supprimer <strong>${name}</strong> ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b91c1c',
        cancelButtonColor: '#545954',
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler'
    }).then(r => {
        if (r.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/customer/${id}`;
            form.submit();
        }
    });
}

// Recherche automatique
let timeout;
document.getElementById('search-input')?.addEventListener('input', function() {
    clearTimeout(timeout);
    timeout = setTimeout(() => document.getElementById('search-form').submit(), 500);
});

// Raccourcis
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
    }
    if (e.key === '/' && !e.target.matches('input, textarea')) {
        e.preventDefault();
        document.getElementById('search-input')?.focus();
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/customer/index.blade.php ENDPATH**/ ?>