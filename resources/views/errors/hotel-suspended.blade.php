<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compte suspendu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow-sm border-0" style="max-width: 540px;">
            <div class="card-body text-center p-5">
                <div class="mb-3">
                    <i class="fas fa-lock fa-3x text-danger"></i>
                </div>
                <h3 class="mb-3">Accès suspendu</h3>
                @php $hotel = auth()->user()?->hotel; @endphp
                <p class="text-muted mb-3">
                    @auth
                        L'accès de <strong>{{ $hotel?->name ?? 'votre établissement' }}</strong>
                        est actuellement suspendu.
                    @endauth
                    @if ($hotel && $hotel->isSubscriptionExpired())
                        <br>Votre abonnement a expiré le
                        <strong>{{ $hotel->subscription_ends_at->format('d/m/Y') }}</strong>.
                    @endif
                </p>

                @if ($hotel && $hotel->suspension_reason)
                    <div class="alert alert-warning text-start small">
                        <i class="fas fa-circle-info me-1"></i> <strong>Motif :</strong> {{ $hotel->suspension_reason }}
                    </div>
                @endif

                <p class="text-muted mb-4">Merci de régulariser votre situation pour réactiver votre espace.</p>

                @if ($hotel && $hotel->is_active && $hotel->isSubscriptionExpired())
                    {{-- Abonnement expiré : l'hôtelier peut payer en ligne pour réactiver --}}
                    <a href="{{ route('billing.show') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-credit-card me-1"></i> Renouveler mon abonnement
                    </a>
                @else
                    <p class="small text-muted">
                        Contactez l'administrateur de la plateforme pour toute question.
                    </p>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt me-1"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
