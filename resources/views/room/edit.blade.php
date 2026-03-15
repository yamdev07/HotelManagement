@extends('template.master')

@section('title', 'Modifier la Chambre')

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

.edit-page {
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
.edit-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.edit-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.edit-breadcrumb a:hover { color: var(--g600); }
.edit-breadcrumb .sep { color: var(--s300); }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.edit-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.edit-brand { display: flex; align-items: center; gap: 14px; }
.edit-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.edit-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.edit-header-title em { font-style: normal; color: var(--g600); }
.edit-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.edit-header-sub i { color: var(--g500); }
.edit-header-actions { display: flex; align-items: center; gap: 10px; }

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
.btn-db-info {
    background: var(--g50); color: var(--g600);
    border: 1.5px solid var(--g200);
}
.btn-db-info:hover {
    background: var(--g100); color: var(--g700);
    border-color: var(--g300); transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert-modern {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: var(--rl);
    margin-bottom: 20px; border: 1.5px solid transparent;
    font-size: .875rem; background: var(--white);
    box-shadow: var(--shadow-sm);
}
.alert-success {
    background: var(--g50); border-color: var(--g200);
    color: var(--g700);
}
.alert-danger {
    background: #fee2e2; border-color: #fecaca;
    color: #b91c1c;
}
.alert-info {
    background: var(--g50); border-color: var(--g200);
    color: var(--g600);
}
.alert-icon {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.alert-success .alert-icon { background: var(--g100); color: var(--g600); }
.alert-danger .alert-icon { background: #fecaca; color: #b91c1c; }
.alert-info .alert-icon { background: var(--g100); color: var(--g600); }
.alert-close {
    margin-left: auto; background: none; border: none;
    color: currentColor; opacity: .6; cursor: pointer;
    font-size: 1rem; transition: var(--transition);
}
.alert-close:hover { opacity: 1; }
.alert ul {
    margin: 8px 0 0 20px; padding: 0;
}

/* ══════════════════════════════════════════════
   CARTE PRINCIPALE
══════════════════════════════════════════════ */
.edit-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
}
.edit-card-header {
    padding: 18px 24px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.edit-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: .95rem; font-weight: 600; color: var(--s800); margin: 0;
}
.edit-card-title i { color: var(--g500); }
.edit-card-body { padding: 28px; }

/* ══════════════════════════════════════════════
   FORMULAIRE
══════════════════════════════════════════════ */
.form-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}
@media(max-width:768px){ .form-grid{ grid-template-columns:1fr; } }

.form-group {
    display: flex; flex-direction: column;
}
.form-label {
    font-size: .75rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.form-label i { font-size: .7rem; color: var(--g500); }
.form-label .optional {
    font-size: .65rem; font-weight: 400; color: var(--s400);
    margin-left: 4px;
}
.form-control, .form-select {
    padding: 10px 14px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .875rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.form-control:focus, .form-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-hint {
    font-size: .65rem; color: var(--s400); margin-top: 4px;
}
.input-group {
    display: flex;
}
.input-group-text {
    padding: 10px 14px; background: var(--s100);
    border: 1.5px solid var(--s200); border-right: none;
    border-radius: var(--r) 0 0 var(--r); font-size: .75rem;
    font-weight: 600; color: var(--s600);
}
.input-group .form-control {
    border-radius: 0 var(--r) var(--r) 0;
}
.invalid-feedback {
    display: flex; align-items: center; gap: 4px;
    font-size: .7rem; color: #b91c1c; margin-top: 4px;
}

/* ══════════════════════════════════════════════
   STATUS BOX
══════════════════════════════════════════════ */
.status-box {
    background: var(--surface); border: 1.5px solid var(--s100);
    border-radius: var(--rl); padding: 16px;
}
.status-display {
    display: flex; align-items: center; gap: 12px;
}
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: var(--r); font-size: .8rem;
    font-weight: 600; flex-shrink: 0;
}
.status-badge--success { background: var(--g100); color: var(--g700); }
.status-badge--warning { background: #fff3cd; color: #856404; }
.status-badge--danger  { background: #fee2e2; color: #b91c1c; }
.status-badge--gray    { background: var(--s100); color: var(--s600); }
.status-info { flex: 1; }
.status-meta {
    font-size: .7rem; color: var(--s400); margin-top: 4px;
}
.status-detail {
    font-size: .7rem; margin-top: 4px;
    display: flex; align-items: center; gap: 4px;
    color: var(--s500);
}
.status-detail i { font-size: .6rem; }

/* ══════════════════════════════════════════════
   INFO BOX
══════════════════════════════════════════════ */
.info-box {
    background: var(--g50); border: 1.5px solid var(--g200);
    border-radius: var(--r); padding: 12px; margin-top: 12px;
    display: flex; gap: 10px;
}
.info-box i {
    color: var(--g600); flex-shrink: 0; margin-top: 2px;
}
.info-box__content {
    font-size: .7rem; color: var(--s600);
}
.info-box__content strong {
    display: block; margin-bottom: 2px; color: var(--s800);
}

/* ══════════════════════════════════════════════
   META CARD
══════════════════════════════════════════════ */
.meta-card {
    background: var(--surface); border: 1.5px solid var(--s100);
    border-radius: var(--rl); padding: 16px;
}
.meta-title {
    font-size: .7rem; font-weight: 600; color: var(--s400);
    margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
    text-transform: uppercase; letter-spacing: .5px;
}
.meta-title i { font-size: .65rem; color: var(--g500); }
.meta-row {
    font-size: .7rem; color: var(--s600); margin-bottom: 4px;
}

/* ══════════════════════════════════════════════
   ACTIONS BAR
══════════════════════════════════════════════ */
.actions-bar {
    padding-top: 24px; margin-top: 24px;
    border-top: 1.5px solid var(--s100);
    display: flex; justify-content: space-between; align-items: center;
}
.actions-group {
    display: flex; gap: 8px;
}

/* ══════════════════════════════════════════════
   FULL WIDTH
══════════════════════════════════════════════ */
.full-width {
    grid-column: 1 / -1;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .edit-page{ padding: 20px; }
    .edit-header{ flex-direction: column; align-items: flex-start; }
    .edit-card-body{ padding: 20px; }
    .actions-bar{ flex-direction: column; gap: 12px; }
    .actions-group{ width: 100%; flex-direction: column; }
    .btn-db{ width: 100%; justify-content: center; }
}
</style>

<div class="edit-page">
    <!-- Breadcrumb -->
    <div class="edit-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('room.index') }}">Chambres</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('room.show', $room->id) }}">Chambre {{ $room->number }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Modifier</span>
    </div>

    <!-- Header -->
    <div class="edit-header anim-2">
        <div class="edit-brand">
            <div class="edit-brand-icon"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="edit-header-title">Modifier la <em>chambre</em></h1>
                <p class="edit-header-sub">
                    <i class="fas fa-door-open me-1"></i> Chambre {{ $room->number }} · {{ $room->name ?? 'Sans nom' }}
                </p>
            </div>
        </div>
        <div class="edit-header-actions">
            <a href="{{ route('room.show', $room->id) }}" class="btn-db btn-db-ghost">
                <i class="fas fa-eye me-2"></i> Voir
            </a>
        </div>
    </div>

    <!-- Alertes -->
    @if($errors->any())
    <div class="alert-modern alert-danger anim-2">
        <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div style="flex:1">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert-modern alert-success anim-2">
        <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
        <span>{{ session('success') }}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert-modern alert-info anim-2">
        <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
        <span>{!! session('info') !!}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    <!-- Formulaire -->
    <div class="edit-card anim-3">
        <div class="edit-card-header">
            <h5 class="edit-card-title">
                <i class="fas fa-info-circle"></i>
                Informations de la chambre
            </h5>
        </div>
        <div class="edit-card-body">
            <form method="POST" action="{{ route('room.update', $room->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    
                    <!-- Numéro de chambre -->
                    <div class="form-group">
                        <label for="number" class="form-label">
                            <i class="fas fa-hashtag"></i>
                            Numéro de chambre *
                        </label>
                        <input type="text" 
                               class="form-control @error('number') is-invalid @enderror" 
                               id="number" 
                               name="number" 
                               value="{{ old('number', $room->number) }}" 
                               placeholder="Ex: 101, 201, 301" 
                               required>
                        @error('number')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-hint">Identifiant unique de la chambre</div>
                    </div>
                    
                    <!-- Nom de la chambre -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-signature"></i>
                            Nom de la chambre
                            <span class="optional">(Optionnel)</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $room->name) }}" 
                               placeholder="Suite Présidentielle, Vue Mer">
                        @error('name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-hint">Nom descriptif de la chambre</div>
                    </div>
                    
                    <!-- Type de chambre -->
                    <div class="form-group">
                        <label for="type_id" class="form-label">
                            <i class="fas fa-bed"></i>
                            Type de chambre *
                        </label>
                        <select id="type_id" 
                                name="type_id" 
                                class="form-select @error('type_id') is-invalid @enderror" 
                                required>
                            <option value="" disabled>-- Sélectionner un type --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id', $room->type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} 
                                    @if($type->base_price)
                                        - {{ number_format($type->base_price, 0, ',', ' ') }} FCFA
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <!-- Statut de la chambre -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-circle"></i>
                            Statut actuel
                            <span class="optional">(Auto-géré)</span>
                        </label>
                        
                        <div class="status-box">
                            <div class="status-display">
                                @php
                                    $statusColor = match($room->roomStatus->name ?? '') {
                                        'Disponible' => 'success',
                                        'Occupée' => 'danger',
                                        'Réservée' => 'warning',
                                        'En maintenance' => 'gray',
                                        'À nettoyer' => 'gray',
                                        default => 'gray'
                                    };
                                @endphp
                                
                                <span class="status-badge status-badge--{{ $statusColor }}">
                                    <i class="fas fa-{{ match($room->roomStatus->name ?? '') {
                                        'Disponible' => 'check',
                                        'Occupée' => 'user',
                                        'Réservée' => 'calendar-check',
                                        'En maintenance' => 'tools',
                                        'À nettoyer' => 'broom',
                                        default => 'question-circle'
                                    } }}"></i>
                                    {{ $room->roomStatus->name ?? 'Inconnu' }}
                                </span>
                                
                                <div class="status-info">
                                    <div class="status-meta">{{ $room->roomStatus->information ?? '' }}</div>
                                    
                                    @if($room->roomStatus->name == 'Occupée')
                                        @php
                                            $activeTransaction = $room->transactions()
                                                ->where('status', 'active')
                                                ->where('check_in', '<=', now())
                                                ->where('check_out', '>=', now())
                                                ->first();
                                        @endphp
                                        @if($activeTransaction)
                                        <div class="status-detail">
                                            <i class="fas fa-user"></i>
                                            Client: {{ $activeTransaction->customer->name }}
                                        </div>
                                        @endif
                                    @elseif($room->roomStatus->name == 'Réservée')
                                        @php
                                            $nextReservation = $room->transactions()
                                                ->where('status', 'reservation')
                                                ->where('check_in', '>', now())
                                                ->orderBy('check_in', 'asc')
                                                ->first();
                                        @endphp
                                        @if($nextReservation)
                                        <div class="status-detail">
                                            <i class="fas fa-calendar"></i>
                                            Arrivée: {{ \Carbon\Carbon::parse($nextReservation->check_in)->format('d/m/Y') }}
                                        </div>
                                        @endif
                                    @elseif($room->roomStatus->name == 'En maintenance')
                                        @if($room->maintenance_started_at)
                                        <div class="status-detail">
                                            <i class="fas fa-clock"></i>
                                            Depuis: {{ \Carbon\Carbon::parse($room->maintenance_started_at)->format('d/m/Y H:i') }}
                                        </div>
                                        @endif
                                        @if($room->maintenance_reason)
                                        <div class="status-detail">
                                            <i class="fas fa-sticky-note"></i>
                                            Raison: {{ $room->maintenance_reason }}
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="room_status_id" value="{{ $room->room_status_id }}">
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <div class="info-box__content">
                                <strong>Statut auto-géré</strong>
                                Ce statut est automatiquement mis à jour en fonction des réservations et séjours.
                            </div>
                        </div>
                        
                        @if(auth()->user()->role == 'Super')
                        <div style="margin-top:12px">
                            <button type="button" class="btn-db btn-db-info" 
                                    onclick="toggleMaintenance({{ $room->id }}, '{{ $room->roomStatus->name ?? '' }}')">
                                <i class="fas fa-tools"></i>
                                {{ $room->roomStatus->name == 'En maintenance' ? 'Terminer la maintenance' : 'Mettre en maintenance' }}
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Capacité -->
                    <div class="form-group">
                        <label for="capacity" class="form-label">
                            <i class="fas fa-users"></i>
                            Capacité *
                        </label>
                        <input type="number" 
                               class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" 
                               name="capacity" 
                               value="{{ old('capacity', $room->capacity) }}" 
                               placeholder="2, 4, 6" 
                               min="1" 
                               max="10" 
                               required>
                        @error('capacity')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-hint">Nombre de personnes (1-10)</div>
                    </div>
                    
                    <!-- Prix par nuit -->
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <i class="fas fa-money-bill-wave"></i>
                            Prix par nuit *
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">FCFA</span>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $room->price) }}" 
                                   placeholder="50000" 
                                   min="0" 
                                   required>
                        </div>
                        @error('price')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <!-- Description de la vue -->
                    <div class="form-group">
                        <label for="view" class="form-label">
                            <i class="fas fa-binoculars"></i>
                            Description de la vue
                        </label>
                        <textarea class="form-control @error('view') is-invalid @enderror" 
                                  id="view" 
                                  name="view" 
                                  rows="1" 
                                  placeholder="Vue sur mer, Vue sur montagne, Vue sur ville">{{ old('view', $room->view) }}</textarea>
                        @error('view')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <!-- Méta-informations -->
                    <div class="form-group">
                        <div class="meta-card">
                            <div class="meta-title">
                                <i class="fas fa-calendar-alt"></i>
                                Informations
                            </div>
                            <div class="meta-row">Créée le: {{ $room->created_at->format('d/m/Y H:i') }}</div>
                            <div class="meta-row">Dernière modification: {{ $room->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Actions -->
                <div class="actions-bar">
                    <a href="{{ route('room.index') }}" class="btn-db btn-db-ghost">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <div class="actions-group">
                        <a href="{{ route('room.show', $room->id) }}" class="btn-db btn-db-info">
                            <i class="fas fa-eye me-2"></i>
                            Voir
                        </a>
                        <button type="submit" class="btn-db btn-db-primary">
                            <i class="fas fa-save me-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleMaintenance(roomId, currentStatus) {
    const isMaintenance = currentStatus === 'En maintenance';
    
    Swal.fire({
        title: isMaintenance ? 'Terminer la maintenance ?' : 'Mettre en maintenance ?',
        html: `
            <div style="text-align:left">
                <p>${isMaintenance 
                    ? 'Cette action marquera la chambre comme disponible à nouveau.' 
                    : 'Cette action marquera temporairement la chambre comme indisponible.'}</p>
                
                ${!isMaintenance ? `
                <div style="margin-bottom:16px">
                    <label style="display:block;margin-bottom:6px;font-weight:600">Raison de la maintenance :</label>
                    <textarea id="maintenanceReason" class="form-control" rows="3" 
                              placeholder="Nettoyage, réparations, rénovation..."></textarea>
                </div>
                ` : ''}
            </div>
        `,
        icon: isMaintenance ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isMaintenance ? '#1e6b2e' : '#eab308',
        cancelButtonColor: '#9ba09b',
        confirmButtonText: isMaintenance ? 'Oui, terminer' : 'Oui, mettre en maintenance',
        cancelButtonText: 'Annuler',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            if (!isMaintenance) {
                const reason = document.getElementById('maintenanceReason').value;
                if (!reason.trim()) {
                    Swal.showValidationMessage('Veuillez entrer une raison de maintenance');
                    return false;
                }
                return { reason: reason.trim() };
            }
            return {};
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value?.reason || '';
            
            Swal.fire({
                title: 'Traitement en cours...',
                text: 'Veuillez patienter',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`/room/${roomId}/maintenance-toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: isMaintenance ? 'end' : 'start',
                    reason: reason
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Opération échouée'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Erreur réseau. Veuillez réessayer.'
                });
            });
        }
    });
}
</script>
@endpush