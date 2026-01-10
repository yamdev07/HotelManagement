@extends('template.master')
@section('title', 'Reservation Confirmation')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
    <style>
        .summary-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-left: 4px solid #4e73df;
        }
        .price-highlight {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2e59d9;
        }
        .customer-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem 0.5rem 0 0;
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
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-options .form-check:hover {
            background-color: #f8f9fa;
            border-color: #4e73df;
        }
        .payment-options .form-check-input:checked + .form-check-label {
            font-weight: bold;
            color: #4e73df;
        }
        .payment-options .form-check-input:checked ~ .payment-details {
            display: block;
        }
        .payment-details {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
@endsection
@section('content')
    @include('transaction.reservation.progressbar')
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Confirmation de Réservation
                        </h4>
                        <small>Finalisez votre réservation</small>
                    </div>
                    
                    <div class="card-body p-4">
                        @if($existingReservationsCount > 0)
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Ce client a déjà {{ $existingReservationsCount }} réservation(s).
                        </div>
                        @endif
                        
                        <!-- Informations de la chambre -->
                        <div class="card summary-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-bed me-2"></i>
                                    Détails de la Chambre
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Numéro de chambre :</strong> {{ $room->number }}</p>
                                        <p><strong>Type de chambre :</strong> {{ $room->type->name ?? 'Standard' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Capacité :</strong> {{ $room->capacity }} personne(s)</p>
                                        <p><strong>Prix par nuit :</strong> <span class="currency-symbol">FCFA</span> {{ number_format($room->price, 0, ',', ' ') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Détails du séjour -->
                        <div class="card summary-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Détails du Séjour
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Date d'arrivée :</strong> {{ date('d/m/Y', strtotime($stayFrom)) }}</p>
                                        <p><strong>Date de départ :</strong> {{ date('d/m/Y', strtotime($stayUntil)) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Durée :</strong> {{ $dayDifference }} {{ $dayDifference > 1 ? 'nuits' : 'nuit' }}</p>
                                        @php
                                            $totalPrice = $room->price * $dayDifference;
                                        @endphp
                                        <p class="price-highlight">
                                            Prix total : <span class="currency-symbol">FCFA</span> {{ number_format($totalPrice, 0, ',', ' ') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options de paiement -->
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Options de Paiement
                                </h5>
                                <small>Choisissez comment vous souhaitez procéder</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" 
                                      action="{{ route('transaction.reservation.payDownPayment', ['customer' => $customer->id, 'room' => $room->id]) }}"
                                      id="reservationForm">
                                    @csrf
                                    
                                    <input type="hidden" name="check_in" value="{{ $stayFrom }}">
                                    <input type="hidden" name="check_out" value="{{ $stayUntil }}">
                                    <input type="hidden" name="downPayment" id="downPaymentInput" value="0">
                                    
                                    <div class="payment-options mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_reserve_only" value="reserve_only" checked>
                                            <label class="form-check-label fw-bold" for="option_reserve_only">
                                                <i class="fas fa-calendar-check text-success me-2"></i>
                                                Réserver sans acompte
                                            </label>
                                            <div class="payment-details">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    La réservation est confirmée sans paiement immédiat. 
                                                    Le paiement complet sera effectué à l'arrivée.
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_deposit" value="pay_deposit">
                                            <label class="form-check-label fw-bold" for="option_pay_deposit">
                                                <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                                Payer un acompte (optionnel)
                                            </label>
                                            <div class="payment-details">
                                                <div class="mt-3">
                                                    <label for="deposit_amount" class="form-label">
                                                        Montant de l'acompte (optionnel)
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">FCFA</span>
                                                        <input type="number" 
                                                               class="form-control" 
                                                               id="deposit_amount" 
                                                               name="deposit_amount"
                                                               value="0"
                                                               min="0"
                                                               max="{{ $totalPrice }}"
                                                               step="100"
                                                               disabled>
                                                    </div>
                                                    <div class="form-text">
                                                        Minimum recommandé : <span class="currency-symbol">FCFA</span> {{ number_format($downPayment, 0, ',', ' ') }}
                                                        (15% du total)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="option_pay_full" value="pay_full">
                                            <label class="form-check-label fw-bold" for="option_pay_full">
                                                <i class="fas fa-wallet text-success me-2"></i>
                                                Payer la totalité maintenant
                                            </label>
                                            <div class="payment-details">
                                                <div class="alert alert-success mt-3 mb-0">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    <strong>Paiement complet :</strong> 
                                                    <span class="currency-symbol">FCFA</span> {{ number_format($totalPrice, 0, ',', ' ') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Résumé du paiement -->
                                    <div class="alert alert-info" id="paymentSummary">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-invoice me-2"></i>
                                                <strong>Résumé :</strong>
                                            </div>
                                            <div>
                                                <span id="summaryText">Réservation sans acompte</span>
                                            </div>
                                        </div>
                                        <div class="mt-2" id="amountDetails" style="display: none;">
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Montant payé :</span>
                                                <strong id="paidAmount">FCFA 0</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Solde à régler :</span>
                                                <strong id="balanceAmount">FCFA {{ number_format($totalPrice, 0, ',', ' ') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Conditions générales -->
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            J'accepte les conditions générales. Je comprends que :
                                            <ul class="mb-0 mt-2 small">
                                                <li>La réservation est confirmée immédiatement</li>
                                                <li>Le paiement sera effectué à l'arrivée (sauf si payé maintenant)</li>
                                                <li>La politique d'annulation s'applique</li>
                                            </ul>
                                        </label>
                                    </div>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('transaction.reservation.chooseRoom', ['customer' => $customer->id]) }}?check_in={{ $stayFrom }}&check_out={{ $stayUntil }}"
                                        class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> Retour au choix des chambres
                                        </a>
                                        <button type="submit" class="btn btn-success px-4" id="submitBtn">
                                            <i class="fas fa-calendar-plus me-2"></i>
                                            Confirmer la Réservation
                                            <small class="d-block mt-1 fw-normal">(Retour au dashboard)</small>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations du client -->
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Informations Client
                        </h5>
                    </div>
                    
                    @if($customer->user && $customer->user->avatar)
                        <img src="{{ $customer->user->getAvatar() }}" 
                             class="customer-avatar" 
                             alt="{{ $customer->name }}">
                    @else
                        <div class="text-center py-4 bg-light">
                            <i class="fas fa-user-circle fa-5x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="info-icon"><i class="fas fa-user"></i></td>
                                <td><strong>Nom :</strong></td>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <td class="info-icon"><i class="fas fa-{{ $customer->gender == 'Male' ? 'male' : 'female' }}"></i></td>
                                <td><strong>Genre :</strong></td>
                                <td>{{ $customer->gender == 'Male' ? 'Homme' : 'Femme' }}</td>
                            </tr>
                            <tr>
                                <td class="info-icon"><i class="fas fa-phone"></i></td>
                                <td><strong>Téléphone :</strong></td>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="info-icon"><i class="fas fa-envelope"></i></td>
                                <td><strong>Email :</strong></td>
                                <td>{{ $customer->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="info-icon"><i class="fas fa-briefcase"></i></td>
                                <td><strong>Profession :</strong></td>
                                <td>{{ $customer->job ?? 'N/A' }}</td>
                            </tr>
                            @if($existingReservationsCount > 0)
                            <tr class="table-info">
                                <td class="info-icon"><i class="fas fa-bed"></i></td>
                                <td><strong>Réservations :</strong></td>
                                <td>{{ $existingReservationsCount }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="fas fa-id-card me-1"></i>
                            ID Client : {{ $customer->id }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.getElementById('reservationForm');
    const downPaymentInput = document.getElementById('downPaymentInput');
    const depositAmountInput = document.getElementById('deposit_amount');
    const paymentSummary = document.getElementById('paymentSummary');
    const summaryText = document.getElementById('summaryText');
    const amountDetails = document.getElementById('amountDetails');
    const paidAmount = document.getElementById('paidAmount');
    const balanceAmount = document.getElementById('balanceAmount');
    const submitBtn = document.getElementById('submitBtn');
    const termsCheckbox = document.getElementById('terms');
    
    // Calculer le prix total
    const totalPrice = {{ $room->price * $dayDifference }};
    
    // Formater la monnaie en FCFA
    function formatCurrency(amount) {
        return 'FCFA ' + amount.toLocaleString('fr-FR');
    }
    
    // Mettre à jour le résumé du paiement
    function updatePaymentSummary() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        let paymentAmount = 0;
        let summary = '';
        let showDetails = false;
        
        switch(selectedOption) {
            case 'reserve_only':
                paymentAmount = 0;
                summary = 'Réservation sans acompte';
                showDetails = false;
                downPaymentInput.value = 0;
                break;
                
            case 'pay_deposit':
                paymentAmount = parseFloat(depositAmountInput.value) || 0;
                summary = 'Acompte optionnel';
                showDetails = paymentAmount > 0;
                downPaymentInput.value = paymentAmount;
                break;
                
            case 'pay_full':
                paymentAmount = totalPrice;
                summary = 'Paiement complet';
                showDetails = true;
                downPaymentInput.value = paymentAmount;
                break;
        }
        
        // Mettre à jour l'affichage
        summaryText.textContent = summary;
        
        if (showDetails) {
            paidAmount.textContent = formatCurrency(paymentAmount);
            balanceAmount.textContent = formatCurrency(totalPrice - paymentAmount);
            amountDetails.style.display = 'block';
            
            // Changer la couleur selon le montant
            if (paymentAmount === totalPrice) {
                paymentSummary.className = 'alert alert-success';
            } else if (paymentAmount > 0) {
                paymentSummary.className = 'alert alert-warning';
            }
        } else {
            amountDetails.style.display = 'none';
            paymentSummary.className = 'alert alert-info';
        }
        
        // Activer/désactiver le bouton en fonction des conditions
        submitBtn.disabled = !termsCheckbox.checked;
        
        // Mettre à jour le texte du bouton
        if (paymentAmount === totalPrice) {
            submitBtn.innerHTML = '<i class="fas fa-wallet me-2"></i> Payer et Réserver';
        } else if (paymentAmount > 0) {
            submitBtn.innerHTML = '<i class="fas fa-money-bill-wave me-2"></i> Payer l\'acompte et Réserver';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-calendar-plus me-2"></i> Confirmer la Réservation';
        }
    }
    
    // Activer/désactiver le champ de dépôt selon l'option
    function toggleDepositInput() {
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        depositAmountInput.disabled = selectedOption !== 'pay_deposit';
        
        if (selectedOption === 'pay_deposit' && !depositAmountInput.value) {
            depositAmountInput.value = {{ $downPayment }};
        } else if (selectedOption === 'pay_full') {
            depositAmountInput.value = totalPrice;
        } else if (selectedOption === 'reserve_only') {
            depositAmountInput.value = 0;
        }
    }
    
    // Écouteurs d'événements
    document.querySelectorAll('input[name="payment_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleDepositInput();
            updatePaymentSummary();
        });
    });
    
    depositAmountInput.addEventListener('input', function() {
        updatePaymentSummary();
    });
    
    termsCheckbox.addEventListener('change', updatePaymentSummary);
    
    // Validation du formulaire
    reservationForm.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Vous devez accepter les conditions générales.');
            termsCheckbox.focus();
            return false;
        }
        
        const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
        const depositAmount = parseFloat(depositAmountInput.value) || 0;
        
        if (selectedOption === 'pay_deposit' && depositAmount > totalPrice) {
            e.preventDefault();
            alert('L\'acompte ne peut pas dépasser le prix total.');
            depositAmountInput.focus();
            return false;
        }
        
        // Afficher l'état de chargement
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement en cours...';
        submitBtn.disabled = true;
    });
    
    // Mise à jour initiale
    toggleDepositInput();
    updatePaymentSummary();
});
</script>
@endsection