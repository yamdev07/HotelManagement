
<?php $__env->startSection('title', 'Effectuer un Paiement'); ?>
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

.payment-page {
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

.btn-success-modern {
    background: var(--primary-600);
    color: white;
}

.btn-success-modern:hover {
    background: var(--primary-700);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
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

.btn-outline-warning-modern {
    background: white;
    color: var(--amber-600);
    border: 1px solid var(--amber-200);
}

.btn-outline-warning-modern:hover {
    background: var(--amber-50);
    border-color: var(--amber-300);
    color: var(--amber-700);
    transform: translateY(-1px);
}

.btn-outline-danger-modern {
    background: white;
    color: #ef4444;
    border: 1px solid #fecaca;
}

.btn-outline-danger-modern:hover {
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

.btn-lg-modern {
    padding: 14px 28px;
    font-size: 1rem;
    border-radius: 12px;
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

/* Résumé transaction */
.summary-card {
    background: linear-gradient(135deg, #065f46, #047857);
    border-radius: 20px;
    padding: 24px;
    color: white;
    margin-bottom: 24px;
    box-shadow: var(--shadow-lg);
}

.summary-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (min-width: 768px) {
    .summary-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.summary-info {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.688rem;
    text-transform: uppercase;
    opacity: 0.7;
    margin-bottom: 2px;
}

.info-value {
    font-weight: 600;
    font-size: 1rem;
}

.summary-amounts {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.amount-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.amount-row:last-child {
    border-bottom: none;
}

.amount-label {
    opacity: 0.8;
}

.amount-value {
    font-weight: 600;
}

.amount-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--amber-400);
}

/* Barre de progression */
.progress-container {
    margin-top: 16px;
}

.progress-bar-modern {
    height: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: var(--amber-400);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Méthodes de paiement */
.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.method-card-modern {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    padding: 20px 16px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.method-card-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-300);
}

.method-card-modern.active {
    border-color: var(--primary-500);
    background: var(--primary-50);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.method-radio {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 18px;
    height: 18px;
    accent-color: var(--primary-500);
}

.method-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 1.5rem;
}

.method-icon-cash { background: var(--primary-100); color: var(--primary-600); }
.method-icon-card { background: var(--blue-100); color: var(--blue-600); }
.method-icon-transfer { background: var(--amber-100); color: var(--amber-600); }
.method-icon-mobile { background: #f3e8ff; color: #9333ea; }
.method-icon-fedapay { background: #ffe4e6; color: #e11d48; }
.method-icon-check { background: var(--gray-100); color: var(--gray-600); }

.method-name {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 4px;
}

.method-description {
    font-size: 0.688rem;
    color: var(--gray-500);
}

/* Montant */
.amount-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    padding: 24px;
}

.amount-label-modern {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.amount-input-wrapper {
    position: relative;
    margin-bottom: 16px;
}

.amount-input {
    width: 100%;
    padding: 16px 20px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    transition: all 0.2s;
}

.amount-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.amount-input.error {
    border-color: #ef4444;
    background: #fee2e2;
}

.amount-currency {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.875rem;
    color: var(--gray-400);
    pointer-events: none;
}

.quick-amounts {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.quick-amount-btn {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    cursor: pointer;
    transition: all 0.2s;
}

.quick-amount-btn:hover {
    background: var(--primary-100);
    border-color: var(--primary-200);
    color: var(--primary-700);
    transform: translateY(-1px);
}

.quick-amount-btn.full {
    background: var(--primary-100);
    color: var(--primary-700);
    border-color: var(--primary-200);
}

/* Champs de méthode */
.method-fields-modern {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 20px;
    margin-top: 16px;
    border: 1px solid var(--gray-200);
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
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-control-modern[readonly] {
    background: var(--gray-100);
    color: var(--gray-500);
}

/* Alertes */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    font-size: 0.875rem;
    background: white;
    box-shadow: var(--shadow-md);
}

.alert-warning {
    background: var(--amber-50);
    border-color: var(--amber-200);
    color: var(--amber-700);
}

.alert-info {
    background: var(--blue-50);
    border-color: var(--blue-200);
    color: var(--blue-700);
}

.alert-success {
    background: var(--primary-50);
    border-color: var(--primary-200);
    color: var(--primary-700);
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-warning .alert-icon {
    background: var(--amber-500);
    color: white;
}

.alert-info .alert-icon {
    background: var(--blue-500);
    color: white;
}

/* Debug panel */
.debug-panel {
    background: var(--gray-900);
    color: var(--gray-300);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    border: 1px solid var(--gray-700);
}

.debug-panel .debug-title {
    color: var(--gray-400);
    font-size: 0.75rem;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.debug-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
}

.debug-item {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    border-bottom: 1px solid var(--gray-700);
}

.debug-label {
    color: var(--gray-400);
}

.debug-value {
    color: var(--amber-400);
    font-weight: 500;
}

/* Action bar */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 24px;
    padding: 20px;
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
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

.modal-modern .modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-modern .modal-body {
    padding: 24px;
}

.modal-modern .modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    padding: 16px 24px;
}

.modal-modern pre {
    max-height: 400px;
    overflow: auto;
    background: var(--gray-900);
    color: var(--gray-300);
    padding: 16px;
    border-radius: 8px;
    font-size: 0.813rem;
}

/* Responsive */
@media (max-width: 768px) {
    .payment-page {
        padding: 16px;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .methods-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-buttons {
        justify-content: stretch;
    }
    
    .action-buttons .btn-modern {
        flex: 1;
    }
}

@media (max-width: 480px) {
    .methods-grid {
        grid-template-columns: 1fr;
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

<div class="payment-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.index')); ?>">Transactions</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.show', $transaction)); ?>">#<?php echo e($transaction->id); ?></a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Paiement</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-money-bill-wave"></i>
            </span>
            <h1>Effectuer un paiement</h1>
        </div>
        <p class="header-subtitle">Transaction #<?php echo e($transaction->id); ?> · <?php echo e($transaction->customer->name); ?></p>
    </div>

    <!-- Debug panel (admin only) -->
    <?php if(auth()->user()->isAdmin()): ?>
    <div class="debug-panel fade-in d-none" id="debug-panel">
        <div class="debug-title">
            <i class="fas fa-bug me-1"></i> Informations de débogage
        </div>
        <div class="debug-grid">
            <div>
                <div class="debug-item">
                    <span class="debug-label">Transaction ID:</span>
                    <span class="debug-value">#<?php echo e($transaction->id); ?></span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Statut:</span>
                    <span class="debug-value"><?php echo e($transaction->status); ?></span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Total (colonne):</span>
                    <span class="debug-value"><?php echo e(number_format($transaction->total_price, 0, ',', ' ')); ?> CFA</span>
                </div>
            </div>
            <div>
                <div class="debug-item">
                    <span class="debug-label">Total (calculé):</span>
                    <span class="debug-value"><?php echo e(number_format($transaction->getTotalPrice(), 0, ',', ' ')); ?> CFA</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Payé (calculé):</span>
                    <span class="debug-value"><?php echo e(number_format($transaction->getTotalPayment(), 0, ',', ' ')); ?> CFA</span>
                </div>
                <div class="debug-item">
                    <span class="debug-label">Reste (calculé):</span>
                    <span class="debug-value"><?php echo e(number_format($transaction->getRemainingPayment(), 0, ',', ' ')); ?> CFA</span>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="refresh-debug">
                <i class="fas fa-sync-alt me-1"></i> Actualiser
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="force-sync">
                <i class="fas fa-cogs me-1"></i> Synchroniser
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="show-api">
                <i class="fas fa-code me-1"></i> API
            </button>
            <button type="button" class="btn-modern btn-sm-modern btn-outline-modern" id="hide-debug">
                <i class="fas fa-eye-slash me-1"></i> Cacher
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Résumé de la transaction -->
    <div class="summary-card fade-in">
        <div class="summary-grid">
            <div class="summary-info">
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Client</div>
                        <div class="info-value"><?php echo e($transaction->customer->name); ?></div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Chambre</div>
                        <div class="info-value">#<?php echo e($transaction->room->number); ?> · <?php echo e($transaction->room->type->name ?? 'Standard'); ?></div>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Période</div>
                        <div class="info-value">
                            <?php echo e($transaction->check_in->format('d/m/Y')); ?> 
                            <i class="fas fa-arrow-right mx-2"></i>
                            <?php echo e($transaction->check_out->format('d/m/Y')); ?>

                            (<?php echo e($transaction->getNightsAttribute()); ?> nuits)
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="summary-amounts">
                <div class="amount-row">
                    <span class="amount-label">Total séjour</span>
                    <span class="amount-value"><?php echo e(number_format($transaction->getTotalPrice(), 0, ',', ' ')); ?> CFA</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Déjà payé</span>
                    <span class="amount-value"><?php echo e(number_format($transaction->getTotalPayment(), 0, ',', ' ')); ?> CFA</span>
                </div>
                <div class="amount-row">
                    <span class="amount-label">Reste à payer</span>
                    <span class="amount-value amount-total"><?php echo e(number_format($transaction->getRemainingPayment(), 0, ',', ' ')); ?> CFA</span>
                </div>
                
                <div class="progress-container">
                    <div class="progress-bar-modern">
                        <div class="progress-fill" id="progressBar" style="width: <?php echo e($transaction->getPaymentRate()); ?>%"></div>
                    </div>
                    <div class="progress-text" id="progressText">
                        <?php echo e(number_format($transaction->getPaymentRate(), 1)); ?>% du séjour payé
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de paiement -->
    <form action="<?php echo e(route('transaction.payment.store', $transaction)); ?>" method="POST" id="paymentForm">
        <?php echo csrf_field(); ?>
        
        <div class="row g-4">
            <!-- Colonne gauche - Montant -->
            <div class="col-lg-5">
                <div class="card-modern fade-in">
                    <div class="card-header-modern">
                        <h5>
                            <i class="fas fa-money-bill-wave"></i>
                            Montant du paiement
                        </h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="amount-card">
                            <label class="amount-label-modern">Montant à payer</label>
                            <div class="amount-input-wrapper">
                                <input type="number" 
                                       class="amount-input" 
                                       id="amount"
                                       name="amount"
                                       min="100"
                                       max="<?php echo e($transaction->getRemainingPayment()); ?>"
                                       step="100"
                                       value="<?php echo e(min($transaction->getRemainingPayment(), $transaction->getRemainingPayment())); ?>"
                                       required>
                                <span class="amount-currency">FCFA</span>
                            </div>
                            
                            <div class="quick-amounts">
                                <?php
                                    $remaining = $transaction->getRemainingPayment();
                                    $quickAmounts = [
                                        min(1000, $remaining),
                                        min(5000, $remaining),
                                        min(10000, $remaining),
                                        min(25000, $remaining),
                                        min(50000, $remaining),
                                        $remaining
                                    ];
                                    $quickAmounts = array_unique(array_filter($quickAmounts));
                                ?>
                                
                                <?php $__currentLoopData = $quickAmounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quickAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($quickAmount >= 100): ?>
                                        <button type="button" 
                                                class="quick-amount-btn <?php echo e($quickAmount == $remaining ? 'full' : ''); ?>"
                                                data-amount="<?php echo e($quickAmount); ?>">
                                            <?php echo e(number_format($quickAmount, 0, ',', ' ')); ?> CFA
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <div id="amountInfo" class="mt-3">
                                <div class="alert-modern alert-info" id="remainingAfter">
                                    <div class="alert-icon">
                                        <i class="fas fa-info"></i>
                                    </div>
                                    <div>
                                        Reste après paiement: 
                                        <strong id="remainingAfterValue"><?php echo e(number_format($transaction->getRemainingPayment(), 0, ',', ' ')); ?> CFA</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="amountWarning" class="d-none">
                                <div class="alert-modern alert-warning" id="warningMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Méthode de paiement -->
            <div class="col-lg-7">
                <div class="card-modern fade-in">
                    <div class="card-header-modern">
                        <h5>
                            <i class="fas fa-credit-card"></i>
                            Méthode de paiement
                        </h5>
                    </div>
                    <div class="card-body-modern">
                        <!-- Méthodes de paiement -->
                        <div class="methods-grid" id="paymentMethods">
                            <?php
                                $paymentMethods = \App\Models\Payment::getPaymentMethods();
                            ?>
                            
                            <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="method-card-modern <?php echo e($loop->first ? 'active' : ''); ?>" 
                                       for="method_<?php echo e($method); ?>">
                                    <input type="radio" 
                                           name="payment_method" 
                                           id="method_<?php echo e($method); ?>"
                                           value="<?php echo e($method); ?>"
                                           class="method-radio"
                                           <?php echo e($loop->first ? 'checked' : ''); ?>

                                           required>
                                    <div class="method-icon-wrapper method-icon-<?php echo e($method == 'cash' ? 'cash' : ($method == 'card' ? 'card' : ($method == 'transfer' ? 'transfer' : ($method == 'mobile_money' ? 'mobile' : ($method == 'fedapay' ? 'fedapay' : 'check'))))); ?>">
                                        <i class="fas <?php echo e($details['icon']); ?>"></i>
                                    </div>
                                    <div class="method-name"><?php echo e($details['label']); ?></div>
                                    <div class="method-description"><?php echo e($details['description']); ?></div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <!-- Champs spécifiques -->
                        <div class="method-fields-modern" id="methodFields">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">Description (optionnelle)</label>
                                        <textarea class="form-control-modern" 
                                                  name="description" 
                                                  id="description"
                                                  rows="2"
                                                  placeholder="Informations sur le paiement..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Référence automatique -->
                            <input type="hidden" name="reference" id="reference" value="">
                            
                            <div class="alert-modern alert-info mt-3">
                                <div class="alert-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <small>
                                        Une référence de paiement sera générée automatiquement.
                                        <br>
                                        <span id="referenceDisplay" class="fw-bold"></span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barre d'actions -->
        <div class="action-bar fade-in">
            <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la transaction
            </a>
            
            <div class="action-buttons">
                <button type="button" class="btn-modern btn-outline-warning-modern" id="validateBtn">
                    <i class="fas fa-check-circle me-2"></i>
                    Valider
                </button>
                <button type="reset" class="btn-modern btn-outline-danger-modern">
                    <i class="fas fa-redo me-2"></i>
                    Réinitialiser
                </button>
                <button type="submit" class="btn-modern btn-success-modern btn-lg-modern" id="submitBtn">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="submitText">Enregistrer le paiement</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal API -->
<div class="modal fade modal-modern" id="apiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code"></i>
                    Réponse API
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="apiResponseContent" class="bg-dark text-light p-3 rounded"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PAGE DE PAIEMENT INITIALISÉE ===');
    
    const transactionId = <?php echo e($transaction->id); ?>;
    const remaining = <?php echo e($transaction->getRemainingPayment()); ?>;
    const totalPrice = <?php echo e($transaction->getTotalPrice()); ?>;
    const totalPayment = <?php echo e($transaction->getTotalPayment()); ?>;
    
    // Éléments DOM
    const amountInput = document.getElementById('amount');
    const descriptionInput = document.getElementById('description');
    const remainingAfter = document.getElementById('remainingAfterValue');
    const remainingAfterDiv = document.getElementById('remainingAfter');
    const amountWarning = document.getElementById('amountWarning');
    const warningMessage = document.getElementById('warningMessage');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const validateBtn = document.getElementById('validateBtn');
    const paymentForm = document.getElementById('paymentForm');
    const referenceInput = document.getElementById('reference');
    const referenceDisplay = document.getElementById('referenceDisplay');
    
    let currentRemaining = remaining;
    let currentAmount = amountInput.value;
    
    // Initialisation
    updateReference();
    
    // Fonction pour mettre à jour la référence
    function updateReference() {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
        
        let prefix = 'PAY-';
        switch(method) {
            case 'cash': prefix = 'CASH-'; break;
            case 'card': prefix = 'CARD-'; break;
            case 'transfer': prefix = 'VIR-'; break;
            case 'mobile_money': prefix = 'MOMO-'; break;
            case 'fedapay': prefix = 'FDP-'; break;
            case 'check': prefix = 'CHQ-'; break;
        }
        
        const ref = `${prefix}${transactionId}-${Date.now()}`;
        referenceInput.value = ref;
        referenceDisplay.textContent = ref;
    }
    
    // Mettre à jour les calculs
    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const newRemaining = currentRemaining - amount;
        const newPaymentRate = ((totalPayment + amount) / totalPrice) * 100;
        
        // Mettre à jour l'affichage
        remainingAfter.textContent = `${newRemaining.toLocaleString('fr-FR')} CFA`;
        progressBar.style.width = `${newPaymentRate}%`;
        progressText.textContent = `${newPaymentRate.toFixed(1)}% du séjour payé`;
        
        // Valider le montant
        if (amount > currentRemaining) {
            amountWarning.classList.remove('d-none');
            warningMessage.innerHTML = `
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>Le montant dépasse le solde restant de ${currentRemaining.toLocaleString('fr-FR')} CFA</div>
            `;
            amountInput.classList.add('error');
        } else if (amount < 100 && amount > 0) {
            amountWarning.classList.remove('d-none');
            warningMessage.innerHTML = `
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div>Le montant minimum est de 100 CFA</div>
            `;
            amountInput.classList.add('error');
        } else {
            amountWarning.classList.add('d-none');
            amountInput.classList.remove('error');
        }
        
        // Mettre à jour le bouton de soumission
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        if (method) {
            const methodLabel = document.querySelector(`label[for="method_${method}"] .method-name`).textContent;
            
            if (amount === currentRemaining && amount > 0) {
                submitText.innerHTML = `<i class="fas fa-check me-2"></i>Payer la totalité (${methodLabel})`;
            } else if (amount > 0) {
                submitText.innerHTML = `<i class="fas fa-money-bill-wave me-2"></i>Payer ${amount.toLocaleString('fr-FR')} CFA (${methodLabel})`;
            } else {
                submitText.innerHTML = 'Enregistrer le paiement';
            }
        }
    }
    
    // Gérer les boutons de montant rapide
    document.querySelectorAll('.quick-amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const amount = parseFloat(this.getAttribute('data-amount'));
            amountInput.value = amount;
            updateCalculations();
            
            // Animation
            this.classList.add('btn-primary-modern');
            setTimeout(() => {
                this.classList.remove('btn-primary-modern');
            }, 300);
        });
    });
    
    // Gérer le changement de méthode de paiement
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Mettre à jour les cartes actives
            document.querySelectorAll('.method-card-modern').forEach(card => {
                card.classList.remove('active');
            });
            this.closest('label').classList.add('active');
            
            // Mettre à jour la référence
            updateReference();
            
            // Mettre à jour la description par défaut
            const method = this.value;
            const descriptions = {
                'cash': 'Paiement en espèces comptant',
                'card': 'Paiement par carte bancaire',
                'transfer': 'Paiement par virement bancaire',
                'mobile_money': 'Paiement par Mobile Money',
                'fedapay': 'Paiement par Fedapay',
                'check': 'Paiement par chèque'
            };
            
            if (!descriptionInput.value) {
                descriptionInput.value = descriptions[method] || '';
            }
            
            updateCalculations();
        });
    });
    
    // Gérer la saisie du montant
    amountInput.addEventListener('input', function() {
        let value = parseFloat(this.value) || 0;
        
        // Limiter à currentRemaining
        if (value > currentRemaining) {
            value = currentRemaining;
            this.value = currentRemaining;
        }
        
        updateCalculations();
    });
    
    // Valider le formulaire
    validateBtn.addEventListener('click', function() {
        const amount = parseFloat(amountInput.value) || 0;
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        
        if (!method) {
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez sélectionner une méthode de paiement',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount <= 0) {
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez saisir un montant valide',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount > currentRemaining) {
            Swal.fire({
                title: 'Erreur',
                text: `Le montant ne peut pas dépasser ${currentRemaining.toLocaleString('fr-FR')} CFA`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (amount < 100) {
            Swal.fire({
                title: 'Erreur',
                text: 'Le montant minimum est de 100 CFA',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const methodLabel = document.querySelector(`label[for="method_${method}"] .method-name`).textContent;
        
        Swal.fire({
            title: 'Validation réussie',
            html: `
                <div class="text-start">
                    <p>Le formulaire est prêt à être soumis.</p>
                    <div class="alert alert-success">
                        <strong>Montant:</strong> ${amount.toLocaleString('fr-FR')} CFA<br>
                        <strong>Méthode:</strong> ${methodLabel}<br>
                        <strong>Reste à payer:</strong> ${(currentRemaining - amount).toLocaleString('fr-FR')} CFA
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Continuer'
        });
    });
    
    // Soumettre le formulaire
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const amount = parseFloat(amountInput.value) || 0;
        
        if (amount <= 0 || amount > currentRemaining) {
            Swal.fire({
                title: 'Erreur',
                text: 'Montant invalide',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
        
        try {
            const formData = new FormData(this);
            
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: '✅ Succès',
                    html: `
                        <div class="text-start">
                            <p>${data.message}</p>
                            <div class="alert alert-success">
                                <strong>Montant:</strong> ${data.data.payment.amount.toLocaleString('fr-FR')} CFA<br>
                                <strong>Référence:</strong> ${data.data.payment.reference}<br>
                                ${data.data.transaction.is_fully_paid ? 
                                    '<div class="mt-2"><i class="fas fa-trophy me-2"></i>Transaction entièrement payée !</div>' : 
                                    `<div class="mt-2">Reste: ${data.data.transaction.remaining.toLocaleString('fr-FR')} CFA</div>`
                                }
                            </div>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Voir la transaction'
                }).then(() => {
                    window.location.href = `/transactions/${transactionId}`;
                });
            } else {
                throw new Error(data.message || 'Erreur lors du paiement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            
            Swal.fire({
                title: '❌ Erreur',
                text: error.message || 'Une erreur est survenue',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            
            submitBtn.disabled = false;
            submitText.innerHTML = 'Enregistrer le paiement';
        }
    });
    
    // Rafraîchissement périodique
    setInterval(async () => {
        try {
            const response = await fetch(`/api/transactions/${transactionId}/check-status`);
            const data = await response.json();
            
            if (data.success && data.transaction.remaining !== currentRemaining) {
                currentRemaining = data.transaction.remaining;
                amountInput.max = currentRemaining;
                updateCalculations();
            }
        } catch (error) {
            console.warn('Erreur rafraîchissement:', error);
        }
    }, 30000);
    
    // Debug panel (admin only)
    <?php if(auth()->user()->isAdmin()): ?>
        const debugPanel = document.getElementById('debug-panel');
        
        document.getElementById('hide-debug').addEventListener('click', function() {
            debugPanel.classList.add('d-none');
        });
        
        document.getElementById('refresh-debug').addEventListener('click', async function() {
            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
            
            try {
                const response = await fetch(`/api/transactions/${transactionId}/check-status`);
                const data = await response.json();
                
                // Mettre à jour les valeurs
                location.reload();
            } catch (error) {
                console.error('Erreur:', error);
            } finally {
                btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Actualiser';
            }
        });
        
        document.getElementById('force-sync').addEventListener('click', async function() {
            const result = await Swal.fire({
                title: 'Synchroniser ?',
                text: 'Recalculer tous les totaux',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            });
            
            if (result.isConfirmed) {
                const btn = this;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
                
                try {
                    const response = await fetch(`/api/transactions/${transactionId}/force-sync`);
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Succès', data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                } finally {
                    btn.innerHTML = '<i class="fas fa-cogs me-1"></i> Synchroniser';
                }
            }
        });
        
        document.getElementById('show-api').addEventListener('click', async function() {
            try {
                const response = await fetch(`/api/transactions/${transactionId}/check-status`);
                const data = await response.json();
                
                document.getElementById('apiResponseContent').textContent = JSON.stringify(data, null, 2);
                
                const modal = new bootstrap.Modal(document.getElementById('apiModal'));
                modal.show();
            } catch (error) {
                console.error('Erreur:', error);
            }
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/payment/create.blade.php ENDPATH**/ ?>