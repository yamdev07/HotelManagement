@extends('template.master')
@section('title', 'Confirmation de Réservation par Type')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Messages de session -->
           {{-- DEBUG : Vérifier si la session existe --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            {!! session('success') !!}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @else
                {{-- Message par défaut si pas de session --}}
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">✅ Réservation confirmée !</h5>
                            <p class="mb-0">Réservation #{{ $transaction->id }} créée avec succès.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Réservation par Type Confirmée !
                    </h3>
                    <small>Le numéro de chambre sera attribué au check-in</small>
                </div>
                
                <div class="card-body">
                    <!-- Résumé de la réservation -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informations Client</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nom :</strong> {{ $transaction->customer->name }}</p>
                                    <p><strong>Téléphone :</strong> {{ $transaction->customer->phone ?? 'Non renseigné' }}</p>
                                    <p><strong>Email :</strong> {{ $transaction->customer->email ?? 'Non renseigné' }}</p>
                                    <a href="{{ route('customer.show', $transaction->customer) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Voir le client
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-tag me-2"></i>Type de Chambre</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Type :</strong> {{ $transaction->roomType->name }}</p>
                                    <p><strong>Prix/nuit :</strong> {{ number_format($transaction->roomType->base_price, 0, ',', ' ') }} FCFA</p>
                                    <p><strong>Capacité :</strong> {{ $transaction->roomType->capacity }} personnes</p>
                                    @if(Route::has('type.show'))
                                    <a href="{{ route('type.show', $transaction->roomType) }}" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-info-circle me-1"></i>Détails du type
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Détails du séjour -->
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Détails du Séjour</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</p>
                                    <p><strong>Départ :</strong> {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Durée :</strong> {{ $transaction->getNightsAttribute() }} nuit(s)</p>
                                    <p><strong>Personnes :</strong> {{ $transaction->person_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations financières -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Informations Financières</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Prix total :</strong> {{ number_format($transaction->total_price, 0, ',', ' ') }} FCFA</p>
                                    @if($transaction->total_payment > 0)
                                        <p class="text-success">
                                            <strong>Acompte payé :</strong> {{ number_format($transaction->total_payment, 0, ',', ' ') }} FCFA
                                        </p>
                                        @php
                                            $remaining = $transaction->total_price - $transaction->total_payment;
                                        @endphp
                                        @if($remaining > 0)
                                            <p class="text-danger">
                                                <strong>Solde à payer :</strong> {{ number_format($remaining, 0, ',', ' ') }} FCFA
                                            </p>
                                        @else
                                            <p class="text-success">
                                                <strong>✅ Paiement complet</strong>
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-info">
                                            <strong>À régler à l'arrivée :</strong> {{ number_format($transaction->total_price, 0, ',', ' ') }} FCFA
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($transaction->payments && $transaction->payments->count() > 0)
                                        <h6>Paiements effectués :</h6>
                                        <ul class="list-group list-group-flush">
                                            @foreach($transaction->payments as $payment)
                                                <li class="list-group-item">
                                                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA - 
                                                    {{ $payment->payment_method }} - 
                                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- IMPORTANT : Section attribution -->
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>IMPORTANT</h5>
                        <p class="mb-3">
                            <strong>❌ AUCUN NUMÉRO DE CHAMBRE ATTRIBUÉ</strong><br>
                            Cette réservation est pour un TYPE de chambre. Le numéro exact sera attribué lors du check-in du client.
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informations :</h6>
                                <ul class="mb-0">
                                    <li>Référence : <strong>#{{ $transaction->id }}</strong></li>
                                    <li>Créée par : {{ $transaction->user->name ?? 'Système' }}</li>
                                    <li>Date de création : {{ $transaction->created_at->format('d/m/Y H:i') }}</li>
                                    <li>Statut : <span class="badge bg-info">Réservation par type</span></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Actions disponibles :</h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('transaction.edit', $transaction) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i>Modifier la réservation
                                    </a>
                                    <a href="{{ route('transaction.payment.create', $transaction) }}" 
                                       class="btn btn-outline-success">
                                        <i class="fas fa-money-bill-wave me-2"></i>Ajouter un paiement
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transaction.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list me-2"></i>Retour aux réservations
                        </a>
                        
                        <div class="btn-group">
                            <a href="{{ route('transaction.edit', $transaction) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <a href="{{ route('transaction.payment.create', $transaction) }}" class="btn btn-success">
                                <i class="fas fa-money-bill-wave me-2"></i>Ajouter un paiement
                            </a>
                            <a href="{{ route('transaction.invoice', $transaction) }}" target="_blank" class="btn btn-info">
                                <i class="fas fa-file-invoice me-2"></i>Facture
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection