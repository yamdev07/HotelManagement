
<?php $__env->startSection('title', 'Modifier Réservation'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .date-picker-container {
            position: relative;
        }
        .date-picker-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
        }
        .nights-counter {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-reservation { background-color: #fff3cd; color: #856404; }
        .status-active { background-color: #d1e7dd; color: #0f5132; }
        .status-completed { background-color: #cfe2ff; color: #084298; }
        .status-cancelled { background-color: #e9ecef; color: #495057; }
        .status-no_show { background-color: #6c757d; color: #ffffff; }
        .alert-status {
            border-left: 4px solid;
            padding-left: 15px;
        }
        .alert-status-reservation { border-left-color: #ffc107; }
        .alert-status-active { border-left-color: #198754; }
        .alert-status-completed { border-left-color: #0dcaf0; }
        .alert-status-cancelled { border-left-color: #dc3545; }
        .alert-status-no_show { border-left-color: #6c757d; }
        .time-input-group {
            position: relative;
        }
        .time-input-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
        }
        .time-input-group input {
            padding-left: 40px;
        }
        .datetime-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .datetime-date {
            flex: 2;
        }
        .datetime-time {
            flex: 1;
        }
        @media (max-width: 768px) {
            .datetime-wrapper {
                flex-direction: column;
                gap: 10px;
            }
            .datetime-date, .datetime-time {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('dashboard.index')); ?>">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('transaction.index')); ?>">Réservations</a>
                        </li>
                        <li class="breadcrumb-item active">Modifier Réservation #<?php echo e($transaction->id); ?></li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Modifier la Réservation #<?php echo e($transaction->id); ?>

                    </h2>
                    <a href="<?php echo e(route('transaction.show', $transaction)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux détails
                    </a>
                </div>
                <p class="text-muted">Modifiez les dates, statut et détails de la réservation</p>
            </div>
        </div>

        <!-- Messages de session -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error') || session('failed')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo e(session('error') ?? session('failed')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Avertissement si réservation annulée ou expirée -->
        <?php if($transaction->status == 'cancelled'): ?>
            <div class="alert alert-danger">
                <i class="fas fa-ban me-2"></i>
                Cette réservation est annulée et ne peut pas être modifiée.
                <?php if($transaction->cancelled_at): ?>
                    <br><small>Annulée le : <?php echo e(\Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i')); ?></small>
                    <?php if($transaction->cancel_reason): ?>
                        <br><small>Raison : <?php echo e($transaction->cancel_reason); ?></small>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($transaction->check_out < now() && $transaction->status == 'active'): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Cette réservation est expirée (départ passé). Certaines modifications peuvent être limitées.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informations de la Réservation</h5>
                        <span class="status-badge status-<?php echo e($transaction->status); ?>">
                            <?php echo e($transaction->status_label); ?>

                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Avertissement selon le statut -->
                        <?php if($transaction->status == 'reservation'): ?>
                            <div class="alert alert-warning alert-status alert-status-reservation">
                                <i class="fas fa-calendar-check me-2"></i>
                                <strong>📅 Réservation</strong> - Le client n'est pas encore arrivé à l'hôtel.
                                Arrivée prévue : <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y à H:i')); ?></strong>
                            </div>
                        <?php elseif($transaction->status == 'active'): ?>
                            <div class="alert alert-success alert-status alert-status-active">
                                <i class="fas fa-bed me-2"></i>
                                <strong>🏨 Dans l'hôtel</strong> - Le client est actuellement en séjour.
                                Départ prévu : <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y à H:i')); ?></strong>
                            </div>
                        <?php elseif($transaction->status == 'completed'): ?>
                            <div class="alert alert-info alert-status alert-status-completed">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>✅ Séjour terminé</strong> - Le client est parti, le séjour est terminé.
                            </div>
                        <?php elseif($transaction->status == 'no_show'): ?>
                            <div class="alert alert-secondary alert-status alert-status-no_show">
                                <i class="fas fa-user-slash me-2"></i>
                                <strong>👤 No Show</strong> - Le client ne s'est pas présenté.
                            </div>
                        <?php endif; ?>

                        <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                        <!-- FORMULAIRE PRINCIPAL DE MODIFICATION -->
                        <form method="POST" action="<?php echo e(route('transaction.update', $transaction)); ?>" id="edit-transaction-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <!-- Section Client -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Informations Client
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nom du Client</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e($transaction->customer->name); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e($transaction->customer->phone ?? 'Non renseigné'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e($transaction->customer->email ?? 'Non renseigné'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Historique</label>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo e(route('transaction.reservation.customerReservations', $transaction->customer)); ?>" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-history me-1"></i> Voir ses réservations
                                                </a>
                                                <a href="<?php echo e(route('customer.show', $transaction->customer)); ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Voir profil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Chambre -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-bed me-2"></i>Informations Chambre
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Numéro de Chambre</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" 
                                                       value="Chambre <?php echo e($transaction->room->number); ?>" readonly>
                                                <span class="input-group-text bg-info text-white">
                                                    <i class="fas fa-door-closed"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type de Chambre</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e($transaction->room->type->name ?? 'Standard'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prix par Nuit (CFA)</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e(Helper::formatCFA($transaction->room->price)); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Statut Chambre</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo e($transaction->room->roomStatus->name ?? 'Indisponible'); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Dates (MODIFIABLE) -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Dates de Séjour
                                    <?php if($transaction->status == 'cancelled' || $transaction->status == 'no_show' || $transaction->status == 'completed'): ?>
                                        <small class="text-danger">(Modification limitée)</small>
                                    <?php endif; ?>
                                </h6>
                                
                                <!-- Arrivée -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Date et Heure d'Arrivée *</label>
                                        <div class="datetime-wrapper">
                                            <div class="datetime-date date-picker-container">
                                                <input type="date" 
                                                       class="form-control <?php $__errorArgs = ['check_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="check_in_date" 
                                                       name="check_in_date" 
                                                       value="<?php echo e(old('check_in_date', \Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d'))); ?>"
                                                       <?php if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?> readonly <?php endif; ?>
                                                       required
                                                       min="<?php echo e(now()->format('Y-m-d')); ?>">
                                                <span class="date-picker-icon">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <?php $__errorArgs = ['check_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="datetime-time time-input-group">
                                                <i class="fas fa-clock"></i>
                                                <input type="time" 
                                                       class="form-control <?php $__errorArgs = ['check_in_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="check_in_time" 
                                                       name="check_in_time" 
                                                       value="<?php echo e(old('check_in_time', \Carbon\Carbon::parse($transaction->check_in)->format('H:i'))); ?>"
                                                       <?php if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?> readonly <?php endif; ?>
                                                       required>
                                                <?php $__errorArgs = ['check_in_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <small class="text-muted">Sélectionnez la date et l'heure d'arrivée du client</small>
                                    </div>
                                </div>
                                
                                <!-- Départ -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Date et Heure de Départ *</label>
                                        <div class="datetime-wrapper">
                                            <div class="datetime-date date-picker-container">
                                                <input type="date" 
                                                       class="form-control <?php $__errorArgs = ['check_out_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="check_out_date" 
                                                       name="check_out_date" 
                                                       value="<?php echo e(old('check_out_date', \Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d'))); ?>"
                                                       <?php if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?> readonly <?php endif; ?>
                                                       required
                                                       min="<?php echo e(now()->addDay()->format('Y-m-d')); ?>">
                                                <span class="date-picker-icon">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                <?php $__errorArgs = ['check_out_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="datetime-time time-input-group">
                                                <i class="fas fa-clock"></i>
                                                <input type="time" 
                                                       class="form-control <?php $__errorArgs = ['check_out_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="check_out_time" 
                                                       name="check_out_time" 
                                                       value="<?php echo e(old('check_out_time', \Carbon\Carbon::parse($transaction->check_out)->format('H:i'))); ?>"
                                                       <?php if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])): ?> readonly <?php endif; ?>
                                                       required>
                                                <?php $__errorArgs = ['check_out_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <small class="text-muted">Sélectionnez la date et l'heure de départ du client</small>
                                    </div>
                                </div>

                                <!-- Calcul des nuits -->
                                <div class="nights-counter mt-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Nuits :</strong></p>
                                            <div id="nights-count" class="h4 text-primary">0</div>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Prix/Nuit :</strong></p>
                                            <div class="h5 text-info"><?php echo e(Helper::formatCFA($transaction->room->price)); ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Nouveau Total :</strong></p>
                                            <div id="new-total" class="h4 text-success">0 CFA</div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Ancien total :</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?>

                                        <br>
                                        <strong>Déjà payé :</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPayment())); ?>

                                        <br>
                                        <strong>Différence :</strong> <span id="price-difference" class="fw-bold">0 CFA</span>
                                    </div>
                                </div>

                                <!-- Vérification de disponibilité -->
                                <?php if(in_array($transaction->status, ['reservation', 'active'])): ?>
                                <div class="mt-3">
                                    <button type="button" id="check-availability-btn" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-search me-2"></i>Vérifier disponibilité des nouvelles dates
                                    </button>
                                    <div id="availability-result" class="mt-2"></div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Section Statut -->
                            <?php if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-exchange-alt me-2"></i>Modifier le Statut
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Statut de la réservation</label>
                                            <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php $__currentLoopData = [
                                                    'reservation' => '📅 Réservation (pas encore arrivé)',
                                                    'active' => '🏨 Dans l\'hôtel (séjour en cours)',
                                                    'completed' => '✅ Séjour terminé (est parti)',
                                                    'cancelled' => '❌ Annulée',
                                                    'no_show' => '👤 No Show (pas venu)'
                                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($value); ?>" 
                                                            <?php echo e(old('status', $transaction->status) == $value ? 'selected' : ''); ?>

                                                            data-desc="<?php echo e([
                                                                'reservation' => 'Client pas encore arrivé',
                                                                'active' => 'Client dans l\'hôtel',
                                                                'completed' => 'Client parti, séjour terminé',
                                                                'cancelled' => 'Réservation annulée',
                                                                'no_show' => 'Client ne s\'est pas présenté'
                                                            ][$value]); ?>">
                                                        <?php echo e($label); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="form-text" id="status-description">
                                                <?php echo e([
                                                    'reservation' => 'Client pas encore arrivé',
                                                    'active' => 'Client dans l\'hôtel',
                                                    'completed' => 'Client parti, séjour terminé',
                                                    'cancelled' => 'Réservation annulée',
                                                    'no_show' => 'Client ne s\'est pas présenté'
                                                ][$transaction->status]); ?>

                                            </div>
                                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Champ raison d'annulation (conditionnel) -->
                                <div id="cancel-reason-field" style="display: none;">
                                    <div class="mb-3">
                                        <label for="cancel_reason" class="form-label">Raison de l'annulation</label>
                                        <textarea class="form-control <?php $__errorArgs = ['cancel_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                  id="cancel_reason" 
                                                  name="cancel_reason" 
                                                  rows="2"
                                                  placeholder="Pourquoi annuler cette réservation ? (optionnel)"><?php echo e(old('cancel_reason', $transaction->cancel_reason)); ?></textarea>
                                        <?php $__errorArgs = ['cancel_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes supplémentaires</label>
                                <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Ajoutez des notes ou instructions spéciales..."><?php echo e(old('notes', $transaction->notes)); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Ces notes seront ajoutées à l'historique de la réservation</small>
                            </div>

                            <!-- Champs cachés pour les dates combinées -->
                            <input type="hidden" id="check_in" name="check_in" value="<?php echo e(old('check_in', $transaction->check_in->format('Y-m-d\TH:i'))); ?>">
                            <input type="hidden" id="check_out" name="check_out" value="<?php echo e(old('check_out', $transaction->check_out->format('Y-m-d\TH:i'))); ?>">

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-4">
                                <div>
                                    <button type="button" class="btn btn-outline-secondary" onclick="confirmCancel()">
                                        <i class="fas fa-times me-2"></i>Annuler les modifications
                                    </button>
                                    
                                    <?php if(in_array($transaction->status, ['reservation', 'active']) && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                                        <!-- Bouton pour ouvrir le modal d'annulation -->
                                        <button type="button" class="btn btn-outline-danger ms-2" onclick="showCancelReasonModal()">
                                            <i class="fas fa-ban me-2"></i>Annuler Réservation
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="save-button">
                                        <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </form> <!-- Fermeture du formulaire principal -->

                        <!-- FORMULAIRE D'ANNULATION CACHÉ (SÉPARÉ) -->
                        <?php if(in_array($transaction->status, ['reservation', 'active']) && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                            <form action="<?php echo e(route('transaction.cancel', $transaction)); ?>" 
                                  method="POST" 
                                  id="cancel-form"
                                  style="display: none;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="cancel_reason" id="hidden_cancel_reason">
                            </form>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous n'avez pas les permissions nécessaires pour modifier cette réservation.
                            Seuls les administrateurs et le personnel de réception peuvent modifier les réservations.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Informations -->
            <div class="col-lg-4">
                <!-- Résumé de la Réservation -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Résumé</h5>
                        <span class="badge bg-primary">#<?php echo e($transaction->id); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user me-2 text-muted"></i>Client</span>
                                <strong><?php echo e($transaction->customer->name); ?></strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-bed me-2 text-muted"></i>Chambre</span>
                                <strong>Chambre <?php echo e($transaction->room->number); ?></strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar me-2 text-muted"></i>Arrivée</span>
                                <div class="text-end">
                                    <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y')); ?></strong><br>
                                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('H:i')); ?></small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar me-2 text-muted"></i>Départ</span>
                                <div class="text-end">
                                    <strong><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y')); ?></strong><br>
                                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('H:i')); ?></small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-moon me-2 text-muted"></i>Nuits actuelles</span>
                                <?php
                                    $currentNights = \Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out);
                                ?>
                                <strong><?php echo e($currentNights); ?> nuit<?php echo e($currentNights > 1 ? 's' : ''); ?></strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-money-bill me-2 text-muted"></i>Total actuel</span>
                                <strong><?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?></strong>
                            </div>
                            <div class="list-group-item">
                                <span class="d-block mb-2"><i class="fas fa-chart-line me-2 text-muted"></i>Statut Actuel</span>
                                <span class="status-badge status-<?php echo e($transaction->status); ?>">
                                    <?php echo e($transaction->status_label); ?>

                                </span>
                                <?php if($transaction->cancelled_at): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Annulée le : <?php echo e(\Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i')); ?>

                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- Actions selon le statut -->
                            <?php if($transaction->status == 'reservation' && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                                <form action="<?php echo e(route('transaction.mark-arrived', $transaction)); ?>" method="POST" class="d-grid">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success mb-2">
                                        <i class="fas fa-sign-in-alt me-2"></i>Marquer comme arrivé
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if($transaction->status == 'active' && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception'])): ?>
                                <form action="<?php echo e(route('transaction.mark-departed', $transaction)); ?>" method="POST" class="d-grid">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-info mb-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Marquer comme parti
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if(!in_array($transaction->status, ['cancelled', 'no_show', 'completed']) && $transaction->getTotalPayment() < $transaction->getTotalPrice()): ?>
                            <a href="<?php echo e(route('transaction.payment.create', $transaction)); ?>" 
                               class="btn btn-outline-success mb-2">
                                <i class="fas fa-credit-card me-2"></i>Ajouter un Paiement
                            </a>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('transaction.invoice', $transaction)); ?>" 
                               class="btn btn-outline-primary mb-2">
                                <i class="fas fa-file-invoice me-2"></i>Voir Facture
                            </a>
                            
                            <a href="<?php echo e(route('customer.show', $transaction->customer)); ?>" 
                               class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>Voir Profil Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Historique -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Historique</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <small class="text-muted">Créée le</small><br>
                                <strong><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i')); ?></strong>
                            </li>
                            <li class="mb-2">
                                <small class="text-muted">Dernière modification</small><br>
                                <strong><?php echo e(\Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y H:i')); ?></strong>
                            </li>
                            <?php if($transaction->cancelled_at): ?>
                            <li class="mb-2">
                                <small class="text-muted">Annulée le</small><br>
                                <strong><?php echo e(\Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i')); ?></strong>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <a href="<?php echo e(route('transaction.history', $transaction)); ?>" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-history me-1"></i> Voir l'historique complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la raison d'annulation -->
    <div class="modal fade" id="cancelReasonModal" tabindex="-1" aria-labelledby="cancelReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="cancelReasonModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirmer l'annulation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible.</p>
                    <div class="mb-3">
                        <label for="modal_cancel_reason" class="form-label">Raison de l'annulation (optionnel)</label>
                        <textarea class="form-control" id="modal_cancel_reason" rows="3" 
                                  placeholder="Pourquoi annuler cette réservation ?"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        L'annulation libérera la chambre et créera un remboursement si des paiements ont été effectués.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitCancelForm()">
                        <i class="fas fa-ban me-2"></i>Confirmer l'annulation
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ VARIABLES GLOBALES ============
    const checkInDateInput = document.getElementById('check_in_date');
    const checkInTimeInput = document.getElementById('check_in_time');
    const checkOutDateInput = document.getElementById('check_out_date');
    const checkOutTimeInput = document.getElementById('check_out_time');
    const checkInHiddenInput = document.getElementById('check_in');
    const checkOutHiddenInput = document.getElementById('check_out');
    const nightsCount = document.getElementById('nights-count');
    const newTotal = document.getElementById('new-total');
    const priceDifference = document.getElementById('price-difference');
    const statusSelect = document.getElementById('status');
    const statusDescription = document.getElementById('status-description');
    const cancelReasonField = document.getElementById('cancel-reason-field');
    const cancelReasonTextarea = document.getElementById('cancel_reason');
    const saveButton = document.getElementById('save-button');
    const transactionId = <?php echo e($transaction->id); ?>;
    const originalStatus = "<?php echo e($transaction->status); ?>";
    const roomPricePerNight = <?php echo e($transaction->room->price); ?>;
    const originalTotalPrice = <?php echo e($transaction->getTotalPrice()); ?>;
    
    // ============ FONCTIONS UTILITAIRES ============
    
    /**
     * Combiner date et heure en format ISO
     */
    function combineDateTime(dateInput, timeInput) {
        const date = dateInput.value;
        const time = timeInput.value || '00:00';
        if (date) {
            return `${date}T${time}`;
        }
        return null;
    }
    
    /**
     * Formater le prix en CFA
     */
    function formatCFA(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' CFA';
    }
    
    /**
     * Calculer les nuits et le total
     */
    function calculateNightsAndTotal() {
        const checkInDateTime = combineDateTime(checkInDateInput, checkInTimeInput);
        const checkOutDateTime = combineDateTime(checkOutDateInput, checkOutTimeInput);
        
        // Mettre à jour les champs cachés
        if (checkInDateTime) {
            checkInHiddenInput.value = checkInDateTime;
        }
        if (checkOutDateTime) {
            checkOutHiddenInput.value = checkOutDateTime;
        }
        
        if (checkInDateTime && checkOutDateTime) {
            const checkIn = new Date(checkInDateTime);
            const checkOut = new Date(checkOutDateTime);
            
            if (checkOut > checkIn) {
                // Calculer la différence en jours (arrondi supérieur)
                const timeDiff = checkOut.getTime() - checkIn.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                nightsCount.textContent = nights;
                const total = nights * roomPricePerNight;
                newTotal.textContent = formatCFA(total);
                
                // Calculer la différence de prix
                const difference = total - originalTotalPrice;
                const differenceElement = document.getElementById('price-difference');
                
                if (difference > 0) {
                    differenceElement.textContent = '+' + formatCFA(difference);
                    differenceElement.className = 'fw-bold text-danger';
                } else if (difference < 0) {
                    differenceElement.textContent = formatCFA(difference);
                    differenceElement.className = 'fw-bold text-success';
                } else {
                    differenceElement.textContent = formatCFA(0);
                    differenceElement.className = 'fw-bold text-muted';
                }
                
                // Validation : départ doit être après arrivée
                if (checkOut <= checkIn) {
                    checkOutDateInput.setCustomValidity('La date de départ doit être après la date d\'arrivée');
                } else {
                    checkOutDateInput.setCustomValidity('');
                }
            } else {
                nightsCount.textContent = '0';
                newTotal.textContent = formatCFA(0);
                checkOutDateInput.setCustomValidity('La date de départ doit être après la date d\'arrivée');
            }
        } else {
            nightsCount.textContent = '0';
            newTotal.textContent = formatCFA(0);
        }
    }
    
    /**
     * Gérer le champ raison d'annulation
     */
    function toggleCancelReasonField() {
        if (statusSelect.value === 'cancelled') {
            cancelReasonField.style.display = 'block';
            if (!cancelReasonTextarea.value) {
                cancelReasonTextarea.value = "Annulation depuis l'interface d'édition";
            }
        } else {
            cancelReasonField.style.display = 'none';
            cancelReasonTextarea.value = '';
        }
    }
    
    /**
     * Mettre à jour la description du statut
     */
    function updateStatusDescription() {
        const selectedOption = statusSelect.options[statusSelect.selectedIndex];
        const description = selectedOption.getAttribute('data-desc');
        statusDescription.textContent = description;
        toggleCancelReasonField();
    }
    
    /**
     * Vérifier la disponibilité des nouvelles dates
     */
    async function checkAvailability() {
        const checkIn = checkInHiddenInput.value;
        const checkOut = checkOutHiddenInput.value;
        
        if (!checkIn || !checkOut) {
            Swal.fire({
                icon: 'warning',
                title: 'Dates incomplètes',
                text: 'Veuillez sélectionner les deux dates et heures'
            });
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            Swal.fire({
                icon: 'error',
                title: 'Dates invalides',
                text: 'La date de départ doit être après la date d\'arrivée'
            });
            return;
        }
        
        try {
            Swal.fire({
                title: 'Vérification en cours...',
                text: 'Vérification de la disponibilité de la chambre',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch(`/transactions/${transactionId}/check-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    check_in: checkIn,
                    check_out: checkOut,
                    transaction_id: transactionId
                })
            });
            
            const data = await response.json();
            
            Swal.close();
            
            const resultDiv = document.getElementById('availability-result');
            if (data.available) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Disponible !</strong> ${data.message}<br>
                        <small>La chambre est libre pour les dates sélectionnées.</small>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Non disponible !</strong> ${data.message}<br>
                        <small>La chambre est déjà réservée pour tout ou partie de cette période.</small>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erreur:', error);
            Swal.close();
            document.getElementById('availability-result').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreur de connexion</strong><br>
                    <small>Impossible de vérifier la disponibilité. Veuillez réessayer.</small>
                </div>
            `;
        }
    }
    
    // ============ ÉVÉNEMENTS ============
    
    // Écouter les changements de dates et heures
    checkInDateInput.addEventListener('change', calculateNightsAndTotal);
    checkInTimeInput.addEventListener('change', calculateNightsAndTotal);
    checkOutDateInput.addEventListener('change', calculateNightsAndTotal);
    checkOutTimeInput.addEventListener('change', calculateNightsAndTotal);
    
    // Écouter le changement de statut
    statusSelect.addEventListener('change', updateStatusDescription);
    
    // Bouton vérification disponibilité
    const checkAvailabilityBtn = document.getElementById('check-availability-btn');
    if (checkAvailabilityBtn) {
        checkAvailabilityBtn.addEventListener('click', checkAvailability);
    }
    
    // Définir la date minimale pour le départ (jour suivant l'arrivée)
    checkInDateInput.addEventListener('change', function() {
        if (this.disabled) return;
        
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        // Formater en YYYY-MM-DD pour l'attribut min
        const minDate = nextDay.toISOString().split('T')[0];
        checkOutDateInput.min = minDate;
        
        // Si la date de départ actuelle est antérieure au nouveau minimum
        if (checkOutDateInput.value && new Date(checkOutDateInput.value) < nextDay) {
            checkOutDateInput.value = minDate;
            calculateNightsAndTotal();
        }
    });
    
    // ============ FONCTIONS GLOBALES (window) ============
    
    /**
     * Confirmer l'annulation des modifications
     */
    window.confirmCancel = function() {
        Swal.fire({
            title: 'Annuler les modifications ?',
            text: 'Toutes les modifications non enregistrées seront perdues.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-check me-2"></i>Oui, annuler',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Non, rester'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo e(route('transaction.show', $transaction)); ?>";
            }
        });
    };
    
    /**
     * Afficher le modal d'annulation
     */
    window.showCancelReasonModal = function() {
        const modal = new bootstrap.Modal(document.getElementById('cancelReasonModal'));
        modal.show();
    };
    
    /**
     * Soumettre le formulaire d'annulation
     */
    window.submitCancelForm = function() {
        const reason = document.getElementById('modal_cancel_reason').value;
        const cancelForm = document.getElementById('cancel-form');
        
        if (cancelForm) {
            // Mettre la raison dans le champ caché
            document.getElementById('hidden_cancel_reason').value = 
                reason || "Annulation depuis l'interface d'édition";
            
            // Soumettre le formulaire
            cancelForm.submit();
        } else {
            console.error('Formulaire d\'annulation non trouvé');
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Formulaire d\'annulation non disponible'
            });
        }
    };
    
    // ============ VALIDATION DU FORMULAIRE PRINCIPAL ============
    
    document.getElementById('edit-transaction-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Combiner les dates avant validation
        const checkIn = combineDateTime(checkInDateInput, checkInTimeInput);
        const checkOut = combineDateTime(checkOutDateInput, checkOutTimeInput);
        const newStatus = statusSelect.value;
        
        // Vérification dates
        if (!checkIn || !checkOut) {
            Swal.fire({
                icon: 'error',
                title: 'Dates incomplètes',
                text: 'Veuillez remplir toutes les dates et heures'
            });
            return false;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            Swal.fire({
                icon: 'error',
                title: 'Dates invalides',
                text: 'La date de départ doit être après la date d\'arrivée'
            });
            checkOutDateInput.focus();
            return false;
        }
        
        // Vérification statut annulation
        if (newStatus === 'cancelled') {
            Swal.fire({
                title: 'Confirmer l\'annulation ?',
                text: 'Cette action est irréversible. La réservation sera annulée.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-ban me-2"></i>Oui, annuler',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Non, garder',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Désactiver le bouton pour éviter double soumission
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                    e.target.submit();
                }
            });
            return false;
        }
        
        // Vérification statut no show
        if (newStatus === 'no_show') {
            Swal.fire({
                title: 'Marquer comme No Show ?',
                text: 'Le client ne s\'est pas présenté. Cette action est irréversible.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-user-slash me-2"></i>Oui, No Show',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                    e.target.submit();
                }
            });
            return false;
        }
        
        // Vérifier si des modifications ont été faites
        const originalCheckIn = "<?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d')); ?>";
        const originalCheckOut = "<?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d')); ?>";
        const originalCheckInTime = "<?php echo e(\Carbon\Carbon::parse($transaction->check_in)->format('H:i')); ?>";
        const originalCheckOutTime = "<?php echo e(\Carbon\Carbon::parse($transaction->check_out)->format('H:i')); ?>";
        const originalNotes = "<?php echo e(addslashes($transaction->notes) ?? ''); ?>";
        const currentNotes = document.getElementById('notes').value;
        
        if (checkInDateInput.value === originalCheckIn && 
            checkInTimeInput.value === originalCheckInTime &&
            checkOutDateInput.value === originalCheckOut && 
            checkOutTimeInput.value === originalCheckOutTime &&
            newStatus === originalStatus && 
            currentNotes === originalNotes.replace(/\\/g, '')) {
            
            Swal.fire({
                title: 'Aucune modification',
                text: 'Aucune modification n\'a été détectée. Souhaitez-vous quand même continuer ?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check me-2"></i>Oui, continuer',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Non, annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                    e.target.submit();
                }
            });
            return false;
        }
        
        // Confirmation standard pour les modifications
        Swal.fire({
            title: 'Enregistrer les modifications ?',
            html: `
                <div class="text-start">
                    <p>Voulez-vous enregistrer les modifications suivantes ?</p>
                    <div class="alert alert-info">
                        <strong>Arrivée :</strong> ${checkInDateInput.value} à ${checkInTimeInput.value}<br>
                        <strong>Départ :</strong> ${checkOutDateInput.value} à ${checkOutTimeInput.value}<br>
                        <strong>Nuits :</strong> ${nightsCount.textContent}<br>
                        <strong>Nouveau total :</strong> ${newTotal.textContent}
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-2"></i>Oui, enregistrer',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
                e.target.submit();
            }
        });
        
        return false;
    });
    
    // ============ INITIALISATION ============
    
    // Calculer au chargement
    calculateNightsAndTotal();
    updateStatusDescription();
    
    // Initialiser les dates min
    if (checkInDateInput.value) {
        const checkInDate = new Date(checkInDateInput.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        const minDate = nextDay.toISOString().split('T')[0];
        checkOutDateInput.min = minDate;
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/edit.blade.php ENDPATH**/ ?>