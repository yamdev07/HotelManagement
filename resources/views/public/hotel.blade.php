<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $hotel->name }}@if($hotel->tagline) — {{ $hotel->tagline }}@endif</title>
    <meta name="description" content="{{ $hotel->tagline ?? $hotel->name }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --hotel-primary: {{ $hotel->primaryColor() }};
            --hotel-secondary: {{ $hotel->secondaryColor() }};
        }
        * { font-family: 'Inter', system-ui, sans-serif; }
        .text-hotel { color: var(--hotel-primary) !important; }
        .bg-hotel { background: var(--hotel-primary) !important; }
        .btn-hotel { background: var(--hotel-primary); border-color: var(--hotel-primary); color: #fff; }
        .btn-hotel:hover { filter: brightness(.92); color: #fff; }
        .navbar-brand { font-weight: 800; }
        .section { padding: 4.5rem 0; }
        footer a { color: #cbd5e1; text-decoration: none; }
        footer a:hover { color: #fff; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $hotel->publicUrl() }}">
            @if ($hotel->logoUrl())
                <img src="{{ $hotel->logoUrl() }}" alt="{{ $hotel->name }}" style="height:34px;border-radius:6px;">
            @endif
            <span class="text-hotel">{{ $hotel->name }}</span>
        </a>
        <div class="d-flex gap-2">
            @if ($hotel->show_rooms)
                <a href="#chambres" class="btn btn-hotel">Voir les chambres</a>
            @endif
        </div>
    </div>
</nav>

<main>
    {{-- Les sections (hero, chambres, restaurant, services, contact) sont ajoutées ici --}}
    @include('public.sections.hero')

    @if ($hotel->show_rooms)
        @include('public.sections.rooms')
    @endif
</main>

<!-- FOOTER -->
<footer class="text-light pt-5 pb-4" style="background: var(--hotel-secondary);">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <h5 class="fw-bold mb-2">{{ $hotel->name }}</h5>
                <p class="text-white-50 small mb-0">{{ $hotel->tagline }}</p>
            </div>
            <div class="col-lg-4">
                @if ($hotel->address)<p class="text-white-50 small mb-1"><i class="fas fa-location-dot me-2"></i>{{ $hotel->address }}</p>@endif
                @if ($hotel->contact_phone)<p class="text-white-50 small mb-1"><i class="fas fa-phone me-2"></i>{{ $hotel->contact_phone }}</p>@endif
                @if ($hotel->contact_email)<p class="text-white-50 small mb-0"><i class="fas fa-envelope me-2"></i>{{ $hotel->contact_email }}</p>@endif
            </div>
            <div class="col-lg-3 text-lg-end">
                <p class="text-white-50 small mb-0">© {{ date('Y') }} {{ $hotel->name }}</p>
                <p class="text-white-50 small">Propulsé par {{ config('app.name', 'MyHotel') }}</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
