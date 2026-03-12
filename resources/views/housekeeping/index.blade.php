@extends('template.master')

@section('title', 'Housekeeping - Nettoyage des Chambres')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    /* Palette principale - Vert */
    --primary-50: #E8F5F0;
    --primary-100: #C1E4D6;
    --primary-200: #96D3BA;
    --primary-300: #6BC29E;
    --primary-400: #4BB589;
    --primary-500: #2AA874;
    --primary-600: #25A06C;
    --primary-700: #1F9661;
    --primary-800: #198C57;
    --primary-900: #0F7C44;

    /* Couleurs d'état */
    --dirty: #EF4444;
    --dirty-dim: rgba(239, 68, 68, 0.1);
    --cleaning: #F59E0B;
    --cleaning-dim: rgba(245, 158, 11, 0.1);
    --clean: #10B981;
    --clean-dim: rgba(16, 185, 129, 0.1);
    --occupied: #3B82F6;
    --occupied-dim: rgba(59, 130, 246, 0.1);
    --maintenance: #8B5CF6;
    --maintenance-dim: rgba(139, 92, 246, 0.1);

    /* Neutres */
    --gray-50: #F9FAFB;
    --gray-100: #F3F4F6;
    --gray-200: #E5E7EB;
    --gray-300: #D1D5DB;
    --gray-400: #9CA3AF;
    --gray-500: #6B7280;
    --gray-600: #4B5563;
    --gray-700: #374151;
    --gray-800: #1F2937;
    --gray-900: #111827;

    /* Ombres */
    --shadow-sm: 0 1px 2px 0 rgba(42, 168, 116, 0.08);
    --shadow-md: 0 4px 6px -1px rgba(42, 168, 116, 0.12);
    --shadow-lg: 0 10px 15px -3px rgba(42, 168, 116, 0.15);
    
    /* Transitions */
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Border radius */
    --r: 14px;
}

* { 
    box-sizing: border-box; 
    margin:0; 
    padding:0;
}

body {
    background: var(--gray-50);
    color: var(--gray-900);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    line-height: 1.5;
}

/* ══════════════════════════════════════
   HEADER
══════════════════════════════════════ */
.hk-header {
    background: white;
    border-bottom: 1px solid var(--gray-200);
    padding: 24px 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.hk-header__inner {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.hk-header__title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.hk-header__icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(42, 168, 116, 0.3);
}

.hk-header__title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--gray-800);
    margin-bottom: 4px;
    letter-spacing: -0.5px;
}

.hk-header__title p {
    font-size: 14px;
    color: var(--gray-500);
}

.hk-header__actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ══════════════════════════════════════
   BUTTONS
══════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid transparent;
    transition: var(--transition);
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}

.btn--primary {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    color: white;
    box-shadow: 0 4px 8px rgba(42, 168, 116, 0.25);
}

.btn--primary:hover {
    background: linear-gradient(135deg, var(--primary-800), var(--primary-600));
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(42, 168, 116, 0.35);
    color: white;
}

.btn--outline {
    background: white;
    border: 2px solid var(--gray-200);
    color: var(--gray-700);
}

.btn--outline:hover {
    border-color: var(--primary-500);
    color: var(--primary-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.btn--success {
    background: var(--clean);
    color: white;
}

.btn--success:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn--danger {
    background: var(--dirty);
    color: white;
}

.btn--danger:hover {
    background: #DC2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn--warning {
    background: var(--cleaning);
    color: white;
}

.btn--warning:hover {
    background: #D97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 8px;
}

.btn-lg {
    padding: 14px 28px;
    font-size: 16px;
}

/* ══════════════════════════════════════
   MAIN CONTAINER
══════════════════════════════════════ */
.hk-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 32px 48px;
}

/* ══════════════════════════════════════
   STATS GRID
══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 16px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-500);
}

.stat-card.dirty::before { background: var(--dirty); }
.stat-card.cleaning::before { background: var(--cleaning); }
.stat-card.clean::before { background: var(--clean); }
.stat-card.occupied::before { background: var(--occupied); }
.stat-card.maintenance::before { background: var(--maintenance); }

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--gray-800);
    line-height: 1;
    margin-bottom: 4px;
    font-family: 'IBM Plex Mono', monospace;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 8px;
}

.stat-footer {
    font-size: 11px;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-footer i {
    font-size: 10px;
}

/* ══════════════════════════════════════
   ACTION BANNER
══════════════════════════════════════ */
.action-banner {
    background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}

.action-banner h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
}

.action-banner p {
    font-size: 14px;
    opacity: 0.9;
}

.action-banner .btn {
    background: white;
    color: var(--primary-700);
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-banner .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

/* ══════════════════════════════════════
   ROOMS GRID - SECTION PRINCIPALE
══════════════════════════════════════ */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: var(--primary-500);
}

.rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.room-card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    transition: var(--transition);
    position: relative;
}

.room-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-300);
}

.room-card__header {
    padding: 16px;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.room-card__number {
    font-size: 24px;
    font-weight: 800;
    color: var(--gray-800);
    font-family: 'IBM Plex Mono', monospace;
}

.room-card__badge {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.room-card__badge.dirty {
    background: var(--dirty-dim);
    color: var(--dirty);
}

.room-card__badge.cleaning {
    background: var(--cleaning-dim);
    color: var(--cleaning);
}

.room-card__badge.clean {
    background: var(--clean-dim);
    color: var(--clean);
}

.room-card__body {
    padding: 16px;
}

.room-card__type {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 4px;
}

.room-card__meta {
    font-size: 12px;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

.room-card__status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 16px;
}

.room-card__status.dirty {
    background: var(--dirty-dim);
    color: var(--dirty);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.room-card__status.cleaning {
    background: var(--cleaning-dim);
    color: var(--cleaning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.room-card__status.clean {
    background: var(--clean-dim);
    color: var(--clean);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Bouton de nettoyage en un clic */
.clean-btn {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 8px;
}

.clean-btn.dirty {
    background: var(--dirty);
    color: white;
}

.clean-btn.dirty:hover {
    background: #DC2626;
    transform: scale(1.02);
}

.clean-btn.clean {
    background: var(--clean-dim);
    color: var(--clean);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.clean-btn.clean:hover {
    background: var(--clean);
    color: white;
    border-color: var(--clean);
}

/* ══════════════════════════════════════
   DEPARTURES & ARRIVALS
══════════════════════════════════════ */
.side-card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 24px;
}

.side-card__header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.side-card__header h3 {
    font-size: 15px;
    font-weight: 700;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.side-card__header i {
    color: var(--primary-500);
}

.side-card__body {
    padding: 0;
}

.side-item {
    padding: 16px 20px;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.side-item:last-child {
    border-bottom: none;
}

.side-item__room {
    font-size: 16px;
    font-weight: 700;
    color: var(--gray-800);
    font-family: 'IBM Plex Mono', monospace;
    margin-right: 12px;
}

.side-item__info {
    flex: 1;
}

.side-item__name {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 2px;
}

.side-item__meta {
    font-size: 11px;
    color: var(--gray-500);
}

.side-item__status {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
}

.side-item__status.departed {
    background: var(--dirty-dim);
    color: var(--dirty);
}

.side-item__status.arriving {
    background: var(--clean-dim);
    color: var(--clean);
}

/* ══════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════ */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 24px;
}

.quick-action-btn {
    background: white;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 16px 12px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    border-color: var(--primary-500);
    box-shadow: var(--shadow-md);
}

.quick-action-btn i {
    font-size: 24px;
    color: var(--primary-500);
    margin-bottom: 8px;
}

.quick-action-btn span {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-700);
}

.quick-action-btn small {
    font-size: 11px;
    color: var(--gray-500);
}

/* ══════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════ */
.empty-state {
    padding: 60px 20px;
    text-align: center;
    background: white;
    border-radius: 16px;
    border: 1px solid var(--gray-200);
}

.empty-state i {
    font-size: 56px;
    color: var(--primary-200);
    margin-bottom: 16px;
}

.empty-state h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--gray-500);
    margin-bottom: 24px;
}

/* ══════════════════════════════════════
   ALERTS
══════════════════════════════════════ */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: var(--clean-dim);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: var(--clean);
}

.alert-success i {
    font-size: 20px;
}

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 1400px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1024px) {
    .hk-header__inner {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .rooms-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hk-header { padding: 20px; }
    .hk-container { padding: 0 20px 40px; }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .rooms-grid {
        grid-template-columns: 1fr;
    }
    
    .action-banner {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="hk-header">
    <div class="hk-header__inner">
        <div class="hk-header__title">
            <div class="hk-header__icon">
                <i class="fas fa-broom"></i>
            </div>
            <div>
                <h1>Housekeeping • Nettoyage</h1>
                <p>Gestion du nettoyage des chambres en un clic</p>
            </div>
        </div>
        <div class="hk-header__actions">
            <a href="{{ route('housekeeping.scan') }}" class="btn btn--outline">
                <i class="fas fa-qrcode"></i>
                Scanner QR
            </a>
            <a href="{{ route('housekeeping.reports') }}" class="btn btn--primary">
                <i class="fas fa-chart-bar"></i>
                Rapports
            </a>
        </div>
    </div>
</div>

{{-- MAIN CONTAINER --}}
<div class="hk-container">

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- STATS GRID --}}
    <div class="stats-grid">
        <div class="stat-card dirty">
            <div class="stat-value">{{ $stats['dirty_rooms'] ?? 0 }}</div>
            <div class="stat-label">À nettoyer</div>
            <div class="stat-footer">
                <i class="fas fa-broom"></i>
                Chambres sales
            </div>
        </div>
        
        <div class="stat-card clean">
            <div class="stat-value">{{ $stats['clean_rooms'] ?? 0 }}</div>
            <div class="stat-label">Nettoyées</div>
            <div class="stat-footer">
                <i class="fas fa-check-circle"></i>
                Prêtes
            </div>
        </div>
        
        <div class="stat-card occupied">
            <div class="stat-value">{{ $stats['occupied_rooms'] ?? 0 }}</div>
            <div class="stat-label">Occupées</div>
            <div class="stat-footer">
                <i class="fas fa-user"></i>
                Clients présents
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_rooms'] ?? 0 }}</div>
            <div class="stat-label">Total</div>
            <div class="stat-footer">
                <i class="fas fa-building"></i>
                Chambres
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $stats['cleaned_today'] ?? 0 }}</div>
            <div class="stat-label">Aujourd'hui</div>
            <div class="stat-footer">
                <i class="fas fa-calendar-day"></i>
                Nettoyées
            </div>
        </div>
        
        <div class="stat-card maintenance">
            <div class="stat-value">{{ $stats['maintenance_rooms'] ?? 0 }}</div>
            <div class="stat-label">Maintenance</div>
            <div class="stat-footer">
                <i class="fas fa-tools"></i>
                En réparation
            </div>
        </div>
    </div>

    {{-- ACTION BANNER --}}
    @if(($stats['dirty_rooms'] ?? 0) > 0)
    <div class="action-banner">
        <div>
            <h3><i class="fas fa-broom me-2"></i> {{ $stats['dirty_rooms'] ?? 0 }} chambre(s) à nettoyer</h3>
            <p>Cliquez simplement sur le bouton vert d'une chambre pour la marquer comme nettoyée</p>
        </div>
        <div>
            <a href="#dirty-section" class="btn">
                <i class="fas fa-arrow-down"></i>
                Voir les chambres
            </a>
        </div>
    </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="row">
        {{-- LEFT COLUMN - CHAMBRES À NETTOYER --}}
        <div class="col-lg-8">
            {{-- SECTION 1: CHAMBRES À NETTOYER (DIRTY) --}}
            <div class="section-title" id="dirty-section">
                <i class="fas fa-broom"></i>
                Chambres à nettoyer
                <span class="badge" style="background: var(--dirty-dim); color: var(--dirty); padding: 4px 12px;">
                    {{ $roomsByStatus['dirty']->count() }}
                </span>
            </div>

            @if($roomsByStatus['dirty']->count() > 0)
            <div class="rooms-grid">
                @foreach($roomsByStatus['dirty'] as $room)
                <div class="room-card">
                    <div class="room-card__header">
                        <div class="room-card__number">#{{ $room->number }}</div>
                        <div class="room-card__badge dirty">
                            <i class="fas fa-broom"></i>
                        </div>
                    </div>
                    <div class="room-card__body">
                        <div class="room-card__type">{{ $room->type->name ?? 'Chambre Standard' }}</div>
                        <div class="room-card__meta">
                            <i class="fas fa-user"></i> {{ $room->capacity }} pers.
                            @if($room->floor)
                            <i class="fas fa-layer-group ms-2"></i> Étage {{ $room->floor }}
                            @endif
                        </div>
                        <div class="room-card__status dirty">
                            <i class="fas fa-exclamation-circle"></i>
                            À nettoyer
                        </div>
                        
                        {{-- BOUTON UN CLIC POUR NETTOYER --}}
                        <form action="{{ route('housekeeping.clean-room', $room->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="clean-btn dirty">
                                <i class="fas fa-check-circle"></i>
                                Marquer comme nettoyée
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state mb-4">
                <i class="fas fa-check-circle" style="color: var(--clean);"></i>
                <h4>Aucune chambre à nettoyer</h4>
                <p>Toutes les chambres sont propres et prêtes</p>
            </div>
            @endif

            {{-- SECTION 2: CHAMBRES NETTOYÉES AUJOURD'HUI --}}
            <div class="section-title mt-4">
                <i class="fas fa-check-circle" style="color: var(--clean);"></i>
                Nettoyées aujourd'hui
                <span class="badge" style="background: var(--clean-dim); color: var(--clean); padding: 4px 12px;">
                    {{ $stats['cleaned_today'] ?? 0 }}
                </span>
            </div>

            @if(isset($roomsCleanedToday) && $roomsCleanedToday->count() > 0)
            <div class="rooms-grid">
                @foreach($roomsCleanedToday->take(4) as $room)
                <div class="room-card" style="opacity: 0.9;">
                    <div class="room-card__header">
                        <div class="room-card__number">#{{ $room->number }}</div>
                        <div class="room-card__badge clean">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div class="room-card__body">
                        <div class="room-card__type">{{ $room->type->name ?? 'Chambre Standard' }}</div>
                        <div class="room-card__meta">
                            <i class="fas fa-clock"></i> 
                            {{ $room->last_cleaned_at ? \Carbon\Carbon::parse($room->last_cleaned_at)->format('H:i') : 'N/A' }}
                        </div>
                        <div class="room-card__status clean">
                            <i class="fas fa-check-circle"></i>
                            Nettoyée
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN - INFOS RAPIDES --}}
        <div class="col-lg-4">
            {{-- DÉPARTS DU JOUR --}}
            <div class="side-card">
                <div class="side-card__header">
                    <h3>
                        <i class="fas fa-sign-out-alt"></i>
                        Départs aujourd'hui
                    </h3>
                    <span class="badge" style="background: var(--dirty-dim); color: var(--dirty);">
                        {{ $todayDepartures->count() }}
                    </span>
                </div>
                <div class="side-card__body">
                    @if($todayDepartures->count() > 0)
                        @foreach($todayDepartures->take(5) as $departure)
                        <div class="side-item">
                            <div class="side-item__room">{{ $departure->room->number }}</div>
                            <div class="side-item__info">
                                <div class="side-item__name">{{ $departure->customer->name ?? 'Client' }}</div>
                                <div class="side-item__meta">
                                    <i class="fas fa-clock"></i> Départ: 12h00
                                </div>
                            </div>
                            <div class="side-item__status departed">
                                À nettoyer
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-check mb-2"></i>
                            <p class="mb-0">Aucun départ aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ARRIVÉES DU JOUR --}}
            <div class="side-card">
                <div class="side-card__header">
                    <h3>
                        <i class="fas fa-sign-in-alt"></i>
                        Arrivées aujourd'hui
                    </h3>
                    <span class="badge" style="background: var(--clean-dim); color: var(--clean);">
                        {{ $todayArrivals->count() }}
                    </span>
                </div>
                <div class="side-card__body">
                    @if($todayArrivals->count() > 0)
                        @foreach($todayArrivals->take(5) as $arrival)
                        <div class="side-item">
                            <div class="side-item__room">{{ $arrival->room->number }}</div>
                            <div class="side-item__info">
                                <div class="side-item__name">{{ $arrival->customer->name ?? 'Client' }}</div>
                                <div class="side-item__meta">
                                    <i class="fas fa-clock"></i> Arrivée: 14h00
                                </div>
                            </div>
                            <div class="side-item__status arriving">
                                À préparer
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times mb-2"></i>
                            <p class="mb-0">Aucune arrivée aujourd'hui</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ACTIONS RAPIDES --}}
            <div class="quick-actions">
                <a href="{{ route('housekeeping.to-clean') }}" class="quick-action-btn">
                    <i class="fas fa-broom"></i>
                    <span>À nettoyer</span>
                    <small>{{ $stats['dirty_rooms'] ?? 0 }}</small>
                </a>
                <a href="{{ route('housekeeping.maintenance') }}" class="quick-action-btn">
                    <i class="fas fa-tools"></i>
                    <span>Maintenance</span>
                    <small>{{ $stats['maintenance_rooms'] ?? 0 }}</small>
                </a>
                <a href="{{ route('housekeeping.mobile') }}" class="quick-action-btn">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Vue mobile</span>
                    <small>Scanner</small>
                </a>
                <a href="{{ route('housekeeping.daily-report') }}" class="quick-action-btn">
                    <i class="fas fa-file-alt"></i>
                    <span>Rapport</span>
                    <small>Quotidien</small>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 3 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 3000);
    
    // Confirmation rapide pour les boutons de nettoyage
    const cleanButtons = document.querySelectorAll('.clean-btn.dirty');
    cleanButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Marquer cette chambre comme nettoyée ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush