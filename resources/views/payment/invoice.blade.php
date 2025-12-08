@extends('template.invoicemaster')
@section('title', 'Facture de Paiement')
@section('head')
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
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .total-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        /* Boutons d'action */
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
            background: #667eea;
            color: white;
            border: none;
        }

        .btn-print:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-pdf {
            background: #e53e3e;
            color: white;
            border: none;
        }

        .btn-pdf:hover {
            background: #c53030;
            transform: translateY(-2px);
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
            }
            
            .action-buttons, .btn-print, .btn-pdf {
                display: none !important;
            }
            
            .invoice-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .total-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
    </style>
    
    <!-- Bibliothèque pour générer le PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@endsection

@section('content')

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
                        <img src="{{ asset('img/logo/sip.png') }}" width="60" class="mr-3">
                        <div>
                            <h1 class="invoice-title mb-1" style="font-size: 28px; font-weight: bold;">FACTURE</h1>
                            <p class="invoice-subtitle mb-0" style="font-size: 14px; opacity: 0.9;">N° INV-{{ $payment->id }}</p>
                            <p class="invoice-subtitle mb-0" style="font-size: 14px; opacity: 0.9;">{{ date('d/m/Y', strtotime($payment->created_at)) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="status-badge {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'status-paid' : 'status-pending' }}">
                        {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'PAYÉ' : 'EN ATTENTE' }}
                    </span>
                    <p class="mt-2 mb-0" style="font-size: 14px; opacity: 0.9;">Date d'émission : {{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Informations de l'hôtel -->
        <div class="p-3 border-bottom">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2" style="font-weight: bold;">SIP HOTEL</h6>
                    <p class="mb-1" style="font-size: 14px;">123 Avenue de l'Hôtel</p>
                    <p class="mb-1" style="font-size: 14px;">75000 Paris, France</p>
                    <p class="mb-0" style="font-size: 14px;">Tél : +33 1 23 45 67 89</p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="mb-1" style="font-size: 14px;">SIRET : 123 456 789 00012</p>
                    <p class="mb-1" style="font-size: 14px;">TVA : FR 12 345 678 901</p>
                    <p class="mb-0" style="font-size: 14px;">Email : contact@siphotel.com</p>
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
                        <p class="mb-2"><strong>ID Client :</strong> {{ $payment->transaction->customer->id }}</p>
                        <p class="mb-2"><strong>Nom :</strong> {{ $payment->transaction->customer->name }}</p>
                        <p class="mb-2"><strong>Profession :</strong> {{ $payment->transaction->customer->job }}</p>
                        <p class="mb-0"><strong>Adresse :</strong> {{ $payment->transaction->customer->address }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="section-title">PÉRIODE DE SÉJOUR</h6>
                    <div class="info-box">
                        <p class="mb-2"><strong>Arrivée :</strong> {{ Helper::dateDayFormat($payment->transaction->check_in) }}</p>
                        <p class="mb-2"><strong>Départ :</strong> {{ Helper::dateDayFormat($payment->transaction->check_out) }}</p>
                        <p class="mb-0"><strong>Durée :</strong> {{ $payment->transaction->getDateDifferenceWithPlural() }}</p>
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
                                <th>Description</th>
                                <th class="text-center">Prix/Jour</th>
                                <th class="text-center">Jours</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chambre {{ $payment->transaction->room->number }} - {{ $payment->transaction->room->type->name }}</td>
                                <td class="text-center">{{ Helper::convertToRupiah($payment->transaction->room->price) }}</td>
                                <td class="text-center">{{ $payment->transaction->getDateDifferenceWithPlural() }}</td>
                                <td class="text-right font-weight-bold">{{ Helper::convertToRupiah($payment->transaction->getTotalPrice()) }}</td>
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
                            <p class="mb-0 amount text-primary">{{ Helper::convertToRupiah($payment->transaction->getTotalPrice()) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Acompte Requis</p>
                            <p class="mb-0 amount">{{ Helper::convertToRupiah($payment->transaction->getMinimumDownPayment()) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Montant Payé</p>
                            <p class="mb-0 amount text-success">{{ Helper::convertToRupiah($payment->price) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Total Payé à ce jour</p>
                            <p class="mb-0 amount">{{ Helper::convertToRupiah($payment->transaction->getTotalPayment()) }}</p>
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
                            {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 
                               Helper::convertToRupiah(0) : 
                               Helper::convertToRupiah($payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment()) }}
                        </h2>
                        <p class="mb-0 opacity-75">
                            {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 
                               '✓ Facture entièrement réglée' : 
                               'À régler avant le départ' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-4 p-3 border rounded">
                <h6 class="section-title mb-3">INFORMATIONS IMPORTANTES</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="small mb-2"><strong>Conditions de paiement :</strong></p>
                        <ul class="small pl-3 mb-0">
                            <li>Acompte minimum de 30% à la réservation</li>
                            <li>Solde à régler à l'arrivée ou au départ</li>
                            <li>Frais d'annulation : voir conditions générales</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <p class="small mb-2"><strong>Moyens de paiement acceptés :</strong></p>
                        <ul class="small pl-3 mb-0">
                            <li>Espèces (€)</li>
                            <li>Carte bancaire</li>
                            <li>Virement bancaire</li>
                            <li>Chèque (uniquement pour les résidents français)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="p-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="small text-muted mb-0">
                        <strong>Signature et cachet :</strong><br>
                        <span style="margin-top: 50px; display: inline-block;">_________________________</span>
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="small text-muted mb-0">
                        Merci de votre confiance.<br>
                        Nous vous souhaitons un agréable séjour !
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="small text-muted mb-0">
                        SIP Hotel • 123 Avenue de l'Hôtel • 75000 Paris • Tél : +33 1 23 45 67 89 • contact@siphotel.com
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
        filename:     'Facture_INV-{{ $payment->id }}.pdf',
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
        background: rgba(0,0,0,0.8);
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

@endsection