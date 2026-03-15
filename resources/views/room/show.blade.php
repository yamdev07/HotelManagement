@extends('template.master')

@section('title', 'Détails de la Chambre')

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

.details-page {
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
.details-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.details-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.details-breadcrumb a:hover { color: var(--g600); }
.details-breadcrumb .sep { color: var(--s300); }
.details-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.details-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.details-brand { display: flex; align-items: center; gap: 14px; }
.details-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.details-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.details-header-title em { font-style: normal; color: var(--g600); }
.details-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.details-header-sub i { color: var(--g500); }
.details-header-actions { display: flex; align-items: center; gap: 10px; }

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
.btn-db-danger {
    background: #fee2e2; color: #b91c1c;
    border: 1.5px solid #fecaca;
}
.btn-db-danger:hover {
    background: #fecaca; border-color: #b91c1c;
    color: #b91c1c; transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   GRILLE PRINCIPALE
══════════════════════════════════════════════ */
.details-grid {
    display: grid; grid-template-columns: 320px 1fr 380px;
    gap: 20px; align-items: start;
}
@media(max-width:1200px){ .details-grid{ grid-template-columns:1fr; } }

/* ══════════════════════════════════════════════
   CARTES
══════════════════════════════════════════════ */
.details-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.details-card:hover { box-shadow: var(--shadow-md); }
.details-card:last-child { margin-bottom: 0; }

.details-card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
    display: flex; align-items: center; justify-content: space-between;
}
.details-card-header--green {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    color: white; border-bottom: none;
}
.details-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: .9rem; font-weight: 600; color: var(--s800); margin: 0;
}
.details-card-title i { color: var(--g500); }
.details-card-header--green .details-card-title i,
.details-card-header--green .details-card-title { color: white; }

.details-card-body { padding: 20px; }

/* ══════════════════════════════════════════════
   GUEST SECTION
══════════════════════════════════════════════ */
.guest-avatar {
    width: 100%; aspect-ratio: 1; object-fit: cover;
    border-radius: var(--rl); margin-bottom: 16px;
    border: 3px solid var(--g200);
}
.guest-name {
    font-size: 1.2rem; font-weight: 700; color: var(--s800);
    margin-bottom: 16px; font-family: var(--font);
}
.guest-info {
    display: flex; flex-direction: column; gap: 12px;
}
.guest-info-item {
    display: flex; gap: 12px; font-size: .8rem;
}
.guest-info-icon {
    width: 20px; flex-shrink: 0; color: var(--g500);
    font-size: .75rem;
}
.guest-info-text {
    flex: 1; color: var(--s600);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    padding: 48px 20px; text-align: center;
}
.empty-icon {
    width: 64px; height: 64px; background: var(--g50);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 1.5rem; color: var(--g300);
    margin: 0 auto 16px; border: 2px solid var(--g100);
}
.empty-title {
    font-size: .95rem; font-weight: 600; color: var(--s700);
    margin-bottom: 4px;
}
.empty-text {
    font-size: .75rem; color: var(--s400); margin-bottom: 16px;
}

/* ══════════════════════════════════════════════
   INFO CARDS
══════════════════════════════════════════════ */
.info-grid {
    display: grid; grid-template-columns: repeat(2, 1fr);
    gap: 12px; margin-bottom: 16px;
}
.info-card {
    background: var(--surface); border-radius: var(--rl);
    padding: 14px; border: 1.5px solid var(--s100);
}
.info-label {
    font-size: .65rem; font-weight: 600; color: var(--s400);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;
}
.info-value {
    font-size: 1rem; font-weight: 700; color: var(--s800);
}

.stat-card {
    background: var(--surface); border: 1.5px solid var(--s100);
    border-radius: var(--rl); padding: 16px;
    display: flex; align-items: center; gap: 14px;
}
.stat-icon {
    width: 48px; height: 48px; border-radius: var(--rl);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-icon--blue { background: var(--g50); color: var(--g600); }
.stat-icon--green { background: var(--g50); color: var(--g600); }
.stat-icon--purple { background: var(--g50); color: var(--g600); }
.stat-label {
    font-size: .7rem; color: var(--s400); margin-bottom: 2px;
}
.stat-value {
    font-size: 1.2rem; font-weight: 700; color: var(--s800);
    font-family: var(--mono); letter-spacing: -.5px;
}
.stat-sub {
    font-size: .65rem; color: var(--s400); margin-top: 2px;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 5px 12px; border-radius: 6px; font-size: .7rem;
    font-weight: 600;
}
.badge--success { background: var(--g100); color: var(--g700); }
.badge--warning { background: #fff3cd; color: #856404; }
.badge--danger  { background: #fee2e2; color: #b91c1c; }
.badge--info    { background: var(--g50); color: var(--g600); }
.badge--gray    { background: var(--s100); color: var(--s600); }

/* ══════════════════════════════════════════════
   GALLERIE D'IMAGES
══════════════════════════════════════════════ */
.img-grid {
    display: flex; flex-direction: column; gap: 12px;
}
.img-card {
    background: var(--white); border: 1.5px solid var(--s100);
    border-radius: var(--rl); overflow: hidden;
    transition: var(--transition);
}
.img-card:hover {
    box-shadow: var(--shadow-md); transform: translateY(-2px);
    border-color: var(--g200);
}
.img-card__img {
    width: 100%; height: 180px; object-fit: cover;
    cursor: pointer; transition: opacity .2s;
}
.img-card__img:hover { opacity: .9; }
.img-card__foot {
    padding: 12px; display: flex; justify-content: space-between;
    align-items: center; border-top: 1.5px solid var(--s100);
}
.img-date {
    font-size: .65rem; color: var(--s400);
    display: flex; align-items: center; gap: 4px;
}
.img-date i { color: var(--g500); }

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
    font-size: .9rem; font-weight: 600; color: var(--s800);
    display: flex; align-items: center; gap: 8px;
}
.modal-db .modal-title i { color: var(--g500); }
.modal-db .modal-body { padding: 20px; }
.modal-db .modal-footer {
    background: var(--surface); border-top: 1.5px solid var(--s100);
    padding: 16px 20px;
}

/* Form elements */
.form-label-db {
    font-size: .7rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; display: block;
}
.form-control-db {
    width: 100%; padding: 8px 12px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .8rem;
    font-family: var(--font); transition: var(--transition);
}
.form-control-db:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.form-text-db {
    font-size: .65rem; color: var(--s400); margin-top: 4px;
}

/* ══════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════ */
@media(max-width:768px){
    .details-page{ padding: 20px; }
    .details-header{ flex-direction: column; align-items: flex-start; }
    .info-grid{ grid-template-columns:1fr; }
    .stat-card{ flex-direction: column; text-align: center; }
}
</style>

<div class="details-page">
    <!-- Breadcrumb -->
    <div class="details-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('room.index') }}">Chambres</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Chambre {{ $room->number }}</span>
    </div>

    <!-- Header -->
    <div class="details-header anim-2">
        <div class="details-brand">
            <div class="details-brand-icon"><i class="fas fa-bed"></i></div>
            <div>
                <h1 class="details-header-title">Détails de la <em>chambre</em></h1>
                <p class="details-header-sub">
                    <i class="fas fa-door-open me-1"></i> Chambre {{ $room->number }} · {{ $room->name ?? 'Sans nom' }}
                </p>
            </div>
        </div>
        <div class="details-header-actions">
            <a href="{{ route('room.edit', $room->id) }}" class="btn-db btn-db-primary">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            <a href="{{ route('room.index') }}" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="details-grid anim-3">

        <!-- Colonne gauche - Client actuel -->
        <div>
            @if (!empty($customer))
            <div class="details-card">
                <div class="details-card-header details-card-header--green">
                    <h5 class="details-card-title">
                        <i class="fas fa-user"></i>
                        Client actuel
                    </h5>
                </div>
                <div class="details-card-body">
                    <img class="guest-avatar" 
                         src="{{ $customer->user->getAvatar() }}" 
                         alt="{{ $customer->name }}">
                    <h4 class="guest-name">{{ $customer->name }}</h4>
                    <div class="guest-info">
                        <div class="guest-info-item">
                            <div class="guest-info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="guest-info-text">{{ $customer->user->email }}</div>
                        </div>
                        <div class="guest-info-item">
                            <div class="guest-info-icon"><i class="fas fa-briefcase"></i></div>
                            <div class="guest-info-text">{{ $customer->job ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="guest-info-item">
                            <div class="guest-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="guest-info-text">{{ $customer->address ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="guest-info-item">
                            <div class="guest-info-icon"><i class="fas fa-phone"></i></div>
                            <div class="guest-info-text">{{ $customer->phone ?? 'Non spécifié' }}</div>
                        </div>
                        @if($customer->birthdate)
                        <div class="guest-info-item">
                            <div class="guest-info-icon"><i class="fas fa-birthday-cake"></i></div>
                            <div class="guest-info-text">{{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="details-card">
                <div class="details-card-body">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-user-slash"></i></div>
                        <p class="empty-title">Chambre disponible</p>
                        <p class="empty-text">Aucun client actuellement</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne centrale - Informations chambre -->
        <div>
            <div class="details-card">
                <div class="details-card-header">
                    <h5 class="details-card-title">
                        <i class="fas fa-info-circle"></i>
                        Informations
                    </h5>
                    <button type="button" class="btn-db btn-db-ghost" data-bs-toggle="modal" data-bs-target="#imageUploadModal">
                        <i class="fas fa-upload me-2"></i>
                        Ajouter une image
                    </button>
                </div>
                <div class="details-card-body">
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-label">Type</div>
                            <div class="info-value">{{ $room->type->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Statut</div>
                            <div>
                                <span class="badge badge--{{ $room->roomStatus->color ?? 'gray' }}">
                                    <i class="fas fa-{{ $room->status_icon ?? 'door-closed' }} me-1"></i>
                                    {{ $room->roomStatus->name ?? 'Inconnu' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:16px">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon--blue">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="stat-label">Capacité</div>
                                <div class="stat-value">{{ $room->capacity }}</div>
                                <div class="stat-sub">personnes</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon stat-icon--green">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <div class="stat-label">Prix</div>
                                <div class="stat-value">{{ number_format($room->price, 0, ',', ' ') }}</div>
                                <div class="stat-sub">FCFA / nuit</div>
                            </div>
                        </div>
                    </div>

                    @if($room->view)
                    <div class="stat-card" style="margin-bottom:12px">
                        <div class="stat-icon stat-icon--purple">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <div>
                            <div class="stat-label">Vue</div>
                            <div class="stat-value" style="font-size:.9rem">{{ $room->view }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Images -->
        <div>
            <div class="details-card">
                <div class="details-card-header">
                    <h5 class="details-card-title">
                        <i class="fas fa-images"></i>
                        Images
                    </h5>
                </div>
                <div class="details-card-body">
                    @php
                        $images = $room->images ?? ($room->image ?? collect());
                    @endphp
                    
                    @if($images && $images->count() > 0)
                    <div class="img-grid">
                        @foreach ($images as $image)
                        <div class="img-card">
                            <img src="{{ asset('img/room/' . $room->number . '/' . $image->url) }}" 
                                 class="img-card__img" 
                                 alt="Image chambre"
                                 onclick="openImageModal('{{ asset('img/room/' . $room->number . '/' . $image->url) }}')">
                            <div class="img-card__foot">
                                <span class="img-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $image->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin')
                                <form action="{{ route('image.destroy', $image->id) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cette image ?')"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-db btn-db-danger" style="padding:4px 10px;font-size:.7rem">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-images"></i></div>
                        <p class="empty-title">Aucune image</p>
                        <p class="empty-text">Cette chambre n'a pas encore d'images</p>
                        @if(auth()->user()->role === 'Super' || auth()->user()->role === 'Admin')
                        <button type="button" class="btn-db btn-db-primary" data-bs-toggle="modal" data-bs-target="#imageUploadModal">
                            <i class="fas fa-upload me-2"></i>
                            Ajouter une image
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade modal-db" id="imageUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload"></i>
                    Ajouter une image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('image.store', ['room' => $room->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom:16px">
                        <label for="image" class="form-label-db">Sélectionner une image</label>
                        <input type="file" 
                               class="form-control-db @error('image') is-invalid @enderror" 
                               name="image" 
                               id="image" 
                               accept="image/*" 
                               required>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text-db">
                            Formats supportés: JPG, PNG, GIF. Max: 2MB.
                        </div>
                    </div>
                    <button type="submit" class="btn-db btn-db-primary w-100">
                        <i class="fas fa-upload me-2"></i>
                        Uploader
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Image -->
<div class="modal fade modal-db" id="imageViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image"></i>
                    Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="text-align:center">
                <img id="fullSizeImage" src="" alt="Full Size" style="max-width:100%;border-radius:var(--r)">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('fullSizeImage').src = imageUrl;
    const modal = new bootstrap.Modal(document.getElementById('imageViewModal'));
    modal.show();
}

@if(session('success'))
    toastr.success("{{ session('success') }}", "Succès");
@endif

@if(session('failed'))
    toastr.error("{{ session('failed') }}", "Erreur");
@endif

@error('image')
    toastr.error("{{ $message }}", "Upload échoué");
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
        modal.show();
    });
@enderror
</script>
@endpush