<!-- Sidebar -->
<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
        <div class="d-flex align-items-center">
            <!-- Remplacez l'image par votre logo -->
            <div class="brand-icon">
                <i class="fas fa-hotel"></i> <!-- Icône FontAwesome -->
            </div>
            <div class="brand-text ms-2">
                <span class="brand-name">Hotel Management</span>
                <small class="brand-subtitle d-block">Gestion Hôtelière</small>
            </div>
        </div>
        <button id="toggle-sidebar" class="btn btn-icon">
            <i class="fas fa-bars"></i> <!-- Icône FontAwesome -->
        </button>
    </a>

    <!-- Sidebar Inner -->
    <div class="sidebar-inner">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <button id="toggle-sidebar-sm" class="btn btn-icon">
                <i class="fas fa-bars"></i> <!-- Icône FontAwesome -->
            </button>
        </div>

        <!-- Sidebar Body -->
        <div class="sidebar-body">

            <!-- Navigation Menu -->
            <nav class="nav-menu">

                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    
                    <a href="{{ route('dashboard.index') }}" 
                       class="nav-item {{ Route::currentRouteName() == 'dashboard.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-pie"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Dashboard</div>
                            <div class="nav-subtitle">Analytique & Vue d'ensemble</div>
                        </div>
                    </a>
                </div>

                <!-- Opérations -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Receptionist')
                <div class="nav-section">
                    <div class="nav-section-title">Opérations</div>

                    <!-- Check-in & Disponibilité -->
                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                        <!-- Check-in -->
                        @php
                            $currentRoute = Route::currentRouteName() ?? '';
                            $isCheckinActive = in_array($currentRoute, ['checkin.index', 'checkin.search', 'checkin.show', 'checkin.direct']);
                        @endphp
                        
                        @if(Route::has('checkin.index'))
                            <a href="{{ route('checkin.index') }}"
                               class="nav-item {{ $isCheckinActive ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-door-open"></i> <!-- Icône FontAwesome -->
                                </div>
                                <div class="nav-content">
                                    <div class="nav-title">Check-in</div>
                                    <div class="nav-subtitle">Enregistrement clients</div>
                                </div>
                            </a>
                        @else
                            <!-- Fallback si route n'existe pas -->
                            <a href="#" 
                               class="nav-item {{ $isCheckinActive ? 'active' : '' }}"
                               onclick="alert('Module check-in en cours de développement'); return false;">
                                <div class="nav-icon">
                                    <i class="fas fa-door-open"></i> <!-- Icône FontAwesome -->
                                </div>
                                <div class="nav-content">
                                    <div class="nav-title">Check-in</div>
                                    <div class="nav-subtitle">Bientôt disponible</div>
                                </div>
                            </a>
                        @endif

                        <!-- Disponibilité -->
                        @if(Route::has('availability.dashboard'))
                        <a href="{{ route('availability.dashboard') }}"
                        class="nav-item {{ str_contains(Route::currentRouteName(), 'availability.') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i> <!-- Icône changée pour dashboard -->
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Disponibilité</div>
                                <div class="nav-subtitle">Dashboard & Inventaire</div> <!-- Texte modifié -->
                            </div>
                        </a>
                        @endif

                        <!-- Réservations Rapides -->
                        @if(Route::has('transaction.reservation.createIdentity') && in_array(auth()->user()->role, ['Super', 'Admin']))
                        <a href="{{ route('transaction.reservation.createIdentity') }}"
                           class="nav-item {{ Route::currentRouteName() == 'transaction.reservation.createIdentity' ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-plus-circle"></i> <!-- Icône FontAwesome -->
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Nouvelle Réservation</div>
                                <div class="nav-subtitle">Création rapide</div>
                            </div>
                        </a>
                        @endif
                    @endif

                    <!-- Caisse -->
                    @if(Route::has('cashier.dashboard'))
                    <a href="{{ route('cashier.dashboard') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'cashier.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-cash-register"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Caisse</div>
                            <div class="nav-subtitle">Sessions & Transactions</div>
                        </div>
                    </a>
                    @endif

                    <!-- Restaurant -->
                    @if(Route::has('restaurant.index'))
                    <a href="{{ route('restaurant.index') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'restaurant.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-utensils"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Restaurant</div>
                            <div class="nav-subtitle">Menus & Commandes</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Gestion -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>

                    <!-- Transactions -->
                    @if(Route::has('transaction.index'))
                    <a href="{{ route('transaction.index') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'transaction.') && !str_contains(Route::currentRouteName(), 'transaction.reservation.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-shopping-bag"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Transactions</div>
                            <div class="nav-subtitle">Réservations & Séjours</div>
                        </div>
                    </a>
                    @endif

                    <!-- Clients -->
                    @if(Route::has('customer.index'))
                    <a href="{{ route('customer.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'customer.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Clients</div>
                            <div class="nav-subtitle">Gestion des clients</div>
                        </div>
                    </a>
                    @endif

                    <!-- Chambres -->
                    @if(Route::has('room.index'))
                    <a href="{{ route('room.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'room.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-bed"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Chambres</div>
                            <div class="nav-subtitle">Gestion des chambres</div>
                        </div>
                    </a>
                    @endif

                    <!-- Types de Chambres -->
                    @if(Route::has('type.index'))
                    <a href="{{ route('type.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'type.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-layer-group"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Types de Chambres</div>
                            <div class="nav-subtitle">Catégories & Tarifs</div>
                        </div>
                    </a>
                    @endif

                    <!-- Équipements -->
                    @if(Route::has('facility.index'))
                    <a href="{{ route('facility.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'facility.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-tools"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Équipements</div>
                            <div class="nav-subtitle">Services & Commodités</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Nettoyage -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping']))
                <div class="nav-section">
                    <div class="nav-section-title">Nettoyage</div>

                    <!-- Femmes de Chambre -->
                    @if(Route::has('housekeeping.index'))
                    <a href="{{ route('housekeeping.index') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'housekeeping.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-broom"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Femmes de Chambre</div>
                            <div class="nav-subtitle">Nettoyage & Maintenance</div>
                        </div>
                    </a>
                    @endif

                    <!-- Statuts des Chambres -->
                    @if(Route::has('roomstatus.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <a href="{{ route('roomstatus.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'roomstatus.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-flag"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Statuts Chambres</div>
                            <div class="nav-subtitle">États & Couleurs</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Administration -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>

                    <!-- Utilisateurs -->
                    @if(Route::has('user.index') && auth()->user()->role == 'Super')
                    <a href="{{ route('user.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'user.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-user-cog"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Utilisateurs</div>
                            <div class="nav-subtitle">Gestion des comptes</div>
                        </div>
                    </a>
                    @endif

                    <!-- Rapports -->
                    @if(Route::has('reports.index'))
                    <a href="{{ route('reports.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'reports.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Rapports</div>
                            <div class="nav-subtitle">Analyses & Statistiques</div>
                        </div>
                    </a>
                    @endif

                    <!-- Journal d'Activité -->
                    @if(Route::has('activity-log.index'))
                    <a href="{{ route('activity-log.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'activity-log.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-history"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Journal</div>
                            <div class="nav-subtitle">Activités système</div>
                        </div>
                    </a>
                    @endif

                    <!-- Paiements -->
                    @if(Route::has('payments.index'))
                    <a href="{{ route('payments.index') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'payments.') || str_contains(Route::currentRouteName(), 'payment.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Paiements</div>
                            <div class="nav-subtitle">Transactions financières</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Mon Compte -->
                <div class="nav-section">
                    <div class="nav-section-title">Mon Compte</div>

                    <!-- Profil -->
                    @if(Route::has('profile.index'))
                    <a href="{{ route('profile.index') }}"
                       class="nav-item {{ str_contains(Route::currentRouteName(), 'profile.') ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-user"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Profil</div>
                            <div class="nav-subtitle">Mes informations</div>
                        </div>
                    </a>
                    @endif

                    <!-- Mes Réservations (pour clients) -->
                    @if(auth()->user()->role == 'Customer' && Route::has('transaction.myReservations'))
                    <a href="{{ route('transaction.myReservations') }}"
                       class="nav-item {{ Route::currentRouteName() == 'transaction.myReservations' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-book"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Mes Réservations</div>
                            <div class="nav-subtitle">Historique</div>
                        </div>
                    </a>
                    @endif

                    <!-- Notifications -->
                    @if(Route::has('notification.index'))
                    <a href="{{ route('notification.index') }}"
                       class="nav-item {{ Route::currentRouteName() == 'notification.index' ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-bell"></i> <!-- Icône FontAwesome -->
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="nav-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Notifications</div>
                            <div class="nav-subtitle">Alertes & Messages</div>
                        </div>
                    </a>
                    @endif

                    <!-- DÉCONNEXION FONCTIONNELLE POUR TOUS -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit(); return false;"
                       class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-sign-out-alt"></i> <!-- Icône FontAwesome -->
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Déconnexion</div>
                            <div class="nav-subtitle">Quitter la session</div>
                        </div>
                    </a>
                </div>

            </nav>

        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @switch(auth()->user()->role)
                            @case('Super')
                                <span class="badge bg-danger">Super Admin</span>
                                @break
                            @case('Admin')
                                <span class="badge bg-primary">Administrateur</span>
                                @break
                            @case('Receptionist')
                                <span class="badge bg-success">Réceptionniste</span>
                                @break
                            @case('Housekeeping')
                                <span class="badge bg-warning">Femme de Chambre</span>
                                @break
                            @case('Customer')
                                <span class="badge bg-info">Client</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ auth()->user()->role }}</span>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

    </div>
</aside>

<!-- STYLES CSS -->
<style>
.sidebar {
    width: 280px;
    background: linear-gradient(180deg, #064e3b 0%, #047857 100%);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    z-index: 1000;
    transition: all 0.3s ease;
    overflow-y: auto;
}

.sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.2rem;
}

.brand-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.brand-text {
    flex: 1;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 700;
    display: block;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 400;
}

#toggle-sidebar {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

#toggle-sidebar:hover {
    background: rgba(255, 255, 255, 0.2);
}

.sidebar-inner {
    padding: 20px 0;
}

.sidebar-header {
    display: none;
    padding: 0 24px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-body {
    padding: 0;
}

.nav-menu {
    padding: 0;
}

.nav-section {
    margin-bottom: 24px;
}

.nav-section-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.5);
    padding: 0 24px 8px;
    margin-bottom: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px 24px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    border-left: 3px solid transparent;
    cursor: pointer;
}

.nav-item:hover {
    color: #fff;
    background: rgba(34, 197, 94, 0.15);
    border-left-color: #22c55e;
}

.nav-item.active {
    color: #fff;
    background: rgba(5, 150, 105, 0.15);
    border-left-color: #16a34a;
}

.nav-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    font-size: 1rem;
}

.nav-item.active .nav-icon {
    background: rgba(5, 150, 105, 0.2);
    color: #16a34a;
}

.nav-title {
    font-size: 0.95rem;
    font-weight: 500;
    color: #fff;
}

.nav-subtitle {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.5);
    margin-top: 2px;
}

.nav-badge {
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    background: #ef4444;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
}

.sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-profile {
    display: flex;
    align-items: center;
}

.user-avatar {
    width: 40px;
    height: 40px;
    margin-right: 12px;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.user-info {
    flex: 1;
}

.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.2;
}

.user-role .badge {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
}

/* Badge colors */
.bg-danger { background: #dc2626; }
.bg-primary { background: #3b82f6; }
.bg-success { background: #10b981; }
.bg-warning { background: #f59e0b; }
.bg-info { background: #06b6d4; }
.bg-secondary { background: #64748b; }

/* Scrollbar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .sidebar-header {
        display: block;
    }
}
</style>

<!-- SCRIPT POUR LA DÉCONNEXION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggle-sidebar');
    const toggleSidebarSm = document.getElementById('toggle-sidebar-sm');
    
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
    
    if (toggleSidebarSm) {
        toggleSidebarSm.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && sidebar && !sidebar.contains(event.target) && 
            toggleSidebarSm && !toggleSidebarSm.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    // Fonction de déconnexion améliorée
    const logoutLink = document.querySelector('a[onclick*="logout-form"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Afficher un message de confirmation
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Soumettre le formulaire
                document.getElementById('logout-form').submit();
                
                // Désactiver le lien pendant 2 secondes pour éviter les doubles clics
                logoutLink.style.opacity = '0.5';
                logoutLink.style.pointerEvents = 'none';
                
                setTimeout(function() {
                    logoutLink.style.opacity = '1';
                    logoutLink.style.pointerEvents = 'auto';
                }, 2000);
            }
        });
    }
});
</script>

<!-- ROUTE D'URGENCE POUR DÉCONNEXION (ajoutez dans routes/web.php) -->
@php
// Route temporaire pour déconnexion d'urgence - À AJOUTER DANS routes/web.php
// Route::get('/logout-emergency', function() {
//     \Illuminate\Support\Facades\Auth::logout();
//     session()->invalidate();
//     session()->regenerateToken();
//     
//     // Efface les cookies
//     $response = redirect('/login')->with('success', 'Déconnexion d\'urgence réussie.');
//     $response->headers->set('Clear-Site-Data', '"cache", "cookies", "storage", "executionContexts"');
//     
//     return $response;
// })->name('logout.emergency');
@endphp