@extends('template.master')
@section('title', 'Check-in — Recherche')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
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

.search-page {
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
.breadcrumb .active {
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
    margin-bottom: 24px;
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

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
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
    text-decoration: none;
}
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    text-decoration: none;
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   SEARCH CONTAINER
══════════════════════════════════════════════ */
.search-container {
    background: linear-gradient(135deg, var(--green-700), var(--green-600));
    border-radius: var(--rxl);
    padding: 28px;
    margin-bottom: 24px;
    color: white;
    box-shadow: var(--shadow-md);
}
.search-container h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
}
.search-container h4 i {
    margin-right: 8px;
}
.search-input-group {
    display: flex;
    background: var(--white);
    border-radius: 100px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.search-input-group span {
    display: flex;
    align-items: center;
    padding: 0 16px;
    color: var(--green-600);
}
.search-input-group input {
    flex: 1;
    border: none;
    padding: 14px 0;
    font-size: .9rem;
    color: var(--gray-800);
}
.search-input-group input:focus {
    outline: none;
}
.search-input-group button {
    background: var(--white);
    border: none;
    padding: 0 24px;
    font-weight: 600;
    color: var(--green-600);
    cursor: pointer;
    transition: var(--transition);
}
.search-input-group button:hover {
    background: var(--green-50);
}

/* ── Quick filters ── */
.quick-filters {
    margin-top: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.2);
    border-radius: 100px;
    font-size: .75rem;
    font-weight: 500;
    color: white;
    cursor: pointer;
    transition: var(--transition);
}
.filter-badge:hover {
    background: rgba(255,255,255,.25);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--white);
}
.card-header h5 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h5 i {
    color: var(--green-600);
}
.card-body {
    padding: 22px;
}

/* ══════════════════════════════════════════════
   RESULTS
══════════════════════════════════════════════ */
.search-results {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 8px;
}
.search-results::-webkit-scrollbar {
    width: 6px;
}
.search-results::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 10px;
}
.search-results::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 10px;
}
.search-results::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}

.reservation-item {
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 20px;
    margin-bottom: 16px;
    transition: var(--transition);
    background: var(--white);
    animation: fadeSlide .3s ease both;
}
.reservation-item:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.reservation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}
.reservation-id {
    font-weight: 700;
    color: var(--green-700);
    font-size: .85rem;
    background: var(--green-50);
    padding: 4px 12px;
    border-radius: 100px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1.5px solid var(--green-200);
}
.reservation-status {
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    text-transform: uppercase;
}
.status-reservation { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.status-active { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.status-completed { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.status-cancelled { background: var(--red-50); color: var(--red-600); border: 1.5px solid var(--red-100); }
.status-no_show { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }

.customer-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    box-shadow: 0 4px 8px rgba(46,133,64,.2);
}

.room-badge {
    background: var(--green-50);
    color: var(--green-700);
    padding: 4px 12px;
    border-radius: 100px;
    font-weight: 600;
    font-size: .75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1.5px solid var(--green-200);
}

/* ── Badge ── */
.badge {
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 20px;
}
.empty-icon {
    font-size: 3.5rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════════════
   GUIDE CARDS
══════════════════════════════════════════════ */
.guide-card {
    text-align: center;
    padding: 20px;
    background: var(--gray-50);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    height: 100%;
    transition: var(--transition);
}
.guide-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.guide-card i {
    font-size: 2rem;
    margin-bottom: 12px;
    color: var(--green-600);
}
.guide-card h6 {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}
.guide-card p {
    font-size: .7rem;
    color: var(--gray-500);
    margin: 0;
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 16px 20px;
    border-radius: var(--rl);
    margin-bottom: 20px;
    border: 1.5px solid;
    display: flex;
    align-items: center;
    gap: 12px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-600);
}
.alert-gray {
    background: var(--gray-100);
    border-color: var(--gray-200);
    color: var(--gray-600);
}

/* ── Loading indicator ── */
.search-loading {
    display: none;
}
.search-loading.active {
    display: block;
}
.loading-spinner {
    text-align: center;
    padding: 40px;
}
.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--gray-200);
    border-top-color: var(--green-600);
    border-radius: 50%;
    animation: spin .8s linear infinite;
    margin: 0 auto 16px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ── Pagination ── */
.pagination {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-top: 20px;
}
.page-item {
    display: inline-block;
}
.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: var(--r);
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
    font-size: .8rem;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
}
.page-link:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    text-decoration: none;
}
.page-item.active .page-link {
    background: var(--green-600);
    border-color: var(--green-600);
    color: white;
}
</style>

<div class="search-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('checkin.index') }}">Check-in</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="active">Recherche</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-search"></i></span>
                <h1>Recherche de <em>réservations</em></h1>
            </div>
            <p class="header-subtitle">Trouvez rapidement une réservation pour le check-in</p>
        </div>
        <a href="{{ route('checkin.index') }}" class="btn btn-gray">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    {{-- Barre de recherche --}}
    <div class="search-container anim-3">
        <h4><i class="fas fa-search"></i>Rechercher une réservation</h4>
        <form method="GET" action="{{ route('checkin.search') }}" id="search-form">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="search-input-group">
                        <span><i class="fas fa-search"></i></span>
                        <input type="text" 
                               name="search" 
                               id="search-input"
                               placeholder="Nom, téléphone, email, chambre..."
                               value="{{ $search ?? '' }}"
                               autocomplete="off"
                               autofocus>
                        <button type="submit">Rechercher</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-green w-100">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </div>
            
            {{-- Filtres rapides --}}
            <div class="quick-filters">
                <span class="filter-badge" onclick="setFilter('arrivals-today')">
                    <i class="fas fa-calendar-day"></i>Arrivées aujourd'hui
                </span>
                <span class="filter-badge" onclick="setFilter('departures-today')">
                    <i class="fas fa-sign-out-alt"></i>Départs aujourd'hui
                </span>
                <span class="filter-badge" onclick="setFilter('reservation')">
                    <i class="fas fa-calendar-check"></i>Réservations
                </span>
                <span class="filter-badge" onclick="setFilter('active')">
                    <i class="fas fa-bed"></i>Dans l'hôtel
                </span>
                <span class="filter-badge" onclick="setFilter('cancelled')">
                    <i class="fas fa-ban"></i>Annulées
                </span>
                <span class="filter-badge" onclick="setFilter('no_show')">
                    <i class="fas fa-user-slash"></i>No Show
                </span>
            </div>
        </form>
    </div>

    {{-- Indicateur de chargement --}}
    <div class="search-loading" id="loading-indicator">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p class="text-muted">Recherche en cours...</p>
        </div>
    </div>

    {{-- Résultats --}}
    <div class="card anim-4">
        <div class="card-header">
            <h5><i class="fas fa-list-ul"></i> Résultats de recherche</h5>
            @if(isset($search) && $search)
                <div>
                    <span class="badge badge-green">{{ $reservations->total() }} résultat(s)</span>
                    <a href="{{ route('checkin.search') }}" class="btn btn-sm btn-gray ms-2">
                        <i class="fas fa-times"></i> Effacer
                    </a>
                </div>
            @endif
        </div>
        <div class="card-body">

            @if(!isset($search) || !$search)
                {{-- État initial --}}
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-search"></i></div>
                    <h4>Recherchez une réservation</h4>
                    <p>Utilisez la barre de recherche ci-dessus</p>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-user"></i>
                                <h6>Par nom</h6>
                                <p>Ex: "Dupont"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-phone"></i>
                                <h6>Par téléphone</h6>
                                <p>Ex: "0123456789"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-envelope"></i>
                                <h6>Par email</h6>
                                <p>Ex: "client@email.com"</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="guide-card">
                                <i class="fas fa-door-closed"></i>
                                <h6>Par chambre</h6>
                                <p>Ex: "101"</p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($reservations->isEmpty())
                {{-- Aucun résultat --}}
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-search-minus"></i></div>
                    <h4>Aucun résultat trouvé</h4>
                    <p>Aucune réservation ne correspond à "{{ $search }}"</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('checkin.search') }}" class="btn btn-gray">Nouvelle recherche</a>
                        <a href="{{ route('checkin.direct') }}" class="btn btn-green">Check-in direct</a>
                    </div>
                </div>

            @else
                {{-- Liste des résultats --}}
                <div class="search-results">
                    @foreach($reservations as $transaction)
                        @php
                            $totalPaid = $transaction->getTotalPayment();
                            $totalPrice = $transaction->getTotalPrice();
                            $remaining = $totalPrice - $totalPaid;
                            $initials = strtoupper(substr($transaction->customer->name, 0, 2));
                            
                            // Déterminer la classe de statut
                            $statusClass = match($transaction->status) {
                                'reservation' => 'status-reservation',
                                'active' => 'status-active',
                                'completed' => 'status-completed',
                                'cancelled' => 'status-cancelled',
                                'no_show' => 'status-no_show',
                                default => 'status-reservation'
                            };
                        @endphp
                        <div class="reservation-item">
                            <div class="reservation-header">
                                <div>
                                    <span class="reservation-id">
                                        <i class="fas fa-hashtag"></i> #{{ $transaction->id }}
                                    </span>
                                    <span class="reservation-status {{ $statusClass }}">
                                        {{ $transaction->status_label }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $transaction->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="customer-avatar">
                                        {{ $initials }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-semibold mb-2">{{ $transaction->customer->name }}</h6>
                                    <div class="text-muted small">
                                        <div class="mb-1">
                                            <i class="fas fa-phone fa-xs me-2" style="color:var(--gray-400);"></i>
                                            {{ $transaction->customer->phone }}
                                        </div>
                                        @if($transaction->customer->email)
                                            <div>
                                                <i class="fas fa-envelope fa-xs me-2" style="color:var(--gray-400);"></i>
                                                {{ $transaction->customer->email }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <span class="room-badge mb-2">
                                        <i class="fas fa-door-closed"></i>
                                        Chambre {{ $transaction->room->number }}
                                    </span>
                                    <div class="text-muted small">
                                        <i class="fas fa-bed"></i>
                                        {{ $transaction->room->type->name ?? 'Standard' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-md-end">
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Arrivée</small>
                                            <strong>{{ $transaction->check_in->format('d/m/Y H:i') }}</strong>
                                        </div>
                                        
                                        <div class="d-flex gap-1 justify-content-md-end">
                                            @if($transaction->status == 'reservation')
                                                <a href="{{ route('checkin.show', $transaction) }}" 
                                                   class="btn btn-green btn-sm">
                                                    <i class="fas fa-door-open"></i> Check-in
                                                </a>
                                                <button onclick="quickCheckIn({{ $transaction->id }})" 
                                                        class="btn btn-gray btn-sm">
                                                    <i class="fas fa-bolt"></i>
                                                </button>
                                            @elseif($transaction->status == 'active')
                                                <a href="{{ route('transaction.show', $transaction) }}" 
                                                   class="btn btn-gray btn-sm">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            @else
                                                <a href="{{ route('transaction.show', $transaction) }}" 
                                                   class="btn btn-gray btn-sm">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Informations supplémentaires --}}
                            <div class="row mt-3 pt-3" style="border-top:1px solid var(--gray-200);">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1" style="color:var(--green-600);"></i>
                                        <strong>Durée:</strong> {{ $transaction->nights }} nuit(s)
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-money-bill-wave me-1" style="color:var(--green-600);"></i>
                                        <strong>Total:</strong> {{ number_format($totalPrice, 0, ',', ' ') }} FCFA
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-credit-card me-1" style="color:var(--green-600);"></i>
                                        <strong>Payé:</strong> {{ number_format($totalPaid, 0, ',', ' ') }} FCFA
                                        @if($remaining > 0)
                                            <span class="text-danger ms-1">
                                                (Solde: {{ number_format($remaining, 0, ',', ' ') }})
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            
                            @if($transaction->status == 'cancelled' && $transaction->cancel_reason)
                                <div class="mt-2 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Raison: {{ $transaction->cancel_reason }}
                                </div>
                            @endif
                            
                            @if($transaction->status == 'no_show')
                                <div class="mt-2 small text-muted">
                                    <i class="fas fa-user-slash me-1"></i>
                                    Client non présenté
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                @if($reservations->hasPages())
                    <div class="pagination">
                        {{ $reservations->appends(['search' => $search])->links('pagination::simple-bootstrap-4') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Aide et actions --}}
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-lightbulb" style="color:var(--green-600);"></i> Conseils</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0" style="color:var(--gray-600); font-size:.8rem;">
                        <li class="mb-2">Utilisez les initiales pour une recherche plus large</li>
                        <li class="mb-2">Les numéros de téléphone peuvent être saisis sans indicatif</li>
                        <li class="mb-2">Recherchez par numéro de chambre</li>
                        <li>Utilisez les filtres rapides pour les besoins courants</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-clock" style="color:var(--green-600);"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkin.index') }}" class="btn btn-gray">
                            <i class="fas fa-home"></i> Dashboard check-in
                        </a>
                        <a href="{{ route('checkin.direct') }}" class="btn btn-green">
                            <i class="fas fa-user-plus"></i> Check-in direct
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function setFilter(type) {
    const input = document.getElementById('search-input');
    const today = new Date().toISOString().split('T')[0];
    
    switch(type) {
        case 'arrivals-today':
            input.value = 'arrivée:' + today;
            break;
        case 'departures-today':
            input.value = 'départ:' + today;
            break;
        case 'reservation':
            input.value = 'statut:reservation';
            break;
        case 'active':
            input.value = 'statut:active';
            break;
        case 'cancelled':
            input.value = 'statut:cancelled';
            break;
        case 'no_show':
            input.value = 'statut:no_show';
            break;
    }
    document.getElementById('search-form').submit();
}

function quickCheckIn(id) {
    if (!confirm('Effectuer un check-in rapide ?')) return;
    
    const loader = document.getElementById('loading-indicator');
    loader.classList.add('active');
    
    fetch(`/checkin/${id}/quick`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loader.classList.remove('active');
        if (data.success) {
            // Afficher une notification de succès
            const alert = document.createElement('div');
            alert.className = 'alert alert-green';
            alert.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message || 'Check-in effectué avec succès'}`;
            document.querySelector('.search-page').prepend(alert);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Erreur: ' + (data.error || 'Échec du check-in'));
        }
    })
    .catch(error => {
        loader.classList.remove('active');
        console.error('Erreur:', error);
        alert('Erreur réseau lors du check-in');
    });
}

// Recherche automatique après délai
let timeout;
document.getElementById('search-input')?.addEventListener('input', function() {
    clearTimeout(timeout);
    if (this.value.length >= 2) {
        timeout = setTimeout(() => {
            document.getElementById('loading-indicator').classList.add('active');
            document.getElementById('search-form').submit();
        }, 800);
    }
});

// Soumettre le formulaire avec la touche Entrée
document.getElementById('search-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('loading-indicator').classList.add('active');
        document.getElementById('search-form').submit();
    }
});
</script>

@endsection