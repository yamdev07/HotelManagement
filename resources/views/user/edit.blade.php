@extends('template.master')
@section('title', 'Modifier Utilisateur')
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

.edit-user-page {
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
.edit-user-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.edit-user-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.edit-user-breadcrumb a:hover { color: var(--g600); }
.edit-user-breadcrumb .sep { color: var(--s300); }
.edit-user-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.edit-user-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.edit-user-brand { display: flex; align-items: center; gap: 14px; }
.edit-user-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.edit-user-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.edit-user-header-title em { font-style: normal; color: var(--g600); }
.edit-user-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.edit-user-header-sub i { color: var(--g500); }
.edit-user-header-actions { display: flex; align-items: center; gap: 10px; }

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
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.edit-user-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.edit-user-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.edit-user-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 1rem; font-weight: 600; color: var(--s800); margin: 0;
}
.edit-user-card-title i { color: var(--g500); }
.edit-user-card-body { padding: 24px; }

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-db {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-db-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-db ul {
    margin: 8px 0 0 20px; padding: 0;
}

/* ══════════════════════════════════════════════
   FORMULAIRE
══════════════════════════════════════════════ */
.form-grid {
    display: grid; grid-template-columns: repeat(1, 1fr);
    gap: 20px;
}

.form-group {
    display: flex; flex-direction: column;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-label .required {
    color: #b91c1c; font-size: .7rem; margin-left: 4px;
}
.form-control, .form-select {
    padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white); width: 100%;
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: #b91c1c;
    background: #fee2e2;
}
.text-danger {
    display: flex; align-items: center; gap: 4px;
    font-size: .7rem; color: #b91c1c; margin-top: 4px;
}
.text-danger i { font-size: .65rem; }

/* ══════════════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════════════ */
.actions-bar {
    padding-top: 24px; margin-top: 24px;
    border-top: 1.5px solid var(--s100);
    display: flex; gap: 12px; justify-content: flex-end;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .edit-user-page{ padding: 20px; }
    .edit-user-header{ flex-direction: column; align-items: flex-start; }
    .edit-user-card-body{ padding: 20px; }
    .actions-bar{ flex-direction: column; }
    .actions-bar .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="edit-user-page">
    <!-- Breadcrumb -->
    <div class="edit-user-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('user.index') }}">Utilisateurs</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Modifier</span>
    </div>

    <!-- Header -->
    <div class="edit-user-header anim-2">
        <div class="edit-user-brand">
            <div class="edit-user-brand-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <h1 class="edit-user-header-title">Modifier <em>l'utilisateur</em></h1>
                <p class="edit-user-header-sub">
                    <i class="fas fa-user me-1"></i> {{ $user->name }} · Mise à jour des informations
                </p>
            </div>
        </div>
        <div class="edit-user-header-actions">
            <a href="{{ route('user.show', $user->id) }}" class="btn-db btn-db-ghost">
                <i class="fas fa-eye me-2"></i> Voir le profil
            </a>
        </div>
    </div>

    <!-- Alertes d'erreurs -->
    @if ($errors->any())
        <div class="alert-db alert-db-danger anim-2">
            <i class="fas fa-exclamation-circle" style="font-size:1.2rem;"></i>
            <div style="flex:1">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="btn-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Formulaire -->
    <div class="edit-user-card anim-3">
        <div class="edit-user-card-header">
            <h5 class="edit-user-card-title">
                <i class="fas fa-info-circle"></i>
                Informations de l'utilisateur
            </h5>
        </div>
        <div class="edit-user-card-body">
            <form method="POST" action="{{ route('user.update', ['user' => $user->id]) }}">
                @method('PUT')
                @csrf
                
                <div class="form-grid">
                    <!-- Nom -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            Nom complet <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ $user->name }}"
                               placeholder="Nom de l'utilisateur">
                        @error('name')
                            <div class="text-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ $user->email }}"
                               placeholder="email@exemple.com">
                        @error('email')
                            <div class="text-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Rôle -->
                    <div class="form-group">
                        <label for="role" class="form-label">
                            <i class="fas fa-shield-alt"></i>
                            Rôle <span class="required">*</span>
                        </label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
                            <option selected disabled hidden>-- Sélectionner un rôle --</option>
                            @if (in_array($user->role, ['Super', 'Admin']))
                                <option value="Super" @if ($user->role == 'Super') selected @endif>Super Admin</option>
                                <option value="Admin" @if ($user->role == 'Admin') selected @endif>Administrateur</option>
                            @endif
                            @if ($user->role == 'Customer')
                                <option value="Customer" @if ($user->role == 'Customer') selected @endif>Client</option>
                            @endif
                            @if ($user->role == 'Receptionist')
                                <option value="Receptionist" @if ($user->role == 'Receptionist') selected @endif>Réceptionniste</option>
                            @endif
                            @if ($user->role == 'Housekeeping')
                                <option value="Housekeeping" @if ($user->role == 'Housekeeping') selected @endif>Housekeeping</option>
                            @endif
                            @if ($user->role == 'Servant')
                                <option value="Servant" @if ($user->role == 'Servant') selected @endif>Serveur</option>
                            @endif
                            @if ($user->role == 'Cuisiner')
                                <option value="Cuisiner" @if ($user->role == 'Cuisiner') selected @endif>Cuisinier</option>
                            @endif
                        </select>
                        @error('role')
                            <div class="text-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="form-hint" style="font-size:.65rem; color:var(--s400); margin-top:4px;">
                            <i class="fas fa-info-circle"></i> Le rôle détermine les permissions de l'utilisateur
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="actions-bar">
                    <a href="{{ route('user.index') }}" class="btn-db btn-db-ghost">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn-db btn-db-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection