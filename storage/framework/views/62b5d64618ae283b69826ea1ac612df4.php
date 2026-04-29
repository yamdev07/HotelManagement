<?php $__env->startSection('title', 'Chambres & Suites — Cactus Palace 5 Étoiles'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    :root {
        --cactus-green: #1A472A;
        --cactus-light: #2E5C3F;
        --cactus-dark:  #0F2918;
        --gold-accent:  #C9A961;
        --gold-light:   #E8D5A3;
        --light-bg:     #F8FAF9;
        --white:        #FFFFFF;
        --text-dark:    #1A1A1A;
        --text-gray:    #6B7280;
        --border-color: #E5E7EB;
        --transition:   all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --shadow-sm:    0 2px 8px rgba(0,0,0,0.06);
        --shadow-md:    0 8px 24px rgba(0,0,0,0.10);
        --shadow-lg:    0 20px 50px rgba(0,0,0,0.14);
    }

    /* ── HERO ── */
    .rooms-hero {
        position: relative;
        min-height: 72vh;
        display: flex;
        align-items: center;
        background: url('https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        background-attachment: fixed;
        overflow: hidden;
    }
    .rooms-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15,41,24,0.88) 0%, rgba(26,71,42,0.72) 50%, rgba(201,169,97,0.12) 100%);
    }
    .rooms-hero .container { position: relative; z-index: 2; }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 22px;
        background: rgba(201,169,97,0.15);
        border: 1px solid rgba(201,169,97,0.4);
        border-radius: 50px;
        color: var(--gold-accent);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 24px;
    }
    .rooms-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 700;
        color: var(--white);
        line-height: 1.1;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }
    .rooms-hero h1 em { font-style: italic; color: var(--gold-accent); }
    .rooms-hero .hero-lead {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.78);
        max-width: 580px;
        line-height: 1.8;
        margin-bottom: 36px;
    }
    .hero-counters {
        display: flex;
        gap: 40px;
        padding-top: 36px;
        border-top: 1px solid rgba(255,255,255,0.15);
        margin-top: 36px;
        flex-wrap: wrap;
    }
    .hero-counter .number {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--gold-accent);
        line-height: 1;
    }
    .hero-counter .label {
        font-size: 11px;
        color: rgba(255,255,255,0.6);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 4px;
    }
    /* ── FILTER BAR ── */
    .filter-bar {
        background: var(--white);
        border-bottom: 1px solid var(--border-color);
        padding: 28px 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    .filter-bar .form-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--cactus-green);
        margin-bottom: 8px;
        display: block;
    }
    .filter-bar .form-select {
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.9rem;
        color: var(--text-dark);
        background-color: var(--white);
        transition: var(--transition);
        height: 44px;
    }
    .filter-bar .form-select:focus {
        border-color: var(--cactus-green);
        box-shadow: 0 0 0 3px rgba(26,71,42,0.08);
        outline: none;
    }
    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: var(--cactus-green);
        color: var(--white);
        border: 2px solid var(--cactus-green);
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        height: 44px;
    }
    .btn-filter:hover { background: transparent; color: var(--cactus-green); }
    .btn-filter-reset {
        background: transparent;
        color: var(--text-gray);
        border-color: var(--border-color);
    }
    .btn-filter-reset:hover { border-color: var(--cactus-green); color: var(--cactus-green); background: transparent; }

    /* ── ACTIVE FILTERS ── */
    .active-filters {
        background: rgba(26,71,42,0.04);
        border-bottom: 1px solid rgba(26,71,42,0.08);
        padding: 12px 0;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: rgba(26,71,42,0.08);
        color: var(--cactus-green);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ── SORT / VIEW BAR ── */
    .sort-view-bar {
        padding: 20px 0 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .sort-view-bar .results-count {
        font-size: 0.9rem;
        color: var(--text-gray);
    }
    .sort-view-bar .results-count strong { color: var(--cactus-green); }
    .sort-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sort-group span {
        font-size: 12px;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-sort {
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border: 1.5px solid var(--border-color);
        border-radius: 6px;
        background: var(--white);
        color: var(--text-gray);
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-sort:hover, .btn-sort.active {
        border-color: var(--cactus-green);
        color: var(--cactus-green);
        background: rgba(26,71,42,0.04);
    }
    .btn-view {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--border-color);
        border-radius: 6px;
        background: var(--white);
        color: var(--text-gray);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.85rem;
    }
    .btn-view:hover, .btn-view.active {
        border-color: var(--cactus-green);
        color: var(--cactus-green);
        background: rgba(26,71,42,0.04);
    }

    /* ── ROOM CARDS ── */
    .rooms-section { background: var(--light-bg); padding: 40px 0 80px; }

    .room-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .room-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    /* Image */
    .room-img-wrap {
        position: relative;
        height: 260px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .room-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
        display: block;
    }
    .room-card:hover .room-img-wrap img { transform: scale(1.06); }

    /* Overlay gradient on image */
    .room-img-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15,41,24,0.5) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Badges on image */
    .room-badge-status {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }
    .room-badge-status.available {
        background: rgba(34,197,94,0.15);
        border: 1px solid rgba(34,197,94,0.5);
        color: #16a34a;
        background: rgba(255,255,255,0.92);
    }
    .room-badge-status.unavailable {
        background: rgba(255,255,255,0.92);
        border: 1px solid rgba(239,68,68,0.3);
        color: #dc2626;
    }

    .room-badge-type {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 2;
        padding: 5px 14px;
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .room-fav-btn {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 2;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.92);
        color: #aaa;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        font-size: 0.9rem;
        outline: none;
        padding: 0;
    }
    .room-fav-btn:hover, .room-fav-btn.active {
        background: #FF5252;
        color: var(--white);
    }

    .room-img-count {
        position: absolute;
        bottom: 16px;
        right: 16px;
        z-index: 2;
        padding: 4px 10px;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(6px);
        color: var(--white);
        border-radius: 50px;
        font-size: 11px;
    }

    /* Card body */
    .room-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .room-number-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--text-gray);
        margin-bottom: 6px;
    }

    .room-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .room-desc {
        font-size: 0.875rem;
        color: var(--text-gray);
        line-height: 1.7;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 46px;
    }

    /* Room specs row */
    .room-specs {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .room-spec {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-gray);
    }
    .room-spec i {
        color: var(--cactus-green);
        font-size: 11px;
        width: 14px;
    }

    /* Facilities */
    .room-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 20px;
    }
    .facility-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        background: rgba(26,71,42,0.06);
        color: var(--cactus-green);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    .facility-more {
        background: rgba(201,169,97,0.12);
        color: #9A7830;
    }

    /* Price + CTA */
    .room-footer {
        margin-top: auto;
        padding-top: 18px;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .room-price .amount {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--cactus-green);
        line-height: 1;
    }
    .room-price .per-night {
        font-size: 11px;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }
    .room-next-avail {
        font-size: 11px;
        color: #d97706;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .room-actions { display: flex; gap: 8px; }
    .btn-room-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        background: var(--cactus-green);
        color: var(--white);
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid var(--cactus-green);
        white-space: nowrap;
    }
    .btn-room-detail:hover { background: transparent; color: var(--cactus-green); }
    .btn-room-book {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid var(--gold-accent);
        white-space: nowrap;
    }
    .btn-room-book:hover { background: transparent; color: #9A7830; }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: var(--white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
    }
    .empty-state .icon-wrap {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(26,71,42,0.06);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: var(--cactus-green);
        margin-bottom: 24px;
    }
    .empty-state h4 {
        font-family: 'Playfair Display', serif;
        color: var(--text-dark);
        margin-bottom: 10px;
    }
    .empty-state p { color: var(--text-gray); margin-bottom: 28px; }

    /* ── PAGINATION ── */
    .pagination .page-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
        border: 1.5px solid var(--border-color);
        color: var(--text-dark);
        margin: 0 3px;
        font-size: 0.875rem;
        transition: var(--transition);
    }
    .pagination .page-link:hover { border-color: var(--cactus-green); color: var(--cactus-green); background: rgba(26,71,42,0.04); }
    .pagination .page-item.active .page-link { background: var(--cactus-green); border-color: var(--cactus-green); color: var(--white); }
    .pagination .page-item.disabled .page-link { opacity: 0.4; }

    /* ── STATS SECTION ── */
    .stats-section {
        background: var(--cactus-dark);
        padding: 80px 0;
    }
    .stat-block { text-align: center; }
    .stat-block .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        color: var(--gold-accent);
        line-height: 1;
        margin-bottom: 8px;
    }
    .stat-block .stat-label {
        font-size: 12px;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    .stat-block .stat-desc {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.4);
        margin-top: 6px;
    }
    .stat-divider {
        width: 1px;
        background: rgba(255,255,255,0.1);
        margin: 0 auto;
    }

    /* ── CTA ── */
    .cta-rooms {
        background: linear-gradient(135deg, var(--cactus-dark) 0%, var(--cactus-green) 60%, var(--cactus-light) 100%);
        padding: 90px 0;
        position: relative;
        overflow: hidden;
    }
    .cta-rooms::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -8%;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: rgba(201,169,97,0.07);
    }
    .cta-rooms .container { position: relative; z-index: 1; }
    .cta-rooms h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: var(--white);
        font-weight: 700;
        margin-bottom: 16px;
    }
    .cta-rooms p {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.72);
        max-width: 520px;
        line-height: 1.8;
        margin-bottom: 36px;
    }
    .btn-cta-gold {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 36px;
        background: var(--gold-accent);
        color: var(--cactus-dark);
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        border: 2px solid var(--gold-accent);
        transition: var(--transition);
    }
    .btn-cta-gold:hover { background: transparent; color: var(--gold-accent); }
    .btn-cta-white {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 36px;
        background: rgba(255,255,255,0.1);
        color: var(--white);
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        border: 2px solid rgba(255,255,255,0.3);
        transition: var(--transition);
    }
    .btn-cta-white:hover { background: rgba(255,255,255,0.18); color: var(--white); }

    /* ── LIST VIEW ── */
    #rooms-grid.view-list .room-item { flex: 0 0 100% !important; max-width: 100% !important; }
    #rooms-grid.view-list .room-card { flex-direction: row; }
    #rooms-grid.view-list .room-card:hover { transform: none; box-shadow: var(--shadow-md); }
    #rooms-grid.view-list .room-img-wrap { flex: 0 0 300px; height: auto; min-height: 220px; }
    #rooms-grid.view-list .room-card-body { padding: 28px; }
    #rooms-grid.view-list .room-desc { -webkit-line-clamp: 3; min-height: unset; }

    /* ── ANIMATIONS ── */
    .room-item { animation: fadeUp 0.5s ease-out both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .room-item:nth-child(1) { animation-delay:.05s }
    .room-item:nth-child(2) { animation-delay:.10s }
    .room-item:nth-child(3) { animation-delay:.15s }
    .room-item:nth-child(4) { animation-delay:.20s }
    .room-item:nth-child(5) { animation-delay:.25s }
    .room-item:nth-child(6) { animation-delay:.30s }

    /* ── RESPONSIVE ── */
    @media (max-width: 991px) {
        .hero-counters { gap: 24px; }
        #rooms-grid.view-list .room-img-wrap { flex: 0 0 220px; }
    }
    @media (max-width: 767px) {
        .rooms-hero { min-height: 85vh; background-attachment: scroll; }
        .filter-bar { padding: 18px 0; position: static; }
        #rooms-grid.view-list .room-card { flex-direction: column; }
        #rooms-grid.view-list .room-img-wrap { flex: none; height: 220px; }
    }
    @media (max-width: 576px) {
        .room-footer { flex-direction: column; align-items: flex-start; }
        .room-actions { width: 100%; }
        .btn-room-detail, .btn-room-book { flex: 1; justify-content: center; }
    }

    *:focus, *:focus-visible { outline: none !important; box-shadow: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="rooms-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="hero-eyebrow">
                    <i class="fas fa-star" style="font-size:10px;"></i>
                    Hôtel 5 Étoiles — Cotonou, Bénin
                    <i class="fas fa-star" style="font-size:10px;"></i>
                </div>
                <h1>
                    Chambres &<br>
                    <em>Suites d'Exception</em>
                </h1>
                <p class="hero-lead">
                    Chaque chambre du Cactus Palace est pensée comme un sanctuaire de confort —
                    matériaux nobles, literie premium et atmosphère apaisante pour un repos parfait.
                </p>
                <div class="hero-counters">
                    <div class="hero-counter">
                        <div class="number"><?php echo e($totalRooms ?? 0); ?></div>
                        <div class="label">Chambres</div>
                    </div>
                    <div class="hero-counter">
                        <div class="number"><?php echo e($availableCount ?? 0); ?></div>
                        <div class="label">Disponibles</div>
                    </div>
                    <div class="hero-counter">
                        <div class="number"><?php echo e($distinctTypes ?? 0); ?></div>
                        <div class="label">Catégories</div>
                    </div>
                    <div class="hero-counter">
                        <div class="number">4.8★</div>
                        <div class="label">Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<div class="filter-bar">
    <div class="container">
        <form action="<?php echo e(route('frontend.rooms')); ?>" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label for="type" class="form-label"><i class="fas fa-layer-group me-2"></i>Catégorie</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Toutes les catégories</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(request('type') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->name); ?> (<?php echo e($type->rooms_count ?? 0); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="capacity" class="form-label"><i class="fas fa-users me-2"></i>Occupants</label>
                    <select class="form-select" id="capacity" name="capacity">
                        <option value="">Toute capacité</option>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php $count = $roomsByCapacity[$i] ?? 0; ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(request('capacity') == $i ? 'selected' : ''); ?>>
                                <?php echo e($i); ?> personne<?php echo e($i > 1 ? 's' : ''); ?> (<?php echo e($count); ?>)
                            </option>
                        <?php endfor; ?>
                        <option value="6" <?php echo e(request('capacity') == 6 ? 'selected' : ''); ?>>6+ personnes</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="price_range" class="form-label"><i class="fas fa-tag me-2"></i>Budget / nuit</label>
                    <select class="form-select" id="price_range" name="price_range">
                        <option value="">Tous les tarifs</option>
                        <option value="0-50000"      <?php echo e(request('price_range')=='0-50000'      ?'selected':''); ?>>Moins de 50 000 FCFA (<?php echo e($priceRanges['0-50000'] ?? 0); ?>)</option>
                        <option value="50000-100000"  <?php echo e(request('price_range')=='50000-100000' ?'selected':''); ?>>50k — 100k FCFA (<?php echo e($priceRanges['50000-100000'] ?? 0); ?>)</option>
                        <option value="100000-150000" <?php echo e(request('price_range')=='100000-150000'?'selected':''); ?>>100k — 150k FCFA (<?php echo e($priceRanges['100000-150000'] ?? 0); ?>)</option>
                        <option value="150000-200000" <?php echo e(request('price_range')=='150000-200000'?'selected':''); ?>>150k — 200k FCFA (<?php echo e($priceRanges['150000-200000'] ?? 0); ?>)</option>
                        <option value="200000+"       <?php echo e(request('price_range')=='200000+'      ?'selected':''); ?>>Plus de 200k FCFA (<?php echo e($priceRanges['200000+'] ?? 0); ?>)</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label" style="opacity:0;">Actions</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter flex-fill justify-content-center">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="<?php echo e(route('frontend.rooms')); ?>" class="btn-filter btn-filter-reset" style="padding:11px 16px;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<?php if(request()->hasAny(['type', 'capacity', 'price_range'])): ?>
<div class="active-filters">
    <div class="container d-flex align-items-center gap-3 flex-wrap">
        <span style="font-size:12px;color:var(--text-gray);text-transform:uppercase;letter-spacing:1px;">Filtres actifs :</span>
        <?php if(request('type')): ?>
            <span class="filter-chip"><i class="fas fa-layer-group" style="font-size:10px;"></i><?php echo e(\App\Models\Type::find(request('type'))->name ?? ''); ?></span>
        <?php endif; ?>
        <?php if(request('capacity')): ?>
            <span class="filter-chip"><i class="fas fa-users" style="font-size:10px;"></i><?php echo e(request('capacity')); ?> personne(s)</span>
        <?php endif; ?>
        <?php if(request('price_range')): ?>
            <span class="filter-chip"><i class="fas fa-tag" style="font-size:10px;"></i><?php echo e(request('price_range')); ?> FCFA</span>
        <?php endif; ?>
        <strong style="color:var(--cactus-green);font-size:13px;"><?php echo e($rooms->total()); ?> résultat(s)</strong>
        <a href="<?php echo e(route('frontend.rooms')); ?>" style="font-size:12px;color:var(--text-gray);text-decoration:none;margin-left:auto;">
            <i class="fas fa-times me-1"></i>Effacer tout
        </a>
    </div>
</div>
<?php endif; ?>


<section class="rooms-section">
    <div class="container">

        
        <div class="sort-view-bar">
            <p class="results-count mb-0">
                <strong><?php echo e($rooms->total()); ?></strong> chambre<?php echo e($rooms->total() > 1 ? 's' : ''); ?> trouvée<?php echo e($rooms->total() > 1 ? 's' : ''); ?>

            </p>
            <div class="d-flex align-items-center gap-3">
                <div class="sort-group">
                    <span>Trier</span>
                    <button class="btn-sort sort-btn" data-sort="price_asc">Prix ↑</button>
                    <button class="btn-sort sort-btn" data-sort="price_desc">Prix ↓</button>
                    <button class="btn-sort sort-btn" data-sort="capacity_desc">Capacité</button>
                    <button class="btn-sort sort-btn" data-sort="name_asc">A–Z</button>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn-view view-btn active" data-view="grid" title="Vue grille"><i class="fas fa-th-large"></i></button>
                    <button class="btn-view view-btn" data-view="list" title="Vue liste"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </div>

        
        <div id="rooms-grid" class="row g-4 mt-1">
            <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-4 col-md-6 room-item"
                 data-price="<?php echo e($room->price); ?>"
                 data-name="<?php echo e(strtolower($room->name)); ?>"
                 data-capacity="<?php echo e($room->capacity); ?>">
                <div class="room-card">

                    
                    <div class="room-img-wrap">
                        <?php
                            $imageUrl = asset('img/default/default-room.png');
                            $roomFolder = public_path('img/room/' . $room->number);
                            if(is_dir($roomFolder)) {
                                foreach(scandir($roomFolder) as $file) {
                                    if($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                                        $imageUrl = asset('img/room/' . $room->number . '/' . $file);
                                        break;
                                    }
                                }
                            }
                        ?>
                        <img src="<?php echo e($imageUrl); ?>"
                             alt="<?php echo e($room->name); ?>"
                             onerror="this.onerror=null;this.src='<?php echo e(asset('img/room/gamesetting.png')); ?>';">

                        
                        <span class="room-badge-status <?php echo e($room->is_available_today ? 'available' : 'unavailable'); ?>">
                            <?php if($room->is_available_today): ?>
                                <i class="fas fa-circle" style="font-size:7px;color:#16a34a;margin-right:4px;"></i>Disponible
                            <?php else: ?>
                                <i class="fas fa-circle" style="font-size:7px;color:#dc2626;margin-right:4px;"></i>Occupée
                            <?php endif; ?>
                        </span>

                        
                        <span class="room-badge-type"><?php echo e($room->type->name ?? 'Standard'); ?></span>

                        
                        <button class="room-fav-btn favorite-btn" data-room-id="<?php echo e($room->id); ?>" tabindex="-1">
                            <i class="far fa-heart"></i>
                        </button>

                        
                        <?php if($room->images && $room->images->count() > 1): ?>
                        <span class="room-img-count"><i class="fas fa-images me-1"></i><?php echo e($room->images->count()); ?></span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="room-card-body">
                        <div class="room-number-label">Chambre <?php echo e($room->number); ?></div>
                        <h3 class="room-name"><?php echo e($room->name); ?></h3>
                        <p class="room-desc"><?php echo e($room->type->description_fr ?? 'Chambre élégante dotée d\'équipements haut de gamme pour un séjour inoubliable.'); ?></p>

                        
                        <div class="room-specs">
                            <div class="room-spec">
                                <i class="fas fa-users"></i>
                                <span><?php echo e($room->capacity); ?> pers.</span>
                            </div>
                            <div class="room-spec">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <span><?php echo e($room->size ?? '25'); ?> m²</span>
                            </div>
                            <div class="room-spec">
                                <i class="fas fa-building"></i>
                                <span>Étage <?php echo e($room->floor ?? 'RDC'); ?></span>
                            </div>
                            <?php if($room->view): ?>
                            <div class="room-spec">
                                <i class="fas fa-eye"></i>
                                <span><?php echo e(Str::limit($room->view, 18)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($room->facilities->count()): ?>
                        <div class="room-facilities">
                            <?php $__currentLoopData = $room->facilities->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="facility-tag">
                                    <i class="fas fa-<?php echo e($facility->icon ?? 'check'); ?>" style="font-size:9px;"></i>
                                    <?php echo e($facility->name); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($room->facilities->count() > 4): ?>
                                <span class="facility-tag facility-more">+<?php echo e($room->facilities->count() - 4); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        
                        <div class="room-footer">
                            <div class="room-price">
                                <div class="amount"><?php echo e($room->type->formatted_price ?? 'N/A'); ?></div>
                                <div class="per-night">par nuit</div>
                                <?php if(!$room->is_available_today && $room->next_available_date): ?>
                                <div class="room-next-avail">
                                    <i class="fas fa-calendar-alt"></i>
                                    Dispo le <?php echo e(\Carbon\Carbon::parse($room->next_available_date)->format('d/m/Y')); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="room-actions">
                                <a href="<?php echo e(route('frontend.room.details', $room->id)); ?>" class="btn-room-detail">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <?php if($room->is_available_today): ?>
                                <a href="<?php echo e(route('frontend.reservation')); ?>?room_id=<?php echo e($room->id); ?>" class="btn-room-book">
                                    <i class="fas fa-calendar-check"></i> Réserver
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="empty-state">
                    <div class="icon-wrap"><i class="fas fa-bed"></i></div>
                    <h4>Aucune chambre trouvée</h4>
                    <p>Aucune chambre ne correspond à vos critères de recherche.<br>Essayez d'élargir vos filtres.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="<?php echo e(route('frontend.rooms')); ?>" class="btn-room-detail">
                            <i class="fas fa-redo"></i> Voir toutes les chambres
                        </a>
                        <a href="<?php echo e(route('frontend.contact')); ?>" class="btn-room-book">
                            <i class="fas fa-headset"></i> Contacter le service client
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($rooms->hasPages()): ?>
        <div class="mt-5 pt-3 d-flex justify-content-center">
            <?php echo e($rooms->onEachSide(1)->links('vendor.pagination.bootstrap-5')); ?>

        </div>
        <?php endif; ?>
    </div>
</section>


<section class="stats-section" data-aos="fade-up">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-6">
                <div class="stat-block">
                    <div class="stat-num"><?php echo e($totalRooms ?? 0); ?></div>
                    <div class="stat-label">Chambres & Suites</div>
                    <div class="stat-desc">Pour tous vos séjours</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-block">
                    <div class="stat-num"><?php echo e($availableCount ?? 0); ?></div>
                    <div class="stat-label">Disponibles ce soir</div>
                    <div class="stat-desc">Prêtes à vous accueillir</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-block">
                    <div class="stat-num"><?php echo e($distinctTypes ?? 0); ?></div>
                    <div class="stat-label">Catégories</div>
                    <div class="stat-desc">Standard à Suite Royale</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-block">
                    <div class="stat-num">4.8<span style="font-size:1.5rem;">★</span></div>
                    <div class="stat-label">Satisfaction client</div>
                    <div class="stat-desc">Note moyenne 2024</div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="cta-rooms" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span style="display:inline-block;padding:6px 18px;background:rgba(201,169,97,0.15);border:1px solid rgba(201,169,97,0.35);border-radius:50px;color:var(--gold-accent);font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:18px;">
                    Besoin d'aide ?
                </span>
                <h2>Trouvez la chambre<br>parfaite pour vous</h2>
                <p>
                    Notre service client est disponible 24h/24 pour vous conseiller
                    et vous aider à choisir le séjour qui correspond à vos envies et votre budget.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo e(route('frontend.reservation')); ?>" class="btn-cta-gold">
                        <i class="fas fa-calendar-check"></i> Réserver maintenant
                    </a>
                    <a href="<?php echo e(route('frontend.contact')); ?>" class="btn-cta-white">
                        <i class="fas fa-headset"></i> Service client
                    </a>
                </div>
            </div>
            <div class="col-lg-5 mt-5 mt-lg-0 text-lg-end" data-aos="fade-left">
                <div style="display:inline-block;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:32px 36px;">
                    <div style="font-family:'Playfair Display',serif;font-size:2.8rem;font-weight:700;color:var(--gold-accent);line-height:1;">5★</div>
                    <div style="color:rgba(255,255,255,0.9);font-weight:600;margin:8px 0 4px;">Cactus Palace</div>
                    <div style="color:rgba(255,255,255,0.5);font-size:0.85rem;">Haie Vive, Cotonou — Bénin</div>
                    <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.1);font-size:12px;color:rgba(255,255,255,0.5);">
                        <i class="fas fa-phone me-2" style="color:var(--gold-accent);"></i>
                        Réservations & informations
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 80 });

document.addEventListener('DOMContentLoaded', function () {

    // Remove focus outline
    document.querySelectorAll('button, a').forEach(el => {
        el.addEventListener('mousedown', e => e.preventDefault());
        el.addEventListener('click', function () { this.blur(); });
    });

    // Auto-submit filters
    document.querySelectorAll('#type, #capacity, #price_range').forEach(s => {
        s.addEventListener('change', () => document.getElementById('filterForm').submit());
    });

    // Favorites
    const favorites = JSON.parse(localStorage.getItem('room_favorites') || '[]');
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        if (favorites.includes(btn.dataset.roomId)) {
            btn.classList.add('active');
            btn.querySelector('i').classList.replace('far', 'fas');
        }
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const id = this.dataset.roomId;
            const icon = this.querySelector('i');
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                icon.classList.replace('far', 'fas');
                let favs = JSON.parse(localStorage.getItem('room_favorites') || '[]');
                if (!favs.includes(id)) favs.push(id);
                localStorage.setItem('room_favorites', JSON.stringify(favs));
            } else {
                icon.classList.replace('fas', 'far');
                let favs = JSON.parse(localStorage.getItem('room_favorites') || '[]');
                localStorage.setItem('room_favorites', JSON.stringify(favs.filter(x => x !== id)));
            }
            this.blur();
        });
    });

    // Sort
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const grid = document.getElementById('rooms-grid');
            const items = [...grid.querySelectorAll('.room-item')];
            items.sort((a, b) => {
                switch (this.dataset.sort) {
                    case 'price_asc':     return +a.dataset.price - +b.dataset.price;
                    case 'price_desc':    return +b.dataset.price - +a.dataset.price;
                    case 'name_asc':      return a.dataset.name.localeCompare(b.dataset.name);
                    case 'capacity_desc': return +b.dataset.capacity - +a.dataset.capacity;
                }
            });
            items.forEach(i => grid.appendChild(i));
        });
    });

    // Grid / List view
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const grid = document.getElementById('rooms-grid');
            if (this.dataset.view === 'list') {
                grid.classList.add('view-list');
                grid.querySelectorAll('.room-item').forEach(i => {
                    i.classList.remove('col-lg-4', 'col-md-6');
                    i.classList.add('col-12');
                });
            } else {
                grid.classList.remove('view-list');
                grid.querySelectorAll('.room-item').forEach(i => {
                    i.classList.remove('col-12');
                    i.classList.add('col-lg-4', 'col-md-6');
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/frontend/pages/rooms.blade.php ENDPATH**/ ?>