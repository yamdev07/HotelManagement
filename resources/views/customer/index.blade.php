@extends('template.master')
@section('title', 'Gestion des Clients')
@section('content')
    <style>
        /* Styles améliorés */
        .customer-card {
            min-height: 420px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
        }

        .customer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(25, 117, 209, 0.15);
        }

        .customer-avatar {
            object-fit: cover;
            height: 280px;
            width: 100%;
            border-bottom: 1px solid #e9ecef;
        }

        .number-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1975d1, #0d47a1);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .action-menu {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }

        .action-menu .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 50%;
            background: white;
            border: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-menu .btn:hover {
            background: #f8f9fa;
            transform: scale(1.1);
        }

        .customer-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            padding: 0.3rem 0;
        }

        .info-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: #1975d1;
            flex-shrink: 0;
        }

        .info-text {
            flex: 1;
            color: #4a5568;
            font-size: 0.9rem;
            line-height: 1.4;
            word-break: break-word;
        }

        .info-text.truncate {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .add-btn {
            background: linear-gradient(135deg, #1975d1, #0d47a1);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(25, 117, 209, 0.25);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            border-radius: 8px;
            padding-left: 40px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #1975d1;
            box-shadow: 0 0 0 3px rgba(25, 117, 209, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            z-index: 10;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            margin: 2rem 0;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #a0aec0;
            margin-bottom: 1.5rem;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #1975d1;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: #718096;
            font-size: 0.9rem;
        }

        /* Animation pour les nouvelles cartes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .customer-card {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .customer-avatar {
                height: 220px;
            }
            
            .customer-card {
                min-height: 380px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $customers->total() }}</div>
                    <div class="stats-label">Clients Totaux</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $customers->perPage() }}</div>
                    <div class="stats-label">Par Page</div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Les stats peuvent être étendues ici -->
            </div>
        </div>

        <!-- Barre d'actions -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <button class="btn add-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-user-plus me-2"></i>Nouveau Client
                </button>
            </div>
            <div class="col-lg-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <form method="GET" action="{{ route('customer.index') }}">
                        <input class="form-control" type="search" placeholder="Rechercher un client..." 
                               name="search" value="{{ request()->input('search') }}">
                    </form>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('failed') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Grille des clients -->
        <div class="row">
            @forelse ($customers as $customer)
                @php
                    $index = ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1;
                @endphp
                
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="customer-card">
                        <!-- Badge numéro -->
                        <div class="number-badge">
                            {{ $index }}
                        </div>

                        <!-- Menu actions -->
                        <div class="action-menu">
                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.show', $customer->id) }}">
                                        <i class="fas fa-eye me-2 text-primary"></i>Détails
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.edit', $customer->id) }}">
                                        <i class="fas fa-edit me-2 text-warning"></i>Modifier
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('customer.destroy', $customer->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Supprimer {{ $customer->name }} ?')">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        <!-- Avatar -->
                        <img src="{{ $customer->user->getAvatar() }}" 
                             alt="{{ $customer->name }}" 
                             class="customer-avatar"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=1975d1&color=fff&size=350'">

                        <!-- Informations -->
                        <div class="card-body p-3">
                            <h5 class="customer-name">{{ $customer->name }}</h5>
                            
                            <div class="customer-info">
                                <!-- Email -->
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-text truncate" title="{{ $customer->user->email }}">
                                        {{ $customer->user->email }}
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                @if($customer->phone)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-text">
                                        {{ $customer->phone }}
                                    </div>
                                </div>
                                @endif

                                <!-- Profession -->
                                @if($customer->job)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="info-text">
                                        {{ $customer->job }}
                                    </div>
                                </div>
                                @endif

                                <!-- Adresse -->
                                @if($customer->address)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-text truncate" title="{{ $customer->address }}">
                                        {{ Str::limit($customer->address, 40) }}
                                    </div>
                                </div>
                                @endif

                                <!-- Date de naissance -->
                                @if($customer->birthdate)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-birthday-cake"></i>
                                    </div>
                                    <div class="info-text">
                                        {{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="mb-3">Aucun client trouvé</h4>
                        <p class="text-muted mb-4">
                            {{ request()->has('search') ? 'Aucun client ne correspond à votre recherche.' : 'Commencez par ajouter votre premier client.' }}
                        </p>
                        <a href="{{ route('customer.create') }}" class="btn add-btn">
                            <i class="fas fa-plus me-2"></i>Ajouter un client
                        </a>
                        @if(request()->has('search'))
                            <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-times me-2"></i>Effacer la recherche
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    <nav aria-label="Page navigation">
                        {{ $customers->onEachSide(1)->links('template.paginationlinks') }}
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal d'ajout (optionnel) -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-4">Choisissez comment ajouter le client :</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Créer un nouveau compte
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script>
    $(document).ready(function() {
        // Auto-hide alerts après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);

        // Effet de survol amélioré
        $('.customer-card').hover(
            function() {
                $(this).css({
                    'box-shadow': '0 15px 30px rgba(25, 117, 209, 0.15)',
                    'border-color': 'rgba(25, 117, 209, 0.2)'
                });
            },
            function() {
                $(this).css({
                    'box-shadow': '0 4px 6px rgba(0, 0, 0, 0.05)',
                    'border-color': 'transparent'
                });
            }
        );

        // Recherche en temps réel (optionnel)
        $('#searchInput').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.customer-card').each(function() {
                const customerText = $(this).text().toLowerCase();
                $(this).toggle(customerText.indexOf(searchTerm) > -1);
            });
        });

        // Animation des cartes
        $('.customer-card').each(function(index) {
            $(this).css({
                'animation-delay': (index * 0.1) + 's',
                'opacity': '0'
            });
        });

        // Tooltips pour les textes tronqués
        $('[title]').tooltip({
            placement: 'top',
            trigger: 'hover'
        });
    });
</script>
@endsection