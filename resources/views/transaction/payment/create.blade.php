@extends('template.master')
@section('title', 'Effectuer un Paiement')
@section('content')

<style>
    .method-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .method-card:hover {
        border-color: #0d6efd;
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .method-card.active {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .method-card .form-check-input {
        position: absolute;
        top: 15px;
        left: 15px;
    }
    
    .method-card .card-body {
        padding: 20px 15px 15px 50px;
        min-height: 140px;
    }
    
    .method-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .method-fields {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border-left: 4px solid #0d6efd;
    }
    
    .quick-amount-btn {
        transition: all 0.2s ease;
    }
    
    .quick-amount-btn:hover {
        transform: scale(1.05);
    }
    
    .amount-input-wrapper {
        position: relative;
    }
    
    .amount-input-wrapper .input-group-text {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .transaction-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
    }
    
    .transaction-summary .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Paiement - Transaction #{{ $transaction->id }}
                    </h5>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-clock me-1"></i>
                            {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Résumé de la transaction -->
                    <div class="transaction-summary p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user fa-lg me-3"></i>
                                            <div>
                                                <div class="small opacity-75">Client</div>
                                                <strong class="h6 mb-0">{{ $transaction->customer->name }}</strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bed fa-lg me-3"></i>
                                            <div>
                                                <div class="small opacity-75">Chambre</div>
                                                <span class="badge bg-light text-dark">#{{ $transaction->room->number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar-alt fa-lg me-3"></i>
                                            <div>
                                                <div class="small opacity-75">Période</div>
                                                <strong class="h6 mb-0">
                                                    {{ $transaction->check_in->format('d/m/Y') }} 
                                                    <i class="fas fa-arrow-right mx-2"></i>
                                                    {{ $transaction->check_out->format('d/m/Y') }}
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-moon fa-lg me-3"></i>
                                            <div>
                                                <div class="small opacity-75">Nuits</div>
                                                <span class="badge bg-light text-dark">{{ $transaction->getNightsAttribute() }} nuits</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white rounded p-3 text-dark">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total séjour:</span>
                                        <strong>{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Déjà payé:</span>
                                        <strong class="text-success">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</strong>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="h5 mb-0">Reste à payer:</span>
                                        <span class="h4 mb-0 text-danger fw-bold">
                                            {{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire de paiement -->
                    <form action="{{ route('transaction.payment.store', $transaction) }}" method="POST" id="payment-form">
                        @csrf
                        
                        <div class="row">
                            <!-- Section Montant -->
                            <div class="col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>Montant du paiement</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="amount" class="form-label fw-bold">
                                                Montant à payer (CFA)
                                                <small class="text-muted d-block">
                                                    Maximum: {{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA
                                                </small>
                                            </label>
                                            <div class="amount-input-wrapper">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-primary text-white">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </span>
                                                    <input type="number" 
                                                           class="form-control form-control-lg" 
                                                           id="amount" 
                                                           name="amount"
                                                           min="0"
                                                           max="{{ $transaction->getRemainingPayment() }}"
                                                           step="100"
                                                           value="{{ min($transaction->getRemainingPayment(), max(1000, $transaction->getRemainingPayment())) }}"
                                                           required
                                                           style="font-weight: 600;">
                                                    <span class="input-group-text fw-bold">CFA</span>
                                                </div>
                                            </div>
                                            <div class="form-text mt-2">
                                                <div id="remaining-after" class="fw-bold"></div>
                                                <div id="payment-percentage" class="small"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Boutons de montant rapide -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Montants rapides:</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @php
                                                    $remaining = $transaction->getRemainingPayment();
                                                    $quickAmounts = [
                                                        min(5000, $remaining),
                                                        min(10000, $remaining),
                                                        min(25000, $remaining),
                                                        min(50000, $remaining),
                                                        $remaining
                                                    ];
                                                    $quickAmounts = array_unique(array_filter($quickAmounts));
                                                @endphp
                                                
                                                @foreach($quickAmounts as $quickAmount)
                                                    <button type="button" 
                                                            class="btn btn-outline-primary quick-amount-btn"
                                                            data-amount="{{ $quickAmount }}">
                                                        {{ number_format($quickAmount, 0, ',', ' ') }} CFA
                                                        @if($quickAmount == $remaining)
                                                            <i class="fas fa-check ms-1"></i>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section Méthode de paiement -->
                            <div class="col-lg-8 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Méthode de paiement</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Sélection de la méthode -->
                                        <div class="mb-4">
                                            <div class="row g-3" id="payment-methods">
                                                @php
                                                    $paymentMethods = \App\Models\Payment::getPaymentMethods();
                                                @endphp
                                                
                                                @foreach($paymentMethods as $method => $details)
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="form-check method-card" id="method-card-{{ $method }}">
                                                            <input class="form-check-input" 
                                                                   type="radio" 
                                                                   name="payment_method" 
                                                                   id="method_{{ $method }}" 
                                                                   value="{{ $method }}"
                                                                   data-requires-reference="{{ $details['requires_reference'] ? 'true' : 'false' }}"
                                                                   data-fields="{{ json_encode($details['fields']) }}"
                                                                   {{ $loop->first ? 'checked' : '' }}
                                                                   required>
                                                            <label class="form-check-label card-body text-center" 
                                                                   for="method_{{ $method }}">
                                                                <div class="method-icon text-{{ $details['color'] }}">
                                                                    <i class="fas {{ $details['icon'] }}"></i>
                                                                </div>
                                                                <h6 class="card-title mb-1 fw-bold">{{ $details['label'] }}</h6>
                                                                <p class="card-text small text-muted mb-0">
                                                                    {{ $details['description'] }}
                                                                </p>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Champs spécifiques à la méthode -->
                                        <div id="method-specific-fields" class="method-fields">
                                            <h6 class="mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Informations supplémentaires
                                                <small id="method-instructions" class="text-muted ms-2"></small>
                                            </h6>
                                            
                                            <!-- Référence générale -->
                                            <div class="row mb-3" id="reference-field">
                                                <div class="col-md-6">
                                                    <label for="reference" class="form-label">
                                                        <strong>Référence de transaction *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="reference" 
                                                           name="reference"
                                                           placeholder="Ex: VIR20240115, CB123456, MM789012"
                                                           value="PAY-{{ strtoupper(\App\Models\Payment::METHOD_CASH) }}-{{ time() }}">
                                                    <div class="form-text">
                                                        Identifiant unique pour tracer le paiement
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Champs pour carte bancaire -->
                                            <div class="row mb-3 d-none" id="card-fields">
                                                <div class="col-md-6">
                                                    <label for="card_last_four" class="form-label">
                                                        <strong>4 derniers chiffres de la carte *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="card_last_four" 
                                                           name="card_last_four"
                                                           maxlength="4"
                                                           placeholder="1234"
                                                           pattern="[0-9]{4}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="card_type" class="form-label">
                                                        <strong>Type de carte *</strong>
                                                    </label>
                                                    <select class="form-select" id="card_type" name="card_type">
                                                        <option value="">Sélectionnez...</option>
                                                        @foreach(\App\Models\Payment::getCardTypes() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- Champs pour chèque -->
                                            <div class="row mb-3 d-none" id="check-fields">
                                                <div class="col-md-6">
                                                    <label for="check_number" class="form-label">
                                                        <strong>Numéro du chèque *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="check_number" 
                                                           name="check_number"
                                                           placeholder="Ex: CHQ123456">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="check_bank_name" class="form-label">
                                                        <strong>Banque émettrice *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="check_bank_name" 
                                                           name="bank_name"
                                                           placeholder="Ex: Banque Atlantique">
                                                </div>
                                            </div>
                                            
                                            <!-- Champs pour virement -->
                                            <div class="row mb-3 d-none" id="transfer-fields">
                                                <div class="col-md-6">
                                                    <label for="transfer_bank_name" class="form-label">
                                                        <strong>Banque *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="transfer_bank_name" 
                                                           name="bank_name"
                                                           placeholder="Ex: Banque Internationale">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="account_number" class="form-label">
                                                        <strong>Numéro de compte *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="account_number" 
                                                           name="account_number"
                                                           placeholder="Ex: 0123456789">
                                                </div>
                                            </div>
                                            
                                            <!-- Champs pour Mobile Money -->
                                            <div class="row mb-3 d-none" id="mobile-money-fields">
                                                <div class="col-md-6">
                                                    <label for="mobile_money_provider" class="form-label">
                                                        <strong>Opérateur *</strong>
                                                    </label>
                                                    <select class="form-select" id="mobile_money_provider" name="mobile_money_provider">
                                                        <option value="">Sélectionnez...</option>
                                                        @foreach(\App\Models\Payment::getMobileMoneyProviders() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mobile_money_number" class="form-label">
                                                        <strong>Numéro mobile *</strong>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="mobile_money_number" 
                                                           name="mobile_money_number"
                                                           placeholder="Ex: 97000000">
                                                </div>
                                            </div>
                                            
                                            <!-- Notes -->
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">
                                                    <strong>Notes (optionnel)</strong>
                                                </label>
                                                <textarea class="form-control" 
                                                          id="notes" 
                                                          name="notes" 
                                                          rows="2"
                                                          placeholder="Informations supplémentaires..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded">
                            <div>
                                <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la transaction
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-danger">
                                    <i class="fas fa-redo me-2"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="submit-btn">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span id="submit-text">Enregistrer le paiement</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const remaining = {{ $transaction->getRemainingPayment() }};
    const amountInput = document.getElementById('amount');
    const remainingAfter = document.getElementById('remaining-after');
    const paymentPercentage = document.getElementById('payment-percentage');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const methodInstructions = document.getElementById('method-instructions');
    
    // Instructions pour chaque méthode
    const methodInstructionsText = {
        'cash': 'Paiement en espèces comptant',
        'card': 'Saisissez les informations de la carte',
        'transfer': 'Saisissez les informations de virement',
        'mobile_money': 'Saisissez les informations Mobile Money',
        'fedapay': 'Transaction Fedapay sécurisée',
        'check': 'Saisissez les informations du chèque'
    };
    
    // Mettre à jour les calculs
    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const newRemaining = remaining - amount;
        const percentage = (amount / remaining) * 100;
        
        // Mettre à jour le reste après paiement
        if (newRemaining > 0) {
            remainingAfter.innerHTML = `
                <span class="text-warning">
                    <i class="fas fa-info-circle me-1"></i>
                    Reste après paiement: <strong>${newRemaining.toLocaleString('fr-FR')} CFA</strong>
                </span>
            `;
        } else if (newRemaining === 0) {
            remainingAfter.innerHTML = `
                <span class="text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    <strong>Séjour entièrement payé !</strong>
                </span>
            `;
        } else {
            remainingAfter.innerHTML = '';
        }
        
        // Mettre à jour le pourcentage
        if (remaining > 0) {
            paymentPercentage.innerHTML = `
                Progression: <strong>${percentage.toFixed(1)}%</strong> du solde restant
            `;
        }
        
        // Mettre à jour le bouton de soumission
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const methodLabel = document.querySelector(`#method_${selectedMethod} + label .card-title`).textContent;
        
        if (amount === remaining) {
            submitText.innerHTML = `<i class="fas fa-check me-2"></i>Régler l'intégralité (${methodLabel})`;
            submitBtn.className = 'btn btn-success btn-lg';
        } else if (amount > 0) {
            submitText.innerHTML = `<i class="fas fa-money-bill-wave me-2"></i>Payer ${amount.toLocaleString('fr-FR')} CFA (${methodLabel})`;
            submitBtn.className = 'btn btn-primary btn-lg';
        } else {
            submitText.innerHTML = 'Enregistrer le paiement';
            submitBtn.className = 'btn btn-success btn-lg';
        }
    }
    
    // Gérer les boutons de montant rapide
    document.querySelectorAll('.quick-amount-btn').forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.getAttribute('data-amount');
            amountInput.value = amount;
            updateCalculations();
        });
    });
    
    // Gérer le changement de méthode de paiement
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const method = this.value;
            const requiresReference = this.getAttribute('data-requires-reference') === 'true';
            const fields = JSON.parse(this.getAttribute('data-fields') || '[]');
            
            // Mettre à jour les cartes actives
            document.querySelectorAll('.method-card').forEach(card => {
                card.classList.remove('active');
            });
            document.getElementById(`method-card-${method}`).classList.add('active');
            
            // Mettre à jour les instructions
            methodInstructions.textContent = methodInstructionsText[method] || '';
            
            // Gérer la référence
            const referenceField = document.getElementById('reference-field');
            const referenceInput = document.getElementById('reference');
            
            if (method === 'cash') {
                referenceField.querySelector('.form-label strong').innerHTML = 'Référence (optionnel)';
                referenceInput.required = false;
                referenceInput.value = `PAY-CASH-${Date.now()}`;
            } else {
                referenceField.querySelector('.form-label strong').innerHTML = 'Référence de transaction *';
                referenceInput.required = true;
                
                // Générer une référence appropriée
                let prefix = 'PAY-';
                switch(method) {
                    case 'card': prefix = 'CB-'; break;
                    case 'transfer': prefix = 'VIR-'; break;
                    case 'mobile_money': prefix = 'MM-'; break;
                    case 'fedapay': prefix = 'FDP-'; break;
                    case 'check': prefix = 'CHQ-'; break;
                }
                referenceInput.value = `${prefix}${Date.now()}`;
            }
            
            // Masquer tous les champs spécifiques
            document.querySelectorAll('#method-specific-fields > .row').forEach(row => {
                if (row.id !== 'reference-field') {
                    row.classList.add('d-none');
                }
            });
            
            // Afficher les champs spécifiques à la méthode
            fields.forEach(field => {
                if (field === 'card_last_four' || field === 'card_type') {
                    document.getElementById('card-fields').classList.remove('d-none');
                } else if (field === 'check_number' || field === 'bank_name') {
                    document.getElementById('check-fields').classList.remove('d-none');
                } else if (field === 'bank_name' && method === 'transfer') {
                    document.getElementById('transfer-fields').classList.remove('d-none');
                } else if (field === 'mobile_money_provider' || field === 'mobile_money_number') {
                    document.getElementById('mobile-money-fields').classList.remove('d-none');
                }
            });
            
            // Mettre à jour le texte du bouton
            updateCalculations();
        });
    });
    
    // Validation du montant
    amountInput.addEventListener('input', function() {
        let value = parseFloat(this.value) || 0;
        
        // Limiter au maximum
        if (value > remaining) {
            value = remaining;
            this.value = remaining;
        }
        
        // Minimum
        if (value < 0) {
            value = 0;
            this.value = 0;
        }
        
        updateCalculations();
    });
    
    // Validation du formulaire
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const amount = parseFloat(amountInput.value) || 0;
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const reference = document.getElementById('reference').value;
        
        // Validation de base
        if (amount <= 0) {
            Swal.fire('Erreur', 'Le montant doit être supérieur à 0 CFA.', 'error');
            amountInput.focus();
            return false;
        }
        
        if (amount > remaining) {
            Swal.fire('Erreur', `Le montant ne peut pas dépasser ${remaining.toLocaleString('fr-FR')} CFA.`, 'error');
            amountInput.focus();
            return false;
        }
        
        // Validation selon la méthode
        let validationErrors = [];
        
        if (method !== 'cash' && (!reference || reference.trim() === '')) {
            validationErrors.push('La référence est obligatoire pour ce type de paiement.');
        }
        
        // Validation des champs spécifiques
        if (method === 'card') {
            const cardLastFour = document.getElementById('card_last_four').value;
            const cardType = document.getElementById('card_type').value;
            
            if (!cardLastFour || cardLastFour.length !== 4 || !/^\d{4}$/.test(cardLastFour)) {
                validationErrors.push('Les 4 derniers chiffres de la carte sont invalides.');
            }
            
            if (!cardType) {
                validationErrors.push('Veuillez sélectionner le type de carte.');
            }
        }
        
        if (method === 'check') {
            const checkNumber = document.getElementById('check_number').value;
            const bankName = document.getElementById('check_bank_name').value;
            
            if (!checkNumber || checkNumber.trim() === '') {
                validationErrors.push('Le numéro du chèque est obligatoire.');
            }
            
            if (!bankName || bankName.trim() === '') {
                validationErrors.push('La banque émettrice est obligatoire.');
            }
        }
        
        if (method === 'transfer') {
            const bankName = document.getElementById('transfer_bank_name').value;
            const accountNumber = document.getElementById('account_number').value;
            
            if (!bankName || bankName.trim() === '') {
                validationErrors.push('Le nom de la banque est obligatoire.');
            }
            
            if (!accountNumber || accountNumber.trim() === '') {
                validationErrors.push('Le numéro de compte est obligatoire.');
            }
        }
        
        if (method === 'mobile_money') {
            const provider = document.getElementById('mobile_money_provider').value;
            const number = document.getElementById('mobile_money_number').value;
            
            if (!provider) {
                validationErrors.push('Veuillez sélectionner l\'opérateur Mobile Money.');
            }
            
            if (!number || number.trim() === '') {
                validationErrors.push('Le numéro mobile est obligatoire.');
            }
        }
        
        // Afficher les erreurs
        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erreurs de validation',
                html: '<ul class="text-start"><li>' + validationErrors.join('</li><li>') + '</li></ul>',
                confirmButtonText: 'Corriger'
            });
            return false;
        }
        
        // Confirmation
        const methodLabel = document.querySelector(`#method_${method} + label .card-title`).textContent;
        const message = amount === remaining 
            ? `✅ Vous allez régler l'intégralité du séjour (${amount.toLocaleString('fr-FR')} CFA) par ${methodLabel}.<br><br>Confirmer le paiement ?`
            : `Confirmer le paiement de <strong>${amount.toLocaleString('fr-FR')} CFA</strong> par ${methodLabel} ?`;
        
        Swal.fire({
            title: 'Confirmer le paiement',
            html: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check me-2"></i>Oui, enregistrer',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher le chargement
                Swal.fire({
                    title: 'Traitement en cours...',
                    text: 'Enregistrement du paiement',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Soumettre le formulaire
                setTimeout(() => {
                    document.getElementById('payment-form').submit();
                }, 500);
            }
        });
    });
    
    // Initialiser
    updateCalculations();
    document.querySelector('input[name="payment_method"]:checked').dispatchEvent(new Event('change'));
    
    console.log('Système de paiement initialisé. Solde restant:', remaining);
});
</script>
@endsection