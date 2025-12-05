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

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .invoice-subtitle {
            font-size: 14px;
            opacity: 0.9;
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

        .table th {
            border-top: none;
            font-weight: 600;
            color: #555;
        }

        .amount {
            font-weight: bold;
            font-size: 18px;
        }
    </style>
@endsection
@section('content')

<div class="container py-5">
    <div class="invoice-container">
        <!-- En-tête de la facture -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/logo/sip.png') }}" width="60" class="mr-3">
                        <div>
                            <h1 class="invoice-title">FACTURE</h1>
                            <p class="invoice-subtitle mb-0">N° INV-{{ $payment->id }}</p>
                            <p class="invoice-subtitle mb-0">{{ date('d/m/Y', strtotime($payment->created_at)) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <span class="status-badge {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'status-paid' : 'status-pending' }}">
                        {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 'PAYÉ' : 'EN ATTENTE' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Corps de la facture -->
        <div class="p-4">
            <!-- Informations de facturation -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="section-title">CLIENT</h6>
                        <p class="mb-1"><strong>ID Client :</strong> {{ $payment->transaction->customer->id }}</p>
                        <p class="mb-1"><strong>Nom :</strong> {{ $payment->transaction->customer->name }}</p>
                        <p class="mb-1"><strong>Profession :</strong> {{ $payment->transaction->customer->job }}</p>
                        <p class="mb-0"><strong>Adresse :</strong> {{ $payment->transaction->customer->address }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="section-title">PÉRIODE DE SÉJOUR</h6>
                        <p class="mb-1"><strong>Arrivée :</strong> {{ Helper::dateDayFormat($payment->transaction->check_in) }}</p>
                        <p class="mb-1"><strong>Départ :</strong> {{ Helper::dateDayFormat($payment->transaction->check_out) }}</p>
                        <p class="mb-0"><strong>Durée :</strong> {{ $payment->transaction->getDateDifferenceWithPlural() }}</p>
                    </div>
                </div>
            </div>

            <!-- Détails du séjour -->
            <div class="mb-4">
                <h6 class="section-title">DÉTAILS DU SÉJOUR</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Chambre</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Prix/Jour</th>
                                <th class="text-center">Jours</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Chambre {{ $payment->transaction->room->number }}</td>
                                <td class="text-center">{{ $payment->transaction->room->type->name }}</td>
                                <td class="text-center">{{ Helper::convertToRupiah($payment->transaction->room->price) }}</td>
                                <td class="text-center">{{ $payment->transaction->getDateDifferenceWithPlural() }}</td>
                                <td class="text-right amount">{{ Helper::convertToRupiah($payment->transaction->getTotalPrice()) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Récapitulatif des paiements -->
            <div class="mb-4">
                <h6 class="section-title">RÉCAPITULATIF DES PAIEMENTS</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Montant Total</p>
                            <p class="mb-0 amount text-primary">{{ Helper::convertToRupiah($payment->transaction->getTotalPrice()) }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Acompte Minimum</p>
                            <p class="mb-0 amount">{{ Helper::convertToRupiah($payment->transaction->getMinimumDownPayment()) }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box text-center">
                            <p class="mb-1 text-muted">Montant Payé</p>
                            <p class="mb-0 amount text-success">{{ Helper::convertToRupiah($payment->price) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total et solde -->
            <div class="total-box">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0">Solde Restant</h5>
                        <p class="mb-0 opacity-75">Montant à régler</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h2 class="mb-0">
                            {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 
                               Helper::convertToRupiah(0) : 
                               Helper::convertToRupiah($payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment()) }}
                        </h2>
                        <p class="mb-0 opacity-75">
                            {{ $payment->transaction->getTotalPrice() - $payment->transaction->getTotalPayment() <= 0 ? 
                               'Facture réglée' : 
                               'À régler avant le départ' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes et conditions -->
            <div class="mt-4">
                <h6 class="section-title">CONDITIONS DE PAIEMENT</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-0">
                            <strong>Moyens de paiement acceptés :</strong><br>
                            Espèces, Carte bancaire, Virement
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="text-muted small mb-0">
                            <strong>Date d'émission :</strong> {{ date('d/m/Y') }}<br>
                            <strong>Heure d'émission :</strong> {{ date('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="p-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="small text-muted mb-0">
                        &copy; {{ date('Y') }} SIP Hotel. Tous droits réservés.<br>
                        Merci de votre confiance.
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="small text-muted mb-0">
                        Pour toute question concernant cette facture,<br>
                        contactez-nous au : +XX XXX XXX XXX
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection