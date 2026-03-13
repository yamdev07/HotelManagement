@extends('template.master')
@section('title', 'Recherche de disponibilité')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, gris, blanc + rouge pour alertes) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-200: #a8d5b5;
    --green-300: #72bb82;
    --green-400: #4a9e5c;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.av-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }
.anim-5 { animation: fadeSlide .4s .32s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}
.header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: var(--r);
    font-size: .85rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
    text-decoration: none;
}
.btn-sm {
    padding: 6px 14px;
    font-size: .75rem;
}

/* ══════════════════════════════════════════════
   SEARCH CARD
══════════════════════════════════════════════ */
.search-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 28px;
}
.search-card-body {
    padding: 24px;
}
.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.form-group {
    margin-bottom: 16px;
}
.form-label {
    display: block;
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--gray-500);
    margin-bottom: 6px;
    letter-spacing: .5px;
}
.form-control,
.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    color: var(--gray-700);
    transition: var(--transition);
    background: var(--white);
    font-family: var(--font);
}
.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}
.search-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════
   INFO PANEL
══════════════════════════════════════════════ */
.info-panel {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 20px;
}
.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-200);
}
.info-item:last-child {
    border-bottom: none;
}
.info-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
}
.info-label {
    font-size: .65rem;
    color: var(--gray-500);
    text-transform: uppercase;
}
.info-value {
    font-weight: 600;
    color: var(--gray-800);
}

/* ══════════════════════════════════════════════
   RESULTS COUNT
══════════════════════════════════════════════ */
.results-count {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding: 12px 20px;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
}
.count-badge {
    background: var(--green-50);
    color: var(--green-700);
    padding: 4px 14px;
    border-radius: 100px;
    font-weight: 600;
    font-size: .8rem;
}
.count-badge-red {
    background: var(--red-50);
    color: var(--red-500);
}
.count-text {
    color: var(--gray-600);
    font-size: .85rem;
}

/* ══════════════════════════════════════════════
   ROOM CARDS
══════════════════════════════════════════════ */
.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}
.room-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
}
.room-card:hover {
    border-color: var(--green-300);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}
.room-card.available {
    border-top: 4px solid var(--green-500);
}
.room-card.unavailable {
    border-top: 4px solid var(--red-500);
    opacity: .9;
}
.room-card-header {
    padding: 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.room-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 4px;
}
.room-type {
    display: inline-block;
    padding: 4px 12px;
    background: var(--green-50);
    color: var(--green-700);
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.room-card.unavailable .room-type {
    background: var(--red-50);
    color: var(--red-500);
}
.room-price-total {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gray-800);
}
.room-price-night {
    font-size: .65rem;
    color: var(--gray-500);
}
.room-card-body {
    padding: 20px;
    flex: 1;
}
.room-feature {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: .8rem;
}
.room-feature i {
    width: 20px;
    color: var(--gray-400);
}
.room-facilities {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 12px;
}
.facility-badge {
    padding: 4px 10px;
    background: var(--gray-100);
    border: 1.5px solid var(--gray-200);
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 500;
    color: var(--gray-600);
}
.room-card-footer {
    padding: 20px;
    border-top: 1.5px solid var(--gray-200);
    display: flex;
    gap: 8px;
}
.btn-room {
    flex: 1;
    padding: 8px;
    border-radius: var(--r);
    font-size: .7rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.btn-room-green {
    background: var(--green-600);
    color: white;
}
.btn-room-green:hover {
    background: var(--green-700);
    transform: translateY(-2px);
}
.btn-room-gray {
    background: var(--white);
    color: var(--gray-700);
    border: 1.5px solid var(--gray-200);
}
.btn-room-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-2px);
}
.btn-room-red {
    background: var(--white);
    color: var(--red-500);
    border: 1.5px solid var(--red-100);
}
.btn-room-red:hover {
    background: var(--red-50);
    transform: translateY(-2px);
}

/* ══════════════════════════════════════════════
   CONFLICT INFO
══════════════════════════════════════════════ */
.conflict-info {
    margin-top: 16px;
    padding: 16px;
    background: var(--red-50);
    border: 1.5px solid var(--red-100);
    border-radius: var(--rl);
}
.conflict-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}
.conflict-count {
    background: var(--red-500);
    color: white;
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}
.conflict-item {
    margin-bottom: 8px;
    padding: 8px 10px;
    background: rgba(255,255,255,.5);
    border-radius: var(--r);
    border-left: 3px solid var(--red-500);
}
.conflict-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--red-500);
    text-decoration: none;
    font-weight: 600;
    font-size: .7rem;
    margin-top: 8px;
}
.conflict-link:hover {
    text-decoration: underline;
}
.status-badge {
    padding: 2px 8px;
    border-radius: 100px;
    font-size: .6rem;
    font-weight: 600;
}
.status-badge.active { background: var(--green-50); color: var(--green-700); }
.status-badge.reservation { background: var(--gray-100); color: var(--gray-600); }

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
}
.empty-state i {
    font-size: 3.5rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}
.empty-state p {
    color: var(--gray-400);
    margin-bottom: 20px;
}
</style>

<div class="av-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Disponibilité</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-search"></i></span>
            <h1>Recherche de <em>disponibilité</em></h1>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('availability.calendar') }}" class="btn btn-gray">
                <i class="fas fa-calendar-alt"></i> Calendrier
            </a>
            <a href="{{ route('availability.inventory') }}" class="btn btn-gray">
                <i class="fas fa-clipboard-list"></i> Inventaire
            </a>
        </div>
    </div>

    {{-- Formulaire de recherche --}}
    <div class="search-card anim-3">
        <div class="search-card-body">
            <form method="GET" action="{{ route('availability.search') }}">
                <div class="search-grid">
                    <div class="form-group">
                        <label class="form-label">Arrivée</label>
                        <input type="date" name="check_in" class="form-control" 
                               value="{{ $checkIn }}" min="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Départ</label>
                        <input type="date" name="check_out" class="form-control" 
                               value="{{ $checkOut }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Adultes</label>
                        <select name="adults" class="form-select">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $adults == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Adulte' : 'Adultes' }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Type de chambre</label>
                        <select name="room_type_id" class="form-select">
                            <option value="">Tous les types</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ $roomTypeId == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="search-actions">
                    <button type="submit" class="btn btn-green">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="{{ route('availability.search') }}" class="btn btn-gray">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(request()->has('check_in'))
    {{-- Info panel --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="results-count anim-4">
                <span class="count-badge">{{ count($availableRooms) }} disponible(s)</span>
                <span class="count-text">sur {{ count($availableRooms) + count($unavailableRooms) }} chambres</span>
                <span class="count-badge count-badge-red" style="margin-left: auto;">{{ $nights }} nuit(s)</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-panel">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="info-content">
                        <div class="info-label">Période</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-users"></i></div>
                    <div class="info-content">
                        <div class="info-label">Personnes</div>
                        <div class="info-value">{{ $adults + $children }} ({{ $adults }} adulte(s), {{ $children }} enfant(s))</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                    <div class="info-content">
                        <div class="info-label">Horaires</div>
                        <div class="info-value">Check-in 14h00 · Check-out 12h00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Résultats --}}
    <div class="anim-5">

        {{-- Chambres disponibles --}}
        @if(count($availableRooms) > 0)
        <div class="mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--green-700);">
                <i class="fas fa-check-circle me-2" style="color:var(--green-600);"></i>
                Chambres disponibles ({{ count($availableRooms) }})
            </h5>
            <div class="room-grid">
                @foreach($availableRooms as $roomData)
                <div class="room-card available">
                    <div class="room-card-header">
                        <div>
                            <div class="room-number">Chambre {{ $roomData['room']->number }}</div>
                            <span class="room-type">{{ $roomData['room']->type->name ?? 'Standard' }}</span>
                        </div>
                        <div class="text-end">
                            <div class="room-price-total">{{ number_format($roomData['total_price'], 0, ',', ' ') }} FCFA</div>
                            <div class="room-price-night">{{ number_format($roomData['price_per_night'], 0, ',', ' ') }} FCFA/nuit</div>
                        </div>
                    </div>
                    
                    <div class="room-card-body">
                        <div class="room-feature">
                            <i class="fas fa-users"></i> Capacité: <strong>{{ $roomData['room']->capacity }} personnes</strong>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-bed"></i> Type: <strong>{{ $roomData['room']->type->name ?? 'Standard' }}</strong>
                        </div>
                        @if($roomData['room']->surface)
                        <div class="room-feature">
                            <i class="fas fa-arrows-alt"></i> Surface: <strong>{{ $roomData['room']->surface }} m²</strong>
                        </div>
                        @endif
                        
                        @if($roomData['room']->facilities->count() > 0)
                        <div class="room-facilities">
                            @foreach($roomData['room']->facilities->take(3) as $facility)
                                <span class="facility-badge">
                                    @if($facility->icon)<i class="fas {{ $facility->icon }} me-1"></i>@endif
                                    {{ $facility->name }}
                                </span>
                            @endforeach
                            @if($roomData['room']->facilities->count() > 3)
                                <span class="facility-badge">+{{ $roomData['room']->facilities->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <div class="room-card-footer">
                        <a href="{{ route('availability.room.detail', $roomData['room']->id) }}" class="btn-room btn-room-gray">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        <a href="{{ route('transaction.reservation.createIdentity', [
                            'room_id' => $roomData['room']->id,
                            'check_in' => $checkIn,
                            'check_out' => $checkOut,
                            'adults' => $adults,
                            'children' => $children
                        ]) }}" class="btn-room btn-room-green">
                            <i class="fas fa-book"></i> Réserver
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Chambres non disponibles --}}
        @if(count($unavailableRooms) > 0)
        <div class="mt-4">
            <h5 class="fw-bold mb-3" style="color:var(--red-600);">
                <i class="fas fa-times-circle me-2" style="color:var(--red-500);"></i>
                Chambres non disponibles ({{ count($unavailableRooms) }})
            </h5>
            <div class="room-grid">
                @foreach($unavailableRooms as $room)
                <div class="room-card unavailable">
                    <div class="room-card-header">
                        <div>
                            <div class="room-number">Chambre {{ $room->number }}</div>
                            <span class="room-type">{{ $room->type->name ?? 'Standard' }}</span>
                        </div>
                    </div>
                    
                    <div class="room-card-body">
                        <div class="room-feature">
                            <i class="fas fa-users"></i> Capacité: <strong>{{ $room->capacity }} personnes</strong>
                        </div>
                        <div class="room-feature">
                            <i class="fas fa-bed"></i> Type: <strong>{{ $room->type->name ?? 'Standard' }}</strong>
                        </div>
                        
                        {{-- Conflits --}}
                        @if(isset($roomConflicts[$room->id]) && count($roomConflicts[$room->id]) > 0)
                        <div class="conflict-info">
                            <div class="conflict-header">
                                <i class="fas fa-exclamation-triangle" style="color:var(--red-500);"></i>
                                <strong style="color:var(--red-700);">Réservations en conflit</strong>
                                <span class="conflict-count">{{ count($roomConflicts[$room->id]) }}</span>
                            </div>
                            <div class="conflict-details">
                                @foreach($roomConflicts[$room->id] as $conflict)
                                @php
                                    $checkInRaw = $conflict['check_in'] ?? 'N/A';
                                    $checkOutRaw = $conflict['check_out'] ?? 'N/A';
                                    $customerName = $conflict['customer'] ?? 'Client inconnu';
                                    $statusClass = $conflict['status_class'] ?? 'bg-secondary';
                                    $statusLabel = $conflict['status'] ?? 'N/A';
                                    
                                    $checkInDisplay = (strpos($checkInRaw, '/') !== false) ? explode('/', $checkInRaw)[0].'/'.explode('/', $checkInRaw)[1] : $checkInRaw;
                                    $checkOutDisplay = (strpos($checkOutRaw, '/') !== false) ? explode('/', $checkOutRaw)[0].'/'.explode('/', $checkOutRaw)[1] : $checkOutRaw;
                                @endphp
                                
                                <div class="conflict-item">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <i class="fas fa-calendar-alt" style="color:var(--red-400);"></i>
                                        <span>{{ $checkInDisplay }} → {{ $checkOutDisplay }}</span>
                                        <span class="status-badge {{ str_contains($statusClass, 'success') ? 'active' : 'reservation' }}">{{ $statusLabel }}</span>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                                        <i class="fas fa-user" style="color:var(--red-400);"></i>
                                        <span>{{ $customerName }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <a href="{{ route('availability.room.conflicts', $room->id) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}"
                               class="conflict-link">
                                <i class="fas fa-external-link-alt"></i> Voir tous les détails
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="room-card-footer">
                        <a href="{{ route('availability.room.detail', $room->id) }}" class="btn-room btn-room-gray">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        <a href="{{ route('availability.room.conflicts', $room->id) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}" 
                           class="btn-room btn-room-red">
                            <i class="fas fa-exclamation-triangle"></i> Conflits
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Aucun résultat --}}
        @if(count($availableRooms) == 0 && count($unavailableRooms) == 0)
        <div class="empty-state">
            <i class="fas fa-bed"></i>
            <h4>Aucune chambre trouvée</h4>
            <p>Aucune chambre ne correspond à vos critères de recherche.<br>Essayez de modifier vos dates ou le type de chambre.</p>
            <a href="{{ route('availability.search') }}" class="btn btn-green">
                <i class="fas fa-edit me-2"></i> Modifier la recherche
            </a>
        </div>
        @endif
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const checkIn = document.querySelector('input[name="check_in"]');
    const checkOut = document.querySelector('input[name="check_out"]');
    
    if (checkIn && checkOut) {
        checkIn.addEventListener('change', function() {
            const inDate = new Date(this.value);
            const next = new Date(inDate);
            next.setDate(next.getDate() + 1);
            
            checkOut.min = next.toISOString().split('T')[0];
            
            if (checkOut.value && new Date(checkOut.value) <= inDate) {
                checkOut.value = next.toISOString().split('T')[0];
            }
        });
        
        checkOut.addEventListener('change', function() {
            const outDate = new Date(this.value);
            const inDate = new Date(checkIn.value);
            
            if (outDate <= inDate) {
                alert('La date de départ doit être après la date d\'arrivée');
                const next = new Date(inDate);
                next.setDate(next.getDate() + 1);
                this.value = next.toISOString().split('T')[0];
            }
        });
    }
});
</script>
@endsection