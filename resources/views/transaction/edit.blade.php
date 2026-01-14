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
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-reservation { background-color: #fff3cd; color: #856404; }
        .status-active { background-color: #d1e7dd; color: #0f5132; }
        .status-completed { background-color: #cfe2ff; color: #084298; }
        .status-cancelled { background-color: #e9ecef; color: #495057; }
        .status-no_show { background-color: #6c757d; color: #ffffff; }
        .alert-status {
            border-left: 4px solid;
            padding-left: 15px;
        }
        .alert-status-reservation { border-left-color: #ffc107; }
        .alert-status-active { border-left-color: #198754; }
        .alert-status-completed { border-left-color: #0dcaf0; }
        .alert-status-cancelled { border-left-color: #dc3545; }
        .alert-status-no_show { border-left-color: #6c757d; }
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
                        Modifier la Réservation #{{ $transaction->id }}
                    </h2>
                    <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <p class="text-muted">Modifiez les dates, statut et détails de la réservation</p>
            </div>
        </div>

        <!-- Messages de session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error') || session('failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') ?? session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Avertissement si réservation annulée ou expirée -->
        @if($transaction->status == 'cancelled')
            <div class="alert alert-danger">
                <i class="fas fa-ban me-2"></i>
                Cette réservation est annulée et ne peut pas être modifiée.
                @if($transaction->cancelled_at)
                    <br><small>Annulée le : {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i') }}</small>
                    @if($transaction->cancel_reason)
                        <br><small>Raison : {{ $transaction->cancel_reason }}</small>
                    @endif
                @endif
            </div>
        @endif

        @if($transaction->check_out < now() && $transaction->status == 'active')
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Cette réservation est expirée (départ passé). Certaines modifications peuvent être limitées.
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informations de la Réservation</h5>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ $transaction->status_label }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Avertissement selon le statut -->
                        @if($transaction->status == 'reservation')
                            <div class="alert alert-warning alert-status alert-status-reservation">
                                <i class="fas fa-calendar-check me-2"></i>
                                <strong>📅 Réservation</strong> - Le client n'est pas encore arrivé à l'hôtel.
                            </div>
                        @elseif($transaction->status == 'active')
                            <div class="alert alert-success alert-status alert-status-active">
                                <i class="fas fa-bed me-2"></i>
                                <strong>🏨 Dans l'hôtel</strong> - Le client est actuellement en séjour.
                            </div>
                        @elseif($transaction->status == 'completed')
                            <div class="alert alert-info alert-status alert-status-completed">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>✅ Séjour terminé</strong> - Le client est parti, le séjour est terminé.
                            </div>
                        @elseif($transaction->status == 'no_show')
                            <div class="alert alert-secondary alert-status alert-status-no_show">
                                <i class="fas fa-user-slash me-2"></i>
                                <strong>👤 No Show</strong> - Le client ne s'est pas présenté.
                            </div>
                        @endif

                        @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $transaction->customer->email ?? 'Non renseigné' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Historique</label>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('transaction.reservation.customerReservations', $transaction->customer) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-history me-1"></i> Voir ses réservations
                                                </a>
                                                <a href="{{ route('customer.show', $transaction->customer) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Voir profil
                                                </a>
                                            </div>
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
                                            <div class="input-group">
                                                <input type="text" class="form-control" 
                                                       value="Chambre {{ $transaction->room->number }}" readonly>
                                                <span class="input-group-text bg-info text-white">
                                                    <i class="fas fa-door-closed"></i>
                                                </span>
                                            </div>
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Statut Chambre</label>
                                            <input type="text" class="form-control" 
                                                   value="{{ $transaction->room->roomStatus->name ?? 'Indisponible' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Dates (MODIFIABLE) -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Dates de Séjour
                                    @if($transaction->status == 'cancelled' || $transaction->status == 'no_show' || $transaction->status == 'completed')
                                        <small class="text-danger">(Modification limitée)</small>
                                    @endif
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
                                                       @if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])) readonly @endif
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
                                                       @if(in_array($transaction->status, ['cancelled', 'no_show', 'completed'])) readonly @endif
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
                                        <strong>Ancien total :</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}
                                        <br>
                                        <strong>Déjà payé :</strong> {{ Helper::formatCFA($transaction->getTotalPayment()) }}
                                    </div>
                                </div>

                                <!-- Vérification de disponibilité -->
                                @if(in_array($transaction->status, ['reservation', 'active']))
                                <div class="mt-3">
                                    <button type="button" id="check-availability-btn" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-search me-2"></i>Vérifier disponibilité des nouvelles dates
                                    </button>
                                    <div id="availability-result" class="mt-2"></div>
                                </div>
                                @endif
                            </div>

                            <!-- Section Statut (Nouveau) -->
                            @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-exchange-alt me-2"></i>Modifier le Statut
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Statut de la réservation</label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                @foreach([
                                                    'reservation' => '📅 Réservation (pas encore arrivé)',
                                                    'active' => '🏨 Dans l\'hôtel (séjour en cours)',
                                                    'completed' => '✅ Séjour terminé (est parti)',
                                                    'cancelled' => '❌ Annulée',
                                                    'no_show' => '👤 No Show (pas venu)'
                                                ] as $value => $label)
                                                    <option value="{{ $value }}" 
                                                            {{ old('status', $transaction->status) == $value ? 'selected' : '' }}
                                                            data-desc="{{ [
                                                                'reservation' => 'Client pas encore arrivé',
                                                                'active' => 'Client dans l\'hôtel',
                                                                'completed' => 'Client parti, séjour terminé',
                                                                'cancelled' => 'Réservation annulée',
                                                                'no_show' => 'Client ne s\'est pas présenté'
                                                            ][$value] }}">
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text" id="status-description">
                                                {{ [
                                                    'reservation' => 'Client pas encore arrivé',
                                                    'active' => 'Client dans l\'hôtel',
                                                    'completed' => 'Client parti, séjour terminé',
                                                    'cancelled' => 'Réservation annulée',
                                                    'no_show' => 'Client ne s\'est pas présenté'
                                                ][$transaction->status] }}
                                            </div>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Champ raison d'annulation (conditionnel) -->
                                <div id="cancel-reason-field" style="display: none;">
                                    <div class="mb-3">
                                        <label for="cancel_reason" class="form-label">Raison de l'annulation</label>
                                        <textarea class="form-control @error('cancel_reason') is-invalid @enderror" 
                                                  id="cancel_reason" 
                                                  name="cancel_reason" 
                                                  rows="2"
                                                  placeholder="Pourquoi annuler cette réservation ? (optionnel)">{{ old('cancel_reason', $transaction->cancel_reason) }}</textarea>
                                        @error('cancel_reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Section Paiement -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>État du Paiement
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-secondary">
                                            <small class="d-block">Total Réservation</small>
                                            <strong class="h5" id="current-total">{{ Helper::formatCFA($transaction->getTotalPrice()) }}</strong>
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
                                            <small class="d-block">Solde à Payer</small>
                                            <strong class="h5">{{ Helper::formatCFA($balance) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($balance > 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Cette réservation a un solde impayé de {{ Helper::formatCFA($balance) }}.
                                    <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                                       class="alert-link ms-2">
                                        <i class="fas fa-plus-circle me-1"></i>Ajouter un paiement
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
                                    <button type="button" class="btn btn-outline-secondary" onclick="confirmCancel()">
                                        <i class="fas fa-times me-2"></i>Annuler les modifications
                                    </button>
                                    @if(in_array($transaction->status, ['reservation', 'active']) && in_array(auth()->user()->role, ['Super', 'Admin']))
                                    <form action="{{ route('transaction.cancel', $transaction->id) }}" 
                                          method="POST" 
                                          class="d-inline ms-2"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-ban me-2"></i>Annuler Réservation
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="save-button">
                                        <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Vous n'avez pas les permissions nécessaires pour modifier cette réservation.
                            Seuls les administrateurs et le personnel de réception peuvent modifier les réservations.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - Informations -->
            <div class="col-lg-4">
                <!-- Résumé de la Réservation -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Résumé</h5>
                        <span class="badge bg-primary">#{{ $transaction->id }}</span>
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
                                <span><i class="fas fa-calendar me-2 text-muted"></i>Arrivée</span>
                                <strong>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar me-2 text-muted"></i>Départ</span>
                                <strong>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-moon me-2 text-muted"></i>Nuits</span>
                                <strong>{{ $transaction->nights }} nuit{{ $transaction->nights > 1 ? 's' : '' }}</strong>
                            </div>
                            <div class="list-group-item">
                                <span class="d-block mb-2"><i class="fas fa-chart-line me-2 text-muted"></i>Statut Actuel</span>
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ $transaction->status_label }}
                                </span>
                                @if($transaction->cancelled_at)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Annulée le : {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <!-- Actions selon le statut -->
                            @if($transaction->status == 'reservation' && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                                <form action="{{ route('transaction.mark-arrived', $transaction->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-success mb-2">
                                        <i class="fas fa-sign-in-alt me-2"></i>Marquer comme arrivé
                                    </button>
                                </form>
                            @endif

                            @if($transaction->status == 'active' && in_array(auth()->user()->role, ['Super', 'Admin', 'Reception']))
                                <form action="{{ route('transaction.mark-departed', $transaction->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-info mb-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Marquer comme parti
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('transaction.payment.create', ['transaction' => $transaction->id]) }}" 
                               class="btn btn-outline-success mb-2">
                                <i class="fas fa-credit-card me-2"></i>Ajouter un Paiement
                            </a>
                            
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" 
                               class="btn btn-outline-primary mb-2">
                                <i class="fas fa-file-invoice me-2"></i>Voir Facture
                            </a>
                            
                            <a href="{{ route('customer.show', $transaction->customer->id) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>Voir Profil Client
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Historique -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Historique</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <small class="text-muted">Créée le</small><br>
                                <strong>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</strong>
                            </li>
                            <li class="mb-2">
                                <small class="text-muted">Dernière modification</small><br>
                                <strong>{{ \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y H:i') }}</strong>
                            </li>
                            @if($transaction->cancelled_at)
                            <li class="mb-2">
                                <small class="text-muted">Annulée le</small><br>
                                <strong>{{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y H:i') }}</strong>
                            </li>
                            @endif
                        </ul>
                        <a href="{{ route('transaction.history', $transaction->id) }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-history me-1"></i> Voir l'historique complet
                        </a>
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
    const currentTotal = document.getElementById('current-total');
    const roomPricePerNight = {{ $transaction->room->price }};
    const statusSelect = document.getElementById('status');
    const statusDescription = document.getElementById('status-description');
    const cancelReasonField = document.getElementById('cancel-reason-field');
    const cancelReasonTextarea = document.getElementById('cancel_reason');
    const saveButton = document.getElementById('save-button');
    const transactionId = {{ $transaction->id }};
    const originalStatus = "{{ $transaction->status }}";
    
    // Fonction pour calculer les nuits et le total
    function calculateNightsAndTotal() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        
        if (checkIn && checkOut && checkOut > checkIn) {
            const timeDiff = checkOut.getTime() - checkIn.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            nightsCount.textContent = nights;
            const total = nights * roomPricePerNight;
            newTotal.textContent = total.toLocaleString('fr-FR') + ' CFA';
            currentTotal.textContent = total.toLocaleString('fr-FR') + ' CFA';
            
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
    
    // Gérer le champ raison d'annulation
    function toggleCancelReasonField() {
        if (statusSelect.value === 'cancelled') {
            cancelReasonField.style.display = 'block';
            if (!cancelReasonTextarea.value) {
                cancelReasonTextarea.value = "Annulation depuis l'interface d'édition";
            }
        } else {
            cancelReasonField.style.display = 'none';
            cancelReasonTextarea.value = '';
        }
    }
    
    // Mettre à jour la description du statut
    function updateStatusDescription() {
        const selectedOption = statusSelect.options[statusSelect.selectedIndex];
        const description = selectedOption.getAttribute('data-desc');
        statusDescription.textContent = description;
        toggleCancelReasonField();
    }
    
    // Vérifier la disponibilité des nouvelles dates
    async function checkAvailability() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        
        if (!checkIn || !checkOut) {
            alert('Veuillez sélectionner les deux dates');
            return;
        }
        
        if (new Date(checkOut) <= new Date(checkIn)) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        try {
            const response = await fetch(`/transaction/${transactionId}/check-availability?check_in=${checkIn}&check_out=${checkOut}`);
            const data = await response.json();
            
            const resultDiv = document.getElementById('availability-result');
            if (data.available) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        ${data.message} pour les nouvelles dates.
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        ${data.message}
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erreur:', error);
            document.getElementById('availability-result').innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors de la vérification
                </div>
            `;
        }
    }
    
    // Écouter les changements de dates
    checkInInput.addEventListener('change', calculateNightsAndTotal);
    checkOutInput.addEventListener('change', calculateNightsAndTotal);
    
    // Écouter le changement de statut
    statusSelect.addEventListener('change', updateStatusDescription);
    
    // Bouton vérification disponibilité
    const checkAvailabilityBtn = document.getElementById('check-availability-btn');
    if (checkAvailabilityBtn) {
        checkAvailabilityBtn.addEventListener('click', checkAvailability);
    }
    
    // Calculer au chargement
    calculateNightsAndTotal();
    updateStatusDescription();
    
    // Fonction de confirmation d'annulation
    window.confirmCancel = function() {
        if (confirm('Voulez-vous vraiment annuler les modifications ? Toutes les modifications seront perdues.')) {
            window.location.href = "{{ route('transaction.index') }}";
        }
    };
    
    // Validation du formulaire
    document.getElementById('edit-transaction-form').addEventListener('submit', function(e) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const newStatus = statusSelect.value;
        
        // Vérification dates
        if (checkOut <= checkIn) {
            e.preventDefault();
            alert('La date de départ doit être après la date d\'arrivée');
            checkOutInput.focus();
            return false;
        }
        
        // Vérification statut annulation
        if (newStatus === 'cancelled') {
            if (!confirm('Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible.')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Vérification statut no show
        if (newStatus === 'no_show') {
            if (!confirm('Marquer comme "No Show" ? Le client ne s\'est pas présenté.')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Vérifier si des modifications ont été faites
        const originalCheckIn = "{{ \Carbon\Carbon::parse($transaction->check_in)->format('Y-m-d') }}";
        const originalCheckOut = "{{ \Carbon\Carbon::parse($transaction->check_out)->format('Y-m-d') }}";
        const originalNotes = "{{ $transaction->notes ?? '' }}";
        const currentNotes = document.getElementById('notes').value;
        
        if (checkInInput.value === originalCheckIn && 
            checkOutInput.value === originalCheckOut && 
            newStatus === originalStatus && 
            currentNotes === originalNotes) {
            if (!confirm('Aucune modification détectée. Souhaitez-vous quand même enregistrer ?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Désactiver le bouton pour éviter double soumission
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
        
        return true;
    });
    
    // Définir la date minimale pour le départ (jour suivant l'arrivée)
    checkInInput.addEventListener('change', function() {
        if (this.disabled) return;
        
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
    
    // Initialiser les dates min
    if (checkInInput.value) {
        const checkInDate = new Date(checkInInput.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        const minDate = nextDay.toISOString().split('T')[0];
        checkOutInput.min = minDate;
    }
});
</script>
@endsection