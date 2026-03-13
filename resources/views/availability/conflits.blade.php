@extends('template.master')

@section('title', 'Conflits de réservation')

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
    /* ROUGE (pour les conflits) */
    --r50: #fee2e2;
    --r100: #fecaca;
    --r500: #b91c1c;
    --r600: #991b1b;
    /* JAUNE/ORANGE (pour avertissements) */
    --y50: #fff7ed;
    --y100: #fed7aa;
    --y500: #c2410c;

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

.conflict-page {
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
   HEADER
══════════════════════════════════════════════ */
.conflict-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1.5px solid var(--s100);
}
.conflict-brand { display: flex; align-items: center; gap: 14px; }
.conflict-brand-icon {
    width: 48px; height: 48px;
    background: var(--g600); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(46,133,64,.35);
}
.conflict-header-title {
    font-size: 1.4rem; font-weight: 700;
    color: var(--s900); line-height: 1.2; letter-spacing: -.3px;
}
.conflict-header-title em { font-style: normal; color: var(--g600); }
.conflict-header-sub {
    font-size: .8rem; color: var(--s400); margin-top: 3px;
    display: flex; align-items: center; gap: 8px;
}
.conflict-header-actions { display: flex; align-items: center; gap: 10px; }

/* ── Boutons ─────────────────────────────────── */
.btn-db {
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
    white-space: nowrap;
}
.btn-db-primary {
    background: var(--g600);
    color: white;
    box-shadow: 0 2px 10px rgba(46,133,64,.25);
}
.btn-db-primary:hover {
    background: var(--g700);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(46,133,64,.3);
    color: white;
    text-decoration: none;
}
.btn-db-ghost {
    background: var(--white);
    color: var(--s600);
    border: 1.5px solid var(--s200);
}
.btn-db-ghost:hover {
    background: var(--g50);
    border-color: var(--g300);
    color: var(--g700);
    transform: translateY(-1px);
    text-decoration: none;
}
.btn-db-danger {
    background: var(--r500);
    color: white;
    border: 1.5px solid var(--r600);
}
.btn-db-danger:hover {
    background: var(--r600);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}
.btn-db-success {
    background: var(--g600);
    color: white;
}
.btn-db-success:hover {
    background: var(--g700);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

/* ══════════════════════════════════════════════
   CARTES D'INFORMATION
══════════════════════════════════════════════ */
.conflict-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 20px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) {
    .conflict-grid { grid-template-columns: 1fr; }
}

.conflict-card {
    background: var(--white);
    border: 1.5px solid var(--s100);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.conflict-card:hover {
    border-color: var(--g200);
    box-shadow: var(--shadow-md);
}

.conflict-card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--s100);
    background: var(--white);
}
.conflict-card-header h5 {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .95rem;
    font-weight: 600;
    color: var(--s800);
    margin: 0;
}
.conflict-card-header h5 i {
    color: var(--g600);
}

.conflict-card-body {
    padding: 20px 22px;
}

/* ── Room info ───────────────────────────────── */
.room-info-badge {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: var(--g600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.room-info-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--s900);
    margin-bottom: 2px;
}
.room-info-meta {
    font-size: .75rem;
    color: var(--s400);
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}
.meta-item i {
    color: var(--g500);
}

/* ── Alert boxes ─────────────────────────────── */
.alert-modern {
    padding: 16px 20px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.alert-info {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.alert-warning {
    background: var(--y50);
    border-color: var(--y100);
    color: var(--y500);
}
.alert-success {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g700);
}
.alert-danger {
    background: var(--r50);
    border-color: var(--r100);
    color: var(--r500);
}
.alert-icon {
    font-size: 1.4rem;
    flex-shrink: 0;
}
.alert-content h6 {
    font-size: .9rem;
    font-weight: 600;
    margin-bottom: 4px;
}
.alert-content p {
    font-size: .8rem;
    margin-bottom: 4px;
}
.alert-content strong {
    font-weight: 700;
}

/* ── Stats mini cartes ───────────────────────── */
.stats-mini-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 16px;
}
.stats-mini-card {
    background: var(--surface);
    border: 1.5px solid var(--s100);
    border-radius: var(--r);
    padding: 12px;
    text-align: center;
}
.stats-mini-label {
    font-size: .65rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--s400);
    letter-spacing: .4px;
    margin-bottom: 4px;
}
.stats-mini-value {
    font-size: 1.4rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--s800);
}

/* ══════════════════════════════════════════════
   TABLEAU DES CONFLITS
══════════════════════════════════════════════ */
.conflicts-table-wrapper {
    overflow-x: auto;
}
.conflicts-table {
    width: 100%;
    border-collapse: collapse;
}
.conflicts-table thead th {
    background: var(--surface);
    color: var(--s500);
    font-weight: 600;
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 14px 16px;
    border-bottom: 1.5px solid var(--s100);
    white-space: nowrap;
}
.conflicts-table tbody td {
    padding: 14px 16px;
    font-size: .82rem;
    color: var(--s700);
    border-bottom: 1px solid var(--s100);
    vertical-align: middle;
}
.conflicts-table tbody tr {
    transition: var(--transition);
}
.conflicts-table tbody tr:hover td {
    background: var(--g50);
}

/* ── Client info ─────────────────────────────── */
.client-mini {
    display: flex;
    align-items: center;
    gap: 8px;
}
.client-mini-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--g100);
    border: 2px solid var(--g200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 600;
    color: var(--g700);
}
.client-mini-name {
    font-weight: 600;
    color: var(--s800);
}

/* ── Badges ──────────────────────────────────── */
.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-reservation { background: var(--g100); color: var(--g700); }
.badge-active { background: var(--g100); color: var(--g700); }
.badge-completed { background: var(--g100); color: var(--g700); }
.badge-cancelled { background: var(--s100); color: var(--s600); }
.badge-no_show { background: var(--s100); color: var(--s500); }

/* ── Progress bar ────────────────────────────── */
.progress-container {
    width: 120px;
}
.progress-bar-modern {
    height: 20px;
    background: var(--s100);
    border-radius: 10px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--r500), var(--r500));
    border-radius: 10px;
    transition: width .6s ease;
}

/* ══════════════════════════════════════════════
   SUGGESTIONS CARD
══════════════════════════════════════════════ */
.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
.suggestion-card {
    background: var(--surface);
    border: 1.5px solid var(--s100);
    border-radius: var(--rl);
    padding: 18px;
    transition: var(--transition);
}
.suggestion-card:hover {
    border-color: var(--g300);
    background: var(--white);
    transform: translateY(-2px);
}
.suggestion-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 600;
    color: var(--s800);
    margin-bottom: 10px;
}
.suggestion-title i {
    color: var(--g600);
}
.suggestion-text {
    font-size: .8rem;
    color: var(--s500);
    margin-bottom: 16px;
}
</style>

<div class="conflict-page">

    {{-- ─── HEADER ─────────────────────────────── --}}
    <div class="conflict-header anim-1">
        <div class="conflict-brand">
            <div class="conflict-brand-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <h1 class="conflict-header-title">Conflits de <em>réservation</em></h1>
                <p class="conflict-header-sub">
                    Détails des réservations en conflit pour cette chambre
                </p>
            </div>
        </div>
        <div class="conflict-header-actions">
            <a href="{{ route('availability.search') }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&children={{ $children }}" 
               class="btn-db btn-db-ghost">
                <i class="fas fa-arrow-left"></i>
                Retour à la recherche
            </a>
        </div>
    </div>

    {{-- ─── GRILLE D'INFORMATIONS ───────────────── --}}
    <div class="conflict-grid anim-2">

        {{-- Carte chambre --}}
        <div class="conflict-card">
            <div class="conflict-card-header">
                <h5><i class="fas fa-bed"></i> Informations chambre</h5>
            </div>
            <div class="conflict-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="room-info-badge">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <div>
                        <div class="room-info-title">Chambre {{ $room->number }}</div>
                        <span class="badge-status badge-reservation">{{ $roomType }}</span>
                    </div>
                </div>
                
                <div class="room-info-meta">
                    <span class="meta-item">
                        <i class="fas fa-users"></i> {{ $roomCapacity }} pers.
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-tag"></i> {{ $formattedRoomPrice }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-info-circle"></i> {{ $roomStatus }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Carte période recherchée --}}
        <div class="conflict-card">
            <div class="conflict-card-header">
                <h5><i class="fas fa-calendar-alt"></i> Période recherchée</h5>
            </div>
            <div class="conflict-card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="alert-modern alert-info">
                            <div class="alert-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="alert-content">
                                <h6>Arrivée</h6>
                                <strong>{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert-modern alert-warning">
                            <div class="alert-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="alert-content">
                                <h6>Départ</h6>
                                <strong>{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stats-mini-grid">
                    <div class="stats-mini-card">
                        <div class="stats-mini-label">Nuits</div>
                        <div class="stats-mini-value">{{ $nights }}</div>
                    </div>
                    <div class="stats-mini-card">
                        <div class="stats-mini-label">Total</div>
                        <div class="stats-mini-value" style="color:var(--g600);">{{ $formattedSearchPrice }}</div>
                    </div>
                    <div class="stats-mini-card">
                        <div class="stats-mini-label">Personnes</div>
                        <div class="stats-mini-value">{{ $totalGuests }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── RÉSUMÉ DES CONFLITS ─────────────────── --}}
    <div class="anim-3">
        @if($conflicts->count() > 0)
        <div class="conflict-card" style="border-color: var(--r100);">
            <div class="conflict-card-header" style="background: var(--r50); border-bottom-color: var(--r100);">
                <h5 style="color: var(--r600);">
                    <i class="fas fa-exclamation-triangle" style="color: var(--r500);"></i>
                    Réservations en conflit ({{ $conflicts->count() }})
                </h5>
                <span class="badge-status" style="background:var(--r500);color:white;">{{ $overlapPercentage }}% chevauchement</span>
            </div>
            <div class="conflict-card-body">
                
                {{-- Alerte --}}
                <div class="alert-modern alert-warning mb-4">
                    <div class="alert-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h6>Attention !</h6>
                        <p>
                            Cette chambre n'est pas disponible pour la période demandée 
                            car elle est déjà réservée pendant <strong>{{ $totalOverlapDays }}</strong> 
                            jour(s) sur les <strong>{{ $nights }}</strong> nuit(s) recherchées.
                        </p>
                        <p class="mb-0">
                            <strong>Nuits disponibles:</strong> {{ $availableNights }} / {{ $nights }}
                        </p>
                    </div>
                </div>

                {{-- Tableau des conflits --}}
                <div class="conflicts-table-wrapper">
                    <table class="conflicts-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Arrivée</th>
                                <th>Départ</th>
                                <th>Nuits</th>
                                <th>Statut</th>
                                <th>Chevauchement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conflictAnalysis as $conflict)
                            <tr>
                                <td>
                                    <div class="client-mini">
                                        <div class="client-mini-avatar">
                                            <i class="fas fa-user fa-xs"></i>
                                        </div>
                                        <span class="client-mini-name">{{ $conflict['customer_name'] }}</span>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($conflict['transaction']->check_in)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($conflict['transaction']->check_out)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge-status badge-reservation">
                                        {{ $conflict['transaction']->check_in->diffInDays($conflict['transaction']->check_out) }} nuit(s)
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status badge-{{ $conflict['status_color'] == 'success' ? 'active' : ($conflict['status_color'] == 'warning' ? 'reservation' : 'cancelled') }}">
                                        {{ $conflict['status_label'] }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $percentage = ($conflict['overlap_days'] / $nights) * 100;
                                    @endphp
                                    <div class="progress-container">
                                        <div class="progress-bar-modern">
                                            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <small style="color:var(--s400);">{{ $conflict['overlap_days'] }} jour(s)</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('transaction.show', $conflict['transaction']->id) }}" 
                                       class="btn-db btn-db-ghost btn-sm" style="padding:4px 10px;font-size:.7rem;">
                                        <i class="fas fa-external-link-alt"></i> Voir
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="conflict-card" style="border-color: var(--g200);">
            <div class="conflict-card-header" style="background: var(--g50); border-bottom-color: var(--g200);">
                <h5 style="color: var(--g700);">
                    <i class="fas fa-check-circle" style="color: var(--g600);"></i>
                    Aucun conflit détecté !
                </h5>
            </div>
            <div class="conflict-card-body">
                <div class="text-center py-4">
                    <div style="width:80px;height:80px;background:var(--g50);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-check-circle fa-3x" style="color:var(--g600);"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color:var(--s800);">Chambre disponible</h4>
                    <p class="text-muted mb-4">Cette chambre est disponible pour la période demandée.</p>
                    <a href="{{ route('transaction.reservation.createIdentity', [
                        'room_id' => $room->id,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'adults' => $adults,
                        'children' => $children
                    ]) }}" 
                       class="btn-db btn-db-success btn-lg">
                        <i class="fas fa-book me-2"></i>
                        Réserver maintenant
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ─── SUGGESTIONS ET ALTERNATIVES ─────────── --}}
    @if($conflicts->count() > 0)
    <div class="anim-4 mt-4">
        <div class="conflict-card">
            <div class="conflict-card-header">
                <h5><i class="fas fa-lightbulb"></i> Suggestions</h5>
            </div>
            <div class="conflict-card-body">
                <div class="suggestions-grid">
                    <div class="suggestion-card">
                        <div class="suggestion-title">
                            <i class="fas fa-calendar-plus"></i>
                            Changer les dates
                        </div>
                        <div class="suggestion-text">
                            Essayez de modifier vos dates pour éviter les périodes de chevauchement.
                        </div>
                        <a href="{{ route('availability.search') }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&children={{ $children }}" 
                           class="btn-db btn-db-ghost w-100">
                            <i class="fas fa-edit me-2"></i>
                            Modifier la recherche
                        </a>
                    </div>
                    <div class="suggestion-card">
                        <div class="suggestion-title">
                            <i class="fas fa-exchange-alt"></i>
                            Changer de chambre
                        </div>
                        <div class="suggestion-text">
                            Consultez les autres chambres disponibles pour les mêmes dates.
                        </div>
                        <a href="{{ route('availability.search') }}" 
                           class="btn-db btn-db-ghost w-100">
                            <i class="fas fa-search me-2"></i>
                            Rechercher d'autres chambres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });

    // Confirmation avant de réserver
    const reserveButton = document.querySelector('a[href*="reservation.createIdentity"]');
    if (reserveButton) {
        reserveButton.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir réserver cette chambre pour cette période ?')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush