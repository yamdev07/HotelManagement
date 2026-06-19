{{-- Section services : prestations de l'établissement --}}
@php
    $services = [
        ['fa-wifi', 'Wi-Fi gratuit', 'Connexion haut débit dans tout l\'établissement.'],
        ['fa-bell-concierge', 'Réception 24/7', 'Une équipe à votre service à toute heure.'],
        ['fa-mug-saucer', 'Petit-déjeuner', 'Petit-déjeuner servi chaque matin.'],
        ['fa-car', 'Parking', 'Stationnement sécurisé pour nos clients.'],
        ['fa-broom', 'Ménage quotidien', 'Chambres entretenues chaque jour.'],
        ['fa-location-dot', 'Emplacement idéal', 'À proximité des points d\'intérêt.'],
    ];
@endphp
<section class="section bg-light" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nos services</h2>
            <p class="text-secondary">Tout pour rendre votre séjour agréable.</p>
        </div>
        <div class="row g-4">
            @foreach ($services as [$icon, $title, $desc])
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-start gap-3 p-3">
                        <div class="text-hotel fs-3"><i class="fas {{ $icon }}"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $title }}</h6>
                            <p class="text-secondary small mb-0">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
