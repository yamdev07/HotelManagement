@extends('template.invoicemaster')

@section('title', __('payment.invoice_title'))

@section('head')
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
@endsection

@section('content')
<div class="container py-5">
    <!-- Boutons d'action -->
    <div class="action-buttons no-print">
        <button class="btn-print mr-3" onclick="printInvoice()">
            <i class="fas fa-print mr-2"></i>{{ __('payment.invoice_print') }}
        </button>
        <button class="btn-pdf" onclick="downloadPDF()">
            <i class="fas fa-file-pdf mr-2"></i>{{ __('payment.invoice_download') }}
        </button>
    </div>

    @php
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
                    $extraChargeRate = __('payment.invoice_late_rate_free');
                } elseif ($extraHours <= 6) {
                    $extraCharge = $roomPrice * 0.5;
                    $extraChargeRate = __('payment.invoice_late_rate_50');
                } else {
                    $extraCharge = $roomPrice;
                    $extraChargeRate = __('payment.invoice_late_rate_100');
                }
            }
            
            // ✅ Ajouter les frais au total du séjour
            $grandTotal += $extraCharge;
        }

        // ✅ Calculer le solde restant
        $remaining = max(0, $grandTotal - $totalPayments);
        // ✅ Déterminer le statut global
        $isFullyPaid = $remaining <= 0;
    @endphp

    <div class="invoice-container" id="invoice-content">
        <!-- En-tête de la facture -->
        <div class="invoice-header">
            <div class="row" style="display: flex; align-items: center;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center;">
                        <img src="{{ asset('img/logo/sip.png') }}" width="60" style="margin-right: 15px;">
                        <div>
                            <h1 style="font-size: 28px; font-weight: bold; margin: 0;">FACTURE</h1>
                            <p style="font-size: 14px; opacity: 0.9; margin: 5px 0 0;">{{ __('payment.invoice_number', ['id' => $transaction->id]) }}</p>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;">
                    @if($hasLateCheckout)
                        <span class="status-badge status-late">
                            <i class="fas fa-clock mr-1"></i> {{ __('payment.invoice_status_late') }}
                        </span>
                    @else
                        <span class="status-badge {{ $isFullyPaid ? 'status-paid' : 'status-pending' }}">
                            {{ $isFullyPaid ? __('payment.invoice_status_paid') : __('payment.invoice_status_pending') }}
                        </span>
                    @endif
                    <p style="font-size: 14px; opacity: 0.9; margin: 10px 0 0;">
                        {{ __('payment.invoice_emission_date') }} {{ date('d/m/Y') }}
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
                        {{ __('payment.invoice_client') }}
                    </h6>
                    <div class="info-box">
                        <p class="mb-2">
                            <strong style="color: #28a745;">{{ __('payment.invoice_client_id') }}</strong> 
                            {{ $transaction->customer->id }}
                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">{{ __('payment.invoice_name') }}</strong> 
                            {{ $transaction->customer->name }}
                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">{{ __('payment.invoice_profession') }}</strong> 
                            {{ $transaction->customer->job ?? __('payment.invoice_not_specified') }}
                        </p>
                        <p class="mb-0">
                            <strong style="color: #28a745;">{{ __('payment.invoice_address') }}</strong> 
                            {{ $transaction->customer->address ?? __('payment.invoice_not_specified') }}
                        </p>
                    </div>
                </div>
                <div style="flex: 1;">
                    <h6 class="section-title">
                        <i class="fas fa-calendar"></i>
                        {{ __('payment.invoice_stay_period') }}
                    </h6>
                    <div class="info-box">
                        <p class="mb-2">
                            <strong style="color: #28a745;">{{ __('payment.invoice_arrival') }}</strong> 
                            {{ $transaction->check_in->format('d/m/Y H:i') }}
                        </p>
                        <p class="mb-2">
                            <strong style="color: #28a745;">{{ __('payment.invoice_checkout_planned') }}</strong> 
                            {{ $transaction->check_out->format('d/m/Y H:i') }}
                        </p>
                        
                        @if($hasLateCheckout)
                            <p class="mb-2">
                                <strong style="color: #dc3545;">{{ __('payment.invoice_checkout_actual') }}</strong> 
                                {{ \Carbon\Carbon::parse($transaction->actual_check_out)->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-0">
                                <strong style="color: #dc3545;">{{ __('payment.invoice_exceeded') }}</strong> 
                                @if($extraHours > 24)
                                    {{ floor($extraHours/24) }} jour(s) et {{ $extraHours % 24 }} heure(s)
                                @else
                                    {{ $extraHours }} heure(s)
                                @endif
                            </p>
                        @else
                            <p class="mb-0">
                                <strong style="color: #28a745;">{{ __('payment.invoice_duration') }}</strong> 
                                {{ $transaction->getDateDifferenceWithPlural() }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Détails du séjour -->
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-bed"></i>
                    {{ __('payment.invoice_stay_details') }}
                </h6>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('payment.invoice_table_description') }}</th>
                                <th class="text-center">{{ __('payment.invoice_table_price_per_day') }}</th>
                                <th class="text-center">{{ __('payment.invoice_table_days') }}</th>
                                <th class="text-right">{{ __('payment.invoice_table_total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ __('payment.invoice_room_label') }} {{ $transaction->room->number }} - 
                                    {{ $transaction->room->type->name ?? 'Standard' }}
                                </td>
                                <td class="text-center">
                                    {{ number_format($transaction->room->price, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="text-center">{{ $transaction->getDateDifferenceWithPlural() }}</td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    {{ number_format($transaction->room->price * $transaction->nights, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            
                            <!-- Frais de late checkout -->
                            @if($hasLateCheckout && $extraCharge > 0)
                            <tr style="background-color: #fff3cd;">
                                <td colspan="4" class="p-0">
                                    <div class="late-checkout-box mb-0">
                                        <div class="late-checkout-title">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>{{ __('payment.invoice_late_fee') }}</span>
                                        </div>
                                        <div style="display: flex; margin-top: 10px;">
                                            <div style="flex: 1;">
                                                <small>{{ __('payment.invoice_late_exceeded') }} {{ $extraHours }} heure(s)</small><br>
                                                <small>{{ __('payment.invoice_late_rate') }} {{ $extraChargeRate }}</small>
                                            </div>
                                            <div style="text-align: right;">
                                                <strong style="font-size: 16px; color: #dc3545;">
                                                    + {{ number_format($extraCharge, 0, ',', ' ') }} FCFA
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Détails des commandes restaurant -->
            @php
                $restaurantOrders = $transaction->restaurantOrders->whereNotIn('status', ['paid', 'cancelled']);
            @endphp

            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-utensils"></i>
                    {{ __('payment.invoice_restaurant') }}
                </h6>

                @if($restaurantOrders->isNotEmpty())
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('payment.invoice_restaurant_table_order') }}</th>
                                    <th>{{ __('payment.invoice_restaurant_table_menu') }}</th>
                                    <th class="text-center">{{ __('payment.invoice_restaurant_table_price') }}</th>
                                    <th class="text-center">{{ __('payment.invoice_restaurant_table_qty') }}</th>
                                    <th class="text-right">{{ __('payment.invoice_restaurant_table_subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurantOrders as $order)
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $item->menu->name ?? 'Article' }}</td>
                                        <td class="text-center">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end"><strong>{{ __('payment.invoice_restaurant_total_order') }} {{ strtoupper($order->status) }}</strong></td>
                                        <td class="text-right"><strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></td>
                                    </tr>
                                @endforeach
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>{{ __('payment.invoice_restaurant_total') }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($restaurantTotal, 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
                        <p class="mb-0 text-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ __('payment.invoice_restaurant_empty') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Extras (minibar, lessive, services) -->
            @if($transaction->extras->isNotEmpty())
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-concierge-bell"></i>
                    {{ __('payment.invoice_extras') }}
                </h6>
                <div style="overflow-x: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('payment.invoice_extras_table_category') }}</th>
                                <th>{{ __('payment.invoice_extras_table_description') }}</th>
                                <th class="text-center">{{ __('payment.invoice_extras_table_unit_price') }}</th>
                                <th class="text-center">{{ __('payment.invoice_extras_table_qty') }}</th>
                                <th class="text-right">{{ __('payment.invoice_extras_table_subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->extras as $extra)
                            <tr>
                                <td><small>{{ $extra->category_label }}</small></td>
                                <td>{{ $extra->description }}</td>
                                <td class="text-center">{{ number_format($extra->amount, 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">{{ $extra->quantity }}</td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    {{ number_format($extra->subtotal, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            @endforeach
                            <tr class="table-success">
                                <td colspan="4" class="text-end"><strong>{{ __('payment.invoice_extras_total') }}</strong></td>
                                <td class="text-right"><strong>{{ number_format($extrasTotal, 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- HISTORIQUE COMPLET DES PAIEMENTS -->
            <div class="mb-4">
                <h6 class="section-title">
                    <i class="fas fa-history"></i>
                    {{ __('payment.invoice_payment_history') }}
                    <span style="margin-left: auto; font-size: 12px; color: #666;">
                        {{ __('payment.invoice_payment_count', ['count' => $allPayments->count()]) }}
                    </span>
                </h6>
                
                @if($allPayments->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-bordered payment-history-table">
                        <thead>
                            <tr>
                                <th>{{ __('payment.invoice_payment_table_date') }}</th>
                                <th>{{ __('payment.invoice_payment_table_reference') }}</th>
                                <th>{{ __('payment.invoice_payment_table_method') }}</th>
                                <th class="text-right">{{ __('payment.invoice_payment_table_amount') }}</th>
                                <th>{{ __('payment.invoice_payment_table_receptionist') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allPayments as $paymentItem)
                            @php
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
                            @endphp
                            <tr>
                                <td>{{ $paymentItem->created_at->format('d/m/Y H:i') }}</td>
                                <td><small>{{ $paymentItem->reference }}</small></td>
                                <td>
                                    <span class="payment-method-badge {{ $methodClass }}">
                                        <i class="fas {{ $methodIcon }} mr-1"></i>
                                        {{ $paymentItem->payment_method_label ?? $paymentItem->payment_method }}
                                    </span>
                                </td>
                                <td class="text-right font-weight-bold" style="color: #28a745;">
                                    {{ number_format($paymentItem->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td>
                                    <small>{{ $paymentItem->user->name ?? $paymentItem->createdBy->name ?? 'Système' }}</small>
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Ligne de total des paiements -->
                            <tr style="background-color: #e8f5e9; font-weight: bold;">
                                <td colspan="3" class="text-right">{{ __('payment.invoice_pay_total') }}</td>
                                <td class="text-right" style="color: #28a745; font-size: 16px;">
                                    {{ number_format($totalPayments, 0, ',', ' ') }} FCFA
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
                    <p class="mb-0 text-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ __('payment.invoice_pay_empty') }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Récapitulatif des montants -->
            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">{{ __('payment.invoice_summary_room') }}</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            {{ number_format($roomSubtotal, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">{{ __('payment.invoice_summary_restaurant') }}</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            {{ number_format($restaurantTotal, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
                @if($extrasTotal > 0)
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">{{ __('payment.invoice_summary_extras') }}</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            {{ number_format($extrasTotal, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
                @endif
                <div style="flex: 1; min-width: 140px; text-align: center;">
                    <div class="info-box">
                        <p class="mb-1 text-muted">{{ __('payment.invoice_summary_total') }}</p>
                        <p class="mb-0 amount" style="color: #28a745;">
                            {{ number_format($grandTotal, 0, ',', ' ') }} FCFA
                        </p>
                        @if($hasLateCheckout && $extraCharge > 0)
                        <small class="text-muted">{{ __('payment.invoice_summary_late_fees', ['amount' => number_format($extraCharge, 0, ',', ' ')]) }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Total et solde -->
            <div class="total-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0 0 5px;">{{ __('payment.invoice_final') }}</h5>
                        <p style="margin: 0; opacity: 0.8; font-size: 13px;">
                            @if($hasLateCheckout && $extraCharge > 0)
                                <i class="fas fa-info-circle mr-1"></i> {{ __('payment.invoice_final_late') }}
                            @else
                                {{ __('payment.invoice_final_balance') }}
                            @endif
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <h2 style="margin: 0 0 5px; font-size: 28px;">
                            @if($remaining <= 0)
                                {{ number_format(0, 0, ',', ' ') }} FCFA
                            @else
                                {{ number_format($remaining, 0, ',', ' ') }} FCFA
                            @endif
                        </h2>
                        <p style="margin: 0; opacity: 0.8; font-size: 13px;">
                            @if($remaining <= 0)
                                <i class="fas fa-check-circle mr-1"></i> {{ __('payment.invoice_final_paid') }}
                            @else
                                {{ __('payment.invoice_final_remaining') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes et informations importantes -->
            <div class="mt-4 p-3" style="border: 1px solid #28a745; border-radius: 8px;">
                <h6 class="section-title mb-3">{{ __('payment.invoice_info_title') }}</h6>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <p class="small mb-2"><strong style="color: #28a745;">{{ __('payment.invoice_info_payment_conditions') }}</strong></p>
                        <ul class="small" style="padding-left: 20px; margin-bottom: 0;">
                            <li>{{ __('payment.invoice_info_deposit') }}</li>
                            <li>{{ __('payment.invoice_info_balance') }}</li>
                            <li>{{ __('payment.invoice_info_cancellation') }}</li>
                        </ul>
                    </div>
                    <div style="flex: 1;">
                        <p class="small mb-2"><strong style="color: #28a745;">{{ __('payment.invoice_info_payment_methods') }}</strong></p>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            <span class="payment-method-badge method-cash">{{ __('payment.invoice_info_cash') }}</span>
                            <span class="payment-method-badge method-card">{{ __('payment.invoice_info_card') }}</span>
                            <span class="payment-method-badge method-mobile">Mobile Money</span>
                            <span class="payment-method-badge method-bank">{{ __('payment.invoice_info_bank') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Politique de late checkout -->
                <div class="mt-3">
                    <p class="small mb-2"><strong style="color: #28a745;">{{ __('payment.invoice_late_policy') }}</strong></p>
                    <ul class="small" style="padding-left: 20px; margin-bottom: 0;">
                        <li>{{ __('payment.invoice_late_policy_free') }}</li>
                        <li>{{ __('payment.invoice_late_policy_50') }}</li>
                        <li>{{ __('payment.invoice_late_policy_100') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="p-3" style="border-top: 2px solid #28a745;">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p class="small text-muted mb-0">
                        <strong style="color: #28a745;">{{ __('payment.invoice_footer_signature') }}</strong><br>
                        <span style="margin-top: 30px; display: inline-block; border-top: 1px solid #28a745; padding-top: 8px; width: 150px;">
                            {{ $transaction->user->name ?? '____________________' }}
                        </span>
                    </p>
                </div>
                <div style="text-align: right;">
                    <p class="small mb-0" style="color: #28a745;">
                        {{ __('payment.invoice_footer_thanks') }}<br>
                        {{ __('payment.invoice_footer_wish') }}
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
        filename:     'Facture_INV-{{ $transaction->id }}.pdf',
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
    loadingMessage.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> {{ __("payment.invoice_pdf_generating") }}';
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
        successMessage.innerHTML = '<i class="fas fa-check mr-2"></i> {{ __("payment.invoice_pdf_success") }}';
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
@endsection