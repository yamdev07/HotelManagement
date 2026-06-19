{{-- Section chambres : chambres disponibles de l'hôtel (données scopées) --}}
<section class="section bg-light" id="chambres">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nos chambres</h2>
            <p class="text-secondary">Découvrez nos {{ $rooms->count() }} chambres disponibles.</p>
        </div>

        @if ($rooms->isEmpty())
            <p class="text-center text-secondary">Aucune chambre disponible pour le moment.</p>
        @else
            <div class="row g-4">
                @foreach ($rooms as $room)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="d-flex align-items-center justify-content-center text-white"
                                 style="height:170px; background: linear-gradient(135deg, var(--hotel-primary), var(--hotel-secondary));">
                                <i class="fas fa-bed fa-3x opacity-75"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="fw-bold mb-0">{{ $room->name ?: 'Chambre '.$room->number }}</h5>
                                    @if ($room->type)
                                        <span class="badge bg-hotel">{{ $room->type->name }}</span>
                                    @endif
                                </div>
                                <p class="text-secondary small mb-3">
                                    <i class="fas fa-user-group me-1"></i> {{ $room->capacity }} pers.
                                    @if ($room->number) · Chambre {{ $room->number }} @endif
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-hotel fs-5">{{ number_format($room->price, 0, ',', ' ') }} {{ $hotel->currency }}</span>
                                    @if ($hotel->show_contact)
                                        <a href="#contact" class="btn btn-sm btn-hotel">Réserver</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
