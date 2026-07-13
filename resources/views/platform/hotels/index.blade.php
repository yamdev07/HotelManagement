@extends('platform.layout')

@section('title', 'Hôtels')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-hotel me-2"></i> Hôtels de la plateforme</h3>
        <a href="{{ route('platform.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nouvel hôtel
        </a>
    </div>

    {{-- Synthèse plateforme --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['Revenus totaux', number_format($summary['revenue'], 0, ',', ' ').' CFA', 'fa-sack-dollar', 'text-success'],
                ['Revenu mensuel (actifs)', number_format($summary['mrr'], 0, ',', ' ').' CFA', 'fa-arrow-trend-up', 'text-primary'],
                ['Hôtels actifs', $summary['active'].' / '.$summary['total'], 'fa-hotel', 'text-dark'],
                ['Réabonnements', $summary['renewals'], 'fa-rotate', 'text-info'],
                ['Expirés / suspendus', $summary['expired'], 'fa-triangle-exclamation', 'text-danger'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $icon, $color])
            <div class="col-6 col-md">
                <div class="card border-0 shadow-sm h-100"><div class="card-body">
                    <div class="text-muted small"><i class="fas {{ $icon }} me-1 {{ $color }}"></i>{{ $label }}</div>
                    <div class="fs-4 fw-bold">{{ $value }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hôtel</th>
                        <th class="text-center">Statut</th>
                        <th>Formule</th>
                        <th>Abonnement</th>
                        <th class="text-center">Users</th>
                        <th class="text-center">Chambres</th>
                        <th class="text-center">Transactions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hotels as $hotel)
                        <tr>
                            <td>
                                <a href="{{ route('platform.hotels.show', $hotel) }}" class="fw-bold text-decoration-none">{{ $hotel->name }}</a>
                                <div class="small text-muted">{{ $hotel->slug }} · {{ $hotel->currency }}</div>
                            </td>
                            <td class="text-center">
                                @if ($hotel->hasActiveAccess())
                                    <span class="badge bg-success">Actif</span>
                                @elseif (! $hotel->is_active)
                                    <span class="badge bg-danger">Suspendu</span>
                                @else
                                    <span class="badge bg-warning text-dark">Expiré</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $hotel->planName() }}</span>
                                <div class="small text-muted">{{ number_format($hotel->monthlyPrice(), 0, ',', ' ') }} CFA/mois</div>
                            </td>
                            <td>
                                @if ($hotel->subscription_ends_at)
                                    <span class="small {{ $hotel->isSubscriptionExpired() ? 'text-danger' : 'text-muted' }}">
                                        jusqu'au {{ $hotel->subscription_ends_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="small text-muted">illimité</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $hotel->users_count }}</td>
                            <td class="text-center">{{ $hotel->rooms_count }}</td>
                            <td class="text-center">{{ $hotel->transactions_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('platform.hotels.show', $hotel) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('platform.hotels.edit', $hotel) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('platform.hotels.toggle', $hotel) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    @if ($hotel->is_active)
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Suspendre {{ $hotel->name }} ?')">
                                            <i class="fas fa-ban"></i> Suspendre
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i> Réactiver
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Aucun hôtel pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
