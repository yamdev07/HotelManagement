{{-- Section contact : coordonnées de l'établissement --}}
<section class="section" id="contact">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nous contacter</h2>
            <p class="text-secondary">Réservez ou posez-nous vos questions.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @if ($hotel->contact_phone)
                <div class="col-md-4">
                    <div class="text-center p-4 border rounded-3 h-100">
                        <div class="text-hotel fs-2 mb-2"><i class="fas fa-phone"></i></div>
                        <h6 class="fw-bold">Téléphone</h6>
                        <a href="tel:{{ $hotel->contact_phone }}" class="text-decoration-none text-secondary">{{ $hotel->contact_phone }}</a>
                    </div>
                </div>
            @endif
            @if ($hotel->contact_email)
                <div class="col-md-4">
                    <div class="text-center p-4 border rounded-3 h-100">
                        <div class="text-hotel fs-2 mb-2"><i class="fas fa-envelope"></i></div>
                        <h6 class="fw-bold">Email</h6>
                        <a href="mailto:{{ $hotel->contact_email }}" class="text-decoration-none text-secondary">{{ $hotel->contact_email }}</a>
                    </div>
                </div>
            @endif
            @if ($hotel->address)
                <div class="col-md-4">
                    <div class="text-center p-4 border rounded-3 h-100">
                        <div class="text-hotel fs-2 mb-2"><i class="fas fa-location-dot"></i></div>
                        <h6 class="fw-bold">Adresse</h6>
                        <p class="text-secondary mb-0">{{ $hotel->address }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if (! $hotel->contact_phone && ! $hotel->contact_email && ! $hotel->address)
            <p class="text-center text-secondary">Coordonnées à venir.</p>
        @endif
    </div>
</section>
