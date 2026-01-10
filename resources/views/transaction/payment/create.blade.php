@extends('template.master')
@section('title', $transaction->customer->name . ' - Paiement Réservation')
@section('content')
    <style>
        .payment-card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }
        
        .customer-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .customer-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .amount-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .readonly-input {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            font-weight: 500;
        }
        
        .cfa-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">
                    <i class="fas fa-credit-card me-2"></i>Paiement de Réservation
                </h2>
                <p class="text-muted mb-0">
                    Client: {{ $transaction->customer->name }} • Chambre: {{ $transaction->room->number }}
                </p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Informations de paiement -->
            <div class="col-lg-9">
                <div class="card payment-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Détails du Paiement
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Numéro de Chambre</div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control readonly-input" 
                                       value="{{ $transaction->room->number }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Date d'Arrivée</div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control readonly-input"
                                    value="{{ Helper::dateFormat($transaction->check_in) }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Date de Départ</div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control readonly-input"
                                    value="{{ Helper::dateFormat($transaction->check_out) }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Prix de la Chambre</div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly-input"
                                        value="{{ Helper::formatCFA($transaction->room->price) }}" readonly>
                                    <span class="input-group-text cfa-badge">CFA</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Nombre de Nuits</div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control readonly-input"
                                    value="{{ $transaction->getDateDifferenceWithPlural($transaction->check_in, $transaction->check_out) }}"
                                    readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Total Séjour</div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly-input"
                                        value="{{ Helper::formatCFA($transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out)) }}"
                                        readonly>
                                    <span class="input-group-text cfa-badge">CFA</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-3 info-label">Déjà Payé</div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly-input"
                                        value="{{ Helper::formatCFA($transaction->getTotalPayment()) }}" readonly>
                                    <span class="input-group-text cfa-badge">CFA</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-sm-3 info-label">Solde Restant</div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control readonly-input"
                                        value="{{ Helper::formatCFA($transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out) - $transaction->getTotalPayment()) }}"
                                        readonly>
                                    <span class="input-group-text cfa-badge">CFA</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Formulaire de paiement -->
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="POST"
                                    action="{{ route('transaction.payment.store', ['transaction' => $transaction->id]) }}">
                                    @csrf
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-3 info-label">Montant à Payer <span class="text-danger">*</span></div>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" class="form-control @error('payment') is-invalid @enderror"
                                                    placeholder="Entrez le montant en CFA" value="" 
                                                    id="payment" name="payment" min="0" required>
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                            @error('payment')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <div id="showPaymentType" class="amount-display">
                                                0 FCFA
                                            </div>
                                            <small class="text-muted">
                                                Solde maximum: {{ Helper::formatCFA($transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out) - $transaction->getTotalPayment()) }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Retour
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-circle me-1"></i>Confirmer le Paiement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations du client -->
            <div class="col-lg-3">
                <div class="card customer-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Informations Client
                        </h5>
                    </div>
                    <div class="text-center p-3">
                        <img src="{{ $transaction->customer->user->getAvatar() }}" 
                             class="customer-avatar rounded" 
                             alt="{{ $transaction->customer->name }}">
                    </div>
                    <div class="card-body">
                        <h4 class="text-center mb-3">{{ $transaction->customer->name }}</h4>
                        
                        <div class="customer-info">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-{{ $transaction->customer->gender == 'Male' ? 'male' : 'female' }} text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Genre</small>
                                    <strong>{{ $transaction->customer->gender == 'Male' ? 'Homme' : 'Femme' }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="fas fa-briefcase text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Profession</small>
                                    <strong>{{ $transaction->customer->job ?? 'Non spécifié' }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-birthday-cake text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Date de Naissance</small>
                                    <strong>{{ $transaction->customer->birthdate ?? 'Non spécifié' }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Adresse</small>
                                    <strong>{{ $transaction->customer->address ?? 'Non spécifié' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
// Formatage automatique du montant en CFA
document.getElementById('payment').addEventListener('input', function(e) {
    const paymentInput = e.target;
    const amountDisplay = document.getElementById('showPaymentType');
    
    if (paymentInput.value) {
        // Convertir en nombre
        const amount = parseInt(paymentInput.value) || 0;
        
        // Formater avec séparateurs de milliers
        const formattedAmount = amount.toLocaleString('fr-FR');
        
        // Mettre à jour l'affichage
        amountDisplay.textContent = formattedAmount + ' FCFA';
        
        // Calculer le solde restant
        const totalPrice = {{ $transaction->getTotalPrice($transaction->room->price, $transaction->check_in, $transaction->check_out) }};
        const totalPaid = {{ $transaction->getTotalPayment() }};
        const remaining = totalPrice - totalPaid;
        
        // Vérifier si le montant dépasse le solde
        if (amount > remaining) {
            amountDisplay.classList.remove('text-success');
            amountDisplay.classList.add('text-danger');
            amountDisplay.innerHTML = formattedAmount + ' FCFA <br><small class="text-danger">Montant trop élevé! Solde max: ' + 
                remaining.toLocaleString('fr-FR') + ' FCFA</small>';
        } else {
            amountDisplay.classList.remove('text-danger');
            amountDisplay.classList.add('text-success');
        }
    } else {
        amountDisplay.textContent = '0 FCFA';
        amountDisplay.classList.remove('text-success', 'text-danger');
    }
});

// Empêcher la saisie de valeurs négatives
document.getElementById('payment').addEventListener('keydown', function(e) {
    if (e.key === '-' || e.key === 'e' || e.key === 'E') {
        e.preventDefault();
    }
});
</script>
@endsection