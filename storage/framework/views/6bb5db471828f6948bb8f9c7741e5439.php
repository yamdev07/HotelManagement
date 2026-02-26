
<?php $__env->startSection('title', 'Gestion des Paiements'); ?>
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

.payments-page {
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

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

/* Dropdown */
.dropdown-modern .dropdown-menu {
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    padding: 8px;
}

.dropdown-modern .dropdown-item {
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.875rem;
    color: var(--gray-700);
    transition: all 0.2s;
}

.dropdown-modern .dropdown-item:hover {
    background: var(--gray-50);
    color: var(--gray-900);
}

.dropdown-modern .dropdown-item i {
    color: var(--primary-500);
    width: 20px;
}

.dropdown-modern .dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid var(--gray-200);
}

/* Cartes */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
    transition: all 0.2s;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
}

.card-header-modern h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h5 i {
    color: var(--primary-500);
}

.card-body-modern {
    padding: 24px;
}

/* Stats cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card-modern {
    background: white;
    border-radius: 20px;
    padding: 24px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: transform 0.2s;
}

.stat-card-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.stat-icon-wrapper.primary { background: var(--primary-100); color: var(--primary-600); }
.stat-icon-wrapper.success { background: var(--primary-100); color: var(--primary-600); }
.stat-icon-wrapper.info { background: var(--blue-100); color: var(--blue-600); }
.stat-icon-wrapper.warning { background: var(--amber-100); color: var(--amber-600); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.stat-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--primary-100);
    color: var(--primary-700);
    margin-top: 8px;
}

/* Table */
.table-container {
    overflow-x: auto;
    border-radius: 16px;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: var(--gray-50);
    padding: 16px 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
}

.table-modern tbody td {
    padding: 20px;
    border-bottom: 1px solid var(--gray-100);
    color: var(--gray-700);
    font-size: 0.875rem;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Badges statut */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
}

.badge-completed {
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.badge-pending {
    background: var(--amber-100);
    color: var(--amber-700);
    border: 1px solid var(--amber-200);
}

.badge-failed {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.badge-refunded {
    background: var(--blue-100);
    color: var(--blue-700);
    border: 1px solid var(--blue-200);
}

.badge-cancelled {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Référence */
.reference-badge {
    background: var(--gray-100);
    color: var(--gray-700);
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--gray-200);
}

/* Avatar */
.avatar-modern {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

/* Actions */
.actions-group {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

.btn-icon-modern {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-1px);
}

/* Pagination */
.pagination-modern {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

.page-item-modern {
    list-style: none;
}

.page-link-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    text-decoration: none;
    transition: all 0.2s;
}

.page-link-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
}

.page-item-modern.active .page-link-modern {
    background: var(--primary-600);
    border-color: var(--primary-600);
    color: white;
}

.page-item-modern.disabled .page-link-modern {
    opacity: 0.5;
    pointer-events: none;
    background: var(--gray-100);
}

/* Empty state */
.empty-state-modern {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-modern i {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 20px;
}

.empty-state-modern h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state-modern p {
    color: var(--gray-400);
    margin-bottom: 24px;
}

/* Search input */
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
    font-size: 0.875rem;
}

.search-wrapper input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.search-wrapper input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

/* Modal */
.modal-modern .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.modal-modern .modal-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 20px 24px;
}

.modal-modern .modal-body {
    padding: 24px;
}

.modal-modern .modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    padding: 16px 24px;
}

.modal-modern .modal-title i {
    color: var(--primary-500);
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .payments-page {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .search-wrapper {
        width: 100%;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-modern thead th {
        padding: 12px;
    }
    
    .table-modern tbody td {
        padding: 12px;
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

<div class="payments-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Paiements</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div>
            <div class="header-title">
                <span class="header-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </span>
                <h1>Gestion des Paiements</h1>
            </div>
            <p class="header-subtitle">
                <?php echo e($payments->total()); ?> paiement(s) enregistré(s)
            </p>
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            <!-- Filtre dropdown -->
            <div class="dropdown dropdown-modern">
                <button class="btn-modern btn-outline-modern dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?status=all"><i class="fas fa-list me-2"></i>Tous les paiements</a></li>
                    <li><a class="dropdown-item" href="?status=completed"><i class="fas fa-check-circle me-2 text-success"></i>Complétés</a></li>
                    <li><a class="dropdown-item" href="?status=pending"><i class="fas fa-clock me-2 text-warning"></i>En attente</a></li>
                    <li><a class="dropdown-item" href="?status=failed"><i class="fas fa-times-circle me-2 text-danger"></i>Échoués</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="?period=today"><i class="fas fa-calendar-day me-2"></i>Aujourd'hui</a></li>
                    <li><a class="dropdown-item" href="?period=this_week"><i class="fas fa-calendar-week me-2"></i>Cette semaine</a></li>
                    <li><a class="dropdown-item" href="?period=this_month"><i class="fas fa-calendar-alt me-2"></i>Ce mois</a></li>
                </ul>
            </div>
            
            <!-- Export button -->
            <button class="btn-modern btn-outline-modern" onclick="exportPayments()">
                <i class="fas fa-file-export me-2"></i>Exporter
            </button>
            
            <!-- Refresh button -->
            <button class="btn-modern btn-outline-modern" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="stats-grid">
        <?php
            $totalAmount = $payments->sum('amount');
            $todayAmount = $payments->where('created_at', '>=', now()->startOfDay())->sum('amount');
        ?>
        
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper primary">
                <i class="fas fa-coins fa-lg"></i>
            </div>
            <div class="stat-number"><?php echo e(Helper::formatCFA($totalAmount)); ?></div>
            <div class="stat-label">Total des paiements</div>
            <div class="stat-badge">Global</div>
        </div>
        
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper success">
                <i class="fas fa-calendar-day fa-lg"></i>
            </div>
            <div class="stat-number"><?php echo e(Helper::formatCFA($todayAmount)); ?></div>
            <div class="stat-label">Reçu aujourd'hui</div>
            <div class="stat-badge"><?php echo e(now()->format('d/m/Y')); ?></div>
        </div>
        
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper info">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div class="stat-number"><?php echo e($payments->unique('transaction.customer_id')->count()); ?></div>
            <div class="stat-label">Clients uniques</div>
            <div class="stat-badge">Paiements</div>
        </div>
        
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper warning">
                <i class="fas fa-bed fa-lg"></i>
            </div>
            <div class="stat-number"><?php echo e($payments->unique('transaction.room_id')->count()); ?></div>
            <div class="stat-label">Chambres payées</div>
            <div class="stat-badge">Réservations</div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="card-modern">
        <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5>
                <i class="fas fa-credit-card"></i>
                Historique des Paiements
            </h5>
            
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher un paiement..." id="searchInput" onkeyup="filterPayments()">
            </div>
        </div>
        
        <div class="card-body-modern p-0">
            <?php if($payments->count() > 0): ?>
                <div class="table-container">
                    <table class="table-modern" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client & Chambre</th>
                                <th>Détails du paiement</th>
                                <th>Date & Heure</th>
                                <th>Statut</th>
                                <th>Traité par</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $statusClass = [
                                        'completed' => 'badge-completed',
                                        'pending' => 'badge-pending',
                                        'failed' => 'badge-failed',
                                        'refunded' => 'badge-refunded',
                                        'cancelled' => 'badge-cancelled'
                                    ][$payment->status] ?? 'badge-cancelled';
                                    
                                    $statusTranslations = [
                                        'completed' => 'Complété',
                                        'pending' => 'En attente',
                                        'failed' => 'Échoué',
                                        'refunded' => 'Remboursé',
                                        'cancelled' => 'Annulé'
                                    ];
                                    
                                    $isToday = $payment->created_at->isToday();
                                ?>
                                
                                <tr style="<?php echo e($isToday ? 'background: var(--primary-50);' : ''); ?>">
                                    <!-- ID -->
                                    <td>
                                        <span class="badge-modern" style="background: var(--gray-800); color: white; border: none;">
                                            #<?php echo e($payment->id); ?>

                                        </span>
                                    </td>
                                    
                                    <!-- Client & Chambre -->
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-modern">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: var(--gray-800);">
                                                    <?php echo e($payment->transaction->customer->name ?? 'N/A'); ?>

                                                </div>
                                                <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                    <i class="fas fa-bed me-1"></i>
                                                    Chambre <?php echo e($payment->transaction->room->number ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Détails du paiement -->
                                    <td>
                                        <div style="font-weight: 700; color: var(--gray-800); font-size: 1rem; margin-bottom: 4px;">
                                            <?php echo e(Helper::formatCFA($payment->amount)); ?>

                                        </div>
                                        
                                        <!-- Référence -->
                                        <?php if($payment->reference): ?>
                                            <div class="reference-badge mb-2">
                                                <i class="fas fa-fingerprint" style="font-size: 0.7rem;"></i>
                                                <?php echo e($payment->reference); ?>

                                            </div>
                                        <?php else: ?>
                                            <div class="reference-badge mb-2" style="color: var(--gray-400);">
                                                <i class="far fa-question-circle"></i>
                                                Référence non disponible
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Méthode -->
                                        <?php if($payment->payment_method): ?>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                <i class="fas fa-credit-card me-1"></i>
                                                <?php echo e(ucfirst($payment->payment_method)); ?>

                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Notes -->
                                        <?php if($payment->notes): ?>
                                            <div style="font-size: 0.75rem; color: var(--gray-400); margin-top: 4px;">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                <?php echo e(Str::limit($payment->notes, 20)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Date & Heure -->
                                    <td>
                                        <div style="font-weight: 500; color: var(--gray-800);">
                                            <?php echo e(Helper::dateFormat($payment->created_at)); ?>

                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo e($payment->created_at->format('H:i')); ?>

                                        </div>
                                    </td>
                                    
                                    <!-- Statut -->
                                    <td>
                                        <span class="badge-modern <?php echo e($statusClass); ?>">
                                            <?php if($payment->status == 'completed'): ?> 
                                                <i class="fas fa-check-circle"></i>
                                            <?php elseif($payment->status == 'pending'): ?>
                                                <i class="fas fa-clock"></i>
                                            <?php elseif($payment->status == 'failed'): ?>
                                                <i class="fas fa-times-circle"></i>
                                            <?php elseif($payment->status == 'refunded'): ?>
                                                <i class="fas fa-undo"></i>
                                            <?php else: ?>
                                                <i class="fas fa-ban"></i>
                                            <?php endif; ?>
                                            <?php echo e($statusTranslations[$payment->status] ?? ucfirst($payment->status)); ?>

                                        </span>
                                        
                                        <?php if($payment->verified_at): ?>
                                            <div style="font-size: 0.688rem; color: var(--primary-600); margin-top: 4px;">
                                                <i class="fas fa-check-circle me-1"></i>Vérifié
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Traité par -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-modern" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; color: var(--gray-700);">
                                                    <?php echo e($payment->user->name ?? 'Système'); ?>

                                                </div>
                                                <div style="font-size: 0.688rem; color: var(--gray-400);">Personnel</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="text-end">
                                        <div class="actions-group">
                                            <!-- Facture -->
                                            <a href="<?php echo e(route('payment.invoice', $payment->id)); ?>" 
                                               class="btn-icon-modern"
                                               data-bs-toggle="tooltip" 
                                               title="Voir la facture">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            
                                            <!-- Transaction -->
                                            <a href="<?php echo e(route('transaction.show', $payment->transaction_id)); ?>" 
                                               class="btn-icon-modern"
                                               data-bs-toggle="tooltip" 
                                               title="Voir la transaction">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            
                                            <!-- Détails -->
                                            <button class="btn-icon-modern"
                                                    onclick="showPaymentDetails(<?php echo e($payment->id); ?>)"
                                                    data-bs-toggle="tooltip" 
                                                    title="Détails du paiement">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($payments->hasPages()): ?>
                    <div style="padding: 16px 24px; border-top: 1px solid var(--gray-100);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div style="font-size: 0.813rem; color: var(--gray-500);">
                                Affichage de <?php echo e($payments->firstItem()); ?> à <?php echo e($payments->lastItem()); ?> 
                                sur <?php echo e($payments->total()); ?> paiements
                            </div>
                            
                            <nav>
                                <ul class="pagination-modern">
                                    <?php if($payments->onFirstPage()): ?>
                                        <li class="page-item-modern disabled">
                                            <span class="page-link-modern">‹</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item-modern">
                                            <a class="page-link-modern" href="<?php echo e($payments->previousPageUrl()); ?>">‹</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php $__currentLoopData = range(1, $payments->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="page-item-modern <?php echo e($i == $payments->currentPage() ? 'active' : ''); ?>">
                                            <a class="page-link-modern" href="<?php echo e($payments->url($i)); ?>"><?php echo e($i); ?></a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <?php if($payments->hasMorePages()): ?>
                                        <li class="page-item-modern">
                                            <a class="page-link-modern" href="<?php echo e($payments->nextPageUrl()); ?>">›</a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item-modern disabled">
                                            <span class="page-link-modern">›</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Empty state -->
                <div class="empty-state-modern">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>Aucun paiement trouvé</h3>
                    <p>Aucun enregistrement de paiement trouvé dans la base de données.</p>
                    <a href="<?php echo e(route('dashboard.index')); ?>" class="btn-modern btn-primary-modern">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Tableau de bord
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
<div class="modal fade modal-modern" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i>
                    Détails du paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetailsBody">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
// Initialisation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Filtre de recherche
function filterPayments() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('paymentsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    }
}

// Export des paiements
function exportPayments() {
    if (confirm('Exporter tous les paiements en CSV ?')) {
        window.location.href = '<?php echo e(route("transaction.export", "payments")); ?>';
    }
}

// Marquer comme complété
function markAsCompleted(paymentId) {
    if (confirm('Marquer ce paiement comme complété ?')) {
        fetch(`/payments/${paymentId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors du marquage du paiement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}

// Afficher les détails du paiement
function showPaymentDetails(paymentId) {
    fetch(`/payments/${paymentId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalBody = document.getElementById('paymentDetailsBody');
                
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-modern" style="margin-bottom: 0;">
                                <div class="card-header-modern" style="background: transparent; border-bottom: 1px solid var(--gray-200);">
                                    <h5 style="margin: 0;">Informations de paiement</h5>
                                </div>
                                <div style="padding: 20px;">
                                    <div style="margin-bottom: 16px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Montant</div>
                                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-600);">
                                            ${data.payment.amount_formatted}
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Référence</div>
                                        <div class="reference-badge" style="font-size: 0.875rem;">
                                            <i class="fas fa-fingerprint me-2"></i>
                                            ${data.payment.reference || 'Non disponible'}
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Statut</div>
                                        <span class="badge-modern ${data.payment.status === 'completed' ? 'badge-completed' : 
                                            (data.payment.status === 'pending' ? 'badge-pending' : 
                                            (data.payment.status === 'failed' ? 'badge-failed' : 
                                            (data.payment.status === 'refunded' ? 'badge-refunded' : 'badge-cancelled')))}">
                                            ${data.payment.status === 'completed' ? 'Complété' : 
                                              (data.payment.status === 'pending' ? 'En attente' : 
                                              (data.payment.status === 'failed' ? 'Échoué' : 
                                              (data.payment.status === 'refunded' ? 'Remboursé' : 'Annulé')))}
                                        </span>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Méthode</div>
                                        <div style="color: var(--gray-700);">${data.payment.method || 'Non spécifiée'}</div>
                                    </div>
                                    
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">ID Transaction</div>
                                        <span class="badge-modern" style="background: var(--gray-800); color: white;">
                                            #${data.payment.transaction_id || 'N/A'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card-modern" style="margin-bottom: 0;">
                                <div class="card-header-modern" style="background: transparent; border-bottom: 1px solid var(--gray-200);">
                                    <h5 style="margin: 0;">Détails de la transaction</h5>
                                </div>
                                <div style="padding: 20px;">
                                    <div style="margin-bottom: 16px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Client</div>
                                        <div style="font-weight: 600; color: var(--gray-800);">${data.payment.guest_name}</div>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Chambre</div>
                                        <span class="badge-modern" style="background: var(--primary-100); color: var(--primary-700);">
                                            ${data.payment.room_number}
                                        </span>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Date de paiement</div>
                                        <div style="color: var(--gray-700);">${data.payment.date_formatted}</div>
                                    </div>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Traité par</div>
                                        <div style="color: var(--gray-700);">${data.payment.processed_by}</div>
                                    </div>
                                    
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 4px;">Créé le</div>
                                        <div style="color: var(--gray-700);">${data.payment.created_at || 'N/A'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${data.payment.notes ? `
                    <div class="mt-4">
                        <div class="card-modern" style="margin-bottom: 0;">
                            <div class="card-header-modern" style="background: transparent; border-bottom: 1px solid var(--gray-200);">
                                <h5 style="margin: 0;">Notes</h5>
                            </div>
                            <div style="padding: 20px;">
                                <div style="background: var(--gray-50); padding: 12px; border-radius: 8px; color: var(--gray-700);">
                                    ${data.payment.notes}
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails');
        });
}

// Annuler le paiement
function cancelPayment(paymentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')) {
        fetch(`/payments/${paymentId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors de l\'annulation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}

// Initier un remboursement
function initiateRefund(paymentId) {
    const amount = prompt('Entrez le montant du remboursement (FCFA):', '');
    if (amount && !isNaN(amount) && amount > 0) {
        fetch(`/payments/${paymentId}/refund`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors du remboursement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/payment/index.blade.php ENDPATH**/ ?>