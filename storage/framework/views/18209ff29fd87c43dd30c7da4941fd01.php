

<?php $__env->startSection('title', 'Confirmation de Réservation'); ?>

<?php $__env->startSection('head'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('style/css/progress-indication.css')); ?>">
    <style>
        .summary-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #4e73df;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .price-highlight {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2e59d9;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 10px 15px;
            border-radius: 8px;
            border-left: 4px solid #2e59d9;
        }
        .customer-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .avatar-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #4e73df, #2e59d9);
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .info-icon {
            width: 30px;
            text-align: center;
            color: #4e73df;
        }
        .currency-symbol {
            font-weight: bold;
            color: #2e59d9;
        }
        .payment-options .form-check {
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .payment-options .form-check:hover {
            background-color: #f8f9fa;
            border-color: #4e73df;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .payment-options .form-check-input:checked + .form-check-label {
            font-weight: bold;
            color: #2e59d9;
        }
        .payment-options .form-check-input:checked ~ .payment-details {
            display: block;
        }
        .payment-options .form-check-input:checked ~ .form-check-label::before {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .payment-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .alert-custom {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .table-custom tr:last-child td {
            border-bottom: none;
        }
        .btn-confirm {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
            background: linear-gradient(135deg, #218838, #1ea085);
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.3);
            background: linear-gradient(135deg, #5a6268, #343a40);
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: -42px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #4e73df;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #4e73df;
            top: 5px;
            z-index: 2;
        }
        .timeline-content {
            padding-left: 20px;
            border-left: 2px solid #4e73df;
            position: relative;
        }
        .timeline-item:last-child .timeline-content {
            border-left: 2px dashed #4e73df;
        }
        .badge-custom {
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 20px;
        }
        .card-header {
            border-radius: 8px 8px 0 0 !important;
            background: linear-gradient(135deg, #4e73df, #2e59d9);
            border: none;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .form-range::-webkit-slider-thumb {
            background: #4e73df;
        }
        .form-range::-moz-range-thumb {
            background: #4e73df;
        }
        .agent-card {
            border-left: 4px solid #20c997;
            background: linear-gradient(135deg, #f8fff9 0%, #e9f7ef 100%);
        }
        .agent-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #20c997;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('transaction.reservation.progressbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <div class="container mt-4">
        <!-- Agent de réservation -->
        <?php if(auth()->guard()->check()): ?>
        <div class="alert alert-info alert-custom mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white rounded-circle p-3 me-3">
                    <i class="fas fa-user-tie fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-user-circle me-2"></i>
                                Agent de réservation
                            </h6>
                            <p class="mb-0 fw-bold"><?php echo e(auth()->user()->name); ?></p>
                            <small class="text-muted"><?php echo e(auth()->user()->email); ?></small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo e(auth()->user()->role == 'Super' ? 'danger' : (auth()->user()->role == 'Admin' ? 'primary' : 'success')); ?>">
                                <?php echo e(auth()->user()->role); ?>

                            </span>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Connecté
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Alertes -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-custom fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo session('success'); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-custom fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if($existingReservationsCount > 0): ?>
                <div class="alert alert-info alert-custom fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Client régulier</h6>
                            <p class="mb-0">Ce client a déjà <?php echo e($existingReservationsCount); ?> réservation(s) dans notre établissement.</p>
                            <a href="<?php echo e(route('transaction.reservation.customerReservations', $customer)); ?>" 
                               class="btn btn-sm btn-outline-info mt-2">
                                <i class="fas fa-history me-1"></i>
                                Voir l'historique
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Formulaire de réservation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-white">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Confirmation de Réservation
                                </h4>
                                <small class="text-white opacity-75">Étape finale - Récapitulatif et paiement</small>
                            </div>
                            <?php if(auth()->guard()->check()): ?>
                            <div class="text-white opacity-75">
                                <small>
                                    <i class="fas fa-user-shield me-1"></i>
                                    Agent: <?php echo e(auth()->user()->name); ?>

                                </small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Informations du séjour -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card summary-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">
                                            <i class="fas fa-bed me-2"></i>
                                            Chambre sélectionnée
                                        </h5>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                                <i class="fas fa-door-closed fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Chambre <?php echo e($room->number); ?></h6>
                                                <p class="text-muted mb-1"><?php echo e($room->type->name ?? 'Standard'); ?></p>
                                                <span class="badge bg-info badge-custom">
                                                    <i class="fas fa-users me-1"></i>
                                                    <?php echo e($room->capacity); ?> personnes
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <p class="mb-1">
                                                <i class="fas fa-tag me-2 text-muted"></i>
                                                Prix par nuit : 
                                                <span class="fw-bold text-dark float-end">
                                                    <?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card summary-card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Détails du séjour
                                        </h5>
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="fw-bold mb-1">Arrivée</h6>
                                                    <p class="mb-0 text-dark">
                                                        <?php echo e(\Carbon\Carbon::parse($stayFrom)->format('d/m/Y')); ?>

                                                        <span class="badge bg-primary ms-2">
                                                            <?php echo e(\Carbon\Carbon::parse($stayFrom)->format('H:i')); ?>

                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="fw-bold mb-1">Départ</h6>
                                                    <p class="mb-0 text-dark">
                                                        <?php echo e(\Carbon\Carbon::parse($stayUntil)->format('d/m/Y')); ?>

                                                        <span class="badge bg-primary ms-2">
                                                            <?php echo e(\Carbon\Carbon::parse($stayUntil)->format('H:i')); ?>

                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-2 border-top">
                                            <p class="mb-1">
                                                <i class="fas fa-moon me-2 text-muted"></i>
                                                Nombre de nuits : 
                                                <span class="fw-bold text-dark float-end"><?php echo e($dayDifference); ?></span>
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-calendar-day me-2 text-muted"></i>
                                                Personnes : 
                                                <span class="fw-bold text-dark float-end">1</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calcul du prix -->
                        <div class="card summary-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-calculator me-2"></i>
                                    Calcul du prix
                                </h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><?php echo e($room->price); ?> FCFA × <?php echo e($dayDifference); ?> nuit(s)</td>
                                                <td class="text-end fw-bold">
                                                    <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA
                                                </td>
                                            </tr>
                                            <tr class="table-light">
                                                <td><strong>Total séjour</strong></td>
                                                <td class="text-end">
                                                    <h5 class="mb-0 text-primary fw-bold">
                                                        <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA
                                                    </h5>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="price-highlight h-100 d-flex flex-column justify-content-center">
                                            <small class="d-block text-muted">TOTAL</small>
                                            <div class="fw-bold fs-3">
                                                <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?>

                                            </div>
                                            <small class="d-block text-muted">FCFA</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options de paiement -->
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-credit-card me-2 text-primary"></i>
                                    Mode de paiement
                                </h5>
                                <small class="text-muted">Choisissez comment vous souhaitez procéder au paiement</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" 
                                      action="<?php echo e(route('transaction.reservation.payDownPayment', ['customer' => $customer->id, 'room' => $room->id])); ?>"
                                      id="reservationForm">
                                    <?php echo csrf_field(); ?>
                                    
                                    <!-- Champs cachés obligatoires -->
                                    <input type="hidden" name="check_in" value="<?php echo e($stayFrom); ?>">
                                    <input type="hidden" name="check_out" value="<?php echo e($stayUntil); ?>">
                                    <input type="hidden" name="person_count" value="1">
                                    <input type="hidden" name="downPayment" id="downPaymentHidden" value="0">
                                    
                                    <div class="payment-options mb-4">
                                        <!-- Option 1 : Réservation sans acompte -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_reserve_only" value="reserve_only" checked>
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_reserve_only">
                                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-calendar-check fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Réserver sans acompte</div>
                                                    <small class="text-muted d-block">
                                                        Confirmation immédiate sans paiement
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="alert alert-light border mt-2">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                                    <small>
                                                        La réservation est confirmée sans paiement immédiat. 
                                                        Le paiement complet sera effectué à l'arrivée du client.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Option 2 : Payer un acompte -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_deposit" value="pay_deposit">
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_pay_deposit">
                                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Payer un acompte</div>
                                                    <small class="text-muted d-block">
                                                        Sécurisez votre réservation avec un acompte
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="mt-3">
                                                    <label for="deposit_amount" class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>
                                                        Montant de l'acompte
                                                    </label>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-primary text-white border-primary">
                                                            <i class="fas fa-currency-sign"></i>
                                                        </span>
                                                        <input type="number" 
                                                               class="form-control border-primary" 
                                                               id="deposit_amount" 
                                                               name="deposit_amount"
                                                               value="<?php echo e($downPayment); ?>"
                                                               min="0"
                                                               max="<?php echo e($room->price * $dayDifference); ?>"
                                                               step="500"
                                                               disabled
                                                               placeholder="Entrez le montant">
                                                        <span class="input-group-text bg-light border-primary">FCFA</span>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <input type="range" 
                                                                   class="form-range" 
                                                                   id="deposit_slider"
                                                                   min="0" 
                                                                   max="<?php echo e($room->price * $dayDifference); ?>" 
                                                                   step="500"
                                                                   value="<?php echo e($downPayment); ?>"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">
                                                                <i class="fas fa-lightbulb me-1"></i>
                                                                Recommandé : <?php echo e(number_format($downPayment, 0, ',', ' ')); ?> FCFA
                                                            </small>
                                                            <small class="text-muted">
                                                                Max : <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Méthode de paiement -->
                                                <div class="mt-3">
                                                    <label for="payment_method" class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        Méthode de paiement
                                                    </label>
                                                    <select class="form-select" id="payment_method" name="payment_method">
                                                        <option value="cash" selected>💵 Espèces</option>
                                                        <option value="card">💳 Carte bancaire</option>
                                                        <option value="mobile_money">📱 Mobile Money</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Option 3 : Payer la totalité -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_full" value="pay_full">
                                            <label class="form-check-label fw-bold d-flex align-items-center" for="option_pay_full">
                                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-wallet fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div>Paiement complet</div>
                                                    <small class="text-muted d-block">
                                                        Payez l'intégralité du séjour maintenant
                                                    </small>
                                                </div>
                                            </label>
                                            <div class="payment-details">
                                                <div class="alert alert-success mt-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                                        <div>
                                                            <h6 class="alert-heading mb-1">Paiement complet</h6>
                                                            <p class="mb-0 fw-bold">
                                                                <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA
                                                            </p>
                                                            <small>Votre réservation sera entièrement confirmée</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Méthode de paiement -->
                                                <div class="mt-3">
                                                    <label for="payment_method_full" class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>
                                                        Méthode de paiement
                                                    </label>
                                                    <select class="form-select" id="payment_method_full" name="payment_method">
                                                        <option value="cash" selected>💵 Espèces</option>
                                                        <option value="card">💳 Carte bancaire</option>
                                                        <option value="mobile_money">📱 Mobile Money</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Résumé du paiement -->
                                    <div class="alert alert-info alert-custom mb-4" id="paymentSummary">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-invoice fa-2x me-3"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="alert-heading mb-2">
                                                    <i class="fas fa-receipt me-2"></i>
                                                    Récapitulatif du paiement
                                                </h6>
                                                <div id="summaryText" class="fw-bold mb-2">
                                                    Réservation sans acompte
                                                </div>
                                                <div id="amountDetails" class="mt-3" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span>Montant payé :</span>
                                                                <strong id="paidAmount" class="text-success">0 FCFA</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span>Solde à régler :</span>
                                                                <strong id="balanceAmount" class="text-primary">
                                                                    <?php echo e(number_format($room->price * $dayDifference, 0, ',', ' ')); ?> FCFA
                                                                </strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-success" 
                                                                     id="paymentProgress"
                                                                     role="progressbar" 
                                                                     style="width: 0%"
                                                                     aria-valuenow="0" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                    0%
                                                                </div>
                                                            </div>
                                                            <small class="text-muted mt-1 d-block text-center" id="progressText">
                                                                Aucun paiement effectué
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Conditions générales -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-body">
                                            <h6 class="card-title text-dark mb-3">
                                                <i class="fas fa-file-contract me-2 text-primary"></i>
                                                Conditions générales
                                            </h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    J'accepte les conditions générales de réservation :
                                                    <ul class="mt-2 mb-0 small">
                                                        <li>La réservation est confirmée immédiatement après validation</li>
                                                        <li>Le paiement complet est dû à l'arrivée (sauf paiement anticipé)</li>
                                                        <li>Annulation gratuite jusqu'à 48h avant l'arrivée</li>
                                                        <li>Check-in à partir de 14h, check-out avant 12h</li>
                                                        <li>Présentation d'une pièce d'identité obligatoire à l'arrivée</li>
                                                    </ul>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Agent responsable -->
                                    <?php if(auth()->guard()->check()): ?>
                                    <div class="card agent-card mb-4">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">
                                                        <i class="fas fa-user-check me-2"></i>
                                                        Responsable de la réservation
                                                    </h6>
                                                    <p class="mb-0 fw-bold"><?php echo e(auth()->user()->name); ?></p>
                                                    <small class="text-muted"><?php echo e(auth()->user()->email); ?> • <?php echo e(auth()->user()->role); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?php echo e(route('transaction.reservation.chooseRoom', ['customer' => $customer->id])); ?>?check_in=<?php echo e($stayFrom); ?>&check_out=<?php echo e($stayUntil); ?>"
                                           class="btn btn-back px-4">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Retour
                                        </a>
                                        
                                        <button type="submit" class="btn btn-confirm px-5" id="submitBtn">
                                            <i class="fas fa-calendar-plus me-2"></i>
                                            <span id="submitText">Confirmer la réservation</span>
                                            <small class="d-block fw-normal opacity-75">Agent: <?php echo e(auth()->user()->name ?? 'Système'); ?></small>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne latérale - Informations client -->
            <div class="col-lg-4">
                <div class="card shadow-lg sticky-top" style="top: 20px;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Informations Client
                        </h5>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="avatar-placeholder">
                        <div class="text-center text-white">
                            <?php if($customer->avatar): ?>
                                <img src="<?php echo e(Storage::url($customer->avatar)); ?>" 
                                     alt="<?php echo e($customer->name); ?>" 
                                     class="customer-avatar">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-6x"></i>
                                <h4 class="mt-3"><?php echo e($customer->name); ?></h4>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table class="table table-custom">
                            <tbody>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-id-card"></i></td>
                                    <td><strong>ID Client</strong></td>
                                    <td class="text-end"><span class="badge bg-secondary">#<?php echo e($customer->id); ?></span></td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-user"></i></td>
                                    <td><strong>Nom complet</strong></td>
                                    <td class="text-end"><?php echo e($customer->name); ?></td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-<?php echo e($customer->gender == 'Male' ? 'male' : 'female'); ?>"></i></td>
                                    <td><strong>Genre</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-<?php echo e($customer->gender == 'Male' ? 'primary' : 'danger'); ?>">
                                            <?php echo e($customer->gender == 'Male' ? 'Homme' : 'Femme'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-phone"></i></td>
                                    <td><strong>Téléphone</strong></td>
                                    <td class="text-end"><?php echo e($customer->phone ?? 'Non renseigné'); ?></td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-envelope"></i></td>
                                    <td><strong>Email</strong></td>
                                    <td class="text-end">
                                        <small><?php echo e($customer->email ?? 'Non renseigné'); ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-briefcase"></i></td>
                                    <td><strong>Profession</strong></td>
                                    <td class="text-end"><?php echo e($customer->job ?? 'Non renseigné'); ?></td>
                                </tr>
                                <?php if($customer->address): ?>
                                <tr>
                                    <td class="info-icon"><i class="fas fa-map-marker-alt"></i></td>
                                    <td><strong>Adresse</strong></td>
                                    <td class="text-end">
                                        <small><?php echo e($customer->address); ?></small>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($existingReservationsCount > 0): ?>
                                <tr class="table-info">
                                    <td class="info-icon"><i class="fas fa-bed"></i></td>
                                    <td><strong>Historique</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-warning text-dark">
                                            <?php echo e($existingReservationsCount); ?> réservation(s)
                                        </span>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($customer->user): ?>
                                <tr class="table-success">
                                    <td class="info-icon"><i class="fas fa-user-tie"></i></td>
                                    <td><strong>Créé par</strong></td>
                                    <td class="text-end">
                                        <small><?php echo e($customer->user->name ?? 'Système'); ?></small>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <!-- Statut client -->
                        <div class="mt-4">
                            <h6 class="text-dark mb-2">
                                <i class="fas fa-chart-line me-2 text-primary"></i>
                                Statut client
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Type</small>
                                    <?php if($existingReservationsCount == 0): ?>
                                        <span class="badge bg-secondary">Nouveau client</span>
                                    <?php elseif($existingReservationsCount < 3): ?>
                                        <span class="badge bg-primary">Client occasionnel</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Client fidèle</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Depuis</small>
                                    <span class="fw-bold">
                                        <?php echo e($customer->created_at ? $customer->created_at->format('m/Y') : 'Récent'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Agent connecté -->
                        <?php if(auth()->guard()->check()): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-dark mb-2">
                                <i class="fas fa-user-shield me-2 text-success"></i>
                                Agent connecté
                            </h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-bold"><?php echo e(auth()->user()->name); ?></p>
                                    <small class="text-muted d-block"><?php echo e(auth()->user()->email); ?></small>
                                    <small class="badge bg-info"><?php echo e(auth()->user()->role); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Réservation créée le <?php echo e(now()->format('d/m/Y à H:i')); ?>

                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.getElementById('reservationForm');
    const downPaymentHidden = document.getElementById('downPaymentHidden');
    const depositAmountInput = document.getElementById('deposit_amount');
    const depositSlider = document.getElementById('deposit_slider');
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentMethodFullSelect = document.getElementById('payment_method_full');
    const paymentSummary = document.getElementById('paymentSummary');
    const summaryText = document.getElementById('summaryText');
    const amountDetails = document.getElementById('amountDetails');
    const paidAmount = document.getElementById('paidAmount');
    const balanceAmount = document.getElementById('balanceAmount');
    const paymentProgress = document.getElementById('paymentProgress');
    const progressText = document.getElementById('progressText');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const termsCheckbox = document.getElementById('terms');
    
    // Variables globales
    const totalPrice = <?php echo e($room->price * $dayDifference); ?>;
    const recommendedDeposit = <?php echo e($downPayment); ?>;
    const agentName = "<?php echo e(auth()->user()->name ?? 'Système'); ?>";
    
    // Formater la monnaie
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }
    
    // Mettre à jour le résumé du paiement
    function updatePaymentSummary() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        let paymentAmount = 0;
        let summary = '';
        let showDetails = false;
        let paymentMethod = 'cash';
        
        switch(selectedOption) {
            case 'reserve_only':
                paymentAmount = 0;
                summary = 'Réservation sans acompte';
                showDetails = false;
                downPaymentHidden.value = 0;
                paymentMethod = 'cash';
                break;
                
            case 'pay_deposit':
                paymentAmount = parseFloat(depositAmountInput.value) || 0;
                summary = `Acompte de ${formatCurrency(paymentAmount)}`;
                showDetails = true;
                downPaymentHidden.value = paymentAmount;
                paymentMethod = paymentMethodSelect.value;
                break;
                
            case 'pay_full':
                paymentAmount = totalPrice;
                summary = 'Paiement complet';
                showDetails = true;
                downPaymentHidden.value = paymentAmount;
                paymentMethod = paymentMethodFullSelect.value;
                break;
        }
        
        // Mettre à jour l'affichage
        summaryText.textContent = summary;
        
        if (showDetails) {
            const percentage = Math.round((paymentAmount / totalPrice) * 100);
            
            paidAmount.textContent = formatCurrency(paymentAmount);
            balanceAmount.textContent = formatCurrency(totalPrice - paymentAmount);
            paymentProgress.style.width = percentage + '%';
            paymentProgress.textContent = percentage + '%';
            amountDetails.style.display = 'block';
            
            // Mettre à jour le texte de progression
            if (percentage === 100) {
                progressText.textContent = 'Paiement complet ✓';
                paymentProgress.className = 'progress-bar bg-success';
                paymentSummary.className = 'alert alert-success alert-custom';
            } else if (percentage > 0) {
                progressText.textContent = percentage + '% du paiement effectué';
                paymentProgress.className = 'progress-bar bg-warning';
                paymentSummary.className = 'alert alert-warning alert-custom';
            } else {
                progressText.textContent = 'Aucun paiement effectué';
                paymentProgress.className = 'progress-bar bg-secondary';
                paymentSummary.className = 'alert alert-info alert-custom';
            }
        } else {
            amountDetails.style.display = 'none';
            paymentSummary.className = 'alert alert-info alert-custom';
        }
        
        // Activer/désactiver le bouton selon les conditions
        submitBtn.disabled = !termsCheckbox.checked;
        
        // Mettre à jour le texte du bouton
        let buttonText = 'Confirmer la réservation';
        let buttonSubtext = 'Agent: ' + agentName;
        
        if (paymentAmount === totalPrice) {
            buttonText = '<i class="fas fa-wallet me-2"></i> Payer et réserver';
            buttonSubtext = formatCurrency(paymentAmount) + ' • Agent: ' + agentName;
        } else if (paymentAmount > 0) {
            buttonText = `<i class="fas fa-money-bill-wave me-2"></i> Payer ${formatCurrency(paymentAmount)}`;
            buttonSubtext = 'Solde: ' + formatCurrency(totalPrice - paymentAmount) + ' • Agent: ' + agentName;
        }
        
        submitText.innerHTML = buttonText;
        document.querySelector('#submitBtn small').textContent = buttonSubtext;
        
        // Mettre à jour le style du bouton
        if (paymentAmount > 0) {
            submitBtn.classList.remove('btn-confirm');
            submitBtn.classList.add('btn-primary');
            submitBtn.style.background = 'linear-gradient(135deg, #4e73df, #2e59d9)';
        } else {
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-confirm');
            submitBtn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
        }
    }
    
    // Synchroniser les méthodes de paiement
    function syncPaymentMethods() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        
        if (selectedOption === 'pay_deposit') {
            paymentMethodFullSelect.value = paymentMethodSelect.value;
        } else if (selectedOption === 'pay_full') {
            paymentMethodSelect.value = paymentMethodFullSelect.value;
        }
    }
    
    // Activer/désactiver les champs selon l'option
    function toggleDepositInput() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        depositAmountInput.disabled = selectedOption !== 'pay_deposit';
        depositSlider.disabled = selectedOption !== 'pay_deposit';
        paymentMethodSelect.disabled = selectedOption !== 'pay_deposit';
        paymentMethodFullSelect.disabled = selectedOption !== 'pay_full';
        
        switch(selectedOption) {
            case 'pay_deposit':
                if (!depositAmountInput.value || depositAmountInput.value == 0) {
                    depositAmountInput.value = recommendedDeposit;
                    depositSlider.value = recommendedDeposit;
                }
                break;
            case 'pay_full':
                depositAmountInput.value = totalPrice;
                depositSlider.value = totalPrice;
                break;
            case 'reserve_only':
                depositAmountInput.value = 0;
                depositSlider.value = 0;
                break;
        }
    }
    
    // Écouteurs d'événements
    document.querySelectorAll('input[name="payment_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleDepositInput();
            syncPaymentMethods();
            updatePaymentSummary();
        });
    });
    
    depositAmountInput.addEventListener('input', function() {
        depositSlider.value = this.value;
        updatePaymentSummary();
    });
    
    depositSlider.addEventListener('input', function() {
        depositAmountInput.value = this.value;
        updatePaymentSummary();
    });
    
    paymentMethodSelect.addEventListener('change', function() {
        syncPaymentMethods();
    });
    
    paymentMethodFullSelect.addEventListener('change', function() {
        syncPaymentMethods();
    });
    
    termsCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
        if (this.checked) {
            submitBtn.classList.remove('btn-secondary');
        } else {
            submitBtn.classList.add('btn-secondary');
        }
    });
    
    // Validation du formulaire
    reservationForm.addEventListener('submit', function(e) {
        console.log('=== DEBUG FORMULAIRE ===');
        console.log('Agent:', agentName);
        console.log('Check-in:', document.querySelector('input[name="check_in"]').value);
        console.log('Check-out:', document.querySelector('input[name="check_out"]').value);
        console.log('Acompte:', downPaymentHidden.value);
        console.log('Option paiement:', document.querySelector('input[name="payment_option"]:checked').value);
        console.log('Conditions acceptées:', termsCheckbox.checked);
        console.log('=== FIN DEBUG ===');
        
        if (!termsCheckbox.checked) {
            e.preventDefault();
            showAlert('error', 'Vous devez accepter les conditions générales pour continuer.');
            termsCheckbox.focus();
            return false;
        }
        
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        const depositAmount = parseFloat(depositAmountInput.value) || 0;
        
        if (selectedOption === 'pay_deposit') {
            if (depositAmount > totalPrice) {
                e.preventDefault();
                showAlert('error', 'L\'acompte ne peut pas dépasser le prix total du séjour.');
                depositAmountInput.focus();
                return false;
            }
            
            if (depositAmount < 0) {
                e.preventDefault();
                showAlert('error', 'Le montant de l\'acompte doit être positif.');
                depositAmountInput.focus();
                return false;
            }
        }
        
        // Afficher l'état de chargement
        submitBtn.innerHTML = `
            <i class="fas fa-spinner fa-spin me-2"></i>
            <span id="submitText">Traitement en cours...</span>
            <small class="d-block fw-normal opacity-75">Agent: ${agentName}</small>
        `;
        submitBtn.disabled = true;
        
        // La soumission continue normalement
    });
    
    // Fonction d'alerte
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const alertContainer = document.createElement('div');
        alertContainer.innerHTML = alertHtml;
        reservationForm.parentNode.insertBefore(alertContainer, reservationForm);
        
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertContainer.querySelector('.alert'));
            alert.close();
        }, 5000);
    }
    
    // Initialisation
    toggleDepositInput();
    syncPaymentMethods();
    updatePaymentSummary();
    
    // Animation d'entrée
    setTimeout(() => {
        document.querySelectorAll('.card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }, 100);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/transaction/reservation/confirmation.blade.php ENDPATH**/ ?>