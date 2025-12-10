@extends('frontend.layouts.master')

@section('title', 'Nos Chambres - Hôtel Luxury Palace')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="mb-3">Nos Chambres & Suites</h1>
                <p class="lead text-muted">Découvrez toutes nos chambres disponibles</p>
            </div>

            <div class="row g-4">
                @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6">
                    <div class="card room-card h-100 border-0 shadow-sm">
                        <img src="{{ $room->firstImage() }}" 
                             class="card-img-top" 
                             alt="{{ $room->type->name ?? 'Chambre' }}"
                             style="height: 250px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $room->type->name ?? 'Chambre Standard' }}</h5>
                            
                            <div class="mb-2">
                                @if($room->roomStatus)
                                    <span class="badge bg-{{ $room->roomStatus->code == 'AVL' ? 'success' : ($room->roomStatus->code == 'OCC' ? 'danger' : 'warning') }}">
                                        {{ $room->roomStatus->name }}
                                    </span>
                                @endif
                                <span class="badge bg-info ms-1">
                                    {{ $room->capacity }} personne{{ $room->capacity > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            @if($room->description)
                            <p class="card-text text-muted mb-3">
                                {{ Str::limit($room->description, 100) }}
                            </p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 text-primary-custom mb-0">
                                    {{ number_format($room->price, 0) }} € / nuit
                                </span>
                                <a href="{{ route('frontend.room.details', $room->id) }}" class="btn btn-primary-custom">
                                    <i class="fas fa-eye me-1"></i> Détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                        <h4>Aucune chambre disponible pour le moment</h4>
                        <p class="text-muted">Nos chambres sont en cours de préparation.</p>
                        <a href="{{ route('frontend.home') }}" class="btn btn-primary-custom">
                            <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            @if($rooms->hasPages())
            <div class="mt-5">
                {{ $rooms->links() }}
            </div>
            @endif
        </div>
    </section>
@endsection