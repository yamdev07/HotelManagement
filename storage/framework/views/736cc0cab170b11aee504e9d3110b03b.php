
<?php $__env->startSection('title', 'Recherche de disponibilité'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE CHECK-IN
═══════════════════════════════════════════════════════════════════ */
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

    --red-50: #fee2e2;
    --red-500: #ef4444;
    --red-600: #dc2626;

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

    --white: #ffffff;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.availability-page {
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
    margin-bottom: 24px;
    flex-wrap: wrap;
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
    font-size: 0.688rem;
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
    margin-bottom: 28px;
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

.header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Boutons */
.btn-modern {
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

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(5, 150, 105, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline-modern {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-success-modern {
    background: var(--primary-600);
    color: white;
}

.btn-success-modern:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
}

.btn-danger-modern {
    background: var(--red-500);
    color: white;
}

.btn-danger-modern:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

/* Cartes */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    height: 100%;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 16px 20px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
}

.card-header-modern h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h6 i {
    color: var(--primary-500);
}

.card-header-success {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
}

.card-header-success h6 {
    color: white;
}

.card-header-success h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-header-danger {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.card-header-danger h6 {
    color: white;
}

.card-header-danger h6 i {
    color: rgba(255, 255, 255, 0.9);
}

.card-body-modern {
    padding: 20px;
}

/* Formulaire de recherche */
.search-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    margin-bottom: 28px;
}

.search-card-body {
    padding: 24px;
}

.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.form-group-modern {
    margin-bottom: 16px;
}

.form-label-modern {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--gray-700);
    transition: all 0.2s;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-select-modern {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--gray-700);
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
}

.form-select-modern:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.search-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
    flex-wrap: wrap;
}

/* Info panel */
.info-panel {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--gray-200);
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-100);
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.688rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-weight: 600;
    color: var(--gray-800);
}

/* Résultats */
.results-count {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px 16px;
    background: white;
    border-radius: 12px;
    border: 1px solid var(--gray-200);
}

.results-count-badge {
    background: var(--primary-100);
    color: var(--primary-700);
    padding: 4px 12px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.813rem;
}

.results-count-text {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
}

.room-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.room-card.available {
    border-top: 4px solid var(--primary-500);
}

.room-card.unavailable {
    border-top: 4px solid var(--red-500);
    opacity: 0.9;
}

.room-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.room-card-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.room-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.room-type {
    display: inline-block;
    padding: 4px 12px;
    background: var(--primary-100);
    color: var(--primary-700);
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}

.room-card.unavailable .room-type {
    background: var(--red-100);
    color: var(--red-700);
}

.room-price {
    text-align: right;
}

.room-price-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
}

.room-price-night {
    font-size: 0.688rem;
    color: var(--gray-500);
}

.room-card-body {
    padding: 20px;
    flex: 1;
}

.room-features {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.room-feature {
    display: flex;
    align-items: center;
    gap: 12px;
}

.feature-icon {
    width: 24px;
    color: var(--gray-400);
    font-size: 0.875rem;
}

.feature-text {
    font-size: 0.813rem;
    color: var(--gray-600);
}

.feature-text strong {
    color: var(--gray-800);
    font-weight: 600;
}

.room-facilities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.facility-badge {
    padding: 4px 10px;
    background: var(--gray-100);
    color: var(--gray-600);
    border-radius: 30px;
    font-size: 0.688rem;
    font-weight: 500;
    border: 1px solid var(--gray-200);
}

.room-card-footer {
    padding: 20px;
    border-top: 1px solid var(--gray-100);
    background: var(--gray-50);
    display: flex;
    gap: 8px;
}

.room-action-btn {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.room-action-btn.primary {
    background: var(--primary-600);
    color: white;
}

.room-action-btn.primary:hover {
    background: var(--primary-700);
    transform: translateY(-2px);
}

.room-action-btn.success {
    background: var(--primary-500);
    color: white;
}

.room-action-btn.success:hover {
    background: var(--primary-600);
    transform: translateY(-2px);
}

.room-action-btn.outline {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.room-action-btn.outline:hover {
    background: var(--gray-50);
    transform: translateY(-2px);
}

.room-action-btn.danger {
    background: white;
    color: var(--red-500);
    border: 1px solid var(--red-200);
}

.room-action-btn.danger:hover {
    background: var(--red-50);
    transform: translateY(-2px);
}

/* Conflict info - MIS À JOUR */
.conflict-info {
    margin-top: 16px;
    padding: 16px;
    background: var(--red-50);
    border-radius: 12px;
    border: 1px solid #fecaca;
}

.conflict-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.conflict-count-badge {
    display: inline-block;
    padding: 2px 8px;
    background: var(--red-500);
    color: white;
    border-radius: 20px;
    font-size: 0.625rem;
    font-weight: 600;
    margin-left: 8px;
}

.conflict-item {
    margin-bottom: 8px;
    padding: 8px 10px;
    background: rgba(239, 68, 68, 0.05);
    border-radius: 8px;
    font-size: 0.75rem;
    border-left: 3px solid var(--red-400);
}

.conflict-item:last-child {
    margin-bottom: 0;
}

.conflict-dates {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-700);
    font-weight: 500;
}

.conflict-customer {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    color: var(--gray-600);
    font-size: 0.7rem;
}

.conflict-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--red-600);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.7rem;
    margin-top: 8px;
}

.conflict-link:hover {
    text-decoration: underline;
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.625rem;
    font-weight: 600;
    margin-left: 4px;
}

.status-reservation { background: #fef3c7; color: #92400e; }
.status-active { background: #d1fae5; color: #065f46; }
.status-completed { background: #dbeafe; color: #1e40af; }
.status-cancelled { background: #fee2e2; color: #b91c1c; }
.status-no_show { background: #f3f4f6; color: #4b5563; }

/* Debug panel */
.debug-panel {
    background: var(--gray-900);
    color: var(--gray-300);
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 20px;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    border: 1px solid var(--gray-700);
}

.debug-panel .debug-title {
    color: var(--gray-400);
    font-size: 0.688rem;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.debug-url {
    word-break: break-all;
    background: var(--gray-800);
    padding: 8px;
    border-radius: 6px;
    color: var(--amber-400);
}

/* Empty state */
.empty-state-modern {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
}

.empty-state-modern i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.empty-state-modern h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state-modern p {
    color: var(--gray-400);
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 768px) {
    .availability-page {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .search-grid {
        grid-template-columns: 1fr;
    }
    
    .room-grid {
        grid-template-columns: 1fr;
    }
    
    .room-card-footer {
        flex-direction: column;
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

.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>

<div class="availability-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Disponibilité</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-search"></i>
            </span>
            <h1>Recherche de disponibilité</h1>
        </div>
        
        <div class="header-actions">
            <a href="<?php echo e(route('availability.calendar')); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-calendar-alt me-2"></i>
                Calendrier
            </a>
            <a href="<?php echo e(route('availability.inventory')); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-clipboard-list me-2"></i>
                Inventaire
            </a>
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <div class="search-card fade-in">
        <div class="search-card-body">
            <form method="GET" action="<?php echo e(route('availability.search')); ?>" id="searchForm">
                <div class="search-grid">
                    <div class="form-group-modern">
                        <label class="form-label-modern">Arrivée</label>
                        <input type="date" 
                               name="check_in" 
                               class="form-control-modern" 
                               value="<?php echo e($checkIn); ?>"
                               min="<?php echo e(now()->format('Y-m-d')); ?>"
                               required>
                    </div>
                    
                    <div class="form-group-modern">
                        <label class="form-label-modern">Départ</label>
                        <input type="date" 
                               name="check_out" 
                               class="form-control-modern" 
                               value="<?php echo e($checkOut); ?>"
                               min="<?php echo e(now()->addDay()->format('Y-m-d')); ?>"
                               required>
                    </div>
                    
                    <div class="form-group-modern">
                        <label class="form-label-modern">Adultes</label>
                        <select name="adults" class="form-select-modern">
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($adults == $i ? 'selected' : ''); ?>>
                                    <?php echo e($i); ?> <?php echo e($i == 1 ? 'Adulte' : 'Adultes'); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group-modern">
                        <label class="form-label-modern">Enfants</label>
                        <select name="children" class="form-select-modern">
                            <?php for($i = 0; $i <= 5; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($children == $i ? 'selected' : ''); ?>>
                                    <?php echo e($i); ?> <?php echo e($i == 1 ? 'Enfant' : 'Enfants'); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group-modern">
                        <label class="form-label-modern">Type de chambre</label>
                        <select name="room_type_id" class="form-select-modern">
                            <option value="">Tous les types</option>
                            <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e($roomTypeId == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <div class="search-actions">
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fas fa-search me-2"></i>
                        Rechercher
                    </button>
                    <a href="<?php echo e(route('availability.search')); ?>" class="btn-modern btn-outline-modern">
                        <i class="fas fa-times me-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php if(request()->has('check_in')): ?>
    <!-- Information panel -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="results-count fade-in">
                <span class="results-count-badge">
                    <?php echo e(count($availableRooms)); ?> disponible(s)
                </span>
                <span class="results-count-text">
                    sur <?php echo e(count($availableRooms) + count($unavailableRooms)); ?> chambres
                </span>
                <span class="results-count-badge" style="background: var(--red-100); color: var(--red-600); margin-left: auto;">
                    <?php echo e($nights); ?> nuit(s)
                </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-panel fade-in">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Période</div>
                        <div class="info-value"><?php echo e(\Carbon\Carbon::parse($checkIn)->format('d/m/Y')); ?> → <?php echo e(\Carbon\Carbon::parse($checkOut)->format('d/m/Y')); ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Personnes</div>
                        <div class="info-value"><?php echo e($adults + $children); ?> (<?php echo e($adults); ?> adulte(s), <?php echo e($children); ?> enfant(s))</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Horaires</div>
                        <div class="info-value">Check-in 14h00 · Check-out 12h00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats -->
    <div class="fade-in">
        <!-- Chambres disponibles -->
        <?php if(count($availableRooms) > 0): ?>
        <div class="card-modern mb-4">
            <div class="card-header-success card-header-modern">
                <h6>
                    <i class="fas fa-check-circle"></i>
                    Chambres disponibles (<?php echo e(count($availableRooms)); ?>)
                </h6>
            </div>
            <div class="card-body-modern">
                <div class="room-grid">
                    <?php $__currentLoopData = $availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="room-card available">
                        <div class="room-card-header">
                            <div>
                                <div class="room-number">Chambre <?php echo e($roomData['room']->number); ?></div>
                                <span class="room-type"><?php echo e($roomData['room']->type->name ?? 'Standard'); ?></span>
                            </div>
                            <div class="room-price">
                                <div class="room-price-total"><?php echo e(number_format($roomData['total_price'], 0, ',', ' ')); ?> FCFA</div>
                                <div class="room-price-night"><?php echo e(number_format($roomData['price_per_night'], 0, ',', ' ')); ?> FCFA/nuit</div>
                            </div>
                        </div>
                        
                        <div class="room-card-body">
                            <div class="room-features">
                                <div class="room-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="feature-text">
                                        Capacité: <strong><?php echo e($roomData['room']->capacity); ?> personnes</strong>
                                    </div>
                                </div>
                                
                                <div class="room-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <div class="feature-text">
                                        Type: <strong><?php echo e($roomData['room']->type->name ?? 'Standard'); ?></strong>
                                    </div>
                                </div>
                                
                                <?php if($roomData['room']->surface): ?>
                                <div class="room-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-arrows-alt"></i>
                                    </div>
                                    <div class="feature-text">
                                        Surface: <strong><?php echo e($roomData['room']->surface); ?> m²</strong>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($roomData['room']->facilities->count() > 0): ?>
                            <div class="room-facilities">
                                <?php $__currentLoopData = $roomData['room']->facilities->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="facility-badge">
                                        <?php if($facility->icon): ?><i class="fas <?php echo e($facility->icon); ?> me-1"></i><?php endif; ?>
                                        <?php echo e($facility->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($roomData['room']->facilities->count() > 3): ?>
                                    <span class="facility-badge">+<?php echo e($roomData['room']->facilities->count() - 3); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="room-card-footer">
                            <a href="<?php echo e(route('availability.room.detail', $roomData['room']->id)); ?>" 
                               class="room-action-btn outline">
                                <i class="fas fa-eye"></i>
                                Détails
                            </a>
                            <a href="<?php echo e(route('transaction.reservation.createIdentity', [
                                'room_id' => $roomData['room']->id,
                                'check_in' => $checkIn,
                                'check_out' => $checkOut,
                                'adults' => $adults,
                                'children' => $children
                            ])); ?>" 
                               class="room-action-btn success">
                                <i class="fas fa-book"></i>
                                Réserver
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Chambres non disponibles - AVEC CONFLITS CORRIGÉS -->
        <?php if(count($unavailableRooms) > 0): ?>
        <div class="card-modern">
            <div class="card-header-danger card-header-modern">
                <h6>
                    <i class="fas fa-times-circle"></i>
                    Chambres non disponibles (<?php echo e(count($unavailableRooms)); ?>)
                </h6>
            </div>
            <div class="card-body-modern">
                <div class="room-grid">
                    <?php $__currentLoopData = $unavailableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="room-card unavailable">
                        <div class="room-card-header">
                            <div>
                                <div class="room-number">Chambre <?php echo e($room->number); ?></div>
                                <span class="room-type"><?php echo e($room->type->name ?? 'Standard'); ?></span>
                            </div>
                        </div>
                        
                        <div class="room-card-body">
                            <div class="room-features">
                                <div class="room-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="feature-text">
                                        Capacité: <strong><?php echo e($room->capacity); ?> personnes</strong>
                                    </div>
                                </div>
                                
                                <div class="room-feature">
                                    <div class="feature-icon">
                                        <i class="fas fa-bed"></i>
                                    </div>
                                    <div class="feature-text">
                                        Type: <strong><?php echo e($room->type->name ?? 'Standard'); ?></strong>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Conflits - VERSION CORRIGÉE (basée sur le contrôleur) -->
                            <?php if(isset($roomConflicts[$room->id]) && count($roomConflicts[$room->id]) > 0): ?>
                            <div class="conflict-info">
                                <div class="conflict-header">
                                    <i class="fas fa-exclamation-triangle" style="color: var(--red-500);"></i>
                                    <strong style="color: var(--red-700);">Réservations en conflit</strong>
                                    <span class="conflict-count-badge"><?php echo e(count($roomConflicts[$room->id])); ?></span>
                                </div>
                                <div class="conflict-details">
                                    <?php $__currentLoopData = $roomConflicts[$room->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Les données sont des tableaux selon le contrôleur
                                        $checkIn = $conflict['check_in'] ?? 'N/A';
                                        $checkOut = $conflict['check_out'] ?? 'N/A';
                                        $customerName = $conflict['customer'] ?? 'Client inconnu';
                                        $statusClass = $conflict['status_class'] ?? 'bg-secondary';
                                        $statusLabel = $conflict['status'] ?? 'N/A';
                                    ?>
                                    
                                    <div class="conflict-item">
                                        <div class="conflict-dates">
                                            <i class="fas fa-calendar-alt" style="color: var(--red-400);"></i>
                                            <span><?php echo e(\Carbon\Carbon::parse($checkIn)->format('d/m')); ?> → <?php echo e(\Carbon\Carbon::parse($checkOut)->format('d/m')); ?></span>
                                            <span class="status-badge <?php echo e(str_replace('badge', 'status', $statusClass)); ?>"><?php echo e($statusLabel); ?></span>
                                        </div>
                                        <div class="conflict-customer">
                                            <i class="fas fa-user" style="color: var(--red-400);"></i>
                                            <span><?php echo e($customerName); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                
                                <!-- Lien pour voir tous les conflits -->
                                <a href="<?php echo e(route('availability.room.conflicts', $room->id)); ?>?check_in=<?php echo e($checkIn); ?>&check_out=<?php echo e($checkOut); ?>" 
                                   class="conflict-link">
                                    <i class="fas fa-external-link-alt"></i>
                                    Voir tous les détails
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="room-card-footer">
                            <a href="<?php echo e(route('availability.room.detail', $room->id)); ?>" 
                               class="room-action-btn outline">
                                <i class="fas fa-eye"></i>
                                Détails
                            </a>
                            <a href="<?php echo e(route('availability.room.conflicts', $room->id)); ?>?check_in=<?php echo e($checkIn); ?>&check_out=<?php echo e($checkOut); ?>" 
                               class="room-action-btn danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Conflits
                            </a>
                        </div>
                        
                        <!-- Debug link (admin only) -->
                        <?php if(auth()->user() && in_array(auth()->user()->role, ['Super', 'Admin'])): ?>
                        <div class="debug-panel">
                            <div class="debug-title">Debug URL</div>
                            <div class="debug-url">
                                <?php echo e(route('availability.room.conflicts', $room->id)); ?>?check_in=<?php echo e($checkIn); ?>&check_out=<?php echo e($checkOut); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Aucun résultat -->
        <?php if(count($availableRooms) == 0 && count($unavailableRooms) == 0): ?>
        <div class="empty-state-modern">
            <i class="fas fa-bed"></i>
            <h4>Aucune chambre trouvée</h4>
            <p>
                Aucune chambre ne correspond à vos critères de recherche.<br>
                Essayez de modifier vos dates ou le type de chambre.
            </p>
            <a href="<?php echo e(route('availability.search')); ?>" class="btn-modern btn-primary-modern">
                <i class="fas fa-edit me-2"></i>
                Modifier la recherche
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Validation des dates
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');
    
    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            
            checkOutInput.min = nextDay.toISOString().split('T')[0];
            
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        });
        
        checkOutInput.addEventListener('change', function() {
            const checkOutDate = new Date(this.value);
            const checkInDate = new Date(checkInInput.value);
            
            if (checkOutDate <= checkInDate) {
                alert('La date de départ doit être après la date d\'arrivée');
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                this.value = nextDay.toISOString().split('T')[0];
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/availability/search.blade.php ENDPATH**/ ?>