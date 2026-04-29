

<?php $__env->startSection('title', 'Facture de Paiement'); ?>

<?php $__env->startSection('head'); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Maven+Pro&display=swap');

        body {
            font-family: 'Maven Pro', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border: 2px solid #28a745;
        }

        .invoice-header {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box {
            background: #f8fff9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .total-box {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .late-checkout-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }

        .late-checkout-title {
            color: #856404;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-paid {
            background: #28a745;
            color: white;
        }

        .status-pending {
            background: #ffc107;
            color: #333;
        }

        .status-late {
            background: #dc3545;
            color: white;
        }

        .amount {
            font-weight: bold;
            font-size: 18px;
        }

        .action-buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-print, .btn-pdf {
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            margin: 0 5px;
        }

        .btn-print {
            background: #28a745;
            color: white;
        }

        .btn-print:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .btn-pdf {
            background: #28a745;
            color: white;
        }

        .btn-pdf:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table thead th {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
            padding: 10px;
            font-size: 13px;
        }

        .table-bordered {
            border: 1px solid #28a745 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 10px;
            font-size: 13px;
        }

        .payment-history-table tbody tr:hover {
            background-color: #f8fff9;
        }

        .payment-method-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }

        .method-cash {
            background: #28a745;
            color: white;
        }

        .method-card {
            background: #007bff;
            color: white;
        }

        .method-mobile {
            background: #6f42c1;
            color: white;
        }

        .method-bank {
            background: #fd7e14;
            color: white;
        }

        .method-other {
            background: #6c757d;
            color: white;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .border-top {
            border-top: 2px solid #28a745 !important;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .small {
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .mt-4 { margin-top: 20px; }
        .mr-1 { margin-right: 5px; }
        .mr-2 { margin-right: 10px; }
        .mr-3 { margin-right: 15px; }
        .ml-1 { margin-left: 5px; }
        .ml-2 { margin-left: 10px; }
        .p-1 { padding: 5px; }
        .p-2 { padding: 10px; }
        .p-3 { padding: 15px; }
        .p-4 { padding: 20px; }

        /* Styles pour l'impression */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .invoice-container, .invoice-container * {
                visibility: visible;
            }
            
            .invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border-radius: 0;
                border: 1px solid #28a745;
            }
            
            .action-buttons, .btn-print, .btn-pdf {
                display: none !important;
            }
            
            .invoice-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #28a745 !important;
            }
            
            .total-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #28a745 !important;
            }
            
            .late-checkout-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: #fff3cd !important;
            }
            
            .table thead th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: #28a745 !important;
            }
            
            .payment-method-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                font-size: 12pt !important;
                padding: 0;
            }
            
            @page {
                margin: 0.5cm;
                size: A4;
            }
        }
    </style>
    
    <!-- Bibliothèque pour générer le PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <!-- Boutons d'action -->
    <div class="action-buttons no-print">
        <button class="btn-print mr-3" onclick="printInvoice()">
            <i class="fas fa-print mr-2"></i>Imprimer la Facture
        </button>
        <button class="btn-pdf" onclick="downloadPDF()">
            <i class="fas fa-file-pdf mr-2"></i>Télécharger en PDF
        </button>
    </div>

    <?php
        // Récupérer la transaction
        $transaction = $payment->transaction;
        
        // ✅ Récupérer TOUS les paiements complétés de la transaction
        $allPayments = $transaction->payments()
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // ✅ Calculer le total payé
        $totalPayments = $allPayments->sum('amount');
        
        // ✅ Calculer les montants détaillés
        $roomSubtotal    = $transaction->room->price * $transaction->nights;
        $restaurantOrders = $transaction->restaurantOrders->whereNotIn('status', ['paid', 'cancelled']);
        $restaurantTotal = $restaurantOrders->sum('total');
        $extrasTotal     = $transaction->extras->sum(fn($e) => $e->amount * $e->quantity);
        $grandTotal      = $transaction->getTotalPrice();
        
        // ✅ Vérifier s'il y a un late checkout
        $hasLateCheckout = $transaction->actual_check_out &&
                          \Carbon\Carbon::parse($transaction->actual_check_out)->gt(
                              \Carbon\Carbon::parse($transaction->check_out)
                          );
        
        // ✅ Calculer les frais de late checkout
        $extraCharge = 0;
        $extraChargeRate = '';
        $extraHours = 0;
        
        if ($hasLateCheckout) {
            $roomPrice = $transaction->room->price;
            $checkOutActual = \Carbon\Carbon::parse($transaction->actual_check_out);
            $checkOutOriginal = \Carbon\Carbon::parse($transaction->check_out);
            $extraHours = $checkOutActual->diffInHours($checkOutOriginal);
            
            // Politique de late checkout
            if ($extraHours > 0) {
                if ($extraHours <= 3) {
                    $extraCharge = 0;
                    $extraChargeRate = 'Gratuit (≤ 3h)';
                } elseif ($extraHours <= 6) {
                    $extraCharge = $roomPrice * 0.5;
                    $extraChargeRate = '50% du prix journalier';
                } else {
                    $extraCharge = $roomPrice;
                    $extraChargeRate = '100% du prix journalier (1 nuit)';
                }
            }
            
            // ✅ Ajouter les frais au total du séjour
            $grandTotal += $extraCharge;
        }

        // ✅ Calculer le solde restant
        $remaining = max(0, $grandTotal - $totalPayments);
        // ✅ Déterminer le statut global
        $isFullyPaid = $remaining <= 0;
    ?>

    <div class="invoice-container" id="invoice-content">
        <!-- En-tête de la facture -->
        <div class="invoice-header">
            <div class="row" style="display: flex; align-items: center;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center;">
                        <img src="<?php echo e(asset('img/logo/sip.png')); ?>" width="60" style="margin-right: 15px;">
                        <div>
                            <h1 style="font-size: 28px; font-weight: bold; margin: 0;">FACTURE</h1>
                            <p style="font-size: 14px; opacity: 0.9; margin: 5px 0 0;">N° INV-<?php echo e($transaction->id); ?></p>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <?php if($hasLateCheckout): ?>
                        <span class="status-badge status-late">
                            <i class="fas fa-clock mr-1"></i> DÉPART TARDIF
                        </span>
                    <?php else: ?>
                        <span class="status-badge <?php echo e($isFullyPaid ? 'status-paid' : 'status-pending'); ?>">
                            <?php echo e($isFullyPaid ? '✓ PAYÉ' : '⏱ EN ATTENTE'); ?>

                        </span>
                    <?php endif; ?>
                    <p style="font-size: 14px; opacity: 0.9; margin: 10px 0 0;">
                        Date d'émission : <?php echo e(date('d/m/Y')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Informations de l'hôtel -->
        <div class="p-3" style="border-bottom: 1px solid #dee2e6;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <h6 style="font-weight: bold; color: #28a745; margin: 0 0 10px;">CACTUS HOTEL</h6>
                    <p style="margin: 3px 0; font-size: 14px;">Haie Vive, Cotonou</p>
                    <p style="margin: 3px 0; font-size: 14px;">Bénin</p>
                    <p style="margin: 3px 0; font-size: 14px;">Tél : +229 XX XX XX XX</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 3px 0; font-size: 14px; color: #28a745;">
                        <strong>RCCM :</strong> BJ-COT-XXXX-XXXXX
                    </p>
                    <p style="margin: 3px 0; font-size: 14px; color: #28a745;">
                        <strong>NIF :</strong> XXXXXXXXX
                    </p>
                    <p style="margin: 3px 0; font-size: 14px; color: #28a745;">
                        <strong>Email :</strong> contact@lecactushotel.bj
                    </p>
                </div>
            </div>
        </div>

        <!-- Corps de la facture -->
        <div class="p-4">

            <!-- Informations de facturation -->
            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <h6 class="section-title">
                        <i class="fas fa-user"></i>
                        CLIENT
                    </h6>
                    <div class="info-box">
                        <p class="mb-2">
                            <strong style="color: #28a745;">ID Client :</strong> 
                            <?php echo e($transaction->customer->id); ?>

                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">Nom :</strong> 
                            <?php echo e($transaction->customer->name); ?>

                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">Profession :</strong> 
                            <?php echo e($transaction->customer->job ?? 'Non spécifié'); ?>

                        </p>
                        <p class="mb-0">
                            <strong style="color: #28a745;">Adresse :</strong> 
                            <?php echo e($transaction->customer->address ?? 'Non spécifié'); ?>

                        </p>
                    </div>
                </div>
                <div style="flex: 1;">
                    <h6 class="section-title">
                        <i class="fas fa-calendar"></i>
                        PÉRIODE DE SÉJOUR
                    </h6>
                    <div class="info-box">
                        <p class="mb-2">
                            <strong style="color: #28a745;">Arrivée :</strong> 
                            <?php echo e($transaction->check_in->format('d/m/Y H:i')); ?>

                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">Départ prévu :</strong> 
                            <?php echo e($transaction->check_out->format('d/m/Y H:i')); ?>

                        </p>
                        
                        <?php if($hasLateCheckout): ?>
                            <p class="mb-2">
                                <strong style="color: #dc3545;">Départ effectif :</strong> 
                                <?php echo e(\Carbon\Carbon::parse($transaction->actual_check_out)->format('d/m/Y H:i')); ?>

                            </p>
                            <p class="mb-0">
                                <strong style="color: #dc3545;">Dépassement :</strong> 
                                <?php if($extraHours > 24): ?>
                                    <?php echo e(floor($extraHours/24)); ?> jour(s) et <?php echo e($extraHours % 24); ?> heure(s)
                                <?php else: ?>
                                    <?php echo e($extraHours); ?> heure(s)
                                <?php endif; ?>
                            </p>
                        <?php else: ?>
                            <p class="mb-0">
                                <strong style="color: #28a745;">Durée :</strong> 
                                <?php echo e($transaction->getDateDifferenceWithPlural()); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Détails du séjour -->
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-bed"></i>
                    DÉTAILS DU SÉJOUR
                </h6>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-center">Prix/Jour</th>
                                <th class="text-center">Jours</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Chambre <?php echo e($transaction->room->number); ?> - 
                                    <?php echo e($transaction->room->type->name ?? 'Standard'); ?>

                                </td>
                                <td class="text-center">
                                    <?php echo e(number_format($transaction->room->price, 0, ',', ' ')); ?> FCFA
                                </td>
                                <td class="text-center"><?php echo e($transaction->getDateDifferenceWithPlural()); ?></td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    <?php echo e(number_format($transaction->room->price * $transaction->nights, 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                            
                            <!-- Frais de late checkout -->
                            <?php if($hasLateCheckout && $extraCharge > 0): ?>
                            <tr style="background-color: #fff3cd;">
                                <td colspan="4" class="p-0">
                                    <div class="late-checkout-box mb-0">
                                        <div class="late-checkout-title">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>FRAIS DE DÉPART TARDIF</span>
                                        </div>
                                        <div style="display: flex; margin-top: 10px;">
                                            <div style="flex: 1;">
                                                <small>Dépassement : <?php echo e($extraHours); ?> heure(s)</small><br>
                                                <small>Tarif appliqué : <?php echo e($extraChargeRate); ?></small>
                                            </div>
                                            <div style="text-align: right;">
                                                <strong style="font-size: 16px; color: #dc3545;">
                                                    + <?php echo e(number_format($extraCharge, 0, ',', ' ')); ?> FCFA
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Détails des commandes restaurant -->
            <?php
                $restaurantOrders = $transaction->restaurantOrders->whereNotIn('status', ['paid', 'cancelled']);
            ?>

            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-utensils"></i>
                    COMMANDES RESTAURANT
                </h6>

                <?php if($restaurantOrders->isNotEmpty()): ?>
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Commande</th>
                                    <th>Menu</th>
                                    <th class="text-center">Prix</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-right">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $restaurantOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>#<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                        <td><?php echo e($item->menu->name ?? 'Article'); ?></td>
                                        <td class="text-center"><?php echo e(number_format($item->price, 0, ',', ' ')); ?> FCFA</td>
                                        <td class="text-center"><?php echo e($item->quantity); ?></td>
                                        <td class="text-right"><?php echo e(number_format($item->price * $item->quantity, 0, ',', ' ')); ?> FCFA</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end"><strong>Total commande <?php echo e(strtoupper($order->status)); ?></strong></td>
                                        <td class="text-right"><strong><?php echo e(number_format($order->total, 0, ',', ' ')); ?> FCFA</strong></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>Total restaurant</strong></td>
                                    <td class="text-right"><strong><?php echo e(number_format($restaurantTotal, 0, ',', ' ')); ?> FCFA</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
                        <p class="mb-0 text-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Aucune commande restaurant enregistrée pour cette transaction.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Extras (minibar, lessive, services) -->
            <?php if($transaction->extras->isNotEmpty()): ?>
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-concierge-bell"></i>
                    EXTRAS & SERVICES
                </h6>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th class="text-center">Prix unit.</th>
                                <th class="text-center">Qté</th>
                                <th class="text-right">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $transaction->extras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><small><?php echo e($extra->category_label); ?></small></td>
                                <td><?php echo e($extra->description); ?></td>
                                <td class="text-center"><?php echo e(number_format($extra->amount, 0, ',', ' ')); ?> FCFA</td>
                                <td class="text-center"><?php echo e($extra->quantity); ?></td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    <?php echo e(number_format($extra->subtotal, 0, ',', ' ')); ?> FCFA
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr class="table-success">
                                <td colspan="4" class="text-end"><strong>Total extras</strong></td>
                                <td class="text-right"><strong><?php echo e(number_format($extrasTotal, 0, ',', ' ')); ?> FCFA</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- HISTORIQUE COMPLET DES PAIEMENTS -->
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-history"></i>
                    HISTORIQUE DES PAIEMENTS
                    <span style="margin-left: auto; font-size: 12px; color: #666;">
                        <?php echo e($allPayments->count()); ?> paiement(s)
                    </span>
                </h6>
                
                <?php if($allPayments->count() > 0): ?>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered payment-history-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Référence</th>
                                <th>Méthode</th>
                                <th class="text-right">Montant</th>
                                <th>Réceptionniste</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Déterminer la classe de la méthode
                                $methodClass = 'method-other';
                                $methodIcon = 'fa-money-bill-wave';
                                
                                switch($paymentItem->payment_method) {
                                    case 'cash':
                                        $methodClass = 'method-cash';
                                        $methodIcon = 'fa-money-bill-wave';
                                        break;
                                    case 'card':
                                        $methodClass = 'method-card';
                                        $methodIcon = 'fa-credit-card';
                                        break;
                                    case 'mobile_money':
                                        $methodClass = 'method-mobile';
                                        $methodIcon = 'fa-mobile-alt';
                                        break;
                                    case 'bank_transfer':
                                        $methodClass = 'method-bank';
                                        $methodIcon = 'fa-university';
                                        break;
                                    case 'fedapay':
                                        $methodClass = 'method-card';
                                        $methodIcon = 'fa-bolt';
                                        break;
                                }
                            ?>
                            <tr>
                                <td><?php echo e($paymentItem->created_at->format('d/m/Y H:i')); ?></td>
                                <td><small><?php echo e($paymentItem->reference); ?></small></td>
                                <td>
                                    <span class="payment-method-badge <?php echo e($methodClass); ?>">
                                        <i class="fas <?php echo e($methodIcon); ?> mr-1"></i>
                                        <?php echo e($paymentItem->payment_method_label ?? $paymentItem->payment_method); ?>

                                    </span>
                                </td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    <?php echo e(number_format($paymentItem->amount, 0, ',', ' ')); ?> FCFA
                                </td>
                                <td>
                                    <small><?php echo e($paymentItem->user->name ?? $paymentItem->createdBy->name ?? 'Système'); ?></small>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Ligne de total des paiements -->
                            <tr style="background-color: #e8f5e9; font-weight: bold;">
                                <td colspan="3" class="text-right">TOTAL PAYÉ</td>
                                <td class="text-right" style="color: #28a745; font-size: 16px;">
                                    <?php echo e(number_format($totalPayments, 0, ',', ' ')); ?> FCFA
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
                    <p class="mb-0 text-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Aucun paiement enregistré pour cette transaction
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Récapitulatif des montants -->
            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">🛏 Chambre</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            <?php echo e(number_format($roomSubtotal, 0, ',', ' ')); ?> FCFA
                        </p>
                    </div>
                </div>
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">🍽 Restaurant</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            <?php echo e(number_format($restaurantTotal, 0, ',', ' ')); ?> FCFA
                        </p>
                    </div>
                </div>
                <?php if($extrasTotal > 0): ?>
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">🔔 Extras</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            <?php echo e(number_format($extrasTotal, 0, ',', ' ')); ?> FCFA
                        </p>
                    </div>
                </div>
                <?php endif; ?>
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">📋 Total Facture</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            <?php echo e(number_format($grandTotal, 0, ',', ' ')); ?> FCFA
                        </p>
                        <?php if($hasLateCheckout && $extraCharge > 0): ?>
                        <small class="text-muted">(dont <?php echo e(number_format($extraCharge, 0, ',', ' ')); ?> FCFA de frais)</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Total et solde -->
            <div class="total-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0 0 5px;">RÉCAPITULATIF FINAL</h5>
                        <p style="margin: 0; opacity: 0.8; font-size: 13px;">
                            <?php if($hasLateCheckout && $extraCharge > 0): ?>
                                <i class="fas fa-info-circle mr-1"></i> Inclut les frais de late checkout
                            <?php else: ?>
                                Solde à payer
                            <?php endif; ?>
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <h2 style="margin: 0 0 5px; font-size: 28px;">
                            <?php if($remaining <= 0): ?>
                                <?php echo e(number_format(0, 0, ',', ' ')); ?> FCFA
                            <?php else: ?>
                                <?php echo e(number_format($remaining, 0, ',', ' ')); ?> FCFA
                            <?php endif; ?>
                        </h2>
                        <p style="margin: 0; opacity: 0.8; font-size: 13px;">
                            <?php if($remaining <= 0): ?>
                                <i class="fas fa-check-circle mr-1"></i> Facture entièrement réglée
                            <?php else: ?>
                                Reste à payer avant le départ
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes et informations importantes -->
            <div class="mt-4 p-3" style="border: 1px solid #28a745; border-radius: 8px;">
                <h6 class="section-title mb-3">INFORMATIONS IMPORTANTES</h6>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <p class="small mb-2"><strong style="color: #28a745;">Conditions de paiement :</strong></p>
                        <ul class="small" style="padding-left: 20px; margin-bottom: 0;">
                            <li>Acompte minimum de 30% à la réservation</li>
                            <li>Solde à régler à l'arrivée ou au départ</li>
                            <li>Frais d'annulation : voir conditions générales</li>
                        </ul>
                    </div>
                    <div style="flex: 1;">
                        <p class="small mb-2"><strong style="color: #28a745;">Moyens de paiement acceptés :</strong></p>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            <span class="payment-method-badge method-cash">Espèces</span>
                            <span class="payment-method-badge method-card">Carte</span>
                            <span class="payment-method-badge method-mobile">Mobile Money</span>
                            <span class="payment-method-badge method-bank">Virement</span>
                        </div>
                    </div>
                </div>
                
                <!-- Politique de late checkout -->
                <div class="mt-3">
                    <p class="small mb-2"><strong style="color: #28a745;">Politique de départ tardif :</strong></p>
                    <ul class="small" style="padding-left: 20px; margin-bottom: 0;">
                        <li>< 3 heures : Gratuit</li>
                        <li>3 - 6 heures : 50% du prix journalier</li>
                        <li>> 6 heures : 100% du prix journalier (1 nuit supplémentaire)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="p-3" style="border-top: 2px solid #28a745;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p class="small text-muted mb-0">
                        <strong style="color: #28a745;">Signature et cachet :</strong><br>
                        <span style="margin-top: 30px; display: inline-block; border-top: 1px solid #28a745; padding-top: 8px; width: 150px;">
                            <?php echo e($transaction->user->name ?? '____________________'); ?>

                        </span>
                    </p>
                </div>
                <div style="text-align: right;">
                    <p class="small mb-0" style="color: #28a745;">
                        Merci de votre confiance.<br>
                        Nous vous souhaitons un agréable séjour !
                    </p>
                </div>
            </div>
            <div class="mt-3 text-center">
                <p class="small mb-0" style="color: #28a745;">
                    CACTUS HOTEL • Haie Vive • Cotonou, Bénin • Tél : +229 XX XX XX XX
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour imprimer la facture
function printInvoice() {
    window.print();
}

// Fonction pour télécharger en PDF
function downloadPDF() {
    const element = document.getElementById('invoice-content');
    
    // Options pour le PDF
    const opt = {
        margin:       0.5,
        filename:     'Facture_INV-<?php echo e($transaction->id); ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { 
            scale: 2,
            useCORS: true,
            logging: false,
            backgroundColor: '#FFFFFF'
        },
        jsPDF:        { 
            unit: 'in', 
            format: 'a4', 
            orientation: 'portrait' 
        }
    };

    // Afficher un message pendant la génération
    const loadingMessage = document.createElement('div');
    loadingMessage.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 20px 30px;
        border-radius: 10px;
        z-index: 9999;
        font-family: 'Maven Pro', sans-serif;
    `;
    loadingMessage.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Génération du PDF en cours...';
    document.body.appendChild(loadingMessage);

    // Générer le PDF
    html2pdf().set(opt).from(element).save().then(() => {
        document.body.removeChild(loadingMessage);
        
        // Notification de succès
        const successMessage = document.createElement('div');
        successMessage.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            z-index: 9999;
            font-family: 'Maven Pro', sans-serif;
        `;
        successMessage.innerHTML = '<i class="fas fa-check mr-2"></i> PDF téléchargé avec succès !';
        document.body.appendChild(successMessage);
        
        setTimeout(() => {
            document.body.removeChild(successMessage);
        }, 2000);
    });
}

// Gestion des événements clavier pour l'impression (Ctrl+P)
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        printInvoice();
    }
});
</script>

<!-- Ajout d'icônes FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.invoicemaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/payment/invoice.blade.php ENDPATH**/ ?>