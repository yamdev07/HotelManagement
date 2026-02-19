

<?php $__env->startSection('title', 'Réservations Client'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-history me-2"></i>
                        Historique des réservations
                    </h1>
                    <p class="text-muted mb-0">
                        Client: <strong><?php echo e($customer->name); ?></strong> | 
                        Email: <?php echo e($customer->email); ?> | 
                        Téléphone: <?php echo e($customer->phone); ?>

                    </p>
                </div>
                <div>
                    <a href="<?php echo e(route('transaction.reservation.pickFromCustomer')); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total réservations</h6>
                            <h3 class="fw-bold text-primary"><?php echo e($reservations->total()); ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Actives</h6>
                            <h3 class="fw-bold text-success">
                                <?php echo e($customer->transactions()->where('status', 'active')->count()); ?>

                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Terminées</h6>
                            <h3 class="fw-bold text-info">
                                <?php echo e($customer->transactions()->where('status', 'completed')->count()); ?>

                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag-checkered fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Annulées</h6>
                            <h3 class="fw-bold text-danger">
                                <?php echo e($customer->transactions()->where('status', 'cancelled')->count()); ?>

                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des réservations -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Historique des réservations</strong>
                        </div>
                        <span class="badge bg-light text-primary">
                            <?php echo e($reservations->total()); ?> réservation(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($reservations->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">ID</th>
                                        <th class="py-3">Chambre</th>
                                        <th class="py-3">Dates</th>
                                        <th class="py-3">Statut</th>
                                        <th class="py-3">Montant</th>
                                        <th class="py-3">Paiement</th>
                                        <th class="py-3">Créé le</th>
                                        <th class="py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-3">
                                            <strong>#<?php echo e($reservation->id); ?></strong>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="room-badge bg-primary me-3">
                                                    <?php echo e($reservation->room->number); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($reservation->room->type->name ?? 'Standard'); ?></div>
                                                    <small class="text-muted">Étage: <?php echo e($reservation->room->floor ?? 'N/A'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold"><?php echo e($reservation->check_in->format('d/m/Y')); ?></div>
                                            <small class="text-muted">au <?php echo e($reservation->check_out->format('d/m/Y')); ?></small>
                                            <div class="mt-1">
                                                <span class="badge bg-info">
                                                    <?php echo e($reservation->check_in->diffInDays($reservation->check_out)); ?> nuit(s)
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-<?php echo e($reservation->status_color); ?> p-2">
                                                <i class="fas fa-<?php echo e($reservation->status_icon); ?> me-1"></i>
                                                <?php echo e($reservation->status_label); ?>

                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold"><?php echo e(number_format($reservation->getTotalPrice(), 0, ',', ' ')); ?> FCFA</div>
                                            <?php if($reservation->down_payment > 0): ?>
                                                <small class="text-muted">
                                                    Acompte: <?php echo e(number_format($reservation->down_payment, 0, ',', ' ')); ?> FCFA
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3">
                                            <?php
                                                $totalPaid = $reservation->getTotalPayment();
                                                $totalPrice = $reservation->getTotalPrice();
                                                $percentage = $totalPrice > 0 ? ($totalPaid / $totalPrice) * 100 : 0;
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-<?php echo e($percentage >= 100 ? 'success' : 'warning'); ?>" 
                                                         style="width: <?php echo e($percentage); ?>%"></div>
                                                </div>
                                                <small class="fw-bold"><?php echo e(round($percentage)); ?>%</small>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <?php echo e(number_format($totalPaid, 0, ',', ' ')); ?> / <?php echo e(number_format($totalPrice, 0, ',', ' ')); ?> FCFA
                                            </small>
                                        </td>
                                        <td class="py-3">
                                            <?php echo e($reservation->created_at->format('d/m/Y H:i')); ?>

                                        </td>
                                        <td class="py-3 text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('transaction.show', $reservation->id)); ?>" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('transaction.invoice', $reservation->id)); ?>" 
                                                   class="btn btn-outline-info">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                                <?php if($reservation->status == 'active'): ?>
                                                <a href="<?php echo e(route('transaction.extend', $reservation->id)); ?>" 
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="text-muted">
                                Affichage de <?php echo e($reservations->firstItem()); ?> à <?php echo e($reservations->lastItem()); ?> 
                                sur <?php echo e($reservations->total()); ?> réservations
                            </div>
                            <div>
                                <?php echo e($reservations->links()); ?>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h4 class="text-dark mb-3">Aucune réservation</h4>
                            <p class="text-muted mb-4">Ce client n'a pas encore effectué de réservation</p>
                            <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>
                                Créer une nouvelle réservation
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .room-badge {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page historique des réservations chargée');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/customer-reservations.blade.php ENDPATH**/ ?>