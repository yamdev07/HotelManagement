@extends('template.master')
@section('title', 'Modifier Réservation')
@section('content')
    <style>
        .date-picker-container {
            position: relative;
        }
        .date-picker-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
        }
        .nights-counter {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
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
                        <li class="breadcrumb-item active">Modifier Réservation #{{ $transaction->id }}</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Modifier la Réservation
                    </h2>
                    <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Modifiez les dates et détails de la réservation</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informations de la Réservation</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('transaction.update', $transaction->id) }}" id="edit-transaction-form">
                            @csrf
                            @method('PUT')
                            
                            <!-- Section Client -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Informations Client
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nom du Client</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $transaction->customer->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $transaction->customer->phone ?? 'Non renseigné' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Chambre -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-bed me-2"></i>Informations Chambre
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Numéro de Chambre</label>
                                            <input type="text" class="form-control" 
                                                   value="Chambre {{ $transaction->room->number }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type de Chambre</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $transaction->room->type->name ?? 'Standard' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prix par Nuit (CFA)</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ Helper::formatCFA($transaction->room->price) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Dates (MODIFIABLE) -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Dates de Séjour
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="check_in" class="form-label">Date d'Arrivée *</label>
                                            <div class="date-picker-container">
                                                <input type="date" 
                                                       class="form-control @error('check_in') is-invalid @enderror" 
                                                       id="check_in" 
                                                       name="check_in" 
                                                       value="{{ old('check_in', \Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d')) }}"
                                                       required
                                                       min="{{ now()->format('Y-m-d') }}">
                                                <span class="date-picker-icon">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                @error('check_in')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="check_out" class="form-label">Date de Départ *</label>
                                            <div class="date-picker-container">
                                                <input type="date" 
                                                       class="form-control @error('check_out') is-invalid @enderror" 
                                                       id="check_out" 
                                                       name="check_out" 
                                                       value="{{ old('check_out', \Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d')) }}"
                                                       required
                                                       min="{{ now()->addDay()->format('Y-m-d') }}">
                                                <span class="date-picker-icon">
                                                    <i class="fas fa-calendar"></i>
                                                </span>
                                                @error('check_out')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Calcul des nuits -->
                                <div class="nights-counter">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nombre de Nuits :</strong></p>
                                            <div id="nights-count" class="h4 text-primary">0</div>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nouveau Total :</strong></p>
                                            <div id="new-total" class="h4 text-success">0 CFA</div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Ancien total : <strong>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Paiement -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>État du Paiement
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-success">
                                            <small class="d-block">Ancien Total</small>
                                            <strong class="h5">{{ Helper::formatCFA($transaction->getTotalPrice()) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-info">
                                            <small class="d-block">Déjà Payé</small>
                                            <strong class="h5">{{ Helper::formatCFA($transaction->getTotalPayment()) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                        @endphp
                                        <div class="alert {{ $balance > 0 ? 'alert-warning' : 'alert-success' }}">
                                            <small class="d-block">Solde</small>
                                            <strong class="h5">{{ Helper::formatCFA($balance) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($balance > 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Cette réservation a un solde impayé de {{ Helper::formatCFA($balance) }}.
                                    <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                       class="alert-link">
                                        Ajouter un paiement
                                    </a>
                                </div>
                                @endif
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes supplémentaires</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Ajoutez des notes ou instructions spéciales...">{{ old('notes', $transaction->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-4">
                                <div>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmCancel()">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </button>
                                    <a href="{{ route('transaction.cancel', $transaction->id) }}" 
                                       class="btn btn-outline-dark ms-2"
                                       onclick="return confirm('Annuler cette réservation?')">
                                        <i class="fas fa-ban me-2"></i>Annuler Réservation
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Informations -->
            <div class="col-lg-4">
                <!-- Résumé de la Réservation -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Résumé</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Numéro Réservation</span>
                                <strong>#{{ $transaction->id }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Client</span>
                                <strong>{{ $transaction->customer->name }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Chambre</span>
                                <strong>Chambre {{ $transaction->room->number }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Date de Création</span>
                                <strong>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Dernière Modification</span>
                                <strong>{{ \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div class="list-group-item">
                                <span class="d-block mb-2">Statut</span>
                                @php
                                    $balance = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                                    $statusClass = $balance <= 0 ? 'success' : ($transaction->check_out < now() ? 'danger' : 'primary');
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    @if($balance <= 0)
                                        Payé
                                    @elseif($transaction->check_out < now())
                                        Expiré
                                    @else
                                        Active
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i>Ajouter un Paiement
                            </a>
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-file-invoice me-2"></i>Voir Facture
                            </a>
                            <a href="{{ route('customer.show', $transaction->customer->id) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>Voir Profil Client
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const nightsCount = document.getElementById('nights-count');
    const newTotal = document.getElementById('new-total');
    const roomPricePerNight = {{ $transaction->room->price }};
    
    // Fonction pour calculer les nuits et le total
    function calculateNightsAndTotal() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        
        if (checkIn && checkOut && checkOut > checkIn) {
            const timeDiff = checkOut.getTime() - checkIn.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            nightsCount.textContent = nights;
            newTotal.textContent = (nights * roomPricePerNight).toLocaleString('fr-FR') + ' CFA';
            
            // Validation : départ doit être après arrivée
            if (checkOut <= checkIn) {
                checkOutInput.setCustomValidity('La date de départ doit être après la date d\'arrivée');
            } else {
                checkOutInput.setCustomValidity('');
            }
        } else {
            nightsCount.textContent = '0';
            newTotal.textContent = '0 CFA';
        }
    }
    
    // Écouter les changements de dates
    checkInInput.addEventListener('change', calculateNightsAndTotal);
    checkOutInput.addEventListener('change', calculateNightsAndTotal);
    
    // Calculer au chargement
    calculateNightsAndTotal();
    
    // Fonction de confirmation d'annulation
    window.confirmCancel = function() {
        if (confirm('Voulez-vous vraiment annuler les modifications ?')) {
            window.location.href = "{{ route('transaction.index') }}";
        }
    };
    
    // Validation du formulaire
    document.getElementById('edit-transaction-form').addEventListener('submit', function(e) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        
        if (checkOut <= checkIn) {
            e.preventDefault();
            alert('La date de départ doit être après la date d\'arrivée');
            checkOutInput.focus();
            return false;
        }
        
        // Vérifier si les dates ont changé
        const originalCheckIn = "{{ \Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d') }}";
        const originalCheckOut = "{{ \Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d') }}";
        
        if (checkInInput.value === originalCheckIn && checkOutInput.value === originalCheckOut) {
            if (!confirm('Aucune modification de dates détectée. Souhaitez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // Définir la date minimale pour le départ (jour suivant l'arrivée)
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        // Formater en YYYY-MM-DD pour l'attribut min
        const minDate = nextDay.toISOString().split('T')[0];
        checkOutInput.min = minDate;
        
        // Si la date de départ actuelle est antérieure au nouveau minimum
        if (checkOutInput.value && new Date(checkOutInput.value) < nextDay) {
            checkOutInput.value = minDate;
            calculateNightsAndTotal();
        }
    });
});
</script>
@endsection