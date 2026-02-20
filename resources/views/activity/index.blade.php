@extends('template.master')
@section('title', 'Journal d\'activités')
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN SYSTEM - MÊME STYLE QUE CHECK-IN
═══════════════════════════════════════════════════════════════════ */
:root {
    --primary-50: #ecfdf5;
    --primary-100: #d1fae5;
    --primary-400: #34d399;
    --primary-500: #10b981;
    --primary-600: #059669;
    --primary-700: #047857;
    --primary-800: #065f46;

    --amber-50: #fffbeb;
    --amber-100: #fef3c7;
    --amber-400: #fbbf24;
    --amber-500: #f59e0b;
    --amber-600: #d97706;

    --blue-50: #eff6ff;
    --blue-100: #dbeafe;
    --blue-500: #3b82f6;
    --blue-600: #2563eb;

    --red-50: #fee2e2;
    --red-500: #ef4444;
    --red-600: #dc2626;

    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
}

* { box-sizing: border-box; }

.activity-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', system-ui, sans-serif;
}

/* Breadcrumb */
.breadcrumb-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.813rem;
    color: var(--gray-400);
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.breadcrumb-custom a {
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-custom a:hover {
    color: var(--primary-600);
}

.breadcrumb-custom .separator {
    color: var(--gray-300);
    font-size: 0.688rem;
}

.breadcrumb-custom .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* En-tête */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-title h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
}

.header-subtitle {
    color: var(--gray-500);
    font-size: 0.875rem;
    margin: 6px 0 0 60px;
}

/* Boutons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(5, 150, 105, 0.4);
    color: white;
    text-decoration: none;
}

.btn-outline-modern {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-900);
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-warning-modern {
    background: var(--amber-500);
    color: white;
}

.btn-warning-modern:hover {
    background: var(--amber-600);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

.btn-danger-modern {
    background: var(--red-500);
    color: white;
}

.btn-danger-modern:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

.btn-sm-modern {
    padding: 6px 14px;
    font-size: 0.813rem;
    border-radius: 8px;
}

.btn-icon-modern {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-1px);
}

/* Dropdown */
.dropdown-modern .dropdown-menu {
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    padding: 8px;
}

.dropdown-modern .dropdown-item {
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.875rem;
    color: var(--gray-700);
    transition: all 0.2s;
}

.dropdown-modern .dropdown-item:hover {
    background: var(--gray-50);
    color: var(--gray-900);
}

.dropdown-modern .dropdown-item i {
    width: 20px;
    color: var(--primary-500);
}

.dropdown-modern .dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid var(--gray-200);
}

/* Cartes */
.card-modern {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    margin-bottom: 24px;
}

.card-modern:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
}

.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid var(--gray-100);
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.card-header-modern h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header-modern h3 i {
    color: var(--primary-500);
}

.card-body-modern {
    padding: 24px;
}

/* Filtres */
.filter-section {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.form-group-modern {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label-modern {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
}

.form-control-modern {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    color: var(--gray-700);
    transition: all 0.2s;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.form-select-modern {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.875rem;
    color: var(--gray-700);
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
}

.filter-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--blue-50);
    border: 1px solid var(--blue-100);
    border-radius: 30px;
    font-size: 0.813rem;
    color: var(--blue-600);
}

.filter-badge i {
    color: var(--blue-500);
}

/* Tableau */
.table-container {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.table-responsive {
    overflow-x: auto;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: var(--gray-50);
    padding: 16px 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
    white-space: nowrap;
}

.table-modern tbody td {
    padding: 20px;
    border-bottom: 1px solid var(--gray-100);
    color: var(--gray-700);
    font-size: 0.875rem;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: var(--gray-50);
}

.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Badges */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
}

.badge-success {
    background: var(--primary-100);
    color: var(--primary-700);
    border: 1px solid var(--primary-200);
}

.badge-warning {
    background: var(--amber-100);
    color: var(--amber-700);
    border: 1px solid var(--amber-200);
}

.badge-danger {
    background: var(--red-50);
    color: var(--red-700);
    border: 1px solid #fecaca;
}

.badge-info {
    background: var(--blue-100);
    color: var(--blue-700);
    border: 1px solid var(--blue-200);
}

.badge-secondary {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

/* Avatar */
.avatar-modern {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
}

.avatar-modern img {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
}

/* Objet badge */
.object-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    background: var(--gray-100);
    color: var(--gray-700);
}

.object-badge i {
    color: var(--primary-500);
}

/* Détails collapse */
.details-collapse {
    margin-top: 12px;
    background: var(--gray-50);
    border-radius: 12px;
    padding: 16px;
    border: 1px solid var(--gray-200);
}

.details-pre {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 16px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-family: 'Courier New', monospace;
    overflow-x: auto;
    max-height: 200px;
}

/* Pagination */
.pagination-modern {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.pagination-modern .page-item {
    list-style: none;
}

.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
}

.pagination-modern .page-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
    transform: translateY(-2px);
}

.pagination-modern .active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: white;
}

.per-page-select {
    width: 80px;
    padding: 6px 10px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.813rem;
    color: var(--gray-700);
    background: white;
    cursor: pointer;
}

/* Stats cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card-modern {
    background: white;
    border-radius: 20px;
    padding: 20px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}

.stat-card-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--primary-100);
    color: var(--primary-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.688rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
}

.stat-change {
    font-size: 0.75rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
}

.stat-change.positive {
    color: var(--primary-600);
}

/* Modal */
.modal-modern .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.modal-modern .modal-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 20px 24px;
}

.modal-modern .modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
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

.modal-modern pre {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 16px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-family: 'Courier New', monospace;
    overflow-x: auto;
    max-height: 300px;
}

/* Alert */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid transparent;
    font-size: 0.875rem;
    background: white;
    box-shadow: var(--shadow-md);
}

.alert-info {
    background: var(--blue-50);
    border-color: var(--blue-200);
    color: var(--blue-800);
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

.alert-info .alert-icon {
    background: var(--blue-500);
    color: white;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .activity-page {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .table-modern thead th {
        padding: 12px;
    }
    
    .table-modern tbody td {
        padding: 12px;
    }
    
    .pagination-modern {
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

.stagger-1 { animation-delay: 0.05s; }
.stagger-2 { animation-delay: 0.1s; }
.stagger-3 { animation-delay: 0.15s; }
</style>

<div class="activity-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs me-1"></i>Dashboard</a>
        <span class="separator"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Journal d'activités</span>
    </div>

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <span class="header-icon">
                <i class="fas fa-history"></i>
            </span>
            <h1>Journal d'activités</h1>
        </div>
        <p class="header-subtitle">Consultez l'historique des actions système</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card-modern fade-in stagger-1">
            <div class="stat-icon-wrapper">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total activités</div>
                <div class="stat-value">{{ $activities->total() }}</div>
                <div class="stat-change">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $activities->lastPage() }} pages
                </div>
            </div>
        </div>
        
        <div class="stat-card-modern fade-in stagger-2">
            <div class="stat-icon-wrapper">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Cette semaine</div>
                <div class="stat-value">{{ $weeklyCount ?? 0 }}</div>
                <div class="stat-change {{ isset($weeklyChange) && $weeklyChange > 0 ? 'positive' : '' }}">
                    @if(isset($weeklyChange))
                        <i class="fas {{ $weeklyChange > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ abs($weeklyChange) }}% vs semaine dernière
                    @endif
                </div>
            </div>
        </div>
        
        <div class="stat-card-modern fade-in stagger-3">
            <div class="stat-icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Utilisateurs actifs</div>
                <div class="stat-value">{{ $activeUsersCount ?? $users->count() }}</div>
                <div class="stat-change">
                    <i class="fas fa-clock"></i>
                    24 dernières heures
                </div>
            </div>
        </div>
    </div>

    <!-- Section de filtres -->
    <div class="filter-section fade-in">
        <form method="GET" action="{{ route('activity.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="form-group-modern">
                    <label class="form-label-modern">Utilisateur</label>
                    <select name="user_id" class="form-select-modern">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">Événement</label>
                    <select name="event" class="form-select-modern">
                        <option value="">Tous les événements</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Création</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Modification</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Suppression</option>
                        <option value="restored" {{ request('event') == 'restored' ? 'selected' : '' }}>Restauration</option>
                    </select>
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">Date de début</label>
                    <input type="date" name="date_from" class="form-control-modern" value="{{ request('date_from') }}">
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">Date de fin</label>
                    <input type="date" name="date_to" class="form-control-modern" value="{{ request('date_to') }}">
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">Type d'objet</label>
                    <select name="subject_type" class="form-select-modern">
                        <option value="">Tous les types</option>
                        <option value="App\Models\User" {{ request('subject_type') == 'App\Models\User' ? 'selected' : '' }}>Utilisateurs</option>
                        <option value="App\Models\Facility" {{ request('subject_type') == 'App\Models\Facility' ? 'selected' : '' }}>Équipements</option>
                        <option value="App\Models\Room" {{ request('subject_type') == 'App\Models\Room' ? 'selected' : '' }}>Chambres</option>
                        <option value="App\Models\Type" {{ request('subject_type') == 'App\Models\Type' ? 'selected' : '' }}>Types</option>
                        <option value="App\Models\Transaction" {{ request('subject_type') == 'App\Models\Transaction' ? 'selected' : '' }}>Transactions</option>
                    </select>
                </div>
                
                <div class="form-group-modern">
                    <label class="form-label-modern">Recherche</label>
                    <input type="text" name="search" class="form-control-modern" 
                           placeholder="Description..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <a href="{{ route('activity.index') }}" class="btn-modern btn-outline-modern">
                    <i class="fas fa-times me-2"></i>Réinitialiser
                </a>
                <button type="button" class="btn-modern btn-warning-modern" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                    <i class="fas fa-broom me-2"></i>Nettoyer
                </button>
                
                <div class="dropdown-modern ms-auto">
                    <button class="btn-modern btn-outline-modern dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('activity.export', ['format' => 'csv']) }}"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                        <li><a class="dropdown-item" href="{{ route('activity.export', ['format' => 'json']) }}"><i class="fas fa-file-code me-2"></i>JSON</a></li>
                        <li><a class="dropdown-item" href="{{ route('activity.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    </ul>
                </div>
            </div>
        </form>
        
        <!-- Résumé des filtres -->
        @if(request()->anyFilled(['user_id', 'event', 'date_from', 'date_to', 'search', 'subject_type']))
            <div class="filter-badge mt-4">
                <i class="fas fa-info-circle"></i>
                <span>
                    Filtres actifs : 
                    @if(request('user_id')) Utilisateur #{{ request('user_id') }} · @endif
                    @if(request('event')) {{ request('event') }} · @endif
                    @if(request('date_from')) dès {{ request('date_from') }} · @endif
                    @if(request('date_to')) jusqu'au {{ request('date_to') }} · @endif
                    @if(request('search')) "{{ request('search') }}" · @endif
                    @if(request('subject_type')) {{ class_basename(request('subject_type')) }} · @endif
                    {{ $activities->total() }} résultat(s)
                </span>
                <a href="{{ route('activity.index') }}" class="btn-icon-modern" style="width: auto; padding: 0 8px;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        @endif
    </div>

    <!-- Tableau des activités -->
    <div class="table-container fade-in">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Heure</th>
                        <th>Action</th>
                        <th>Utilisateur</th>
                        <th>Objet</th>
                        <th>Événement</th>
                        <th>IP</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        @php
                            $eventColor = match($activity->event) {
                                'created' => 'badge-success',
                                'updated' => 'badge-warning',
                                'deleted' => 'badge-danger',
                                'restored' => 'badge-info',
                                default => 'badge-secondary'
                            };
                            $eventIcon = match($activity->event) {
                                'created' => 'fa-plus-circle',
                                'updated' => 'fa-edit',
                                'deleted' => 'fa-trash-alt',
                                'restored' => 'fa-undo-alt',
                                default => 'fa-history'
                            };
                            $eventLabel = match($activity->event) {
                                'created' => 'Création',
                                'updated' => 'Modification',
                                'deleted' => 'Suppression',
                                'restored' => 'Restauration',
                                default => ucfirst($activity->event)
                            };
                            
                            $modelName = $activity->subject ? class_basename($activity->subject_type) : 'Inconnu';
                            $modelIcon = match($modelName) {
                                'User' => 'fa-user',
                                'Facility' => 'fa-cogs',
                                'Room' => 'fa-bed',
                                'Type' => 'fa-tag',
                                'Transaction' => 'fa-receipt',
                                default => 'fa-cube'
                            };
                        @endphp
                        
                        <tr>
                            <td>
                                <span class="badge-modern badge-secondary">#{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</span>
                            </td>
                            
                            <td>
                                <div style="font-weight: 500; color: var(--gray-800);">{{ $activity->created_at->format('d/m/Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $activity->created_at->format('H:i:s') }}</div>
                            </td>
                            
                            <td>
                                <div style="font-weight: 500; color: var(--gray-800); margin-bottom: 4px;">{{ $activity->description }}</div>
                                
                                @if($activity->properties->count() > 0)
                                    <button class="btn-modern btn-sm-modern btn-outline-modern" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#details-{{ $activity->id }}"
                                            style="padding: 2px 8px; font-size: 0.7rem;">
                                        <i class="fas fa-code me-1"></i>Détails
                                    </button>
                                    
                                    <div class="collapse details-collapse" id="details-{{ $activity->id }}">
                                        <pre class="details-pre">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @endif
                            </td>
                            
                            <td>
                                @if($activity->causer)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-modern" style="width: 32px; height: 32px;">
                                            @if($activity->causer->avatar)
                                                <img src="{{ asset('storage/' . $activity->causer->avatar) }}" alt="{{ $activity->causer->name }}">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 500; color: var(--gray-800);">{{ $activity->causer->name }}</div>
                                            <div style="font-size: 0.688rem; color: var(--gray-500);">{{ $activity->causer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-modern" style="width: 32px; height: 32px; background: var(--gray-200); color: var(--gray-500);">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <div style="color: var(--gray-500); font-style: italic;">Système</div>
                                    </div>
                                @endif
                            </td>
                            
                            <td>
                                <div class="object-badge">
                                    <i class="fas {{ $modelIcon }}"></i>
                                    <span>{{ $modelName }}</span>
                                </div>
                                @if($activity->subject && method_exists($activity->subject, 'getNameAttribute'))
                                    <div style="font-size: 0.688rem; color: var(--gray-500); margin-top: 4px;">
                                        {{ $activity->subject->getNameAttribute() }}
                                    </div>
                                @endif
                            </td>
                            
                            <td>
                                <span class="badge-modern {{ $eventColor }}">
                                    <i class="fas {{ $eventIcon }} me-1"></i>
                                    {{ $eventLabel }}
                                </span>
                            </td>
                            
                            <td>
                                <div style="font-family: 'Courier New', monospace; font-size: 0.75rem; color: var(--gray-500);">
                                    {{ $activity->properties['ip_address'] ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <div class="actions-group" style="display: flex; gap: 4px; justify-content: center;">
                                    <button class="btn-icon-modern" 
                                            onclick="showActivityDetails({{ $activity->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Voir détails">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('activity.show', $activity->id) }}" 
                                       class="btn-icon-modern"
                                       data-bs-toggle="tooltip" 
                                       title="Ouvrir">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 60px 20px;">
                                <div class="empty-state-modern" style="text-align: center;">
                                    <i class="fas fa-history" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 16px;"></i>
                                    <h5 style="color: var(--gray-600);">Aucune activité trouvée</h5>
                                    <p style="color: var(--gray-400);">Aucun log d'activité n'a été enregistré pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
        <div style="padding: 20px 24px; border-top: 1px solid var(--gray-100);">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div style="font-size: 0.813rem; color: var(--gray-500);">
                    Affichage de <strong>{{ $activities->firstItem() }}</strong> à 
                    <strong>{{ $activities->lastItem() }}</strong> sur 
                    <strong>{{ $activities->total() }}</strong> entrées
                </div>
                
                <nav>
                    <ul class="pagination-modern">
                        <!-- Première page -->
                        <li class="page-item {{ $activities->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $activities->url(1) }}" title="Première page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>
                        
                        <!-- Page précédente -->
                        <li class="page-item {{ $activities->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $activities->previousPageUrl() }}" title="Page précédente">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                        
                        <!-- Pages numérotées -->
                        @php
                            $current = $activities->currentPage();
                            $last = $activities->lastPage();
                            $start = max($current - 2, 1);
                            $end = min($current + 2, $last);
                            
                            if ($start > 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            
                            for ($i = $start; $i <= $end; $i++) {
                                echo '<li class="page-item ' . ($i == $current ? 'active' : '') . '">';
                                echo '<a class="page-link" href="' . $activities->url($i) . '">' . $i . '</a>';
                                echo '</li>';
                            }
                            
                            if ($end < $last) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        @endphp
                        
                        <!-- Page suivante -->
                        <li class="page-item {{ !$activities->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $activities->nextPageUrl() }}" title="Page suivante">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        
                        <!-- Dernière page -->
                        <li class="page-item {{ !$activities->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $activities->url($last) }}" title="Dernière page">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size: 0.75rem; color: var(--gray-500);">Lignes par page:</span>
                    <select class="per-page-select" onchange="changePerPage(this)">
                        <option value="10" {{ $activities->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $activities->perPage() == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $activities->perPage() == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $activities->perPage() == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal de détails -->
    <div class="modal fade modal-modern" id="activityDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle" style="color: var(--primary-500);"></i>
                        Détails de l'activité
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="activityDetailsContent">
                    <!-- Chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de nettoyage -->
    <div class="modal fade modal-modern" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-broom" style="color: var(--amber-500);"></i>
                        Nettoyer les logs
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('activity.cleanup') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p style="color: var(--gray-600); margin-bottom: 16px;">
                            Supprimer les logs plus anciens que :
                        </p>
                        
                        <div class="form-group-modern mb-4">
                            <label class="form-label-modern">Nombre de jours</label>
                            <input type="number" name="days" class="form-control-modern" min="1" max="365" value="30">
                            <small class="text-muted" style="margin-top: 4px; display: block;">
                                Les logs plus anciens que ce nombre de jours seront définitivement supprimés.
                            </small>
                        </div>
                        
                        <div class="alert-modern alert-info">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <strong>Attention :</strong> Cette action est irréversible. 
                                {{ $activities->total() }} logs seront analysés.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modern btn-outline-modern" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn-modern btn-danger-modern">
                            <i class="fas fa-broom me-2"></i>Nettoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script>
// Initialisation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Afficher les détails d'une activité
function showActivityDetails(activityId) {
    fetch(`/activity/${activityId}/details`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('activityDetailsContent');
            
            content.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h6 style="color: var(--gray-700); font-weight: 600; margin-bottom: 12px;">Informations générales</h6>
                    <div style="background: var(--gray-50); border-radius: 12px; padding: 16px;">
                        <div style="display: grid; grid-template-columns: 120px 1fr; gap: 8px;">
                            <span style="color: var(--gray-500);">ID :</span>
                            <span style="color: var(--gray-800);">${data.id}</span>
                            
                            <span style="color: var(--gray-500);">Date :</span>
                            <span style="color: var(--gray-800);">${data.created_at}</span>
                            
                            <span style="color: var(--gray-500);">Événement :</span>
                            <span><span class="badge-modern ${data.event_color === 'success' ? 'badge-success' : (data.event_color === 'warning' ? 'badge-warning' : (data.event_color === 'danger' ? 'badge-danger' : 'badge-info'))}">
                                <i class="fas ${data.event_icon}"></i> ${data.event_label}
                            </span></span>
                            
                            <span style="color: var(--gray-500);">Log Name :</span>
                            <span style="color: var(--gray-800);">${data.log_name}</span>
                            
                            <span style="color: var(--gray-500);">IP :</span>
                            <span style="color: var(--gray-800); font-family: monospace;">${data.ip_address || 'N/A'}</span>
                            
                            <span style="color: var(--gray-500);">User Agent :</span>
                            <span style="color: var(--gray-800); font-size: 0.75rem;">${data.user_agent || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                
                <h6 style="color: var(--gray-700); font-weight: 600; margin-bottom: 12px;">Propriétés</h6>
                <pre>${JSON.stringify(data.properties, null, 2)}</pre>
                
                <h6 style="color: var(--gray-700); font-weight: 600; margin-bottom: 12px; margin-top: 20px;">Modifications</h6>
                <pre>${JSON.stringify(data.changes, null, 2)}</pre>
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('activityDetailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails');
        });
}

// Changer le nombre d'éléments par page
function changePerPage(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', select.value);
    window.location.href = url.toString();
}
</script>
@endsection