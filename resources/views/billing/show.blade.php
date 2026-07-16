@extends('template.master')

@section('title', 'Mon abonnement')

@section('content')
@php
    $expired = $hotel->isSubscriptionExpired();
    $daysLeft = $hotel->subscription_ends_at ? now()->startOfDay()->diffInDays($hotel->subscription_ends_at->startOfDay(), false) : null;
    $currency = $hotel->displayCurrency();
    $fmt = fn ($n) => number_format($n, 0, ',', ' ');
@endphp
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i> Mon abonnement</h3>
        <span class="badge bg-{{ $expired ? 'danger' : ($hotel->is_active ? 'success' : 'secondary') }} fs-6">
            {{ ! $hotel->is_active ? 'Suspendu' : ($expired ? 'Expiré' : 'Actif') }}
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-triangle-exclamation me-1"></i>{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- État courant --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small text-uppercase">Formule actuelle</div>
                <div class="h4 mb-0 mt-1">{{ $hotel->planName() }}</div>
                <div class="text-muted small">{{ $fmt($hotel->monthlyPrice()) }} {{ $currency }} / mois · {{ $hotel->countryName() }}</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small text-uppercase">Échéance</div>
                <div class="h4 mb-0 mt-1">{{ $hotel->subscription_ends_at?->format('d/m/Y') ?? '·' }}</div>
                <div class="small {{ $expired ? 'text-danger' : 'text-muted' }}">
                    @if ($expired) Expiré @elseif ($daysLeft !== null) Encore {{ $daysLeft }} jour(s) @endif
                </div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small text-uppercase">Total réglé</div>
                <div class="h4 mb-0 mt-1">{{ $fmt($hotel->totalPaid()) }} {{ $currency }}</div>
                <div class="text-muted small">{{ $hotel->renewalsCount() }} renouvellement(s)</div>
            </div></div>
        </div>
    </div>

    @if ($suspendedByAdmin)
        <div class="alert alert-warning">
            <i class="fas fa-lock me-1"></i>
            Votre compte a été suspendu par la plateforme.
            @if ($hotel->suspension_reason)
                <strong>Motif : {{ $hotel->suspension_reason }}.</strong>
            @endif
            Le paiement en ligne est indisponible. Merci de nous contacter pour régulariser votre situation.
        </div>
    @elseif (! $configured)
        <div class="alert alert-info">
            <i class="fas fa-circle-info me-1"></i>
            Le paiement en ligne n'est pas encore activé sur cette plateforme. Contactez-nous pour renouveler votre abonnement.
        </div>
    @else
        {{-- Paiement --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-arrows-rotate me-2"></i>Renouveler ou changer de formule</h5>
                <p class="text-muted">Prix adaptés à votre pays ({{ $hotel->countryName() }}). Paiement sécurisé via FedaPay (Mobile Money & carte).</p>

                <form action="{{ route('billing.checkout') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        @foreach ($tiers as $key => $tier)
                            @php $price = \App\Models\Hotel::priceFor($key, $hotel->country); @endphp
                            <div class="col-md-4">
                                <label class="card h-100 border {{ $hotel->plan === $key ? 'border-primary' : '' }} plan-pick" style="cursor:pointer">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="radio" name="plan" value="{{ $key }}" {{ $hotel->plan === $key ? 'checked' : '' }} required>
                                                <span class="fw-bold">{{ $tier['name'] }}</span>
                                            </div>
                                            @if (! empty($tier['popular']))<span class="badge bg-primary">Populaire</span>@endif
                                        </div>
                                        <div class="h4 mt-2 mb-0">{{ $fmt($price) }} <small class="text-muted fs-6">{{ $currency }}/mois</small></div>
                                        <div class="small text-muted">{{ $tier['tagline'] }}</div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-3 mt-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Durée</label>
                            <select name="months" class="form-select">
                                @foreach ($months as $m)
                                    <option value="{{ $m }}">{{ $m }} mois</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-lock me-2"></i>Payer et activer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Historique --}}
    @if ($hotel->subscriptions->count())
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-clock-rotate-left me-2"></i>Historique</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead><tr><th>Date</th><th>Formule</th><th>Type</th><th class="text-end">Montant</th><th>Jusqu'au</th></tr></thead>
                        <tbody>
                        @foreach ($hotel->subscriptions as $s)
                            <tr>
                                <td>{{ $s->starts_at?->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($s->plan) }}</td>
                                <td>
                                    @if ($s->status === 'trial')<span class="badge bg-info">Essai</span>
                                    @elseif ($s->is_renewal)<span class="badge bg-success">Renouvellement</span>
                                    @else<span class="badge bg-secondary">Souscription</span>@endif
                                </td>
                                <td class="text-end">{{ $fmt($s->amount) }} {{ $s->currency }}</td>
                                <td>{{ $s->ends_at?->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
