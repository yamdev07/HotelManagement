@extends('template.master')
@section('title', 'Nombre de personnes')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #28a745;
            --accent-color: #ffd700;
            --light-color: #f8f9fa;
            --dark-color: #333;
            --border-radius: 12px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: var(--box-shadow);
        }

        .room-type-card {
            background: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e0e0e0;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.15);
        }

        .next-btn {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #218838 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .next-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .person-counter {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .counter-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .counter-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .counter-value {
            font-size: 1.5rem;
            font-weight: bold;
            min-width: 50px;
            text-align: center;
        }

        .profile-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            border: none;
            background: white;
            box-shadow: var(--box-shadow);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3a5a80 100%);
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
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

        .price-display {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--secondary-color);
            text-align: center;
            margin: 1rem 0;
        }

        .night-count {
            color: var(--dark-color);
            font-size: 1rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .alert-warning {
            background: #fff3cd;
            border: 2px solid #ffd166;
            border-radius: var(--border-radius);
            padding: 1rem;
            color: #856404;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    @include('transaction.reservation.progressbar')
    
    <div class="container py-4">
        <div class="row">
            <!-- Section principale -->
            <div class="col-lg-8 mb-4">
                <!-- Informations résumé -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informations de réservation</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2"></i>Client:</strong> {{ $customer->name }}</p>
                            <p><strong><i class="fas fa-tag me-2"></i>Type de chambre:</strong> {{ $roomType->name }}</p>
                            <p><strong><i class="fas fa-money-bill-wave me-2"></i>Prix/nuit:</strong> {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-md-6">
                            @if($check_in && $check_out)
                            <p><strong><i class="fas fa-calendar-alt me-2"></i>Arrivée:</strong> {{ \Carbon\Carbon::parse($check_in)->format('d/m/Y') }}</p>
                            <p><strong><i class="fas fa-calendar-alt me-2"></i>Départ:</strong> {{ \Carbon\Carbon::parse($check_out)->format('d/m/Y') }}</p>
                            <p><strong><i class="fas fa-moon me-2"></i>Durée:</strong> {{ \Carbon\Carbon::parse($check_in)->diffInDays(\Carbon\Carbon::parse($check_out)) }} nuit(s)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Type de chambre -->
                <div class="room-type-card">
                    <h5 class="mb-3"><i class="fas fa-bed me-2"></i>Type sélectionné</h5>
                    <div class="row align-items-center">
                        @if($roomType->image && Storage::exists('public/' . $roomType->image))
                        <div class="col-md-4 mb-3 mb-md-0">
                            <img src="{{ asset('storage/' . $roomType->image) }}" 
                                 alt="{{ $roomType->name }}" 
                                 class="img-fluid rounded" 
                                 style="height: 150px; object-fit: cover;">
                        </div>
                        @endif
                        <div class="{{ $roomType->image ? 'col-md-8' : 'col-12' }}">
                            <h6 class="mb-2">{{ $roomType->name }}</h6>
                            <p class="mb-2 text-muted">{{ $roomType->description ?: 'Chambre confortable avec équipements standards.' }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                @if($roomType->capacity)
                                <span class="badge bg-info">
                                    <i class="fas fa-users me-1"></i>Capacité: {{ $roomType->capacity }} pers.
                                </span>
                                @endif
                                @if($roomType->size)
                                <span class="badge bg-secondary">
                                    <i class="fas fa-ruler-combined me-1"></i>{{ $roomType->size }} m²
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avertissement sur la capacité -->
                @if($roomType->capacity && $existingReservations > 0)
                <div class="alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Attention</h6>
                    <p class="mb-0">
                        Ce client a déjà {{ $existingReservations }} réservation(s) de ce type de chambre. 
                        Assurez-vous que le nombre total de personnes respecte la capacité de la chambre.
                    </p>
                </div>
                @endif

                <!-- Formulaire nombre de personnes -->
                <form method="POST" 
                      action="{{ route('transaction.reservation.confirmation', ['customer' => $customer->id, 'roomType' => $roomType->id]) }}">
                    @csrf
                    
                    <!-- Dates (cachées car déjà choisies) -->
                    <input type="hidden" name="check_in" value="{{ $check_in }}">
                    <input type="hidden" name="check_out" value="{{ $check_out }}">

                    <!-- Adultes -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-user me-2"></i>Nombre d'adultes
                        </label>
                        <div class="person-counter">
                            <button type="button" class="counter-btn" onclick="decrementAdult()">-</button>
                            <div class="counter-value" id="adultValue">1</div>
                            <button type="button" class="counter-btn" onclick="incrementAdult()">+</button>
                            <input type="hidden" name="adults" id="adultInput" value="1" required>
                        </div>
                        <small class="text-muted">Minimum 1 adulte</small>
                    </div>

                    <!-- Enfants -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-child me-2"></i>Nombre d'enfants (optionnel)
                        </label>
                        <div class="person-counter">
                            <button type="button" class="counter-btn" onclick="decrementChild()">-</button>
                            <div class="counter-value" id="childValue">0</div>
                            <button type="button" class="counter-btn" onclick="incrementChild()">+</button>
                            <input type="hidden" name="children" id="childInput" value="0">
                        </div>
                        <small class="text-muted">Âge recommandé: moins de 12 ans</small>
                    </div>

                    <!-- Affichage prix estimé -->
                    @if($check_in && $check_out)
                    @php
                        $nights = \Carbon\Carbon::parse($check_in)->diffInDays(\Carbon\Carbon::parse($check_out));
                        $totalPrice = $roomType->base_price * $nights;
                    @endphp
                    <div class="price-display" id="priceDisplay">
                        Total estimé: {{ number_format($totalPrice, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="night-count">
                        ({{ $nights }} nuit(s) × {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA)
                    </div>
                    @endif

                    <!-- Boutons navigation -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('transaction.reservation.choose-type', ['customer' => $customer->id]) }}?check_in={{ $check_in }}&check_out={{ $check_out }}" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn next-btn">
                            Continuer <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar profil -->
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="profile-header">
                        <img src="{{ $customer->user->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $customer->name }}" 
                             class="profile-avatar">
                        <div class="profile-name">{{ $customer->name }}</div>
                        <div class="text-white opacity-75">
                            <i class="fas fa-id-card me-1"></i>ID: {{ $customer->id }}
                        </div>
                    </div>
                    
                    <div class="p-3">
                        <table class="table table-borderless">
                            <tr>
                                <td style="width: 40px">
                                    <i class="fas {{ $customer->gender == 'Male' ? 'fa-mars' : 'fa-venus' }} text-primary"></i>
                                </td>
                                <td>
                                    <div class="fw-bold">Genre</div>
                                    <div class="text-muted">{{ $customer->gender == 'Male' ? 'Homme' : 'Femme' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-briefcase text-primary"></i>
                                </td>
                                <td>
                                    <div class="fw-bold">Profession</div>
                                    <div class="text-muted">{{ $customer->job ?? 'Non spécifié' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-birthday-cake text-primary"></i>
                                </td>
                                <td>
                                    <div class="fw-bold">Naissance</div>
                                    <div class="text-muted">{{ $customer->birthdate ? date('d/m/Y', strtotime($customer->birthdate)) : 'Non spécifiée' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-phone text-primary"></i>
                                </td>
                                <td>
                                    <div class="fw-bold">Téléphone</div>
                                    <div class="text-muted">{{ $customer->phone ?? 'Non spécifié' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-history text-primary"></i>
                                </td>
                                <td>
                                    <div class="fw-bold">Historique</div>
                                    <div class="text-muted">{{ $customer->transactions()->count() }} réservation(s)</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion des compteurs
        let adultCount = 1;
        let childCount = 0;

        function updateCounters() {
            document.getElementById('adultValue').textContent = adultCount;
            document.getElementById('adultInput').value = adultCount;
            document.getElementById('childValue').textContent = childCount;
            document.getElementById('childInput').value = childCount;
        }

        function incrementAdult() {
            adultCount++;
            updateCounters();
        }

        function decrementAdult() {
            if (adultCount > 1) {
                adultCount--;
                updateCounters();
            }
        }

        function incrementChild() {
            childCount++;
            updateCounters();
        }

        function decrementChild() {
            if (childCount > 0) {
                childCount--;
                updateCounters();
            }
        }

        // Validation initiale
        document.addEventListener('DOMContentLoaded', function() {
            updateCounters();
        });
    </script>
@endsection