<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">

    <!-- Logo -->
    <a href="{{ route('dashboard.index') }}" class="sidebar-logo">
        <div class="d-flex align-items-center">
            <div>
                <img src="{{ ($currentHotel ?? null)?->logoUrl() ?? asset('img/logo_cactus1.jpeg') }}"
                    alt="{{ $currentHotel->name ?? 'Hotel' }}"
                    style="height: 38px; border-radius: 8px; flex-shrink:0;">
            </div>
            <div class="brand-text ms-2">
                <span class="brand-name">{{ $currentHotel->name ?? 'Hotel Management' }}</span>
                <small class="brand-subtitle d-block">{{ __('sidebar.brand_subtitle') }}</small>
            </div>
        </div>
        <button id="toggle-sidebar" class="btn-icon-toggle" title="{{ __('sidebar.toggle_title') }}">
            <i class="fas fa-bars"></i>
        </button>
    </a>

    <!-- Sidebar Inner -->
    <div class="sidebar-inner">

        <!-- Sidebar Header (mobile only) -->
        <div class="sidebar-header-mobile">
            <span class="header-title-mobile"><i class="fas fa-bars me-2"></i>{{ __('sidebar.mobile_menu') }}</span>
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
                @if (!in_array(auth()->user()->role, ['Customer', 'Servant', 'Cuisiner']))
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('sidebar.section_dashboard') }}</div>

                    <a href="{{ route('dashboard.index') }}" class="nav-item {{ $activeClass('dashboard.index') }}"
                        data-tooltip="{{ __('sidebar.dashboard_title') }}">
                        <div class="nav-icon"><i class="fas fa-chart-pie"></i></div>
                        <div class="nav-content">
                            <div class="nav-title">{{ __('sidebar.dashboard_title') }}</div>
                            <div class="nav-subtitle">{{ __('sidebar.dashboard_subtitle') }}</div>
                        </div>
                    </a>

                    @if (Route::has('revenue.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                        <a href="{{ route('revenue.index') }}" class="nav-item restricted {{ $activeClass('revenue.') }}"
                            data-tooltip="Revenus">
                            <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.revenue_title') }}</div>
                                <div class="nav-subtitle">Pilotage financier</div>
                            </div>
                        </a>
                    @endif

                    @if (Route::has('promo.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                        <a href="{{ route('promo.index') }}" class="nav-item restricted {{ $activeClass('promo.') }}"
                            data-tooltip="Codes promo">
                            <div class="nav-icon"><i class="fas fa-tags"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.promo_title') }}</div>
                                <div class="nav-subtitle">Réductions vitrine</div>
                            </div>
                        </a>
                    @endif

                    @if (Route::has('reviews.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                        <a href="{{ route('reviews.index') }}" class="nav-item restricted {{ $activeClass('reviews.') }}"
                            data-tooltip="Avis clients">
                            <div class="nav-icon"><i class="fas fa-star"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.reviews_title') }}</div>
                                <div class="nav-subtitle">Modération vitrine</div>
                            </div>
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager', 'Receptionist']))
                        @if (Route::has('availability.dashboard'))
                            <a href="{{ route('availability.dashboard') }}"
                                class="nav-item {{ $activeClass('availability.', false) }}"
                                data-tooltip="{{ __('sidebar.availability_title') }}">
                                <div class="nav-icon"><i class="fas fa-th-large"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.availability_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.availability_subtitle') }}</div>
                                </div>
                            </a>
                        @endif
                    @endif
                </div>
                @endif

                <!-- ACTIONS RAPIDES -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager', 'Receptionist']))
                    <div class="nav-section">
                        <div class="nav-section-title">{{ __('sidebar.section_quick_actions') }}</div>

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
                                data-tooltip="{{ __('sidebar.checkin_title') }}">
                                <div class="nav-icon"><i class="fas fa-door-open"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.checkin_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.checkin_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('transaction.reservation.createIdentity'))
                            <a href="{{ route('transaction.reservation.createIdentity') }}"
                                class="nav-item nav-item--highlight {{ $activeClass('transaction.reservation.createIdentity') }}"
                                data-tooltip="{{ __('sidebar.new_reservation_title') }}">
                                <div class="nav-icon"><i class="fas fa-calendar-plus"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.new_reservation_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.new_reservation_subtitle') }}</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- OPÉRATIONS -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager', 'Receptionist', 'Cashier', 'Servant', 'Cuisiner']))
                    <div class="nav-section">
                        <div class="nav-section-title">{{ __('sidebar.section_operations') }}</div>

                        @if (Route::has('transaction.index') && !in_array(auth()->user()->role, ['Servant', 'Cuisiner']))
                            <a href="{{ route('transaction.index') }}"
                                class="nav-item {{ $activeClass('transaction.', false) && !str_contains($currentRoute, 'transaction.reservation.') ? 'active' : '' }}"
                                data-tooltip="{{ __('sidebar.client_list_title') }}">
                                <div class="nav-icon"><i class="fas fa-shopping-bag"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.client_list_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.client_list_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('cashier.dashboard') && !in_array(auth()->user()->role, ['Servant', 'Cuisiner']))
                            <a href="{{ route('cashier.dashboard') }}"
                                class="nav-item {{ $activeClass('cashier.', false) }}" data-tooltip="{{ __('sidebar.cashier_title') }}">
                                <div class="nav-icon"><i class="fas fa-cash-register"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.cashier_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.cashier_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('restaurant.index') && auth()->user()->canUseModule('restaurant'))
                            @php $pendingOrdersCount = \App\Models\RestaurantOrder::where('status', 'pending')->count(); @endphp
                            <a href="{{ route('restaurant.index') }}"
                                class="nav-item {{ $activeClass('restaurant.', false) }}" data-tooltip="{{ __('sidebar.restaurant_title') }}">
                                <div class="nav-icon"><i class="fas fa-utensils"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.restaurant_title') }}</div>
                                    <div class="nav-subtitle">
                                        {{ __('sidebar.restaurant_pending', ['count' => $pendingOrdersCount]) }}
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
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager', 'Receptionist']))
                    <div class="nav-section">
                        <div class="nav-section-title">{{ __('sidebar.section_management') }}</div>

                        @if (Route::has('customer.index'))
                            <a href="{{ route('customer.index') }}"
                                class="nav-item {{ $activeClass('customer.index') }}" data-tooltip="{{ __('sidebar.customers_title') }}">
                                <div class="nav-icon"><i class="fas fa-users"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.customers_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.customers_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('room.index'))
                            <a href="{{ route('room.index') }}" class="nav-item {{ $activeClass('room.index') }}"
                                data-tooltip="{{ __('sidebar.rooms_title') }}">
                                <div class="nav-icon"><i class="fas fa-bed"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.rooms_title') }}</div>
                                    <div class="nav-subtitle">
                                        @if (auth()->user()->role == 'Receptionist')
                                            {{ __('sidebar.rooms_subtitle_view') }}
                                        @else
                                            {{ __('sidebar.rooms_subtitle_full') }}
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('channels.index') && in_array(auth()->user()->role, ['Super', 'Admin']))
                            <a href="{{ route('channels.index') }}"
                                class="nav-item restricted {{ $activeClass('channels.') }}"
                                data-tooltip="Synchronisation Booking/Airbnb">
                                <div class="nav-icon"><i class="fas fa-arrows-rotate"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.sync_title') }}</div>
                                    <div class="nav-subtitle">Booking · Airbnb</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('type.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                            <a href="{{ route('type.index') }}"
                                class="nav-item restricted {{ $activeClass('type.index') }}"
                                data-tooltip="{{ __('sidebar.room_types_title') }}">
                                <div class="nav-icon"><i class="fas fa-layer-group"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.room_types_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.room_types_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('payments.index'))
                            @php $isPaymentActive = $activeClass('payments.', false) || $activeClass('payment.', false); @endphp
                            <a href="{{ route('payments.index') }}"
                                class="nav-item {{ $isPaymentActive ? 'active' : '' }}" data-tooltip="{{ __('sidebar.payments_title') }}">
                                <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.payments_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.payments_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('hotel.settings.edit') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                            <a href="{{ route('hotel.settings.edit') }}"
                                class="nav-item {{ $activeClass('hotel.settings.') }}" data-tooltip="{{ __('sidebar.establishment_title') }}">
                                <div class="nav-icon"><i class="fas fa-palette"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.establishment_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.establishment_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('billing.show') && in_array(auth()->user()->role, ['Super', 'Admin']))
                            <a href="{{ route('billing.show') }}"
                                class="nav-item {{ $activeClass('billing.') }}" data-tooltip="{{ __('sidebar.billing_title') }}">
                                <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.billing_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.billing_subtitle') }}</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- AIDE / GUIDE (tous les rôles) -->
                @if (Route::has('guide'))
                    <div class="nav-section">
                        <a href="{{ route('guide') }}" target="_blank" rel="noopener"
                            class="nav-item"                         data-tooltip="{{ __('sidebar.guide_title') }}">
                            <div class="nav-icon"><i class="fas fa-book-open"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.guide_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.guide_subtitle') }}</div>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- NETTOYAGE -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager', 'Housekeeping', 'Receptionist']) && auth()->user()->canUseModule('housekeeping'))
                    <div class="nav-section">
                        <div class="nav-section-title">{{ __('sidebar.section_housekeeping') }}</div>

                        @if (Route::has('housekeeping.dashboard'))
                            <a href="{{ route('housekeeping.dashboard') }}"
                                class="nav-item {{ $activeClass('housekeeping.', false) }}"
                                data-tooltip="{{ __('sidebar.housekeeping_title') }}">
                                <div class="nav-icon"><i class="fas fa-broom"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.housekeeping_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.housekeeping_subtitle') }}</div>
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
                                title="{{ __('sidebar.checkin_readonly_subtitle') }}" data-tooltip="{{ __('sidebar.checkin_title') }}">
                                <div class="nav-icon"><i class="fas fa-door-open"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.checkin_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.checkin_readonly_subtitle') }}</div>
                                </div>
                                <span class="readonly-tag">👁️</span>
                            </a>
                        @endif

                        @if (Route::has('roomstatus.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                            <a href="{{ route('roomstatus.index') }}"
                                class="nav-item {{ $activeClass('roomstatus.index') }}" data-tooltip="{{ __('sidebar.room_status_title') }}">
                                <div class="nav-icon"><i class="fas fa-flag"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.room_status_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.room_status_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('facility.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                            <a href="{{ route('facility.index') }}"
                                class="nav-item {{ $activeClass('facility.index') }}" data-tooltip="{{ __('sidebar.equipment_title') }}">
                                <div class="nav-icon"><i class="fas fa-tools"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.equipment_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.equipment_subtitle') }}</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- ADMINISTRATION -->
                @if (in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                    <div class="nav-section">
                        <div class="nav-section-title">{{ __('sidebar.section_administration') }}</div>

                        {{-- Personnel : l'hôtelier gère son équipe (issue #180) --}}
                        @if (Route::has('staff.index') && in_array(auth()->user()->role, ['Super', 'Admin', 'Manager']))
                            <a href="{{ route('staff.index') }}"
                                class="nav-item {{ $activeClass('staff.', false) }}" data-tooltip="{{ __('sidebar.staff_title') }}">
                                <div class="nav-icon"><i class="fas fa-user-tie"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.staff_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.staff_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('user.index') && auth()->user()->role == 'Super')
                            <a href="{{ route('user.index') }}"
                                class="nav-item restricted {{ $activeClass('user.index') }}"
                                data-tooltip="{{ __('sidebar.users_title') }}">
                                <div class="nav-icon"><i class="fas fa-user-cog"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.users_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.users_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('reports.index') && auth()->user()->canUseModule('reports'))
                            <a href="{{ route('reports.index') }}"
                                class="nav-item {{ $activeClass('reports.index') }}" data-tooltip="{{ __('sidebar.reports_title') }}">
                                <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.reports_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.reports_subtitle') }}</div>
                                </div>
                            </a>
                        @endif

                        @if (Route::has('activity.index'))
                            <a href="{{ route('activity.index') }}"
                                class="nav-item {{ $activeClass('activity.index') }}" data-tooltip="{{ __('sidebar.activity_title') }}">
                                <div class="nav-icon"><i class="fas fa-history"></i></div>
                                <div class="nav-content">
                                    <div class="nav-title">{{ __('sidebar.activity_title') }}</div>
                                    <div class="nav-subtitle">{{ __('sidebar.activity_subtitle') }}</div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- MON COMPTE -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('sidebar.section_my_account') }}</div>

                    @if (Route::has('profile.index'))
                        <a href="{{ route('profile.index') }}"
                            class="nav-item {{ $activeClass('profile.', false) }}" data-tooltip="{{ __('sidebar.profile_title') }}">
                            <div class="nav-icon"><i class="fas fa-user"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.profile_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.profile_subtitle') }}</div>
                            </div>
                        </a>
                    @endif

                    @if (auth()->user()->role == 'Customer' && Route::has('transaction.myReservations'))
                        <a href="{{ route('transaction.myReservations') }}"
                            class="nav-item {{ $activeClass('transaction.myReservations') }}"
                            data-tooltip="{{ __('sidebar.my_reservations_title') }}">
                            <div class="nav-icon"><i class="fas fa-book"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.my_reservations_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.my_reservations_subtitle') }}</div>
                            </div>
                        </a>
                    @endif

                    @if (Route::has('notification.index'))
                        <a href="{{ route('notification.index') }}"
                            class="nav-item {{ $activeClass('notification.index') }}" data-tooltip="{{ __('sidebar.notifications_title') }}">
                            <div class="nav-icon">
                                <i class="fas fa-bell"></i>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="nav-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.notifications_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.notifications_subtitle') }}</div>
                            </div>
                        </a>
                    @endif

                    @if (auth()->user()->role == 'Receptionist' && Route::has('receptionist.session.active'))
                        <a href="{{ route('receptionist.session.active') }}"
                            class="nav-item {{ $activeClass('receptionist.session.', false) }}"
                            data-tooltip="{{ __('sidebar.session_title') }}">
                            <div class="nav-icon"><i class="fas fa-user-clock"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.session_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.session_subtitle') }}</div>
                            </div>
                        </a>
                    @endif

                    <!-- DÉCONNEXION -->
                    @if ($hasActiveSession)
                        <div class="nav-item nav-item--logout"
                            onclick="if(typeof Swal!=='undefined'){Swal.fire({title:'{{ __('sidebar.logout_blocked_title') }}',html:'{{ __('sidebar.logout_blocked_text', ['id' => $activeSession->id]) }}',icon:'warning',confirmButtonColor:'{{ ($currentHotel ?? null)?->primaryColor() ?? "#1e6b2e" }}',confirmButtonText:'{{ __('sidebar.logout_blocked_confirm') }}',showCancelButton:true,cancelButtonText:'{{ __('sidebar.logout_blocked_cancel') }}',cancelButtonColor:'#545954'}).then(r=>{if(r.dismiss===Swal.DismissReason.cancel)window.location.href='{{ route('cashier.sessions.show', $activeSession) }}';});}else{alert('{{ __('sidebar.logout_blocked_text', ['id' => $activeSession->id]) }}');}"
                            style="cursor:pointer;opacity:.7" data-tooltip="{{ __('sidebar.logout_blocked') }}">
                            <div class="nav-icon"><i class="fas fa-sign-out-alt" style="color:#fca5a5"></i></div>
                            <div class="nav-content">
                                <div class="nav-title" style="color:#fca5a5">{{ __('sidebar.logout_blocked') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.session_active', ['id' => $activeSession->id]) }}</div>
                            </div>
                        </div>
                    @else
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                            @csrf</form>
                        <a href="#" class="nav-item nav-item--logout"
                            onclick="event.preventDefault(); confirmLogout(); return false;"
                            data-tooltip="{{ __('sidebar.logout_title') }}">
                            <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
                            <div class="nav-content">
                                <div class="nav-title">{{ __('sidebar.logout_title') }}</div>
                                <div class="nav-subtitle">{{ __('sidebar.logout_subtitle') }}</div>
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
                    @php $avatarPath = auth()->user()->avatar ? auth()->user()->getAvatar() : null; @endphp
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
                                <span class="role-pill role-super">{{ __('sidebar.role_super') }}</span>
                            @break

                            @case('Admin')
                                <span class="role-pill role-admin">{{ __('sidebar.role_admin') }}</span>
                            @break

                            @case('Manager')
                                <span class="role-pill role-admin">Direction</span>
                            @break

                            @case('Receptionist')
                                <span class="role-pill role-recep">{{ __('sidebar.role_receptionist') }}</span>
                            @break

                            @case('Cashier')
                                <span class="role-pill role-recep">{{ __('sidebar.role_cashier') }}</span>
                            @break

                            @case('Housekeeping')
                                <span class="role-pill role-house">{{ __('sidebar.role_housekeeping') }}</span>
                            @break

                            @case('Servant')
                                <span class="role-pill role-recep">{{ __('sidebar.role_servant') }}</span>
                            @break

                            @case('Cuisiner')
                                <span class="role-pill role-house">{{ __('sidebar.role_cook') }}</span>
                            @break

                            @case('Customer')
                                <span class="role-pill role-cust">{{ __('sidebar.role_customer') }}</span>
                            @break

                            @default
                                <span class="role-pill role-other">{{ auth()->user()->role }}</span>
                        @endswitch
                    </div>
                    @if ($hasActiveSession)
                        <div class="session-dot">
                            <span class="dot-live"></span>
                            <small>{{ __('sidebar.session_hash', ['id' => $activeSession->id]) }}</small>
                        </div>
                    @endif
                </div>
            </div>
            <div class="sidebar-lang-toggle">
                <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}"
                   class="lang-toggle-btn" title="{{ app()->getLocale() === 'fr' ? 'Switch to English' : 'Passer en français' }}">
                    <i class="fas fa-globe"></i>
                    <span>{{ app()->getLocale() === 'fr' ? 'English' : 'Français' }}</span>
                </a>
            </div>
            <div class="sidebar-theme-toggle">
                <button type="button" class="theme-toggle-btn" id="themeToggle" title="Changer de thème">
                    <i class="fas fa-sun" id="themeIcon"></i>
                    <span id="themeLabel">Clair</span>
                </button>
            </div>
            <div class="sidebar-time">
                <i class="far fa-clock"></i>
                <span id="sidebar-datetime">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>

    </div>
</aside>

<style>
/* ═══════════════════════════════════════════════════════════════
   SIDEBAR, design épuré clair (maquette). Comportement (repli 64px,
   mobile, tooltips) préservé ; couleurs = neutres propres + accent de
   l'hôtel (--g*). Thème clair & sombre.
   ═══════════════════════════════════════════════════════════════ */
.sidebar {
  /* Clair par défaut, sombre en mode sombre (suit le thème de l'app) */
  --sb-surface: #ffffff;
  --sb-surface-2: #f6f8fb;
  --sb-line: #e8ecef;
  --sb-ink: #232a33;
  --sb-ink2: #5b6470;
  --sb-ink3: #98a2ad;
  --sb-tint: #f2f5f8;
  --sb-acc: var(--g600, #5b60e6);
  --sb-acc-tint: color-mix(in srgb, var(--g500, #6366f1) 12%, #fff);

  width: 272px;
  background: var(--sb-surface);
  color: var(--sb-ink);
  position: fixed; left: 0; top: 0; height: 100vh; z-index: 1001;
  transition: width .3s cubic-bezier(.4,0,.2,1);
  display: flex; flex-direction: column;
  overflow: hidden;
  font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
}
html[data-theme="dark"] .sidebar {
  --sb-surface: #12151e;
  --sb-surface-2: #181c27;
  --sb-line: rgba(255,255,255,.07);
  --sb-ink: #e7eaf3;
  --sb-ink2: #98a0b3;
  --sb-ink3: #6b7284;
  --sb-tint: rgba(255,255,255,.055);
  --sb-acc: var(--g500, #6366f1);
  --sb-acc-tint: color-mix(in srgb, var(--g500, #6366f1) 20%, transparent);
}

/* ── Sidebar flottante à coins arrondis (desktop) ── */
@media (min-width: 769px) {
  .sidebar {
    top: 20px; left: 20px;
    height: calc(100vh - 40px);
    border: 1px solid var(--sb-line);
    border-radius: 18px;
    box-shadow: 0 16px 44px -26px rgba(20,30,25,.25);
  }
  html[data-theme="dark"] .sidebar { box-shadow: 0 20px 55px -24px rgba(0,0,0,.6); }
}

/* Liquid glass : sidebar translucide, fort flou + saturation, bord et reflet */
html.has-app-bg .sidebar {
  background: color-mix(in srgb, var(--sb-surface) 64%, transparent);
  backdrop-filter: blur(24px) saturate(165%);
  -webkit-backdrop-filter: blur(24px) saturate(165%);
  border-color: color-mix(in srgb, #ffffff 22%, transparent);
  box-shadow: 0 16px 44px -22px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.25);
}
/* Carte utilisateur en bas : verre elle aussi */
html.has-app-bg .user-profile {
  background: color-mix(in srgb, var(--sb-surface-2) 55%, transparent);
  backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
}

/* ── logo ── */
.sidebar-logo {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px; border-bottom: 1px solid var(--sb-line);
  color: var(--sb-ink); text-decoration: none; flex-shrink: 0; min-height: 64px;
  transition: background .18s;
}
.sidebar-logo:hover { background: var(--sb-tint); text-decoration: none; color: var(--sb-ink); }
.brand-text { flex: 1; min-width: 0; }
.brand-name { font-size: .95rem; font-weight: 680; display: block; line-height: 1.2; letter-spacing: -.01em;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.brand-subtitle { font-size: .68rem; color: var(--sb-ink3); margin-top: 2px; }

/* ── toggle button ── */
.btn-icon-toggle {
  background: var(--sb-tint); border: 1px solid var(--sb-line); color: var(--sb-ink2);
  width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; flex-shrink: 0; transition: all .18s; font-size: .85rem;
}
.btn-icon-toggle:hover { background: var(--sb-surface); color: var(--sb-ink); }

/* ── inner / body ── */
.sidebar-inner { flex: 1; display: flex; flex-direction: column; min-height: 0; }
.sidebar-header-mobile { display: none; }
.sidebar-body {
  flex: 1; overflow-y: auto; overflow-x: hidden; padding: 8px 0 8px;
  scrollbar-width: none; -ms-overflow-style: none;   /* aucune barre visible (scroll conservé) */
}
.sidebar-body::-webkit-scrollbar { width: 0 !important; height: 0 !important; display: none; }

/* ── sections ── */
.nav-menu { padding: 0; }
.nav-section { margin-bottom: 10px; }
.nav-section-title {
  font-size: .63rem; text-transform: uppercase; letter-spacing: .1em;
  color: var(--sb-ink3); padding: 20px 24px 9px; font-weight: 700;
}

/* ── nav items ── */
.nav-item {
  display: flex; align-items: center; gap: 2px;
  padding: 12px 14px; margin: 4px 12px; border-radius: 11px;
  color: var(--sb-ink2); text-decoration: none; cursor: pointer; position: relative;
  transition: background .16s, color .16s;
}
.nav-item:hover { color: var(--sb-ink); background: var(--sb-tint); text-decoration: none; }
.nav-item.active { color: var(--sb-acc); background: var(--sb-acc-tint); font-weight: 600; box-shadow: inset 3px 0 0 0 var(--sb-acc); }
html[data-theme="dark"] .nav-item.active { color: #fff; }
.nav-item--highlight .nav-title { font-weight: 600; }
.nav-item--highlight .nav-icon { color: var(--sb-acc); }
.nav-item--logout { color: var(--sb-ink2); }
.nav-item--logout:hover { background: color-mix(in srgb, #ef4444 12%, transparent); color: #dc2626; }
html[data-theme="dark"] .nav-item--logout:hover { color: #f87171; }
.nav-item--readonly .nav-icon { color: var(--sb-ink3); }

.nav-icon {
  width: 24px; display: flex; align-items: center; justify-content: center;
  margin-right: 12px; font-size: .95rem;
  flex-shrink: 0; color: var(--sb-ink2); transition: color .16s;
}
.nav-item:hover .nav-icon { color: var(--sb-ink); }
.nav-item.active .nav-icon { color: var(--sb-acc); }
html[data-theme="dark"] .nav-item.active .nav-icon { color: #fff; }

.nav-content { min-width: 0; flex: 1; }
.nav-title { font-size: .86rem; font-weight: 500; color: inherit; line-height: 1.3;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.nav-subtitle { display: none; } /* rail épuré : une seule ligne par item */

.nav-badge {
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  background: #e5484d; color: #fff; font-size: .6rem; padding: 2px 6px; border-radius: 10px;
  min-width: 16px; text-align: center; font-weight: 700;
}
.nav-item.restricted::after {
  content: "\f023"; font-family: "Font Awesome 6 Free"; font-weight: 900;
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  font-size: .62rem; opacity: .3;
}
.readonly-tag { font-size: .65rem; margin-left: auto; opacity: .5; }

/* ── footer ── */
.sidebar-footer { padding: 10px 12px 12px; border-top: 1px solid var(--sb-line); flex-shrink: 0; }
.user-profile { display: flex; align-items: center; gap: 10px;
  padding: 9px 10px; border-radius: 12px; background: var(--sb-surface-2); border: 1px solid var(--sb-line); }
.user-avatar { width: 34px; height: 34px; flex-shrink: 0; }
.user-avatar img { width: 100%; height: 100%; border-radius: 9px; object-fit: cover; border: 1px solid var(--sb-line); }
.avatar-placeholder {
  width: 100%; height: 100%; border-radius: 9px; background: var(--sb-acc);
  display: flex; align-items: center; justify-content: center; color: #fff;
  font-size: .9rem; font-weight: 700;
}
.user-info { flex: 1; min-width: 0; }
.user-name { color: var(--sb-ink); font-weight: 640; font-size: .82rem;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }

.role-pill { font-size: .6rem; padding: 2px 8px; border-radius: 20px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .3px; color: #fff; display: inline-block; }
.role-super { background: #dc2626; }
.role-admin { background: var(--sb-acc); }
.role-recep { background: #2563eb; }
.role-house { background: #d97706; }
.role-cust  { background: #0891b2; }
.role-other { background: #64748b; }

.session-dot { display: flex; align-items: center; gap: 5px; margin-top: 4px; }
.session-dot small { font-size: .62rem; color: var(--sb-ink3); }
.dot-live { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; display: inline-block; animation: pulse-dot 2s infinite; }
@keyframes pulse-dot {
  0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
  50% { opacity: .6; box-shadow: 0 0 0 4px rgba(34,197,94,0); }
}
@media (prefers-reduced-motion: reduce) { .dot-live { animation: none; } }

.sidebar-time { display: flex; align-items: center; gap: 6px; margin-top: 10px; padding-top: 10px;
  border-top: 1px solid var(--sb-line); font-size: .68rem; color: var(--sb-ink3); }

/* ── lang / theme toggles ── */
.sidebar-lang-toggle { margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--sb-line); }
.lang-toggle-btn, .theme-toggle-btn {
  display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px;
  color: var(--sb-ink2); text-decoration: none; font-size: .78rem; font-weight: 550;
  transition: background .18s, color .18s; width: 100%; border: none; background: none;
  cursor: pointer; font-family: inherit;
}
.lang-toggle-btn:hover, .theme-toggle-btn:hover { background: var(--sb-tint); color: var(--sb-ink); text-decoration: none; }
.lang-toggle-btn i, .theme-toggle-btn i { font-size: .85rem; width: 18px; text-align: center; }
.sidebar-theme-toggle { margin-top: 6px; }
.sidebar.collapsed .sidebar-theme-toggle, .sidebar.collapsed .sidebar-lang-toggle { display: none; }
#sidebar-datetime { font-family: 'DM Mono', monospace; font-weight: 500; }

/* ════ COLLAPSED (desktop) · 64px ════ */
.sidebar.collapsed { width: 64px; }
.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-content,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .nav-badge,
.sidebar.collapsed .user-info,
.sidebar.collapsed .sidebar-time,
.sidebar.collapsed .sidebar-theme-toggle,
.sidebar.collapsed .restricted::after,
.sidebar.collapsed .readonly-tag { display: none; }
.sidebar.collapsed .sidebar-logo { justify-content: center; padding: 16px 8px; }
.sidebar.collapsed .nav-item { justify-content: center; padding: 11px 0; margin: 6px 10px; }
.sidebar.collapsed .nav-icon { margin-right: 0; width: 38px; height: 38px; }
.sidebar.collapsed .user-profile { justify-content: center; }
.sidebar.collapsed .user-avatar { margin: 0; }
.sidebar.collapsed .sidebar-footer { padding: 12px 8px; }

/* tooltip quand collapsed */
.sidebar.collapsed .nav-item::before {
  content: attr(data-tooltip); position: absolute; left: calc(100% + 10px); top: 50%;
  transform: translateY(-50%); background: #232a26; color: #fff; padding: 5px 10px;
  border-radius: 6px; font-size: .78rem; white-space: nowrap; pointer-events: none;
  opacity: 0; transition: opacity .15s; z-index: 9999; box-shadow: 0 6px 18px rgba(0,0,0,.22);
}
.sidebar.collapsed .nav-item:hover::before { opacity: 1; }

/* ════ OVERLAY MOBILE ════ */
.sidebar-overlay {
  display: none; position: fixed; inset: 0; background: rgba(15,25,20,.5);
  z-index: 1000; opacity: 0; transition: opacity .3s; backdrop-filter: blur(3px);
}
.sidebar-overlay.show { display: block; opacity: 1; }

/* ════ MOBILE ≤ 768px ════ */
@media (max-width: 768px) {
  .sidebar {
    width: 82vw; max-width: 300px; transform: translateX(-100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1); height: 100vh; z-index: 1050;
  }
  .sidebar.show { transform: translateX(0); }
  .sidebar.collapsed { width: 82vw; max-width: 300px; }
  .sidebar.collapsed .brand-text,
  .sidebar.collapsed .nav-content,
  .sidebar.collapsed .nav-section-title,
  .sidebar.collapsed .user-info,
  .sidebar.collapsed .sidebar-time { display: block !important; }
  .sidebar.collapsed .nav-item { justify-content: flex-start; padding: 8px 12px; margin: 1px 10px; }
  .sidebar.collapsed .nav-icon { margin-right: 10px; width: 30px; height: 30px; }
  .sidebar.collapsed .user-profile { justify-content: flex-start; }
  .sidebar.collapsed .sidebar-logo { justify-content: space-between; padding: 14px 16px; }
  #toggle-sidebar { display: none !important; }
  .sidebar-header-mobile {
    display: flex !important; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-bottom: 1px solid var(--sb-line); flex-shrink: 0;
  }
  .header-title-mobile { color: var(--sb-ink); font-size: .88rem; font-weight: 700;
    display: flex; align-items: center; gap: 8px; }
  .sidebar-body { flex: 1; overflow-y: auto; -webkit-overflow-scrolling: touch; }
  .sidebar-footer { flex-shrink: 0; }
}
@media (max-width: 380px) { .sidebar { width: 92vw; max-width: none; } }

/* ═══════════ Popup de déconnexion (SweetAlert habillé, épuré, theme-aware) ═══════════ */
/* On n'habille que l'apparence, les boutons restent gérés par SweetAlert (interaction OK). */
.swal-logout { border-radius: 20px !important; font-family: 'DM Sans', system-ui, sans-serif !important; padding-bottom: 8px !important; }
.swal-logout .swal2-icon { border: 0 !important; width: 62px; height: 62px;
  background: color-mix(in srgb, var(--g600, #2e8540) 15%, transparent) !important;
  color: var(--g600, #2e8540) !important; }
.swal-logout .swal2-icon .swal2-icon-content { font-size: 1.5rem; color: var(--g600, #2e8540) !important; }
.swal-logout .swal2-title { font-size: 1.2rem !important; font-weight: 700 !important; letter-spacing: -.01em; }
.swal-logout .swal2-html-container { font-size: .92rem !important; }
.swal-logout .swal2-confirm, .swal-logout .swal2-cancel { border-radius: 11px !important; font-weight: 650 !important; padding: 10px 20px !important; box-shadow: none !important; }
/* Mode sombre : popup sombre lisible */
html[data-theme="dark"] .swal-logout { background: #161b18 !important; }
html[data-theme="dark"] .swal-logout .swal2-title,
html[data-theme="dark"] .swal-logout .swal2-html-container { color: #e9eeeb !important; }
</style>

<script>
    // ── Popup de déconnexion soigné (SweetAlert stylé) ──
    window.confirmLogout = function () {
        var doLogout = function () {
            var f = document.getElementById('logout-form');
            if (f) f.submit();
        };
        var L = {
            title:   @js(__('sidebar.logout_confirm_title')),
            text:    @js(__('sidebar.logout_confirm_text')),
            confirm: @js(__('sidebar.logout_confirm_button')),
            cancel:  @js(__('sidebar.logout_cancel_button'))
        };
        if (typeof Swal === 'undefined') { if (confirm(L.text)) doLogout(); return; }
        Swal.fire({
            icon: 'question',
            iconHtml: '<i class="fas fa-right-from-bracket"></i>',
            title: L.title,
            text: L.text,
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonText: L.confirm,
            cancelButtonText: L.cancel,
            confirmButtonColor: @js(($currentHotel ?? null)?->primaryColor() ?? '#2e8540'),
            cancelButtonColor: '#6b746e',
            customClass: { popup: 'swal-logout' }
        }).then(function (r) { if (r.isConfirmed) doLogout(); });
    };

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

            /* On NE fixe PAS la marge en dur : le CSS (body.sidebar-is-collapsed)
               gère margin-left (312px ouverte / 104px repliée). On efface tout
               résidu inline pour que le CSS prime. */
            if (content) { content.style.marginLeft = ''; }

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
                /* Mobile : le CSS gère (margin 0). On efface tout inline résiduel. */
                if (content) content.style.marginLeft = '';
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

    // ===== Position de la sidebar conservée entre les pages (issue #172) =====
    // Avant : après un clic sur un onglet du bas (ex. Journal), la page se
    // rechargeait et la sidebar revenait en haut (Dashboard). On mémorise le
    // scroll et on le restaure ; sinon on centre l'élément actif.
    (function () {
        const sb = document.querySelector('.sidebar-body');
        if (!sb) return;
        const KEY = 'sidebar-scroll';
        const saved = sessionStorage.getItem(KEY);
        if (saved !== null) {
            sb.scrollTop = parseInt(saved, 10) || 0;
        } else {
            const active = sb.querySelector('.nav-item.active');
            if (active) sb.scrollTop = Math.max(0, active.offsetTop - sb.clientHeight / 2);
        }
        sb.addEventListener('scroll', function () {
            sessionStorage.setItem(KEY, sb.scrollTop);
        }, { passive: true });
    })();
</script>
