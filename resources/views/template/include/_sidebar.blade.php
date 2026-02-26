<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
        <div class="d-flex align-items-center">
            <div class="">
                <img src="{{ asset('img/logo_cactus1.jpeg') }}"
                    alt="Hotel Cactus"
                    style="height: 42px; border-radius: 8px;">
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

        <!-- Sidebar Header (mobile) -->
        <div class="sidebar-header">
            <span class="header-title">
                <i class="fas fa-bars me-2"></i>Menu
            </span>
            <button id="toggle-sidebar-sm" class="btn btn-icon">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Sidebar Body -->
        <div class="sidebar-body">
            <nav class="nav-menu">

                @php
                    $currentRoute = Route::currentRouteName() ?: '';
                    $activeClass = function($routeName, $exact = true) use ($currentRoute) {
                        if ($exact) {
                            return $currentRoute === $routeName ? 'active' : '';
                        }
                        return str_starts_with($currentRoute, $routeName) ? 'active' : '';
                    };
                    
                    // Vérifier si l'utilisateur a une session active
                    $hasActiveSession = isset($activeSession) && $activeSession;
                @endphp

                <!-- ═══════════════════════════════════════
                     TABLEAU DE BORD
                     ═══════════════════════════════════════ -->
                <div class="nav-section">
                    <div class="nav-section-title">Tableau de Bord</div>

                    <a href="{{ route('dashboard.index') }}"
                       class="nav-item {{ $activeClass('dashboard.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Dashboard</div>
                            <div class="nav-subtitle">Vue d'ensemble</div>
                        </div>
                    </a>

                    @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                        @if(Route::has('availability.dashboard'))
                        <a href="{{ route('availability.dashboard') }}"
                           class="nav-item {{ $activeClass('availability.', false) }}">
                            <div class="nav-icon">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">Disponibilité</div>
                                <div class="nav-subtitle">Inventaire en temps réel</div>
                            </div>
                        </a>
                        @endif
                    @endif
                </div>
                <!-- ═══════════════════════════════════════
                     ACTIONS RAPIDES — Check-in & Réservation
                     ═══════════════════════════════════════ -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                <div class="nav-section">
                    <div class="nav-section-title">Actions Rapides</div>

                    <!-- Check-in -->
                    @php
                        $isCheckinActive = in_array($currentRoute, [
                            'checkin.index', 'checkin.search', 'checkin.show',
                            'checkin.direct', 'checkin.process-direct-checkin',
                            'checkin.quick', 'checkin.availability'
                        ]);
                    @endphp

                    @if(Route::has('checkin.index'))
                    <a href="{{ route('checkin.index') }}"
                       class="nav-item nav-item--highlight {{ $isCheckinActive ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Check-in/Check-out</div>
                            <div class="nav-subtitle">Enregistrement clients</div>
                        </div>
                    </a>
                    @endif

                    <!-- Nouvelle Réservation -->
                    @if(Route::has('transaction.reservation.createIdentity'))
                    <a href="{{ route('transaction.reservation.createIdentity') }}"
                       class="nav-item nav-item--highlight {{ $activeClass('transaction.reservation.createIdentity') }}">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Nouvelle Réservation</div>
                            <div class="nav-subtitle">Créer rapidement</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif


                <!-- ═══════════════════════════════════════
                     OPÉRATIONS
                     ═══════════════════════════════════════ -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                <div class="nav-section">
                    <div class="nav-section-title">Opérations</div>

                    @if(Route::has('transaction.index'))
                    <a href="{{ route('transaction.index') }}"
                       class="nav-item {{ ($activeClass('transaction.', false) && !str_contains($currentRoute, 'transaction.reservation.')) ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Liste des Clients</div>
                            <div class="nav-subtitle">Réservations & Séjours</div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('cashier.dashboard'))
                    <a href="{{ route('cashier.dashboard') }}"
                       class="nav-item {{ $activeClass('cashier.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Caisse</div>
                            <div class="nav-subtitle">Sessions & Encaissements</div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('restaurant.index'))
                    <a href="{{ route('restaurant.index') }}"
                       class="nav-item {{ $activeClass('restaurant.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Restaurant</div>
                            <div class="nav-subtitle">Menus & Commandes</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- ═══════════════════════════════════════
                     GESTION
                     ═══════════════════════════════════════ -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Receptionist']))
                <div class="nav-section">
                    <div class="nav-section-title">Gestion</div>

                    @if(Route::has('customer.index'))
                    <a href="{{ route('customer.index') }}"
                       class="nav-item {{ $activeClass('customer.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Clients</div>
                            <div class="nav-subtitle">Gestion des clients</div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('room.index'))
                    <a href="{{ route('room.index') }}"
                       class="nav-item {{ $activeClass('room.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Chambres</div>
                            <div class="nav-subtitle">
                                @if(auth()->user()->role == 'Receptionist') Vue & État @else Gestion complète @endif
                            </div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('type.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                    <a href="{{ route('type.index') }}"
                       class="nav-item restricted {{ $activeClass('type.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Types de Chambres</div>
                            <div class="nav-subtitle">Catégories & Tarifs</div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('payments.index'))
                    @php $isPaymentActive = $activeClass('payments.', false) || $activeClass('payment.', false); @endphp
                    <a href="{{ route('payments.index') }}"
                       class="nav-item {{ $isPaymentActive ? 'active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Paiements</div>
                            <div class="nav-subtitle">Transactions financières</div>
                        </div>
                    </a>
                    @endif

                    @if(Route::has('reservation.index') && auth()->user()->role == 'Receptionist')
                    <a href="{{ route('reservation.index') }}"
                       class="nav-item {{ $activeClass('reservation.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Réservations</div>
                            <div class="nav-subtitle">Créer, modifier, consulter</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif

                <!-- ═══════════════════════════════════════
                     NETTOYAGE
                     ═══════════════════════════════════════ -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin', 'Housekeeping', 'Receptionist']))
                <div class="nav-section">
                    <div class="nav-section-title">Nettoyage</div>

                    @if(Route::has('housekeeping.dashboard'))
                    <a href="{{ route('housekeeping.dashboard') }}"
                       class="nav-item {{ $activeClass('housekeeping.', false) }}">
                        <div class="nav-icon">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div class="nav-content">
                            <div class="nav-title">Housekeeping</div>
                            <div class="nav-subtitle">Nettoyage & Maintenance</div>
                        </div>
                    </a>
                    @endif

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

                <!-- ═══════════════════════════════════════
                     ADMINISTRATION
                     ═══════════════════════════════════════ -->
                @if(in_array(auth()->user()->role, ['Super', 'Admin']))
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>

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

                <!-- ═══════════════════════════════════════
                     MON COMPTE
                     ═══════════════════════════════════════ -->
                <div class="nav-section">
                    <div class="nav-section-title">Mon Compte</div>

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

                    <!-- ═══════════════════════════════════════
                         DÉCONNEXION AVEC PROTECTION
                         ═══════════════════════════════════════ -->
                    @if($hasActiveSession)
                        <!-- Version bloquée - session active -->
                        <div class="nav-item nav-item--logout"
                             onclick="event.preventDefault(); 
                                      if (typeof Swal !== 'undefined') {
                                          Swal.fire({
                                              title: '⚠️ Session Active',
                                              html: 'Vous avez une session active <strong>#{{ $activeSession->id }}</strong>.<br><br>' +
                                                    'Veuillez la clôturer avant de vous déconnecter.',
                                              icon: 'warning',
                                              confirmButtonColor: '#10b981',
                                              confirmButtonText: 'Compris',
                                              showCancelButton: true,
                                              cancelButtonText: 'Aller à la session',
                                              cancelButtonColor: '#3b82f6'
                                          }).then((result) => {
                                              if (result.dismiss === Swal.DismissReason.cancel) {
                                                  window.location.href = '{{ route("cashier.sessions.show", $activeSession) }}';
                                              }
                                          });
                                      } else {
                                          alert('❌ Session active #{{ $activeSession->id }}. Veuillez clôturer avant de vous déconnecter.');
                                      }"
                             style="cursor: pointer; opacity: 0.7;"
                             title="Déconnexion impossible - Clôturez d'abord votre session #{{ $activeSession->id }}">
                            <div class="nav-icon">
                                <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i>
                            </div>
                            <div class="nav-content">
                                <div class="nav-title" style="color: #ef4444;">Déconnexion (bloquée)</div>
                                <div class="nav-subtitle">Session #{{ $activeSession->id }} active</div>
                            </div>
                        </div>
                    @else
                        <!-- Version normale - pas de session active -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="nav-item nav-item--logout"
                           onclick="event.preventDefault(); 
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            title: 'Déconnexion',
                                            text: 'Voulez-vous vraiment vous déconnecter ?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#10b981',
                                            cancelButtonColor: '#64748b',
                                            confirmButtonText: 'Oui, déconnecter',
                                            cancelButtonText: 'Annuler'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('logout-form').submit();
                                            }
                                        });
                                    } else {
                                        if (confirm('Déconnecter votre session ?')) {
                                            document.getElementById('logout-form').submit();
                                        }
                                    }
                                    return false;">
                            <div class="nav-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
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
                            } 
                            elseif (str_starts_with(auth()->user()->avatar, 'storage/') || str_contains(auth()->user()->avatar, 'storage/')) {
                                $avatarPath = asset(auth()->user()->avatar);
                            }
                            else {
                                $avatarPath = asset('storage/' . auth()->user()->avatar);
                            }
                        }
                    @endphp

                    @if($avatarPath)
                        <img src="{{ $avatarPath }}" 
                             alt="{{ auth()->user()->name }}"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=059669&color=fff&size=40';">
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
                    @if($hasActiveSession)
                    <div class="session-indicator mt-1">
                        <small class="text-success">
                            <i class="fas fa-circle fa-xs"></i> Session #{{ $activeSession->id }} active
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <div class="datetime-info mt-2 pt-2 border-top border-white-10">
                <small class="text-white-50 d-block">
                    <i class="far fa-clock me-1"></i>
                    <span id="sidebar-datetime">{{ now()->format('d/m/Y H:i') }}</span>
                </small>
            </div>
        </div>

    </div>
</aside>

<!-- Le CSS et JavaScript restent identiques -->
<style>
/* ─── Base ─────────────────────────────────── */
.sidebar {
    width: 272px;
    background: linear-gradient(170deg, #064e3b 0%, #065f46 55%, #047857 100%);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    z-index: 1001;
    transition: width 0.3s cubic-bezier(.4,0,.2,1), transform 0.3s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 24px rgba(0,0,0,.18);
}

/* ─── Logo ─────────────────────────────────── */
.sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    color: #fff;
    text-decoration: none;
    flex-shrink: 0;
    transition: background .2s;
}
.sidebar-logo:hover { background: rgba(255,255,255,.04); }

.brand-text { flex: 1; min-width: 0; }
.brand-name {
    font-size: 1rem;
    font-weight: 700;
    display: block;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.brand-subtitle {
    font-size: 0.72rem;
    color: rgba(255,255,255,.6);
    font-weight: 400;
    margin-top: 2px;
    white-space: nowrap;
}

/* ─── Toggle button ─────────────────────────── */
#toggle-sidebar {
    background: rgba(255,255,255,.1);
    border: none;
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .2s, transform .3s;
}
#toggle-sidebar:hover { background: rgba(255,255,255,.2); }

/* ─── Inner layout ──────────────────────────── */
.sidebar-inner {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}
.sidebar-header { 
    display: none; 
    padding: 0 20px 16px; 
}
.sidebar-body {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 12px 0 8px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.15) transparent;
}
.sidebar-body::-webkit-scrollbar { width: 4px; }
.sidebar-body::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,.15);
    border-radius: 2px;
}

/* ─── Nav section ───────────────────────────── */
.nav-menu { padding: 0; }
.nav-section { margin-bottom: 4px; }

.nav-section-title {
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: rgba(255,255,255,.38);
    padding: 10px 20px 4px;
    font-weight: 700;
}

/* ─── Nav item ──────────────────────────────── */
.nav-item {
    display: flex;
    align-items: center;
    padding: 9px 20px;
    color: rgba(255,255,255,.78);
    text-decoration: none;
    transition: background .18s ease, border-left-color .18s ease, padding-left .18s ease, color .18s ease;
    position: relative;
    border-left: 3px solid transparent;
    cursor: pointer;
    margin: 1px 0;
}
.nav-item:hover {
    color: #fff;
    background: linear-gradient(90deg, rgba(52,211,153,.14), rgba(52,211,153,.02));
    border-left-color: rgba(52,211,153,.6);
    padding-left: 24px;
    text-decoration: none;
}
.nav-item.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(16,185,129,.22), rgba(16,185,129,.04));
    border-left-color: #10b981;
    font-weight: 500;
}

/* ─── Highlight items ─────────────────────────── */
.nav-item--highlight .nav-icon {
    background: rgba(16,185,129,.18);
    color: #6ee7b7;
}
.nav-item--highlight .nav-title {
    font-weight: 600;
    font-size: 0.95rem;
}
.nav-item--highlight:hover .nav-icon,
.nav-item--highlight.active .nav-icon {
    background: rgba(16,185,129,.28);
    color: #34d399;
    transform: scale(1.08);
}

/* ─── Logout item ───────────────────────────── */
.nav-item--logout:hover {
    background: linear-gradient(90deg, rgba(239,68,68,.14), transparent);
    border-left-color: rgba(239,68,68,.5);
    color: #fca5a5;
}
.nav-item--logout:hover .nav-icon { color: #fca5a5; }

/* ─── Nav icon ──────────────────────────────── */
.nav-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    background: rgba(255,255,255,.06);
    border-radius: 8px;
    font-size: 0.92rem;
    flex-shrink: 0;
    transition: background .18s, color .18s, transform .18s;
}
.nav-item.active .nav-icon {
    background: rgba(16,185,129,.2);
    color: #34d399;
    transform: scale(1.06);
}

/* ─── Nav content ───────────────────────────── */
.nav-content { min-width: 0; }
.nav-title {
    font-size: 0.88rem;
    font-weight: 500;
    color: inherit;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nav-subtitle {
    font-size: 0.72rem;
    color: rgba(255,255,255,.45);
    margin-top: 1px;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ─── Badge notification ─────────────────────── */
.nav-badge {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-size: 0.65rem;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 17px;
    text-align: center;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(239,68,68,.35);
}

/* ─── Restricted ────────────────────────────── */
.nav-item.restricted::after {
    content: "🔒";
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.72rem;
    opacity: 0.4;
}

/* ─── Footer ────────────────────────────────── */
.sidebar-footer {
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,.08);
    background: rgba(0,0,0,.12);
    flex-shrink: 0;
}
.user-profile { display: flex; align-items: center; gap: 10px; }
.user-avatar { width: 38px; height: 38px; flex-shrink: 0; }
.user-avatar img {
    width: 100%; height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,.15);
}
.avatar-placeholder {
    width: 100%; height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #047857);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1rem;
    border: 2px solid rgba(255,255,255,.15);
}
.user-info { flex: 1; min-width: 0; }
.user-name {
    color: #fff; font-weight: 600; font-size: 0.85rem;
    line-height: 1.2; margin-bottom: 3px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.user-role .badge {
    font-size: 0.65rem; padding: 2px 6px;
    border-radius: 4px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.session-indicator .text-success {
    font-size: 0.68rem; display: flex; align-items: center; gap: 4px;
}
.session-indicator .fa-circle {
    font-size: 0.55rem; animation: pulse 2s infinite;
}
.datetime-info small { font-size: 0.72rem; color: rgba(255,255,255,.4); }
#sidebar-datetime { font-family: 'Courier New', monospace; font-weight: 500; }

/* ─── Badge colors ──────────────────────────── */
.bg-danger   { background: linear-gradient(135deg, #dc2626, #b91c1c) !important; }
.bg-primary  { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
.bg-success  { background: linear-gradient(135deg, #10b981, #047857) !important; }
.bg-warning  { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
.bg-info     { background: linear-gradient(135deg, #06b6d4, #0891b2) !important; }
.bg-secondary{ background: linear-gradient(135deg, #64748b, #475569) !important; }

.border-white-10 { border-color: rgba(255,255,255,.08) !important; }

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.4; }
}

/* ─── Collapsed ─────────────────────────────── */
.sidebar.collapsed { width: 68px; }

.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-content,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .nav-badge,
.sidebar.collapsed .user-info,
.sidebar.collapsed .datetime-info,
.sidebar.collapsed .restricted::after {
    display: none;
}
.sidebar.collapsed .sidebar-logo {
    justify-content: center;
    padding: 18px 10px;
}
.sidebar.collapsed .nav-item {
    justify-content: center;
    padding: 11px 0;
    border-left-width: 2px;
}
.sidebar.collapsed .nav-item:hover { padding-left: 0; }
.sidebar.collapsed .nav-icon { margin-right: 0; width: 38px; height: 38px; }
.sidebar.collapsed .user-profile { justify-content: center; }
.sidebar.collapsed .user-avatar { margin: 0; }
.sidebar.collapsed .sidebar-footer { padding: 12px 8px; }

/* Tooltip on collapsed */
.sidebar.collapsed .nav-item { position: relative; }
.sidebar.collapsed .nav-item::before {
    content: attr(data-tooltip);
    position: absolute;
    left: calc(100% + 10px);
    top: 50%;
    transform: translateY(-50%);
    background: rgba(6,78,59,.97);
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity .15s;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,.3);
}
.sidebar.collapsed .nav-item:hover::before { opacity: 1; }

/* ─── Mobile sidebar overlay ───────────────── */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,.6);
    z-index: 1000;
    opacity: 0;
    transition: opacity .3s;
    backdrop-filter: blur(2px);
}
.sidebar-overlay.show {
    display: block;
    opacity: 1;
}

/* ─── Mobile ────────────────────────────────── */
@media (max-width: 768px) {
    .sidebar {
        width: 85vw;
        max-width: 320px;
        transform: translateX(-100%);
        box-shadow: 6px 0 32px rgba(0,0,0,.3);
        height: 100vh;
        overflow: hidden;
    }
    .sidebar.show { 
        transform: translateX(0);
    }
    
    /* Hide desktop toggle */
    #toggle-sidebar { display: none !important; }
    
    /* Show mobile header */
    .sidebar-header {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,.08);
        background: rgba(0,0,0,.1);
        flex-shrink: 0;
    }
    .sidebar-header .header-title {
        color: white;
        font-size: 1rem;
        font-weight: 700;
        display: flex !important;
        align-items: center;
        gap: 8px;
    }
    .sidebar-header button {
        background: rgba(255,255,255,.1);
        color: white;
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .2s, transform .2s;
        font-size: 1.1rem;
    }
    .sidebar-header button:hover {
        background: rgba(255,255,255,.2);
        transform: rotate(90deg);
    }
    
    /* Logo section - keep visible on mobile */
    .sidebar-logo { 
        padding: 14px 20px;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-logo img {
        height: 36px !important;
    }
    .brand-name {
        font-size: 0.95rem !important;
    }
    .brand-subtitle {
        font-size: 0.7rem !important;
    }
    
    /* Sidebar inner - ensure proper height */
    .sidebar-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
    
    /* Sidebar body - scrollable area */
    .sidebar-body {
        flex: 1;
        overflow-y: auto !important;
        overflow-x: hidden;
        padding: 8px 0;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Nav sections - ensure visibility */
    .nav-section {
        display: block !important;
        margin-bottom: 8px;
    }
    .nav-section-title {
        display: block !important;
        padding: 8px 20px 4px;
        font-size: 0.65rem;
    }
    
    /* Nav items - ensure proper display */
    .nav-item {
        display: flex !important;
        padding: 10px 20px;
        margin: 1px 0;
    }
    .nav-icon {
        display: flex !important;
        width: 36px;
        height: 36px;
    }
    .nav-content {
        display: block !important;
    }
    .nav-title,
    .nav-subtitle {
        display: block !important;
    }
    
    /* Footer adjustments */
    .sidebar-footer {
        padding: 12px 16px;
        flex-shrink: 0;
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .user-avatar {
        width: 36px;
        height: 36px;
    }
    .user-name {
        font-size: 0.82rem;
    }
    .user-role .badge {
        font-size: 0.62rem;
    }
    .datetime-info {
        margin-top: 8px;
        padding-top: 8px;
    }
}

@media (max-width: 480px) {
    .sidebar {
        width: 100vw;
        max-width: none;
    }
    
    .nav-item {
        padding: 9px 16px;
    }
    .nav-section-title {
        padding-left: 16px;
        padding-right: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar      = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const toggleBtn    = document.getElementById('toggle-sidebar');
    const toggleBtnSm  = document.getElementById('toggle-sidebar-sm');

    /* ── Add data-tooltip for collapsed mode ── */
    document.querySelectorAll('.nav-item').forEach(item => {
        const title = item.querySelector('.nav-title');
        if (title) item.setAttribute('data-tooltip', title.textContent.trim());
    });

    /* ── Toggle desktop collapse ── */
    function applyCollapsed(collapsed) {
        sidebar.classList.toggle('collapsed', collapsed);
        if (toggleBtn) {
            toggleBtn.querySelector('i').className = collapsed
                ? 'fas fa-chevron-right'
                : 'fas fa-bars';
        }
        localStorage.setItem('sidebarCollapsed', collapsed);
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            applyCollapsed(!sidebar.classList.contains('collapsed'));
        });
    }

    /* ── Restore state ── */
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        applyCollapsed(true);
    }

    /* ── Toggle mobile sidebar ── */
    function toggleMobileSidebar() {
        const isOpen = sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show', isOpen);
        
        // Prevent body scroll when sidebar is open
        document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    if (toggleBtnSm) {
        toggleBtnSm.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleMobileSidebar();
        });
    }

    /* ── Close on overlay click ── */
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            toggleMobileSidebar();
        });
    }

    /* ── Close on nav link click (mobile) ── */
    if (window.innerWidth < 768) {
        document.querySelectorAll('.nav-item[href]').forEach(link => {
            link.addEventListener('click', () => {
                setTimeout(() => {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }, 200);
            });
        });
    }

    /* ── Real-time clock ── */
    function updateClock() {
        const el = document.getElementById('sidebar-datetime');
        if (!el) return;
        el.textContent = new Date().toLocaleDateString('fr-FR', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }
    updateClock();
    setInterval(updateClock, 30000);

    /* ── Handle resize ── */
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});
</script>