@extends('platform.layout')

@section('title', $hotel->name)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('platform.hotels.index') }}" class="text-decoration-none small text-muted">← Tous les hôtels</a>
            <h3 class="fw-bold mb-1 mt-1">{{ $hotel->name }}
                @if ($hotel->hasActiveAccess())
                    <span class="badge bg-success align-middle">Actif</span>
                @elseif (! $hotel->is_active)
                    <span class="badge bg-danger align-middle">Suspendu</span>
                @else
                    <span class="badge bg-warning text-dark align-middle">Expiré</span>
                @endif
            </h3>
            <div class="text-muted">{{ $hotel->planName() }} · {{ number_format($hotel->monthlyPrice(), 0, ',', ' ') }} CFA/mois · <a href="{{ $hotel->publicUrl() }}" target="_blank">voir le site</a></div>
        </div>
        <a href="{{ route('platform.hotels.edit', $hotel) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-pen me-1"></i>Modifier</a>
    </div>

    {{-- Chiffres clés --}}
    <div class="row g-3 mb-4">
        @php
            $tiles = [
                ['Total encaissé', number_format($hotel->totalPaid(), 0, ',', ' ').' CFA', 'fa-sack-dollar'],
                ['Réabonnements', $hotel->renewalsCount(), 'fa-rotate'],
                ['Utilisateurs', $hotel->users_count, 'fa-users'],
                ['Chambres', $hotel->rooms_count, 'fa-bed'],
                ['Transactions', $hotel->transactions_count, 'fa-receipt'],
            ];
        @endphp
        @foreach ($tiles as [$label, $value, $icon])
            <div class="col-6 col-md">
                <div class="card border-0 shadow-sm h-100"><div class="card-body">
                    <div class="text-muted small"><i class="fas {{ $icon }} me-1"></i>{{ $label }}</div>
                    <div class="fs-4 fw-bold">{{ $value }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- Infos + renouvellement --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3"><div class="card-body">
                <h6 class="fw-semibold mb-3">Informations</h6>
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted">Admin</dt><dd class="col-7">{{ $hotel->owner?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Email</dt><dd class="col-7">{{ $hotel->contact_email ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Téléphone</dt><dd class="col-7">{{ $hotel->contact_phone ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Créé le</dt><dd class="col-7">{{ $hotel->created_at?->format('d/m/Y') }}</dd>
                    <dt class="col-5 text-muted">Fin abonnement</dt>
                    <dd class="col-7 {{ $hotel->isSubscriptionExpired() ? 'text-danger fw-semibold' : '' }}">
                        {{ $hotel->subscription_ends_at?->format('d/m/Y') ?? 'illimité' }}
                    </dd>
                </dl>
            </div></div>

            <div class="card border-0 shadow-sm"><div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="fas fa-rotate me-1"></i>Renouveler l'abonnement</h6>
                <form action="{{ route('platform.hotels.renew', $hotel) }}" method="POST" class="d-flex gap-2 align-items-end">
                    @csrf
                    <div>
                        <label class="form-label small mb-1">Mois</label>
                        <input type="number" name="months" value="1" min="1" max="24" class="form-control form-control-sm" style="width:80px">
                    </div>
                    <button class="btn btn-primary btn-sm flex-grow-1"><i class="fas fa-check me-1"></i>Renouveler</button>
                </form>
                <div class="small text-muted mt-2">Prolonge la période et enregistre le paiement ({{ number_format($hotel->monthlyPrice(), 0, ',', ' ') }} CFA/mois).</div>
            </div></div>
        </div>

        {{-- Historique des abonnements --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h6 class="fw-semibold mb-3">Historique des abonnements</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Période</th><th>Formule</th><th>Type</th><th class="text-end">Montant</th><th>Par</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($hotel->subscriptions as $sub)
                                <tr>
                                    <td class="small">{{ $sub->starts_at?->format('d/m/Y') }} → {{ $sub->ends_at?->format('d/m/Y') ?? '—' }}</td>
                                    <td>{{ ucfirst($sub->plan) }}</td>
                                    <td>
                                        @if ($sub->status === 'trial')
                                            <span class="badge bg-info-subtle text-info">Essai</span>
                                        @elseif ($sub->is_renewal)
                                            <span class="badge bg-primary-subtle text-primary">Renouvellement</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Souscription</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($sub->amount, 0, ',', ' ') }} {{ $sub->currency }}</td>
                                    <td class="small text-muted">{{ $sub->createdBy?->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Aucun abonnement enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div>
@endsection
