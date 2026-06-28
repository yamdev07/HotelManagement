@php
    $roomCount = isset($rooms) ? $rooms->count() : 0;
@endphp
<section class="section" id="apropos">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="eyebrow mb-3">Bienvenue</div>
                <h2 class="display-serif mb-4" style="font-size:clamp(2rem,4vw,3.2rem);">
                    Une expérience<br>d'exception
                </h2>
                <p class="text-secondary" style="font-size:1.08rem;line-height:1.9;">
                    {{ $hotel->description ?: 'Niché dans un cadre raffiné, '.$hotel->name.' vous accueille pour un séjour inoubliable. Chaque détail est pensé pour votre confort : des chambres élégantes, un service attentionné et une atmosphère unique.' }}
                </p>
                @if ($hotel->show_rooms)
                    <a href="#chambres" class="btn-c mt-3">Voir nos chambres</a>
                @endif
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
                <div class="row g-4 text-center">
                    <div class="col-6">
                        <div class="p-4 lift" style="background:#faf9f7;border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">{{ $roomCount ?: '∞' }}</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">Chambres</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:#faf9f7;border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">24/7</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">Réception</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:#faf9f7;border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">5★</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">Service</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-4 lift" style="background:#faf9f7;border-radius:6px;">
                            <div class="display-serif text-c" style="font-size:2.8rem;">100%</div>
                            <div class="eyebrow mt-1" style="color:var(--ink);opacity:.6;">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
