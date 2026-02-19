

<?php $__env->startSection('title', 'Chambre ' . $room->number); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root {
    --bg:       #f8fafc;
    --surf:     #ffffff;
    --surf2:    #f1f5f9;
    --surf3:    #e2e8f0;
    --brd:      #e2e8f0;
    --brd2:     #cbd5e1;
    --txt:      #0f172a;
    --txt2:     #475569;
    --txt3:     #94a3b8;
    --acc:      #3b82f6;
    --grn:      #10b981;
    --yel:      #f59e0b;
    --red:      #ef4444;
    --ora:      #f97316;
    --cyan:     #06b6d4;
    --r:        12px;
}

* { box-sizing: border-box; margin:0; padding:0; }
body {
    background: var(--bg);
    color: var(--txt);
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

/* ══════════════════════════════════════
   TOPBAR
══════════════════════════════════════ */
.room-topbar {
    background: var(--surf);
    border-bottom: 1px solid var(--brd);
    padding: 20px 28px;
    margin-bottom: 24px;
}
.room-topbar__inner {
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.room-topbar__title h1 {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -.5px;
    margin-bottom: 6px;
    color: var(--txt);
}
.room-topbar__meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.room-topbar__meta span {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--txt2);
}
.room-topbar__actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ══════════════════════════════════════
   BADGES
══════════════════════════════════════ */
.badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge--success { background: rgba(16,185,129,.15); color: var(--grn); }
.badge--warning { background: rgba(245,158,11,.15); color: var(--yel); }
.badge--danger  { background: rgba(239,68,68,.15); color: var(--red); }
.badge--info    { background: rgba(59,130,246,.15); color: var(--acc); }
.badge--light   { background: var(--surf3); color: var(--txt2); }

/* ══════════════════════════════════════
   BUTTONS
══════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid;
    text-decoration: none;
    transition: all .15s;
    cursor: pointer;
    white-space: nowrap;
}
.btn--primary {
    background: var(--acc);
    border-color: var(--acc);
    color: white;
}
.btn--primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
    transform: translateY(-1px);
}
.btn--success {
    background: var(--grn);
    border-color: var(--grn);
    color: white;
}
.btn--success:hover {
    background: #059669;
    border-color: #059669;
    color: white;
    transform: translateY(-1px);
}
.btn--warning {
    background: var(--yel);
    border-color: var(--yel);
    color: white;
}
.btn--warning:hover {
    background: #d97706;
    border-color: #d97706;
    color: white;
    transform: translateY(-1px);
}
.btn--outline {
    background: transparent;
    border-color: var(--brd2);
    color: var(--txt2);
}
.btn--outline:hover {
    background: var(--surf2);
    border-color: var(--brd2);
    color: var(--txt);
}

/* ══════════════════════════════════════
   MAIN CONTAINER
══════════════════════════════════════ */
.room-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 28px 48px;
}
.room-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 20px;
}

/* ══════════════════════════════════════
   CARDS
══════════════════════════════════════ */
.card {
    background: var(--surf);
    border: 1px solid var(--brd);
    border-radius: var(--r);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
    transition: box-shadow .2s;
}
.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.card__head {
    padding: 14px 20px;
    border-bottom: 1px solid var(--brd);
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 13px;
    color: var(--txt);
}
.card__head--primary { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border-bottom: none; }
.card__head--success { background: linear-gradient(135deg, #10b981, #059669); color: white; border-bottom: none; }
.card__head--warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-bottom: none; }
.card__head--info    { background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; border-bottom: none; }
.card__head--dark    { background: linear-gradient(135deg, #334155, #1e293b); color: white; border-bottom: none; }
.card__head i {
    font-size: 14px;
}
.card__badge {
    margin-left: auto;
    flex-shrink: 0;
}
.card__body {
    padding: 20px;
}

/* ══════════════════════════════════════
   INFO CARD
══════════════════════════════════════ */
.info-row {
    margin-bottom: 18px;
}
.info-row:last-child {
    margin-bottom: 0;
}
.info-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--txt3);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.info-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--txt);
}
.info-value--lg {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -.7px;
}
.info-value--price {
    font-family: 'JetBrains Mono', monospace;
    color: var(--grn);
}

/* ══════════════════════════════════════
   STATS GRID
══════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
.stat-item {
    text-align: center;
    padding: 12px;
    background: var(--surf2);
    border-radius: 8px;
}
.stat-value {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -.5px;
    color: var(--txt);
    margin-bottom: 2px;
}
.stat-label {
    font-size: 11px;
    color: var(--txt3);
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ══════════════════════════════════════
   FACILITIES
══════════════════════════════════════ */
.facilities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.facility-badge {
    padding: 6px 12px;
    background: var(--surf2);
    border: 1px solid var(--brd);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--txt2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.facility-badge i {
    font-size: 11px;
}

/* ══════════════════════════════════════
   CURRENT GUEST / AVAILABLE
══════════════════════════════════════ */
.guest-card {
    display: flex;
    align-items: center;
    gap: 16px;
}
.guest-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--surf2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    color: var(--txt2);
    flex-shrink: 0;
}
.guest-info {
    flex: 1;
}
.guest-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--txt);
    margin-bottom: 4px;
}
.guest-meta {
    font-size: 12px;
    color: var(--txt3);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.guest-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}
.guest-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
}

.available-state {
    text-align: center;
    padding: 36px 20px;
}
.available-state i {
    font-size: 48px;
    color: var(--grn);
    margin-bottom: 16px;
    opacity: .8;
}
.available-state h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--txt);
    margin-bottom: 8px;
}
.available-state p {
    color: var(--txt3);
    margin-bottom: 20px;
}

/* ══════════════════════════════════════
   CALENDAR
══════════════════════════════════════ */
.legend {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.legend-sq {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}
.legend-sq--avail   { background: rgba(16,185,129,.2); border: 1px solid rgba(16,185,129,.4); }
.legend-sq--occ     { background: rgba(239,68,68,.2); border: 1px solid rgba(239,68,68,.4); }
.legend-sq--unavail { background: rgba(148,163,184,.2); border: 1px solid rgba(148,163,184,.4); }
.legend-sq--today   { background: rgba(245,158,11,.25); border: 2px solid var(--yel); }
.legend-item span {
    font-size: 12px;
    color: var(--txt3);
}

.cal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.cal-table thead th {
    background: var(--surf2);
    border: 1px solid var(--brd);
    padding: 10px;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--txt3);
    text-transform: uppercase;
    letter-spacing: .4px;
}
.cal-table tbody td {
    border: 1px solid var(--brd);
    padding: 0;
    text-align: center;
    position: relative;
}
.cal-day {
    min-height: 68px;
    padding: 8px;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.cal-day:hover {
    transform: scale(1.04);
    z-index: 10;
    box-shadow: 0 0 0 2px var(--acc);
}
.cal-day__num {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 2px;
}
.cal-day__month {
    font-size: 10px;
    color: var(--txt3);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.cal-day__icon i {
    font-size: 13px;
}

.cal-day--avail {
    background: rgba(16,185,129,.12);
    border-left: 2px solid rgba(16,185,129,.3);
}
.cal-day--avail .cal-day__icon i {
    color: var(--grn);
}
.cal-day--avail:hover {
    background: rgba(16,185,129,.2);
}

.cal-day--occ {
    background: rgba(239,68,68,.12);
    border-left: 2px solid rgba(239,68,68,.3);
}
.cal-day--occ .cal-day__icon i {
    color: var(--red);
}
.cal-day--occ:hover {
    background: rgba(239,68,68,.2);
}

.cal-day--unavail {
    background: rgba(148,163,184,.08);
    border-left: 2px solid rgba(148,163,184,.2);
    cursor: not-allowed;
}
.cal-day--unavail .cal-day__icon i {
    color: var(--txt3);
    opacity: .5;
}
.cal-day--unavail:hover {
    transform: none;
    box-shadow: none;
}

.cal-day--today {
    background: rgba(245,158,11,.15) !important;
    border: 2px solid var(--yel) !important;
    font-weight: 700;
}

.conflict-badge {
    position: absolute;
    top: 3px;
    right: 3px;
    background: var(--red);
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 4px;
}

.cal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--brd);
}
.cal-footer__info {
    font-size: 12px;
    color: var(--txt3);
}

/* ══════════════════════════════════════
   NEXT RESERVATION
══════════════════════════════════════ */
.next-res {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.next-res__info h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--txt);
    margin-bottom: 4px;
}
.next-res__meta {
    font-size: 12px;
    color: var(--txt3);
}

/* ══════════════════════════════════════
   QUICK ACTIONS
══════════════════════════════════════ */
.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 12px;
    border-radius: 10px;
    border: 1px solid var(--brd);
    background: var(--surf);
    text-decoration: none;
    transition: all .15s;
    cursor: pointer;
    min-height: 120px;
}
.action-btn:hover:not(:disabled) {
    background: var(--surf2);
    border-color: var(--brd2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
.action-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
}
.action-btn__icon {
    font-size: 28px;
    margin-bottom: 10px;
}
.action-btn--primary .action-btn__icon { color: var(--acc); }
.action-btn--success .action-btn__icon { color: var(--grn); }
.action-btn--warning .action-btn__icon { color: var(--yel); }
.action-btn--info .action-btn__icon { color: var(--cyan); }
.action-btn__title {
    font-size: 13px;
    font-weight: 700;
    color: var(--txt);
    margin-bottom: 4px;
}
.action-btn__desc {
    font-size: 11px;
    color: var(--txt3);
    text-align: center;
}

/* ══════════════════════════════════════
   MODAL
══════════════════════════════════════ */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
}
.modal-header {
    border-bottom: 1px solid var(--brd);
    padding: 18px 24px;
}
.modal-title {
    font-weight: 700;
    font-size: 16px;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1px solid var(--brd);
    padding: 16px 24px;
}

/* ══════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════ */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.card {
    animation: fadeIn .3s ease both;
}

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width: 1200px) {
    .room-grid {
        grid-template-columns: 1fr;
    }
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 768px) {
    .room-topbar {
        padding: 16px 20px;
    }
    .room-topbar__inner {
        flex-direction: column;
        align-items: flex-start;
    }
    .room-topbar__actions {
        width: 100%;
        justify-content: space-between;
    }
    .room-container {
        padding: 0 20px 40px;
    }
    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .cal-day {
        min-height: 56px;
        padding: 6px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $roomStats = array_merge([
        'total_transactions' => 0,
        'total_revenue' => 0,
        'total_revenue_30d' => 0,
        'avg_stay_duration' => 0,
        'avg_daily_rate' => $room->price ?? 0,
        'occupancy_rate_30d' => 0,
        'next_available' => null,
        'formatted_next_available' => 'Immédiate',
        'last_30_days_revenue' => 0
    ], $roomStats ?? []);
    
    \Carbon\Carbon::setLocale('fr');
    
    $user = auth()->user();
    $canCheckOut = in_array($user->role ?? '', ['Super', 'Admin', 'Reception']);
?>


<div class="room-topbar">
    <div class="room-topbar__inner">
        <div class="room-topbar__title">
            <h1>Chambre <?php echo e($room->number); ?></h1>
            <div class="room-topbar__meta">
                <span class="badge badge--<?php echo e($room->room_status_id == 1 ? 'success' : ($room->room_status_id == 2 ? 'danger' : 'warning')); ?>">
                    <i class="fas fa-<?php echo e($room->room_status_id == 1 ? 'check' : ($room->room_status_id == 2 ? 'tools' : 'broom')); ?>"></i>
                    <?php echo e($room->roomStatus->name ?? 'Statut inconnu'); ?>

                </span>
                <span>
                    <i class="fas fa-bed"></i>
                    <?php echo e($room->type->name ?? 'Type inconnu'); ?>

                </span>
                <span>
                    <i class="fas fa-users"></i>
                    <?php echo e($room->capacity); ?> personnes
                </span>
            </div>
        </div>
        <div class="room-topbar__actions">
            <a href="<?php echo e(route('availability.calendar')); ?>?room_number=<?php echo e($room->number); ?>" class="btn btn--outline">
                <i class="fas fa-calendar-alt"></i>
                Calendrier
            </a>
            <a href="<?php echo e(route('availability.inventory')); ?>" class="btn btn--outline">
                <i class="fas fa-clipboard-list"></i>
                Inventaire
            </a>
            <a href="<?php echo e(route('room.edit', $room->id)); ?>" class="btn btn--warning">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
        </div>
    </div>
</div>


<div class="room-container">
    <div class="room-grid">

        
        <div>
            
            <div class="card" style="margin-bottom:20px">
                <div class="card__head card__head--primary">
                    <i class="fas fa-info-circle"></i>
                    Informations générales
                </div>
                <div class="card__body">
                    <div class="info-row">
                        <div class="info-label">Numéro de chambre</div>
                        <div class="info-value info-value--lg"><?php echo e($room->number); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Type</div>
                        <div class="info-value"><?php echo e($room->type->name ?? 'Type inconnu'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Prix par nuit</div>
                        <div class="info-value info-value--lg info-value--price">
                            <?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
                        <div class="info-row">
                            <div class="info-label">Capacité</div>
                            <div class="info-value">
                                <i class="fas fa-users" style="font-size:14px;margin-right:4px;color:var(--txt3)"></i>
                                <?php echo e($room->capacity); ?>

                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Étage</div>
                            <div class="info-value"><?php echo e($room->floor ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Surface</div>
                        <div class="info-value"><?php echo e($room->size ?? 'N/A'); ?> m²</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Dernière mise à jour</div>
                        <div class="info-value" style="font-size:12px;color:var(--txt3)">
                            <i class="fas fa-clock" style="font-size:11px;margin-right:4px"></i>
                            <?php echo e($room->updated_at ? $room->updated_at->format('d/m/Y H:i') : 'N/A'); ?>

                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($room->facilities->count() > 0): ?>
            <div class="card" style="margin-bottom:20px">
                <div class="card__head card__head--success">
                    <i class="fas fa-wifi"></i>
                    Équipements
                </div>
                <div class="card__body">
                    <div class="facilities">
                        <?php $__currentLoopData = $room->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="facility-badge">
                            <i class="fas fa-<?php echo e($facility->icon ?? 'check'); ?>"></i>
                            <?php echo e($facility->name); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card">
                <div class="card__head card__head--info">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques (30 jours)
                </div>
                <div class="card__body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($roomStats['total_transactions']); ?></div>
                            <div class="stat-label">Réservations</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" style="color:var(--acc)"><?php echo e(number_format($roomStats['occupancy_rate_30d'], 1)); ?>%</div>
                            <div class="stat-label">Taux d'occupation</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(number_format($roomStats['avg_stay_duration'], 1)); ?></div>
                            <div class="stat-label">Nuits moyennes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" style="font-size:16px;font-family:'JetBrains Mono',monospace"><?php echo e(number_format($roomStats['avg_daily_rate'], 0)); ?></div>
                            <div class="stat-label">Prix moyen/jour</div>
                        </div>
                    </div>
                    <div class="stat-item" style="margin-top:12px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:8px">
                        <div class="stat-value" style="color:var(--grn);font-family:'JetBrains Mono',monospace;font-size:20px">
                            <?php echo e(number_format($roomStats['total_revenue_30d'], 0, ',', ' ')); ?> FCFA
                        </div>
                        <div class="stat-label">Revenu total (30j)</div>
                    </div>
                    
                    <?php if($roomStats['next_available'] && $roomStats['next_available'] instanceof \Carbon\Carbon): ?>
                    <div style="margin-top:12px;padding:10px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:8px;text-align:center">
                        <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">PROCHAINE DISPONIBILITÉ</div>
                        <div style="font-weight:700;color:var(--yel)"><?php echo e($roomStats['next_available']->format('d/m/Y')); ?></div>
                    </div>
                    <?php elseif(isset($roomStats['formatted_next_available']) && $roomStats['formatted_next_available'] != 'Immédiate'): ?>
                    <div style="margin-top:12px;padding:10px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:8px;text-align:center">
                        <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">DISPONIBLE</div>
                        <div style="font-weight:700;color:var(--grn)">Immédiatement</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div>
            
            <?php if($currentTransaction): ?>
            <div class="card" style="margin-bottom:20px;border-color:var(--yel)">
                <div class="card__head card__head--warning">
                    <i class="fas fa-user-check"></i>
                    Client actuel
                    <span class="card__badge">
                        <span class="badge badge--light">
                            <?php echo e($currentTransaction->check_in->format('d/m/Y')); ?> - <?php echo e($currentTransaction->check_out->format('d/m/Y')); ?>

                        </span>
                    </span>
                </div>
                <div class="card__body">
                    <div class="guest-card">
                        <div class="guest-avatar">
                            <?php echo e(substr($currentTransaction->customer->name ?? 'C', 0, 1)); ?>

                        </div>
                        <div class="guest-info">
                            <div class="guest-name"><?php echo e($currentTransaction->customer->name ?? 'Client inconnu'); ?></div>
                            <div class="guest-meta">
                                <?php if($currentTransaction->customer->email ?? false): ?>
                                <span><i class="fas fa-envelope"></i> <?php echo e($currentTransaction->customer->email); ?></span>
                                <?php endif; ?>
                                <?php if($currentTransaction->customer->phone ?? false): ?>
                                <span><i class="fas fa-phone"></i> <?php echo e($currentTransaction->customer->phone); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="guest-badges">
                                <span class="badge badge--info"><?php echo e($currentTransaction->nights ?? 1); ?> nuit(s)</span>
                                <span class="badge badge--success"><?php echo e(number_format($currentTransaction->total_price ?? 0, 0, ',', ' ')); ?> FCFA</span>
                                <span class="badge badge--<?php echo e(($currentTransaction->status ?? '') == 'active' ? 'warning' : 'info'); ?>">
                                    <?php echo e($currentTransaction->status_label ?? 'Réservation'); ?>

                                </span>
                            </div>
                        </div>
                        <div class="guest-actions">
                            <a href="<?php echo e(route('transaction.show', ['transaction' => $currentTransaction->id])); ?>" class="btn btn--outline">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                            <?php if(($currentTransaction->status ?? '') == 'active'): ?>
                                <?php if($canCheckOut): ?>
                                <a href="<?php echo e(route('transaction.check-out', ['transaction' => $currentTransaction->id])); ?>" 
                                   class="btn btn--warning"
                                   onclick="return confirm('Êtes-vous sûr de vouloir faire le check-out ?');">
                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                </a>
                                <?php else: ?>
                                <button class="btn btn--warning" disabled title="Seul le personnel de réception peut faire le check-out">
                                    <i class="fas fa-sign-out-alt"></i> Check-out
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card" style="margin-bottom:20px;border-color:var(--grn)">
                <div class="card__head card__head--success">
                    <i class="fas fa-door-open"></i>
                    Chambre disponible
                    <span class="card__badge">
                        <span class="badge badge--light"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
                    </span>
                </div>
                <div class="card__body">
                    <div class="available-state">
                        <i class="fas fa-check-circle"></i>
                        <h5>Chambre libre</h5>
                        <p>Cette chambre est actuellement disponible pour une nouvelle réservation.</p>
                        <?php if($room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>?room_id=<?php echo e($room->id); ?>" class="btn btn--success">
                            <i class="fas fa-plus-circle"></i>
                            Créer une réservation
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card" style="margin-bottom:20px">
                <div class="card__head card__head--dark">
                    <i class="fas fa-calendar-alt"></i>
                    Disponibilité (30 prochains jours)
                    <span class="card__badge">
                        <small style="color:rgba(255,255,255,.7)"><?php echo e(now()->format('d/m')); ?> → <?php echo e(now()->addDays(30)->format('d/m')); ?></small>
                    </span>
                </div>
                <div class="card__body">
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-sq legend-sq--avail"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq--occ"></div>
                            <span>Occupée</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq--unavail"></div>
                            <span>Indisponible</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-sq legend-sq--today"></div>
                            <span>Aujourd'hui</span>
                        </div>
                    </div>
                    
                    <div style="overflow-x:auto">
                        <table class="cal-table">
                            <thead>
                                <tr>
                                    <?php for($i = 0; $i < 7; $i++): ?>
                                        <?php $day = now()->addDays($i); ?>
                                        <th>
                                            <div style="font-weight:700;font-size:12px"><?php echo e($day->isoFormat('dd')); ?></div>
                                            <div style="font-size:10px;color:var(--txt3)"><?php echo e($day->format('d')); ?></div>
                                        </th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($week = 0; $week < 5; $week++): ?>
                                    <tr>
                                        <?php for($day = 0; $day < 7; $day++): ?>
                                            <?php
                                                $dateIndex = $week * 7 + $day;
                                                if ($dateIndex >= 30) break;
                                                
                                                $date = now()->addDays($dateIndex);
                                                $dateKey = $date->format('Y-m-d');
                                                $availability = $calendar[$dateKey] ?? null;
                                                
                                                $isOccupied = false;
                                                $reservationInfo = '';
                                                
                                                if ($availability && isset($availability['occupied'])) {
                                                    $isOccupied = $availability['occupied'];
                                                    if (isset($availability['reservation_count']) && $availability['reservation_count'] > 0) {
                                                        $reservationInfo = $availability['reservation_count'] . ' réservation(s)';
                                                    }
                                                }
                                                
                                                $cssClass = 'cal-day--avail';
                                                $icon = 'fas fa-check';
                                                $tooltipText = 'Disponible - ' . number_format($room->price, 0, ',', ' ') . ' FCFA/nuit';
                                                
                                                if ($isOccupied) {
                                                    $cssClass = 'cal-day--occ';
                                                    $icon = 'fas fa-user';
                                                    $tooltipText = 'Occupée';
                                                    if ($reservationInfo) {
                                                        $tooltipText .= ' - ' . $reservationInfo;
                                                    }
                                                } elseif ($room->room_status_id != 1) {
                                                    $cssClass = 'cal-day--unavail';
                                                    $icon = 'fas fa-times';
                                                    $tooltipText = 'Indisponible - ' . ($room->roomStatus->name ?? 'Maintenance');
                                                }
                                                
                                                $isToday = $date->isToday();
                                                if ($isToday) {
                                                    $cssClass .= ' cal-day--today';
                                                    $tooltipText .= ' - Aujourd\'hui';
                                                }
                                            ?>
                                            <td>
                                                <div class="cal-day <?php echo e($cssClass); ?>"
                                                    data-date="<?php echo e($dateKey); ?>"
                                                    data-is-occupied="<?php echo e($isOccupied ? 'true' : 'false'); ?>"
                                                    data-room-id="<?php echo e($room->id); ?>"
                                                    data-room-number="<?php echo e($room->number); ?>"
                                                    onclick="handleCalendarDayClick(this)"
                                                    title="<?php echo e($tooltipText); ?>">
                                                    <div class="cal-day__num"><?php echo e($date->format('d')); ?></div>
                                                    <div class="cal-day__month"><?php echo e($date->isoFormat('MMM')); ?></div>
                                                    <div class="cal-day__icon">
                                                        <i class="<?php echo e($icon); ?>"></i>
                                                    </div>
                                                    <?php if($isOccupied && isset($availability['reservation_count']) && $availability['reservation_count'] > 1): ?>
                                                    <div class="conflict-badge">
                                                        <?php echo e($availability['reservation_count']); ?>

                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="cal-footer">
                        <div class="cal-footer__info">
                            <i class="fas fa-info-circle"></i>
                            Cliquez sur une date pour voir/réserver
                        </div>
                        <div style="display:flex;gap:8px">
                            <button class="btn btn--outline" style="font-size:12px;padding:6px 12px" onclick="scrollToTodayInCalendar()">
                                <i class="fas fa-calendar-day"></i> Aujourd'hui
                            </button>
                            <a href="<?php echo e(route('availability.calendar')); ?>?room_number=<?php echo e($room->number); ?>" 
                               class="btn btn--outline" style="font-size:12px;padding:6px 12px">
                                <i class="fas fa-expand-alt"></i> Calendrier complet
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($nextReservation): ?>
            <div class="card" style="margin-bottom:20px">
                <div class="card__head card__head--info">
                    <i class="fas fa-calendar-plus"></i>
                    Prochaine réservation
                    <span class="card__badge">
                        <span class="badge badge--light"><?php echo e($nextReservation->check_in->format('d/m/Y')); ?></span>
                    </span>
                </div>
                <div class="card__body">
                    <div class="next-res">
                        <div class="next-res__info">
                            <h6><?php echo e($nextReservation->customer->name ?? 'Client inconnu'); ?></h6>
                            <div class="next-res__meta">
                                <i class="fas fa-calendar"></i>
                                <?php echo e($nextReservation->check_in->format('d/m/Y')); ?> → <?php echo e($nextReservation->check_out->format('d/m/Y')); ?>

                                &nbsp;•&nbsp;
                                <i class="fas fa-moon"></i>
                                <?php echo e($nextReservation->nights); ?> nuit(s)
                                &nbsp;•&nbsp;
                                <i class="fas fa-users"></i>
                                <?php echo e($nextReservation->person_count ?? 1); ?> pers.
                            </div>
                        </div>
                        <span class="badge badge--warning">Confirmée</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card">
                <div class="card__head card__head--primary">
                    <i class="fas fa-bolt"></i>
                    Actions rapides
                </div>
                <div class="card__body">
                    <div class="actions-grid">
                        <a href="<?php echo e(route('availability.search', ['room_type_id' => $room->type_id, 'check_in' => now()->format('Y-m-d'), 'check_out' => now()->addDays(1)->format('Y-m-d')])); ?>" 
                           class="action-btn action-btn--primary">
                            <div class="action-btn__icon"><i class="fas fa-search"></i></div>
                            <div class="action-btn__title">Rechercher</div>
                            <div class="action-btn__desc">Voir disponibilités</div>
                        </a>
                        
                        <?php if($room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('transaction.reservation.createIdentity', ['room_id' => $room->id])); ?>" 
                           class="action-btn action-btn--success">
                            <div class="action-btn__icon"><i class="fas fa-book"></i></div>
                            <div class="action-btn__title">Réserver</div>
                            <div class="action-btn__desc">Nouvelle réservation</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled>
                            <div class="action-btn__icon" style="color:var(--txt3)"><i class="fas fa-ban"></i></div>
                            <div class="action-btn__title">Indisponible</div>
                            <div class="action-btn__desc"><?php echo e($room->roomStatus->name ?? 'Non disponible'); ?></div>
                        </button>
                        <?php endif; ?>
                        
                        <?php
                            $canMaintenance = in_array($user->role ?? '', ['Super', 'Admin', 'Reception', 'Housekeeping']);
                        ?>
                        <?php if($canMaintenance && $room->room_status_id == 1): ?>
                        <a href="<?php echo e(route('housekeeping.mark-maintenance', $room->id)); ?>" class="action-btn action-btn--warning">
                            <div class="action-btn__icon"><i class="fas fa-tools"></i></div>
                            <div class="action-btn__title">Maintenance</div>
                            <div class="action-btn__desc">Marquer en maintenance</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled title="<?php echo e(!$canMaintenance ? 'Non autorisé' : 'Chambre déjà en maintenance'); ?>">
                            <div class="action-btn__icon" style="color:var(--txt3)"><i class="fas fa-tools"></i></div>
                            <div class="action-btn__title">Maintenance</div>
                            <div class="action-btn__desc"><?php echo e(!$canMaintenance ? 'Non autorisé' : 'Indisponible'); ?></div>
                        </button>
                        <?php endif; ?>
                        
                        <?php
                            $canClean = in_array($user->role ?? '', ['Super', 'Admin', 'Housekeeping']);
                            $isDirty = $room->room_status_id == 3;
                        ?>
                        <?php if($canClean && $isDirty): ?>
                        <a href="<?php echo e(route('housekeeping.mark-cleaned', $room->id)); ?>" class="action-btn action-btn--info">
                            <div class="action-btn__icon"><i class="fas fa-broom"></i></div>
                            <div class="action-btn__title">Nettoyée</div>
                            <div class="action-btn__desc">Marquer comme nettoyée</div>
                        </a>
                        <?php else: ?>
                        <button class="action-btn" disabled title="<?php echo e(!$canClean ? 'Non autorisé' : 'Chambre déjà nettoyée'); ?>">
                            <div class="action-btn__icon" style="color:var(--txt3)"><i class="fas fa-broom"></i></div>
                            <div class="action-btn__title">Nettoyée</div>
                            <div class="action-btn__desc"><?php echo e(!$canClean ? 'Non autorisé' : 'Indisponible'); ?></div>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltips = [].slice.call(document.querySelectorAll('[title]'));
    tooltips.forEach(function(el) {
        new bootstrap.Tooltip(el);
    });
});

function handleCalendarDayClick(cell) {
    const date = cell.getAttribute('data-date');
    const isOccupied = cell.getAttribute('data-is-occupied') === 'true';
    const roomId = cell.getAttribute('data-room-id');
    const roomNumber = cell.getAttribute('data-room-number');
    
    if (isOccupied) {
        showOccupancyDetailsModal(roomId, date, roomNumber);
    } else {
        showReservationModal(roomId, roomNumber, date);
    }
}

function showOccupancyDetailsModal(roomId, date, roomNumber) {
    const content = `
        <div class="modal-header" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:white">
            <h5 class="modal-title"><i class="fas fa-calendar-times me-2"></i>Chambre Occupée</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
        </div>
        <div class="modal-body">
            <div style="padding:12px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;margin-bottom:16px">
                <i class="fas fa-exclamation-triangle" style="color:var(--red);margin-right:6px"></i>
                La chambre ${roomNumber} est occupée le ${new Date(date).toLocaleDateString('fr-FR')}.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">DATE</div>
                    <div style="font-weight:700">${new Date(date).toLocaleDateString('fr-FR')}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">STATUT</div>
                    <span class="badge badge--danger">Occupée</span>
                </div>
            </div>
            <div style="padding:12px;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.3);border-radius:8px">
                <i class="fas fa-info-circle" style="color:var(--acc);margin-right:6px"></i>
                Pour voir les détails, consultez la liste des transactions.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn--outline" data-bs-dismiss="modal">Fermer</button>
            <a href="/transaction" class="btn btn--primary"><i class="fas fa-list me-2"></i>Voir transactions</a>
        </div>
    `;
    showModal(content);
}

function showReservationModal(roomId, roomNumber, date) {
    const checkInDate = new Date(date);
    const checkOutDate = new Date(checkInDate);
    checkOutDate.setDate(checkOutDate.getDate() + 1);
    
    const checkInStr = checkInDate.toISOString().split('T')[0];
    const checkOutStr = checkOutDate.toISOString().split('T')[0];
    
    const content = `
        <div class="modal-header" style="background:linear-gradient(135deg,#10b981,#059669);color:white">
            <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>Réserver la chambre</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
        </div>
        <div class="modal-body">
            <div style="padding:12px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:8px;margin-bottom:16px">
                <i class="fas fa-check-circle" style="color:var(--grn);margin-right:6px"></i>
                La chambre ${roomNumber} est disponible pour le ${new Date(date).toLocaleDateString('fr-FR')}.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                <div>
                    <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">CHAMBRE</div>
                    <div style="font-weight:700">${roomNumber}</div>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--txt3);margin-bottom:4px">DATE DE DÉBUT</div>
                    <div style="font-weight:700">${new Date(date).toLocaleDateString('fr-FR')}</div>
                </div>
            </div>
            <div style="padding:12px;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.3);border-radius:8px">
                <i class="fas fa-info-circle" style="color:var(--acc);margin-right:6px"></i>
                Vous serez redirigé vers le formulaire de réservation.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn--outline" data-bs-dismiss="modal">Annuler</button>
            <a href="<?php echo e(route('transaction.reservation.createIdentity')); ?>?room_id=${roomId}&check_in=${checkInStr}&check_out=${checkOutStr}" 
               class="btn btn--success"><i class="fas fa-book me-2"></i>Continuer</a>
        </div>
    `;
    showModal(content);
}

function showModal(content) {
    const existing = document.getElementById('calendarModal');
    if (existing) existing.remove();
    
    const html = `<div class="modal fade" id="calendarModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">${content}</div></div></div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    
    const el = document.getElementById('calendarModal');
    const modal = new bootstrap.Modal(el);
    modal.show();
    
    el.addEventListener('hidden.bs.modal', function() { el.remove(); });
}

function scrollToTodayInCalendar() {
    const today = document.querySelector('.cal-day--today');
    if (today) {
        today.scrollIntoView({ behavior: 'smooth', block: 'center' });
        today.style.boxShadow = '0 0 20px rgba(245,158,11,.7)';
        setTimeout(() => { today.style.boxShadow = ''; }, 1500);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/availability/room-detail.blade.php ENDPATH**/ ?>