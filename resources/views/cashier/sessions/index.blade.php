@extends('template.master')

@section('title', 'Sessions de Caisse')

@push('styles')
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

* { box-sizing: border-box; }

.sessions-page {
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
   HEADER
══════════════════════════════════════════════ */
.sessions-header {
    background: var(--white);
    border-bottom: 1.5px solid var(--gray-200);
    padding: 20px 0;
    margin-bottom: 24px;
}
.header-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title i {
    color: var(--green-600);
    margin-right: 10px;
}
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .active {
    color: var(--gray-600);
    font-weight: 500;
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
}
.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
}

/* ══════════════════════════════════════════════
   STATS CARDS
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 18px;
    transition: var(--transition);
}
.stat-card:hover {
    border-color: var(--green-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}
.stat-label {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--gray-900);
    line-height: 1;
}
.stat-value-green { color: var(--green-600); }
.stat-value-gray { color: var(--gray-600); }
.stat-subtitle {
    font-size: .7rem;
    color: var(--gray-400);
    margin-top: 4px;
}

/* ══════════════════════════════════════════════
   FILTERS
══════════════════════════════════════════════ */
.filters-row {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rl);
    padding: 16px 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}
.filter-group {
    flex: 1;
    min-width: 180px;
}
.filter-select, .filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
    color: var(--gray-700);
    background: var(--white);
}
.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgba(46,133,64,.1);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 14px 16px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    text-align: left;
}
.table tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr {
    transition: var(--transition);
}
.table tbody tr:hover td {
    background: var(--green-50);
    cursor: pointer;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.user-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    object-fit: cover;
}
.user-details {
    line-height: 1.3;
}
.user-name {
    font-weight: 600;
    color: var(--gray-800);
}
.user-role {
    font-size: .65rem;
    color: var(--gray-500);
}

/* ══════════════════════════════════════════════
   MONTANTS
══════════════════════════════════════════════ */
.amount {
    font-weight: 600;
    font-family: var(--mono);
}
.amount-green { color: var(--green-600); }
.amount-red { color: var(--red-500); }
.amount-gray { color: var(--gray-600); }
.amount-diff {
    font-size: .75rem;
    margin-top: 2px;
    display: block;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    width: 72px;
    height: 72px;
    background: var(--gray-100);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}

/* ══════════════════════════════════════════════
   PAGINATION
══════════════════════════════════════════════ */
.pagination {
    padding: 16px 20px;
    border-top: 1.5px solid var(--gray-200);
    display: flex;
    justify-content: flex-end;
}
.pagination .page-link {
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
    margin: 0 2px;
    border-radius: var(--r);
}
.pagination .page-item.active .page-link {
    background: var(--green-600);
    border-color: var(--green-600);
    color: white;
}

/* ══════════════════════════════════════════════
   TOOLTIP
══════════════════════════════════════════════ */
[data-tooltip] {
    position: relative;
    cursor: help;
}
[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 4px 8px;
    background: var(--gray-800);
    color: white;
    font-size: .65rem;
    border-radius: var(--r);
    white-space: nowrap;
    display: none;
    z-index: 10;
}
[data-tooltip]:hover:before {
    display: block;
}
</style>
@endpush

@section('content')
<div class="sessions-page">

    {{-- Header --}}
    <div class="sessions-header anim-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-history"></i>
                    Sessions de Caisse
                </h1>
                <p class="text-muted small mt-2">Gérez et consultez toutes les sessions de caisse</p>
            </div>
            @if(in_array(auth()->user()->role, ['Admin', 'Super']))
            <a href="{{ route('cashier.sessions.create') }}" class="btn btn-green">
                <i class="fas fa-plus"></i> Nouvelle session
            </a>
            @endif
        </div>
        
        <div class="breadcrumb">
            <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <a href="{{ route('cashier.dashboard') }}">Caissier</a>
            <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
            <span class="active">Sessions</span>
        </div>
    </div>

    {{-- Statistiques --}}
    @php
        $totalSessions = $sessions->total();
        $activeSessions = $sessions->where('status', 'active')->count();
        $totalRevenue = $sessions->sum(function($session) {
            return $session->final_balance ?? $session->current_balance ?? 0;
        });
        $avgSession = $totalSessions > 0 ? $totalRevenue / $totalSessions : 0;
    @endphp

    <div class="stats-grid anim-2">
        <div class="stat-card">
            <div class="stat-label">Total sessions</div>
            <div class="stat-value">{{ $totalSessions }}</div>
            <div class="stat-subtitle">Depuis le début</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Sessions actives</div>
            <div class="stat-value stat-value-green">{{ $activeSessions }}</div>
            <div class="stat-subtitle">En cours</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Chiffre d'affaires</div>
            <div class="stat-value stat-value-green">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
            <div class="stat-subtitle">FCFA total</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Moyenne/session</div>
            <div class="stat-value">{{ number_format($avgSession, 0, ',', ' ') }}</div>
            <div class="stat-subtitle">FCFA par session</div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="filters-row anim-3">
        <div class="filter-group">
            <select class="filter-select" id="status-filter">
                <option value="">Tous les statuts</option>
                <option value="active">Sessions actives</option>
                <option value="closed">Sessions fermées</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="user-filter">
                <option value="">Tous les utilisateurs</option>
                @if(isset($allUsers) && $allUsers)
                    @foreach($allUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="filter-group">
            <input type="date" class="filter-input" id="date-filter" placeholder="Date">
        </div>
        <div class="filter-group d-flex gap-2">
            <button class="btn btn-green" id="apply-filters">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <button class="btn btn-gray" id="reset-filters">
                <i class="fas fa-undo"></i> Réinitialiser
            </button>
        </div>
    </div>

    {{-- Liste des sessions --}}
    <div class="table-card">
        @if($sessions->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Réceptionniste</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Durée</th>
                        <th>Initial</th>
                        <th>Final</th>
                        <th>Différence</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    @php
                        $finalAmount = $session->final_balance ?? $session->current_balance ?? 0;
                        $difference = $finalAmount - $session->initial_balance;
                        $duration = $session->end_time 
                            ? $session->start_time->diff($session->end_time)
                            : $session->start_time->diff(now());
                        $hours = $duration->h + ($duration->days * 24);
                        $mins = $duration->i;
                    @endphp
                    <tr onclick="window.location='{{ route('cashier.sessions.show', $session) }}'">
                        <td><strong>#{{ $session->id }}</strong></td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if($session->user && $session->user->getAvatar())
                                        <img src="{{ $session->user->getAvatar() }}" alt="">
                                    @else
                                        {{ strtoupper(substr($session->user->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="user-details">
                                    <div class="user-name">{{ $session->user->name ?? 'Utilisateur inconnu' }}</div>
                                    <div class="user-role">{{ $session->user->role ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $session->start_time->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $session->start_time->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($session->end_time)
                                <div>{{ $session->end_time->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $session->end_time->format('H:i') }}</small>
                            @else
                                <span class="badge badge-green">En cours</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-gray">
                                {{ $hours }}h {{ $mins }}min
                            </span>
                        </td>
                        <td class="amount">{{ number_format($session->initial_balance, 0, ',', ' ') }}</td>
                        <td class="amount">{{ number_format($finalAmount, 0, ',', ' ') }}</td>
                        <td>
                            <span class="amount {{ $difference > 0 ? 'amount-green' : ($difference < 0 ? 'amount-red' : '') }}">
                                {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 0, ',', ' ') }}
                            </span>
                            @if($session->status == 'active')
                                <span class="amount-diff text-muted" data-tooltip="Solde actuel">(estimé)</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $session->status == 'active' ? 'badge-green' : 'badge-gray' }}">
                                {{ $session->status == 'active' ? 'Active' : 'Fermée' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1" onclick="event.stopPropagation();">
                                <a href="{{ route('cashier.sessions.show', $session) }}" 
                                   class="btn-icon" 
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($session->status == 'closed')
                                <a href="{{ route('cashier.sessions.report', $session) }}" 
                                   class="btn-icon"
                                   title="Rapport détaillé">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(method_exists($sessions, 'links'))
        <div class="pagination">
            {{ $sessions->links() }}
        </div>
        @endif
        
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-history"></i>
            </div>
            <h5 class="fw-bold mb-2">Aucune session</h5>
            <p class="text-muted mb-3">Aucune session de caisse n'a été trouvée.</p>
            @if(in_array(auth()->user()->role, ['Admin', 'Super']))
            <a href="{{ route('cashier.sessions.create') }}" class="btn btn-green">
                <i class="fas fa-plus"></i> Démarrer une session
            </a>
            @endif
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtres
    const statusFilter = document.getElementById('status-filter');
    const userFilter = document.getElementById('user-filter');
    const dateFilter = document.getElementById('date-filter');
    const applyBtn = document.getElementById('apply-filters');
    const resetBtn = document.getElementById('reset-filters');
    
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const url = new URL(window.location.href);
            statusFilter.value ? url.searchParams.set('status', statusFilter.value) : url.searchParams.delete('status');
            userFilter.value ? url.searchParams.set('user_id', userFilter.value) : url.searchParams.delete('user_id');
            dateFilter.value ? url.searchParams.set('date', dateFilter.value) : url.searchParams.delete('date');
            window.location.href = url.toString();
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            url.searchParams.delete('user_id');
            url.searchParams.delete('date');
            window.location.href = url.toString();
        });
    }
    
    // Charger les filtres existants
    const params = new URLSearchParams(window.location.search);
    if (statusFilter && params.has('status')) statusFilter.value = params.get('status');
    if (userFilter && params.has('user_id')) userFilter.value = params.get('user_id');
    if (dateFilter && params.has('date')) dateFilter.value = params.get('date');
});
</script>
@endpush

@endsection