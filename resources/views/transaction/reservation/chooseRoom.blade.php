@extends('template.master')
@section('title', 'Choix du type de chambre')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #28a745;
            --accent-color: #ffd700;
            --light-color: #f8f9fa;
            --dark-color: #333;
            --gray-color: #666;
            --light-gray: #e0e0e0;
            --border-radius: 12px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        .room-type-card {
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 1.5rem;
            background: white;
            height: 100%;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .room-type-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow);
            border-color: var(--primary-color);
        }
        
        .room-type-card.selected {
            border: 2px solid var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.15);
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
        }
        
        .selection-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--secondary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .room-type-card.selected .selection-badge {
            opacity: 1;
        }
        
        .room-type-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .room-type-info {
            padding: 1.5rem;
        }
        
        .room-type-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .room-type-description {
            color: var(--gray-color);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            min-height: 50px;
        }
        
        .room-features {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        
        .room-features li {
            padding: 0.4rem 0.5rem;
            color: var(--gray-color);
            font-size: 0.9rem;
            background: #f8f9fa;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        
        .room-features li i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 20px;
            font-size: 0.9rem;
        }
        
        .room-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .room-price-badge {
            background: var(--accent-color);
            color: var(--dark-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .room-availability {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            font-weight: 600;
            gap: 0.5rem;
        }
        
        .room-available {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .room-limited {
            background: #fff8e1;
            color: #ff8f00;
            border: 1px solid #ffecb3;
        }
        
        .room-unavailable {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        .choose-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a5a80 100%);
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1rem;
        }
        
        .choose-btn:hover {
            background: linear-gradient(135deg, #3a5a80 0%, #2d4560 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 111, 165, 0.3);
        }
        
        .choose-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .choose-btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Sections principales */
        .summary-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: var(--box-shadow);
        }
        
        .summary-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .summary-title i {
            color: var(--primary-color);
        }
        
        .summary-details {
            color: var(--gray-color);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .summary-details strong {
            color: var(--dark-color);
            min-width: 80px;
            display: inline-block;
        }
        
        .date-form {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.15);
        }
        
        .search-btn {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #218838 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.9rem 1.5rem;
            font-weight: 600;
            width: 100%;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .search-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        /* Profil client - Sidebar */
        .sidebar-container {
            position: sticky;
            top: 20px;
        }
        
        .profile-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            border: none;
            background: white;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a5a80 100%);
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin: 0 auto 1rem;
            display: block;
            object-fit: cover;
            background: white;
        }
        
        .profile-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .profile-id {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            background: rgba(0, 0, 0, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }
        
        .profile-body {
            padding: 1.5rem;
        }
        
        .profile-info {
            color: var(--gray-color);
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
            width: 32px;
            color: var(--primary-color);
            font-size: 1.1rem;
            flex-shrink: 0;
            text-align: center;
        }
        
        .info-content {
            flex-grow: 1;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            color: var(--gray-color);
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        /* Instructions */
        .instructions-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .instructions-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .instructions-steps {
            counter-reset: step;
            margin: 0;
            padding: 0;
        }
        
        .instructions-steps li {
            counter-increment: step;
            margin-bottom: 0.75rem;
            padding-left: 2rem;
            position: relative;
            color: var(--gray-color);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .instructions-steps li:before {
            content: counter(step);
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .instructions-steps li.current {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .instructions-steps li.current:before {
            background: var(--secondary-color);
        }
        
        /* Bouton Suivant dans la sidebar */
        .next-btn-sidebar {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #218838 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            margin-bottom: 1rem;
        }
        
        .next-btn-sidebar:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        
        .next-btn-sidebar:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }
        
        .next-btn-sidebar:disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .next-btn-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        
        .next-btn-main {
            font-size: 1.2rem;
        }
        
        .next-btn-sub {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        /* Section sélection */
        .selection-info {
            background: linear-gradient(135deg, #e8f4ff 0%, #d4e7ff 100%);
            border: 2px solid #cce5ff;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            display: none;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .selection-info.show {
            display: block;
        }
        
        .selected-type-name {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.15rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin: 0.5rem 0;
        }
        
        .change-selection-btn {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .change-selection-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Autres styles */
        .room-type-card.disabled {
            opacity: 0.6;
            filter: grayscale(0.5);
            cursor: not-allowed;
        }
        
        .room-type-card.disabled:hover {
            transform: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-color: #e0e0e0;
        }
        
        .availability-info {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8f9fa;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }
        
        .important-note {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffd166;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 2rem;
        }
        
        .important-note h5 {
            color: #856404;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
        }
        
        .important-note p {
            color: #856404;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 0;
        }
        
        .no-types {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            border: 2px dashed #dee2e6;
            box-shadow: var(--box-shadow);
        }
        
        .no-types h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffd166;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        
        .main-content {
            padding-bottom: 40px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .room-type-image {
                height: 200px;
            }
            
            .room-features {
                grid-template-columns: 1fr;
            }
            
            .date-form .row > div {
                margin-bottom: 1rem;
            }
            
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
            
            .next-btn-sidebar {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                border-radius: 0;
                margin: 0;
                padding: 1.25rem;
                z-index: 1000;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            }
            
            .main-content {
                padding-bottom: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .room-type-image {
                height: 180px;
            }
            
            .room-type-info {
                padding: 1.25rem;
            }
            
            .room-type-name {
                font-size: 1.3rem;
            }
            
            .summary-title {
                font-size: 1.15rem;
            }
            
            .profile-body {
                padding: 1.25rem;
            }
        }
    </style>
@endsection

@section('content')
    @include('transaction.reservation.progressbar')
    
    <div class="container py-4 main-content">
        <div class="row">
            <!-- Section principale -->
            <div class="col-lg-8 mb-4">
                <!-- Résumé client -->
                <div class="summary-box">
                    <div class="summary-title">
                        <i class="fas fa-user-circle"></i>
                        Nouvelle réservation - Client
                    </div>
                    <div class="summary-details">
                        <div><strong>Nom :</strong> {{ $customer->name }}</div>
                        <div><strong>Email :</strong> {{ $customer->email }}</div>
                        <div><strong>Téléphone :</strong> {{ $customer->phone }}</div>
                    </div>
                </div>
                
                <!-- IMPORTANT : Note sur le système -->
                <div class="important-note">
                    <h5><i class="fas fa-info-circle"></i>Nouveau système de réservation</h5>
                    <p>
                        Cliquez sur un type de chambre pour le sélectionner, puis cliquez sur "Suivant" dans la barre latérale pour continuer.
                    </p>
                </div>
                
                <!-- Formulaire dates si non définies -->
                @if(!$check_in || !$check_out)
                <div class="date-form">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Sélectionner les dates de séjour</h5>
                    <form method="GET" action="{{ route('transaction.reservation.choose-type', ['customer' => $customer->id]) }}">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-label">Date d'arrivée</div>
                                <input type="date" 
                                       name="check_in" 
                                       class="form-control" 
                                       value="{{ old('check_in', $check_in ?? date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <div class="form-label">Date de départ</div>
                                <input type="date" 
                                       name="check_out" 
                                       class="form-control" 
                                       value="{{ old('check_out', $check_out ?? date('Y-m-d', strtotime('+1 day'))) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-calendar-check me-2"></i>Vérifier disponibilité
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
                
                <!-- Résumé dates si définies -->
                @if($check_in && $check_out)
                <div class="summary-box">
                    <div class="summary-title">
                        <i class="fas fa-calendar-check"></i>
                        Disponibilité pour la période
                    </div>
                    <div class="summary-details">
                        <div><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($check_in)->format('d/m/Y') }}</div>
                        <div><strong>Départ :</strong> {{ \Carbon\Carbon::parse($check_out)->format('d/m/Y') }}</div>
                        <div><strong>Durée :</strong> {{ \Carbon\Carbon::parse($check_in)->diffInDays(\Carbon\Carbon::parse($check_out)) }} nuit(s)</div>
                    </div>
                </div>
                @endif
                
                <!-- Information sur la sélection -->
                <div class="selection-info" id="selectionInfo">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><i class="fas fa-check-circle text-success me-2"></i>Type sélectionné :</strong>
                            <div class="selected-type-name" id="selectedTypeName"></div>
                        </div>
                        <button type="button" class="change-selection-btn" onclick="clearSelection()">
                            <i class="fas fa-sync-alt"></i> Changer
                        </button>
                    </div>
                </div>
                
                <!-- Liste des types de chambre -->
                @if($roomTypes->isNotEmpty() && $check_in && $check_out)
                    <div class="row" id="roomTypesContainer">
                        @foreach($roomTypes as $type)
                        @php
                            $availability = $type->available_count ?? 0;
                            $isAvailable = $availability > 0;
                            $availabilityClass = 'room-available';
                            $availabilityText = 'Disponible';
                            
                            if ($availability == 0) {
                                $availabilityClass = 'room-unavailable';
                                $availabilityText = 'Complet';
                            } elseif ($availability < 3) {
                                $availabilityClass = 'room-limited';
                                $availabilityText = 'Limité';
                            }
                        @endphp
                        
                        <div class="col-lg-6 mb-4" data-room-type-id="{{ $type->id }}">
                            <div class="room-type-card {{ !$isAvailable ? 'disabled' : '' }}" 
                                 id="roomTypeCard{{ $type->id }}"
                                 onclick="{{ $isAvailable ? "selectRoomType({$type->id}, '{$type->name}')" : '' }}">
                                <div class="selection-badge">
                                    <i class="fas fa-check"></i>
                                </div>
                                
                                <!-- Image du type de chambre -->
                                @if($type->image && Storage::exists('public/' . $type->image))
                                <img src="{{ asset('storage/' . $type->image) }}" 
                                    alt="{{ $type->name }}" 
                                    class="room-type-image">
                                @else
                                <div class="room-type-image d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-bed fa-4x text-muted"></i>
                                </div>
                                @endif
                                
                                <div class="room-type-info">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="room-type-name">{{ $type->name }}</div>
                                        <span class="room-availability {{ $availabilityClass }}">
                                            <i class="fas fa-{{ $availability > 0 ? 'check' : 'times' }}"></i>
                                            {{ $availabilityText }}
                                        </span>
                                    </div>
                                    
                                    <div class="room-type-description">
                                        {{ $type->description ?: 'Chambre confortable avec équipements standards.' }}
                                    </div>
                                    
                                    <ul class="room-features">
                                        <li>
                                            <i class="fas fa-users"></i>
                                            <strong>Capacité :</strong> {{ $type->capacity ?? 2 }} pers.
                                        </li>
                                        <li>
                                            <i class="fas fa-ruler-combined"></i>
                                            <strong>Superficie :</strong> {{ $type->size ?? 'N/A' }} m²
                                        </li>
                                        <li>
                                            <i class="fas fa-bed"></i>
                                            <strong>Lits :</strong> {{ $type->bed ?? 'Double' }}
                                        </li>
                                        @if($type->amenities)
                                        <li>
                                            <i class="fas fa-check-circle"></i>
                                            <strong>Équipements :</strong> {{ Str::limit($type->amenities, 30) }}
                                        </li>
                                        @endif
                                    </ul>
                                    
                                    <div class="room-price">
                                        {{ number_format($type->base_price ?? 0, 0, ',', ' ') }} FCFA
                                        <span class="room-price-badge">/ nuit</span>
                                    </div>
                                    
                                    <div class="availability-info">
                                        <i class="fas fa-door-open"></i>
                                        {{ $availability }} chambre(s) disponible(s) pour ces dates
                                    </div>
                                    
                                    @if($isAvailable)
                                    <button type="button" class="choose-btn mt-3" onclick="selectRoomType({{ $type->id }}, '{{ $type->name }}')">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Sélectionner ce type
                                    </button>
                                    @else
                                    <button class="choose-btn mt-3" disabled>
                                        <i class="fas fa-times me-2"></i>
                                        Non disponible
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @elseif($roomTypes->isNotEmpty() && (!$check_in || !$check_out))
                    <div class="alert-warning">
                        <i class="fas fa-calendar-alt"></i>
                        Veuillez d'abord sélectionner des dates pour voir la disponibilité.
                    </div>
                @elseif($check_in && $check_out)
                    <div class="no-types">
                        <h3>
                            <i class="fas fa-bed"></i>
                            Aucun type de chambre disponible
                        </h3>
                        <p class="text-muted mb-3">
                            Aucun type de chambre n'est disponible pour la période sélectionnée.
                        </p>
                        <a href="{{ route('transaction.reservation.choose-type', ['customer' => $customer->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Changer les dates
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar avec profil client et bouton Suivant -->
            <div class="col-lg-4">
                <div class="sidebar-container">
                    <!-- Profil client -->
                    <div class="profile-card">
                        <div class="profile-header">
                            <img src="{{ $customer->user->avatar ?? asset('images/default-avatar.png') }}" 
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
                                        <div class="info-value">{{ Str::limit($customer->address ?? '', 30) ?: 'Non spécifiée' }}</div>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Historique</div>
                                        <div class="info-value">
                                            {{ $customer->transactions()->count() }} réservation(s)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="instructions-box">
                        <div class="instructions-title">
                            <i class="fas fa-lightbulb"></i>
                            Étapes de réservation
                        </div>
                        <ol class="instructions-steps">
                            <li class="current">Sélectionner un type de chambre</li>
                            <li>Saisir le nombre de personnes</li>
                            <li>Choisir les chambres spécifiques</li>
                            <li>Confirmer la réservation</li>
                        </ol>
                    </div>
                    
                    <!-- Bouton Suivant dans la sidebar -->
                    @if($check_in && $check_out && $roomTypes->isNotEmpty())
                    <button type="button" class="next-btn-sidebar" id="nextBtnSidebar" onclick="goToNextStep()" disabled>
                        <i class="fas fa-arrow-right fa-lg"></i>
                        <div class="next-btn-text">
                            <span class="next-btn-main">Suivant</span>
                            <span class="next-btn-sub">Saisir le nombre de personnes</span>
                        </div>
                    </button>
                    <div class="text-center text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Étape 1 sur 4 - Sélectionnez un type de chambre
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Variables globales
    let selectedRoomTypeId = null;
    let selectedRoomTypeName = null;
    
    // Sélectionner un type de chambre
    function selectRoomType(roomTypeId, roomTypeName) {
        // Désélectionner tout d'abord
        clearSelection();
        
        // Stocker la sélection
        selectedRoomTypeId = roomTypeId;
        selectedRoomTypeName = roomTypeName;
        
        // Mettre en surbrillance la carte sélectionnée
        const card = document.getElementById(`roomTypeCard${roomTypeId}`);
        if (card) {
            card.classList.add('selected');
        }
        
        // Mettre à jour l'affichage de sélection
        const selectedTypeNameEl = document.getElementById('selectedTypeName');
        const selectionInfo = document.getElementById('selectionInfo');
        const nextBtnSidebar = document.getElementById('nextBtnSidebar');
        
        if (selectedTypeNameEl) selectedTypeNameEl.textContent = roomTypeName;
        if (selectionInfo) selectionInfo.classList.add('show');
        if (nextBtnSidebar) nextBtnSidebar.disabled = false;
        
        // Faire défiler jusqu'à l'info de sélection
        if (selectionInfo) {
            selectionInfo.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
    
    // Effacer la sélection
    function clearSelection() {
        // Supprimer la classe selected de toutes les cartes
        const selectedCards = document.querySelectorAll('.room-type-card.selected');
        selectedCards.forEach(card => {
            card.classList.remove('selected');
        });
        
        // Réinitialiser les variables
        selectedRoomTypeId = null;
        selectedRoomTypeName = null;
        
        // Mettre à jour l'affichage
        const selectionInfo = document.getElementById('selectionInfo');
        const nextBtnSidebar = document.getElementById('nextBtnSidebar');
        
        if (selectionInfo) selectionInfo.classList.remove('show');
        if (nextBtnSidebar) nextBtnSidebar.disabled = true;
    }
    
    // Aller à l'étape suivante
    function goToNextStep() {
        if (!selectedRoomTypeId || !selectedRoomTypeName) {
            alert('Veuillez d\'abord sélectionner un type de chambre.');
            return;
        }
        
        // Vérifier que des dates sont sélectionnées
        const checkIn = '{{ $check_in }}';
        const checkOut = '{{ $check_out }}';
        
        if (!checkIn || !checkOut || checkIn === 'null' || checkOut === 'null') {
            alert('Veuillez d\'abord sélectionner des dates de séjour.');
            return;
        }
        
        // Rediriger vers la page suivante
        const url = `{{ route('transaction.reservation.viewCountPerson', ['customer' => $customer->id]) }}?room_type_id=${selectedRoomTypeId}&check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}`;
        window.location.href = url;
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter des écouteurs d'événements pour la sélection par clic sur la carte
        const roomTypeCards = document.querySelectorAll('.room-type-card:not(.disabled)');
        roomTypeCards.forEach(card => {
            // Ne pas ajouter d'écouteur si le bouton a déjà un onclick
            const chooseBtn = card.querySelector('.choose-btn');
            if (!chooseBtn.getAttribute('onclick')) {
                card.addEventListener('click', function(e) {
                    // Ne pas déclencher si on clique sur le bouton (il a son propre gestionnaire)
                    if (e.target.closest('.choose-btn')) return;
                    
                    const typeId = this.closest('[data-room-type-id]').getAttribute('data-room-type-id');
                    const typeName = this.querySelector('.room-type-name').textContent;
                    selectRoomType(typeId, typeName);
                });
            }
        });
        
        // Validation des dates
        const checkInInput = document.querySelector('input[name="check_in"]');
        const checkOutInput = document.querySelector('input[name="check_out"]');
        
        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                // Si la date de départ est antérieure à la nouvelle date minimale, la réinitialiser
                if (new Date(checkOutInput.value) < nextDay) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            });
            
            checkOutInput.addEventListener('change', function() {
                const checkInDate = new Date(checkInInput.value);
                const checkOutDate = new Date(this.value);
                
                if (checkOutDate <= checkInDate) {
                    alert('La date de départ doit être après la date d\'arrivée');
                    this.value = '';
                }
            });
        }
        
        // Animation d'apparition des cartes
        const roomCards = document.querySelectorAll('.room-type-card');
        roomCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
    </script>
@endsection