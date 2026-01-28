@extends('template.master')
@section('title', 'Gestion des Clients')
@section('content')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
            --hover-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1), 0 10px 20px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background: var(--light-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .header-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.5);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
        }

        .stat-card.primary::before {
            background: var(--primary-gradient);
        }

        .stat-card.secondary::before {
            background: var(--secondary-gradient);
        }

        .stat-card.success::before {
            background: var(--success-gradient);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .action-bar {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.2);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.3);
        }

        .search-container {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .customer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .customer-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.5);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .customer-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .customer-header {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .customer-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .customer-card:hover .customer-avatar {
            transform: scale(1.05);
        }

        .customer-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.7));
            display: flex;
            align-items: flex-end;
            padding: 1.5rem;
        }

        .customer-name {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .customer-number {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .customer-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        .customer-info {
            padding: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .info-item:hover {
            background: #f8fafc;
        }

        .info-icon {
            width: 20px;
            color: #667eea;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #334155;
            font-weight: 500;
            line-height: 1.4;
            word-break: break-word;
        }

        .info-value.truncate {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
        }

        .empty-state-icon {
            font-size: 4rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            color: #334155;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-state-description {
            color: #64748b;
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .filter-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .filter-badge {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.3s ease;
        }

        .filter-badge:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }

        .filter-badge.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .badge-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.125rem 0.375rem;
            border-radius: 10px;
            font-size: 0.75rem;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        .stagger-delay-1 {
            animation-delay: 0.1s;
        }

        .stagger-delay-2 {
            animation-delay: 0.2s;
        }

        .stagger-delay-3 {
            animation-delay: 0.3s;
        }

        @media (max-width: 768px) {
            .customer-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-container {
                max-width: 100%;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Section d'en-tête -->
        <div class="header-section fade-in">
            <h1 class="display-6 fw-bold mb-3">Gestion des Clients</h1>
            <p class="text-muted mb-0">Gérez efficacement votre base de données clients</p>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card primary fade-in">
                <div class="stat-number">{{ $customers->total() }}</div>
                <div class="stat-label">Clients Totaux</div>
                <div class="mt-3">
                    <span class="text-success fw-semibold">
                        <i class="fas fa-arrow-up me-1"></i>
                        {{ $customers->perPage() }} par page
                    </span>
                </div>
            </div>
            
            <div class="stat-card secondary fade-in stagger-delay-1">
                <div class="stat-number">{{ $customers->count() }}</div>
                <div class="stat-label">Clients Actifs</div>
                <div class="mt-3">
                    <span class="text-muted">
                        <i class="fas fa-users me-1"></i>
                        Affichés
                    </span>
                </div>
            </div>
            
            <div class="stat-card success fade-in stagger-delay-2">
                <div class="stat-number">{{ ceil($customers->total() / $customers->perPage()) }}</div>
                <div class="stat-label">Pages Totales</div>
                <div class="mt-3">
                    <span class="text-info fw-semibold">
                        <i class="fas fa-layer-group me-1"></i>
                        Page {{ $customers->currentPage() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Barre d'actions -->
        <div class="action-bar fade-in stagger-delay-3">
            <div>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus-circle"></i>
                    Nouveau Client
                </button>
                
                <!-- Filtres rapides -->
                <div class="filter-badges">
                    <a href="{{ route('customer.index') }}" class="filter-badge {{ !request()->has('search') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        Tous
                        <span class="badge-count">{{ $customers->total() }}</span>
                    </a>
                    @if(request()->has('search'))
                    <a href="{{ route('customer.index') }}" class="filter-badge">
                        <i class="fas fa-times"></i>
                        Effacer la recherche
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="{{ route('customer.index') }}">
                    <input type="search" 
                           class="search-input" 
                           placeholder="Rechercher par nom, email, téléphone..." 
                           name="search" 
                           value="{{ request()->input('search') }}"
                           autocomplete="off">
                </form>
            </div>
        </div>

        <!-- Messages de statut -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm fade-in" role="alert">
            <div class="bg-success bg-gradient rounded-circle p-2 me-3">
                <i class="fas fa-check text-white" style="font-size: 1rem;"></i>
            </div>
            <div class="flex-grow-1">
                <strong>Succès !</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('failed'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm fade-in" role="alert">
            <div class="bg-danger bg-gradient rounded-circle p-2 me-3">
                <i class="fas fa-exclamation text-white" style="font-size: 1rem;"></i>
            </div>
            <div class="flex-grow-1">
                <strong>Erreur !</strong> {{ session('failed') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Grille des clients -->
        @if($customers->count() > 0)
        <div class="customer-grid">
            @foreach ($customers as $customer)
            @php
                $index = ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1;
                $colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#43e97b', '#38f9d7'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            
            <div class="customer-card fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <!-- Numéro du client -->
                <div class="customer-number">{{ $index }}</div>
                
                <!-- Actions -->
                <div class="customer-actions">
                    <div class="dropdown">
                        <button class="action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.show', $customer->id) }}">
                                    <i class="fas fa-eye text-primary me-2"></i>Voir détails
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.edit', $customer->id) }}">
                                    <i class="fas fa-edit text-warning me-2"></i>Modifier
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('customer.destroy', $customer->id) }}" id="delete-form-{{ $customer->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="dropdown-item text-danger"
                                            onclick="confirmDelete('{{ $customer->name }}', {{ $customer->id }})">
                                        <i class="fas fa-trash me-2"></i>Supprimer
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- En-tête avec avatar -->
                <div class="customer-header">
                    <img src="{{ $customer->user->getAvatar() }}" 
                         alt="{{ $customer->name }}" 
                         class="customer-avatar"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background={{ substr($color, 1) }}&color=fff&size=350&bold=true&font-size=0.5'">
                    <div class="customer-overlay">
                        <h3 class="customer-name">{{ $customer->name }}</h3>
                    </div>
                </div>
                
                <!-- Informations du client -->
                <div class="customer-info">
                    <!-- Email -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Email</div>
                            <div class="info-value truncate" title="{{ $customer->user->email }}">
                                {{ $customer->user->email }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Téléphone -->
                    @if($customer->phone)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $customer->phone }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Profession -->
                    @if($customer->job)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Profession</div>
                            <div class="info-value">{{ $customer->job }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Dernière information -->
                    @if($customer->birthdate)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Date de naissance</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}
                                ({{ \Carbon\Carbon::parse($customer->birthdate)->age }} ans)
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="d-flex justify-content-center mt-5 fade-in">
            <nav aria-label="Pagination">
                {{ $customers->onEachSide(2)->links('template.paginationlinks', [
                    'class' => 'pagination pagination-lg'
                ]) }}
            </nav>
        </div>
        @endif
        
        @else
        <!-- État vide -->
        <div class="empty-state fade-in">
            <div class="empty-state-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="empty-state-title">
                {{ request()->has('search') ? 'Aucun client trouvé' : 'Aucun client pour le moment' }}
            </h3>
            <p class="empty-state-description">
                {{ request()->has('search') 
                    ? 'Aucun client ne correspond à votre recherche. Essayez d\'autres termes.' 
                    : 'Commencez par ajouter votre premier client à votre base de données.' }}
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->has('search'))
                <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Effacer la recherche
                </a>
                @endif
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un client
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Ajouter un nouveau client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="bg-gradient-primary rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-user-plus text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-semibold mb-3">Choisissez une méthode</h5>
                        <p class="text-muted">Sélectionnez comment vous souhaitez ajouter un nouveau client à votre système.</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.create') }}" class="btn btn-primary-custom btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            Créer un nouveau compte client
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des éléments
        const animateElements = () => {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        };
        
        // Initial animation
        setTimeout(animateElements, 100);
        
        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
        
        // Hover effects for cards
        const cards = document.querySelectorAll('.customer-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
        
        // Tooltips for truncated text
        const truncatedElements = document.querySelectorAll('.truncate');
        truncatedElements.forEach(el => {
            el.addEventListener('mouseenter', function() {
                if (this.scrollWidth > this.clientWidth) {
                    const tooltip = new bootstrap.Tooltip(this, {
                        title: this.getAttribute('title'),
                        placement: 'top',
                        trigger: 'hover'
                    });
                    tooltip.show();
                }
            });
        });
        
        // Search debouncing
        let searchTimeout;
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 500);
            });
        }
        
        // Confirmation de suppression
        window.confirmDelete = function(name, id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: `Le client <strong>"${name}"</strong> sera définitivement supprimé.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                backdrop: 'rgba(0, 0, 0, 0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        };
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N pour nouveau client
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                const modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
                modal.show();
            }
            
            // / pour focus sur la recherche
            if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput?.focus();
            }
        });
        
        // Update stats with animation
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            let currentValue = 0;
            const duration = 1500;
            const increment = finalValue / (duration / 16);
            
            const updateNumber = () => {
                if (currentValue < finalValue) {
                    currentValue += increment;
                    if (currentValue > finalValue) currentValue = finalValue;
                    stat.textContent = Math.floor(currentValue).toLocaleString();
                    requestAnimationFrame(updateNumber);
                }
            };
            
            // Only animate on initial load
            if (!sessionStorage.getItem('statsAnimated')) {
                stat.textContent = '0';
                setTimeout(updateNumber, 500);
                sessionStorage.setItem('statsAnimated', 'true');
            }
        });
    });
</script>
@endsection