<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $hotel->name }}</title>
</head>
<body>
    <h1>{{ $hotel->name }}</h1>
    <p>{{ $rooms->count() }} chambres disponibles.</p>
</body>
</html>
