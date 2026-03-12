

<?php $__env->startSection('title', 'Rapport de Session #' . $session->id); ?>

<?php $__env->startPush('styles'); ?>
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
:root {
    /* Palette 3 couleurs professionnelle */
    --primary: #0f3b4c;
    --primary-dark: #0a2a36;
    --success: #1e5a2a;
    --success-dark: #15451e;
    --success-light: #e8f3ea;
    --gray-900: #1e293b;
    --gray-800: #2d3a4f;
    --gray-700: #334155;
    --gray-600: #475569;
    --gray-500: #64748b;
    --gray-400: #94a3b8;
    --gray-300: #cbd5e1;
    --gray-200: #e2e8f0;
    --gray-100: #f1f5f9;
    --gray-50: #f8fafc;
    --white: #ffffff;
    --border: #e9edf2;
    
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.02);
    --shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    --radius-sm: 6px;
    --radius: 10px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--gray-50);
    padding: 1.5rem 0;
}

.report-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ========================================= */
/* CARTE PRINCIPALE */
/* ========================================= */
.report-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow);
    overflow: hidden;
    border: 1px solid var(--border);
}

/* ========================================= */
/* EN-TÊTE COMPACT */
/* ========================================= */
.report-header {
    padding: 1.25rem 2rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    background: white;
}

.header-title h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}

.header-title p {
    color: var(--gray-500);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-title p i {
    color: var(--primary);
    font-size: 0.4rem;
}

.header-badge {
    padding: 0.35rem 1.25rem;
    background: var(--gray-100);
    border-radius: 100px;
    font-weight: 500;
    font-size: 0.75rem;
    color: var(--gray-700);
    border: 1px solid var(--border);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.header-badge.active {
    background: var(--success-light);
    color: var(--success);
    border-color: var(--success);
    font-weight: 600;
}

/* ========================================= */
/* GRILLE D'INFORMATIONS COMPACTE */
/* ========================================= */
.info-grid {
    padding: 1.25rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--border);
}

.info-item {
    background: var(--white);
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-icon {
    width: 36px;
    height: 36px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1rem;
}

.info-label {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.1rem;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    line-height: 1.2;
}

.info-sub {
    font-size: 0.65rem;
    color: var(--gray-500);
}

/* ========================================= */
/* KPI CARDS COMPACTES */
/* ========================================= */
.kpi-grid {
    padding: 1.25rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    border-bottom: 1px solid var(--border);
    background: white;
}

.kpi-card {
    padding: 1rem 1.25rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.kpi-title {
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.kpi-icon {
    width: 28px;
    height: 28px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.75rem;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    letter-spacing: -0.02em;
}

.kpi-footer {
    font-size: 0.65rem;
    color: var(--gray-500);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kpi-badge {
    padding: 0.2rem 0.6rem;
    background: var(--gray-100);
    border-radius: 100px;
    color: var(--gray-700);
    font-weight: 500;
    font-size: 0.6rem;
}

.kpi-badge.success {
    background: var(--success-light);
    color: var(--success);
}

/* ========================================= */
/* SECTION TITRE COMPACT */
/* ========================================= */
.section-header {
    padding: 1rem 2rem 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    width: 30px;
    height: 30px;
    background: var(--gray-100);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.875rem;
}

.section-title h2 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

.section-count {
    padding: 0.2rem 0.8rem;
    background: var(--gray-100);
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--gray-700);
}

/* ========================================= */
/* MÉTHODES DE PAIEMENT COMPACTES */
/* ========================================= */
.methods-grid {
    padding: 1rem 2rem;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.method-card {
    padding: 1rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.method-name {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
}

.method-stats {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 0.5rem;
}

.method-amount {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
}

.method-count {
    font-size: 0.65rem;
    color: var(--gray-500);
    font-weight: 500;
}

.method-progress {
    height: 3px;
    background: var(--gray-100);
    border-radius: 100px;
    overflow: hidden;
}

.method-progress-bar {
    height: 100%;
    background: var(--primary);
    border-radius: 100px;
}

.method-progress-bar.success {
    background: var(--success);
}

/* ========================================= */
/* TABLEAU COMPACT POUR 1 PAGE */
/* ========================================= */
.table-container {
    margin: 1rem 2rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: white;
    max-height: 350px;
    overflow-y: auto;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.75rem;
}

th {
    text-align: left;
    padding: 0.6rem 1rem;
    background: var(--gray-50);
    font-size: 0.6rem;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
    position: sticky;
    top: 0;
    background: var(--gray-50);
    z-index: 10;
}

td {
    padding: 0.6rem 1rem;
    border-bottom: 1px solid var(--border);
    color: var(--gray-700);
    vertical-align: middle;
}

tr:last-child td {
    border-bottom: none;
}

/* Badges compacts */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.6rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-700);
    white-space: nowrap;
}

.badge.success {
    background: var(--success-light);
    color: var(--success);
}

.method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.6rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-700);
}

.method-badge i {
    font-size: 0.6rem;
}

.method-badge.cash {
    background: var(--success-light);
    color: var(--success);
}

.method-badge.card {
    background: #e3f0f5;
    color: var(--primary);
}

/* Montants compacts */
.amount {
    font-weight: 600;
    font-size: 0.7rem;
}

.amount.positive {
    color: var(--success);
}

/* Pied du tableau compact */
.table-footer {
    padding: 0.6rem 1rem;
    background: var(--gray-50);
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.table-totals {
    display: flex;
    gap: 1.5rem;
}

.total-label {
    font-size: 0.6rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.total-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--gray-900);
}

/* ========================================= */
/* RÉSUMÉ FINANCIER COMPACT */
/* ========================================= */
.summary-grid {
    padding: 1rem 2rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    background: var(--gray-50);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.summary-item {
    text-align: center;
    padding: 0.75rem;
    background: white;
    border-radius: var(--radius);
    border: 1px solid var(--border);
}

.summary-label {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.25rem;
}

.summary-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.1rem;
    letter-spacing: -0.02em;
}

.summary-value.success {
    color: var(--success);
}

.summary-sub {
    font-size: 0.6rem;
    color: var(--gray-500);
}

/* ========================================= */
/* PIED DE PAGE COMPACT */
/* ========================================= */
.report-footer {
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    background: white;
}

.signatures {
    display: flex;
    gap: 2rem;
}

.signature-item {
    text-align: center;
}

.signature-title {
    font-size: 0.6rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.25rem;
}

.signature-line {
    width: 120px;
    height: 1px;
    background: var(--border);
    margin: 0.35rem 0;
}

.signature-name {
    color: var(--gray-600);
    font-size: 0.7rem;
    font-weight: 500;
}

.signature-empty {
    color: var(--gray-400);
    font-size: 0.7rem;
    font-style: italic;
}

.footer-actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1.25rem;
    border-radius: var(--radius);
    font-weight: 500;
    font-size: 0.75rem;
    cursor: pointer;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-secondary {
    background: white;
    color: var(--gray-700);
    border-color: var(--border);
}

.btn-success {
    background: var(--success);
    color: white;
}

/* ========================================= */
/* PRINT STYLES OPTIMISÉS 1 PAGE */
/* ========================================= */
@media print {
    @page {
        size: A4 portrait;
        margin: 0.5cm;
    }
    
    body { 
        background: white; 
        padding: 0;
        font-size: 10pt;
    }
    
    .report-wrapper {
        padding: 0;
        max-width: 100%;
    }
    
    .report-card {
        box-shadow: none;
        border: 1px solid #ddd;
        border-radius: 0;
    }
    
    .footer-actions, 
    .btn,
    .table-filters {
        display: none !important;
    }
    
    .kpi-grid {
        break-inside: avoid;
    }
    
    .methods-grid {
        break-inside: avoid;
    }
    
    .table-container {
        max-height: none;
        overflow: visible;
        break-inside: avoid;
    }
    
    th {
        background: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .badge, .method-badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Réduire encore pour l'impression */
    .info-grid { padding: 0.5rem 1rem; }
    .kpi-grid { padding: 0.5rem 1rem; }
    .section-header { padding: 0.5rem 1rem 0; }
    .methods-grid { padding: 0.5rem 1rem; }
    .table-container { margin: 0.5rem 1rem; }
    .summary-grid { padding: 0.5rem 1rem; }
    .report-footer { padding: 0.5rem 1rem; }
    
    td, th { padding: 0.3rem 0.5rem; }
    
    .info-value { font-size: 0.9rem; }
    .kpi-value { font-size: 1.25rem; }
}

/* ========================================= */
/* RESPONSIVE */
/* ========================================= */
@media (max-width: 1200px) {
    .info-grid, 
    .kpi-grid, 
    .methods-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .report-wrapper { padding: 0 0.75rem; }
    
    .info-grid, 
    .kpi-grid, 
    .methods-grid,
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .signatures {
        flex-direction: column;
        gap: 1rem;
    }
    
    .signature-line { width: 100%; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="report-wrapper">
    <div class="report-card">
        
        <!-- EN-TÊTE -->
        <div class="report-header">
            <div class="header-title">
                <h1>Rapport de Session #<?php echo e($session->id); ?></h1>
                <p>
                    <i class="fas fa-circle"></i>
                    <?php echo e(now()->format('d/m/Y H:i')); ?> • <?php echo e(auth()->user()->name); ?>

                </p>
            </div>
            
            <div class="header-badge <?php echo e($session->status); ?>">
                <i class="fas fa-<?php echo e($session->status == 'active' ? 'play' : 'check-circle'); ?>"></i>
                <?php echo e($session->status == 'active' ? 'Session active' : 'Fermée'); ?>

            </div>
        </div>

        <!-- INFORMATIONS GÉNÉRALES -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div class="info-content">
                    <div class="info-label">RÉCEPTIONNISTE</div>
                    <div class="info-value"><?php echo e($session->user->name); ?></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar"></i></div>
                <div class="info-content">
                    <div class="info-label">DATE</div>
                    <div class="info-value"><?php echo e($session->start_time->format('d/m/Y')); ?></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-clock"></i></div>
                <div class="info-content">
                    <div class="info-label">HORAIRES</div>
                    <div class="info-value"><?php echo e($session->start_time->format('H:i')); ?>-<?php echo e($session->end_time ? $session->end_time->format('H:i') : '...'); ?></div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-coins"></i></div>
                <div class="info-content">
                    <div class="info-label">SOLDE INITIAL</div>
                    <div class="info-value"><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></div>
                </div>
            </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Encaissé</span>
                    <span class="kpi-icon"><i class="fas fa-arrow-up"></i></span>
                </div>
                <div class="kpi-value"><?php echo e(number_format($totalCompleted, 0, ',', ' ')); ?></div>
                <div class="kpi-footer">
                    <span>FCFA</span>
                    <span class="kpi-badge success"><?php echo e($paymentCount); ?> paiements</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Remboursé</span>
                    <span class="kpi-icon"><i class="fas fa-arrow-down"></i></span>
                </div>
                <div class="kpi-value"><?php echo e(number_format($totalRefunded, 0, ',', ' ')); ?></div>
                <div class="kpi-footer">
                    <span>FCFA</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Solde actuel</span>
                    <span class="kpi-icon"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="kpi-value"><?php echo e(number_format($session->final_balance ?? $session->current_balance, 0, ',', ' ')); ?></div>
                <div class="kpi-footer">
                    <?php if($session->balance_difference != 0): ?>
                    <span>Écart: <?php echo e(number_format(abs($session->balance_difference), 0, ',', ' ')); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Moyenne</span>
                    <span class="kpi-icon"><i class="fas fa-chart-line"></i></span>
                </div>
                <div class="kpi-value"><?php echo e($paymentCount > 0 ? number_format($totalCompleted / $paymentCount, 0, ',', ' ') : 0); ?></div>
                <div class="kpi-footer">
                    <span>FCFA/tx</span>
                </div>
            </div>
        </div>

        <!-- RÉPARTITION DES PAIEMENTS -->
        <?php if(isset($byMethod) && $byMethod->count() > 0): ?>
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-pie"></i>
                <h2>Répartition</h2>
            </div>
            <span class="section-count"><?php echo e($paymentCount); ?> tx</span>
        </div>

        <div class="methods-grid">
            <?php $__currentLoopData = $byMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $methodName = strtolower($method['method'] ?? '');
                $isCash = str_contains($methodName, 'cash') || str_contains($methodName, 'espèces') || $method['method'] == 'Espèces';
                $percentage = $paymentCount > 0 ? round(($method['count'] / $paymentCount) * 100) : 0;
            ?>
            <div class="method-card">
                <div class="method-name"><?php echo e($method['method']); ?></div>
                <div class="method-stats">
                    <span class="method-amount"><?php echo e(number_format($method['total'], 0, ',', ' ')); ?></span>
                    <span class="method-count"><?php echo e($method['count']); ?> tx</span>
                </div>
                <div class="method-progress">
                    <div class="method-progress-bar <?php echo e($isCash ? 'success' : ''); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- TABLEAU DES PAIEMENTS (VERSION COMPACTE) -->
        <?php if($payments->count() > 0): ?>
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-list"></i>
                <h2>Transactions</h2>
            </div>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Réf.</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Méthode</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payments->take(15); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php
                            $isCompleted = $payment->status == 'completed';
                            $isCash = $payment->payment_method == 'cash';
                            
                            $methodClass = 'cash';
                            $methodIcon = 'fa-money-bill-wave';
                            
                            if($payment->payment_method == 'card' || $payment->payment_method == 'fedapay') {
                                $methodClass = 'card';
                                $methodIcon = 'fa-credit-card';
                            } elseif($payment->payment_method == 'mobile_money') {
                                $methodClass = 'mobile';
                                $methodIcon = 'fa-mobile-alt';
                            }
                        ?>
                        <tr data-status="<?php echo e($payment->status); ?>" data-method="<?php echo e($payment->payment_method); ?>">
                            <td><span style="font-family: monospace;"><?php echo e(substr($payment->reference, -8)); ?></span></td>
                            <td><?php echo e($payment->created_at->format('d/m H:i')); ?></td>
                            <td>
                                <?php if($payment->transaction && $payment->transaction->customer): ?>
                                    <?php echo e(Str::limit($payment->transaction->customer->name, 15)); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="method-badge <?php echo e($methodClass); ?>">
                                    <i class="fas <?php echo e($methodIcon); ?>"></i>
                                </span>
                            </td>
                            <td>
                                <span class="amount <?php echo e($payment->amount > 0 ? 'positive' : ''); ?>">
                                    <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($isCompleted ? 'success' : ''); ?>">
                                    <?php echo e($isCompleted ? '✓' : '⏱'); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="table-totals">
                    <div><span class="total-label">Total</span> <span class="total-value"><?php echo e($paymentCount); ?></span></div>
                    <div><span class="total-label">Montant</span> <span class="total-value success"><?php echo e(number_format($totalCompleted, 0, ',', ' ')); ?></span></div>
                </div>
                <div>
                    <span class="badge success">✓ <?php echo e($payments->where('status', 'completed')->count()); ?></span>
                    <span class="badge">⏱ <?php echo e($payments->where('status', 'pending')->count()); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- RÉSUMÉ FINANCIER -->
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Solde initial</div>
                <div class="summary-value"><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Total encaissé</div>
                <div class="summary-value success"><?php echo e(number_format($totalCompleted, 0, ',', ' ')); ?></div>
            </div>
            
            <div class="summary-item">
                <div class="summary-label">Solde final</div>
                <div class="summary-value"><?php echo e(number_format($session->final_balance ?? $session->current_balance, 0, ',', ' ')); ?></div>
            </div>
        </div>

        <!-- SIGNATURES -->
        <div class="report-footer">
            <div class="signatures">
                <div class="signature-item">
                    <div class="signature-title">Réceptionniste</div>
                    <div class="signature-line"></div>
                    <span class="signature-name"><?php echo e($session->user->name); ?></span>
                </div>
                <div class="signature-item">
                    <div class="signature-title">Supérieur</div>
                    <div class="signature-line"></div>
                    <span class="signature-empty"></span>
                </div>
                <div class="signature-item">
                    <div class="signature-title">Cachet</div>
                    <div class="signature-line"></div>
                    <span class="signature-empty"></span>
                </div>
            </div>
            
            <div class="footer-actions">
                <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    window.print();
}

// Animation simple
document.addEventListener('DOMContentLoaded', function() {
    // Réduire le tableau si trop de lignes
    const rows = document.querySelectorAll('#payments-table tbody tr');
    if (rows.length > 18) {
        for (let i = 18; i < rows.length; i++) {
            rows[i].style.display = 'none';
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/sessions/report.blade.php ENDPATH**/ ?>