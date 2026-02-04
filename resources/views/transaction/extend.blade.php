@extends('template.master')
@section('title', 'Prolonger la Réservation #' . $transaction->id)
@section('content')

<style>
    .extend-card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .date-preview {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .price-breakdown {
        background-color: #e8f4f8;
        border-left: 4px solid #17a2b8;
        padding: 15px;
        border-radius: 6px;
    }
    
    .night-option {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .night-option:hover {
        border-color: #0dcaf0;
        background-color: #f8f9fa;
    }
    
    .night-option.selected {
        border-color: #198754;
        background-color: #d1e7dd;
    }
    
    .night-badge {
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.index') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('transaction.index') }}">Réservations</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('transaction.show', $transaction) }}">#{{ $transaction->id }}</a>
                    </li>
                    <li class="breadcrumb-item active">Prolonger</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">
                    <i class="fas fa-calendar-plus text-primary me-2"></i>
                    Prolonger la Réservation #{{ $transaction->id }}
                </h2>
                <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux détails
                </a>
            </div>
            <p class="text-muted">Ajoutez des nuits supplémentaires au séjour du client</p>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error') || session('failed'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {!! session('error') ?? session('failed') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card extend-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Prolonger le séjour</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('transaction.extend.process', $transaction) }}" id="extend-form">
                        @csrf
                        
                        <!-- Informations actuelles -->
                        <div class="alert alert-info mb-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Séjour actuel</h6>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small class="text-muted">Client</small>
                                    <p class="mb-1"><strong>{{ $transaction->customer->name }}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Chambre</small>
                                    <p class="mb-1"><strong>Chambre {{ $transaction->room->number }}</strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">Arrivée</small>
                                    <p class="mb-1">
                                        <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Départ actuel</small>
                                    <p class="mb-1">
                                        <strong>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Nuits actuelles</small>
                                    <p class="mb-1">
                                        <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out) }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sélection du nombre de nuits supplémentaires -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-moon me-2"></i>Nombre de nuits supplémentaires
                            </h6>
                            
                            <div class="row" id="nights-options">
                                <!-- Options de nuits prédéfinies -->
                                <div class="col-md-3">
                                    <div class="night-option" data-nights="1" onclick="selectNights(1)">
                                        <div class="text-center">
                                            <div class="h4 mb-1">1</div>
                                            <small class="text-muted">Nuit</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="night-option" data-nights="2" onclick="selectNights(2)">
                                        <div class="text-center">
                                            <div class="h4 mb-1">2</div>
                                            <small class="text-muted">Nuits</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="night-option" data-nights="3" onclick="selectNights(3)">
                                        <div class="text-center">
                                            <div class="h4 mb-1">3</div>
                                            <small class="text-muted">Nuits</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="night-option" data-nights="7" onclick="selectNights(7)">
                                        <div class="text-center">
                                            <div class="h4 mb-1">7</div>
                                            <small class="text-muted">Nuits</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sélection personnalisée -->
                            <div class="mt-3">
                                <label for="additional_nights" class="form-label">
                                    <i class="fas fa-sliders-h me-1"></i>Ou nombre personnalisé
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('additional_nights') is-invalid @enderror" 
                                           id="additional_nights" 
                                           name="additional_nights" 
                                           min="1" 
                                           max="30" 
                                           value="{{ old('additional_nights', 1) }}"
                                           required
                                           onchange="updatePreview()">
                                    <span class="input-group-text">nuit(s)</span>
                                    @error('additional_nights')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Maximum 30 nuits supplémentaires</small>
                            </div>
                        </div>

                        <!-- Nouvelle date de départ -->
                        <div class="mb-4">
                            <label for="new_check_out" class="form-label fw-bold">
                                <i class="fas fa-calendar-day me-1"></i>Nouvelle date de départ
                            </label>
                            <div class="input-group">
                                <input type="date" 
                                       class="form-control @error('new_check_out') is-invalid @enderror" 
                                       id="new_check_out" 
                                       name="new_check_out" 
                                       value="{{ old('new_check_out', $suggestedDate->format('Y-m-d')) }}"
                                       required
                                       onchange="updateNightsFromDate()">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                @error('new_check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Départ actuel : {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}
                            </small>
                        </div>

                        <!-- Prévisualisation -->
                        <div class="date-preview mb-4">
                            <h6><i class="fas fa-eye me-2"></i>Prévisualisation</h6>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <p class="mb-1"><small>Nuits actuelles</small></p>
                                    <p id="current-nights" class="h4 mb-0">0</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p class="mb-1"><small>Nuits supplémentaires</small></p>
                                    <p id="additional-nights-preview" class="h4 mb-0 text-primary">0</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p class="mb-1"><small>Total nuits</small></p>
                                    <p id="total-nights" class="h4 mb-0 text-success">0</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Nouvelle date de départ</small></p>
                                    <p id="new-check-out-preview" class="h5 mb-0">-</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Statut de disponibilité</small></p>
                                    <p id="availability-status" class="mb-0">
                                        <span class="badge bg-secondary">Non vérifié</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Détails du prix -->
                        <div class="price-breakdown mb-4">
                            <h6><i class="fas fa-calculator me-2"></i>Détails du prix</h6>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Prix par nuit</small></p>
                                    <p class="h5 mb-0 text-info">
                                        {{ number_format($transaction->room->price, 0, ',', ' ') }} CFA
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Total actuel</small></p>
                                    <p class="h5 mb-0">
                                        {{ number_format($transaction->getTotalPrice(), 0, ',', ' ') }} CFA
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Supplément</small></p>
                                    <p id="additional-price" class="h4 mb-0 text-primary">0 CFA</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><small>Nouveau total</small></p>
                                    <p id="new-total-price" class="h4 mb-0 text-success">0 CFA</p>
                                </div>
                            </div>
                        </div>

                        <!-- Vérification de disponibilité -->
                        <div class="mb-4">
                            <button type="button" id="check-availability-btn" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search me-2"></i>Vérifier disponibilité de la chambre
                            </button>
                            <div id="availability-result" class="mt-2"></div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes de prolongation (optionnel)
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Raison de la prolongation, instructions spéciales...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="extend-btn">
                                <i class="fas fa-calendar-plus me-2"></i>Confirmer la prolongation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Informations -->
        <div class="col-lg-4">
            <!-- Résumé -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user me-2 text-muted"></i>Client</span>
                            <strong>{{ $transaction->customer->name }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bed me-2 text-muted"></i>Chambre</span>
                            <strong>Chambre {{ $transaction->room->number }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-check me-2 text-muted"></i>Arrivée</span>
                            <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-times me-2 text-muted"></i>Départ actuel</span>
                            <strong>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-money-bill me-2 text-muted"></i>Prix/nuit</span>
                            <strong>{{ number_format($transaction->room->price, 0, ',', ' ') }} CFA</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-chart-line me-2 text-muted"></i>Statut</span>
                            <span class="badge bg-{{ $transaction->status == 'active' ? 'success' : 'warning' }}">
                                {{ $transaction->status == 'active' ? 'Dans l\'hôtel' : 'Réservation' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>La prolongation prend effet immédiatement</li>
                        <li>Le supplément sera ajouté au total de la réservation</li>
                        <li>Vérifiez toujours la disponibilité de la chambre</li>
                        <li>Le client sera notifié de la prolongation</li>
                        <li>Toute modification est enregistrée dans l'historique</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricePerNight = {{ $transaction->room->price }};
    const currentCheckOut = "{{ $transaction->check_out->format('Y-m-d') }}";
    const currentNights = {{ \Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out) }};
    const currentTotal = {{ $transaction->getTotalPrice() }};
    
    // Formater le prix
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR').format(price) + ' CFA';
    }
    
    // Mettre à jour la prévisualisation
    function updatePreview() {
        const additionalNights = parseInt(document.getElementById('additional_nights').value) || 0;
        const newCheckOutInput = document.getElementById('new_check_out');
        
        // Calculer la nouvelle date de départ
        const currentCheckOutDate = new Date(currentCheckOut);
        const newCheckOutDate = new Date(currentCheckOutDate);
        newCheckOutDate.setDate(newCheckOutDate.getDate() + additionalNights);
        
        // Mettre à jour le champ date
        const formattedDate = newCheckOutDate.toISOString().split('T')[0];
        newCheckOutInput.value = formattedDate;
        
        // Mettre à jour l'affichage
        document.getElementById('additional-nights-preview').textContent = additionalNights;
        document.getElementById('total-nights').textContent = currentNights + additionalNights;
        document.getElementById('current-nights').textContent = currentNights;
        document.getElementById('new-check-out-preview').textContent = 
            newCheckOutDate.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
        
        // Calculer les prix
        const additionalPrice = additionalNights * pricePerNight;
        const newTotalPrice = currentTotal + additionalPrice;
        
        document.getElementById('additional-price').textContent = formatPrice(additionalPrice);
        document.getElementById('new-total-price').textContent = formatPrice(newTotalPrice);
        
        // Mettre à jour les options sélectionnées
        updateSelectedNightOption(additionalNights);
    }
    
    // Mettre à jour le nombre de nuits à partir de la date
    function updateNightsFromDate() {
        const newCheckOut = document.getElementById('new_check_out').value;
        if (!newCheckOut) return;
        
        const currentCheckOutDate = new Date(currentCheckOut);
        const newCheckOutDate = new Date(newCheckOut);
        
        // Calculer la différence en jours
        const timeDiff = newCheckOutDate.getTime() - currentCheckOutDate.getTime();
        const additionalNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (additionalNights > 0) {
            document.getElementById('additional_nights').value = additionalNights;
            updatePreview();
        }
    }
    
    // Sélectionner une option de nuits
    window.selectNights = function(nights) {
        document.getElementById('additional_nights').value = nights;
        updatePreview();
    }
    
    // Mettre à jour l'option sélectionnée
    function updateSelectedNightOption(nights) {
        document.querySelectorAll('.night-option').forEach(option => {
            const optionNights = parseInt(option.getAttribute('data-nights'));
            if (optionNights === nights) {
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    }
    
    // Vérifier la disponibilité
    async function checkAvailability() {
        const newCheckOut = document.getElementById('new_check_out').value;
        const transactionId = {{ $transaction->id }};
        
        if (!newCheckOut) {
            alert('Veuillez d\'abord sélectionner une date de départ');
            return;
        }
        
        if (new Date(newCheckOut) <= new Date(currentCheckOut)) {
            alert('La nouvelle date de départ doit être après la date actuelle');
            return;
        }
        
        try {
            // Afficher chargement
            const checkBtn = document.getElementById('check-availability-btn');
            const originalText = checkBtn.innerHTML;
            checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Vérification...';
            checkBtn.disabled = true;
            
            const response = await fetch(`/transaction/${transactionId}/check-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    check_in: "{{ $transaction->check_in->format('Y-m-d') }}",
                    check_out: newCheckOut,
                    transaction_id: transactionId
                })
            });
            
            const data = await response.json();
            
            // Restaurer le bouton
            checkBtn.innerHTML = originalText;
            checkBtn.disabled = false;
            
            // Afficher le résultat
            const resultDiv = document.getElementById('availability-result');
            const statusBadge = document.getElementById('availability-status');
            
            if (data.available) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Disponible !</strong> La chambre est libre pour la période de prolongation.
                    </div>
                `;
                statusBadge.innerHTML = '<span class="badge bg-success">Disponible ✓</span>';
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Non disponible !</strong> ${data.message}
                    </div>
                `;
                statusBadge.innerHTML = '<span class="badge bg-danger">Non disponible ✗</span>';
            }
            
        } catch (error) {
            console.error('Erreur:', error);
            document.getElementById('availability-result').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors de la vérification
                </div>
            `;
            
            // Restaurer le bouton
            const checkBtn = document.getElementById('check-availability-btn');
            checkBtn.innerHTML = '<i class="fas fa-search me-2"></i>Vérifier disponibilité de la chambre';
            checkBtn.disabled = false;
        }
    }
    
    // Attacher l'événement au bouton de vérification
    document.getElementById('check-availability-btn').addEventListener('click', checkAvailability);
    
    // Validation du formulaire
    document.getElementById('extend-form').addEventListener('submit', function(e) {
        const additionalNights = parseInt(document.getElementById('additional_nights').value) || 0;
        const newCheckOut = document.getElementById('new_check_out').value;
        
        if (additionalNights < 1 || additionalNights > 30) {
            e.preventDefault();
            alert('Le nombre de nuits supplémentaires doit être entre 1 et 30');
            return false;
        }
        
        if (new Date(newCheckOut) <= new Date(currentCheckOut)) {
            e.preventDefault();
            alert('La nouvelle date de départ doit être après la date actuelle');
            return false;
        }
        
        // Confirmation
        const confirmationMessage = `Confirmez-vous la prolongation du séjour ?\n\n` +
                                  `+${additionalNights} nuit(s) supplémentaire(s)\n` +
                                  `Nouvelle date de départ: ${new Date(newCheckOut).toLocaleDateString('fr-FR')}\n` +
                                  `Supplément: ${formatPrice(additionalNights * pricePerNight)}`;
        
        if (!confirm(confirmationMessage)) {
            e.preventDefault();
            return false;
        }
        
        // Désactiver le bouton pour éviter double soumission
        const extendBtn = document.getElementById('extend-btn');
        extendBtn.disabled = true;
        extendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
        
        return true;
    });
    
    // Initialiser
    updatePreview();
    
    // Définir la date minimale pour le nouveau départ
    const currentDate = new Date(currentCheckOut);
    currentDate.setDate(currentDate.getDate() + 1);
    document.getElementById('new_check_out').min = currentDate.toISOString().split('T')[0];
});
</script>
@endsection