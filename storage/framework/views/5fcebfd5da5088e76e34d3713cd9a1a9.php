
<?php $__env->startSection('title', 'Facture de Paiement'); ?>
<?php $__env->startSection('head'); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Maven+Pro&display=swap');

        body {
            font-family: 'Maven Pro', sans-serif;
            background-color: #f8f9fa;
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
        }

        .btn-print {
            background: #28a745;
            color: white;
            border: none;
        }

        .btn-print:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .btn-pdf {
            background: #28a745;
            color: white;
            border: none;
        }

        .btn-pdf:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .table thead th {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }

        .table-bordered {
            border-color: #28a745 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: #dee2e6;
        }

        .status-available {
            background: #28a745;
            color: white;
        }

        .text-primary {
            color: #28a745 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .border-top {
            border-top-color: #28a745 !important;
        }

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
            
            .table thead th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: #28a745 !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                font-size: 12pt !important;
            }
            
            @page {
                margin: 0.5cm;
                size: A4;
            }
        }
        
        /* Style pour l'affichage des montants en CFA */
        .currency {
            font-size: 14px;
            font-weight: normal;
        }
    </style>
    
    <!-- Bibliothèque pour générer le PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container py-5">
    <!-- Boutons d'action -->
    <div class="action-buttons no-print">
        <button class="btn btn-print mr-3" onclick="printInvoice()">
            <i class="fas fa-print mr-2"></i>Imprimer la Facture
        </button>
        <button class="btn btn-pdf" onclick="downloadPDF()">
            <i class="fas fa-file-pdf mr-2"></i>Télécharger en PDF
        </button>
    </div>

    <div class="invoice-container" id="invoice-content">
        <!-- En-tête de la facture -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e(asset('img/logo/sip.png')); ?>" width="60" class="mr-3">
                        <div>
                            <h1 class="invoice-title mb-1" style="font-size: 28px; font-weight: bold;">FACTURE</h1>
                            <p class="invoice-subtitle mb-0" style="font-size: 14px; opacity: 0.9;">N° INV-<?php echo e($payment->id); ?></p>
                            <p class="invoice-subtitle mb-0" style="font-size: 14px; opacity: 0.9;"><?php echo e(date('d/m/Y', strtotime($payment->created_at))); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="status-badge <?php echo e($payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'status-paid' : 'status-pending'); ?>">
                        <?php echo e($payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'PAYÉ' : 'EN ATTENTE'); ?>

                    </span>
                    <p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.9;">Date d'émission : <?php echo e(date('d/m/Y')); ?></p>
                </div>
            </div>
        </div>

        <!-- Informations de l'hôtel -->
        <div class="p-3 border-bottom">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2" style="font-weight: bold; color: #28a745;">CACTUS HOTEL</h6>
                    <p class="mb-1" style="font-size: 14px;">Haie Vive, Cotonou</p>
                    <p class="mb-1" style="font-size: 14px;">Bénin</p>
                    <p class="mb-0" style="font-size: 14px;">Tél : +229 XX XX XX XX</p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="mb-1" style="font-size: 14px; color: #28a745;"><strong>RCCM :</strong> BJ-COT-XXXX-XXXXX</p>
                    <p class="mb-1" style="font-size: 14px; color: #28a745;"><strong>NIF :</strong> XXXXXXXXX</p>
                    <p class="mb-0" style="font-size: 14px; color: #28a745;"><strong>Email :</strong> contact@lecactushotel.bj</p>
                </div>
            </div>
        </div>

        <!-- Corps de la facture -->
        <div class="p-4">
            <!-- Informations de facturation -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="section-title">CLIENT</h6>
                    <div class="info-box">
                        <p class="mb-2"><strong style="color: #28a745;">ID Client :</strong> <?php echo e($payment->transaction->customer->id); ?></p>
                        <p class="mb-2"><strong style="color: #28a745;">Nom :</strong> <?php echo e($payment->transaction->customer->name); ?></p>
                        <p class="mb-2"><strong style="color: #28a745;">Profession :</strong> <?php echo e($payment->transaction->customer->job); ?></p>
                        <p class="mb-0"><strong style="color: #28a745;">Adresse :</strong> <?php echo e($payment->transaction->customer->address); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="section-title">PÉRIODE DE SÉJOUR</h6>
                    <div class="info-box">
                        <p class="mb-2"><strong style="color: #28a745;">Arrivée :</strong> <?php echo e(Helper::dateDayFormat($payment->transaction->check_in)); ?></p>
                        <p class="mb-2"><strong style="color: #28a745;">Départ :</strong> <?php echo e(Helper::dateDayFormat($payment->transaction->check_out)); ?></p>
                        <p class="mb-0"><strong style="color: #28a745;">Durée :</strong> <?php echo e($payment->transaction->getDateDifferenceWithPlural()); ?></p>
                    </div>
                </div>
            </div>

            <!-- Détails du séjour -->
            <div class="mb-4">
                <h6 class="section-title">DÉTAILS DU SÉJOUR</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="background-color: #28a745 !important; color: white !important;">Description</th>
                                <th class="text-center" style="background-color: #28a745 !important; color: white !important;">Prix/Jour</th>
                                <th class="text-center" style="background-color: #28a745 !important; color: white !important;">Jours</th>
                                <th class="text-right" style="background-color: #28a745 !important; color: white !important;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chambre <?php echo e($payment->transaction->room->number); ?> - <?php echo e($payment->transaction->room->type->name); ?></td>
                                <td class="text-center">
                                    <?php echo e(Helper::formatCFA($payment->transaction->room->price)); ?>

                                </td>
                                <td class="text-center"><?php echo e($payment->transaction->getDateDifferenceWithPlural()); ?></td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    <?php echo e(Helper::formatCFA($payment->transaction->getTotalPrice())); ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Récapitulatif des paiements -->
            <div class="mb-4">
                <h6 class="section-title">RÉCAPITULATIF DES PAIEMENTS</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Total Séjour</p>
                            <p class="mb-0 amount" style="color: #28a745;">
                                <?php echo e(Helper::formatCFA($payment->transaction->getTotalPrice())); ?>

                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Acompte Requis</p>
                            <p class="mb-0 amount" style="color: #28a745;">
                                <?php echo e(Helper::formatCFA($payment->transaction->getMinimumDownPayment())); ?>

                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Montant Payé</p>
                            <p class="mb-0 amount" style="color: #28a745;">
                                <?php echo e(Helper::formatCFA($payment->price)); ?>

                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Total Payé à ce jour</p>
                            <p class="mb-0 amount" style="color: #28a745;">
                                <?php echo e(Helper::formatCFA($payment->transaction->getTotalPayment())); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total et solde -->
            <div class="total-box">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-1">SOLDE À PAYER</h5>
                        <p class="mb-0 opacity-75">Montant restant dû</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 class="mb-1">
                            <?php
                                $balance = $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment();
                            ?>
                            <?php if($balance <= 0): ?>
                                <?php echo e(Helper::formatCFA(0)); ?>

                            <?php else: ?>
                                <?php echo e(Helper::formatCFA($balance)); ?>

                            <?php endif; ?>
                        </h2>
                        <p class="mb-0 opacity-75">
                            <?php echo e($payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 
                               '✓ Facture entièrement réglée' : 
                               'À régler avant le départ'); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-4 p-3 border rounded" style="border-color: #28a745 !important;">
                <h6 class="section-title mb-3">INFORMATIONS IMPORTANTES</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="small mb-2"><strong style="color: #28a745;">Conditions de paiement :</strong></p>
                        <ul class="small pl-3 mb-0">
                            <li>Acompte minimum de 30% à la réservation</li>
                            <li>Solde à régler à l'arrivée ou au départ</li>
                            <li>Frais d'annulation : voir conditions générales</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="small mb-2"><strong style="color: #28a745;">Moyens de paiement acceptés :</strong></p>
                        <ul class="small pl-3 mb-0">
                            <li>Espèces (FCFA)</li>
                            <li>Carte bancaire</li>
                            <li>Virement bancaire</li>
                            <li>Mobile money (Moov Money, MTN Mobile Money)</li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="small mb-0"><strong style="color: #28a745;">Note :</strong> Tous les montants sont exprimés en Francs CFA (FCFA)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="p-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="small text-muted mb-0">
                        <strong style="color: #28a745;">Signature et cachet :</strong><br>
                        <span style="margin-top: 50px; display: inline-block; border-top: 1px solid #28a745; padding-top: 10px;">_________________________</span>
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="small mb-0" style="color: #28a745;">
                        Merci de votre confiance.<br>
                        Nous vous souhaitons un agréable séjour !
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="small mb-0" style="color: #28a745;">
                        CACTUS HOTEL • Haie Vive • Cotonou, Bénin • Tél : +229 XX XX XX XX • contact@lecactushotel.bj
                    </p>
                </div>
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
        filename:     'Facture_INV-<?php echo e($payment->id); ?>.pdf',
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

// Afficher un message pour le mode impression
window.addEventListener('beforeprint', function() {
    console.log('Mode impression activé');
});

window.addEventListener('afterprint', function() {
    console.log('Mode impression terminé');
});
</script>

<!-- Ajout d'icônes FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.invoicemaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/payment/invoice.blade.php ENDPATH**/ ?>