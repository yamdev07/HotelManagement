@extends('template.master')
@section('title', 'Choix de la chambre')
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

.choose-room-page {
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
.choose-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--s400);
    margin-bottom: 20px;
}
.choose-breadcrumb a {
    color: var(--s400); text-decoration: none;
    transition: var(--transition);
}
.choose-breadcrumb a:hover { color: var(--g600); }
.choose-breadcrumb .sep { color: var(--s300); }
.choose-breadcrumb .current { color: var(--s600); font-weight: 500; }

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.choose-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 16px; margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.choose-brand { display: flex; align-items: center; gap: 14px; }
.choose-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.choose-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.choose-header-title em { font-style: normal; color: var(--g600); }
.choose-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.choose-header-sub i { color: var(--g500); }
.choose-header-actions { display: flex; align-items: center; gap: 10px; }

/* ══════════════════════════════════════════════
   PROGRESS BAR
══════════════════════════════════════════════ */
.progress-container {
    margin-bottom: 30px;
}
.progress-steps {
    display: flex; justify-content: space-between; position: relative;
    margin-bottom: 20px;
}
.progress-steps::before {
    content: ''; position: absolute; top: 20px; left: 0; right: 0;
    height: 2px; background: var(--s200); z-index: 1;
}
.progress-step {
    position: relative; z-index: 2; text-align: center; flex: 1;
}
.step-circle {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--white); border: 2px solid var(--s200);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px; font-weight: 600; color: var(--s600);
    transition: var(--transition);
}
.step-active .step-circle {
    background: var(--g600); border-color: var(--g600);
    color: white;
}
.step-completed .step-circle {
    background: var(--g500); border-color: var(--g500);
    color: white;
}
.step-label {
    font-size: .75rem; color: var(--s400); font-weight: 500;
}
.step-active .step-label {
    color: var(--g600); font-weight: 600;
}

/* ══════════════════════════════════════════════
   SUMMARY BOX
══════════════════════════════════════════════ */
.summary-box {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    border-radius: var(--rxl);
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-md);
}
.summary-title {
    font-size: 1.2rem; font-weight: 700; color: white;
    margin-bottom: 8px;
}
.summary-details {
    color: rgba(255,255,255,0.9); font-size: .9rem;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.summary-details i { color: rgba(255,255,255,0.6); }

/* ══════════════════════════════════════════════
   FILTERS CONTAINER
══════════════════════════════════════════════ */
.filters-container {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); padding: 20px;
    margin-bottom: 24px; box-shadow: var(--shadow-sm);
}
.filter-label {
    font-size: .7rem; font-weight: 600; color: var(--s600);
    margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px;
}
.filter-select {
    width: 100%; padding: 8px 12px; border-radius: var(--r);
    border: 1.5px solid var(--s200); font-size: .8rem;
    font-family: var(--font); transition: var(--transition);
    background: var(--white);
}
.filter-select:focus {
    outline: none; border-color: var(--g400);
    box-shadow: 0 0 0 3px var(--g100);
}
.search-btn {
    width: 100%; padding: 8px 16px; border-radius: var(--r);
    background: var(--g600); color: white; border: none;
    font-size: .8rem; font-weight: 500; transition: var(--transition);
    cursor: pointer; margin-top: 24px;
}
.search-btn:hover {
    background: var(--g700); transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

/* ══════════════════════════════════════════════
   ROOM CARD
══════════════════════════════════════════════ */
.room-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    margin-bottom: 20px; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.room-card:hover {
    transform: translateY(-4px); box-shadow: var(--shadow-lg);
    border-color: var(--g200);
}
.room-image {
    width: 100%; height: 250px; object-fit: cover;
}
.room-info {
    padding: 20px;
}
.room-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 12px;
}
.room-number {
    font-size: 1.3rem; font-weight: 700; color: var(--s800);
    line-height: 1.2;
}
.room-type {
    font-size: .8rem; color: var(--s400); margin-top: 2px;
}
.room-capacity {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 30px;
    background: var(--g50); color: var(--g600);
    border: 1px solid var(--g200); font-size: .75rem; font-weight: 600;
}
.room-price {
    font-size: 1.1rem; font-weight: 700; color: var(--g600);
    font-family: var(--mono); margin: 12px 0;
}
.room-price small {
    font-size: .7rem; font-weight: 400; color: var(--s400);
    font-family: var(--font);
}
.room-description {
    font-size: .8rem; color: var(--s600);
    line-height: 1.5; margin-bottom: 16px;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
    overflow: hidden;
}
.choose-btn {
    width: 100%; padding: 12px; border-radius: var(--r);
    background: var(--g600); color: white; border: none;
    font-size: .85rem; font-weight: 600; transition: var(--transition);
    cursor: pointer; display: inline-flex; align-items: center;
    justify-content: center; gap: 8px; text-decoration: none;
}
.choose-btn:hover {
    background: var(--g700); transform: translateY(-2px);
    box-shadow: var(--shadow-md); text-decoration: none; color: white;
}

/* ══════════════════════════════════════════════
   PROFILE CARD
══════════════════════════════════════════════ */
.profile-card {
    background: var(--white); border-radius: var(--rxl);
    border: 1.5px solid var(--s100); overflow: hidden;
    box-shadow: var(--shadow-sm); position: sticky; top: 100px;
}
.profile-header {
    background: linear-gradient(135deg, var(--g700), var(--g500));
    padding: 24px 20px; text-align: center;
}
.profile-avatar {
    width: 100px; height: 100px; border-radius: 50%;
    border: 4px solid white; box-shadow: var(--shadow-md);
    margin: 0 auto 16px; object-fit: cover;
}
.profile-name {
    font-size: 1.1rem; font-weight: 600; color: white;
    margin-bottom: 4px;
}
.profile-id {
    font-size: .75rem; color: rgba(255,255,255,0.8);
}
.profile-body {
    padding: 20px;
}
.profile-info-row {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid var(--s100);
}
.profile-info-row:last-child { border-bottom: none; }
.profile-info-icon {
    width: 24px; color: var(--g500); font-size: .9rem;
}
.profile-info-content {
    flex: 1;
}
.profile-info-label {
    font-size: .65rem; font-weight: 600; color: var(--s400);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px;
}
.profile-info-value {
    font-size: .8rem; color: var(--s800);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.no-rooms {
    background: var(--white); border-radius: var(--rxl);
    border: 2px dashed var(--s200); padding: 48px 24px;
    text-align: center;
}
.no-rooms h3 {
    font-size: 1.1rem; font-weight: 600; color: var(--s600);
    margin-bottom: 8px;
}
.no-rooms p {
    color: var(--s400); font-size: .8rem;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination-container {
    display: flex; justify-content: center; margin-top: 28px;
}
.pagination-custom {
    display: flex; gap: 4px; list-style: none;
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
    .choose-room-page{ padding: 20px; }
    .choose-header{ flex-direction: column; align-items: flex-start; }
    .profile-card{ position: static; margin-top: 20px; }
    .room-image{ height: 200px; }
}
</style>

<div class="choose-room-page">
    <!-- Breadcrumb -->
    <div class="choose-breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.reservation.createIdentity') }}">Création client</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('transaction.reservation.viewCountPerson', $customer->id) }}">Dates</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Choix chambre</span>
    </div>

    <!-- Header -->
    <div class="choose-header anim-2">
        <div class="choose-brand">
            <div class="choose-brand-icon"><i class="fas fa-bed"></i></div>
            <div>
                <h1 class="choose-header-title">Choix de la <em>chambre</em></h1>
                <p class="choose-header-sub">
                    <i class="fas fa-door-open me-1"></i> Étape 3/4 · Sélection de la chambre
                </p>
            </div>
        </div>
        <div class="choose-header-actions">
            <a href="{{ route('transaction.reservation.viewCountPerson', $customer->id) }}?check_in={{ request()->input('check_in') }}&check_out={{ request()->input('check_out') }}&count_person={{ request()->input('count_person') }}" class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container anim-3">
        <div class="progress-steps">
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Identité</div>
            </div>
            <div class="progress-step step-completed">
                <div class="step-circle"><i class="fas fa-check"></i></div>
                <div class="step-label">Dates</div>
            </div>
            <div class="progress-step step-active">
                <div class="step-circle">3</div>
                <div class="step-label">Chambre</div>
            </div>
            <div class="progress-step">
                <div class="step-circle">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Section principale -->
        <div class="col-lg-8">
            <!-- Résumé -->
            <div class="summary-box anim-4">
                <div class="summary-title">
                    <i class="fas fa-door-open me-2"></i>
                    {{ $roomsCount }} chambre(s) disponible(s)
                </div>
                <div class="summary-details">
                    <span><i class="fas fa-users me-1"></i>{{ request()->input('count_person') }} personne(s)</span>
                    <span><i class="fas fa-calendar-alt me-1"></i>Du {{ Helper::dateFormat(request()->input('check_in')) }}</span>
                    <span><i class="fas fa-calendar-alt me-1"></i>Au {{ Helper::dateFormat(request()->input('check_out')) }}</span>
                </div>
            </div>

            <!-- Filtres -->
            <div class="filters-container anim-5">
                <form method="GET" action="{{ route('transaction.reservation.chooseRoom', ['customer' => $customer->id]) }}">
                    <input type="hidden" name="count_person" value="{{ request()->input('count_person') }}">
                    <input type="hidden" name="check_in" value="{{ request()->input('check_in') }}">
                    <input type="hidden" name="check_out" value="{{ request()->input('check_out') }}">

                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="filter-label">Trier par</div>
                            <select class="filter-select" name="sort_name">
                                <option value="Price" @if(request()->input('sort_name') == 'Price') selected @endif>Prix</option>
                                <option value="Number" @if(request()->input('sort_name') == 'Number') selected @endif>Numéro</option>
                                <option value="Capacity" @if(request()->input('sort_name') == 'Capacity') selected @endif>Capacité</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div class="filter-label">Ordre</div>
                            <select class="filter-select" name="sort_type">
                                <option value="ASC" @if(request()->input('sort_type') == 'ASC') selected @endif>Croissant</option>
                                <option value="DESC" @if(request()->input('sort_type') == 'DESC') selected @endif>Décroissant</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Liste des chambres -->
            @forelse ($rooms as $room)
                <div class="room-card anim-6">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ $room->firstImage() }}" 
                                 alt="Chambre {{ $room->number }}" 
                                 class="room-image">
                        </div>
                        <div class="col-md-8">
                            <div class="room-info">
                                <div class="room-header">
                                    <div>
                                        <div class="room-number">Chambre {{ $room->number }}</div>
                                        <div class="room-type">{{ $room->type->name ?? 'Standard' }}</div>
                                    </div>
                                    <span class="room-capacity">
                                        <i class="fas fa-user"></i> {{ $room->capacity }}
                                    </span>
                                </div>

                                <div class="room-price">
                                    {{ Helper::formatCFA($room->price) }} <small>/nuit</small>
                                </div>

                                <div class="room-description">
                                    {{ $room->type->description_fr ?? 'Description non disponible' }}
                                </div>

                                <a href="{{ route('transaction.reservation.confirmation', [
                                    'customer' => $customer->id, 
                                    'room' => $room->id, 
                                    'from' => request()->input('check_in'), 
                                    'to' => request()->input('check_out')
                                ]) }}" class="choose-btn">
                                    <i class="fas fa-check-circle"></i>
                                    Choisir cette chambre
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-rooms anim-4">
                    <h3><i class="fas fa-bed me-2"></i>Aucune chambre disponible</h3>
                    <p class="text-muted">
                        Aucune chambre ne correspond à votre recherche pour 
                        {{ request()->input('count_person') }} personne(s)
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($rooms->hasPages())
                <div class="pagination-container">
                    <div class="pagination-custom">
                        {{ $rooms->onEachSide(1)->appends([
                            'count_person' => request()->input('count_person'),
                            'check_in' => request()->input('check_in'),
                            'check_out' => request()->input('check_out'),
                            'sort_name' => request()->input('sort_name'),
                            'sort_type' => request()->input('sort_type'),
                        ])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Profil client -->
        <div class="col-lg-4">
            <div class="profile-card anim-5">
                <div class="profile-header">
                    <img src="{{ $customer->user->getAvatar() }}" 
                         alt="{{ $customer->name }}" 
                         class="profile-avatar">
                    <div class="profile-name">{{ $customer->name }}</div>
                    <div class="profile-id">ID: #{{ $customer->id }}</div>
                </div>

                <div class="profile-body">
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas {{ $customer->gender == 'Male' ? 'fa-mars' : 'fa-venus' }}"></i></div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Genre</div>
                            <div class="profile-info-value">{{ $customer->gender == 'Male' ? 'Homme' : ($customer->gender == 'Female' ? 'Femme' : 'Autre') }}</div>
                        </div>
                    </div>

                    @if($customer->job)
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-briefcase"></i></div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Profession</div>
                            <div class="profile-info-value">{{ $customer->job }}</div>
                        </div>
                    </div>
                    @endif

                    @if($customer->birthdate)
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-birthday-cake"></i></div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Naissance</div>
                            <div class="profile-info-value">{{ \Carbon\Carbon::parse($customer->birthdate)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($customer->phone)
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-phone"></i></div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Téléphone</div>
                            <div class="profile-info-value">{{ $customer->phone }}</div>
                        </div>
                    </div>
                    @endif

                    @if($customer->address)
                    <div class="profile-info-row">
                        <div class="profile-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="profile-info-content">
                            <div class="profile-info-label">Adresse</div>
                            <div class="profile-info-value">{{ Str::limit($customer->address, 30) }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection