<section class="section" id="contact" style="background:#faf9f7;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Réservations & informations</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Nous contacter</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            @if ($hotel->contact_phone)
                <div class="col-md-4" data-aos="fade-up">
                    <div class="text-center p-5 lift" style="background:#fff;border-radius:6px;box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                        <div class="svc-ico mb-3"><i class="fas fa-phone"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.3rem;">Téléphone</h4>
                        <a href="tel:{{ $hotel->contact_phone }}" class="text-secondary">{{ $hotel->contact_phone }}</a>
                    </div>
                </div>
            @endif
            @if ($hotel->contact_email)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="120">
                    <div class="text-center p-5 lift" style="background:#fff;border-radius:6px;box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                        <div class="svc-ico mb-3"><i class="fas fa-envelope"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.3rem;">Email</h4>
                        <a href="mailto:{{ $hotel->contact_email }}" class="text-secondary">{{ $hotel->contact_email }}</a>
                    </div>
                </div>
            @endif
            @if ($hotel->address)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="240">
                    <div class="text-center p-5 lift" style="background:#fff;border-radius:6px;box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                        <div class="svc-ico mb-3"><i class="fas fa-location-dot"></i></div>
                        <h4 class="serif mb-2" style="font-size:1.3rem;">Adresse</h4>
                        <p class="text-secondary mb-0">{{ $hotel->address }}</p>
                    </div>
                </div>
            @endif

            @if (! $hotel->contact_phone && ! $hotel->contact_email && ! $hotel->address)
                <div class="col-12 text-center text-secondary">Coordonnées à venir.</div>
            @endif
        </div>
    </div>
</section>
