

<?php $__env->startSection('title', 'Rapport de Session #' . $session->id); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root {
    --primary: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
    --border: #e2e8f0;
}

.report-container {
    max-width: 1200px;
    margin: 2rem auto;
}

.report-header {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.report-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.report-subtitle {
    color: #64748b;
    font-size: 0.9rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
}

.stat-value-success {
    color: var(--success);
}

.stat-value-warning {
    color: var(--warning);
}

.stat-value-danger {
    color: var(--danger);
}

.payments-table {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.payments-table table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table th {
    background: var(--light);
    padding: 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
}

.payments-table td {
    padding: 1rem;
    border-top: 1px solid var(--border);
}

.method-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.method-cash {
    background: rgba(16,185,129,0.1);
    color: var(--success);
}

.method-card {
    background: rgba(59,130,246,0.1);
    color: var(--primary);
}

.method-mobile {
    background: rgba(245,158,11,0.1);
    color: var(--warning);
}

.summary-box {
    background: var(--light);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px dashed var(--border);
}

.summary-row:last-child {
    border-bottom: none;
}

.btn-print {
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-print:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-back {
    background: white;
    color: var(--dark);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-back:hover {
    background: var(--light);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="report-container">
    
    <!-- En-tête -->
    <div class="report-header">
        <h1 class="report-title">Rapport de Session #<?php echo e($session->id); ?></h1>
        <p class="report-subtitle">
            Généré le <?php echo e(now()->format('d/m/Y à H:i')); ?> par <?php echo e(auth()->user()->name); ?>

        </p>
    </div>

    <!-- Informations générales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Réceptionniste</div>
            <div class="stat-value"><?php echo e($session->user->name); ?></div>
            <small class="text-muted"><?php echo e($session->user->role); ?></small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Période</div>
            <div class="stat-value"><?php echo e($session->start_time->format('d/m/Y')); ?></div>
            <small class="text-muted">
                <?php echo e($session->start_time->format('H:i')); ?> - 
                <?php echo e($session->end_time ? $session->end_time->format('H:i') : 'En cours'); ?>

            </small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Durée</div>
            <div class="stat-value"><?php echo e($durationFormatted); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Statut</div>
            <div class="stat-value <?php echo e($session->status == 'active' ? 'stat-value-success' : 'stat-value-warning'); ?>">
                <?php echo e($session->status == 'active' ? 'Active' : 'Fermée'); ?>

            </div>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Solde initial</div>
            <div class="stat-value"><?php echo e(number_format($session->initial_balance, 0, ',', ' ')); ?></div>
            <small class="text-muted">FCFA</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total encaissé</div>
            <div class="stat-value stat-value-success"><?php echo e(number_format($totalCompleted, 0, ',', ' ')); ?></div>
            <small class="text-muted"><?php echo e($paymentCount); ?> paiement(s)</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total remboursé</div>
            <div class="stat-value stat-value-danger"><?php echo e(number_format($totalRefunded, 0, ',', ' ')); ?></div>
            <small class="text-muted">FCFA</small>
        </div>

        <div class="stat-card">
            <div class="stat-label">Solde final</div>
            <div class="stat-value <?php echo e($session->balance_difference > 0 ? 'stat-value-success' : ($session->balance_difference < 0 ? 'stat-value-danger' : '')); ?>">
                <?php echo e(number_format($session->final_balance ?? $session->current_balance, 0, ',', ' ')); ?>

            </div>
            <?php if($session->balance_difference != 0): ?>
            <small class="text-<?php echo e($session->balance_difference > 0 ? 'success' : 'danger'); ?>">
                Écart: <?php echo e(number_format(abs($session->balance_difference), 0, ',', ' ')); ?> FCFA
            </small>
            <?php endif; ?>
        </div>
    </div>

    <!-- Détail par méthode -->
    <?php if($byMethod->count() > 0): ?>
    <div class="summary-box">
        <h5 class="mb-3">Répartition par mode de paiement</h5>
        <div class="row">
            <?php $__currentLoopData = $byMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-3">
                <div class="p-3 bg-white rounded border">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas <?php echo e($method['icon']); ?>" style="color: var(--primary);"></i>
                        <strong><?php echo e($method['method']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><?php echo e($method['count']); ?> paiement(s)</span>
                        <span class="fw-bold"><?php echo e(number_format($method['total'], 0, ',', ' ')); ?> FCFA</span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liste des paiements -->
    <?php if($payments->count() > 0): ?>
    <div class="payments-table">
        <div class="p-3 bg-white border-bottom">
            <h5 class="mb-0">Détail des paiements</h5>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($payment->reference); ?></strong></td>
                        <td><?php echo e($payment->created_at->format('d/m H:i')); ?></td>
                        <td>
                            <?php if($payment->transaction && $payment->transaction->customer): ?>
                                <?php echo e($payment->transaction->customer->name); ?>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold <?php echo e($payment->amount > 0 ? 'text-success' : 'text-danger'); ?>">
                            <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA
                        </td>
                        <td>
                            <?php
                                $methodClass = 'method-cash';
                                $icon = 'fa-money-bill-wave';
                                if($payment->payment_method == 'card') {
                                    $methodClass = 'method-card';
                                    $icon = 'fa-credit-card';
                                } elseif($payment->payment_method == 'mobile_money') {
                                    $methodClass = 'method-mobile';
                                    $icon = 'fa-mobile-alt';
                                }
                            ?>
                            <span class="method-badge <?php echo e($methodClass); ?>">
                                <i class="fas <?php echo e($icon); ?>"></i>
                                <?php echo e($payment->payment_method_label); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($payment->status_class); ?>">
                                <?php echo e($payment->status_text); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-between mt-4">
        <a href="<?php echo e(route('cashier.sessions.show', $session)); ?>" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Retour aux détails
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print me-2"></i>Imprimer le rapport
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/cashier/sessions/report.blade.php ENDPATH**/ ?>