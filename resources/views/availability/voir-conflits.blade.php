{{-- resources/views/availability/conflicts.blade.php --}}
@extends('template.master')

@section('title', 'Conflits de réservation')

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
}

* { box-sizing: border-box; }

.cf-page {
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
@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to   { opacity: 1; transform: translateX(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }
.anim-3 { animation: fadeSlide .4s .16s ease both; }
.anim-4 { animation: fadeSlide .4s .24s ease both; }

/* ══════════════════════════════════════════════
   BOUTONS
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
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
}
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
}
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   CARTES
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-xs);
    transition: var(--transition);
}
.card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-md);
}
.card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: .95rem;
}
.card-body {
    padding: 22px;
}

/* ── Statuts ── */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   EN-TÊTE
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.page-header h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
}
.page-header h1 i {
    color: var(--red-500);
    margin-right: 8px;
}
.page-header p {
    color: var(--gray-500);
    font-size: .8rem;
}

/* ══════════════════════════════════════════════
   INFO ROWS
══════════════════════════════════════════════ */
.info-row {
    margin-bottom: 12px;
}
.info-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 2px;
}
.info-value {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-800);
}
.info-value-lg {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
}

/* ══════════════════════════════════════════════
   STAT CARD
══════════════════════════════════════════════ */
.stat-card {
    text-align: center;
    padding: 24px;
    border-radius: var(--rxl);
    background: var(--white);
    border: 1.5px solid var(--gray-200);
}
.stat-card-green { border-color: var(--green-500); }
.stat-card-red { border-color: var(--red-500); }
.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--gray-900);
}

/* ══════════════════════════════════════════════
   TABLEAU
══════════════════════════════════════════════ */
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th {
    text-align: left;
    padding: 14px 16px;
    background: var(--gray-50);
    color: var(--gray-500);
    font-weight: 600;
    font-size: .7rem;
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
}
.table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
}
.table tr {
    transition: var(--transition);
    animation: slideIn .3s ease both;
}
.table tr:nth-child(1) { animation-delay: .05s; }
.table tr:nth-child(2) { animation-delay: .10s; }
.table tr:nth-child(3) { animation-delay: .15s; }
.table tr:nth-child(4) { animation-delay: .20s; }
.table tr:nth-child(5) { animation-delay: .25s; }
.table tr:hover td {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   TIMELINE
══════════════════════════════════════════════ */
.timeline-simple {
    background: var(--surface);
    border-radius: var(--rl);
    padding: 20px;
}
.timeline-visual {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    justify-content: center;
    margin: 20px 0;
}
.timeline-day {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    font-size: .7rem;
    font-weight: 600;
    cursor: help;
}
.timeline-day.available {
    background: var(--green-600);
    color: white;
}
.timeline-day.conflict {
    background: var(--red-500);
    color: white;
}
.legend-color {
    width: 20px;
    height: 10px;
    border-radius: 2px;
}
.legend-color.green { background: var(--green-600); }
.legend-color.red { background: var(--red-500); }

/* ══════════════════════════════════════════════
   SOLUTION CARDS
══════════════════════════════════════════════ */
.solution-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 24px 16px;
    text-align: center;
    height: 100%;
    transition: var(--transition);
}
.solution-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.solution-icon {
    font-size: 2rem;
    margin-bottom: 12px;
}
.solution-icon.green { color: var(--green-600); }
.solution-icon.red { color: var(--red-500); }
.solution-icon.gray { color: var(--gray-500); }
.solution-title {
    font-weight: 600;
    font-size: .9rem;
    margin-bottom: 8px;
}
.solution-text {
    color: var(--gray-500);
    font-size: .75rem;
    margin-bottom: 16px;
}

/* ══════════════════════════════════════════════
   ALERTES
══════════════════════════════════════════════ */
.alert {
    padding: 16px 20px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
.alert-icon {
    font-size: 1.4rem;
    flex-shrink: 0;
}
</style>

<div class="cf-page">

    {{-- En-tête --}}
    <div class="page-header anim-1">
        <div>
            <h1>
                <i class="fas fa-exclamation-triangle"></i>
                Conflits de réservation
            </h1>
            <p>Chambre {{ $room->number }} - {{ $room->type->name ?? 'Standard' }}</p>
        </div>
        <a href="{{ route('availability.search') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Retour à la recherche
        </a>
    </div>

    {{-- Informations de recherche --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8 anim-2">
            <div class="card">
                <div class="card-header">
                    <h5>Votre recherche</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-label">Arrivée</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($checkIn)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-label">Départ</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($checkOut)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-label">Durée</div>
                            <div class="info-value">{{ $nights }} nuit(s)</div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-label">Adultes</div>
                            <div class="info-value">{{ $adults }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-label">Enfants</div>
                            <div class="info-value">{{ $children }}</div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="info-label">Prix total pour cette période</div>
                            <div class="info-value-lg">{{ $formattedSearchPrice }}</div>
                            <small class="text-muted">({{ $formattedRoomPrice }})</small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-4">
                                <div>
                                    <div class="info-label">Capacité</div>
                                    <div class="info-value">{{ $roomCapacity }} personnes</div>
                                </div>
                                <div>
                                    <div class="info-label">Statut</div>
                                    <div class="info-value">{{ $roomStatus }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statut conflit --}}
        <div class="col-md-4 anim-3">
            <div class="stat-card {{ $conflicts->count() > 0 ? 'stat-card-red' : 'stat-card-green' }}">
                @if($conflicts->count() > 0)
                    <div class="stat-icon" style="color:var(--red-500);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-number">{{ $conflicts->count() }}</div>
                    <div class="badge badge-red mb-2">réservation(s) en conflit</div>
                    <div class="badge badge-gray">Indisponible</div>
                @else
                    <div class="stat-icon" style="color:var(--green-600);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">✓</div>
                    <div class="badge badge-green">Aucun conflit</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Conflits --}}
    @if($conflicts->count() > 0)
    <div class="row mb-4 anim-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: var(--red-50);">
                    <h5 style="color:var(--red-600);">
                        <i class="fas fa-calendar-times me-2"></i>
                        Réservations en conflit ({{ $conflicts->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-red">
                        <div class="alert-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <strong>Cette chambre n'est pas disponible pour votre période</strong>
                            <p class="mb-0">La chambre {{ $room->number }} est déjà réservée pendant votre séjour.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Prix</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conflicts as $reservation)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $reservation->customer->name ?? 'Client inconnu' }}</div>
                                        <small class="text-muted">{{ $reservation->customer->phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $reservation->check_in->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $reservation->check_in->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $reservation->check_out->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $reservation->check_out->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-gray">
                                            {{ $reservation->check_in->diffInDays($reservation->check_out) }} nuit(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($reservation->status == 'active')
                                            <span class="badge badge-green">En séjour</span>
                                        @elseif($reservation->status == 'reservation')
                                            <span class="badge badge-gray">Réservée</span>
                                        @else
                                            <span class="badge badge-gray">{{ $reservation->status }}</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <a href="{{ route('transaction.show', $reservation->id) }}" 
                                           class="btn btn-outline btn-sm" style="padding:4px 10px; font-size:.7rem;">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Timeline --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt me-2" style="color:var(--green-600);"></i> Visualisation de la période</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-simple">
                        @php
                            $startDate = \Carbon\Carbon::parse($checkIn);
                            $endDate = \Carbon\Carbon::parse($checkOut);
                            $searchDays = $startDate->diffInDays($endDate);
                        @endphp
                        
                        <div class="d-flex align-items-center gap-4 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="legend-color green"></div>
                                <small class="text-muted">Votre recherche</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="legend-color red"></div>
                                <small class="text-muted">Réservation existante</small>
                            </div>
                        </div>
                        
                        <div class="timeline-visual">
                            @for($i = 0; $i < $searchDays; $i++)
                                @php
                                    $currentDate = $startDate->copy()->addDays($i);
                                    $isConflict = false;
                                    
                                    foreach($conflicts as $reservation) {
                                        if ($currentDate->between(
                                            $reservation->check_in->copy()->startOfDay(),
                                            $reservation->check_out->copy()->subDay()->endOfDay()
                                        )) {
                                            $isConflict = true;
                                            break;
                                        }
                                    }
                                @endphp
                                <div class="timeline-day {{ $isConflict ? 'conflict' : 'available' }}"
                                     title="{{ $currentDate->format('d/m/Y') }}">
                                    {{ $currentDate->format('d') }}
                                </div>
                            @endfor
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <small class="text-muted">{{ $startDate->format('d/m/Y') }}</small>
                            <small class="text-muted">{{ $endDate->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Solutions alternatives --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="solution-card">
                <div class="solution-icon green">
                    <i class="fas fa-search"></i>
                </div>
                <div class="solution-title">Option 1 : Autres chambres</div>
                <div class="solution-text">Trouvez d'autres chambres disponibles pour vos dates</div>
                <a href="{{ route('availability.search') }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&children={{ $children }}" 
                   class="btn btn-outline btn-sm">
                    <i class="fas fa-bed me-2"></i> Voir disponibilités
                </a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="solution-card">
                <div class="solution-icon red">
                    <i class="fas fa-calendar-edit"></i>
                </div>
                <div class="solution-title">Option 2 : Modifier vos dates</div>
                <div class="solution-text">Changez les dates de votre séjour</div>
                <a href="{{ route('availability.search') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-calendar me-2"></i> Modifier
                </a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="solution-card">
                <div class="solution-icon gray">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="solution-title">Option 3 : Attendre</div>
                <div class="solution-text">
                    Disponible à partir du 
                    @php
                        $nextAvailable = $conflicts->sortBy('check_out')->first();
                    @endphp
                    @if($nextAvailable)
                        <strong>{{ $nextAvailable->check_out->format('d/m/Y') }}</strong>
                    @else
                        <strong>prochainement</strong>
                    @endif
                </div>
                <a href="{{ route('availability.calendar') }}?room_type={{ $room->type_id }}" 
                   class="btn btn-outline btn-sm">
                    <i class="fas fa-calendar-alt me-2"></i> Calendrier
                </a>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation supplémentaire pour les lignes
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach((row, index) => {
        row.style.animation = `slideIn .3s ease ${.05 * index}s both`;
    });
});
</script>

@endsection