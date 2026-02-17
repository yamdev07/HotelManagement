@extends('template.master')
@section('title', 'Gestion des Clients')
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   STYLES CUSTOMER INDEX - Design moderne cohérent
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-soft: rgba(37, 99, 235, 0.08);
    --success: #10b981;
    --success-light: rgba(16, 185, 129, 0.08);
    --warning: #f59e0b;
    --warning-light: rgba(245, 158, 11, 0.08);
    --danger: #ef4444;
    --danger-light: rgba(239, 68, 68, 0.08);
    --info: #3b82f6;
    --info-light: rgba(59, 130, 246, 0.08);
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --white: #ffffff;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.05);
    --shadow-hover: 0 10px 30px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background: var(--gray-50);
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* ────────── ANIMATIONS ────────── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in {
    animation: fadeUp 0.4s ease both;
}
.stagger-1 { animation-delay: 0.05s; }
.stagger-2 { animation-delay: 0.1s; }
.stagger-3 { animation-delay: 0.15s; }

/* ────────── EN-TÊTE ────────── */
.header-section {
    background: var(--white);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
}
.header-section h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.header-section h1 i {
    color: var(--primary);
}
.header-section p {
    color: var(--gray-500);
    font-size: 0.9rem;
}

/* ────────── STATISTIQUES ────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.stat-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
    transform: translateY(-2px);
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary);
    border-radius: 4px 0 0 4px;
}
.stat-card.primary::before { background: var(--primary); }
.stat-card.success::before { background: var(--success); }
.stat-card.info::before { background: var(--info); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
}
.stat-footer {
    margin-top: 12px;
    font-size: 0.75rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ────────── BARRE D'ACTIONS ────────── */
.action-bar {
    background: var(--white);
    border-radius: var(--radius);
    padding: 16px 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.action-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.action-right {
    flex: 1;
    max-width: 400px;
}

/* ────────── BOUTONS ────────── */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid transparent;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.btn-modern:hover {
    transform: translateY(-2px);
    text-decoration: none;
}
.btn-primary-modern {
    background: var(--primary);
    color: white;
}
.btn-primary-modern:hover {
    background: var(--primary-light);
    color: white;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
}
.btn-outline-modern {
    background: transparent;
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}
.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
    color: var(--gray-800);
}

/* ────────── FILTRES RAPIDES ────────── */
.filter-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    text-decoration: none;
    transition: var(--transition);
}
.filter-badge:hover {
    background: var(--gray-200);
    color: var(--gray-800);
    transform: translateY(-1px);
}
.filter-badge.active {
    background: var(--primary-soft);
    color: var(--primary);
    border-color: var(--primary);
    font-weight: 600;
}
.badge-count {
    background: var(--gray-200);
    padding: 2px 6px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

/* ────────── RECHERCHE AMÉLIORÉE ────────── */
.search-container {
    position: relative;
    width: 100%;
}
.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 0.9rem;
    pointer-events: none;
    z-index: 2;
}
.search-input {
    width: 100%;
    padding: 10px 16px 10px 42px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.85rem;
    transition: var(--transition);
    background: var(--white);
}
.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}
.search-input::placeholder {
    color: var(--gray-400);
}
.search-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    background: none;
    border: none;
    font-size: 0.9rem;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    transition: var(--transition);
    z-index: 2;
}
.search-clear:hover {
    color: var(--gray-600);
    background: var(--gray-100);
}
.search-results-count {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.7rem;
    color: var(--gray-400);
    background: var(--gray-100);
    padding: 2px 6px;
    border-radius: 20px;
    pointer-events: none;
}

/* ────────── ALERTES ────────── */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    font-size: 0.85rem;
    background: var(--white);
    box-shadow: var(--shadow);
}
.alert-success {
    background: var(--success-light);
    border-color: rgba(16, 185, 129, 0.2);
    color: #047857;
}
.alert-danger {
    background: var(--danger-light);
    border-color: rgba(239, 68, 68, 0.2);
    color: #b91c1c;
}
.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.alert-success .alert-icon {
    background: var(--success);
    color: white;
}
.alert-danger .alert-icon {
    background: var(--danger);
    color: white;
}
.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: currentColor;
    opacity: 0.5;
    cursor: pointer;
    padding: 4px;
    font-size: 0.9rem;
    transition: var(--transition);
}
.alert-close:hover {
    opacity: 1;
}

/* ────────── GRILLE CLIENTS ────────── */
.customer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.customer-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    cursor: pointer;
}
.customer-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--gray-300);
    transform: translateY(-4px);
}

.customer-header {
    position: relative;
    height: 120px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    padding: 16px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.customer-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    background: var(--white);
    position: absolute;
    bottom: -35px;
    left: 20px;
}

.customer-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(4px);
    padding: 4px 10px;
    border-radius: 30px;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.customer-number {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(4px);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
}

.customer-body {
    padding: 45px 20px 20px 20px;
}

.customer-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.customer-name a {
    color: var(--gray-800);
    text-decoration: none;
    transition: var(--transition);
}
.customer-name a:hover {
    color: var(--primary);
}

.customer-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-100);
}
.customer-info-item:last-child {
    border-bottom: none;
}
.customer-info-icon {
    width: 20px;
    color: var(--gray-400);
    font-size: 0.85rem;
}
.customer-info-label {
    font-size: 0.7rem;
    color: var(--gray-500);
    margin-bottom: 2px;
}
.customer-info-value {
    font-size: 0.85rem;
    color: var(--gray-700);
    font-weight: 500;
}

.customer-footer {
    display: flex;
    gap: 8px;
    padding: 15px 20px;
    border-top: 1px solid var(--gray-100);
    background: var(--gray-50);
}

.customer-action-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 0;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-700);
    text-decoration: none;
    transition: var(--transition);
    cursor: pointer;
}
.customer-action-btn:hover {
    background: var(--gray-100);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
}
.customer-action-btn.primary {
    background: var(--primary-soft);
    color: var(--primary);
    border-color: rgba(37, 99, 235, 0.2);
}
.customer-action-btn.primary:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* ────────── MENU DROPDOWN ────────── */
.dropdown-modern {
    border: none;
    border-radius: 10px;
    box-shadow: var(--shadow-hover);
    padding: 8px;
    min-width: 180px;
}
.dropdown-modern .dropdown-item {
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: var(--transition);
}
.dropdown-modern .dropdown-item:hover {
    background: var(--gray-50);
}
.dropdown-modern .dropdown-item i {
    width: 18px;
    color: var(--gray-500);
}
.dropdown-modern .dropdown-divider {
    margin: 6px 0;
    border-top: 1px solid var(--gray-200);
}

/* ────────── ÉTAT VIDE ────────── */
.empty-state {
    background: var(--white);
    border-radius: var(--radius);
    padding: 60px 20px;
    text-align: center;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
}
.empty-state-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
}
.empty-state-text {
    color: var(--gray-500);
    font-size: 0.85rem;
    max-width: 400px;
    margin: 0 auto 24px;
}

/* ────────── PAGINATION ────────── */
.pagination-modern {
    display: flex;
    gap: 5px;
    justify-content: center;
    margin-top: 30px;
}
.pagination-modern .page-item {
    list-style: none;
}
.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: 1px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-600);
    font-size: 0.8rem;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}
.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}
.pagination-modern .active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* ────────── MODAL ────────── */
.modal-modern {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}
.modal-modern .modal-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 18px 24px;
}
.modal-modern .modal-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    display: flex;
    align-items: center;
    gap: 8px;
}
.modal-modern .modal-body {
    padding: 24px;
}
.modal-modern .modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    padding: 16px 24px;
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- En-tête -->
    <div class="header-section fade-in">
        <h1>
            <i class="fas fa-users me-2"></i>
            Gestion des Clients
        </h1>
        <p>Gérez votre base de données clients et consultez leurs réservations</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card primary fade-in stagger-1">
            <div class="stat-number">{{ $customers->total() }}</div>
            <div class="stat-label">Clients totaux</div>
            <div class="stat-footer">
                <i class="fas fa-user"></i>
                {{ $customers->total() }} enregistrés
            </div>
        </div>
        
        <div class="stat-card success fade-in stagger-2">
            <div class="stat-number">{{ $customers->count() }}</div>
            <div class="stat-label">Sur cette page</div>
            <div class="stat-footer">
                <i class="fas fa-users"></i>
                Page {{ $customers->currentPage() }}/{{ $customers->lastPage() }}
            </div>
        </div>
        
        <div class="stat-card info fade-in stagger-3">
            <div class="stat-number">{{ $customers->lastPage() }}</div>
            <div class="stat-label">Pages totales</div>
            <div class="stat-footer">
                <i class="fas fa-layer-group"></i>
                {{ $customers->perPage() }} par page
            </div>
        </div>
    </div>

    <!-- Barre d'actions -->
    <div class="action-bar fade-in">
        <div class="action-left">
            <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="fas fa-plus-circle"></i>
                Nouveau client
            </button>
            
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
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="{{ route('customer.index') }}" id="search-form">
                    <input type="search" 
                           class="search-input" 
                           placeholder="Rechercher par nom, email, téléphone..." 
                           name="search" 
                           id="search-input"
                           value="{{ request()->input('search') }}"
                           autocomplete="off">
                    @if(request()->has('search'))
                    <button type="button" class="search-clear" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                    <span class="search-results-count">{{ $customers->total() }} résultat(s)</span>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
    <div class="alert-modern alert-success fade-in">
        <div class="alert-icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error') || session('failed'))
    <div class="alert-modern alert-danger fade-in">
        <div class="alert-icon">
            <i class="fas fa-exclamation"></i>
        </div>
        <div class="flex-grow-1">{{ session('error') ?? session('failed') }}</div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Grille des clients -->
    @if($customers->count() > 0)
    <div class="customer-grid">
        @foreach ($customers as $customer)
        @php
            $index = ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1;
        @endphp
        
        <div class="customer-card fade-in" style="animation-delay: {{ $loop->index * 0.03 }}s">
            <!-- En-tête -->
            <div class="customer-header">
                <span class="customer-badge">
                    <i class="fas fa-star"></i>
                    Client #{{ $index }}
                </span>
                <span class="customer-number">{{ $index }}</span>
                <img src="{{ $customer->user->getAvatar() }}" 
                     alt="{{ $customer->name }}" 
                     class="customer-avatar"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=2563eb&color=fff&size=70'">
            </div>
            
            <!-- Corps -->
            <div class="customer-body">
                <div class="customer-name">
                    <a href="{{ route('customer.show', $customer->id) }}" class="text-decoration-none">
                        {{ $customer->name }}
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link p-0" style="color: var(--gray-400);" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-modern">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.show', $customer->id) }}">
                                    <i class="fas fa-eye me-2"></i>Voir le profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('transaction.reservation.customerReservations', $customer->id) }}">
                                    <i class="fas fa-calendar-check me-2"></i>Voir ses réservations
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.edit', $customer->id) }}">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" onclick="confirmDelete('{{ $customer->name }}', {{ $customer->id }})">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Informations -->
                <div class="customer-info-item">
                    <div class="customer-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="customer-info-label">Email</div>
                        <div class="customer-info-value">{{ $customer->user->email }}</div>
                    </div>
                </div>
                
                @if($customer->phone)
                <div class="customer-info-item">
                    <div class="customer-info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="customer-info-label">Téléphone</div>
                        <div class="customer-info-value">{{ $customer->phone }}</div>
                    </div>
                </div>
                @endif
                
                @if($customer->job)
                <div class="customer-info-item">
                    <div class="customer-info-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="customer-info-label">Profession</div>
                        <div class="customer-info-value">{{ $customer->job }}</div>
                    </div>
                </div>
                @endif
                
                @if($customer->birthdate)
                <div class="customer-info-item">
                    <div class="customer-info-icon">
                        <i class="fas fa-cake-candles"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="customer-info-label">Date de naissance</div>
                        <div class="customer-info-value">
                            {{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}
                            ({{ \Carbon\Carbon::parse($customer->birthdate)->age }} ans)
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Footer avec actions -->
            <div class="customer-footer">
                <a href="{{ route('customer.show', $customer->id) }}" class="customer-action-btn primary">
                    <i class="fas fa-user"></i>
                    Profil
                </a>
                <a href="{{ route('transaction.reservation.customerReservations', $customer->id) }}" class="customer-action-btn">
                    <i class="fas fa-calendar-check"></i>
                    Réservations
                </a>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="pagination-modern fade-in">
        {{ $customers->onEachSide(2)->links('pagination::bootstrap-5', [
            'class' => 'pagination-modern'
        ]) }}
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
        <p class="empty-state-text">
            {{ request()->has('search') 
                ? 'Aucun client ne correspond à votre recherche. Essayez d\'autres termes.' 
                : 'Commencez par ajouter votre premier client à votre base de données.' }}
        </p>
        <div class="d-flex gap-2 justify-content-center">
            @if(request()->has('search'))
            <a href="{{ route('customer.index') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-times me-2"></i>Effacer la recherche
            </a>
            @endif
            <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="fas fa-plus-circle me-2"></i>Ajouter un client
            </button>
        </div>
    </div>
    @endif

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-modern">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus" style="color: var(--primary);"></i>
                        Nouveau client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div style="font-size: 3rem; color: var(--primary-soft); margin-bottom: 16px;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h6 class="fw-semibold mb-3">Choisissez une méthode</h6>
                    <p class="text-muted small mb-4">Sélectionnez comment vous souhaitez ajouter un nouveau client.</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.create') }}" class="btn-modern btn-primary-modern w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Créer un nouveau compte
                        </a>
                        <button type="button" class="btn-modern btn-outline-modern w-100" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire de suppression caché -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Auto-dismiss des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert-modern');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Debounce pour la recherche
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('search-form').submit();
            }, 500);
        });
    }

    // Confirmation de suppression
    window.confirmDelete = function(name, id) {
        Swal.fire({
            title: 'Confirmer la suppression',
            html: `Êtes-vous sûr de vouloir supprimer <strong>${name}</strong> ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Supprimer',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
            reverseButtons: true,
            background: '#ffffff',
            borderRadius: '12px',
            customClass: {
                popup: 'shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = `/customer/${id}`;
                form.submit();
            }
        });
    };

    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N pour nouveau client
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
            modal.show();
        }
        
        // / pour focus recherche
        if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            e.preventDefault();
            document.getElementById('search-input')?.focus();
        }
        
        // Esc pour effacer la recherche
        if (e.key === 'Escape' && document.activeElement === searchInput && !searchInput.value) {
            document.activeElement.blur();
        }
    });
});

// Fonction pour effacer la recherche
function clearSearch() {
    const form = document.getElementById('search-form');
    const input = document.getElementById('search-input');
    input.value = '';
    form.submit();
}
</script>
@endsection