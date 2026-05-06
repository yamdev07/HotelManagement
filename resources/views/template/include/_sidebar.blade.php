<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
        <div class="d-flex align-items-center">
            <div>
                <img src="{{ asset('img/logo_cactus1.jpeg') }}" alt="Hotel Cactus"
                    style="height: 38px; border-radius: 8px; flex-shrink:0;">
            </div>
            <div class="brand-text ms-2">
                <span class="brand-name">Hotel Management</span>
                <small class="brand-subtitle d-block">Gestion Hôtelière</small>
            </div>
        </div>
        <button id="toggle-sidebar" class="btn-icon-toggle" title="Réduire">
            <i class="fas fa-bars"></i>
        </button>
    </a>

    <!-- Sidebar Inner -->
    <div class="sidebar-inner">

        <!-- Sidebar Header (mobile only) -->
        <div class="sidebar-header-mobile">
            <span class="header-title-mobile"><i class="fas fa-bars me-2"></i>Menu</span>
            <button id="toggle-sidebar-sm" class="btn-icon-toggle">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Sidebar Body -->
        <div class="sidebar-body">
            <nav class="nav-menu">

                @php
                    $currentRoute = Route::currentRouteName() ?: '';
                    $activeClass = function ($routeName, $exact = true) use ($currentRoute) {
                        if ($exact) {
                            return $currentRoute === $routeName ? 'active' : '';
                        }
                        return str_starts_with($currentRoute, $routeName) ? 'active' : '';
                    };
                    $hasActiveSession = isset($activeSession) && $activeSession;
                @endphp

                <!-- TABLEAU DE BORD -->
                @if (!in_array(auth()->user()->role, ['Customer', 'Servant']))
                <div class="nav-section">
                    <div class="nav-section-title">Tableau de Bord</div>

                    <a href="{{ route('dashboard.index') }}" class="nav-item {{ $activeClass('dashboard.index') }}"
                        data-tooltip="Dashboard">
                        <div class="nav-icon"><i class="fas fa-chart-pie"></i></div>
                        <div class="nav-content">
                            <div class="nav-title">Dashboard</div>
                            <div class="nav-subtitle">Vue d'ensemble</div>
                        </div>
                    </a>

                    @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                        @if (Route::has('availability.dashboard'))
                            <a href="{{ route('availability.dashboard') }}"
                                class="nav-item {{ $activeClass('availability.', false) }}"
                                data-tooltip="Disponibilité">
                                <div class="nav-icon"><i class="fas fa-th-large"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Disponibilité</div>
                                    <div class="nav-subtitle">Inventaire en temps réel</div>
                                </div>
                            </a>
                        @endif
                    @endif
                </div>
                @endif

                <!-- ACTIONS RAPIDES -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                    <div class="nav-section">
                        <div class="nav-section-title">Actions Rapides</div>

                        @php
                            $isCheckinActive = in_array($currentRoute, [
                                'checkin.index',
                                'checkin.search',
                                'checkin.show',
                                'checkin.direct',
                                'checkin.process-direct-checkin',
                                'checkin.quick',
                                'checkin.availability',
                            ]);
                        @endphp

                        @if (Route::has('checkin.index'))
                            <a href="{{ route('checkin.index') }}"
                                class="nav-item nav-item--highlight {{ $isCheckinActive ? 'active' : '' }}"
                                data-tooltip="Check-in/out">
                                <div class="nav-icon"><i class="fas fa-door-open"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Check-in/Check-out</div>
                                    <div class="nav-subtitle">Enregistrement clients</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('transaction.reservation.createIdentity'))
                            <a href="{{ route('transaction.reservation.createIdentity') }}"
                                class="nav-item nav-item--highlight {{ $activeClass('transaction.reservation.createIdentity') }}"
                                data-tooltip="Nouvelle Réservation">
                                <div class="nav-icon"><i class="fas fa-calendar-plus"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Nouvelle Réservation</div>
                                    <div class="nav-subtitle">Créer rapidement</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- OPÉRATIONS -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist', 'Servant']))
                    <div class="nav-section">
                        <div class="nav-section-title">Opérations</div>

                        @if (Route::has('transaction.index') && !auth()->user()->isServant())
                            <a href="{{ route('transaction.index') }}"
                                class="nav-item {{ $activeClass('transaction.', false) && !str_contains($currentRoute, 'transaction.reservation.') ? 'active' : '' }}"
                                data-tooltip="Liste Clients">
                                <div class="nav-icon"><i class="fas fa-shopping-bag"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Liste des Clients</div>
                                    <div class="nav-subtitle">Réservations & Séjours</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('cashier.dashboard'))
                            <a href="{{ route('cashier.dashboard') }}"
                                class="nav-item {{ $activeClass('cashier.', false) }}" data-tooltip="Caisse">
                                <div class="nav-icon"><i class="fas fa-cash-register"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Caisse</div>
                                    <div class="nav-subtitle">Sessions & Encaissements</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('restaurant.index'))
                            @php $pendingOrdersCount = \App\Models\RestaurantOrder::where('status', 'pending')->count(); @endphp
                            <a href="{{ route('restaurant.index') }}"
                                class="nav-item {{ $activeClass('restaurant.', false) }}" data-tooltip="Restaurant">
                                <div class="nav-icon"><i class="fas fa-utensils"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Restaurant</div>
                                    <div class="nav-subtitle">
                                        {{ $pendingOrdersCount }} commande{{ $pendingOrdersCount > 1 ? 's' : '' }} en attente
                                    </div>
                                </div>
                                @if ($pendingOrdersCount > 0)
                                    <span class="nav-badge">{{ $pendingOrdersCount }}</span>
                                @endif
                            </a>
                        @endif


                    </div>
                @endif

                <!-- GESTION -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                    <div class="nav-section">
                        <div class="nav-section-title">Gestion</div>

                        @if (Route::has('customer.index'))
                            <a href="{{ route('customer.index') }}"
                                class="nav-item {{ $activeClass('customer.index') }}" data-tooltip="Clients">
                                <div class="nav-icon"><i class="fas fa-users"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Clients</div>
                                    <div class="nav-subtitle">Gestion des clients</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('room.index'))
                            <a href="{{ route('room.index') }}" class="nav-item {{ $activeClass('room.index') }}"
                                data-tooltip="Chambres">
                                <div class="nav-icon"><i class="fas fa-bed"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Chambres</div>
                                    <div class="nav-subtitle">
                                        @if (auth()->user()->role == 'Receptionist')
                                            Vue & État
                                        @else
                                            Gestion complète
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('type.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                            <a href="{{ route('type.index') }}"
                                class="nav-item restricted {{ $activeClass('type.index') }}"
                                data-tooltip="Types Chambres">
                                <div class="nav-icon"><i class="fas fa-layer-group"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Types de Chambres</div>
                                    <div class="nav-subtitle">Catégories & Tarifs</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('payments.index'))
                            @php $isPaymentActive = $activeClass('payments.', false) || $activeClass('payment.', false); @endphp
                            <a href="{{ route('payments.index') }}"
                                class="nav-item {{ $isPaymentActive ? 'active' : '' }}" data-tooltip="Paiements">
                                <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Paiements</div>
                                    <div class="nav-subtitle">Transactions financières</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- NETTOYAGE -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping', 'Receptionist']))
                    <div class="nav-section">
                        <div class="nav-section-title">Nettoyage</div>

                        @if (Route::has('housekeeping.dashboard'))
                            <a href="{{ route('housekeeping.dashboard') }}"
                                class="nav-item {{ $activeClass('housekeeping.', false) }}"
                                data-tooltip="Housekeeping">
                                <div class="nav-icon"><i class="fas fa-broom"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Housekeeping</div>
                                    <div class="nav-subtitle">Nettoyage & Maintenance</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('checkin.index') && in_array(auth()->user()->role, ['Housekeeping']))
                            @php
                                $isCheckinActive = in_array($currentRoute, [
                                    'checkin.index',
                                    'checkin.search',
                                    'checkin.show',
                                    'checkin.direct',
                                    'checkin.process-direct-checkin',
                                    'checkin.quick',
                                    'checkin.availability',
                                ]);
                            @endphp
                            <a href="{{ route('checkin.index') }}"
                                class="nav-item nav-item--readonly {{ $isCheckinActive ? 'active' : '' }}"
                                title="Mode lecture seule" data-tooltip="Check-in (lecture)">
                                <div class="nav-icon"><i class="fas fa-door-open"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Check-in/Check-out</div>
                                    <div class="nav-subtitle">Visualisation</div>
                                </div>
                                <span class="readonly-tag">👁️</span>
                            </a>
                        @endif

                        @if (Route::has('roomstatus.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                            <a href="{{ route('roomstatus.index') }}"
                                class="nav-item {{ $activeClass('roomstatus.index') }}" data-tooltip="Statuts">
                                <div class="nav-icon"><i class="fas fa-flag"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Statuts Chambres</div>
                                    <div class="nav-subtitle">États & Couleurs</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('facility.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                            <a href="{{ route('facility.index') }}"
                                class="nav-item {{ $activeClass('facility.index') }}" data-tooltip="Équipements">
                                <div class="nav-icon"><i class="fas fa-tools"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Équipements</div>
                                    <div class="nav-subtitle">Services & Commodités</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- ADMINISTRATION -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin']))
                    <div class="nav-section">
                        <div class="nav-section-title">Administration</div>

                        @if (Route::has('user.index') && auth()->user()->role == 'Super')
                            <a href="{{ route('user.index') }}"
                                class="nav-item restricted {{ $activeClass('user.index') }}"
                                data-tooltip="Utilisateurs">
                                <div class="nav-icon"><i class="fas fa-user-cog"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Utilisateurs</div>
                                    <div class="nav-subtitle">Gestion des comptes</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('reports.index'))
                            <a href="{{ route('reports.index') }}"
                                class="nav-item {{ $activeClass('reports.index') }}" data-tooltip="Rapports">
                                <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Rapports</div>
                                    <div class="nav-subtitle">Analyses & Statistiques</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('activity.index'))
                            <a href="{{ route('activity.index') }}"
                                class="nav-item {{ $activeClass('activity.index') }}" data-tooltip="Journal">
                                <div class="nav-icon"><i class="fas fa-history"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">Journal</div>
                                    <div class="nav-subtitle">Activités système</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- MON COMPTE -->
                <div class="nav-section">
                    <div class="nav-section-title">Mon Compte</div>

                    @if (Route::has('profile.index'))
                        <a href="{{ route('profile.index') }}"
                            class="nav-item {{ $activeClass('profile.', false) }}" data-tooltip="Profil">
                            <div class="nav-icon"><i class="fas fa-user"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">Profil</div>
                                <div class="nav-subtitle">Mes informations</div>
                            </div>
                        </a>
                    @endif

                    @if (auth()->user()->role == 'Customer' && Route::has('transaction.myReservations'))
                        <a href="{{ route('transaction.myReservations') }}"
                            class="nav-item {{ $activeClass('transaction.myReservations') }}"
                            data-tooltip="Mes Réservations">
                            <div class="nav-icon"><i class="fas fa-book"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">Mes Réservations</div>
                                <div class="nav-subtitle">Historique</div>
                            </div>
                        </a>
                    @endif

                    @if (Route::has('notification.index'))
                        <a href="{{ route('notification.index') }}"
                            class="nav-item {{ $activeClass('notification.index') }}" data-tooltip="Notifications">
                            <div class="nav-icon">
                                <i class="fas fa-bell"></i>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="nav-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Notifications</div>
                                <div class="nav-subtitle">Alertes & Messages</div>
                            </div>
                        </a>
                    @endif

                    @if (auth()->user()->role == 'Receptionist' && Route::has('receptionist.session.active'))
                        <a href="{{ route('receptionist.session.active') }}"
                            class="nav-item {{ $activeClass('receptionist.session.', false) }}"
                            data-tooltip="Ma Session">
                            <div class="nav-icon"><i class="fas fa-user-clock"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">Ma Session</div>
                                <div class="nav-subtitle">Suivi d'activité</div>
                            </div>
                        </a>
                    @endif

                    <!-- DÉCONNEXION -->
                    @if ($hasActiveSession)
                        <div class="nav-item nav-item--logout"
                            onclick="if(typeof Swal!=='undefined'){Swal.fire({title:'⚠️ Session Active',html:'Vous avez une session active <strong>#{{ $activeSession->id }}</strong>.<br><br>Veuillez la clôturer avant de vous déconnecter.',icon:'warning',confirmButtonColor:'#1e6b2e',confirmButtonText:'Compris',showCancelButton:true,cancelButtonText:'Aller à la session',cancelButtonColor:'#545954'}).then(r=>{if(r.dismiss===Swal.DismissReason.cancel)window.location.href='{{ route('cashier.sessions.show', $activeSession) }}';});}else{alert('Session active. Clôturez d\'abord.');}"
                            style="cursor:pointer;opacity:.7" data-tooltip="Déconnexion bloquée">
                            <div class="nav-icon"><i class="fas fa-sign-out-alt" style="color:#fca5a5"></i></div>
                            <div class="nav-content">
                                <div class="nav-title" style="color:#fca5a5">Déconnexion (bloquée)</div>
                                <div class="nav-subtitle">Session #{{ $activeSession->id }} active</div>
                            </div>
                        </div>
                    @else
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                            @csrf</form>
                        <a href="#" class="nav-item nav-item--logout"
                            onclick="event.preventDefault();if(typeof Swal!=='undefined'){Swal.fire({title:'Déconnexion',text:'Voulez-vous vraiment vous déconnecter ?',icon:'question',showCancelButton:true,confirmButtonColor:'#1e6b2e',cancelButtonColor:'#545954',confirmButtonText:'Oui, déconnecter',cancelButtonText:'Annuler'}).then(r=>{if(r.isConfirmed)document.getElementById('logout-form').submit();});}else{if(confirm('Déconnecter ?'))document.getElementById('logout-form').submit();}return false;"
                            data-tooltip="Déconnexion">
                            <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">Déconnexion</div>
                                <div class="nav-subtitle">Quitter la session</div>
                            </div>
                        </a>
                    @endif
                </div>

            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    @php
                        $avatarPath = null;
                        if (auth()->user()->avatar) {
                            if (str_starts_with(auth()->user()->avatar, '/img/user/')) {
                                $avatarPath = asset(auth()->user()->avatar);
                            } elseif (
                                str_starts_with(auth()->user()->avatar, 'storage/') ||
                                str_contains(auth()->user()->avatar, 'storage/')
                            ) {
                                $avatarPath = asset(auth()->user()->avatar);
                            } else {
                                $avatarPath = asset('storage/' . auth()->user()->avatar);
                            }
                        }
                    @endphp
                    @if ($avatarPath)
                        <img src="{{ $avatarPath }}" alt="{{ auth()->user()->name }}"
                            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1e6b2e&color=fff&size=40';">
                    @else
                        <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role-badge">
                        @switch(auth()->user()->role)
                            @case('Super')
                                <span class="role-pill role-super">Super Admin</span>
                            @break

                            @case('Admin')
                                <span class="role-pill role-admin">Administrateur</span>
                            @break

                            @case('Receptionist')
                                <span class="role-pill role-recep">Réceptionniste</span>
                            @break

                            @case('Housekeeping')
                                <span class="role-pill role-house">Femme de Chambre</span>
                            @break

                            @case('Servant')
                                <span class="role-pill role-recep">Serveur</span>
                            @break

                            @case('Customer')
                                <span class="role-pill role-cust">Client</span>
                            @break

                            @default
                                <span class="role-pill role-other">{{ auth()->user()->role }}</span>
                        @endswitch
                    </div>
                    @if ($hasActiveSession)
                        <div class="session-dot">
                            <span class="dot-live"></span>
                            <small>Session #{{ $activeSession->id }}</small>
                        </div>
                    @endif
                </div>
            </div>
            <div class="sidebar-time">
                <i class="far fa-clock"></i>
                <span id="sidebar-datetime">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>

    </div>
</aside>

<style>
    /* ════════════════════════════════════════
   SIDEBAR — BASE
════════════════════════════════════════ */
    .sidebar {
        width: 272px;
        background: linear-gradient(170deg, #064e3b 0%, #065f46 55%, #047857 100%);
        color: #fff;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        z-index: 1001;
        transition: width .3s cubic-bezier(.4, 0, .2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 24px rgba(0, 0, 0, .18);
        overflow: hidden;
    }

    /* ════════════════════════════════════════
   LOGO
════════════════════════════════════════ */
    .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, .09);
        color: #fff;
        text-decoration: none;
        flex-shrink: 0;
        background: rgba(0, 0, 0, .08);
        transition: background .2s;
        min-height: 64px;
    }

    .sidebar-logo:hover {
        background: rgba(0, 0, 0, .14);
        text-decoration: none;
        color: #fff;
    }

    .brand-text {
        flex: 1;
        min-width: 0;
    }

    .brand-name {
        font-size: .92rem;
        font-weight: 700;
        display: block;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .brand-subtitle {
        font-size: .68rem;
        color: rgba(255, 255, 255, .55);
        margin-top: 2px;
    }

    /* ════════════════════════════════════════
   TOGGLE BUTTONS
════════════════════════════════════════ */
    .btn-icon-toggle {
        background: rgba(255, 255, 255, .1);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: background .2s;
        font-size: .85rem;
    }

    .btn-icon-toggle:hover {
        background: rgba(255, 255, 255, .2);
    }

    /* ════════════════════════════════════════
   INNER LAYOUT
════════════════════════════════════════ */
    .sidebar-inner {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        background: inherit;
    }

    .sidebar-header-mobile {
        display: none;
    }

    .sidebar-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 10px 0 8px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, .15) transparent;
    }

    .sidebar-body::-webkit-scrollbar {
        width: 3px;
    }

    .sidebar-body::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .15);
        border-radius: 2px;
    }

    /* ════════════════════════════════════════
   NAV SECTIONS
════════════════════════════════════════ */
    .nav-menu {
        padding: 0;
    }

    .nav-section {
        margin-bottom: 2px;
    }

    .nav-section-title {
        font-size: .62rem;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: rgba(255, 255, 255, .35);
        padding: 10px 18px 4px;
        font-weight: 700;
    }

    /* ════════════════════════════════════════
   NAV ITEMS
════════════════════════════════════════ */
    .nav-item {
        display: flex;
        align-items: center;
        padding: 9px 18px;
        color: rgba(255, 255, 255, .78);
        text-decoration: none;
        transition: background .18s, border-left-color .18s, padding-left .18s, color .18s;
        border-left: 3px solid transparent;
        cursor: pointer;
        margin: 1px 0;
        position: relative;
    }

    .nav-item:hover {
        color: #fff;
        background: linear-gradient(90deg, rgba(52, 211, 153, .15), rgba(52, 211, 153, .02));
        border-left-color: rgba(52, 211, 153, .6);
        padding-left: 22px;
        text-decoration: none;
    }

    .nav-item.active {
        color: #fff;
        background: linear-gradient(90deg, rgba(16, 185, 129, .22), rgba(16, 185, 129, .04));
        border-left-color: #10b981;
        font-weight: 500;
    }

    .nav-item--highlight .nav-icon {
        background: rgba(16, 185, 129, .18);
        color: #6ee7b7;
    }

    .nav-item--highlight .nav-title {
        font-weight: 600;
    }

    .nav-item--highlight:hover .nav-icon,
    .nav-item--highlight.active .nav-icon {
        background: rgba(16, 185, 129, .28);
        color: #34d399;
        transform: scale(1.08);
    }

    .nav-item--logout:hover {
        background: linear-gradient(90deg, rgba(239, 68, 68, .14), transparent);
        border-left-color: rgba(239, 68, 68, .4);
        color: #fca5a5;
    }

    .nav-item--readonly .nav-icon {
        background: rgba(255, 255, 255, .06);
        color: rgba(255, 255, 255, .5);
    }

    .nav-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 11px;
        background: rgba(255, 255, 255, .07);
        border-radius: 8px;
        font-size: .85rem;
        flex-shrink: 0;
        transition: background .18s, color .18s, transform .18s;
    }

    .nav-item.active .nav-icon {
        background: rgba(16, 185, 129, .22);
        color: #34d399;
        transform: scale(1.05);
    }

    .nav-content {
        min-width: 0;
        flex: 1;
    }

    .nav-title {
        font-size: .84rem;
        font-weight: 500;
        color: inherit;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-subtitle {
        font-size: .68rem;
        color: rgba(255, 255, 255, .42);
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-badge {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: #ef4444;
        color: white;
        font-size: .6rem;
        padding: 2px 5px;
        border-radius: 10px;
        min-width: 16px;
        text-align: center;
        font-weight: 700;
    }

    .nav-item.restricted::after {
        content: "🔒";
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-size: .68rem;
        opacity: .35;
    }

    .readonly-tag {
        font-size: .65rem;
        margin-left: auto;
        opacity: .5;
    }

    /* ════════════════════════════════════════
   FOOTER
════════════════════════════════════════ */
    .sidebar-footer {
        padding: 14px 18px;
        border-top: 1px solid rgba(255, 255, 255, .09);
        background: rgba(0, 0, 0, .14);
        flex-shrink: 0;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, .15);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #047857);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: .95rem;
        border: 2px solid rgba(255, 255, 255, .15);
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        color: #fff;
        font-weight: 600;
        font-size: .82rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }

    .role-pill {
        font-size: .6rem;
        padding: 2px 7px;
        border-radius: 4px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        color: white;
        display: inline-block;
    }

    .role-super {
        background: rgba(220, 38, 38, .75);
    }

    .role-admin {
        background: rgba(37, 99, 235, .75);
    }

    .role-recep {
        background: rgba(16, 185, 129, .75);
    }

    .role-house {
        background: rgba(245, 158, 11, .75);
    }

    .role-cust {
        background: rgba(6, 182, 212, .75);
    }

    .role-other {
        background: rgba(100, 116, 139, .75);
    }

    .session-dot {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 3px;
    }

    .session-dot small {
        font-size: .62rem;
        color: rgba(255, 255, 255, .5);
    }

    .dot-live {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #34d399;
        display: inline-block;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            box-shadow: 0 0 0 0 rgba(52, 211, 153, .5);
        }

        50% {
            opacity: .6;
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0);
        }
    }

    .sidebar-time {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, .08);
        font-size: .68rem;
        color: rgba(255, 255, 255, .38);
    }

    #sidebar-datetime {
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }

    /* ════════════════════════════════════════
   COLLAPSED (desktop) — réduit à 64px
════════════════════════════════════════ */
    .sidebar.collapsed {
        width: 64px;
    }

    .sidebar.collapsed .brand-text,
    .sidebar.collapsed .nav-content,
    .sidebar.collapsed .nav-section-title,
    .sidebar.collapsed .nav-badge,
    .sidebar.collapsed .user-info,
    .sidebar.collapsed .sidebar-time,
    .sidebar.collapsed .restricted::after,
    .sidebar.collapsed .readonly-tag {
        display: none;
    }

    .sidebar.collapsed .sidebar-logo {
        justify-content: center;
        padding: 16px 8px;
    }

    .sidebar.collapsed .nav-item {
        justify-content: center;
        padding: 10px 0;
        border-left-width: 2px;
    }

    .sidebar.collapsed .nav-item:hover {
        padding-left: 0;
    }

    .sidebar.collapsed .nav-icon {
        margin-right: 0;
        width: 36px;
        height: 36px;
    }

    .sidebar.collapsed .user-profile {
        justify-content: center;
    }

    .sidebar.collapsed .user-avatar {
        margin: 0;
    }

    .sidebar.collapsed .sidebar-footer {
        padding: 12px 8px;
    }

    /* Tooltip quand collapsed */
    .sidebar.collapsed .nav-item::before {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%);
        background: rgba(6, 78, 59, .97);
        color: #fff;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: .78rem;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity .15s;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .3);
    }

    .sidebar.collapsed .nav-item:hover::before {
        opacity: 1;
    }

    /* ════════════════════════════════════════
   OVERLAY MOBILE
════════════════════════════════════════ */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .6);
        z-index: 1000;
        opacity: 0;
        transition: opacity .3s;
        backdrop-filter: blur(3px);
    }

    .sidebar-overlay.show {
        display: block;
        opacity: 1;
    }

    /* ════════════════════════════════════════
   MOBILE ≤ 768px
════════════════════════════════════════ */
    @media (max-width: 768px) {
        .sidebar {
            width: 82vw;
            max-width: 300px;
            transform: translateX(-100%);
            transition: transform .28s cubic-bezier(.4, 0, .2, 1);
            height: 100vh;
            z-index: 1050;
            background: linear-gradient(170deg, #064e3b 0%, #065f46 55%, #047857 100%) !important;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        /* Annuler collapsed sur mobile */
        .sidebar.collapsed {
            width: 82vw;
            max-width: 300px;
        }

        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .nav-content,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .sidebar-time {
            display: block !important;
        }

        .sidebar.collapsed .nav-item {
            justify-content: flex-start;
            padding: 9px 18px;
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 11px;
            width: 32px;
            height: 32px;
        }

        .sidebar.collapsed .user-profile {
            justify-content: flex-start;
        }

        .sidebar.collapsed .sidebar-logo {
            justify-content: space-between;
            padding: 16px 18px;
        }

        #toggle-sidebar {
            display: none !important;
        }

        .sidebar-header-mobile {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            background: rgba(0, 0, 0, .12);
            flex-shrink: 0;
        }

        .header-title-mobile {
            color: white;
            font-size: .88rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-body {
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .sidebar-footer {
            flex-shrink: 0;
        }
    }

    @media (max-width: 380px) {
        .sidebar {
            width: 92vw;
            max-width: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        var toggleDesktop = document.getElementById('toggle-sidebar');
        var toggleMobile = document.getElementById('toggle-sidebar-sm');
        var content = document.getElementById('page-content-wrapper');

        /* ── data-tooltip auto ── */
        document.querySelectorAll('.nav-item').forEach(function(item) {
            var title = item.querySelector('.nav-title');
            if (title && !item.getAttribute('data-tooltip')) {
                item.setAttribute('data-tooltip', title.textContent.trim());
            }
        });

        /* ════════════════════════════════════════
           DESKTOP : collapse / expand
           Met aussi à jour le margin-left du contenu
        ════════════════════════════════════════ */
        function setCollapsed(collapsed) {
            if (window.innerWidth <= 768) return;

            sidebar.classList.toggle('collapsed', collapsed);
            localStorage.setItem('sidebarCollapsed', collapsed);

            /* ← CORRECTION PRINCIPALE : on met à jour le contenu */
            if (content) {
                content.style.marginLeft = collapsed ? '64px' : '272px';
            }

            if (toggleDesktop) {
                toggleDesktop.querySelector('i').className = collapsed ?
                    'fas fa-chevron-right' :
                    'fas fa-bars';
            }
        }

        if (toggleDesktop) {
            toggleDesktop.addEventListener('click', function(e) {
                e.preventDefault();
                setCollapsed(!sidebar.classList.contains('collapsed'));
            });
        }

        /* Restaurer l'état au chargement */
        if (window.innerWidth > 768) {
            var saved = localStorage.getItem('sidebarCollapsed') === 'true';
            setCollapsed(saved);
        }

        /* ════════════════════════════════════════
           MOBILE : open / close (overlay)
        ════════════════════════════════════════ */
        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        function toggleMobileSidebar() {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        }

        if (toggleMobile) {
            toggleMobile.addEventListener('click', function(e) {
                e.stopPropagation();
                closeSidebar();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        /* Fermer sidebar mobile après clic sur lien */
        document.querySelectorAll('.nav-item').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) setTimeout(closeSidebar, 150);
            });
        });

        /* Exposer globalement pour _mobile-header.blade.php */
        window.openSidebar = openSidebar;
        window.closeSidebar = closeSidebar;
        window.toggleMobileSidebar = toggleMobileSidebar;

        /* ════════════════════════════════════════
           RESIZE
        ════════════════════════════════════════ */
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
                var saved = localStorage.getItem('sidebarCollapsed') === 'true';
                setCollapsed(saved);
            } else {
                /* Mobile : contenu pleine largeur */
                if (content) content.style.marginLeft = '0';
            }
        });

        /* ════════════════════════════════════════
           HORLOGE
        ════════════════════════════════════════ */
        function updateClock() {
            var el = document.getElementById('sidebar-datetime');
            if (!el) return;
            el.textContent = new Date().toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        updateClock();
        setInterval(updateClock, 30000);
    });
</script>
