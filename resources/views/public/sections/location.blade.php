@if (! empty($hotel->address))
<section class="section" id="localisation">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Nous trouver</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Localisation</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5" data-aos="fade-right">
                <div style="background:var(--card);border:1px solid var(--line);border-radius:8px;padding:2rem;height:100%;">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <i class="fas fa-location-dot text-c" style="font-size:1.4rem;margin-top:4px;"></i>
                        <div>
                            <div class="eyebrow mb-1" style="color:var(--ink);opacity:.6;">Adresse</div>
                            <div style="font-size:1.05rem;">{{ $hotel->address }}</div>
                        </div>
                    </div>
                    @if($hotel->contact_phone)
                        <div class="d-flex align-items-center gap-3 mb-3"><i class="fas fa-phone text-c" style="width:20px;"></i><span>{{ $hotel->contact_phone }}</span></div>
                    @endif
                    @if($hotel->contact_email)
                        <div class="d-flex align-items-center gap-3 mb-4"><i class="fas fa-envelope text-c" style="width:20px;"></i><span>{{ $hotel->contact_email }}</span></div>
                    @endif
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($hotel->address) }}" target="_blank" rel="noopener" class="btn-c">
                        <i class="fas fa-diamond-turn-right me-2"></i>Itinéraire
                    </a>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="120">
                <div style="border-radius:8px;overflow:hidden;box-shadow:0 20px 50px -30px rgba(0,0,0,.4);height:100%;min-height:340px;">
                    <iframe title="Carte de localisation" width="100%" height="100%" style="border:0;min-height:340px;display:block;"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q={{ urlencode($hotel->address) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
