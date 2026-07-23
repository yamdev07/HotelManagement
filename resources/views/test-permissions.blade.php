<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Permissions - Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test Permissions</h1>
        <div class="card">
            <div class="card-header">
                <h5>Utilisateur connecté</h5>
            </div>
            <div class="card-body">
                <p><strong>Nom :</strong> {{ $user->name }}</p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                <p><strong>Rôle :</strong> {{ $user->role }}</p>
                <p><strong>ID :</strong> {{ $user->id }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Routes de test</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($tests as $name => $url)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $name }}
                            <a href="{{ $url }}" class="btn btn-sm btn-outline-primary" target="_blank">Tester</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
