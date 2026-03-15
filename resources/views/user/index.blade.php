@extends('template.master')
@section('title', 'Gestion des Utilisateurs')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── Palette : 3 couleurs uniquement ── */
    /* VERT */
    --g50:  #f0faf0;
    --g100: #d4edda;
    --g200: #a8d5b5;
    --g300: #72bb82;
    --g400: #4a9e5c;
    --g500: #2e8540;
    --g600: #1e6b2e;
    --g700: #155221;
    --g800: #0d3a16;
    --g900: #072210;
    /* BLANC / SURFACE */
    --white:    #ffffff;
    --surface:  #f7f9f7;
    --surface2: #eef3ee;
    /* GRIS */
    --s50:  #f8f9f8;
    --s100: #eff0ef;
    --s200: #dde0dd;
    --s300: #c2c7c2;
    --s400: #9ba09b;
    --s500: #737873;
    --s600: #545954;
    --s700: #3a3e3a;
    --s800: #252825;
    --s900: #131513;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,.10), 0 4px 12px rgba(0,0,0,.05);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s cubic-bezier(.4,0,.2,1);
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.users-page {
    padding: 28px 32px 64px;
    background: var(--surface);
    min-height: 100vh;
    font-family: var(--font);
    color: var(--s800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(.96); }
    to   { opacity: 1; transform: scale(1); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }
.anim-6 { animation: fadeSlide .4s .40s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.users-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.users-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.users-breadcrumb a:hover { color: var(--g600); }
.users-breadcrumb .sep { color: var(--s300); }
.users-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.users-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.users-brand { display: flex; align-items: center; gap: 14px; }
.users-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.users-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.users-header-title em { font-style: normal; color: var(--g600); }
.users-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.users-header-sub i { color: var(--g500); }
.users-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   BOUTONS
══════════════════════════════════════════════ */
.btn-db {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--r);
    font-size: .8rem; font-weight: 500; border: none;
    cursor: pointer; transition: var(--transition);
    text-decoration: none; white-space: nowrap; line-height: 1;
    font-family: var(--font);
}
.btn-db-primary {
    background: var(--g600); color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white); color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--s50); border-color: var(--s300);
    color: var(--s900); text-decoration: none;
}

/* ══════════════════════════════════════════════
   STAT CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 14px; margin-bottom: 24px;
}
@media(max-width:1100px){ .stats-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:560px) { .stats-grid{ grid-template-columns:1fr; } }

.stat-card {
    background: var(--white); border-radius: var(--rl);
    padding: 22px 20px 18px;
    border: 1.5px solid var(--s100);
    text-decoration: none; display: block;
    position: relative; overflow: hidden;
    transition: var(--transition); box-shadow: var(--shadow-xs);
}
.stat-card:hover {
    transform: translateY(-3px); box-shadow: var(--shadow-md);
    border-color: var(--g200); text-decoration: none;
}
.stat-card::after {
    content: ''; position: absolute;
    bottom: 0; left: 0; right: 0; height: 3px;
    background: var(--bar-c, var(--g400));
    border-radius: 0 0 var(--rl) var(--rl);
}

.stat-card--total { --bar-c: var(--g500); }
.stat-card--users { --bar-c: var(--g600); }
.stat-card--customers { --bar-c: var(--g300); }
.stat-card--page { --bar-c: var(--s400); }

.stat-card-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
.stat-card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.stat-card--total .stat-card-icon { background: var(--g100); color: var(--g600); }
.stat-card--users .stat-card-icon { background: var(--g50); color: var(--g600); }
.stat-card--customers .stat-card-icon { background: var(--g50); color: var(--g500); }
.stat-card--page .stat-card-icon { background: var(--s100); color: var(--s500); }

.stat-card-value {
    font-size: 2.2rem; font-weight: 700; color: var(--s900);
    line-height: 1; letter-spacing: -1px; margin-bottom: 4px;
    font-family: var(--mono);
}
.stat-card-label { font-size: .8rem; color: var(--s400); margin-bottom: 4px; }
.stat-card-footer {
    display: flex; align-items: center; gap: 5px;
    font-size: .72rem; padding-top: 12px;
    border-top: 1px solid var(--s100); color: var(--s400);
}
.stat-card--total .stat-card-footer { color: var(--g600); }
.stat-card--users .stat-card-footer { color: var(--g600); }
.stat-card--customers .stat-card-footer { color: var(--g500); }

/* ══════════════════════════════════════════════
   ACTION BAR
══════════════════════════════════════════════ */
.action-bar {
    background: var(--white); border-radius: var(--rxl);
    padding: 16px 20px; margin-bottom: 24px;
    border: 1.5px solid var(--s100); box-shadow: var(--shadow-sm);
    display: flex; flex-wrap: wrap; align-items: center;
    justify-content: space-between; gap: 16px;
}
.action-left { display: flex; align-items: center; gap: 12px; }
.action-right { flex: 1; max-width: 400px; }

.filter-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 30px; font-size: .75rem;
    font-weight: 600; background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.badge-count {
    background: var(--white); padding: 2px 6px; border-radius: 20px;
    font-size: .65rem; font-weight: 600;
}

.search-container {
    position: relative; width: 100%;
}
.search-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%); color: var(--s400);
    font-size: .9rem; pointer-events: none; z-index: 2;
}
.search-input {
    width: 100%; padding: 10px 16px 10px 42px;
    border: 1.5px solid var(--s200); border-radius: var(--rl);
    font-size: .875rem; transition: var(--transition);
    background: var(--white); font-family: var(--font);
}
.search-input:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.users-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.users-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    display: flex; align-items: center; justify-content: space-between;
}
.users-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.users-card-title i { color: var(--g500); }
.users-card-badge {
    background: var(--g100); color: var(--g700);
    font-size: .7rem; font-weight: 600; padding: 4px 10px;
    border-radius: 100px;
}
.users-card-body { padding: 0; }

/* ══════════════════════════════════════════════
   TABLEAU
══════════════════════════════════════════════ */
.users-table {
    width: 100%; border-collapse: collapse;
}
.users-table thead th {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .7px; color: var(--s400);
    padding: 14px 20px; background: var(--surface);
    border-bottom: 1.5px solid var(--s100); white-space: nowrap;
}
.users-table tbody tr {
    border-bottom: 1px solid var(--s100); transition: var(--transition);
}
.users-table tbody tr:last-child { border-bottom: none; }
.users-table tbody tr:hover { background: var(--g50); }
.users-table td {
    padding: 16px 20px; vertical-align: middle;
}

/* User Avatar */
.user-avatar-cell {
    display: flex; align-items: center; gap: 12px;
}
.user-avatar {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; flex-shrink: 0;
}
.user-avatar.admin {
    background: var(--g100); color: var(--g600);
}
.user-avatar.staff {
    background: var(--g50); color: var(--g600);
}
.user-avatar.customer {
    background: var(--s100); color: var(--s600);
}

/* Role Badges */
.role-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 30px; font-size: .7rem;
    font-weight: 600;
}
.role-badge.admin {
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.role-badge.staff {
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200);
}
.role-badge.customer {
    background: var(--s100); color: var(--s600);
    border: 1px solid var(--s200);
}

/* ══════════════════════════════════════════════
   ACTION BUTTONS
══════════════════════════════════════════════ */
.action-group {
    display: flex; align-items: center; gap: 4px; justify-content: center;
}
.btn-db-icon {
    width: 32px; height: 32px; padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 6px; font-size: .75rem;
    background: var(--white); color: var(--s400);
    border: 1.5px solid var(--s200); cursor: pointer;
    transition: var(--transition); text-decoration: none;
    font-family: var(--font);
}
.btn-db-icon:hover {
    transform: translateY(-1px);
}
.btn-db-icon-view:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200);
}
.btn-db-icon-edit:hover {
    background: var(--g50); color: var(--g600);
    border-color: var(--g200);
}
.btn-db-icon-delete:hover {
    background: #fee2e2; color: #b91c1c;
    border-color: #fecaca;
}
.btn-db-icon.disabled {
    opacity: 0.3; cursor: not-allowed;
    pointer-events: none;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 64px 24px; text-align: center;
}
.empty-icon {
    width: 80px; height: 80px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 2rem; color: var(--g300);
    margin: 0 auto 20px; border: 2px solid var(--g100);
}
.empty-title {
    font-size: 1rem; font-weight: 600; color: var(--s700);
    margin-bottom: 8px;
}
.empty-text {
    font-size: .8rem; color: var(--s400);
    margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-custom {
    display: flex; gap: 4px; justify-content: center; padding: 20px;
}
.pagination-custom .page-item { list-style: none; }
.pagination-custom .page-link {
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 8px;
    border: 1.5px solid var(--s200); background: var(--white);
    color: var(--s600); font-size: .75rem; font-weight: 500;
    transition: var(--transition); text-decoration: none;
}
.pagination-custom .page-link:hover {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700); transform: translateY(-1px);
}
.pagination-custom .active .page-link {
    background: var(--g600); border-color: var(--g600);
    color: white;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .users-page{ padding: 20px; }
    .users-header{ flex-direction: column; align-items: flex-start; }
    .stats-grid{ grid-template-columns:1fr; }
    .action-bar{ flex-direction: column; align-items: stretch; }
    .action-right{ max-width: 100%; }
    .users-table{ display: block; overflow-x: auto; }
    .users-table td{ padding: 12px; }
}
</style>

<div class="users-page">
    <!-- Breadcrumb -->
    <div class="users-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Utilisateurs</span>
    </div>

    <!-- Header -->
    <div class="users-header anim-2">
        <div class="users-brand">
            <div class="users-brand-icon"><i class="fas fa-users-cog"></i></div>
            <div>
                <h1 class="users-header-title">Gestion des <em>utilisateurs</em></h1>
                <p class="users-header-sub">
                    <i class="fas fa-users me-1"></i> Gérez les utilisateurs et clients de l'application
                </p>
            </div>
        </div>
        <div class="users-header-actions">
            <a href="{{ route('user.create') }}" class="btn-db btn-db-primary">
                <i class="fas fa-plus-circle me-2"></i> Nouvel utilisateur
            </a>
        </div>
    </div>

    <!-- Statistics -->
    @php
        $totalUsers = $users->total();
        $totalCustomers = $customers->total();
        $adminCount = $users->where('role', 'Admin')->count();
        $receptionistCount = $users->where('role', 'Receptionist')->count();
        $housekeepingCount = $users->where('role', 'Housekeeping')->count();
    @endphp
    
    <div class="stats-grid anim-3">
        <div class="stat-card stat-card--total">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalUsers + $totalCustomers }}</div>
            <div class="stat-card-label">Utilisateurs totaux</div>
            <div class="stat-card-footer">
                <i class="fas fa-user"></i>
                Tous les comptes
            </div>
        </div>
        
        <div class="stat-card stat-card--users">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-user-tie"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalUsers }}</div>
            <div class="stat-card-label">Utilisateurs</div>
            <div class="stat-card-footer">
                <i class="fas fa-shield-alt"></i>
                {{ $adminCount }} admin · {{ $receptionistCount + $housekeepingCount }} staff
            </div>
        </div>
        
        <div class="stat-card stat-card--customers">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-user"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalCustomers }}</div>
            <div class="stat-card-label">Clients</div>
            <div class="stat-card-footer">
                <i class="fas fa-id-card"></i>
                Comptes clients
            </div>
        </div>
        
        <div class="stat-card stat-card--page">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-layer-group"></i></div>
            </div>
            <div class="stat-card-value">{{ $users->currentPage() }}</div>
            <div class="stat-card-label">Page actuelle</div>
            <div class="stat-card-footer">
                <i class="fas fa-list"></i>
                {{ $users->perPage() }} par page
            </div>
        </div>
    </div>

    <!-- Action Bar (Users) -->
    <div class="action-bar anim-4">
        <div class="action-left">
            <span class="filter-badge">
                <i class="fas fa-users"></i>
                Tous
                <span class="badge-count">{{ $totalUsers + $totalCustomers }}</span>
            </span>
        </div>
        
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <form method="GET" action="{{ route('user.index') }}">
                    <input type="hidden" name="qc" value="{{ request()->input('qc') }}">
                    <input type="text" 
                           class="search-input" 
                           placeholder="Rechercher un utilisateur..." 
                           name="qu" 
                           value="{{ request()->input('qu') }}"
                           autocomplete="off">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Users Column -->
        <div class="col-lg-6">
            <div class="users-card anim-5">
                <div class="users-card-header">
                    <h5 class="users-card-title">
                        <i class="fas fa-user-tie"></i>
                        Utilisateurs
                    </h5>
                    <span class="users-card-badge">
                        <i class="fas fa-users"></i>
                        {{ $users->total() }} enregistrés
                    </span>
                </div>
                
                <div class="users-card-body">
                    @if($users->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        @php
                                            $userType = $user->role == 'Admin' ? 'admin' : 'staff';
                                            $avatarIcon = $user->role == 'Admin' ? 'crown' : ($user->role == 'Receptionist' ? 'headset' : 'broom');
                                            $roleIcon = $user->role == 'Admin' ? 'shield-alt' : 'id-badge';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span style="font-weight: 600; color: var(--g600);">
                                                    {{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="user-avatar-cell">
                                                    <div class="user-avatar {{ $userType }}">
                                                        <i class="fas fa-{{ $avatarIcon }}"></i>
                                                    </div>
                                                    <span style="font-weight: 500; color:var(--s800);">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td style="color:var(--s600);">{{ $user->email }}</td>
                                            <td>
                                                <span class="role-badge {{ $userType }}">
                                                    <i class="fas fa-{{ $roleIcon }}"></i>
                                                    {{ $user->role }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-group">
                                                    <a href="{{ route('user.show', ['user' => $user->id]) }}" 
                                                       class="btn-db-icon btn-db-icon-view"
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" 
                                                       class="btn-db-icon btn-db-icon-edit"
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form class="d-inline" method="POST"
                                                          id="delete-post-form-{{ $user->id }}"
                                                          action="{{ route('user.destroy', ['user' => $user->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="btn-db-icon btn-db-icon-delete"
                                                                onclick="confirmDelete('{{ $user->name }}', {{ $user->id }}, '{{ $user->role }}')"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($users->hasPages())
                        <div class="pagination-custom">
                            {{ $users->onEachSide(1)->appends(['customers' => $customers->currentPage(), 'qc' => request()->input('qc')])->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-user-tie"></i></div>
                            <p class="empty-title">Aucun utilisateur</p>
                            <p class="empty-text">Commencez par ajouter votre premier utilisateur.</p>
                            <a href="{{ route('user.create') }}" class="btn-db btn-db-primary">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Customers Column -->
        <div class="col-lg-6">
            <!-- Customer Search Bar -->
            <div class="action-bar anim-4" style="margin-bottom: 16px;">
                <div class="action-left">
                    <span class="filter-badge">
                        <i class="fas fa-user"></i>
                        Clients
                        <span class="badge-count">{{ $customers->total() }}</span>
                    </span>
                </div>
                
                <div class="action-right">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <form method="GET" action="{{ route('user.index') }}">
                            <input type="hidden" name="qu" value="{{ request()->input('qu') }}">
                            <input type="text" 
                                   class="search-input" 
                                   placeholder="Rechercher un client..." 
                                   name="qc" 
                                   value="{{ request()->input('qc') }}"
                                   autocomplete="off">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="users-card anim-5">
                <div class="users-card-header">
                    <h5 class="users-card-title">
                        <i class="fas fa-user"></i>
                        Clients
                    </h5>
                    <span class="users-card-badge">
                        <i class="fas fa-users"></i>
                        {{ $customers->total() }} enregistrés
                    </span>
                </div>
                
                <div class="users-card-body">
                    @if($customers->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $user)
                                        <tr>
                                            <td>
                                                <span style="font-weight: 600; color: var(--g500);">
                                                    {{ ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="user-avatar-cell">
                                                    <div class="user-avatar customer">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <span style="font-weight: 500; color:var(--s800);">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td style="color:var(--s600);">{{ $user->email }}</td>
                                            <td>
                                                <span class="role-badge customer">
                                                    <i class="fas fa-user"></i>
                                                    Customer
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-group">
                                                    <span class="btn-db-icon disabled" 
                                                          title="Détails non disponibles">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                    
                                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" 
                                                       class="btn-db-icon btn-db-icon-edit"
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form class="d-inline" method="POST"
                                                          id="delete-post-form-customer-{{ $user->id }}"
                                                          action="{{ route('user.destroy', ['user' => $user->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="btn-db-icon btn-db-icon-delete"
                                                                onclick="confirmDelete('{{ $user->name }}', {{ $user->id }}, 'Customer')"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($customers->hasPages())
                        <div class="pagination-custom">
                            {{ $customers->onEachSide(1)->appends(['users' => $users->currentPage(), 'qu' => request()->input('qu')])->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-user"></i></div>
                            <p class="empty-title">Aucun client</p>
                            <p class="empty-text">Aucun client enregistré pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Search debounce
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(input => {
        let searchTimeout;
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    });
});

function confirmDelete(name, id, role) {
    Swal.fire({
        title: 'Confirmer la suppression',
        html: `<strong>${name}</strong> (${role}) sera supprimé définitivement.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Oui, supprimer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        reverseButtons: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#737873'
    }).then((result) => {
        if (result.isConfirmed) {
            if (role == "Customer") {
                document.getElementById('delete-post-form-customer-' + id).submit();
            } else {
                document.getElementById('delete-post-form-' + id).submit();
            }
        }
    });
}
</script>
@endsection