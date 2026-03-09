
<?php $__env->startSection('title', 'Détails de la Réservation #' . $transaction->id); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN MODERNE - MÊME FONCTIONNALITÉS
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

.detail-page {
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
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
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

/* Info badge heures */
.info-badge {
    background: var(--blue-50);
    color: var(--blue-600);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--blue-200);
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
}

.btn-warning-modern {
    background: var(--amber-500);
    color: white;
}

.btn-warning-modern:hover {
    background: var(--amber-600);
    transform: translateY(-1px);
}

.btn-outline-modern {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-outline-danger-modern {
    background: white;
    color: #ef4444;
    border: 1px solid #ef4444;
}

.btn-outline-danger-modern:hover {
    background: #ef4444;
    color: white;
}

.btn-sm {
    padding: 6px 14px;
    font-size: 0.813rem;
}

.btn-modern:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Badges statut */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.813rem;
    font-weight: 600;
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

.status-cancelled {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.status-no_show {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Badge late checkout */
.badge-late {
    background: var(--amber-100);
    color: var(--amber-700);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid var(--amber-200);
}

/* Badge paiement en attente */
.badge-pending {
    background: #fff3cd;
    color: #856404;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid #ffeeba;
}

/* Cartes */
.detail-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
}

.detail-card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--gray-100);
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

.card-header h5 i {
    color: var(--primary-500);
}

.card-body {
    padding: 24px;
}

/* Labels */
.detail-label {
    font-size: 0.688rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.detail-value {
    font-size: 0.938rem;
    font-weight: 500;
    color: var(--gray-800);
    margin-bottom: 12px;
}

/* Avatar client */
.client-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.client-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* Badge chambre */
.room-badge-large {
    background: var(--primary-50);
    color: var(--primary-700);
    font-weight: 700;
    padding: 8px 24px;
    border-radius: 40px;
    font-size: 1.25rem;
    display: inline-block;
    border: 1px solid var(--primary-200);
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-200);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -19px;
    top: 6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--primary-500);
    border: 2px solid white;
}

/* Stat boxes */
.stat-box {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    border: 1px solid var(--gray-200);
}

.stat-label {
    font-size: 0.688rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
}

.stat-value-success {
    color: var(--primary-600);
}

.stat-value-danger {
    color: #ef4444;
}

.stat-value-primary {
    color: var(--blue-600);
}

.stat-value-warning {
    color: var(--amber-600);
}

/* Sélecteur statut */
.status-select {
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: white;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    min-width: 180px;
}

.status-select:focus {
    outline: none;
    border-color: var(--primary-500);
}

/* Alertes statut */
.alert-status {
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-left: 4px solid;
}

.alert-status-reservation {
    border-left-color: var(--amber-500);
    background: var(--amber-50);
}

.alert-status-active {
    border-left-color: var(--primary-500);
    background: var(--primary-50);
}

.alert-status-completed {
    border-left-color: var(--blue-500);
    background: var(--blue-50);
}

.alert-status-cancelled {
    border-left-color: #ef4444;
    background: #fee2e2;
}

.alert-status-no_show {
    border-left-color: var(--gray-500);
    background: var(--gray-100);
}

/* Divider */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 20px 0;
}

/* Actions rapides */
.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
}

/* Badge paiement */
.payment-status-paid {
    color: var(--primary-600);
    font-weight: 600;
}

.payment-status-pending {
    color: var(--amber-600);
    font-weight: 600;
}

/* Toast notification */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Loader */
.loader {
    border: 3px solid #f3f3f3;
    border-radius: 50%;
    border-top: 3px solid var(--primary-500);
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.alert.alert-success {
    border-radius: 12px;
    background: var(--primary-50);
    border-color: var(--primary-200);
    color: var(--primary-800);
}

.alert.alert-danger {
    border-radius: 12px;
    background: #fee2e2;
    border-color: #fecaca;
    color: #b91c1c;
}

.alert.alert-warning {
    border-radius: 12px;
    background: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}

.modal-content {
    border-radius: 20px;
    border: none;
}

.modal-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
}

.modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
}
</style>

<div class="detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="<?php echo e(route('dashboard.index')); ?>"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="<?php echo e(route('transaction.index')); ?>">Réservations</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">#<?php echo e($transaction->id); ?></span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-calendar-check"></i>
            </span>
            <h1>Réservation #<?php echo e($transaction->id); ?></h1>
            <span class="info-badge">
                <i class="fas fa-clock"></i> Check-in 12h | Check-out 12h (largesse 14h)
            </span>
            
            
            <?php if($transaction->late_checkout): ?>
                <span class="badge-late">
                    <i class="fas fa-clock"></i> Late checkout: <?php echo e($transaction->expected_checkout_time); ?>

                    <?php if($transaction->late_checkout_fee): ?>
                        (+<?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> FCFA)
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
        
        <div class="d-flex gap-2 flex-wrap">
            <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
            <form action="<?php echo e(route('transaction.updateStatus', $transaction)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <select name="status" class="status-select" onchange="this.form.submit()">
                    <option value="reservation" <?php echo e($transaction->status == 'reservation' ? 'selected' : ''); ?>>📅 Réservation</option>
                    <option value="active" <?php echo e($transaction->status == 'active' ? 'selected' : ''); ?>>🏨 Dans l'hôtel</option>
                    <option value="completed" <?php echo e($transaction->status == 'completed' ? 'selected' : ''); ?>>✅ Terminé</option>
                    <option value="cancelled" <?php echo e($transaction->status == 'cancelled' ? 'selected' : ''); ?>>❌ Annulée</option>
                    <option value="no_show" <?php echo e($transaction->status == 'no_show' ? 'selected' : ''); ?>>👤 No Show</option>
                </select>
            </form>
            <?php endif; ?>
            
            <a href="<?php echo e(route('transaction.index')); ?>" class="btn-modern btn-outline-modern">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Messages de session -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo session('success'); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('error') || session('failed')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error') ?? session('failed')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('warning')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i> <?php echo e(session('info')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Alerte statut avec heure mise à jour -->
    <?php if($transaction->status == 'reservation'): ?>
    <div class="alert-status alert-status-reservation">
        <i class="fas fa-calendar-check fa-2x" style="color: var(--amber-600);"></i>
        <div>
            <strong class="d-block mb-1">📅 RÉSERVATION</strong>
            <p class="mb-0 small">Arrivée prévue : <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?> à 12h00</strong></p>
        </div>
    </div>
    <?php elseif($transaction->status == 'active'): ?>
    <div class="alert-status alert-status-active">
        <i class="fas fa-bed fa-2x" style="color: var(--primary-600);"></i>
        <div>
            <strong class="d-block mb-1">🏨 DANS L'HÔTEL</strong>
            <p class="mb-0 small">
                Départ prévu : 
                <strong>
                    <?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?> 
                    <?php if($transaction->late_checkout): ?>
                        à <span style="color: var(--amber-600);"><?php echo e($transaction->expected_checkout_time); ?></span>
                        <?php if($transaction->late_checkout_fee): ?>
                            <span class="badge-late" style="margin-left: 8px;">+<?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> FCFA</span>
                        <?php endif; ?>
                    <?php else: ?>
                        à 12h00
                    <?php endif; ?>
                </strong>
            </p>
        </div>
    </div>
    <?php elseif($transaction->status == 'completed'): ?>
    <div class="alert-status alert-status-completed">
        <i class="fas fa-check-circle fa-2x" style="color: var(--blue-600);"></i>
        <div>
            <strong class="d-block mb-1">✅ SÉJOUR TERMINÉ</strong>
            <p class="mb-0 small">
                Client parti le <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_out_actual ?? $transaction->check_out)->format('d/m/Y à H:i')); ?></strong>
                <?php if($transaction->late_checkout): ?>
                    <br><span class="badge-late"><i class="fas fa-clock"></i> Late checkout: <?php echo e($transaction->expected_checkout_time); ?> (<?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> FCFA)</span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php elseif($transaction->status == 'cancelled'): ?>
    <div class="alert-status alert-status-cancelled">
        <i class="fas fa-ban fa-2x" style="color: #b91c1c;"></i>
        <div>
            <strong class="d-block mb-1">❌ ANNULÉE</strong>
            <?php if($transaction->cancelled_at): ?>
            <p class="mb-0 small">Annulée le <strong><?php echo e(\Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y à H:i')); ?></strong>
                <?php if($transaction->cancel_reason): ?>
                <br>Raison : <?php echo e($transaction->cancel_reason); ?>

                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php elseif($transaction->status == 'no_show'): ?>
    <div class="alert-status alert-status-no_show">
        <i class="fas fa-user-slash fa-2x" style="color: var(--gray-500);"></i>
        <div>
            <strong class="d-block mb-1">👤 NO SHOW</strong>
            <p class="mb-0 small">Client ne s'est pas présenté</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Actions rapides -->
    <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist'])): ?>
    <div class="quick-actions">
        <?php if($transaction->status == 'reservation'): ?>
            <?php
                $now = \Carbon\Carbon::now();
                $checkInDateTime = \Carbon\Carbon::parse($transaction->check_in)->setTime(12, 0, 0);
            ?>
            <?php if($now->gte($checkInDateTime)): ?>
                <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-sign-in-alt me-1"></i>Arrivée
                    </button>
                </form>
            <?php else: ?>
                <button type="button" class="btn-modern btn-outline-modern" disabled>
                    <i class="fas fa-clock me-1"></i>Arrivée à 12h
                </button>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if($transaction->status == 'active'): ?>
            <?php
                $now = \Carbon\Carbon::now();
                $checkOutDate = \Carbon\Carbon::parse($transaction->check_out)->setTime(12, 0, 0);
                $checkOutLargess = $checkOutDate->copy()->setTime(14, 0, 0);
                $lateCheckoutEnd = $checkOutDate->copy()->setTime(20, 0, 0);
                
                // ✅ Vérification plus robuste du paiement late checkout
                $latePayment = null;
                $isLatePaid = false;
                $hasPendingLatePayment = false;
                
                if ($transaction->late_checkout && $transaction->late_checkout_fee > 0) {
                    foreach ($transaction->payments as $payment) {
                        $isLateReference = $payment->reference && str_contains($payment->reference, 'LATE-');
                        $isLateDescription = $payment->description && (
                            str_contains($payment->description, 'Late checkout') || 
                            str_contains($payment->description, 'late checkout')
                        );
                        
                        if ($isLateReference || $isLateDescription) {
                            if ($payment->status == 'completed') {
                                $latePayment = $payment;
                                $isLatePaid = true;
                                break;
                            } elseif ($payment->status == 'pending') {
                                $hasPendingLatePayment = true;
                                $latePayment = $payment;
                            }
                        }
                    }
                }
                
                // Debug silencieux
                if (!$isLatePaid && $transaction->late_checkout) {
                    \Log::info('Debug late checkout', [
                        'transaction_id' => $transaction->id,
                        'late_checkout' => $transaction->late_checkout,
                        'late_fee' => $transaction->late_checkout_fee,
                        'is_late_paid' => $isLatePaid,
                        'has_pending' => $hasPendingLatePayment,
                        'payments_count' => $transaction->payments->count(),
                    ]);
                }
            ?>
            
            
            <?php if($now->gte($checkOutDate) && $now->lte($checkOutLargess)): ?>
                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-modern btn-success-modern">
                        <i class="fas fa-sign-out-alt me-1"></i>Départ (largesse)
                    </button>
                </form>
            
            
            <?php elseif($now->gt($checkOutLargess) && $now->lt($lateCheckoutEnd)): ?>
                <?php if(!$transaction->late_checkout): ?>
                    
                    <button type="button" class="btn-modern btn-warning-modern" data-bs-toggle="modal" data-bs-target="#lateCheckoutModal">
                        <i class="fas fa-clock me-1"></i>Late checkout
                    </button>
                <?php else: ?>
                    
                    <?php if($isLatePaid): ?>
                        
                        <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-modern btn-success-modern">
                                <i class="fas fa-sign-out-alt me-1"></i>Départ (late checkout)
                            </button>
                        </form>
                    <?php elseif($hasPendingLatePayment): ?>
                        
                        <div class="d-inline-block" data-bs-toggle="tooltip" 
                             title="Paiement en attente - Cliquez sur 'Marquer payé' dans la liste des paiements">
                            <span class="btn-modern btn-outline-modern disabled">
                                <i class="fas fa-clock me-1"></i>Départ (paiement en attente)
                            </span>
                        </div>
                    <?php else: ?>
                        
                        <div class="d-inline-block" data-bs-toggle="tooltip" 
                             title="Supplément late checkout de <?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> FCFA non payé">
                            <span class="btn-modern btn-outline-modern disabled">
                                <i class="fas fa-ban me-1"></i>Départ bloqué
                            </span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            
            
            <?php elseif($now->gte($lateCheckoutEnd) && !$transaction->late_checkout): ?>
                <a href="<?php echo e(route('transaction.extend', $transaction)); ?>" class="btn-modern btn-warning-modern">
                    <i class="fas fa-calendar-plus me-1"></i>Prolonger d'une nuit
                </a>
            
            
            <?php elseif($now->gte($lateCheckoutEnd) && $transaction->late_checkout): ?>
                <span class="btn-modern btn-outline-modern disabled" 
                      data-bs-toggle="tooltip" 
                      title="Départ après 20h - Prolongation nécessaire">
                    <i class="fas fa-clock me-1"></i>Départ impossible
                </span>
            
            
            <?php elseif($transaction->late_checkout && $now->lt($checkOutLargess)): ?>
                <span class="btn-modern btn-outline-modern disabled" 
                      data-bs-toggle="tooltip" 
                      title="Départ prévu à <?php echo e($transaction->expected_checkout_time); ?>">
                    <i class="fas fa-clock me-1"></i>Départ à <?php echo e($transaction->expected_checkout_time); ?>

                </span>
            
            
            <?php else: ?>
                <button type="button" class="btn-modern btn-outline-modern" disabled>
                    <i class="fas fa-clock me-1"></i>Départ à 12h
                </button>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if(in_array($transaction->status, ['reservation', 'active'])): ?>
        <a href="<?php echo e(route('transaction.extend', $transaction)); ?>" class="btn-modern btn-warning-modern">
            <i class="fas fa-calendar-plus me-1"></i>Prolonger
        </a>
        <?php endif; ?>
        
        <?php if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
        <a href="<?php echo e(route('transaction.edit', $transaction)); ?>" class="btn-modern btn-outline-modern">
            <i class="fas fa-edit me-1"></i>Modifier
        </a>
        <?php endif; ?>
        
        <?php if($remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show'])): ?>
        <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-modern btn-primary-modern">
            <i class="fas fa-money-bill-wave me-1"></i>Paiement
        </a>
        <?php endif; ?>
        
        <?php if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
        <button type="button" class="btn-modern btn-outline-danger-modern" 
                data-bs-toggle="modal" data-bs-target="#cancelModal">
            <i class="fas fa-ban me-1"></i>Annuler
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-8">
            <!-- Client -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i>Informations Client</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="client-avatar">
                            <?php if($transaction->customer->user && $transaction->customer->user->getAvatar()): ?>
                                <img src="<?php echo e($transaction->customer->user->getAvatar()); ?>" alt="<?php echo e($transaction->customer->name); ?>">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($transaction->customer->name, 0, 1))); ?>

                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="mb-1" style="color: var(--gray-800); font-weight: 600;"><?php echo e($transaction->customer->name); ?></h4>
                            <p class="text-muted small mb-0"><?php echo e($transaction->customer->email ?? 'Email non renseigné'); ?></p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Téléphone</p>
                            <p class="detail-value"><?php echo e($transaction->customer->phone ?? 'Non renseigné'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">NIC/ID</p>
                            <p class="detail-value"><?php echo e($transaction->customer->nik ?? 'Non renseigné'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-2">
                        <a href="<?php echo e(route('customer.show', $transaction->customer)); ?>" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir profil
                        </a>
                        <a href="<?php echo e(route('transaction.reservation.customerReservations', $transaction->customer)); ?>" class="btn-modern btn-outline-modern btn-sm">
                            <i class="fas fa-history me-1"></i>Historique
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chambre et dates avec heure mise à jour -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-bed"></i>Informations Séjour</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 text-center mb-3 mb-md-0">
                            <p class="detail-label">Chambre</p>
                            <span class="room-badge-large"><?php echo e($transaction->room->number); ?></span>
                            <p class="text-muted small mt-2"><?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?></p>
                        </div>
                        <div class="col-md-6 text-center">
                            <p class="detail-label">Durée du séjour</p>
                            <span class="room-badge-large" style="background: var(--gray-100); color: var(--gray-700); border-color: var(--gray-200);">
                                <?php echo e($nights); ?> nuit<?php echo e($nights > 1 ? 's' : ''); ?>

                                <?php if($transaction->late_checkout): ?>
                                    <br><small class="text-warning">(dont late checkout)</small>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Arrivée</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-check me-2" style="color: var(--primary-500);"></i>
                                <?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?>

                                <span class="text-muted ms-2">12:00</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">Départ</p>
                            <p class="detail-value">
                                <i class="fas fa-calendar-times me-2" style="color: #ef4444;"></i>
                                <?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?>

                                <span class="text-muted ms-2">
                                    <?php if($transaction->late_checkout): ?>
                                        <strong style="color: var(--amber-600);"><?php echo e($transaction->expected_checkout_time); ?></strong>
                                    <?php else: ?>
                                        12:00
                                    <?php endif; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Statut chambre</p>
                            <p class="detail-value">
                                <?php if($transaction->room->roomStatus): ?>
                                <span class="status-badge <?php echo e($transaction->room->roomStatus->name == 'Occupée' ? 'status-active' : ($transaction->room->roomStatus->name == 'Disponible' ? 'status-completed' : 'status-reservation')); ?>">
                                    <?php echo e($transaction->room->roomStatus->name); ?>

                                </span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">Statut réservation</p>
                            <p class="detail-value">
                                <span class="status-badge status-<?php echo e($transaction->status); ?>">
                                    <?php echo e($transaction->status_label); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paiements avec affichage du supplément late checkout -->
            <div class="detail-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-money-bill-wave"></i>Paiements</h5>
                    <span class="status-badge <?php echo e($isFullyPaid ? 'status-active' : ($remaining > 0 ? 'status-reservation' : 'status-completed')); ?>">
                        <?php echo e($isFullyPaid ? 'Soldé' : ($remaining > 0 ? 'En attente' : 'Aucune dette')); ?>

                    </span>
                </div>
                <div class="card-body">
                    <!-- Résumé financier avec late checkout -->
                    <div class="row g-3 mb-4">
                        <?php
                            $totalInitial = $totalPrice - ($transaction->late_checkout_fee ?? 0);
                        ?>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Total initial</p>
                                <p class="stat-value"><?php echo e(number_format($totalInitial, 0, ',', ' ')); ?> CFA</p>
                            </div>
                        </div>
                        
                        <?php if($transaction->late_checkout_fee > 0): ?>
                        <div class="col-md-3">
                            <div class="stat-box" style="background: var(--amber-50); border-color: var(--amber-200);">
                                <p class="stat-label">Supplément late</p>
                                <p class="stat-value" style="color: var(--amber-600);">+ <?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> CFA</p>
                                <small class="text-muted"><?php echo e($transaction->expected_checkout_time ?? 'N/A'); ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Total final</p>
                                <p class="stat-value stat-value-primary"><?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> CFA</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Payé</p>
                                <p class="stat-value stat-value-success"><?php echo e(number_format($totalPayment, 0, ',', ' ')); ?> CFA</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-box">
                                <p class="stat-label">Reste</p>
                                <p class="stat-value <?php echo e($remaining > 0 ? 'stat-value-danger' : 'stat-value-success'); ?>">
                                    <?php if($remaining > 0): ?>
                                        <?php echo e(number_format($remaining, 0, ',', ' ')); ?> CFA
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des paiements -->
                    <?php if($payments && $payments->count() > 0): ?>
                        <p class="detail-label mb-3">Historique des paiements</p>
                        <div class="timeline">
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isLatePayment = (str_contains($payment->reference ?? '', 'LATE-') || 
                                                      str_contains($payment->description ?? '', 'Late checkout') ||
                                                      str_contains($payment->description ?? '', 'late checkout'));
                                ?>
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1" style="font-weight: 600;">
                                                Paiement #<?php echo e($payment->id); ?>

                                                <?php if($isLatePayment): ?>
                                                    <span class="badge-late" style="margin-left: 8px;">Late checkout</span>
                                                <?php endif; ?>
                                                <?php if($payment->status == 'pending'): ?>
                                                    <span class="badge-pending" style="margin-left: 8px;">
                                                        <i class="fas fa-clock me-1"></i>En attente
                                                    </span>
                                                <?php elseif($payment->status == 'completed'): ?>
                                                    <span class="badge-late" style="background: var(--primary-100); color: var(--primary-700); margin-left: 8px;">
                                                        <i class="fas fa-check-circle me-1"></i>Payé
                                                    </span>
                                                <?php endif; ?>
                                            </h6>
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo e(\Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i')); ?>

                                            </p>
                                            <?php if($payment->payment_method): ?>
                                                <p class="text-muted small mb-1">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    <?php echo e(ucfirst($payment->payment_method_label ?? $payment->payment_method)); ?>

                                                </p>
                                            <?php endif; ?>
                                            <?php if($payment->description): ?>
                                                <p class="text-muted small mb-0">Note: <?php echo e($payment->description); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <p class="fw-bold mb-1" style="font-size: 1.1rem; <?php echo e($payment->status == 'pending' ? 'color: var(--amber-600);' : 'color: var(--primary-600);'); ?>">
                                                <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> CFA
                                            </p>
                                            <a href="<?php echo e(route('payment.invoice', $payment)); ?>" class="btn-modern btn-outline-modern btn-sm" target="_blank">
                                                <i class="fas fa-receipt"></i> Reçu
                                            </a>
                                            <?php if($payment->status == 'pending'): ?>
                                                <button onclick="markPaymentAsPaid(<?php echo e($payment->id); ?>)" class="btn btn-sm btn-success mt-1">
                                                    <i class="fas fa-check"></i> Marquer payé
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x mb-3" style="color: var(--gray-300);"></i>
                            <h5 style="color: var(--gray-600);">Aucun paiement</h5>
                            <p class="text-muted small">Aucun paiement n'a été effectué pour cette réservation.</p>
                            <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && $remaining > 0 && !in_array($transaction->status, ['cancelled', 'no_show'])): ?>
                                <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" class="btn-modern btn-primary-modern mt-2">
                                    <i class="fas fa-plus me-1"></i>Ajouter un paiement
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if($transaction->late_checkout_fee > 0 && $transaction->status == 'active'): ?>
                        <?php
                            $remainingWithLate = $totalPrice - $totalPayment;
                            $latePayment = $payments->first(function($p) {
                                return (($p->reference && str_contains($p->reference, 'LATE-')) || 
                                       ($p->description && (str_contains($p->description, 'Late checkout') || str_contains($p->description, 'late checkout'))));
                            });
                        ?>
                        <?php if($remainingWithLate > 0 && (!$latePayment || $latePayment->status == 'pending')): ?>
                            <div class="alert alert-warning mt-3" style="border-left: 4px solid var(--amber-500);">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <i class="fas fa-clock me-2" style="color: var(--amber-600);"></i>
                                        <strong>Late checkout enregistré</strong><br>
                                        <span class="small">
                                            Départ à <strong><?php echo e($transaction->expected_checkout_time); ?></strong> - 
                                            Supplément de <strong><?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> FCFA</strong>
                                            <?php if($latePayment && $latePayment->status == 'pending'): ?>
                                                <br><span class="text-info"><i class="fas fa-info-circle me-1"></i>Paiement en attente - Cliquez sur "Marquer payé"</span>
                                            <?php elseif(!$latePayment): ?>
                                                <br><span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Aucun paiement créé</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <?php if(!$latePayment): ?>
                                        <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>?amount=<?php echo e($transaction->late_checkout_fee); ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-money-bill-wave me-1"></i>Créer un paiement
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-4">
            <!-- Actions rapides compactes -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('transaction.history', $transaction)); ?>" class="btn-modern btn-outline-modern w-100">
                            <i class="fas fa-history me-1"></i>Historique
                        </a>
                        
                        <?php if($payments && $payments->count() > 0): ?>
                        <a href="<?php echo e(route('transaction.invoice', $transaction)); ?>" class="btn-modern btn-outline-modern w-100" target="_blank">
                            <i class="fas fa-file-invoice me-1"></i>Facture
                        </a>
                        <?php endif; ?>
                        
                        <?php if($transaction->status == 'cancelled' && in_array(auth()->user()->role, ['Super', 'Admin'])): ?>
                        <form action="<?php echo e(route('transaction.restore', $transaction)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-modern btn-warning-modern w-100" onclick="return confirm('Restaurer cette réservation ?')">
                                <i class="fas fa-undo me-1"></i>Restaurer
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires avec heure de départ -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i>Détails</h5>
                </div>
                <div class="card-body">
                    <p class="detail-label">Nombre de personnes</p>
                    <p class="detail-value"><?php echo e($transaction->person_count ?? 1); ?> personne<?php echo e(($transaction->person_count ?? 1) > 1 ? 's' : ''); ?></p>
                    
                    <p class="detail-label">Prix par nuit</p>
                    <p class="detail-value"><?php echo e(number_format($transaction->room->price, 0, ',', ' ')); ?> CFA</p>
                    
                    <?php if($transaction->late_checkout_fee): ?>
                    <p class="detail-label">Supplément late checkout</p>
                    <p class="detail-value" style="color: var(--amber-600);">+ <?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> CFA</p>
                    <?php endif; ?>
                    
                    
                    <?php if($transaction->expected_checkout_time && $transaction->late_checkout): ?>
                    <p class="detail-label">Heure de départ</p>
                    <p class="detail-value" style="color: var(--amber-600);">
                        <i class="fas fa-clock me-1"></i> <?php echo e($transaction->expected_checkout_time); ?>

                    </p>
                    <?php endif; ?>
                    
                    <p class="detail-label">Créée le</p>
                    <p class="detail-value"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i')); ?></p>
                    
                    <?php if($transaction->user): ?>
                    <p class="detail-label">Créée par</p>
                    <p class="detail-value"><?php echo e($transaction->user->name); ?></p>
                    <?php endif; ?>
                    
                    <?php if($transaction->updated_at != $transaction->created_at): ?>
                    <p class="detail-label">Dernière modification</p>
                    <p class="detail-value"><?php echo e(\Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y à H:i')); ?></p>
                    <?php endif; ?>
                    
                    <?php if($transaction->notes): ?>
                    <div class="divider"></div>
                    <p class="detail-label">Notes</p>
                    <p class="detail-value" style="white-space: pre-line;"><?php echo e($transaction->notes); ?></p>
                    <?php endif; ?>
                    
                    <?php if($transaction->checkout_notes && $transaction->late_checkout): ?>
                    <div class="divider"></div>
                    <p class="detail-label">Notes départ</p>
                    <p class="detail-value" style="white-space: pre-line; color: var(--amber-700);">📝 <?php echo e($transaction->checkout_notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="detail-card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i>Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Nuits</p>
                                <p class="stat-value"><?php echo e($nights); ?></p>
                                <?php if($transaction->late_checkout): ?>
                                    <small class="text-warning">+ late checkout</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Paiements</p>
                                <p class="stat-value"><?php echo e($payments ? $payments->count() : 0); ?></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Total</p>
                                <p class="stat-value stat-value-primary"><?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> CFA</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3">
                                <p class="stat-label">Payé</p>
                                <p class="stat-value stat-value-success"><?php echo e(number_format($totalPayment, 0, ',', ' ')); ?> CFA</p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($remaining > 0): ?>
                    <div class="divider"></div>
                    <div class="text-center">
                        <p class="detail-label mb-1">Reste à payer</p>
                        <p class="stat-value stat-value-danger h4"><?php echo e(number_format($remaining, 0, ',', ' ')); ?> CFA</p>
                        <?php if($transaction->late_checkout_fee > 0): ?>
                            <small class="text-muted">dont <?php echo e(number_format($transaction->late_checkout_fee, 0, ',', ' ')); ?> CFA de late checkout</small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($transaction->status == 'active' && !$transaction->late_checkout): ?>
<div class="modal fade" id="lateCheckoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--amber-500), var(--amber-400)); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>
                    Late checkout - Chambre <?php echo e($transaction->room->number); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('transaction.late-checkout', $transaction)); ?>" method="POST" id="lateCheckoutForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-info mb-4" style="background: var(--blue-50); border-color: var(--blue-200);">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        <strong>Client: <?php echo e($transaction->customer->name); ?></strong><br>
                        Départ normal: <?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?> à 12h00
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    Nouvelle heure de départ
                                </label>
                                <select name="late_checkout_time" class="form-select form-select-lg" id="lateTimeSelect" required>
                                    <option value="">Choisir une heure</option>
                                    <option value="15:00">15h00</option>
                                    <option value="16:00">16h00</option>
                                    <option value="17:00">17h00</option>
                                    <option value="18:00">18h00</option>
                                    <option value="19:00">19h00</option>
                                    <option value="20:00">20h00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                    Méthode de paiement
                                </label>
                                <select name="payment_method" class="form-select form-select-lg" required>
                                    <option value="cash">Espèces</option>
                                    <option value="card">Carte bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="transfer">Virement</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Suggestions de montant</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <?php
                                $prixNuit = $transaction->room->price;
                                $suggestions = [
                                    '0' => 'Gratuit',
                                    round($prixNuit * 0.25) => '25%',
                                    round($prixNuit * 0.5) => '50%',
                                    round($prixNuit * 0.75) => '75%',
                                    $prixNuit => '100% (nuit)',
                                ];
                            ?>
                            
                            <?php $__currentLoopData = $suggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $montant => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn btn-sm btn-outline-primary suggestion-btn" 
                                        data-amount="<?php echo e($montant); ?>">
                                    <?php echo e($label); ?><br>
                                    <small><?php echo e(number_format($montant, 0, ',', ' ')); ?> FCFA</small>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Supplément (FCFA) - <span class="text-primary">LIBRE</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light">FCFA</span>
                            <input type="number" name="late_fee" id="lateFee" class="form-control" 
                                   value="<?php echo e(round($prixNuit * 0.5)); ?>" min="0" step="100" 
                                   style="font-size: 1.2rem; font-weight: 600;" required>
                        </div>
                        <small class="text-muted">
                            Prix nuit: <?php echo e(number_format($prixNuit, 0, ',', ' ')); ?> FCFA
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Raison du late checkout..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-modern btn-warning-modern">
                        <i class="fas fa-check me-2"></i>Confirmer late checkout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('lateTimeSelect')?.addEventListener('change', function() {
    const pricePerNight = <?php echo e($transaction->room->price); ?>;
    const hour = parseInt(this.value.split(':')[0]);
    
    let suggestedPercentage = 0.5;
    if (hour <= 15) suggestedPercentage = 0.4;
    else if (hour <= 17) suggestedPercentage = 0.5;
    else if (hour <= 19) suggestedPercentage = 0.6;
    else if (hour == 20) suggestedPercentage = 0.7;
    
    const suggestedAmount = Math.round(pricePerNight * suggestedPercentage);
    const currentValue = document.getElementById('lateFee').value;
    
    if (currentValue == Math.round(pricePerNight * 0.5)) {
        document.getElementById('lateFee').value = suggestedAmount;
    }
});

// Gestionnaires pour les boutons de suggestion
document.querySelectorAll('.suggestion-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('lateFee').value = this.dataset.amount;
    });
});
</script>
<?php endif; ?>

<!-- Modal d'annulation -->
<?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']) && !in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?>
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-ban text-danger me-2"></i>
                    Annuler la réservation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('transaction.cancel', $transaction)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    <p class="mb-3">Êtes-vous sûr de vouloir annuler cette réservation ?</p>
                    <div class="mb-3">
                        <label class="form-label">Raison (optionnelle)</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Pourquoi annuler ?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn-modern btn-outline-danger-modern">
                        <i class="fas fa-ban me-1"></i>Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Formulaire annulation masqué -->
<form id="cancel-form" method="POST" action="<?php echo e(route('transaction.cancel', 0)); ?>" class="d-none">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <input type="hidden" name="transaction_id" id="cancel-transaction-id-input">
    <input type="hidden" name="cancel_reason" id="cancel-reason-input">
</form>


<script>
function markPaymentAsPaid(paymentId) {
    if (!confirm('Confirmer le paiement de ce supplément ?')) {
        return;
    }
    
    // Afficher un indicateur de chargement
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
    btn.disabled = true;
    
    console.log('Tentative de paiement pour:', paymentId);
    
    fetch(`/payments/${paymentId}/mark-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Réponse:', data);
        
        if (data.success) {
            // Toast de succès
            showToast('success', 'Paiement confirmé !', 'Le paiement a été marqué comme payé avec succès.');
            
            // Recharger après un délai
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Restaurer le bouton
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Afficher l'erreur
            showToast('error', 'Erreur', data.error || data.message || 'Erreur inconnue');
        }
    })
    .catch(error => {
        console.error('Erreur réseau:', error);
        
        // Restaurer le bouton
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        showToast('error', 'Erreur de communication', error.toString());
    });
}

// Fonction pour afficher les toasts
function showToast(type, title, message) {
    // Utiliser SweetAlert2 si disponible
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        // Fallback vers alert
        alert(title + ': ' + message);
    }
}

// Fonction pour vérifier le statut du late checkout
function checkLateCheckoutStatus(transactionId) {
    fetch(`/transaction/${transactionId}/late-checkout-status`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.has_late_checkout) {
                console.log('Statut late checkout:', data.data);
            }
        })
        .catch(error => console.error('Erreur vérification late checkout:', error));
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });
    
    // Gestionnaire pour le changement de statut
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function(e) {
            const newStatus = this.value;
            const oldStatus = this.options[this.selectedIndex].dataset.oldStatus || this.value;
            
            if (newStatus === 'cancelled') {
                if (!confirm('⚠️ Êtes-vous sûr de vouloir annuler cette réservation ?')) {
                    this.value = oldStatus;
                    return false;
                }
            }
            
            if (newStatus === 'no_show') {
                if (!confirm('⚠️ Marquer comme "No Show" ?')) {
                    this.value = oldStatus;
                    return false;
                }
            }
        });
    });
    
    // Vérifier le statut du late checkout au chargement
    const transactionId = <?php echo e($transaction->id); ?>;
    checkLateCheckoutStatus(transactionId);
});

// Rafraîchir la page après une action
<?php if(session('success') || session('error') || session('warning') || session('info')): ?>
    setTimeout(function() {
        location.reload();
    }, 2000);
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/show.blade.php ENDPATH**/ ?>