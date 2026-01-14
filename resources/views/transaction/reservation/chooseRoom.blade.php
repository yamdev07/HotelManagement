@extends('template.master')
@section('title', 'Choix de la chambre')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
    <style>
        .room-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            background: white;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #4a6fa5;
        }
        
        .room-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .room-info {
            padding: 1.5rem;
        }
        
        .room-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .room-type {
            color: #4a6fa5;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        
        .room-capacity {
            display: inline-block;
            background: #f0f7ff;
            color: #4a6fa5;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .room-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .room-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .choose-btn {
            background: #4a6fa5;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .choose-btn:hover {
            background: #3a5a80;
            transform: translateY(-2px);
        }
        
        .summary-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #4a6fa5;
        }
        
        .summary-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .summary-details {
            color: #666;
            font-size: 0.95rem;
        }
        
        .filters-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e0e0e0;
        }
        
        .filter-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .filter-select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            width: 100%;
        }
        
        .search-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1.8rem;
        }
        
        .search-btn:hover {
            background: #218838;
        }
        
        .no-rooms {
            text-align: center;
            padding: 3rem 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #ddd;
        }
        
        .no-rooms h3 {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .profile-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            background: white;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #4a6fa5 0%, #3a5a80 100%);
            padding: 2rem 1.5rem 1rem;
            text-align: center;
            position: relative;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin: 0 auto 1rem;
            display: block;
            object-fit: cover;
        }
        
        .profile-name {
            font-size: 1.25rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .profile-id {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        .profile-body {
            padding: 1.5rem;
        }
        
        .profile-info {
            color: #666;
        }
        
        .info-row {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-icon {
            width: 30px;
            color: #4a6fa5;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .info-content {
            flex-grow: 1;
        }
        
        .info-label {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        
        .info-value {
            color: #666;
            font-size: 0.9rem;
        }
        
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .cfa-badge {
            background: #ffd700;
            color: #333;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .room-image {
                height: 200px;
            }
            
            .filters-container .row > div {
                margin-bottom: 1rem;
            }
            
            .search-btn {
                margin-top: 0;
            }
        }
    </style>
@endsection

@section('content')
    @include('transaction.reservation.progressbar')
    
    <div class="container py-4">
        <div class="row">
            <!-- Section principale -->
            <div class="col-lg-8">
                <!-- Résumé -->
                <div class="summary-box">
                    <div class="summary-title">
                        {{ $roomsCount }} chambre(s) disponible(s)
                    </div>
                    <div class="summary-details">
                        <strong>{{ request()->input('count_person') }} personne(s)</strong> · 
                        Du {{ Helper::dateFormat(request()->input('check_in')) }} 
                        au {{ Helper::dateFormat(request()->input('check_out')) }}
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="filters-container">
                    <form method="GET" action="{{ route('transaction.reservation.chooseRoom', ['customer' => $customer->id]) }}">
                        <input type="hidden" name="count_person" value="{{ request()->input('count_person') }}">
                        <input type="hidden" name="check_in" value="{{ request()->input('check_in') }}">
                        <input type="hidden" name="check_out" value="{{ request()->input('check_out') }}">
                        
                        <div class="row align-items-end">
                            <div class="col-md-5">
                                <div class="filter-label">Trier par</div>
                                <select class="filter-select" name="sort_name">
                                    <option value="Price" @if(request()->input('sort_name') == 'Price') selected @endif>Prix</option>
                                    <option value="Number" @if(request()->input('sort_name') == 'Number') selected @endif>Numéro</option>
                                    <option value="Capacity" @if(request()->input('sort_name') == 'Capacity') selected @endif>Capacité</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="filter-label">Ordre</div>
                                <select class="filter-select" name="sort_type">
                                    <option value="ASC" @if(request()->input('sort_type') == 'ASC') selected @endif>Croissant</option>
                                    <option value="DESC" @if(request()->input('sort_type') == 'DESC') selected @endif>Décroissant</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Liste des chambres -->
                @forelse ($rooms as $room)
                    <div class="room-card">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $room->firstImage() }}" 
                                     alt="Chambre {{ $room->number }}" 
                                     class="room-image">
                            </div>
                            <div class="col-md-8">
                                <div class="room-info">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="room-number">Chambre {{ $room->number }}</div>
                                            <div class="room-type">{{ $room->type->name }}</div>
                                        </div>
                                        <span class="room-capacity">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $room->capacity }} personne(s)
                                        </span>
                                    </div>
                                    
                                    <div class="room-price">
                                        {{ Helper::formatCFA($room->price) }} / jour
                                        <span class="cfa-badge">FCFA</span>
                                    </div>
                                    
                                    <div class="room-description">
                                        {{ $room->view }}
                                    </div>
                                    
                                    <a href="{{ route('transaction.reservation.confirmation', [
                                        'customer' => $customer->id, 
                                        'room' => $room->id, 
                                        'from' => request()->input('check_in'), 
                                        'to' => request()->input('check_out')
                                    ]) }}" 
                                       class="choose-btn">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Choisir cette chambre
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-rooms">
                        <h3>
                            <i class="fas fa-bed me-2"></i>
                            Aucune chambre disponible
                        </h3>
                        <p class="text-muted">
                            Aucune chambre ne correspond à votre recherche pour 
                            {{ request()->input('count_person') }} personne(s)
                        </p>
                    </div>
                @endforelse
                
                <!-- Pagination -->
                @if($rooms->hasPages())
                    <div class="pagination-container">
                        {{ $rooms->onEachSide(1)->appends([
                            'count_person' => request()->input('count_person'),
                            'check_in' => request()->input('check_in'),
                            'check_out' => request()->input('check_out'),
                            'sort_name' => request()->input('sort_name'),
                            'sort_type' => request()->input('sort_type'),
                        ])->links('template.paginationlinks') }}
                    </div>
                @endif
            </div>
            
            <!-- Profil client -->
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="{{ $customer->user->getAvatar() }}" 
                             alt="{{ $customer->name }}" 
                             class="profile-avatar">
                        <div class="profile-name">{{ $customer->name }}</div>
                        <div class="profile-id">
                            <i class="fas fa-id-card me-1"></i>ID: {{ $customer->id }}
                        </div>
                    </div>
                    
                    <div class="profile-body">
                        <div class="profile-info">
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas {{ $customer->gender == 'Male' ? 'fa-mars' : 'fa-venus' }}"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Genre</div>
                                    <div class="info-value">{{ $customer->gender == 'Male' ? 'Homme' : 'Femme' }}</div>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Profession</div>
                                    <div class="info-value">{{ $customer->job ?? 'Non spécifié' }}</div>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Naissance</div>
                                    <div class="info-value">{{ $customer->birthdate ? date('d/m/Y', strtotime($customer->birthdate)) : 'Non spécifiée' }}</div>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Téléphone</div>
                                    <div class="info-value">{{ $customer->phone ?? 'Non spécifié' }}</div>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Adresse</div>
                                    <div class="info-value">{{ Str::limit($customer->address, 30) ?? 'Non spécifiée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection