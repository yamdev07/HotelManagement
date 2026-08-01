@extends('template.master')

@section('title', 'Profil Utilisateur - ' . $user->name)

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

.profile-page {
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
.profile-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.profile-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.profile-breadcrumb a:hover { color: var(--g600); }
.profile-breadcrumb .sep { color: var(--s300); }
.profile-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   CARTE PROFIL
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 24px; box-shadow: var(--shadow-sm);
}

.profile-header {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    padding: 3rem 2rem;
    color: white;
    text-align: center;
    position: relative;
}

.profile-avatar {
    width: 150px; height: 150px;
    border-radius: 50%; border: 5px solid white;
    object-fit: cover; margin: 0 auto 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: transform .3s ease;
}
.profile-avatar:hover { transform: scale(1.05); }

/* ══════════════════════════════════════════════
   BADGES DE RÔLE
══════════════════════════════════════════════ */
.role-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 20px; border-radius: 30px; font-size: .9rem;
    font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
    color: white;
}
.badge-super { background: var(--g600); }
.badge-admin { background: var(--g500); }
.badge-receptionist { background: var(--g400); }
.badge-customer { background: var(--g300); color: var(--s800); }
.badge-housekeeping { background: var(--g200); color: var(--s800); }

/* ══════════════════════════════════════════════
   CARTES D'INFORMATION
══════════════════════════════════════════════ */
.info-card {
    background: var(--white); border-radius: var(--rl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.info-card:hover {
    box-shadow: var(--shadow-md); transform: translateY(-2px);
}
.info-card-body { padding: 20px; }

.info-icon {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; margin-right: 15px;
    background: var(--g50); color: var(--g600);
}

/* ══════════════════════════════════════════════
   ACTIVITÉS
══════════════════════════════════════════════ */
.activity-item {
    padding: 12px 16px; border-left: 3px solid transparent;
    transition: var(--transition); border-radius: var(--r);
}
.activity-item:hover {
    background: var(--g50); border-left-color: var(--g500);
}

/* ══════════════════════════════════════════════
   STATISTIQUES
══════════════════════════════════════════════ */
.stat-item {
    text-align: center; padding: 16px;
    border-radius: var(--rl); background: var(--white);
    border: 1.5px solid var(--s100); box-shadow: var(--shadow-sm);
}
.stat-number {
    font-size: 2.2rem; font-weight: 700; line-height: 1;
    margin-bottom: .5rem; color: var(--s800); font-family: var(--mono);
}
.stat-label {
    color: var(--s400); font-size: .7rem; text-transform: uppercase;
    letter-spacing: .5px;
}

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
    box-shadow: 0 2px 10px rgb(from var(--g500) r g b / .3);
}
.btn-db-primary:hover {
    background: var(--g700); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgb(from var(--g500) r g b / .35);
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
.btn-db-outline-primary {
    background: transparent; color: var(--g600);
    border: 1.5px solid var(--g200);
}
.btn-db-outline-primary:hover {
    background: var(--g50); color: var(--g700);
    border-color: var(--g300); transform: translateY(-1px);
}
.btn-db-outline-warning {
    background: transparent; color: #856404;
    border: 1.5px solid #ffeeba;
}
.btn-db-outline-warning:hover {
    background: #fff3cd; color: #856404;
    border-color: #ffc107; transform: translateY(-1px);
}
.btn-db-outline-info {
    background: transparent; color: var(--g600);
    border: 1.5px solid var(--g200);
}
.btn-db-outline-info:hover {
    background: var(--g50); color: var(--g700);
    border-color: var(--g300); transform: translateY(-1px);
}
.btn-db-outline-danger {
    background: transparent; color: #b91c1c;
    border: 1.5px solid #fecaca;
}
.btn-db-outline-danger:hover {
    background: #fee2e2; color: #b91c1c;
    border-color: #fecaca; transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge-db {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 20px; font-size: .7rem;
    font-weight: 600;
}
.badge-db-success {
    background: var(--g100); color: var(--g700);
    border: 1px solid var(--g200);
}
.badge-db-warning {
    background: #fff3cd; color: #856404;
    border: 1px solid #ffeeba;
}
.badge-db-danger {
    background: #fee2e2; color: #b91c1c;
    border: 1px solid #fecaca;
}
.badge-db-info {
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200);
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-db {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    border: 1.5px solid transparent; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-db-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-db-primary {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-db-info {
    background: var(--g50); border-color: var(--g200);
    color: var(--g600);
}
.alert-db-warning {
    background: #fff3cd; border-color: #ffeeba;
    color: #856404;
}
.alert-db-success {
    background: var(--g100); border-color: var(--g200);
    color: var(--g700);
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-db .modal-content {
    border-radius: var(--rxl); border: 1.5px solid var(--s200);
    overflow: hidden; box-shadow: var(--shadow-lg);
}
.modal-db .modal-header {
    background: var(--surface); border-bottom: 1.5px solid var(--s100);
    padding: 16px 20px;
}
.modal-db .modal-title {
    font-size: .95rem; font-weight: 600; color: var(--s800);
    display: flex; align-items: center; gap: 8px;
}
.modal-db .modal-title i { color: var(--g500); }
.modal-db .modal-body { padding: 20px; }
.modal-db .modal-footer {
    background: var(--surface); border-top: 1.5px solid var(--s100);
    padding: 16px 20px;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .profile-page{ padding: 16px; }
    .profile-header{ padding: 2rem 1rem; }
    .profile-avatar{ width: 100px; height: 100px; }
    .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="profile-page">
    <!-- Breadcrumb -->
    <div class="profile-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('user.index') }}">Utilisateurs</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ $user->name }}</span>
    </div>

    <!-- Carte Profil -->
    <div class="profile-card anim-2">
        <div class="profile-header">
            <img src="{{ $user->getAvatar() }}" 
                 class="profile-avatar" 
                 alt="{{ $user->name }}"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e6b2e&color=fff&size=150'">
            
            <h2 class="mb-2" style="color:white;">{{ $user->name }}</h2>
            
            @switch($user->role)
                @case('Super')
                    <span class="role-badge badge-super">
                        <i class="fas fa-crown me-1"></i>Super Admin
                    </span>
                    @break
                @case('Admin')
                    <span class="role-badge badge-admin">
                        <i class="fas fa-user-shield me-1"></i>Administrateur
                    </span>
                    @break
                @case('Receptionist')
                    <span class="role-badge badge-receptionist">
                        <i class="fas fa-concierge-bell me-1"></i>Réceptionniste
                    </span>
                    @break
                @case('Housekeeping')
                    <span class="role-badge badge-housekeeping">
                        <i class="fas fa-broom me-1"></i>Housekeeping
                    </span>
                    @break
                @default
                    <span class="role-badge badge-customer">
                        <i class="fas fa-user me-1"></i>{{ $user->role }}
                    </span>
            @endswitch
            
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.8);">
                <i class="fas fa-user-circle me-1"></i> ID: #{{ $user->id }}
                • Membre depuis {{ $user->created_at->format('d/m/Y') }}
            </p>
        </div>
        
        <div class="info-card-body">
            <div class="row g-4">
                <!-- Colonne gauche -->
                <div class="col-lg-8">
                    <!-- Informations personnelles -->
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-4" style="color:var(--s800); font-size:.95rem;">
                                <i class="fas fa-id-card me-2" style="color:var(--g500);"></i>
                                Informations Personnelles
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block" style="color:var(--s400);">Email</small>
                                            <strong style="color:var(--s800);">{{ $user->email }}</strong>
                                            @if($user->email_verified_at)
                                            <span class="badge-db badge-db-success ms-2">
                                                <i class="fas fa-check-circle"></i> Vérifié
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block" style="color:var(--s400);">Téléphone</small>
                                            <strong style="color:var(--s800);">{{ $user->phone ?? 'Non renseigné' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($user->address)
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="info-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block" style="color:var(--s400);">Adresse</small>
                                            <strong style="color:var(--s800);">{{ $user->address }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-4 pt-3" style="border-top:1px solid var(--s100);">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="mailto:{{ $user->email }}" class="btn-db btn-db-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>Envoyer un email
                                    </a>
                                    
                                    @if(auth()->user()->role === 'Super' && auth()->user()->id !== $user->id)
                                    <a href="{{ route('user.edit', $user) }}" class="btn-db btn-db-outline-warning">
                                        <i class="fas fa-edit me-2"></i>Modifier
                                    </a>
                                    @endif
                                    
                                    @if(auth()->user()->id === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn-db btn-db-outline-info">
                                        <i class="fas fa-user-edit me-2"></i>Mon profil
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiques client -->
                    @if($user->role === 'Customer' && isset($user->customer))
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-4" style="color:var(--s800); font-size:.95rem;">
                                <i class="fas fa-chart-line me-2" style="color:var(--g500);"></i>
                                Statistiques Client
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <div class="stat-item">
                                        <div class="stat-number" style="color:var(--g600);">
                                            {{ $user->customer->transactions->count() ?? 0 }}
                                        </div>
                                        <div class="stat-label">Réservations</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="stat-item">
                                        <div class="stat-number" style="color:var(--g600);">
                                            {{ number_format($user->customer->transactions->sum('total_price') ?? 0, 0) }}
                                        </div>
                                        <div class="stat-label">FCFA dépensés</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="stat-item">
                                        <div class="stat-number" style="color:var(--g500);">
                                            {{ $user->customer->transactions->where('status', 'active')->count() ?? 0 }}
                                        </div>
                                        <div class="stat-label">Séjours actifs</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="stat-item">
                                        <div class="stat-number" style="color:var(--g400);">
                                            {{ $user->customer->transactions->where('status', 'completed')->count() ?? 0 }}
                                        </div>
                                        <div class="stat-label">Séjours terminés</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Dernières activités -->
                    @if($user->activities && $user->activities->count() > 0)
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-4" style="color:var(--s800); font-size:.95rem;">
                                <i class="fas fa-history me-2" style="color:var(--g500);"></i>
                                Dernières Activités
                            </h5>
                            
                            <div class="activity-list">
                                @foreach($user->activities->take(5) as $activity)
                                <div class="activity-item mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-circle text-success me-2" style="font-size:0.5rem; color:var(--g500);"></i>
                                            <span class="fw-medium" style="color:var(--s800);">{{ $activity->description }}</span>
                                        </div>
                                        <small class="text-muted" style="color:var(--s400);">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($activity->properties && !empty($activity->properties))
                                    <small class="text-muted ms-3 d-block mt-1" style="color:var(--s400);">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ json_encode($activity->properties) }}
                                    </small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Colonne droite -->
                <div class="col-lg-4">
                    <!-- Statut du compte -->
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-3" style="color:var(--s800); font-size:.9rem;">
                                <i class="fas fa-user-check me-2" style="color:var(--g500);"></i>
                                Statut du Compte
                            </h5>
                            
                            <div style="display:flex; flex-direction:column; gap:12px;">
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--s600);">Statut:</span>
                                    <span>
                                        @if($user->is_active)
                                        <span class="badge-db badge-db-success">
                                            <i class="fas fa-check-circle me-1"></i>Actif
                                        </span>
                                        @else
                                        <span class="badge-db badge-db-danger">
                                            <i class="fas fa-times-circle me-1"></i>Inactif
                                        </span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--s600);">Email vérifié:</span>
                                    <span>
                                        @if($user->email_verified_at)
                                        <span class="badge-db badge-db-success">Oui</span>
                                        @else
                                        <span class="badge-db badge-db-warning">Non</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--s600);">Dernière connexion:</span>
                                    <span style="color:var(--s800);">
                                        @if($user->last_login_at)
                                        <small>{{ $user->last_login_at->diffForHumans() }}</small>
                                        @else
                                        <small class="text-muted">Jamais</small>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <span style="color:var(--s600);">Créé le:</span>
                                    <small style="color:var(--s800);">{{ $user->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions -->
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-3" style="color:var(--s800); font-size:.9rem;">
                                <i class="fas fa-shield-alt me-2" style="color:var(--g500);"></i>
                                Permissions
                            </h5>
                            
                            @switch($user->role)
                                @case('Super')
                                    <div class="alert-db alert-db-danger">
                                        <i class="fas fa-crown"></i>
                                        <div>
                                            <strong>Super Admin</strong>
                                            <p class="mb-0 small mt-1">Accès complet à toutes les fonctionnalités</p>
                                        </div>
                                    </div>
                                    @break
                                @case('Admin')
                                    <div class="alert-db alert-db-primary">
                                        <i class="fas fa-user-shield"></i>
                                        <div>
                                            <strong>Administrateur</strong>
                                            <p class="mb-0 small mt-1">Gestion complète avec restrictions mineures</p>
                                        </div>
                                    </div>
                                    @break
                                @case('Receptionist')
                                    <div class="alert-db alert-db-info">
                                        <i class="fas fa-concierge-bell"></i>
                                        <div>
                                            <strong>Réceptionniste</strong>
                                            <p class="mb-0 small mt-1">Accès limité aux opérations de réception</p>
                                        </div>
                                    </div>
                                    @break
                                @case('Housekeeping')
                                    <div class="alert-db alert-db-warning">
                                        <i class="fas fa-broom"></i>
                                        <div>
                                            <strong>Housekeeping</strong>
                                            <p class="mb-0 small mt-1">Accès aux fonctions de nettoyage uniquement</p>
                                        </div>
                                    </div>
                                    @break
                                @default
                                    <div class="alert-db alert-db-success">
                                        <i class="fas fa-user"></i>
                                        <div>
                                            <strong>Client</strong>
                                            <p class="mb-0 small mt-1">Accès à ses propres réservations seulement</p>
                                        </div>
                                    </div>
                            @endswitch
                        </div>
                    </div>
                    
                    <!-- Actions rapides (Super seulement) -->
                    @if(auth()->user()->role === 'Super' && auth()->user()->id !== $user->id)
                    <div class="info-card">
                        <div class="info-card-body">
                            <h5 class="card-title mb-3" style="color:var(--s800); font-size:.9rem;">
                                <i class="fas fa-bolt me-2" style="color:var(--g500);"></i>
                                Actions Rapides
                            </h5>
                            
                            <div class="d-grid gap-2">
                                <button class="btn-db btn-db-outline-danger w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#resetPasswordModal">
                                    <i class="fas fa-key me-2"></i>Réinitialiser le mot de passe
                                </button>
                                
                                <button class="btn-db btn-db-outline-warning w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#toggleStatusModal">
                                    @if($user->is_active)
                                    <i class="fas fa-user-slash me-2"></i>Désactiver le compte
                                    @else
                                    <i class="fas fa-user-check me-2"></i>Activer le compte
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de réinitialisation de mot de passe -->
@if(auth()->user()->role === 'Super' && auth()->user()->id !== $user->id)
<div class="modal fade modal-db" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>Réinitialiser le mot de passe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir réinitialiser le mot de passe de <strong>{{ $user->name }}</strong> ?</p>
                <div class="alert-db alert-db-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Un email sera envoyé à {{ $user->email }} avec les instructions de réinitialisation.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-db btn-db-ghost" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('user.password.reset', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-db btn-db-outline-danger">
                        <i class="fas fa-key me-1"></i>Réinitialiser
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'activation/désactivation -->
<div class="modal fade modal-db" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($user->is_active)
                    <i class="fas fa-user-slash me-2"></i>Désactiver le compte
                    @else
                    <i class="fas fa-user-check me-2"></i>Activer le compte
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($user->is_active)
                <p>Êtes-vous sûr de vouloir désactiver le compte de <strong>{{ $user->name }}</strong> ?</p>
                <div class="alert-db alert-db-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    L'utilisateur ne pourra plus se connecter jusqu'à réactivation.
                </div>
                @else
                <p>Êtes-vous sûr de vouloir activer le compte de <strong>{{ $user->name }}</strong> ?</p>
                <div class="alert-db alert-db-success">
                    <i class="fas fa-check-circle me-2"></i>
                    L'utilisateur pourra à nouveau se connecter.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-db btn-db-ghost" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('user.toggle.status', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-db btn-db-outline-warning">
                        @if($user->is_active)
                        <i class="fas fa-user-slash me-1"></i>Désactiver
                        @else
                        <i class="fas fa-user-check me-1"></i>Activer
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Confirmation avant action
    const actionButtons = document.querySelectorAll('.btn-db-outline-danger, .btn-db-outline-warning');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (button.__ok) { button.__ok = false; return; }
            e.preventDefault();
            window.confirmAction('Êtes-vous sûr de vouloir effectuer cette action ?', function () {
                button.__ok = true;
                if (button.tagName === 'A' && button.getAttribute('href')) { window.location.href = button.href; }
                else if (button.form) { button.form.submit(); }
                else { button.click(); }
            }, { danger: true });
        });
    });
});
</script>
@endsection