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
                <p class="text-muted mb-4">
                    @auth
                        L'accès de <strong>{{ auth()->user()->hotel?->name ?? 'votre établissement' }}</strong>
                        est actuellement suspendu.
                    @endauth
                    @php $hotel = auth()->user()?->hotel; @endphp
                    @if ($hotel && $hotel->isSubscriptionExpired())
                        Votre abonnement a expiré le
                        <strong>{{ $hotel->subscription_ends_at->format('d/m/Y') }}</strong>.
                    @endif
                    <br>
                    Merci de régulariser votre abonnement pour réactiver votre espace.
                </p>
                <p class="small text-muted">
                    Contactez l'administrateur de la plateforme pour toute question.
                </p>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
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
