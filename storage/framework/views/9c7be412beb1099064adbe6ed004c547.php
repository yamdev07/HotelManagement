<?php $__env->startSection('title', 'Spécialités Africaines — Cactus Palace 5 Étoiles'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
:root {
    --earth-brown:  #5C3317;
    --earth-light:  #8B5A2B;
    --earth-dark:   #3B1F0A;
    --gold-accent:  #C9A961;
    --gold-light:   #E8C97A;
    --kente-red:    #C0392B;
    --kente-green:  #1A6B3A;
    --kente-yellow: #F39C12;
    --light-bg:     #FAF6F0;
    --white:        #FFFFFF;
    --text-dark:    #1A1A1A;
    --text-gray:    #6B7280;
    --border-color: #E8DDD0;
    --transition:   all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm:    0 2px 8px rgba(0,0,0,0.06);
    --shadow-md:    0 8px 24px rgba(0,0,0,0.10);
    --shadow-lg:    0 20px 50px rgba(0,0,0,0.14);
}

/* ── HERO ── */
.africa-hero {
    position: relative;
    min-height: 78vh;
    display: flex;
    align-items: center;
    background: url('https://images.unsplash.com/photo-1504945005722-33670dcaf685?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    background-attachment: fixed;
    overflow: hidden;
}
.africa-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(59,31,10,0.92) 0%, rgba(92,51,23,0.78) 50%, rgba(201,169,97,0.12) 100%);
}
.africa-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 40px,
        rgba(201,169,97,0.03) 40px,
        rgba(201,169,97,0.03) 80px
    );
}
.africa-hero .container { position: relative; z-index: 3; }

.hero-kente-bar {
    display: flex; gap: 0; height: 4px; margin-bottom: 28px;
    border-radius: 2px; overflow: hidden; max-width: 180px;
}
.hero-kente-bar span { flex: 1; }
.hero-kente-bar .k1 { background: var(--kente-red); }
.hero-kente-bar .k2 { background: var(--gold-accent); }
.hero-kente-bar .k3 { background: var(--kente-green); }
.hero-kente-bar .k4 { background: var(--gold-accent); }
.hero-kente-bar .k5 { background: var(--kente-red); }

.africa-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 8px 22px;
    background: rgba(201,169,97,0.15);
    border: 1px solid rgba(201,169,97,0.4);
    border-radius: 50px;
    color: var(--gold-accent);
    font-size: 12px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 20px;
}
.africa-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.6rem, 6vw, 5rem);
    font-weight: 700; color: var(--white); line-height: 1.1;
    margin-bottom: 20px; letter-spacing: -1px;
}
.africa-hero h1 em { font-style: italic; color: var(--gold-accent); }
.africa-hero .hero-lead {
    font-size: 1.05rem; color: rgba(255,255,255,0.78);
    max-width: 600px; line-height: 1.9; margin-bottom: 36px;
}

.hero-africa-stats {
    display: flex; gap: 40px; flex-wrap: wrap;
    padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.15);
}
.hero-africa-stat .num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700; color: var(--gold-accent); line-height: 1;
}
.hero-africa-stat .lbl {
    font-size: 11px; color: rgba(255,255,255,0.6);
    letter-spacing: 1px; text-transform: uppercase; margin-top: 4px;
}

/* ── INTRO BAND ── */
.africa-intro-band {
    background: linear-gradient(90deg, var(--earth-dark), var(--earth-brown));
    padding: 22px 0;
    border-top: 3px solid var(--gold-accent);
}
.africa-intro-band .band-inner {
    display: flex; align-items: center; justify-content: center;
    gap: 40px; flex-wrap: wrap;
}
.band-item {
    display: flex; align-items: center; gap: 10px;
    color: rgba(255,255,255,0.85); font-size: 0.9rem;
}
.band-item i { color: var(--gold-accent); font-size: 1rem; }
.band-divider { width: 1px; height: 24px; background: rgba(255,255,255,0.2); }

/* ── SECTION GÉNÉRALE ── */
.africa-section {
    padding: 90px 0;
}
.africa-section:nth-child(odd) { background: var(--light-bg); }
.africa-section:nth-child(even) { background: var(--white); }

.africa-section-header { text-align: center; margin-bottom: 56px; }

.section-kente {
    display: flex; gap: 0; height: 3px; margin: 0 auto 18px; border-radius: 2px;
    overflow: hidden; width: 60px;
}
.section-kente span { flex: 1; }
.section-kente .k1 { background: var(--kente-red); }
.section-kente .k2 { background: var(--gold-accent); }
.section-kente .k3 { background: var(--kente-green); }

.section-tag-africa {
    display: inline-block; padding: 5px 18px;
    background: rgba(92,51,23,0.08); color: var(--earth-brown);
    border-radius: 50px; font-size: 11px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; margin-bottom: 12px;
}
.section-title-africa {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    font-weight: 700; color: var(--text-dark); letter-spacing: -0.5px; margin-bottom: 14px;
}
.section-title-africa em { font-style: italic; color: var(--earth-brown); }
.section-subtitle {
    font-size: 1rem; color: var(--text-gray); max-width: 520px; margin: 0 auto; line-height: 1.8;
}

/* ── CARTES MENU AFRICAIN ── */
.africa-card {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-sm);
}
.africa-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(201,169,97,0.4);
}

.africa-card-img {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: var(--light-bg);
}
.africa-card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .6s ease;
}
.africa-card:hover .africa-card-img img { transform: scale(1.08); }
.africa-card-noimg {
    height: 220px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--earth-dark), var(--earth-brown));
    font-size: 3.5rem;
}

.africa-card-badge {
    position: absolute; top: 14px; left: 14px;
    background: linear-gradient(135deg, var(--earth-brown), var(--earth-light));
    color: var(--white); border-radius: 8px;
    padding: 4px 12px; font-size: 11px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
}

.africa-price-badge {
    position: absolute; bottom: 14px; right: 14px;
    background: rgba(15,41,24,0.88); backdrop-filter: blur(10px);
    color: var(--gold-accent); border-radius: 10px;
    padding: 6px 14px; font-size: 1rem; font-weight: 700;
    font-family: 'Playfair Display', serif;
}

.africa-card-body {
    padding: 22px;
    display: flex; flex-direction: column; flex: 1;
}
.africa-card-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 700;
    color: var(--text-dark); margin-bottom: 10px;
    line-height: 1.3;
}
.africa-card-desc {
    font-size: 0.875rem; color: var(--text-gray);
    line-height: 1.7; flex: 1; margin-bottom: 18px;
}
.africa-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 14px; border-top: 1px solid var(--border-color);
}

.origin-tag {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px;
    background: rgba(92,51,23,0.07); color: var(--earth-brown);
    border-radius: 20px; font-size: 11px; font-weight: 600;
}
.origin-tag i { font-size: 9px; }

.btn-africa-order {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    background: linear-gradient(135deg, var(--earth-brown), var(--earth-light));
    color: var(--white);
    border: none; border-radius: 9px;
    font-size: 0.8rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    text-decoration: none;
}
.btn-africa-order:hover {
    background: linear-gradient(135deg, var(--earth-dark), var(--earth-brown));
    color: var(--gold-accent);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(92,51,23,0.25);
}

/* ── TABS NAVIGATION ── */
.africa-tabs {
    display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;
    margin-bottom: 52px;
}
.africa-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 28px; border-radius: 50px;
    font-size: 0.9rem; font-weight: 600;
    cursor: pointer; border: 2px solid var(--border-color);
    background: var(--white); color: var(--text-gray);
    transition: var(--transition);
}
.africa-tab:hover {
    border-color: var(--earth-brown); color: var(--earth-brown);
}
.africa-tab.active {
    background: linear-gradient(135deg, var(--earth-brown), var(--earth-light));
    border-color: var(--earth-brown);
    color: var(--white);
    box-shadow: 0 4px 18px rgba(92,51,23,0.25);
}
.africa-tab .tab-count {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(255,255,255,0.2);
    font-size: 11px; font-weight: 700;
}
.africa-tab.active .tab-count { background: rgba(255,255,255,0.25); }

/* ── SECTION BLOC ── */
.menu-section-block { display: none; }
.menu-section-block.active { display: block; }

/* ── CTA SECTION ── */
.africa-cta {
    background: linear-gradient(135deg, var(--earth-dark) 0%, var(--earth-brown) 60%, #7A4020 100%);
    padding: 80px 0;
    position: relative; overflow: hidden;
}
.africa-cta::before {
    content: '';
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&q=40') center/cover no-repeat;
    opacity: 0.07;
}
.africa-cta .container { position: relative; z-index: 2; }
.africa-cta h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    color: var(--white); margin-bottom: 14px;
}
.africa-cta h2 em { font-style: italic; color: var(--gold-accent); }
.africa-cta p { color: rgba(255,255,255,0.72); max-width: 540px; margin: 0 auto 32px; line-height: 1.8; }

.btn-cta-gold {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 15px 36px;
    background: linear-gradient(135deg, var(--gold-accent), var(--gold-light));
    color: var(--earth-dark); border: none; border-radius: 10px;
    font-size: 0.95rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    text-decoration: none;
}
.btn-cta-gold:hover {
    background: linear-gradient(135deg, var(--gold-light), var(--gold-accent));
    box-shadow: 0 10px 28px rgba(201,169,97,0.35);
    transform: translateY(-2px);
    color: var(--earth-dark);
}
.btn-cta-outline {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 15px 36px;
    background: transparent;
    color: var(--white); border: 2px solid rgba(255,255,255,0.4); border-radius: 10px;
    font-size: 0.95rem; font-weight: 700;
    cursor: pointer; transition: var(--transition);
    text-decoration: none;
}
.btn-cta-outline:hover {
    border-color: var(--gold-accent); color: var(--gold-accent);
    box-shadow: 0 8px 24px rgba(201,169,97,0.15);
}

/* ── EMPTY STATE ── */
.africa-empty {
    text-align: center; padding: 60px 20px;
}
.africa-empty-icon {
    width: 90px; height: 90px; border-radius: 50%;
    background: rgba(92,51,23,0.08);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 2.5rem;
}

/* Responsive */
@media(max-width: 991px) {
    .africa-hero { min-height: 65vh; background-attachment: scroll; }
    .hero-africa-stats { gap: 24px; }
    .africa-section { padding: 60px 0; }
}
@media(max-width: 767px) {
    .africa-tabs { gap: 6px; }
    .africa-tab { padding: 9px 18px; font-size: 0.82rem; }
    .band-divider { display: none; }
    .africa-intro-band .band-inner { gap: 20px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="africa-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-9" data-aos="fade-right">
                <div class="hero-kente-bar">
                    <span class="k1"></span><span class="k2"></span><span class="k3"></span>
                    <span class="k4"></span><span class="k5"></span>
                </div>
                <div class="africa-hero-eyebrow">
                    <i class="fas fa-globe-africa" style="font-size:11px;"></i>
                    Cuisine Authentique d'Afrique
                    <i class="fas fa-globe-africa" style="font-size:11px;"></i>
                </div>
                <h1>
                    Spécialités<br>
                    <em>Africaines</em>
                </h1>
                <p class="hero-lead">
                    Un voyage culinaire au cœur du continent africain. Nos chefs célèbrent les saveurs
                    authentiques du Sénégal, du Cameroun, de la Côte d'Ivoire et du Nigeria à travers
                    des recettes transmises de génération en génération.
                </p>
                <div class="hero-africa-stats">
                    <div class="hero-africa-stat">
                        <div class="num"><?php echo e($menus->count()); ?></div>
                        <div class="lbl">Spécialités</div>
                    </div>
                    <div class="hero-africa-stat">
                        <div class="num">4+</div>
                        <div class="lbl">Pays d'origine</div>
                    </div>
                    <div class="hero-africa-stat">
                        <div class="num">100%</div>
                        <div class="lbl">Authentique</div>
                    </div>
                    <div class="hero-africa-stat">
                        <div class="num">5★</div>
                        <div class="lbl">Cuisine primée</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="africa-intro-band">
    <div class="container">
        <div class="band-inner">
            <div class="band-item"><i class="fas fa-fire-alt"></i><span>Épices authentiques</span></div>
            <div class="band-divider"></div>
            <div class="band-item"><i class="fas fa-seedling"></i><span>Produits locaux frais</span></div>
            <div class="band-divider"></div>
            <div class="band-item"><i class="fas fa-heart"></i><span>Recettes traditionnelles</span></div>
            <div class="band-divider"></div>
            <div class="band-item"><i class="fas fa-globe-africa"></i><span>4 pays d'Afrique</span></div>
            <div class="band-divider"></div>
            <div class="band-item"><i class="fas fa-star"></i><span>Chef étoilé africain</span></div>
        </div>
    </div>
</div>


<section class="africa-section" style="background: var(--light-bg); padding: 80px 0;">
    <div class="container">

        <div class="africa-section-header" data-aos="fade-up">
            <div class="section-kente"><span class="k1"></span><span class="k2"></span><span class="k3"></span></div>
            <span class="section-tag-africa">Notre Carte Africaine</span>
            <h2 class="section-title-africa">Les saveurs <em>du continent</em></h2>
            <p class="section-subtitle">
                Chaque plat raconte une histoire, chaque épice évoque une terre.
                Découvrez notre sélection de <?php echo e($menus->count()); ?> spécialités africaines.
            </p>
        </div>

        
        <div class="africa-tabs" data-aos="fade-up" data-aos-delay="60">
            <button class="africa-tab active" data-tab="tous">
                <i class="fas fa-th"></i> Tout voir
                <span class="tab-count"><?php echo e($menus->count()); ?></span>
            </button>
            <button class="africa-tab" data-tab="entree">
                <i class="fas fa-leaf"></i> Entrées
                <span class="tab-count"><?php echo e($entrees->count()); ?></span>
            </button>
            <button class="africa-tab" data-tab="plat">
                <i class="fas fa-utensils"></i> Plats
                <span class="tab-count"><?php echo e($plats->count()); ?></span>
            </button>
            <button class="africa-tab" data-tab="dessert">
                <i class="fas fa-birthday-cake"></i> Desserts
                <span class="tab-count"><?php echo e($desserts->count()); ?></span>
            </button>
            <button class="africa-tab" data-tab="boisson">
                <i class="fas fa-glass-martini-alt"></i> Boissons
                <span class="tab-count"><?php echo e($boissons->count()); ?></span>
            </button>
        </div>

        
        <div class="menu-section-block active" id="block-tous" data-aos="fade-up">
            <?php if($menus->isEmpty()): ?>
                <div class="africa-empty">
                    <div class="africa-empty-icon">🌍</div>
                    <h4 style="font-family:'Playfair Display',serif;">Carte en préparation</h4>
                    <p style="color:var(--text-gray);">Notre chef prépare de nouvelles spécialités.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6">
                        <?php echo $__env->make('frontend.partials.african-card', ['menu' => $menu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="menu-section-block" id="block-entree">
            <?php if($entrees->isEmpty()): ?>
                <div class="africa-empty"><div class="africa-empty-icon">🥗</div><p>Aucune entrée disponible.</p></div>
            <?php else: ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $entrees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo e(($loop->index % 3) * 60); ?>">
                        <?php echo $__env->make('frontend.partials.african-card', ['menu' => $menu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="menu-section-block" id="block-plat">
            <?php if($plats->isEmpty()): ?>
                <div class="africa-empty"><div class="africa-empty-icon">🍽️</div><p>Aucun plat disponible.</p></div>
            <?php else: ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $plats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo e(($loop->index % 3) * 60); ?>">
                        <?php echo $__env->make('frontend.partials.african-card', ['menu' => $menu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="menu-section-block" id="block-dessert">
            <?php if($desserts->isEmpty()): ?>
                <div class="africa-empty"><div class="africa-empty-icon">🍮</div><p>Aucun dessert disponible.</p></div>
            <?php else: ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $desserts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo e(($loop->index % 3) * 60); ?>">
                        <?php echo $__env->make('frontend.partials.african-card', ['menu' => $menu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="menu-section-block" id="block-boisson">
            <?php if($boissons->isEmpty()): ?>
                <div class="africa-empty"><div class="africa-empty-icon">🥤</div><p>Aucune boisson disponible.</p></div>
            <?php else: ?>
                <div class="row g-4">
                    <?php $__currentLoopData = $boissons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo e(($loop->index % 3) * 60); ?>">
                        <?php echo $__env->make('frontend.partials.african-card', ['menu' => $menu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>


<section class="africa-cta">
    <div class="container text-center">
        <div data-aos="fade-up">
            <div class="section-kente" style="margin-bottom:20px;"><span class="k1"></span><span class="k2"></span><span class="k3"></span></div>
            <h2>Envie de vivre <em>l'expérience</em> ?</h2>
            <p>Réservez une table ou passez une commande directement depuis notre restaurant en ligne.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo e(route('frontend.restaurant')); ?>#reservationSection" class="btn-cta-gold">
                    <i class="fas fa-calendar-check"></i> Réserver une table
                </a>
                <a href="<?php echo e(route('frontend.restaurant')); ?>#menuSection" class="btn-cta-outline">
                    <i class="fas fa-book-open"></i> Voir la carte complète
                </a>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 700, once: true, offset: 60 });

document.addEventListener('click', function(e) {
    const tab = e.target.closest('.africa-tab');
    if (!tab) return;

    document.querySelectorAll('.africa-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const target = tab.dataset.tab;
    document.querySelectorAll('.menu-section-block').forEach(b => b.classList.remove('active'));
    document.getElementById('block-' + target).classList.add('active');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/frontend/pages/african-specialties.blade.php ENDPATH**/ ?>