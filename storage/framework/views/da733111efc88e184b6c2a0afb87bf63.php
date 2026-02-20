
<?php $__env->startSection('title', 'Check-in — Recherche'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM (identique à la page check-in)
═══════════════════════════════════════════════════════════════ */
:root {
    --primary-50: #ecfdf5;
    --primary-100: #d1fae5;
    --primary-400: #34d399;
    --primary-500: #10b981;
    --primary-600: #059669;
    --primary-700: #047857;
    --primary-800: #065f46;

    --amber-50: #fffbeb;
    --amber-100: #fef3c7;
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --amber-600: #d97706;

    --blue-50: #eff6ff;
    --blue-100: #dbeafe;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;

    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

/* Layout principal */
.search-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', system-ui, sans-serif;
}

/* Breadcrumb */
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.813rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}

.breadcrumb-custom a {
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-custom a:hover {
    color: var(--primary-600);
}

.breadcrumb-custom .separator {
    color: var(--gray-300);
}

.breadcrumb-custom .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* En-tête */
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

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(5, 150, 105, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

/* Search Container */
.search-container {
    background: linear-gradient(135deg, #065f46 0%, #059669 100%);
    border-radius: 20px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
}

.search-container h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.search-container .input-group {
    background: white;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.search-container .input-group-text {
    background: white;
    border: none;
    color: var(--primary-600);
    padding-left: 20px;
}

.search-container .form-control {
    border: none;
    padding: 15px 5px 15px 0;
    font-size: 1rem;
}

.search-container .form-control:focus {
    box-shadow: none;
}

.search-container .btn-light {
    border-radius: 50px;
    padding: 12px;
    font-weight: 600;
    background: white;
    border: none;
    transition: all 0.2s;
}

.search-container .btn-light:hover {
    background: var(--gray-100);
    transform: translateY(-1px);
}

/* Quick filters */
.quick-filters {
    margin-top: 16px;
}

.quick-filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    font-size: 0.813rem;
    font-weight: 500;
    color: white;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.quick-filter-badge:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

/* Loading indicator */
.search-loading {
    display: none;
}

.search-loading.active {
    display: block;
}

.loading-spinner {
    text-align: center;
    padding: 40px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--gray-200);
    border-top-color: var(--primary-600);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Cards */
.card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
}

.card-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
}

.card-header h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body {
    padding: 24px;
}

/* Résultats de recherche */
.search-results {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 8px;
}

.search-results::-webkit-scrollbar {
    width: 6px;
}

.search-results::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 10px;
}

.search-results::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 10px;
}

.search-results::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}

.reservation-item {
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.2s;
    background: white;
}

.reservation-item:hover {
    border-color: var(--primary-300);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.reservation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}

.reservation-id {
    font-weight: 700;
    color: var(--primary-600);
    font-size: 1rem;
    background: var(--primary-50);
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.reservation-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-reservation { 
    background: var(--amber-100); 
    color: var(--amber-700);
    border: 1px solid var(--amber-200);
}
.status-active { 
    background: var(--primary-100); 
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}
.status-completed { 
    background: var(--blue-100); 
    color: var(--blue-700);
    border: 1px solid var(--blue-200);
}

.customer-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.25rem;
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.2);
}

.room-badge {
    background: var(--primary-50);
    color: var(--primary-700);
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.813rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--primary-100);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.empty-state h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--gray-400);
    margin-bottom: 24px;
}

/* Guide cards */
.guide-card {
    text-align: center;
    padding: 20px;
    border-radius: 12px;
    background: var(--gray-50);
    height: 100%;
    transition: all 0.2s;
}

.guide-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.guide-card i {
    font-size: 2rem;
    margin-bottom: 12px;
}

.guide-card h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.guide-card p {
    font-size: 0.75rem;
    color: var(--gray-400);
    margin: 0;
}

/* Buttons group */
.btn-group-custom {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

.btn-success {
    background: var(--primary-600);
    color: white;
    border: none;
}

.btn-success:hover {
    background: var(--primary-700);
}

.btn-outline-success {
    background: white;
    color: var(--primary-600);
    border: 1px solid var(--primary-200);
}

.btn-outline-success:hover {
    background: var(--primary-50);
    border-color: var(--primary-300);
    color: var(--primary-700);
}

.btn-outline-info {
    background: white;
    color: var(--blue-600);
    border: 1px solid var(--blue-200);
}

.btn-outline-info:hover {
    background: var(--blue-50);
    border-color: var(--blue-300);
    color: var(--blue-700);
}

/* Badges */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.688rem;
    font-weight: 600;
}

.badge-primary {
    background: var(--primary-100);
    color: var(--primary-700);
}

/* Alert */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: var(--primary-50);
    border-color: var(--primary-100);
    color: var(--primary-800);
}

.alert-dismissible .btn-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: 0.5;
    cursor: pointer;
}

.alert-dismissible .btn-close:hover {
    opacity: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .search-page {
        padding: 16px;
    }
    
    .reservation-item .row {
        gap: 16px;
    }
    
    .customer-avatar {
        margin-bottom: 8px;
    }
    
    .btn-group-custom {
        justify-content: flex-start;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-item {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>

<div class="search-page">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('checkin.index')); ?>">Check-in</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Recherche</span>
    </nav>

    <!-- En-tête -->
    <div class="page-header">
        <div>
            <div class="header-title">
                <span class="header-icon">
                    <i class="fas fa-search"></i>
                </span>
                <h1>Recherche de réservations</h1>
            </div>
            <p class="header-subtitle">Trouvez rapidement une réservation pour le check-in</p>
        </div>
        <a href="<?php echo e(route('checkin.index')); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left me-2"></i>Retour au check-in
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="search-container">
        <h4>
            <i class="fas fa-search me-2"></i>Rechercher une réservation
        </h4>
        <form method="GET" action="<?php echo e(route('checkin.search')); ?>" id="search-form">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               id="search-input"
                               placeholder="Nom du client, téléphone, email, numéro de chambre..."
                               value="<?php echo e($search ?? ''); ?>"
                               autocomplete="off"
                               autofocus>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-light w-100">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </div>
            
            <!-- Filtres rapides -->
            <div class="quick-filters">
                <div class="d-flex flex-wrap gap-2">
                    <span class="quick-filter-badge" onclick="setFilter('arrivals-today')">
                        <i class="fas fa-calendar-day"></i>Arrivées aujourd'hui
                    </span>
                    <span class="quick-filter-badge" onclick="setFilter('departures-today')">
                        <i class="fas fa-sign-out-alt"></i>Départs aujourd'hui
                    </span>
                    <span class="quick-filter-badge" onclick="setFilter('reservation')">
                        <i class="fas fa-calendar-check"></i>Réservations
                    </span>
                    <span class="quick-filter-badge" onclick="setFilter('active')">
                        <i class="fas fa-bed"></i>Dans l'hôtel
                    </span>
                </div>
            </div>
        </form>
    </div>

    <!-- Indicateur de chargement -->
    <div class="search-loading" id="loading-indicator">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p class="text-muted">Recherche en cours...</p>
        </div>
    </div>

    <!-- Résultats -->
    <div class="card">
        <div class="card-header">
            <h5>
                <i class="fas fa-list-ul" style="color: var(--primary-500);"></i>
                Résultats de recherche
            </h5>
            <?php if(isset($search) && $search): ?>
                <div>
                    <span class="badge badge-primary"><?php echo e($reservations->count()); ?> résultat(s)</span>
                    <a href="<?php echo e(route('checkin.search')); ?>" class="btn btn-outline btn-sm ms-2">
                        <i class="fas fa-times me-1"></i>Effacer
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if(!isset($search) || !$search): ?>
                <!-- État initial -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Recherchez une réservation</h4>
                    <p>Utilisez la barre de recherche ci-dessus pour trouver des réservations par nom, téléphone, email ou numéro de chambre.</p>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-user text-primary"></i>
                                <h6>Par nom</h6>
                                <p>Ex: "Dupont"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-phone text-success"></i>
                                <h6>Par téléphone</h6>
                                <p>Ex: "0123456789"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-envelope text-warning"></i>
                                <h6>Par email</h6>
                                <p>Ex: "client@email.com"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-door-closed text-info"></i>
                                <h6>Par chambre</h6>
                                <p>Ex: "101"</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif($reservations->isEmpty()): ?>
                <!-- Aucun résultat -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search-minus"></i>
                    </div>
                    <h4>Aucun résultat trouvé</h4>
                    <p>Aucune réservation ne correspond à votre recherche "<?php echo e($search); ?>"</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?php echo e(route('checkin.search')); ?>" class="btn btn-outline">
                            <i class="fas fa-search me-2"></i>Nouvelle recherche
                        </a>
                        <a href="<?php echo e(route('checkin.direct')); ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Check-in direct
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Liste des résultats -->
                <div class="search-results">
                    <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="reservation-item animate-item">
                            <div class="reservation-header">
                                <div>
                                    <span class="reservation-id">
                                        <i class="fas fa-hashtag fa-xs"></i>
                                        Réservation #<?php echo e($transaction->id); ?>

                                    </span>
                                    <span class="reservation-status status-<?php echo e($transaction->status); ?> ms-2">
                                        <?php echo e($transaction->status_label); ?>

                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?php echo e($transaction->created_at->format('d/m/Y')); ?>

                                </small>
                            </div>
                            
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="customer-avatar">
                                        <?php echo e(substr($transaction->customer->name, 0, 1)); ?>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-2 fw-semibold"><?php echo e($transaction->customer->name); ?></h6>
                                    <div class="text-muted small">
                                        <div class="mb-1">
                                            <i class="fas fa-phone fa-xs me-2" style="color: var(--gray-400);"></i>
                                            <?php echo e($transaction->customer->phone); ?>

                                        </div>
                                        <?php if($transaction->customer->email): ?>
                                            <div>
                                                <i class="fas fa-envelope fa-xs me-2" style="color: var(--gray-400);"></i>
                                                <?php echo e($transaction->customer->email); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <span class="room-badge mb-2">
                                        <i class="fas fa-door-closed"></i>
                                        Chambre <?php echo e($transaction->room->number); ?>

                                    </span>
                                    <div class="text-muted small">
                                        <i class="fas fa-bed me-1"></i>
                                        <?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-md-end">
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Arrivée</small>
                                            <strong><?php echo e($transaction->check_in->format('d/m/Y H:i')); ?></strong>
                                        </div>
                                        
                                        <div class="btn-group-custom">
                                            <?php if($transaction->status == 'reservation'): ?>
                                                <a href="<?php echo e(route('checkin.show', $transaction)); ?>" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-door-open me-1"></i> Check-in
                                                </a>
                                                <button onclick="quickCheckIn(<?php echo e($transaction->id); ?>)" 
                                                        class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-bolt"></i>
                                                </button>
                                            <?php elseif($transaction->status == 'active'): ?>
                                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" 
                                                   class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye me-1"></i> Voir
                                                </a>
                                                <button onclick="checkoutGuest(<?php echo e($transaction->id); ?>)" 
                                                        class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-sign-out-alt me-1"></i> Check-out
                                                </button>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-eye me-1"></i> Détails
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations supplémentaires -->
                            <div class="row mt-3 pt-3" style="border-top: 1px solid var(--gray-100);">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--primary-400);"></i>
                                        <strong>Durée:</strong> <?php echo e($transaction->nights); ?> nuit(s)
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-money-bill-wave me-1" style="color: var(--primary-400);"></i>
                                        <strong>Total:</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?>

                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-credit-card me-1" style="color: var(--primary-400);"></i>
                                        <strong>Payé:</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPayment())); ?>

                                        <?php if($transaction->getRemainingPayment() > 0): ?>
                                            <span class="text-warning ms-1">
                                                (Solde: <?php echo e(Helper::formatCFA($transaction->getRemainingPayment())); ?>)
                                            </span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <!-- Pagination -->
                <?php if($reservations->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($reservations->appends(['search' => $search])->links()); ?>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aide et actions rapides -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-lightbulb" style="color: var(--amber-400);"></i>
                        Conseils de recherche
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="color: var(--gray-600);">
                        <li class="mb-2">Utilisez les initiales pour une recherche plus large</li>
                        <li class="mb-2">Les numéros de téléphone peuvent être saisis avec ou sans indicatif</li>
                        <li class="mb-2">Recherchez par numéro de chambre pour voir tous les clients d'une chambre</li>
                        <li>Utilisez les filtres rapides pour les besoins courants</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-clock" style="color: var(--blue-400);"></i>
                        Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('checkin.index')); ?>" class="btn btn-outline">
                            <i class="fas fa-home me-2"></i>Retour au dashboard check-in
                        </a>
                        <a href="<?php echo e(route('checkin.direct')); ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Check-in direct (sans réservation)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
function setFilter(filterType) {
    const searchInput = document.getElementById('search-input');
    
    switch(filterType) {
        case 'arrivals-today':
            searchInput.value = 'arrivée:' + new Date().toLocaleDateString('fr-CA');
            break;
        case 'departures-today':
            searchInput.value = 'départ:' + new Date().toLocaleDateString('fr-CA');
            break;
        case 'reservation':
            searchInput.value = 'statut:reservation';
            break;
        case 'active':
            searchInput.value = 'statut:active';
            break;
    }
    
    document.getElementById('search-form').submit();
}

function quickCheckIn(transactionId) {
    if (confirm('Effectuer un check-in rapide sans formulaire détaillé ?')) {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.classList.add('active');
        
        fetch(`/checkin/${transactionId}/quick`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.classList.remove('active');
            
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-in rapide effectué avec succès!'}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                `;
                document.querySelector('.search-page').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                alert('Erreur: ' + (data.error || 'Échec du check-in rapide'));
            }
        })
        .catch(error => {
            loadingIndicator.classList.remove('active');
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-in rapide');
        });
    }
}

function checkoutGuest(transactionId) {
    if (confirm('Effectuer le check-out de ce client ?')) {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.classList.add('active');
        
        fetch(`/transaction/${transactionId}/check-out`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.classList.remove('active');
            
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-out effectué avec succès!'}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                `;
                document.querySelector('.search-page').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                alert('Erreur: ' + (data.message || 'Échec du check-out'));
            }
        })
        .catch(error => {
            loadingIndicator.classList.remove('active');
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-out');
        });
    }
}

// Recherche automatique après délai
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const loadingIndicator = document.getElementById('loading-indicator');
    
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        if (this.value.length >= 2) {
            searchTimeout = setTimeout(() => {
                loadingIndicator.classList.add('active');
                searchForm.submit();
            }, 1000);
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/checkin/search.blade.php ENDPATH**/ ?>