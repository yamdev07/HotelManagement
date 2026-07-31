<header class="mobile-header">
    <div class="mh-inner">

        {{-- ── Hamburger ── --}}
        <button class="mh-hamburger" type="button" onclick="window.toggleMobileSidebar && window.toggleMobileSidebar()" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>

        {{-- ── Brand ── --}}
        <a href="{{ route('dashboard.index') }}" class="mh-brand">
            <div class="mh-brand-icon">
                <i class="fas fa-hotel"></i>
            </div>
            <span class="mh-brand-name">{{ ($currentHotel ?? null)?->name ?? config('app.name', 'checkinHub') }}</span>
        </a>

        {{-- ── Right : lang + notifs + profil ── --}}
        <div class="mh-right">

            {{-- Language Toggle --}}
            <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}"
               class="mh-icon-btn" title="{{ app()->getLocale() === 'fr' ? 'English' : 'Français' }}">
                {{ strtoupper(app()->getLocale()) }}
            </a>

            {{-- Notifications --}}
            <div class="dropdown">
                <button class="mh-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="mh-notif-dot">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end mh-dropdown-notif shadow-lg">
                    <div class="mh-dd-head">
                        <span class="mh-dd-title">{{ __('sidebar.notif_dropdown_title') }}</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="mh-dd-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </div>
                    <div class="mh-notif-list">
                        @forelse(auth()->user()->unreadNotifications->take(4) as $notification)
                        <a href="{{ route('notification.routeTo', ['id' => $notification->id]) }}" class="mh-notif-item">
                            <div class="mh-notif-ico"><i class="fas fa-bell"></i></div>
                            <div class="mh-notif-body">
                                <div class="mh-notif-msg">{{ Str::limit($notification->data['title'] ?? __('sidebar.notif_new'), 42) }}</div>
                                <div class="mh-notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </a>
                        @empty
                        <div class="mh-notif-empty">
                            <i class="fas fa-bell-slash"></i>
                            <span>{{ __('sidebar.notif_empty') }}</span>
                        </div>
                        @endforelse
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 4)
                    <a href="{{ route('notification.index') }}" class="mh-dd-footer">
                        {{ __('sidebar.notif_view_all') }} ({{ auth()->user()->unreadNotifications->count() }})
                    </a>
                    @endif
                </div>
            </div>

            {{-- Profil --}}
            <div class="dropdown">
                <button class="mh-profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="mh-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="mh-profile-info d-none d-sm-block">
                        <div class="mh-profile-name">{{ auth()->user()->name }}</div>
                        <div class="mh-profile-role">{{ auth()->user()->role ?? 'Admin' }}</div>
                    </div>
                    <i class="fas fa-chevron-down mh-arrow d-none d-sm-block"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end mh-dropdown-profile shadow-lg">
                    <div class="mh-dd-head mh-dd-head--profile">
                        <div class="mh-avatar mh-avatar--sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <div>
                            <div class="mh-dd-user-name">{{ auth()->user()->name }}</div>
                            <div class="mh-dd-user-role">{{ auth()->user()->role ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <div class="mh-dd-divider"></div>
                    @if(Route::has('profile.index'))
                    <a class="mh-dd-item" href="{{ route('profile.index') }}">
                        <i class="fas fa-user"></i> {{ __('sidebar.my_profile') }}
                    </a>
                    @endif
                    @if(Route::has('notification.index'))
                    <a class="mh-dd-item" href="{{ route('notification.index') }}">
                        <i class="fas fa-bell"></i> {{ __('sidebar.notifications_title') }}
                    </a>
                    @endif
                    <div class="mh-dd-divider"></div>
                    <a class="mh-dd-item mh-dd-item--danger"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('mh-logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> {{ __('sidebar.logout_title') }}
                    </a>
                    <form id="mh-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>

        </div>
    </div>
</header>

<style>
/* ════════════════════════════════════════
   MOBILE HEADER
════════════════════════════════════════ */
.mobile-header {
    display: none; /* masqué sur desktop */
    position: sticky;
    top: 0;
    z-index: 999;
    height: 56px;
    background: #ffffff;
    border-bottom: 1.5px solid #dde0dd;
    box-shadow: 0 1px 8px rgba(0,0,0,.06);
}

.mh-inner {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 14px;
    gap: 10px;
}

/* ── Hamburger ── */
.mh-hamburger {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--g50);
    border: 1.5px solid var(--g100);
    color: var(--hotel-primary, var(--g600));
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
    font-size: .95rem;
    transition: background .2s, transform .15s;
}
.mh-hamburger:hover { background: var(--g100); transform: scale(1.05); }
.mh-hamburger:active { transform: scale(.96); }

/* ── Brand ── */
.mh-brand {
    display: flex; align-items: center; gap: 8px;
    text-decoration: none; flex: 1; min-width: 0;
}
.mh-brand-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: var(--hotel-primary, var(--g600));
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: .78rem; flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(30,107,46,.3);
}
.mh-brand-name {
    font-size: .88rem; font-weight: 700;
    color: #131513;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-family: 'DM Sans', system-ui, sans-serif;
}

/* ── Right group ── */
.mh-right {
    display: flex; align-items: center; gap: 6px; flex-shrink: 0;
}

/* ── Icon button (notif) ── */
.mh-icon-btn {
    width: 36px; height: 36px; border-radius: 10px;
    background: #f8f9f8; border: 1.5px solid #dde0dd;
    color: #545954; display: flex; align-items: center; justify-content: center;
    cursor: pointer; position: relative; font-size: .85rem;
    transition: background .2s;
}
.mh-icon-btn:hover { background: #eff0ef; color: var(--hotel-primary, var(--g600)); }

.mh-notif-dot {
    position: absolute; top: -3px; right: -3px;
    background: var(--hotel-primary, var(--g500)); color: white;
    font-size: .55rem; font-weight: 700;
    min-width: 16px; height: 16px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px;
    border: 2px solid white;
}

/* ── Profile button ── */
.mh-profile-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 5px 10px 5px 5px;
    background: #f8f9f8; border: 1.5px solid #dde0dd;
    border-radius: 10px; cursor: pointer;
    transition: background .2s;
}
.mh-profile-btn:hover { background: #eff0ef; }

.mh-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, var(--hotel-primary, var(--g600)), var(--hotel-primary, var(--g500)));
    color: white; font-size: .7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-family: 'DM Mono', monospace;
    border: 2px solid var(--g100);
}
.mh-avatar--sm { width: 34px; height: 34px; font-size: .72rem; }

.mh-profile-name { font-size: .78rem; font-weight: 600; color: #252825; line-height: 1.2; }
.mh-profile-role { font-size: .62rem; color: #9ba09b; }
.mh-arrow { font-size: .6rem; color: #9ba09b; }

/* ════════════════════════════════════════
   DROPDOWNS
════════════════════════════════════════ */
.mh-dropdown-notif,
.mh-dropdown-profile {
    border-radius: 14px !important;
    border: 1.5px solid #dde0dd !important;
    padding: 0 !important;
    overflow: hidden;
    font-family: 'DM Sans', system-ui, sans-serif;
}
.mh-dropdown-notif { width: 300px; }
.mh-dropdown-profile { min-width: 200px; }

/* Header dropdown */
.mh-dd-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px;
    background: var(--g50);
    border-bottom: 1.5px solid var(--g100);
}
.mh-dd-head--profile {
    gap: 10px; justify-content: flex-start;
    background: linear-gradient(135deg, var(--g50), #f8f9f8);
}
.mh-dd-title { font-size: .82rem; font-weight: 700; color: #252825; }
.mh-dd-badge {
    background: var(--hotel-primary, var(--g600)); color: white;
    font-size: .62rem; font-weight: 700;
    padding: 2px 7px; border-radius: 100px;
}
.mh-dd-user-name { font-size: .82rem; font-weight: 700; color: #252825; }
.mh-dd-user-role { font-size: .68rem; color: #9ba09b; }

/* Notif list */
.mh-notif-list { max-height: 220px; overflow-y: auto; }
.mh-notif-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 16px;
    text-decoration: none; color: #3a3e3a;
    border-bottom: 1px solid #eff0ef;
    transition: background .15s;
}
.mh-notif-item:hover { background: var(--g50); text-decoration: none; color: var(--hotel-primary, var(--g600)); }
.mh-notif-ico {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--g100); color: var(--hotel-primary, var(--g600));
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; flex-shrink: 0; margin-top: 1px;
}
.mh-notif-msg { font-size: .78rem; font-weight: 500; line-height: 1.3; }
.mh-notif-time { font-size: .65rem; color: #9ba09b; margin-top: 2px; }

.mh-notif-empty {
    display: flex; flex-direction: column; align-items: center;
    gap: 6px; padding: 24px 16px;
    font-size: .78rem; color: #9ba09b;
}
.mh-notif-empty i { font-size: 1.2rem; color: #c2c7c2; }

.mh-dd-footer {
    display: block; text-align: center;
    padding: 10px; font-size: .75rem; font-weight: 600;
    color: var(--hotel-primary, var(--g600)); text-decoration: none;
    background: #f8f9f8; border-top: 1.5px solid #eff0ef;
    transition: background .15s;
}
.mh-dd-footer:hover { background: var(--g50); text-decoration: none; }

/* Profile dropdown items */
.mh-dd-divider { height: 1px; background: #eff0ef; margin: 4px 0; }
.mh-dd-item {
    display: flex; align-items: center; gap: 9px;
    padding: 9px 16px; font-size: .8rem; font-weight: 500;
    color: #3a3e3a; text-decoration: none;
    transition: background .15s;
}
.mh-dd-item i { width: 14px; text-align: center; color: #9ba09b; font-size: .78rem; }
.mh-dd-item:hover { background: var(--g50); color: var(--hotel-primary, var(--g600)); text-decoration: none; }
.mh-dd-item:hover i { color: var(--hotel-primary, var(--g600)); }
.mh-dd-item--danger { color: #545954; }
.mh-dd-item--danger:hover { background: #f8f9f8; color: #3a3e3a; }

/* ════════════════════════════════════════
   RESPONSIVE · mobile header visible ≤ 768px
════════════════════════════════════════ */
@media (max-width: 768px) {
    .mobile-header { display: block; }
}

/* Sur très petit écran cacher le nom brand si pas de place */
@media (max-width: 360px) {
    .mh-brand-name { display: none; }
}
</style>