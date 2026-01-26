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
    
    .debug-panel {
        background: #f8f9fa;
        border-left: 4px solid #6c757d;
        padding: 15px;
        border-radius: 5px;
        font-family: 'Courier New', monospace;
    }
    
    .debug-panel .debug-title {
        font-size: 0.9em;
        color: #6c757d;
        margin-bottom: 8px;
    }
    
    .debug-panel .debug-item {
        font-size: 0.85em;
        margin-bottom: 4px;
    }
    
    .debug-panel .debug-value {
        font-weight: bold;
        color: #0d6efd;
    }
    
    .payment-progress {
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .payment-progress .progress-bar {
        transition: width 0.5s ease;
    }
    
    #api-modal .modal-body pre {
        max-height: 400px;
        overflow: auto;
    }
    
    .toast-container {
        z-index: 9999;
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
                    <div class="d-flex align-items-center gap-2">
                        @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-sm btn-outline-light" id="debug-toggle">
                                <i class="fas fa-bug me-1"></i> Debug
                            </button>
                        @endif
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-clock me-1"></i>
                            {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Debug panel (caché par défaut) -->
                    @if(auth()->user()->isAdmin())
                        <div class="debug-panel mb-4 d-none" id="debug-panel">
                            <div class="debug-title">
                                <i class="fas fa-code me-1"></i> Informations de débogage
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="debug-item">
                                        Transaction ID: <span class="debug-value">#{{ $transaction->id }}</span>
                                    </div>
                                    <div class="debug-item">
                                        Statut: <span class="debug-value">{{ $transaction->status }}</span>
                                    </div>
                                    <div class="debug-item">
                                        Prix total (colonne): <span class="debug-value">{{ number_format($transaction->total_price, 0, ',', ' ') }} CFA</span>
                                    </div>
                                    <div class="debug-item">
                                        Paiement total (colonne): <span class="debug-value">{{ number_format($transaction->total_payment, 0, ',', ' ') }} CFA</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="debug-item">
                                        Prix total (calculé): <span class="debug-value" id="debug-total-price">{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</span>
                                    </div>
                                    <div class="debug-item">
                                        Paiement total (calculé): <span class="debug-value" id="debug-total-payment">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</span>
                                    </div>
                                    <div class="debug-item">
                                        Solde restant (calculé): <span class="debug-value" id="debug-remaining">{{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA</span>
                                    </div>
                                    <div class="debug-item">
                                        Paiements (total/complétés): 
                                        <span class="debug-value" id="debug-payment-count">
                                            {{ $transaction->payments()->count() }} / {{ $transaction->payments()->where('status', 'completed')->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="refresh-debug">
                                    <i class="fas fa-sync-alt me-1"></i> Actualiser
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" id="force-sync">
                                    <i class="fas fa-cogs me-1"></i> Forcer synchronisation
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info" id="show-api">
                                    <i class="fas fa-code me-1"></i> Voir API
                                </button>
                            </div>
                        </div>
                    @endif

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
                                        <strong id="summary-total-price">{{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Déjà payé:</span>
                                        <strong class="text-success" id="summary-total-payment">{{ number_format($transaction->getTotalPayment(), 0, ',', ' ') }} CFA</strong>
                                    </div>
                                    
                                    <!-- Barre de progression -->
                                    <div class="mb-2">
                                        <div class="payment-progress bg-light mb-1">
                                            <div class="progress-bar bg-success" 
                                                 id="payment-progress-bar"
                                                 style="width: {{ $transaction->getPaymentRate() }}%">
                                            </div>
                                        </div>
                                        <div class="small text-center">
                                            <span id="payment-percentage-text">{{ number_format($transaction->getPaymentRate(), 1) }}%</span> payé
                                        </div>
                                    </div>
                                    
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="h5 mb-0">Reste à payer:</span>
                                        <span class="h4 mb-0 text-danger fw-bold" id="summary-remaining">
                                            {{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire de paiement SIMPLIFIÉ -->
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
                                                    Maximum: <span id="max-amount">{{ number_format($transaction->getRemainingPayment(), 0, ',', ' ') }} CFA</span>
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
                                                           min="100"
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
                                            <div class="d-flex flex-wrap gap-2" id="quick-amount-buttons">
                                                @php
                                                    $remaining = $transaction->getRemainingPayment();
                                                    $quickAmounts = [
                                                        min(1000, $remaining),
                                                        min(5000, $remaining),
                                                        min(10000, $remaining),
                                                        min(25000, $remaining),
                                                        min(50000, $remaining),
                                                        $remaining
                                                    ];
                                                    $quickAmounts = array_unique(array_filter($quickAmounts));
                                                @endphp
                                                
                                                @foreach($quickAmounts as $quickAmount)
                                                    @if($quickAmount >= 100)
                                                        <button type="button" 
                                                                class="btn btn-outline-primary quick-amount-btn"
                                                                data-amount="{{ $quickAmount }}">
                                                            {{ number_format($quickAmount, 0, ',', ' ') }} CFA
                                                            @if($quickAmount == $remaining)
                                                                <i class="fas fa-check ms-1"></i>
                                                            @endif
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Validation en temps réel -->
                                        <div class="alert alert-warning d-none" id="amount-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <span id="warning-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section Méthode de paiement SIMPLIFIÉE -->
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
                                        
                                        <!-- Champs spécifiques à la méthode SIMPLIFIÉS -->
                                        <div id="method-specific-fields" class="method-fields">
                                            <h6 class="mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Informations supplémentaires
                                            </h6>
                                            
                                            <!-- Description -->
                                            <div class="mb-3">
                                                <label for="description" class="form-label">
                                                    <strong>Description (optionnel)</strong>
                                                </label>
                                                <textarea class="form-control" 
                                                          id="description" 
                                                          name="description" 
                                                          rows="2"
                                                          placeholder="Informations sur le paiement..."></textarea>
                                                <div class="form-text">
                                                    Ex: "Paiement d'acompte", "Settlement de facture", etc.
                                                </div>
                                            </div>
                                            
                                            <!-- Référence automatique (cachée car générée automatiquement) -->
                                            <input type="hidden" 
                                                   id="reference" 
                                                   name="reference"
                                                   value="PAY-{{ strtoupper('cash') }}-{{ time() }}-{{ rand(1000, 9999) }}">
                                            
                                            <!-- Note sur la référence -->
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Une référence de paiement sera générée automatiquement pour ce paiement.
                                            </div>
                                            
                                            <!-- Validation des champs -->
                                            <div class="alert alert-danger d-none" id="method-validation">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                <span id="validation-text"></span>
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
                                <button type="button" class="btn btn-outline-warning" id="validate-form">
                                    <i class="fas fa-check-circle me-2"></i>Valider
                                </button>
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

<!-- Modal pour les réponses API -->
<div class="modal fade" id="api-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-code me-2"></i>Réponse API</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="api-response-content" class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INITIALISATION SYSTÈME DE PAIEMENT SIMPLIFIÉ ===');
    
    const transactionId = {{ $transaction->id }};
    const remaining = {{ $transaction->getRemainingPayment() }};
    const totalPrice = {{ $transaction->getTotalPrice() }};
    const totalPayment = {{ $transaction->getTotalPayment() }};
    
    // Éléments DOM
    const amountInput = document.getElementById('amount');
    const descriptionInput = document.getElementById('description');
    const remainingAfter = document.getElementById('remaining-after');
    const paymentPercentage = document.getElementById('payment-percentage');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const amountWarning = document.getElementById('amount-warning');
    const warningText = document.getElementById('warning-text');
    const paymentProgressBar = document.getElementById('payment-progress-bar');
    const paymentPercentageText = document.getElementById('payment-percentage-text');
    const paymentForm = document.getElementById('payment-form');
    const referenceInput = document.getElementById('reference');
    
    // Stocker l'état initial
    let currentRemaining = remaining;
    let paymentRate = (totalPayment / totalPrice) * 100;
    
    // Mettre à jour les calculs
    function updateCalculations() {
        const amount = parseFloat(amountInput.value) || 0;
        const newRemaining = currentRemaining - amount;
        const newPaymentRate = ((totalPayment + amount) / totalPrice) * 100;
        
        // Mettre à jour les éléments d'affichage
        updateDisplay(amount, newRemaining, newPaymentRate);
        
        // Validation
        validateAmount(amount);
        
        // Mettre à jour le bouton de soumission
        updateSubmitButton(amount, newRemaining);
        
        console.log('Calculs mis à jour:', {
            amount: amount,
            newRemaining: newRemaining,
            paymentRate: newPaymentRate,
            currentRemaining: currentRemaining
        });
    }
    
    function updateDisplay(amount, newRemaining, newPaymentRate) {
        // Texte reste après paiement
        if (newRemaining > 0) {
            remainingAfter.innerHTML = `
                <span class="text-warning">
                    <i class="fas fa-info-circle me-1"></i>
                    Reste après paiement: <strong>${newRemaining.toLocaleString('fr-FR')} CFA</strong>
                </span>
            `;
            remainingAfter.className = 'fw-bold text-warning';
        } else if (newRemaining === 0) {
            remainingAfter.innerHTML = `
                <span class="text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    <strong>Séjour entièrement payé !</strong>
                </span>
            `;
            remainingAfter.className = 'fw-bold text-success';
        } else {
            remainingAfter.innerHTML = '';
        }
        
        // Pourcentage
        paymentPercentage.innerHTML = `
            Progression: <strong>${newPaymentRate.toFixed(1)}%</strong> du total
        `;
        
        // Barre de progression
        paymentProgressBar.style.width = `${newPaymentRate}%`;
        paymentPercentageText.textContent = `${newPaymentRate.toFixed(1)}%`;
        
        // Mettre à jour le maximum affiché
        document.getElementById('max-amount').textContent = `${currentRemaining.toLocaleString('fr-FR')} CFA`;
        
        // Mettre à jour l'attribut max de l'input
        amountInput.max = currentRemaining;
    }
    
    function validateAmount(amount) {
        amountWarning.classList.add('d-none');
        
        if (amount > currentRemaining) {
            warningText.textContent = `Le montant dépasse le solde restant de ${currentRemaining.toLocaleString('fr-FR')} CFA`;
            amountWarning.classList.remove('d-none');
            amountInput.classList.add('is-invalid');
            return false;
        }
        
        if (amount < 100) {
            warningText.textContent = 'Le montant minimum est de 100 CFA';
            amountWarning.classList.remove('d-none');
            amountInput.classList.add('is-invalid');
            return false;
        }
        
        amountInput.classList.remove('is-invalid');
        return true;
    }
    
    function updateSubmitButton(amount, newRemaining) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) return;
        
        const method = selectedMethod.value;
        const methodLabel = document.querySelector(`#method_${method} + label .card-title`).textContent;
        
        if (amount === currentRemaining || newRemaining === 0) {
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
            const amount = parseFloat(this.getAttribute('data-amount'));
            amountInput.value = amount;
            updateCalculations();
            
            // Animation de feedback
            this.classList.add('btn-primary');
            this.classList.remove('btn-outline-primary');
            setTimeout(() => {
                this.classList.remove('btn-primary');
                this.classList.add('btn-outline-primary');
            }, 300);
        });
    });
    
    // Gérer le changement de méthode de paiement
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const method = this.value;
            
            // Mettre en surbrillance la carte sélectionnée
            document.querySelectorAll('.method-card').forEach(card => {
                card.classList.remove('active');
            });
            document.getElementById(`method-card-${method}`).classList.add('active');
            
            // Générer une nouvelle référence
            let prefix = 'PAY-';
            switch(method) {
                case 'cash': prefix = 'CASH-'; break;
                case 'card': prefix = 'CARD-'; break;
                case 'transfer': prefix = 'VIR-'; break;
                case 'mobile_money': prefix = 'MOMO-'; break;
                case 'fedapay': prefix = 'FDP-'; break;
                case 'check': prefix = 'CHQ-'; break;
            }
            referenceInput.value = `${prefix}${transactionId}-${Date.now()}-${Math.floor(Math.random() * 10000)}`;
            
            // Mettre à jour la description par défaut
            const descriptions = {
                'cash': 'Paiement en espèces comptant',
                'card': 'Paiement par carte bancaire',
                'transfer': 'Paiement par virement bancaire',
                'mobile_money': 'Paiement par Mobile Money',
                'fedapay': 'Paiement Fedapay',
                'check': 'Paiement par chèque'
            };
            
            if (!descriptionInput.value) {
                descriptionInput.value = descriptions[method] || '';
            }
            
            updateCalculations();
        });
    });
    
    // Validation du montant en temps réel
    amountInput.addEventListener('input', function() {
        let value = parseFloat(this.value) || 0;
        
        // Limiter à currentRemaining
        if (value > currentRemaining) {
            value = currentRemaining;
            this.value = currentRemaining;
        }
        
        // Minimum
        if (value < 0) {
            value = 0;
            this.value = 0;
        }
        
        updateCalculations();
    });
    
    // Validation du formulaire
    document.getElementById('validate-form').addEventListener('click', function() {
        const amount = parseFloat(amountInput.value) || 0;
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        
        let isValid = true;
        let errors = [];
        
        // Validation du montant
        if (amount <= 0) {
            errors.push('Le montant doit être supérieur à 0 CFA');
            isValid = false;
        }
        
        if (amount > currentRemaining + 100) {
            errors.push(`Le montant ne peut pas dépasser ${currentRemaining.toLocaleString('fr-FR')} CFA`);
            isValid = false;
        }
        
        // Afficher les résultats
        if (isValid) {
            const methodLabel = document.querySelector(`#method_${method} + label .card-title`).textContent;
            Swal.fire({
                title: '✅ Validation réussie',
                html: `
                    <div class="text-start">
                        <p>Le formulaire est prêt à être soumis.</p>
                        <div class="alert alert-success">
                            <strong>Montant:</strong> ${amount.toLocaleString('fr-FR')} CFA<br>
                            <strong>Méthode:</strong> ${methodLabel}<br>
                            <strong>Reste à payer:</strong> ${(currentRemaining - amount).toLocaleString('fr-FR')} CFA
                        </div>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Continuer'
            });
        } else {
            Swal.fire({
                title: '❌ Erreurs de validation',
                html: errors.join('<br>'),
                icon: 'error',
                confirmButtonText: 'Corriger'
            });
        }
    });
    
    // Gestionnaire de soumission
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        console.log('=== TENTATIVE DE SOUMISSION SIMPLIFIÉE ===');
        
        const amount = parseFloat(amountInput.value) || 0;
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Validation finale
        if (amount <= 0 || amount > currentRemaining + 100) {
            Swal.fire({
                title: '❌ Erreur',
                text: `Montant invalide. Maximum: ${currentRemaining.toLocaleString('fr-FR')} CFA`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        // Désactiver le bouton
        const originalButtonContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
        
        try {
            // Soumettre via AJAX
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Succès - afficher le message et rediriger
                console.log('Paiement réussi:', data);
                
                await Swal.fire({
                    title: '✅ Succès',
                    html: `
                        <div class="text-start">
                            <p>${data.message}</p>
                            <div class="alert alert-success">
                                <h5 class="mb-1">${data.data.payment.amount.toLocaleString('fr-FR')} CFA</h5>
                                <small class="text-muted">${data.data.payment.method_label}</small>
                                <div class="mt-2">
                                    <small>Référence: ${data.data.payment.reference}</small>
                                </div>
                            </div>
                            ${data.data.transaction.is_fully_paid ? 
                                '<div class="alert alert-info mt-2"><i class="fas fa-trophy me-2"></i>Transaction entièrement payée !</div>' : 
                                `<div class="alert alert-warning mt-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Solde restant: ${data.data.transaction.remaining.toLocaleString('fr-FR')} CFA
                                </div>`
                            }
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Voir la transaction',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                
                // Redirection
                window.location.href = `/transactions/${transactionId}`;
                
            } else {
                // Erreur de validation ou autre
                console.error('Erreur paiement:', data);
                
                let errorMessage = data.message || 'Une erreur est survenue';
                
                if (data.errors) {
                    const errorList = Object.values(data.errors).flat().join('<br>');
                    errorMessage = errorList;
                }
                
                Swal.fire({
                    title: '❌ Erreur',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalButtonContent;
            }
            
        } catch (error) {
            console.error('Erreur réseau:', error);
            
            Swal.fire({
                title: '❌ Erreur réseau',
                text: 'Impossible de se connecter au serveur. Veuillez réessayer.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonContent;
        }
    });
    
    // Fonctions de débogage
    @if(auth()->user()->isAdmin())
        // Toggle debug panel
        document.getElementById('debug-toggle').addEventListener('click', function() {
            const panel = document.getElementById('debug-panel');
            panel.classList.toggle('d-none');
            this.innerHTML = panel.classList.contains('d-none') 
                ? '<i class="fas fa-bug me-1"></i> Debug'
                : '<i class="fas fa-eye-slash me-1"></i> Cacher';
        });
        
        // Rafraîchir les données de débogage
        document.getElementById('refresh-debug').addEventListener('click', async function() {
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch(`/api/transactions/${transactionId}/check-status`);
                const data = await response.json();
                
                if (data.success) {
                    // Mettre à jour les valeurs de débogage
                    document.getElementById('debug-total-price').textContent = 
                        data.transaction.total_price.toLocaleString('fr-FR') + ' CFA';
                    document.getElementById('debug-total-payment').textContent = 
                        data.transaction.total_payment.toLocaleString('fr-FR') + ' CFA';
                    document.getElementById('debug-remaining').textContent = 
                        data.transaction.remaining.toLocaleString('fr-FR') + ' CFA';
                    document.getElementById('debug-payment-count').textContent = 
                        `${data.payments.total_count} / ${data.payments.completed_count}`;
                    
                    // Mettre à jour les valeurs principales
                    currentRemaining = data.transaction.remaining;
                    updateCalculations();
                    
                    Swal.fire('Succès', 'Données actualisées', 'success');
                }
            } catch (error) {
                Swal.fire('Erreur', 'Impossible de rafraîchir', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        
        // Forcer la synchronisation
        document.getElementById('force-sync').addEventListener('click', async function() {
            const result = await Swal.fire({
                title: 'Synchroniser ?',
                text: 'Recalculer tous les totaux',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            });
            
            if (result.isConfirmed) {
                const btn = this;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
                btn.disabled = true;
                
                try {
                    const response = await fetch(`/api/transactions/${transactionId}/force-sync`);
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire('Succès', data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        throw new Error(data.error);
                    }
                } catch (error) {
                    Swal.fire('Erreur', error.message, 'error');
                } finally {
                    btn.innerHTML = '<i class="fas fa-cogs me-1"></i> Synchroniser';
                    btn.disabled = false;
                }
            }
        });
        
        // Afficher les données API
        document.getElementById('show-api').addEventListener('click', async function() {
            try {
                const response = await fetch(`/api/transactions/${transactionId}/check-status`);
                const data = await response.json();
                
                document.getElementById('api-response-content').textContent = 
                    JSON.stringify(data, null, 2);
                
                const modal = new bootstrap.Modal(document.getElementById('api-modal'));
                modal.show();
            } catch (error) {
                Swal.fire('Erreur', 'Impossible de récupérer les données API', 'error');
            }
        });
    @endif
    
    // Initialiser
    console.log('État initial:', {
        transactionId: transactionId,
        remaining: remaining,
        totalPrice: totalPrice,
        totalPayment: totalPayment,
        paymentRate: paymentRate
    });
    
    updateCalculations();
    document.querySelector('input[name="payment_method"]:checked').dispatchEvent(new Event('change'));
    
    // Rafraîchissement périodique
    setInterval(async () => {
        try {
            const response = await fetch(`/api/transactions/${transactionId}/check-status`);
            const data = await response.json();
            
            if (data.success && data.transaction.remaining !== currentRemaining) {
                console.log('Solde mis à jour:', {
                    ancien: currentRemaining,
                    nouveau: data.transaction.remaining
                });
                
                currentRemaining = data.transaction.remaining;
                updateCalculations();
            }
        } catch (error) {
            console.warn('Erreur rafraîchissement:', error);
        }
    }, 30000);
});
</script>
@endsection