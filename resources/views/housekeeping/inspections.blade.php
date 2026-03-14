@extends('template.master')

@section('title', 'Chambres à Inspecter')

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

.inspect-page {
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
}
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    color: white;
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
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
}
.stat-left h6 {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.stat-left h2 {
    font-size: 2rem;
    font-weight: 700;
    font-family: var(--mono);
    color: var(--gray-900);
    line-height: 1;
    margin-bottom: 2px;
}
.stat-left small {
    font-size: .65rem;
    color: var(--gray-400);
}
.stat-icon {
    font-size: 2rem;
    opacity: .5;
}
.stat-icon.yellow { color: var(--red-500); }
.stat-icon.red { color: var(--red-500); }
.stat-icon.green { color: var(--green-600); }
.stat-icon.blue { color: var(--green-600); }

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header.yellow {
    background: var(--red-500);
    color: white;
}
.card-header i { color: white; }
.card-header .badge {
    background: rgba(255,255,255,.2);
    color: white;
    border: 1.5px solid rgba(255,255,255,.2);
}
.card-body {
    padding: 0;
}
.card-footer {
    padding: 16px 20px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--gray-50);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 12px 16px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr:hover td {
    background: var(--green-50);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .65rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }
.badge-yellow { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }

/* ══════════════════════════════════════════════
   ROOM CHECKBOX
══════════════════════════════════════════════ */
.room-checkbox {
    accent-color: var(--green-600);
    width: 16px;
    height: 16px;
    cursor: pointer;
}
.room-number {
    display: flex;
    align-items: center;
    gap: 8px;
}
.room-icon {
    width: 32px;
    height: 32px;
    background: var(--red-50);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--red-500);
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    font-size: 3rem;
    color: var(--green-500);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 8px;
}
.empty-state p {
    color: var(--gray-400);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════════════
   CHECKLIST
══════════════════════════════════════════════ */
.checklist-section {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    padding: 20px;
    margin-top: 20px;
}
.checklist-title {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.checklist-title i {
    color: var(--green-600);
}
.checklist-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.checklist-col h6 {
    font-size: .8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.checklist-col h6 i {
    color: var(--green-600);
}
.checklist-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: .75rem;
    color: var(--gray-600);
}
.checklist-item i {
    color: var(--green-600);
    font-size: .8rem;
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 22px;
}
.modal-title i {
    color: var(--green-600);
}
.modal-body {
    padding: 22px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 22px;
}
.form-check-input {
    accent-color: var(--green-600);
    margin-right: 8px;
}
.form-check-label {
    font-size: .8rem;
    color: var(--gray-700);
}
.rating {
    direction: rtl;
    unicode-bidi: bidi-override;
}
.rating input {
    display: none;
}
.rating label {
    color: var(--gray-300);
    font-size: 2rem;
    padding: 0 4px;
    cursor: pointer;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: var(--green-600);
}
</style>

<div class="inspect-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">Inspections</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-clipboard-check"></i></span>
                <h1>Inspections <em>requises</em></h1>
            </div>
            <p class="header-subtitle">Chambres nécessitant une inspection de qualité</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button class="btn btn-red" onclick="markAllInspected()">
                <i class="fas fa-check-double"></i> Tout marquer
            </button>
        </div>
    </div>

    {{-- Statistiques --}}
    @php
        $waiting24h = $inspectionRooms->filter(fn($r) => $r->inspection_requested_at && $r->inspection_requested_at->diffInHours(now()) > 24)->count();
        $inspectedToday = \App\Models\Room::where('needs_inspection', false)->whereDate('inspected_at', today())->count();
        $avgHours = $inspectionRooms->avg(fn($r) => $r->inspection_requested_at ? $r->inspection_requested_at->diffInHours(now()) : 0);
    @endphp

    <div class="stats-grid anim-3">
        <div class="stat-card">
            <div class="stat-left">
                <h6>À inspecter</h6>
                <h2>{{ $inspectionRooms->count() }}</h2>
                <small>En attente</small>
            </div>
            <div class="stat-icon yellow"><i class="fas fa-clipboard-list"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Attente >24h</h6>
                <h2>{{ $waiting24h }}</h2>
                <small>Priorité haute</small>
            </div>
            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Inspectées aujourd'hui</h6>
                <h2>{{ $inspectedToday }}</h2>
                <small>Déjà traitées</small>
            </div>
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-left">
                <h6>Moyenne d'attente</h6>
                <h2>{{ round($avgHours) }}h</h2>
                <small>Temps d'attente</small>
            </div>
            <div class="stat-icon blue"><i class="fas fa-hourglass-half"></i></div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card">
        <div class="card-header yellow">
            <div><i class="fas fa-list"></i> Liste des chambres à inspecter</div>
            <span class="badge badge-yellow">{{ $inspectionRooms->count() }} inspection(s)</span>
        </div>
        <div class="card-body">
            @if($inspectionRooms->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" id="selectAll" class="room-checkbox"></th>
                                <th>Chambre</th>
                                <th>Type</th>
                                <th>Demandée le</th>
                                <th>Demandée par</th>
                                <th>Attente</th>
                                <th>Priorité</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inspectionRooms as $room)
                            @php
                                $waitHours = $room->inspection_requested_at ? $room->inspection_requested_at->diffInHours(now()) : 0;
                                $priority = $waitHours > 48 ? 'red' : ($waitHours > 24 ? 'yellow' : 'green');
                                $priorityText = $waitHours > 48 ? 'Haute' : ($waitHours > 24 ? 'Moyenne' : 'Basse');
                            @endphp
                            <tr>
                                <td><input type="checkbox" class="room-checkbox" value="{{ $room->id }}"></td>
                                <td>
                                    <div class="room-number">
                                        <div class="room-icon"><i class="fas fa-door-closed"></i></div>
                                        <div>
                                            <strong>{{ $room->number }}</strong>
                                            <small class="d-block text-muted">Étage {{ substr($room->number, 0, 1) ?? '?' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-gray">{{ $room->type->name ?? 'Standard' }}</span></td>
                                <td>
                                    @if($room->inspection_requested_at)
                                        <div>{{ $room->inspection_requested_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $room->inspection_requested_at->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($room->inspection_requested_by)
                                        <span class="badge badge-gray">{{ \App\Models\User::find($room->inspection_requested_by)->name ?? 'Inconnu' }}</span>
                                    @else
                                        <span class="text-muted">Système</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $priority }}">{{ round($waitHours) }}h</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $priority }}">{{ $priorityText }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <form action="{{ route('housekeeping.complete-inspection', $room->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-green btn-sm"><i class="fas fa-check"></i> Inspecter</button>
                                        </form>
                                        <button class="btn btn-gray btn-sm" onclick="showInspectionModal({{ $room->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-check-circle"></i></div>
                    <h4>Toutes les inspections sont terminées !</h4>
                    <p>Aucune chambre ne nécessite d'inspection pour le moment.</p>
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-green">Retour au dashboard</a>
                </div>
            @endif
        </div>
        @if($inspectionRooms->count() > 0)
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-red" onclick="markSelectedInspected()">
                    <i class="fas fa-check"></i> Marquer sélectionnées
                </button>
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Priorité: 
                    <span class="badge badge-red">Haute >48h</span>
                    <span class="badge badge-yellow">Moyenne 24-48h</span>
                    <span class="badge badge-green">Basse <24h</span>
                </small>
            </div>
        </div>
        @endif
    </div>

    {{-- Checklist --}}
    <div class="checklist-section">
        <div class="checklist-title">
            <i class="fas fa-clipboard-list"></i>
            Checklist d'inspection standard
        </div>
        <div class="checklist-grid">
            <div class="checklist-col">
                <h6><i class="fas fa-bath"></i> Salle de bain</h6>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Propreté sanitaires</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Robinetterie fonctionnelle</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Sol propre et sec</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Fournitures complètes</div>
            </div>
            <div class="checklist-col">
                <h6><i class="fas fa-bed"></i> Chambre</h6>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Literie impeccable</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Sol et surfaces propres</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Équipements fonctionnels</div>
                <div class="checklist-item"><i class="fas fa-check-circle"></i> Aération correcte</div>
            </div>
        </div>
    </div>

</div>

{{-- Modal d'inspection --}}
<div class="modal fade" id="inspectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clipboard-check"></i> Inspection détaillée</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="inspectionModalBody">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x" style="color:var(--green-600);"></i>
                    <p class="mt-2">Chargement...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.room-checkbox').forEach(cb => cb.checked = this.checked);
    });
});

function markAllInspected() {
    if (!confirm('Marquer toutes les chambres comme inspectées ?')) return;
    fetch('{{ route("housekeeping.bulk-complete-inspections") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ room_ids: Array.from(document.querySelectorAll('.room-checkbox')).map(cb => cb.value) })
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

function markSelectedInspected() {
    const ids = Array.from(document.querySelectorAll('.room-checkbox:checked')).map(cb => cb.value);
    if(ids.length === 0) return alert('Sélectionnez au moins une chambre');
    if(!confirm(`Marquer ${ids.length} chambre(s) comme inspectée(s) ?`)) return;
    fetch('{{ route("housekeeping.bulk-complete-inspections") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ room_ids: ids })
    }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
}

function showInspectionModal(roomId) {
    const modal = new bootstrap.Modal(document.getElementById('inspectionModal'));
    const body = document.getElementById('inspectionModalBody');
    body.innerHTML = `
        <form id="inspectionForm">
            <div class="mb-4">
                <h6>Chambre 101 - Inspection de qualité</h6>
                <p class="text-muted">Vérifiez chaque point de la checklist</p>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3"><i class="fas fa-bath" style="color:var(--green-600);"></i> Salle de bain</h6>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="b1"> <label for="b1">Propreté des sanitaires</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="b2"> <label for="b2">Robinetterie fonctionnelle</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="b3"> <label for="b3">Sol propre et sec</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="b4"> <label for="b4">Fournitures complètes</label></div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3"><i class="fas fa-bed" style="color:var(--green-600);"></i> Chambre</h6>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="r1"> <label for="r1">Literie impeccable</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="r2"> <label for="r2">Sol et surfaces propres</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="r3"> <label for="r3">Équipements fonctionnels</label></div>
                    <div class="mb-2"><input type="checkbox" class="form-check-input" id="r4"> <label for="r4">Aération correcte</label></div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Note globale</label>
                <div class="rating">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4" checked><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-gray" data-bs-dismiss="modal">Annuler</button>
                <button class="btn btn-green" type="submit">Valider</button>
            </div>
        </form>
    `;
    modal.show();
}
</script>

@endsection