

<?php $__env->startSection('title', 'Hôtel Luxury Palace - L\'excellence au service du luxe'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section modernisé -->
    <section class="hero-modern">
        <div class="hero-content">
            <div class="container">
                <div class="row min-vh-100 align-items-center justify-content-center">
                    <div class="col-lg-10 text-center">
                        <div class="hero-badge mb-4" data-aos="fade-down">
                            <span class="badge-text">Luxury Collection</span>
                        </div>
                        <h1 class="hero-title mb-4" data-aos="fade-up" data-aos-delay="100">
                            Luxury Palace
                        </h1>
                        <p class="hero-subtitle mb-5" data-aos="fade-up" data-aos-delay="200">
                            L'expérience ultime du luxe et de l'élégance en plein cœur de la ville
                        </p>
                        <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                            <a href="<?php echo e(route('frontend.rooms')); ?>" class="btn-modern btn-primary me-3">
                                Découvrir nos suites
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <a href="<?php echo e(route('frontend.contact')); ?>" class="btn-modern btn-outline">
                                Réserver maintenant
                                <i class="fas fa-calendar-check ms-2"></i>
                            </a>
                        </div>
                        
                        <!-- Formulaire intégré dans l'héros -->
                        <div class="booking-in-hero mt-6" data-aos="fade-up" data-aos-delay="400">
                            <div class="booking-form-inline">
                                <form class="booking-form">
                                    <div class="form-group">
                                        <label>Arrivée</label>
                                        <input type="date" class="form-input" placeholder="Date d'arrivée">
                                    </div>
                                    <div class="form-group">
                                        <label>Départ</label>
                                        <input type="date" class="form-input" placeholder="Date de départ">
                                    </div>
                                    <div class="form-group">
                                        <label>Invités</label>
                                        <select class="form-input">
                                            <option>1 Adulte</option>
                                            <option>2 Adultes</option>
                                            <option>3 Adultes</option>
                                            <option>4+ Adultes</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-search">
                                        <i class="fas fa-search"></i>
                                        Rechercher
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="mouse"></div>
            <span>Défiler</span>
        </div>
    </section>

    <!-- Section Introduction -->
    <section class="intro-section py-7">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="intro-image-wrapper">
                        <div class="main-image">
                            <img src="https://images.unsplash.com/photo-1564501049418-3c27787d01e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="Lobby Luxury Palace" 
                                 class="img-fluid">
                        </div>
                        <div class="floating-badge">
                            <div class="badge-content">
                                <h3>30+</h3>
                                <p>Années d'excellence</p>
                            </div>
                        </div>
                        <div class="decoration-element"></div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="section-header mb-4">
                        <span class="section-tag">Notre Histoire</span>
                        <h2 class="section-title">L'Art de l'Hospitalité Réinventé</h2>
                    </div>
                    <p class="lead-text mb-4">
                        Depuis 1995, le Luxury Palace incarne l'excellence hôtelière dans sa forme la plus pure. 
                        Chaque détail est pensé pour créer des expériences inoubliables et élever le luxe à un art de vivre.
                    </p>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Excellence Certifiée</h4>
                                <p>5 étoiles & 25+ récompenses internationales</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Engagement Durable</h4>
                                <p>Luxe responsable & éco-conception</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Chambres -->
    <section class="rooms-section py-7">
        <div class="container">
            <div class="text-center mb-6" data-aos="fade-up">
                <span class="section-tag">Nos Suites</span>
                <h2 class="section-title">Chambres & Suites d'Exception</h2>
                <p class="section-subtitle mx-auto">
                    Des espaces où le raffinement rencontre le confort absolu
                </p>
            </div>

            <div class="row g-4">
                <?php $__currentLoopData = $featuredRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                    <div class="room-card-modern">
                        <div class="room-image-wrapper">
                            <img src="<?php echo e($room->first_image_url); ?>" 
                                 alt="<?php echo e($room->name); ?>"
                                 class="room-image">
                            <div class="room-status">
                                <?php if($room->room_status_id == 1): ?>
                                <span class="status-badge available">
                                    <i class="fas fa-check"></i> Disponible
                                </span>
                                <?php else: ?>
                                <span class="status-badge on-request">
                                    <i class="fas fa-clock"></i> Sur demande
                                </span>
                                <?php endif; ?>
                            </div>
                            <button class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        
                        <div class="room-details">
                            <div class="room-header">
                                <div>
                                    <h3 class="room-name"><?php echo e($room->name); ?></h3>
                                    <p class="room-type"><?php echo e($room->type->name ?? 'Suite Premium'); ?></p>
                                </div>
                                <span class="capacity-badge">
                                    <i class="fas fa-user"></i> <?php echo e($room->capacity); ?>

                                </span>
                            </div>
                            
                            <?php if($room->short_description): ?>
                            <p class="room-description"><?php echo e(Str::limit($room->short_description, 100)); ?></p>
                            <?php endif; ?>
                            
                            <div class="room-specs">
                                <div class="spec-item">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    <span><?php echo e($room->size ?? '--'); ?> m²</span>
                                </div>
                                <?php if($room->view): ?>
                                <div class="spec-item">
                                    <i class="fas fa-eye"></i>
                                    <span><?php echo e($room->view); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="room-footer">
                                <div class="price-info">
                                    <span class="price"><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA</span>
                                    <span class="price-label">par nuit</span>
                                </div>
                                <a href="<?php echo e(route('frontend.room.details', $room->id)); ?>" class="btn-view">
                                    Voir détails
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo e(route('frontend.rooms')); ?>" class="btn-modern btn-outline-lg">
                    Voir toutes les suites
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Section Services -->
    <section class="services-section py-7">
        <div class="container">
            <div class="text-center mb-6" data-aos="fade-up">
                <span class="section-tag">Expériences</span>
                <h2 class="section-title">Services d'Exception</h2>
                <p class="section-subtitle mx-auto">
                    Des prestations sur-mesure pour sublimer votre séjour
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="0">
                    <div class="service-card-modern">
                        <div class="service-icon-wrapper">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Gastronomie Étoilée</h3>
                        <p>Chef étoilé Michelin et cuisine fusion innovante</p>
                        <a href="<?php echo e(route('frontend.restaurant')); ?>" class="service-link">
                            Découvrir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="service-card-modern">
                        <div class="service-icon-wrapper">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3>Spa & Wellness</h3>
                        <p>Centre de bien-être avec soins personnalisés</p>
                        <a href="#" class="service-link">
                            Découvrir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="service-card-modern">
                        <div class="service-icon-wrapper">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h3>Conciergerie 24/7</h3>
                        <p>Service dédié pour répondre à tous vos besoins</p>
                        <a href="#" class="service-link">
                            Découvrir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="service-card-modern">
                        <div class="service-icon-wrapper">
                            <i class="fas fa-car-side"></i>
                        </div>
                        <h3>Transfert VIP</h3>
                        <p>Véhicules de luxe avec chauffeur privé</p>
                        <a href="#" class="service-link">
                            Découvrir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Témoignages -->
    <section class="testimonials-section py-7">
        <div class="container">
            <div class="text-center mb-6" data-aos="fade-up">
                <span class="section-tag light">Témoignages</span>
                <h2 class="section-title text-white">Ce Que Nos Clients Disent</h2>
                <p class="section-subtitle text-white-50 mx-auto">
                    L'excellence reconnue par nos clients les plus exigeants
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="testimonial-card-modern">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            Une expérience hôtelière absolument exceptionnelle. Le service est impeccable, 
                            l'attention aux détails est remarquable et chaque moment est un véritable enchantement.
                        </p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" 
                                 alt="Sophie Laurent"
                                 class="author-avatar">
                            <div class="author-info">
                                <h5>Sophie Laurent</h5>
                                <p>CEO, Luxe Group Paris</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card-modern">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            Le restaurant gastronomique est une véritable révélation culinaire. 
                            Chaque plat est une œuvre d'art qui ravit autant les yeux que les papilles.
                        </p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                 alt="Thomas Dubois"
                                 class="author-avatar">
                            <div class="author-info">
                                <h5>Thomas Dubois</h5>
                                <p>Critique Gastronomique</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card-modern">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">
                            Le spa est un havre de paix absolu. L'ambiance zen et les soins sur mesure 
                            créent une expérience de relaxation incomparable.
                        </p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                                 alt="Marie Chen"
                                 class="author-avatar">
                            <div class="author-info">
                                <h5>Marie Chen</h5>
                                <p>Lifestyle Influencer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row mt-6 pt-5" data-aos="fade-up">
                <div class="row g-4">
                    <div class="col-lg-3 col-6">
                        <div class="stat-item">
                            <h3 class="stat-number">4.9</h3>
                            <p class="stat-label">Note moyenne</p>
                            <div class="stat-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="stat-item">
                            <h3 class="stat-number">98%</h3>
                            <p class="stat-label">Satisfaction client</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="stat-item">
                            <h3 class="stat-number">5★</h3>
                            <p class="stat-label">Classement officiel</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="stat-item">
                            <h3 class="stat-number">25+</h3>
                            <p class="stat-label">Récompenses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="cta-section py-7">
        <div class="container">
            <div class="cta-wrapper">
                <div class="row align-items-center">
                    <div class="col-lg-7" data-aos="fade-right">
                        <h2 class="cta-title">Vivez l'Expérience Luxury Palace</h2>
                        <p class="cta-text">
                            Réservez dès maintenant et bénéficiez d'une offre exclusive de bienvenue
                        </p>
                        <div class="cta-actions">
                            <a href="<?php echo e(route('frontend.contact')); ?>" class="btn-modern btn-primary-lg">
                                <i class="fas fa-calendar-check me-2"></i>
                                Réserver maintenant
                            </a>
                            <a href="tel:+33123456789" class="btn-modern btn-white-lg">
                                <i class="fas fa-phone me-2"></i>
                                (+33) 1 23 45 67 89
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 text-center" data-aos="fade-left">
                        <div class="offer-badge">
                            <div class="badge-circle">
                                <span class="discount">-25%</span>
                                <span class="offer-label">Offre exclusive</span>
                                <span class="offer-desc">Réservation en ligne</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<style>
/* ============================================
   VARIABLES & BASE
   ============================================ */
:root {
    --cactus-green: #1A472A;
    --cactus-light: #2E5C3F;
    --cactus-dark: #0F2918;
    --gold-accent: #C9A961;
    --light-bg: #F8FAF9;
    --white: #FFFFFF;
    --text-dark: #1A1A1A;
    --text-gray: #6B7280;
    --border-color: #E5E7EB;
    
    --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm: 0 2px 8px rgba(26, 71, 42, 0.08);
    --shadow-md: 0 8px 24px rgba(26, 71, 42, 0.12);
    --shadow-lg: 0 16px 48px rgba(26, 71, 42, 0.16);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: var(--text-dark);
    line-height: 1.6;
    overflow-x: hidden;
    padding-top: 0;
}

.py-7 {
    padding: 100px 0;
}

/* ============================================
   HERO SECTION
   ============================================ */
.hero-modern {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(rgba(15, 41, 24, 0.85), rgba(26, 71, 42, 0.9)), 
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    overflow: hidden;
    display: flex;
    align-items: center;
}

.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-badge {
    display: inline-block;
    margin-bottom: 30px;
}

.badge-text {
    display: inline-block;
    padding: 8px 24px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    color: var(--gold-accent);
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: clamp(3rem, 8vw, 7rem);
    font-weight: 300;
    color: var(--white);
    letter-spacing: -2px;
    margin-bottom: 20px;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: rgba(255, 255, 255, 0.85);
    max-width: 700px;
    margin: 0 auto 40px;
    font-weight: 300;
}

.hero-cta {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 60px;
}

/* Formulaire intégré dans l'héros */
.booking-in-hero {
    max-width: 900px;
    margin: 0 auto;
}

.booking-form-inline {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 30px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    color: rgba(255, 255, 255, 0.6);
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    z-index: 3;
}

.mouse {
    width: 24px;
    height: 40px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    position: relative;
}

.mouse::before {
    content: '';
    width: 4px;
    height: 8px;
    background: rgba(255, 255, 255, 0.6);
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
    animation: scroll 1.5s infinite;
}

@keyframes scroll {
    0% { top: 8px; opacity: 1; }
    50% { top: 20px; opacity: 0.5; }
    100% { top: 8px; opacity: 1; }
}

/* ============================================
   BUTTONS
   ============================================ */
.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 15px 35px;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition-smooth);
    cursor: pointer;
    border: none;
    letter-spacing: 0.5px;
}

.btn-primary {
    background: var(--white);
    color: var(--cactus-green);
}

.btn-primary:hover {
    background: var(--gold-accent);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(201, 169, 97, 0.3);
}

.btn-outline {
    background: transparent;
    color: var(--white);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--white);
    color: var(--white);
}

.btn-outline-lg {
    padding: 18px 45px;
    font-size: 16px;
    background: transparent;
    color: var(--cactus-green);
    border: 2px solid var(--cactus-green);
}

.btn-outline-lg:hover {
    background: var(--cactus-green);
    color: var(--white);
}

.btn-primary-lg {
    padding: 18px 45px;
    font-size: 16px;
    background: var(--cactus-green);
    color: var(--white);
}

.btn-primary-lg:hover {
    background: var(--cactus-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-white-lg {
    padding: 18px 45px;
    font-size: 16px;
    background: var(--white);
    color: var(--cactus-green);
    border: 2px solid var(--white);
}

.btn-white-lg:hover {
    background: transparent;
    color: var(--white);
}

/* ============================================
   FORMULAIRES DE RÉSERVATION
   ============================================ */
.booking-form {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-gray);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-input {
    padding: 14px 18px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transition-smooth);
    background: var(--white);
    width: 100%;
}

.form-input:focus {
    outline: none;
    border-color: var(--cactus-green);
    box-shadow: 0 0 0 4px rgba(26, 71, 42, 0.1);
}

.btn-search {
    padding: 14px 30px;
    background: var(--cactus-green);
    color: var(--white);
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    height: 52px;
}

.btn-search:hover {
    background: var(--cactus-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* ============================================
   SECTIONS COMMUNES
   ============================================ */
.section-tag {
    display: inline-block;
    padding: 6px 20px;
    background: rgba(26, 71, 42, 0.1);
    color: var(--cactus-green);
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 15px;
}

.section-tag.light {
    background: rgba(255, 255, 255, 0.1);
    color: var(--gold-accent);
}

.section-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 15px;
    letter-spacing: -1px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-gray);
    max-width: 650px;
    line-height: 1.7;
}

/* ============================================
   INTRO SECTION
   ============================================ */
.intro-section {
    background: var(--light-bg);
}

.intro-image-wrapper {
    position: relative;
}

.main-image {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.main-image img {
    width: 100%;
    height: auto;
    display: block;
}

.floating-badge {
    position: absolute;
    bottom: 30px;
    right: -30px;
    background: var(--white);
    padding: 30px;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    text-align: center;
}

.floating-badge h3 {
    font-size: 3rem;
    font-weight: 700;
    color: var(--cactus-green);
    margin-bottom: 5px;
}

.floating-badge p {
    font-size: 14px;
    color: var(--text-gray);
    margin: 0;
}

.decoration-element {
    position: absolute;
    top: -20px;
    left: -20px;
    width: 150px;
    height: 150px;
    background: var(--gold-accent);
    opacity: 0.15;
    border-radius: 20px;
    z-index: -1;
}

.lead-text {
    font-size: 1.1rem;
    color: var(--text-gray);
    line-height: 1.8;
}

.features-grid {
    display: grid;
    gap: 25px;
    margin-top: 30px;
}

.feature-item {
    display: flex;
    gap: 20px;
    align-items: start;
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 24px;
    flex-shrink: 0;
}

.feature-content h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 5px;
}

.feature-content p {
    font-size: 0.95rem;
    color: var(--text-gray);
    margin: 0;
}

/* ============================================
   ROOMS SECTION
   ============================================ */
.rooms-section {
    background: var(--white);
}

.room-card-modern {
    background: var(--white);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-smooth);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.room-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.room-image-wrapper {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.room-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.room-card-modern:hover .room-image {
    transform: scale(1.08);
}

.room-status {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 2;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.status-badge.available {
    background: rgba(34, 197, 94, 0.9);
    color: var(--white);
}

.status-badge.on-request {
    background: rgba(251, 191, 36, 0.9);
    color: var(--white);
}

.wishlist-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-smooth);
    z-index: 2;
}

.wishlist-btn:hover {
    background: var(--cactus-green);
    color: var(--white);
    transform: scale(1.1);
}

.room-details {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.room-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.room-name {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 5px;
}

.room-type {
    font-size: 0.9rem;
    color: var(--text-gray);
    margin: 0;
}

.capacity-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(26, 71, 42, 0.1);
    color: var(--cactus-green);
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
}

.room-description {
    font-size: 0.95rem;
    color: var(--text-gray);
    margin-bottom: 20px;
    line-height: 1.6;
}

.room-specs {
    display: flex;
    gap: 20px;
    padding: 15px 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: var(--text-gray);
}

.spec-item i {
    color: var(--cactus-green);
}

.room-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.price-info {
    display: flex;
    flex-direction: column;
}

.price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--cactus-green);
}

.price-label {
    font-size: 0.85rem;
    color: var(--text-gray);
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--cactus-green);
    color: var(--white);
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition-smooth);
}

.btn-view:hover {
    background: var(--cactus-light);
    transform: translateX(3px);
}

/* ============================================
   SERVICES SECTION
   ============================================ */
.services-section {
    background: var(--light-bg);
}

.service-card-modern {
    background: var(--white);
    padding: 40px 30px;
    border-radius: 16px;
    text-align: center;
    transition: var(--transition-smooth);
    height: 100%;
    border: 2px solid transparent;
}

.service-card-modern:hover {
    border-color: var(--cactus-green);
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.service-icon-wrapper {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 32px;
    color: var(--white);
    transition: var(--transition-smooth);
}

.service-card-modern:hover .service-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

.service-card-modern h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 15px;
}

.service-card-modern p {
    font-size: 0.95rem;
    color: var(--text-gray);
    margin-bottom: 20px;
    line-height: 1.6;
}

.service-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--cactus-green);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: var(--transition-smooth);
}

.service-link:hover {
    gap: 12px;
    color: var(--cactus-light);
}

/* ============================================
   TESTIMONIALS SECTION
   ============================================ */
.testimonials-section {
    background: linear-gradient(135deg, var(--cactus-dark), var(--cactus-green));
    position: relative;
}

.testimonials-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="%231A472A"/><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></svg>');
    opacity: 0.3;
}

.testimonials-section .container {
    position: relative;
    z-index: 2;
}

.testimonial-card-modern {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: var(--transition-smooth);
    height: 100%;
}

.testimonial-card-modern:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-5px);
    border-color: var(--gold-accent);
}

.quote-icon {
    font-size: 2rem;
    color: var(--gold-accent);
    opacity: 0.5;
    margin-bottom: 20px;
}

.rating {
    display: flex;
    gap: 5px;
}

.rating i {
    color: var(--gold-accent);
    font-size: 14px;
}

.testimonial-text {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.7;
    margin-bottom: 25px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    border: 3px solid var(--gold-accent);
    object-fit: cover;
}

.author-info h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--white);
    margin-bottom: 3px;
}

.author-info p {
    font-size: 0.85rem;
    color: var(--gold-accent);
    margin: 0;
}

/* Stats */
.stats-row {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--gold-accent);
    margin-bottom: 10px;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
}

.stat-stars {
    display: flex;
    gap: 3px;
    justify-content: center;
    margin-top: 8px;
}

.stat-stars i {
    color: var(--gold-accent);
    font-size: 14px;
}

/* ============================================
   CTA SECTION
   ============================================ */
.cta-section {
    background: var(--light-bg);
}

.cta-wrapper {
    background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
    border-radius: 24px;
    padding: 80px 60px;
    position: relative;
    overflow: hidden;
}

.cta-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(201, 169, 97, 0.2) 0%, transparent 70%);
}

.cta-title {
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 700;
    color: var(--white);
    margin-bottom: 15px;
    letter-spacing: -1px;
}

.cta-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 35px;
}

.cta-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.offer-badge {
    position: relative;
}

.badge-circle {
    width: 240px;
    height: 240px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: float 3s ease-in-out infinite;
    padding: 20px;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.discount {
    font-size: 3rem;
    font-weight: 700;
    color: var(--cactus-green);
    line-height: 1;
}

.offer-label {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-top: 8px;
}

.offer-desc {
    font-size: 0.85rem;
    color: var(--text-gray);
    margin-top: 5px;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 991px) {
    .py-7 {
        padding: 70px 0;
    }
    
    .booking-form {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .floating-badge {
        position: static;
        margin-top: 30px;
        max-width: 250px;
    }
    
    .cta-wrapper {
        padding: 50px 30px;
        text-align: center;
    }
    
    .cta-actions {
        justify-content: center;
    }
    
    .badge-circle {
        margin: 30px auto 0;
    }
}

@media (max-width: 768px) {
    .hero-cta {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    
    .btn-modern {
        width: 100%;
    }
    
    .scroll-indicator {
        display: none;
    }
    
    .booking-form-inline {
        padding: 20px;
    }
    
    .stats-row .row {
        gap: 30px;
    }
    
    .booking-form {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: clamp(2.5rem, 6vw, 4rem);
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .booking-form-inline {
        margin: 0 -15px;
        border-radius: 0;
    }
    
    .cta-actions {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 50
    });
    
    // Gestion des dates de réservation
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    
    if (dateInputs.length >= 2) {
        // Date d'arrivée
        dateInputs[0].min = today;
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInputs[0].value = tomorrow.toISOString().split('T')[0];
        
        // Date de départ
        dateInputs[1].min = tomorrow.toISOString().split('T')[0];
        const dayAfter = new Date(tomorrow);
        dayAfter.setDate(dayAfter.getDate() + 2);
        dateInputs[1].value = dayAfter.toISOString().split('T')[0];
        
        // Update departure min when arrival changes
        dateInputs.forEach((input, index) => {
            if (index === 0) {
                input.addEventListener('change', function() {
                    const arrivalDate = new Date(this.value);
                    arrivalDate.setDate(arrivalDate.getDate() + 1);
                    
                    dateInputs[1].min = arrivalDate.toISOString().split('T')[0];
                    
                    if (new Date(dateInputs[1].value) <= new Date(this.value)) {
                        const newDeparture = new Date(this.value);
                        newDeparture.setDate(newDeparture.getDate() + 1);
                        dateInputs[1].value = newDeparture.toISOString().split('T')[0];
                    }
                });
            }
        });
    }
    
    // Wishlist buttons
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#DC2626';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
            }
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Counter animation for stats
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };
    
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const text = stat.textContent;
                    if (text.includes('%') || text.includes('+')) {
                        const number = parseInt(text);
                        animateCounter(stat, 0, number, 2000, text.includes('%') ? '%' : '+');
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    const statsRow = document.querySelector('.stats-row');
    if (statsRow) {
        statsObserver.observe(statsRow);
    }
    
    function animateCounter(element, start, end, duration, suffix = '') {
        let startTime = null;
        
        function animation(currentTime) {
            if (!startTime) startTime = currentTime;
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current + suffix;
            
            if (progress < 1) {
                requestAnimationFrame(animation);
            }
        }
        
        requestAnimationFrame(animation);
    }
    
    // Validation des formulaires de réservation
    const bookingForms = document.querySelectorAll('.booking-form');
    bookingForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const arrivalInput = this.querySelector('input[type="date"]:nth-of-type(1)');
            const departureInput = this.querySelector('input[type="date"]:nth-of-type(2)');
            
            if (arrivalInput && departureInput) {
                const arrivalDate = new Date(arrivalInput.value);
                const departureDate = new Date(departureInput.value);
                
                if (departureDate <= arrivalDate) {
                    alert('La date de départ doit être postérieure à la date d\'arrivée.');
                    departureInput.focus();
                    return false;
                }
                
                // Simulation de recherche
                alert('Recherche en cours... Redirection vers les disponibilités.');
                // window.location.href = '<?php echo e(route("frontend.rooms")); ?>?arrival=' + arrivalInput.value + '&departure=' + departureInput.value;
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/frontend/pages/home.blade.php ENDPATH**/ ?>