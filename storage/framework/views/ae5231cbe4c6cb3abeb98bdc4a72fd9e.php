
<?php $__env->startSection('title', 'Check-in - Réservation'); ?>
<?php $__env->startSection('content'); ?>
    <style>
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #0d6efd;
        }
        .info-box {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .room-status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .room-available { background-color: #28a745; }
        .room-occupied { background-color: #dc3545; }
        .room-maintenance { background-color: #ffc107; }
        .room-cleaning { background-color: #17a2b8; }
        .room-dirty { background-color: #ffc107; animation: pulse 2s infinite; }
        
        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .alternative-room {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .alternative-room:hover {
            border-color: #0d6efd;
            background-color: #f0f8ff;
        }
        .alternative-room.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .alternative-room.dirty {
            border-color: #ffc107;
            background-color: #fff3cd;
        }
        .alternative-room.dirty:hover {
            border-color: #fd7e14;
            background-color: #ffe69c;
        }
        .alternative-room.dirty.selected {
            border-color: #fd7e14;
            background-color: #fff3cd;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .status-badge.clean {
            background-color: #28a745;
            color: white;
        }
        .status-badge.dirty {
            background-color: #ffc107;
            color: #856404;
        }
        .status-badge.occupied {
            background-color: #dc3545;
            color: white;
        }
        .price-difference {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .price-positive { background-color: #ffe6e6; color: #dc3545; }
        .price-negative { background-color: #e6ffe6; color: #28a745; }
        .price-neutral { background-color: #f8f9fa; color: #6c757d; }
        .form-stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 10px;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }
        .step.active::after {
            background-color: #0d6efd;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }
        .step.active .step-number {
            background-color: #0d6efd;
            color: white;
        }
        .step.completed .step-number {
            background-color: #28a745;
            color: white;
        }
        .step-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .step.active .step-label {
            color: #0d6efd;
            font-weight: bold;
        }
        .form-tab {
            display: none;
        }
        .form-tab.active {
            display: block;
        }
        .urgent-cleaning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            animation: pulse-bg 2s infinite;
        }
        @keyframes pulse-bg {
            0% { background-color: #fff3cd; }
            50% { background-color: #ffe69c; }
            100% { background-color: #fff3cd; }
        }
        .blocked-checkin {
            text-align: center;
            padding: 40px 20px;
        }
        .blocked-checkin i {
            font-size: 5rem;
            color: #ffc107;
            margin-bottom: 20px;
        }
        .blocked-checkin h3 {
            margin-bottom: 15px;
            color: #856404;
        }
        .blocked-checkin p {
            color: #6c757d;
            max-width: 500px;
            margin: 0 auto 25px;
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
                            <a href="<?php echo e(route('checkin.index')); ?>">Check-in</a>
                        </li>
                        <li class="breadcrumb-item active">Check-in Réservation #<?php echo e($transaction->id); ?></li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        Check-in Réservation #<?php echo e($transaction->id); ?>

                    </h2>
                    <a href="<?php echo e(route('checkin.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Enregistrez l'arrivée du client et complétez les informations</p>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo session('success'); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- 🔴 ALERTE SI CHECK-IN BLOQUÉ -->
        <?php if(isset($canCheckIn) && !$canCheckIn): ?>
            <div class="alert alert-warning alert-dismissible fade show urgent-cleaning" role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-broom fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading fw-bold mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Check-in temporairement impossible
                        </h5>
                        <p class="mb-2"><?php echo e($checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage()); ?></p>
                        
                        <?php if(isset($isUrgentCleaning) && $isUrgentCleaning): ?>
                            <div class="mt-2">
                                <button class="btn btn-warning" onclick="notifyHousekeeping(<?php echo e($transaction->room->id); ?>)">
                                    <i class="fas fa-bell me-2"></i>
                                    Notifier le housekeeping en urgence
                                </button>
                            </div>
                            <div class="mt-2 small">
                                <i class="fas fa-clock me-1"></i>
                                Client attendu à <?php echo e($transaction->check_in->format('H:i')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- 🔴 ALERTE SI CHAMBRE SALE MAIS RÉSERVABLE -->
        <?php if(isset($isAvailableForBooking) && $isAvailableForBooking && !($canCheckIn ?? true)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note :</strong> Cette chambre est réservable mais nécessite un nettoyage avant le check-in.
                <?php if($isUrgentCleaning ?? false): ?>
                    <span class="badge bg-warning ms-2">URGENT - Arrivée aujourd'hui</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- 🔴 SI CHECK-IN BLOQUÉ, AFFICHER UNE VERSION SIMPLIFIÉE -->
        <?php if(isset($canCheckIn) && !$canCheckIn): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-clock me-2"></i>
                                En attente de nettoyage
                            </h5>
                        </div>
                        <div class="card-body blocked-checkin">
                            <i class="fas fa-broom"></i>
                            <h3>La chambre est en cours de nettoyage</h3>
                            <p><?php echo e($checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage()); ?></p>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="alert alert-light border text-start">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Informations client :</h6>
                                                <p class="mb-1"><strong><?php echo e($transaction->customer->name); ?></strong></p>
                                                <p class="mb-1"><?php echo e($transaction->customer->phone); ?></p>
                                                <p class="mb-0"><?php echo e($transaction->customer->email ?? 'Email non renseigné'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Détails réservation :</h6>
                                                <p class="mb-1">Chambre <?php echo e($transaction->room->number); ?></p>
                                                <p class="mb-1">Arrivée: <?php echo e($transaction->check_in->format('d/m/Y H:i')); ?></p>
                                                <p class="mb-0">Départ: <?php echo e($transaction->check_out->format('d/m/Y H:i')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="<?php echo e(route('checkin.index')); ?>" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                </a>
                                <?php if($isUrgentCleaning ?? false): ?>
                                    <button class="btn btn-warning btn-lg ms-2" onclick="notifyHousekeeping(<?php echo e($transaction->room->id); ?>)">
                                        <i class="fas fa-bell me-2"></i>Notifier housekeeping
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                            <?php if(!$alternativeRooms->isEmpty()): ?>
                                <hr class="my-4">
                                <h5 class="mb-3">Chambres alternatives disponibles :</h5>
                                <div class="row">
                                    <?php $__currentLoopData = $alternativeRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $altRoom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">Chambre <?php echo e($altRoom->number); ?></h6>
                                                    <p class="small mb-1"><?php echo e($altRoom->type->name ?? 'Standard'); ?></p>
                                                    <p class="small mb-2"><?php echo e(Helper::formatCFA($altRoom->price)); ?>/nuit</p>
                                                    <?php if($altRoom->room_status_id == 6): ?>
                                                        <span class="badge bg-warning">À nettoyer</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Prête</span>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-outline-primary mt-2 w-100" 
                                                            onclick="selectAlternativeRoomFromBlocked(<?php echo e($altRoom->id); ?>)">
                                                        Sélectionner
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- SI CHECK-IN POSSIBLE, AFFICHER LE FORMULAIRE COMPLET -->
            
            <!-- Stepper -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-stepper">
                        <div class="step active" id="step-1">
                            <div class="step-number">1</div>
                            <div class="step-label">Vérification</div>
                        </div>
                        <div class="step" id="step-2">
                            <div class="step-number">2</div>
                            <div class="step-label">Informations</div>
                        </div>
                        <div class="step" id="step-3">
                            <div class="step-number">3</div>
                            <div class="step-label">Chambre</div>
                        </div>
                        <div class="step" id="step-4">
                            <div class="step-number">4</div>
                            <div class="step-label">Confirmation</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <form method="POST" action="<?php echo e(route('checkin.store', $transaction)); ?>" id="checkin-form">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Étape 1: Vérification (MODIFIÉE) -->
                        <div class="form-tab active" id="tab-1">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Vérification de la Réservation</h5>
                                </div>
                                <div class="card-body">
                                    <!-- 🔴 Statut de la chambre -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="info-box d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6><i class="fas fa-bed me-2"></i>Statut de la chambre</h6>
                                                    <p class="mb-0">
                                                        <span class="badge bg-<?php echo e($transaction->room->status_color); ?> fs-6">
                                                            <i class="fas <?php echo e($transaction->room->status_icon); ?> me-1"></i>
                                                            <?php echo e($transaction->room->status_label); ?>

                                                        </span>
                                                    </p>
                                                </div>
                                                <div>
                                                    <?php if($transaction->room->room_status_id == 1): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Prête pour check-in
                                                        </span>
                                                    <?php elseif($transaction->room->room_status_id == 6): ?>
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-broom me-1"></i>À nettoyer
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Vérifiez les informations de la réservation avant de procéder au check-in.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-user me-2"></i>Client</h6>
                                                <p class="mb-1"><strong><?php echo e($transaction->customer->name); ?></strong></p>
                                                <p class="mb-1 text-muted small"><?php echo e($transaction->customer->phone); ?></p>
                                                <p class="mb-0 text-muted small"><?php echo e($transaction->customer->email ?? 'Email non renseigné'); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-bed me-2"></i>Chambre Réservée</h6>
                                                <p class="mb-1"><strong>Chambre <?php echo e($transaction->room->number); ?></strong></p>
                                                <p class="mb-1 text-muted small"><?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?></p>
                                                <p class="mb-0 text-muted small"><?php echo e($transaction->room->capacity); ?> personnes • <?php echo e(Helper::formatCFA($transaction->room->price)); ?>/nuit</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>Dates</h6>
                                                <p class="mb-1"><strong>Arrivée:</strong> <?php echo e($transaction->check_in->format('d/m/Y H:i')); ?></p>
                                                <p class="mb-1"><strong>Départ:</strong> <?php echo e($transaction->check_out->format('d/m/Y H:i')); ?></p>
                                                <p class="mb-0"><strong>Durée:</strong> <?php echo e($transaction->nights); ?> nuit(s)</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-money-bill-wave me-2"></i>Paiement</h6>
                                                <p class="mb-1"><strong>Total:</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?></p>
                                                <p class="mb-1"><strong>Payé:</strong> <?php echo e(Helper::formatCFA($transaction->getTotalPayment())); ?></p>
                                                <p class="mb-0">
                                                    <strong>Solde:</strong> 
                                                    <span class="<?php echo e($transaction->getRemainingPayment() > 0 ? 'text-warning' : 'text-success'); ?>">
                                                        <?php echo e(Helper::formatCFA($transaction->getRemainingPayment())); ?>

                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 text-center">
                                        <?php if($isRoomAvailable): ?>
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                La chambre réservée est disponible pour le séjour.
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                                <i class="fas fa-arrow-right me-2"></i>Continuer
                                            </button>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                La chambre réservée n'est pas disponible pour le séjour.
                                                Vous devrez sélectionner une autre chambre.
                                            </div>
                                            <button type="button" class="btn btn-warning" onclick="nextStep(2)">
                                                <i class="fas fa-arrow-right me-2"></i>Sélectionner une autre chambre
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 2: Informations -->
                        <div class="form-tab" id="tab-2">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Informations Complémentaires</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-section">
                                        <h6><i class="fas fa-users me-2"></i>Occupants</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="adults" class="form-label">Adultes *</label>
                                                <input type="number" class="form-control <?php $__errorArgs = ['adults'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="adults" name="adults" 
                                                       value="<?php echo e(old('adults', $transaction->person_count ?? 1)); ?>" 
                                                       min="1" max="10" required>
                                                <?php $__errorArgs = ['adults'];
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
                                            <div class="col-md-6 mb-3">
                                                <label for="children" class="form-label">Enfants (0-12 ans)</label>
                                                <input type="number" class="form-control <?php $__errorArgs = ['children'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="children" name="children" 
                                                       value="<?php echo e(old('children', 0)); ?>" 
                                                       min="0" max="10">
                                                <?php $__errorArgs = ['children'];
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
                                    
                                    <div class="form-section">
                                        <h6><i class="fas fa-id-card me-2"></i>Pièce d'Identité</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="id_type" class="form-label">Type de pièce *</label>
                                                <select class="form-control <?php $__errorArgs = ['id_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                        id="id_type" name="id_type" required>
                                                    <option value="">Sélectionnez...</option>
                                                    <?php $__currentLoopData = $idTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($value); ?>" 
                                                                <?php echo e(old('id_type') == $value ? 'selected' : ''); ?>>
                                                            <?php echo e($label); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php $__errorArgs = ['id_type'];
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
                                            <div class="col-md-6 mb-3">
                                                <label for="id_number" class="form-label">Numéro de pièce *</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="id_number" name="id_number" 
                                                       value="<?php echo e(old('id_number')); ?>" 
                                                       placeholder="Ex: AB123456" required>
                                                <?php $__errorArgs = ['id_number'];
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
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nationality" class="form-label">Nationalité *</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="nationality" name="nationality" 
                                                       value="<?php echo e(old('nationality')); ?>" 
                                                       placeholder="Ex: Française" required>
                                                <?php $__errorArgs = ['nationality'];
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
                                    
                                    <div class="form-section">
                                        <h6><i class="fas fa-comment-alt me-2"></i>Autres Informations</h6>
                                        <div class="mb-3">
                                            <label for="special_requests" class="form-label">Demandes Spéciales</label>
                                            <textarea class="form-control <?php $__errorArgs = ['special_requests'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="special_requests" name="special_requests" 
                                                      rows="3" placeholder="Préférences alimentaires, accessibilité, autres..."><?php echo e(old('special_requests')); ?></textarea>
                                            <?php $__errorArgs = ['special_requests'];
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
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes Internes</label>
                                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="notes" name="notes" 
                                                      rows="2" placeholder="Notes pour le personnel..."><?php echo e(old('notes')); ?></textarea>
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
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                            <i class="fas fa-arrow-left me-2"></i>Retour
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                            <i class="fas fa-arrow-right me-2"></i>Continuer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 3: Chambre (MODIFIÉE) -->
                        <div class="form-tab" id="tab-3">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Sélection de la Chambre</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <?php if($isRoomAvailable): ?>
                                            La chambre réservée est disponible. Vous pouvez conserver cette chambre ou en sélectionner une autre.
                                        <?php else: ?>
                                            La chambre réservée n'est pas disponible. Veuillez sélectionner une chambre alternative.
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- 🔴 Statut de la chambre originale -->
                                    <?php if($transaction->room->room_status_id == 6): ?>
                                        <div class="alert alert-warning mb-3">
                                            <i class="fas fa-broom me-2"></i>
                                            <strong>Attention :</strong> La chambre réservée (Chambre <?php echo e($transaction->room->number); ?>) est actuellement sale.
                                            Le check-in ne sera possible qu'après nettoyage.
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Option 1: Conserver la chambre originale -->
                                    <?php if($isRoomAvailable): ?>
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="radio" name="room_option" 
                                                   id="keep_original" value="keep" checked 
                                                   onchange="toggleRoomOptions('keep')">
                                            <label class="form-check-label" for="keep_original">
                                                <h6 class="mb-1">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    Conserver la chambre originale
                                                </h6>
                                                <div class="ms-4">
                                                    <p class="mb-1">
                                                        <strong>Chambre <?php echo e($transaction->room->number); ?></strong>
                                                        <?php if($transaction->room->room_status_id == 6): ?>
                                                            <span class="badge bg-warning ms-2">À nettoyer</span>
                                                        <?php endif; ?>
                                                    </p>
                                                    <p class="mb-1 text-muted small"><?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?></p>
                                                    <p class="mb-0 text-muted small"><?php echo e($transaction->room->capacity); ?> personnes • <?php echo e(Helper::formatCFA($transaction->room->price)); ?>/nuit</p>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Option 2: Changer de chambre -->
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="radio" name="room_option" 
                                               id="change_room" value="change" 
                                               <?php echo e(!$isRoomAvailable ? 'checked' : ''); ?>

                                               onchange="toggleRoomOptions('change')">
                                        <label class="form-check-label" for="change_room">
                                            <h6 class="mb-1">
                                                <i class="fas fa-exchange-alt text-primary me-2"></i>
                                                Changer de chambre
                                            </h6>
                                            <div class="ms-4">
                                                <p class="mb-1">Sélectionnez une chambre alternative</p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Liste des chambres alternatives (MODIFIÉE) -->
                                    <div id="alternative-rooms-container" style="<?php echo e($isRoomAvailable ? 'display: none;' : ''); ?>">
                                        <h6 class="mb-3">Chambres disponibles pour cette période :</h6>
                                        
                                        <?php if($alternativeRooms->isEmpty()): ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Aucune chambre alternative disponible pour cette période.
                                                Veuillez vérifier les disponibilités ou ajuster les dates.
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php $__currentLoopData = $alternativeRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $isDirty = $room->room_status_id == 6;
                                                        $canCheckIn = $room->canCheckIn ? $room->canCheckIn() : ($room->room_status_id == 1);
                                                    ?>
                                                    <div class="col-md-6">
                                                        <div class="alternative-room <?php echo e($isDirty ? 'dirty' : ''); ?>" 
                                                             onclick="selectAlternativeRoom(<?php echo e($room->id); ?>, <?php echo e($room->price); ?>, <?php echo e($isDirty ? 'true' : 'false'); ?>)"
                                                             id="room-<?php echo e($room->id); ?>">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        <span class="room-status-indicator <?php echo e($isDirty ? 'room-dirty' : 'room-available'); ?>"></span>
                                                                        Chambre <?php echo e($room->number); ?>

                                                                    </h6>
                                                                    <p class="mb-1 text-muted small"><?php echo e($room->type->name); ?></p>
                                                                    <p class="mb-0 text-muted small"><?php echo e($room->capacity); ?> personnes • <?php echo e(Helper::formatCFA($room->price)); ?>/nuit</p>
                                                                    <?php if($isDirty): ?>
                                                                        <span class="badge bg-warning mt-1">
                                                                            <i class="fas fa-broom me-1"></i>À nettoyer
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-success mt-1">
                                                                            <i class="fas fa-check-circle me-1"></i>Prête
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div>
                                                                    <i class="fas fa-check-circle text-success" 
                                                                       id="check-<?php echo e($room->id); ?>" 
                                                                       style="display: none;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            
                                            <!-- 🔴 Warning pour chambre sale -->
                                            <div class="mt-4" id="dirty-room-warning" style="display: none;">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>Attention :</strong> La chambre sélectionnée nécessite un nettoyage.
                                                    Le check-in ne pourra être effectué qu'après nettoyage par l'équipe housekeeping.
                                                </div>
                                            </div>
                                            
                                            <!-- Affichage différence de prix -->
                                            <div class="mt-4" id="price-difference-info" style="display: none;">
                                                <div class="alert alert-info">
                                                    <h6><i class="fas fa-money-bill-wave me-2"></i>Impact sur le prix</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">Ancien total:</p>
                                                            <p class="h5"><?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">Nouveau total:</p>
                                                            <p class="h5" id="new-total-price">0 CFA</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 small">Différence:</p>
                                                            <p class="h5" id="price-difference">0 CFA</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="confirmed_price_change" name="confirmed_price_change">
                                                    <label class="form-check-label" for="confirmed_price_change">
                                                        Je confirme le changement de prix
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Champs cachés pour la sélection de chambre -->
                                    <input type="hidden" name="change_room" id="change_room_input" value="<?php echo e(!$isRoomAvailable ? '1' : '0'); ?>">
                                    <input type="hidden" name="new_room_id" id="new_room_id">
                                    <input type="hidden" name="selected_room_dirty" id="selected_room_dirty" value="0">
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                            <i class="fas fa-arrow-left me-2"></i>Retour
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                                            <i class="fas fa-arrow-right me-2"></i>Continuer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Étape 4: Confirmation (MODIFIÉE) -->
                        <div class="form-tab" id="tab-4">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Confirmation du Check-in</h5>
                                </div>
                                <div class="card-body">
                                    <!-- 🔴 Alerte si chambre sale -->
                                    <div id="confirmation-dirty-warning" style="display: none;" class="alert alert-warning mb-3">
                                        <i class="fas fa-broom me-2"></i>
                                        <strong>Attention :</strong> La chambre sélectionnée est actuellement sale.
                                        Le check-in ne pourra être finalisé qu'après nettoyage.
                                    </div>
                                    
                                    <div class="alert alert-success">
                                        <i class="fas fa-clipboard-check fa-2x mb-3"></i>
                                        <h5>Résumé du Check-in</h5>
                                        <p class="mb-0">Vérifiez les informations avant de finaliser le check-in</p>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-user me-2"></i>Client</h6>
                                                <p class="mb-1" id="summary-client"><?php echo e($transaction->customer->name); ?></p>
                                                <p class="mb-0 text-muted small" id="summary-phone"><?php echo e($transaction->customer->phone); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-bed me-2"></i>Chambre</h6>
                                                <p class="mb-1" id="summary-room">Chambre <?php echo e($transaction->room->number); ?></p>
                                                <p class="mb-0 text-muted small" id="summary-room-type"><?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?></p>
                                                <p class="mb-0 text-muted small" id="summary-room-status"></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-users me-2"></i>Occupants</h6>
                                                <p class="mb-1" id="summary-adults">Adultes: 1</p>
                                                <p class="mb-0" id="summary-children">Enfants: 0</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <h6><i class="fas fa-id-card me-2"></i>Identité</h6>
                                                <p class="mb-1" id="summary-id-type">Type: -</p>
                                                <p class="mb-0" id="summary-id-number">Numéro: -</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="info-box">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>Séjour</h6>
                                                <p class="mb-1">
                                                    <strong>Arrivée:</strong> <?php echo e($transaction->check_in->format('d/m/Y H:i')); ?>

                                                    <span class="text-muted">(check-in maintenant)</span>
                                                </p>
                                                <p class="mb-1"><strong>Départ:</strong> <?php echo e($transaction->check_out->format('d/m/Y H:i')); ?></p>
                                                <p class="mb-0"><strong>Durée:</strong> <?php echo e($transaction->nights); ?> nuit(s)</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-4">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Attention:</strong> En confirmant, le statut de la réservation passera à "active" et 
                                        la chambre sera marquée comme occupée. Cette action est irréversible.
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                                            <i class="fas fa-arrow-left me-2"></i>Retour
                                        </button>
                                        <button type="submit" class="btn btn-success" id="confirm-checkin">
                                            <i class="fas fa-check-circle me-2"></i>Confirmer le Check-in
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar: Informations rapides (MODIFIÉE) -->
                <div class="col-lg-4">
                    <!-- Statut de la réservation -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Statut Réservation</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="display-6 mb-2">#<?php echo e($transaction->id); ?></div>
                                <span class="badge bg-warning fs-6">Réservation</span>
                            </div>
                            
                            <!-- 🔴 Statut chambre -->
                            <div class="mb-3 text-center">
                                <span class="badge bg-<?php echo e($transaction->room->status_color); ?> fs-6">
                                    <i class="fas <?php echo e($transaction->room->status_icon); ?> me-1"></i>
                                    Chambre: <?php echo e($transaction->room->status_label); ?>

                                </span>
                                <?php if($transaction->room->room_status_id == 6): ?>
                                    <span class="badge bg-warning mt-2 d-block">
                                        <i class="fas fa-broom me-1"></i>À nettoyer - Check-in bloqué
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Date création:</span>
                                    <strong><?php echo e($transaction->created_at->format('d/m/Y')); ?></strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Arrivée prévue:</span>
                                    <strong><?php echo e($transaction->check_in->format('H:i')); ?></strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Nuits:</span>
                                    <strong><?php echo e($transaction->nights); ?></strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Total:</span>
                                    <strong><?php echo e(Helper::formatCFA($transaction->getTotalPrice())); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides (MODIFIÉES) -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if(!isset($canCheckIn) || $canCheckIn): ?>
                                    <button type="button" class="btn btn-outline-primary" onclick="quickCheckIn()">
                                        <i class="fas fa-bolt me-2"></i>Check-in Rapide
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-warning" onclick="notifyHousekeeping(<?php echo e($transaction->room->id); ?>)">
                                        <i class="fas fa-bell me-2"></i>Notifier Housekeeping
                                    </button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('transaction.show', $transaction)); ?>" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-file-invoice me-2"></i>Voir Facture
                                </a>
                                <a href="<?php echo e(route('customer.show', $transaction->customer)); ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-user me-2"></i>Profil Client
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Aide (MODIFIÉE) -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Aide</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-<?php echo e((!isset($canCheckIn) || $canCheckIn) ? 'info' : 'warning'); ?> small mb-0">
                                <?php if(!isset($canCheckIn) || $canCheckIn): ?>
                                    <p class="mb-2"><strong>Procédure de check-in:</strong></p>
                                    <ol class="mb-0 ps-3">
                                        <li>Vérifiez l'identité du client</li>
                                        <li>Complétez les informations requises</li>
                                        <li>Attribuez une chambre disponible</li>
                                        <li>Confirmez le check-in</li>
                                    </ol>
                                <?php else: ?>
                                    <p class="mb-2"><strong>Check-in bloqué :</strong></p>
                                    <p class="mb-2"><?php echo e($checkInBlockedReason ?? $transaction->room->getCheckInErrorMessage()); ?></p>
                                    <?php if($isUrgentCleaning ?? false): ?>
                                        <button class="btn btn-warning btn-sm mt-2 w-100" onclick="notifyHousekeeping(<?php echo e($transaction->room->id); ?>)">
                                            <i class="fas fa-bell me-2"></i>Notifier housekeeping
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- 🔴 Fonction JavaScript pour notifier housekeeping -->
    <script>
    function notifyHousekeeping(roomId) {
        fetch('/checkin/notify-housekeeping/' + roomId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Erreur lors de la notification');
        });
    }

    function selectAlternativeRoomFromBlocked(roomId) {
        // Rediriger vers une nouvelle réservation avec cette chambre
        if (confirm('Voulez-vous changer pour cette chambre ?')) {
            // Logique pour changer de chambre depuis la page bloquée
            window.location.href = '/checkin/<?php echo e($transaction->id); ?>/change-room/' + roomId;
        }
    }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
let currentStep = 1;
let selectedRoomId = null;
let selectedRoomDirty = false;
let originalRoomPrice = <?php echo e($transaction->room->price); ?>;
let originalTotal = <?php echo e($transaction->getTotalPrice()); ?>;
let nights = <?php echo e($transaction->nights); ?>;

function updateStepIndicator(step) {
    // Mettre à jour toutes les étapes
    for (let i = 1; i <= 4; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        const stepNumber = stepElement.querySelector('.step-number');
        
        if (i < step) {
            stepElement.classList.remove('active');
            stepElement.classList.add('completed');
            stepNumber.innerHTML = '<i class="fas fa-check"></i>';
        } else if (i === step) {
            stepElement.classList.add('active');
            stepElement.classList.remove('completed');
            stepNumber.textContent = i;
        } else {
            stepElement.classList.remove('active', 'completed');
            stepNumber.textContent = i;
        }
    }
}

function showTab(tabNumber) {
    // Cacher tous les onglets
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`tab-${i}`).classList.remove('active');
    }
    // Afficher l'onglet actif
    document.getElementById(`tab-${tabNumber}`).classList.add('active');
}

function nextStep(next) {
    // Validation de l'étape actuelle
    if (currentStep === 2) {
        // Validation des informations client
        const adults = document.getElementById('adults').value;
        const idType = document.getElementById('id_type').value;
        const idNumber = document.getElementById('id_number').value;
        const nationality = document.getElementById('nationality').value;
        
        if (!adults || !idType || !idNumber || !nationality) {
            alert('Veuillez remplir tous les champs obligatoires de l\'étape 2');
            return;
        }
        
        // Mettre à jour le résumé
        document.getElementById('summary-adults').textContent = `Adultes: ${adults}`;
        document.getElementById('summary-children').textContent = `Enfants: ${document.getElementById('children').value || 0}`;
        document.getElementById('summary-id-type').textContent = `Type: ${document.getElementById('id_type').options[document.getElementById('id_type').selectedIndex].text}`;
        document.getElementById('summary-id-number').textContent = `Numéro: ${idNumber}`;
    }
    
    if (currentStep === 3) {
        // Validation de la sélection de chambre
        const roomOption = document.querySelector('input[name="room_option"]:checked').value;
        
        if (roomOption === 'change') {
            if (!selectedRoomId) {
                alert('Veuillez sélectionner une chambre alternative');
                return;
            }
            
            // 🔴 Vérifier si chambre sale
            if (selectedRoomDirty) {
                if (!confirm('⚠️ La chambre sélectionnée est sale. Le check-in ne sera possible qu\'après nettoyage. Voulez-vous continuer ?')) {
                    return;
                }
            }
            
            // Vérifier si changement de prix confirmé
            const priceDifferenceElement = document.getElementById('price-difference');
            const priceDifferenceText = priceDifferenceElement.textContent.replace('CFA', '').replace(/\s/g, '');
            const priceDifference = parseInt(priceDifferenceText);
            
            if (priceDifference !== 0) {
                const confirmed = document.getElementById('confirmed_price_change').checked;
                if (!confirmed) {
                    alert('Veuillez confirmer le changement de prix avant de continuer');
                    return;
                }
            }
        }
        
        // Mettre à jour le résumé de chambre
        if (roomOption === 'keep') {
            document.getElementById('summary-room').textContent = `Chambre <?php echo e($transaction->room->number); ?>`;
            document.getElementById('summary-room-type').textContent = `<?php echo e($transaction->room->type->name ?? 'Type non spécifié'); ?>`;
            document.getElementById('summary-room-status').textContent = '';
            document.getElementById('confirmation-dirty-warning').style.display = 'none';
        } else {
            const selectedRoomElement = document.getElementById(`room-${selectedRoomId}`);
            const roomNumber = selectedRoomElement.querySelector('h6').textContent.replace('Chambre ', '');
            const roomType = selectedRoomElement.querySelector('p.text-muted').textContent;
            document.getElementById('summary-room').textContent = `Chambre ${roomNumber}`;
            document.getElementById('summary-room-type').textContent = roomType;
            
            // 🔴 Afficher warning si chambre sale
            if (selectedRoomDirty) {
                document.getElementById('summary-room-status').textContent = '⚠️ Chambre sale - Check-in après nettoyage';
                document.getElementById('confirmation-dirty-warning').style.display = 'block';
            } else {
                document.getElementById('summary-room-status').textContent = '';
                document.getElementById('confirmation-dirty-warning').style.display = 'none';
            }
        }
    }
    
    currentStep = next;
    updateStepIndicator(currentStep);
    showTab(currentStep);
}

function prevStep(prev) {
    currentStep = prev;
    updateStepIndicator(currentStep);
    showTab(currentStep);
}

function toggleRoomOptions(option) {
    const changeRoomInput = document.getElementById('change_room_input');
    const alternativeRoomsContainer = document.getElementById('alternative-rooms-container');
    
    if (option === 'change') {
        changeRoomInput.value = '1';
        alternativeRoomsContainer.style.display = 'block';
        document.getElementById('new_room_id').value = '';
        document.getElementById('price-difference-info').style.display = 'none';
        document.getElementById('dirty-room-warning').style.display = 'none';
        document.getElementById('selected_room_dirty').value = '0';
        selectedRoomDirty = false;
    } else {
        changeRoomInput.value = '0';
        alternativeRoomsContainer.style.display = 'none';
        document.getElementById('new_room_id').value = '';
        document.getElementById('price-difference-info').style.display = 'none';
        document.getElementById('dirty-room-warning').style.display = 'none';
        document.getElementById('selected_room_dirty').value = '0';
        selectedRoomDirty = false;
        
        // Désélectionner toutes les chambres
        document.querySelectorAll('.alternative-room').forEach(room => {
            room.classList.remove('selected');
            const roomId = room.id.replace('room-', '');
            document.getElementById(`check-${roomId}`).style.display = 'none';
        });
    }
}

function selectAlternativeRoom(roomId, roomPrice, isDirty) {
    // Désélectionner toutes les chambres
    document.querySelectorAll('.alternative-room').forEach(room => {
        room.classList.remove('selected');
        const id = room.id.replace('room-', '');
        document.getElementById(`check-${id}`).style.display = 'none';
    });
    
    // Sélectionner la chambre choisie
    document.getElementById(`room-${roomId}`).classList.add('selected');
    document.getElementById(`check-${roomId}`).style.display = 'inline-block';
    
    // Mettre à jour les champs cachés
    document.getElementById('new_room_id').value = roomId;
    document.getElementById('selected_room_dirty').value = isDirty ? 1 : 0;
    selectedRoomId = roomId;
    selectedRoomDirty = isDirty;
    
    // Afficher warning si chambre sale
    if (isDirty) {
        document.getElementById('dirty-room-warning').style.display = 'block';
    } else {
        document.getElementById('dirty-room-warning').style.display = 'none';
    }
    
    // Calculer et afficher la différence de prix
    const newTotal = roomPrice * nights;
    const priceDifference = newTotal - originalTotal;
    
    document.getElementById('new-total-price').textContent = formatCFA(newTotal);
    document.getElementById('price-difference').textContent = formatCFA(priceDifference);
    
    const priceDifferenceElement = document.getElementById('price-difference');
    priceDifferenceElement.className = 'h5';
    
    if (priceDifference > 0) {
        priceDifferenceElement.classList.add('price-positive');
    } else if (priceDifference < 0) {
        priceDifferenceElement.classList.add('price-negative');
    } else {
        priceDifferenceElement.classList.add('price-neutral');
    }
    
    document.getElementById('price-difference-info').style.display = 'block';
    
    // Vérifier la case de confirmation si pas de changement de prix
    if (priceDifference === 0) {
        document.getElementById('confirmed_price_change').checked = true;
    } else {
        document.getElementById('confirmed_price_change').checked = false;
    }
}

function formatCFA(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' CFA';
}

function quickCheckIn() {
    if (confirm('Effectuer un check-in rapide sans formulaire détaillé ?\n\nLe client sera enregistré avec les informations de base et la chambre originale.')) {
        fetch(`/checkin/<?php echo e($transaction->id); ?>/quick`, {
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
            if (data.success) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message || 'Check-in rapide effectué avec succès!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').prepend(alertDiv);
                
                setTimeout(() => {
                    window.location.href = '<?php echo e(route("checkin.index")); ?>';
                }, 2000);
            } else {
                alert('Erreur: ' + (data.error || 'Échec du check-in rapide'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors du check-in rapide');
        });
    }
}

// Initialiser le stepper
document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicator(1);
    
    // Initialiser les valeurs par défaut pour le résumé
    document.getElementById('summary-client').textContent = '<?php echo e($transaction->customer->name); ?>';
    document.getElementById('summary-phone').textContent = '<?php echo e($transaction->customer->phone); ?>';
    document.getElementById('summary-room').textContent = 'Chambre <?php echo e($transaction->room->number); ?>';
    document.getElementById('summary-room-type').textContent = '<?php echo e($transaction->room->type->name ?? "Type non spécifié"); ?>';
    
    // Si la chambre n'est pas disponible, forcer la sélection de chambre alternative
    <?php if(!$isRoomAvailable): ?>
        document.getElementById('change_room').checked = true;
        toggleRoomOptions('change');
    <?php endif; ?>
    
    // Désactiver la soumission multiple
    const form = document.getElementById('checkin-form');
    const submitButton = document.getElementById('confirm-checkin');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }
            
            // Désactiver le bouton et afficher l'indicateur de chargement
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
            form.classList.add('submitting');
            
            return true;
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/checkin/show.blade.php ENDPATH**/ ?>