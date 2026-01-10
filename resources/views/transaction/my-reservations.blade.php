@extends('template.master')
@section('title', 'Mes Réservations')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>
                <i class="fas fa-calendar-alt me-2"></i>
                @if($isCustomer)
                    Mes Réservations
                @else
                    Toutes les Réservations
                @endif
            </h2>
        </div>
    </div>

    <!-- Si client, montrer seulement ses réservations -->
    @if($isCustomer)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Vous voyez ici uniquement vos propres réservations.
    </div>
    @endif

    <!-- Liste des réservations actives -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-clock me-2"></i>Réservations Actives
                <span class="badge bg-primary">{{ $transactions->count() }}</span>
            </h5>
            
            @if($transactions->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chambre</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Nuits</th>
                                    <th>Total</th>
                                    <th>Payé</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->room->number }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                    <td>{{ $transaction->getDateDifferenceWithPlural() }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPayment()) }}</td>
                                    <td>
                                        @if($transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0)
                                            <span class="badge bg-success">Payé</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaction.show.public', $transaction->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucune réservation active.
            </div>
            @endif
        </div>
    </div>

    <!-- Historique des réservations -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i>Anciennes Réservations
                <span class="badge bg-secondary">{{ $transactionsExpired->count() }}</span>
            </h5>
            
            @if($transactionsExpired->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chambre</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Nuits</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactionsExpired as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->room->number }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_in) }}</td>
                                    <td>{{ Helper::dateFormat($transaction->check_out) }}</td>
                                    <td>{{ $transaction->getDateDifferenceWithPlural() }}</td>
                                    <td>{{ Helper::formatCFA($transaction->getTotalPrice()) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">Terminée</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucune ancienne réservation.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection