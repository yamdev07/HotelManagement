<section class="section" id="chambres" style="background:#faf9f7;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="eyebrow mb-2">Hébergement</div>
            <h2 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Nos chambres</h2>
            <div class="hero-divider" style="background:var(--c);opacity:.5;"></div>
        </div>

        @if ($rooms->isEmpty())
            <p class="text-center text-secondary">Nos chambres seront bientôt disponibles à la réservation.</p>
        @else
            <div class="row g-4">
                @foreach ($rooms as $room)
                    @php $img = optional($room->images->first())->getRoomImage(); @endphp
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 120 }}">
                        <div class="room-card lift h-100">
                            <div class="room-media">
                                <div class="img" style="background-image: {{ $img ? "url('{$img}')" : 'linear-gradient(135deg, var(--c), var(--d))' }};">
                                    @unless($img)<div class="d-flex h-100 align-items-center justify-content-center text-white opacity-50"><i class="fas fa-bed fa-3x"></i></div>@endunless
                                </div>
                                <span class="room-price">{{ number_format($room->price, 0, ',', ' ') }} {{ $hotel->currency }}<small style="font-weight:400;opacity:.85;"> /nuit</small></span>
                            </div>
                            <div class="p-4">
                                @if ($room->type)<div class="eyebrow mb-1">{{ $room->type->name }}</div>@endif
                                <h4 class="serif mb-2" style="font-size:1.5rem;">{{ $room->name ?: 'Chambre '.$room->number }}</h4>
                                <p class="text-secondary small mb-3">
                                    <i class="fas fa-user-group me-1 text-c"></i> {{ $room->capacity }} personnes
                                    @if ($room->number) &nbsp;·&nbsp; <i class="fas fa-door-closed me-1 text-c"></i> Chambre {{ $room->number }}@endif
                                </p>
                                @if ($hotel->show_contact)
                                    <a href="#contact" class="text-c fw-semibold" style="letter-spacing:.03em;">Réserver <i class="fas fa-arrow-right-long ms-1"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
