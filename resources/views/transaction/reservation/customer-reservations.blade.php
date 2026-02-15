@extends('template.master')

@section('title', 'Réservations Client')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-history me-2"></i>
                        Historique des réservations
                    </h1>
                    <p class="text-muted mb-0">
                        Client: <strong>{{ $customer->name }}</strong> | 
                        Email: {{ $customer->email }} | 
                        Téléphone: {{ $customer->phone }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('transaction.reservation.pickFromCustomer') }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total réservations</h6>
                            <h3 class="fw-bold text-primary">{{ $reservations->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Actives</h6>
                            <h3 class="fw-bold text-success">
                                {{ $customer->transactions()->where('status', 'active')->count() }}
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Terminées</h6>
                            <h3 class="fw-bold text-info">
                                {{ $customer->transactions()->where('status', 'completed')->count() }}
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag-checkered fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Annulées</h6>
                            <h3 class="fw-bold text-danger">
                                {{ $customer->transactions()->where('status', 'cancelled')->count() }}
                            </h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des réservations -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Historique des réservations</strong>
                        </div>
                        <span class="badge bg-light text-primary">
                            {{ $reservations->total() }} réservation(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">ID</th>
                                        <th class="py-3">Chambre</th>
                                        <th class="py-3">Dates</th>
                                        <th class="py-3">Statut</th>
                                        <th class="py-3">Montant</th>
                                        <th class="py-3">Paiement</th>
                                        <th class="py-3">Créé le</th>
                                        <th class="py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                    <tr>
                                        <td class="py-3">
                                            <strong>#{{ $reservation->id }}</strong>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="room-badge bg-primary me-3">
                                                    {{ $reservation->room->number }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $reservation->room->type->name ?? 'Standard' }}</div>
                                                    <small class="text-muted">Étage: {{ $reservation->room->floor ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold">{{ $reservation->check_in->format('d/m/Y') }}</div>
                                            <small class="text-muted">au {{ $reservation->check_out->format('d/m/Y') }}</small>
                                            <div class="mt-1">
                                                <span class="badge bg-info">
                                                    {{ $reservation->check_in->diffInDays($reservation->check_out) }} nuit(s)
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $reservation->status_color }} p-2">
                                                <i class="fas fa-{{ $reservation->status_icon }} me-1"></i>
                                                {{ $reservation->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold">{{ number_format($reservation->getTotalPrice(), 0, ',', ' ') }} FCFA</div>
                                            @if($reservation->down_payment > 0)
                                                <small class="text-muted">
                                                    Acompte: {{ number_format($reservation->down_payment, 0, ',', ' ') }} FCFA
                                                </small>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $totalPaid = $reservation->getTotalPayment();
                                                $totalPrice = $reservation->getTotalPrice();
                                                $percentage = $totalPrice > 0 ? ($totalPaid / $totalPrice) * 100 : 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $percentage >= 100 ? 'success' : 'warning' }}" 
                                                         style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <small class="fw-bold">{{ round($percentage) }}%</small>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ number_format($totalPaid, 0, ',', ' ') }} / {{ number_format($totalPrice, 0, ',', ' ') }} FCFA
                                            </small>
                                        </td>
                                        <td class="py-3">
                                            {{ $reservation->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-3 text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('transaction.show', $reservation->id) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('transaction.invoice', $reservation->id) }}" 
                                                   class="btn btn-outline-info">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                                @if($reservation->status == 'active')
                                                <a href="{{ route('transaction.extend', $reservation->id) }}" 
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="text-muted">
                                Affichage de {{ $reservations->firstItem() }} à {{ $reservations->lastItem() }} 
                                sur {{ $reservations->total() }} réservations
                            </div>
                            <div>
                                {{ $reservations->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h4 class="text-dark mb-3">Aucune réservation</h4>
                            <p class="text-muted mb-4">Ce client n'a pas encore effectué de réservation</p>
                            <a href="{{ route('transaction.reservation.createIdentity') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>
                                Créer une nouvelle réservation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .room-badge {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page historique des réservations chargée');
});
</script>
@endpush