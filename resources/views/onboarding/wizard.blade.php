{{-- Wizard d'onboarding (version enrichie au commit suivant) --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bienvenue — configuration</title>
</head>
<body>
    <h1>Configurez votre site</h1>
    <form action="{{ route('onboarding.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input name="name" value="{{ $hotel->name }}">
        <button type="submit">Valider</button>
    </form>
</body>
</html>
