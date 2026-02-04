<!-- Sidebar -->
<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
        <div class="d-flex align-items-center">
            <div class="brand-icon">
                <i class="fas fa-hotel"></i>
            </div>
            <div class="brand-text ms-2">
                <span class="brand-name">Hotel Management</span>
                <small class="brand-subtitle d-block">Gestion Hôtelière</small>
            </div>
        </div>
        <button id="toggle-sidebar" class="btn btn-icon">
            <i class="fas fa-bars"></i>
        </button>
    </a>

    <!-- Sidebar Inner -->
    <div class="sidebar-inner">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <button id="toggle-sidebar-sm" class="btn btn-icon">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Sidebar Body -->
        <div class="sidebar-body">

            <!-- Navigation Menu -->
            <nav class="nav-menu">

                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-section-title">Dashboard</div>
                    
                    @php
                        // Solution robuste pour éviter les erreurs str_starts_with
                        $currentRoute = Route::currentRouteName() ?: '';
                        $activeClass = function($routeName, $exact = true) use ($currentRoute) {
                            if ($exact) {
                                return $currentRoute === $routeName ? 'active' : '';
                            }
                            return str_starts_with($currentRoute, $routeName) ? 'active' : '';
                        };
                    @endphp
                    
                    <a href="{{ route('dashboard.index') }}" 
                       class="nav-item {{ $activeClass('dashboard.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Dashboard</div>
                            <div class="nav-subtitle">Analytique & Vue d'ensemble</div>
                        </div>
                    </a>
                </div>

                <!-- Opérations (Réceptionnistes avec permissions étendues) -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Receptionist')
                <div class="nav-section">
                    <div class="nav-section-title">Opérations</div>

                    <!-- Check-in -->
                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                        @php
                            $isCheckinActive = in_array($currentRoute, [
                                'checkin.index', 
                                'checkin.search', 
                                'checkin.show', 
                                'checkin.direct', 
                                'checkin.process-direct-checkin', 
                                'checkin.quick', 
                                'checkin.availability'
                            ]);
                        @endphp
                        
                        @if(Route::has('checkin.index'))
                            <a href="{{ route('checkin.index') }}"
                               class="nav-item {{ $isCheckinActive ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="nav-content">
                                    <div class="nav-title">Check-in</div>
                                    <div class="nav-subtitle">Enregistrement clients</div>
                                </div>
                            </a>
                        @endif

                        <!-- Disponibilité -->
                        @if(Route::has('availability.dashboard'))
                        <a href="{{ route('availability.dashboard') }}"
                        class="nav-item {{ $activeClass('availability.', false) }}">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Disponibilité</div>
                                <div class="nav-subtitle">Dashboard & Inventaire</div>
                            </div>
                        </a>
                        @endif

                        <!-- Transactions (Réservations & Séjours) -->
                        @if(Route::has('transaction.index'))
                        <a href="{{ route('transaction.index') }}"
                           class="nav-item {{ $activeClass('transaction.', false) && !str_contains($currentRoute, 'transaction.reservation.') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Transactions</div>
                                <div class="nav-subtitle">
                                    @if(auth()->user()->role == 'Receptionist')
                                        <span class="text-success">✓</span> Gestion complète
                                    @else
                                        Réservations & Séjours
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endif

                        <!-- Réservations Rapides -->
                        @if(Route::has('transaction.reservation.createIdentity'))
                        <a href="{{ route('transaction.reservation.createIdentity') }}"
                           class="nav-item {{ $activeClass('transaction.reservation.createIdentity') }}">
                            <div class="nav-icon">
                                <i class="fas fa-plus-circle"></i>
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
                       class="nav-item {{ $activeClass('cashier.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Caisse</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-success">✓</span> Sessions & Transactions
                                @else
                                    Sessions & Transactions
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Restaurant -->
                    @if(Route::has('restaurant.index'))
                    <a href="{{ route('restaurant.index') }}"
                       class="nav-item {{ $activeClass('restaurant.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Restaurant</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-success">✓</span> Menus & Commandes
                                @else
                                    Menus & Commandes
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Gestion (Admin + Réceptionnistes avec permissions complètes) -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Receptionist')
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>

                    <!-- Clients -->
                    @if(Route::has('customer.index'))
                    <a href="{{ route('customer.index') }}"
                       class="nav-item {{ $activeClass('customer.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Clients</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-success">✓</span> Gestion complète
                                @else
                                    Gestion des clients
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Chambres -->
                    @if(Route::has('room.index'))
                    <a href="{{ route('room.index') }}"
                       class="nav-item {{ $activeClass('room.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Chambres</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-success">✓</span> Vue & État
                                @else
                                    Gestion complète
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Types de Chambres (Admin seulement) -->
                    @if(Route::has('type.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <a href="{{ route('type.index') }}"
                       class="nav-item restricted {{ $activeClass('type.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Types de Chambres</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    Lecture seulement
                                @else
                                    Catégories & Tarifs
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Paiements -->
                    @if(Route::has('payments.index'))
                    @php
                        $isPaymentActive = $activeClass('payments.', false) || $activeClass('payment.', false);
                    @endphp
                    <a href="{{ route('payments.index') }}"
                       class="nav-item {{ $isPaymentActive ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Paiements</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-success">✓</span> Transactions & Encaissements
                                @else
                                    Transactions financières
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Réservations Avancées -->
                    @if(Route::has('reservation.index') && auth()->user()->role == 'Receptionist')
                    <a href="{{ route('reservation.index') }}"
                       class="nav-item {{ $activeClass('reservation.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Réservations</div>
                            <div class="nav-subtitle">
                                <span class="text-success">✓</span> Créer, modifier, consulter
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Nettoyage (Housekeeping staff + Vue pour réceptionnistes) -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping', 'Receptionist']))
                <div class="nav-section">
                    <div class="nav-section-title">Nettoyage</div>

                    <!-- Dashboard Housekeeping -->
                    @if(Route::has('housekeeping.dashboard'))
                    <a href="{{ route('housekeeping.dashboard') }}"
                       class="nav-item {{ $activeClass('housekeeping.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Housekeeping</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist')
                                    <span class="text-info">👁️</span> Vue des chambres
                                @else
                                    Nettoyage & Maintenance
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    <!-- Statuts des Chambres (Admin seulement) -->
                    @if(Route::has('roomstatus.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <a href="{{ route('roomstatus.index') }}"
                       class="nav-item {{ $activeClass('roomstatus.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Statuts Chambres</div>
                            <div class="nav-subtitle">États & Couleurs</div>
                        </div>
                    </a>
                    @endif

                    <!-- Équipements (Admin seulement) -->
                    @if(Route::has('facility.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <a href="{{ route('facility.index') }}"
                       class="nav-item {{ $activeClass('facility.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Équipements</div>
                            <div class="nav-subtitle">Services & Commodités</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- Administration (Admin seulement) -->
                @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>

                    <!-- Utilisateurs -->
                    @if(Route::has('user.index') && auth()->user()->role == 'Super')
                    <a href="{{ route('user.index') }}"
                       class="nav-item restricted {{ $activeClass('user.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-user-cog"></i>
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
                       class="nav-item {{ $activeClass('reports.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Rapports</div>
                            <div class="nav-subtitle">Analyses & Statistiques</div>
                        </div>
                    </a>
                    @endif

                    <!-- Journal d'Activité -->
                    @if(Route::has('activity.index'))
                    <a href="{{ route('activity.index') }}"
                       class="nav-item {{ $activeClass('activity.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Journal</div>
                            <div class="nav-subtitle">Activités système</div>
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
                       class="nav-item {{ $activeClass('profile.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-user"></i>
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
                       class="nav-item {{ $activeClass('transaction.myReservations') }}">
                        <div class="nav-icon">
                            <i class="fas fa-book"></i>
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
                       class="nav-item {{ $activeClass('notification.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-bell"></i>
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

                    <!-- Sessions Réceptionniste -->
                    @if(auth()->user()->role == 'Receptionist' && Route::has('receptionist.session.active'))
                    <a href="{{ route('receptionist.session.active') }}"
                       class="nav-item {{ $activeClass('receptionist.session.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Ma Session</div>
                            <div class="nav-subtitle">Suivi d'activité</div>
                        </div>
                    </a>
                    @endif

                    <!-- DÉCONNEXION -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit(); return false;"
                       class="nav-item">
                        <div class="nav-icon">
                            <i class="fas fa-sign-out-alt"></i>
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
                    <!-- Indicateur de session pour réceptionnistes -->
                    @if(auth()->user()->role == 'Receptionist' && auth()->user()->is_active_session)
                    <div class="session-indicator mt-1">
                        <small class="text-success">
                            <i class="fas fa-circle fa-xs"></i> Session active
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Indicateur de permissions pour réceptionnistes -->
            @if(auth()->user()->role == 'Receptionist')
            <div class="permissions-info mt-2 pt-2 border-top border-white-10">
                <small class="text-white-70 d-block">
                    <i class="fas fa-key me-1"></i>
                    <strong>Permissions :</strong> Transactions complètes
                </small>
                <small class="text-white-50 d-block mt-1">
                    <i class="fas fa-info-circle me-1"></i>
                    Accès complet sauf suppression de réservations
                </small>
            </div>
            @endif
            
            <!-- Date et heure en temps réel -->
            <div class="datetime-info mt-2 pt-2 border-top border-white-10">
                <small class="text-white-50 d-block">
                    <i class="far fa-clock me-1"></i>
                    <span id="sidebar-datetime">{{ now()->format('d/m/Y H:i') }}</span>
                </small>
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
    box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
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
    transition: all 0.3s ease;
}

.sidebar-logo:hover {
    background: rgba(255, 255, 255, 0.05);
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.brand-text {
    flex: 1;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 700;
    display: block;
    line-height: 1.2;
    letter-spacing: 0.5px;
}

.brand-subtitle {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 400;
    margin-top: 2px;
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
    transform: rotate(90deg);
}

.sidebar-inner {
    padding: 20px 0;
    height: calc(100vh - 80px);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    display: none;
    padding: 0 24px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-body {
    padding: 0;
    flex: 1;
    overflow-y: auto;
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
    font-weight: 600;
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
    background: transparent;
    border-radius: 0;
}

.nav-item:hover {
    color: #fff;
    background: linear-gradient(90deg, rgba(34, 197, 94, 0.15), transparent);
    border-left-color: #22c55e;
    padding-left: 28px;
}

.nav-item.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(5, 150, 105, 0.25), transparent);
    border-left-color: #16a34a;
    font-weight: 500;
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
    transition: all 0.2s ease;
}

.nav-item.active .nav-icon {
    background: rgba(5, 150, 105, 0.2);
    color: #16a34a;
    transform: scale(1.1);
}

.nav-title {
    font-size: 0.95rem;
    font-weight: 500;
    color: #fff;
    line-height: 1.2;
}

.nav-subtitle {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.5);
    margin-top: 2px;
    line-height: 1.3;
}

.nav-subtitle .text-success {
    color: #10b981 !important;
    font-weight: 600;
    margin-right: 4px;
}

.nav-subtitle .text-info {
    color: #06b6d4 !important;
    margin-right: 4px;
}

.nav-badge {
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
}

.user-profile {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    margin-right: 12px;
    position: relative;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.user-info {
    flex: 1;
}

.user-name {
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.2;
    margin-bottom: 4px;
}

.user-role .badge {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.session-indicator .text-success {
    font-size: 0.7rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 4px;
}

.session-indicator .fa-circle {
    font-size: 0.6rem;
    animation: pulse 2s infinite;
}

.permissions-info {
    font-size: 0.75rem;
}

.permissions-info small {
    display: flex;
    align-items: center;
}

.datetime-info {
    font-size: 0.75rem;
}

#sidebar-datetime {
    font-family: 'Courier New', monospace;
    font-weight: 500;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.bg-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.bg-primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.bg-success { background: linear-gradient(135deg, #10b981, #047857); }
.bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
.bg-info { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.bg-secondary { background: linear-gradient(135deg, #64748b, #475569); }

.nav-item.restricted {
    opacity: 0.9;
    position: relative;
}

.nav-item.restricted::after {
    content: "🔒";
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.8rem;
    opacity: 0.5;
}

.border-white-10 {
    border-color: rgba(255, 255, 255, 0.1) !important;
}

.sidebar-body::-webkit-scrollbar {
    width: 6px;
}

.sidebar-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.sidebar-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.sidebar-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

@media (max-width: 768px) {
    .sidebar {
        width: 280px;
        transform: translateX(-100%);
        box-shadow: 5px 0 30px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .sidebar-header {
        display: flex;
        justify-content: flex-end;
        padding: 20px;
    }
    
    .sidebar-header button {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .sidebar-header button:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .sidebar-logo {
        padding: 15px 20px;
    }
    
    .nav-item {
        padding: 12px 20px;
    }
    
    .sidebar-footer {
        padding: 15px 20px;
    }
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-content,
.sidebar.collapsed .user-info,
.sidebar.collapsed .permissions-info,
.sidebar.collapsed .datetime-info,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .nav-badge,
.sidebar.collapsed #toggle-sidebar i {
    display: none;
}

.sidebar.collapsed .brand-icon {
    margin: 0 auto;
}

.sidebar.collapsed .sidebar-logo {
    justify-content: center;
    padding: 20px 10px;
}

.sidebar.collapsed .nav-item {
    justify-content: center;
    padding: 15px 10px;
}

.sidebar.collapsed .nav-icon {
    margin-right: 0;
    width: 40px;
    height: 40px;
}

.sidebar.collapsed .user-profile {
    justify-content: center;
}

.sidebar.collapsed .user-avatar {
    margin-right: 0;
}
</style>

<!-- SCRIPT POUR LA SIDEBAR -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggle-sidebar');
    const toggleSidebarSm = document.getElementById('toggle-sidebar-sm');
    
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            
            const icon = toggleSidebar.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-bars';
            }
        });
    }
    
    if (toggleSidebarSm) {
        toggleSidebarSm.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        if (toggleSidebar) {
            const icon = toggleSidebar.querySelector('i');
            icon.className = 'fas fa-chevron-right';
        }
    }
    
    function updateDateTime() {
        const now = new Date();
        const datetimeElement = document.getElementById('sidebar-datetime');
        if (datetimeElement) {
            datetimeElement.textContent = now.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
    
    updateDateTime();
    setInterval(updateDateTime, 60000);
    
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && sidebar && !sidebar.contains(event.target) && 
            toggleSidebarSm && !toggleSidebarSm.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    const logoutLink = document.querySelector('a[onclick*="logout-form"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    if (window.innerWidth < 768) {
        const navLinks = document.querySelectorAll('.nav-item[href]');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                setTimeout(() => {
                    sidebar.classList.remove('show');
                }, 300);
            });
        });
    }
});
</script>