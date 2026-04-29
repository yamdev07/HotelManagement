<?php $__env->startSection('title', 'Contact — Cactus Palace 5 Étoiles'); ?>

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
.contact-hero {
    position: relative;
    min-height: 68vh;
    display: flex;
    align-items: center;
    background: url('https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
    background-attachment: fixed;
    overflow: hidden;
}
.contact-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15,41,24,0.88) 0%, rgba(26,71,42,0.72) 50%, rgba(201,169,97,0.12) 100%);
}
.contact-hero .container { position: relative; z-index: 2; }

.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 8px 22px;
    background: rgba(201,169,97,0.15);
    border: 1px solid rgba(201,169,97,0.4);
    border-radius: 50px;
    color: var(--gold-accent);
    font-size: 12px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    margin-bottom: 24px;
}
.contact-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 6vw, 4.5rem);
    font-weight: 700; color: var(--white); line-height: 1.1;
    margin-bottom: 20px; letter-spacing: -1px;
}
.contact-hero h1 em { font-style: italic; color: var(--gold-accent); }
.contact-hero .hero-lead {
    font-size: 1.1rem; color: rgba(255,255,255,0.78);
    max-width: 540px; line-height: 1.8; margin-bottom: 0;
}

.hero-stat-bar {
    display: flex; gap: 40px; flex-wrap: wrap;
    padding-top: 36px; border-top: 1px solid rgba(255,255,255,0.15); margin-top: 36px;
}
.hero-stat .num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700; color: var(--gold-accent); line-height: 1;
}
.hero-stat .lbl {
    font-size: 11px; color: rgba(255,255,255,0.6);
    letter-spacing: 1px; text-transform: uppercase; margin-top: 4px;
}

/* ── SECTIONS ── */
.py-section { padding: 90px 0; }

.section-tag {
    display: inline-block; padding: 5px 18px;
    background: rgba(26,71,42,0.08); color: var(--cactus-green);
    border-radius: 50px; font-size: 11px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase; margin-bottom: 14px;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    font-weight: 700; color: var(--text-dark); letter-spacing: -0.5px; margin-bottom: 14px;
}
.section-subtitle { font-size: 1rem; color: var(--text-gray); line-height: 1.8; }

/* ── CONTACT INFO CARDS ── */
.contact-info-section { background: var(--light-bg); }

.contact-info-card {
    background: var(--white); border-radius: 20px;
    border: 1px solid var(--border-color); padding: 36px 28px;
    text-align: center; transition: var(--transition); height: 100%;
    position: relative; overflow: hidden;
}
.contact-info-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: linear-gradient(90deg, var(--cactus-green), var(--gold-accent));
    transform: scaleX(0); transition: var(--transition);
}
.contact-info-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); border-color: transparent; }
.contact-info-card:hover::before { transform: scaleX(1); }

.contact-icon-wrap {
    width: 72px; height: 72px; border-radius: 18px; margin: 0 auto 22px;
    background: linear-gradient(135deg, rgba(26,71,42,0.08), rgba(201,169,97,0.08));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: var(--cactus-green);
    transition: var(--transition);
}
.contact-info-card:hover .contact-icon-wrap {
    background: linear-gradient(135deg, var(--cactus-green), var(--cactus-light));
    color: var(--white);
}
.contact-info-card h4 {
    font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700;
    color: var(--text-dark); margin-bottom: 14px;
}
.contact-info-card p { font-size: 0.9rem; color: var(--text-gray); margin: 0; line-height: 1.8; }
.contact-info-card a { color: var(--cactus-green); text-decoration: none; font-weight: 500; }
.contact-info-card a:hover { color: var(--gold-accent); }

/* ── FORM SECTION ── */
.form-section { background: var(--white); }

.contact-form-wrap {
    background: var(--white); border-radius: 24px;
    border: 1px solid var(--border-color); box-shadow: var(--shadow-lg);
    overflow: hidden;
}
.form-header {
    background: linear-gradient(135deg, var(--cactus-dark), var(--cactus-green));
    padding: 36px 40px;
}
.form-header h3 {
    font-family: 'Playfair Display', serif; font-size: 1.6rem; color: var(--white);
    margin-bottom: 8px;
}
.form-header p { font-size: 0.9rem; color: rgba(255,255,255,0.65); margin: 0; }

.form-body { padding: 36px 40px; }

.cf-label {
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
    color: var(--cactus-green); margin-bottom: 8px; display: block;
}
.cf-input {
    width: 100%; padding: 13px 16px;
    border: 1.5px solid var(--border-color); border-radius: 10px;
    font-size: 0.9rem; color: var(--text-dark);
    background: var(--white); transition: var(--transition);
}
.cf-input:focus {
    outline: none; border-color: var(--cactus-green);
    box-shadow: 0 0 0 3px rgba(26,71,42,0.08);
}
.cf-input::placeholder { color: #bbb; }

.cf-select { cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%231A472A'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 36px;
}
.cf-textarea { resize: vertical; min-height: 130px; }

.btn-send {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 15px 40px; background: var(--cactus-green); color: var(--white);
    border: 2px solid var(--cactus-green); border-radius: 10px;
    font-size: 0.95rem; font-weight: 700; cursor: pointer;
    transition: var(--transition);
}
.btn-send:hover {
    background: transparent; color: var(--cactus-green);
    box-shadow: 0 10px 28px rgba(26,71,42,0.18);
}

/* Alerts */
.cf-alert {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 18px; border-radius: 10px; margin-bottom: 24px;
    font-size: 0.9rem;
}
.cf-alert.success { background: rgba(26,71,42,0.08); color: var(--cactus-green); border-left: 3px solid var(--cactus-green); }
.cf-alert.danger  { background: rgba(239,68,68,0.08); color: #dc2626; border-left: 3px solid #ef4444; }

/* Map section */
.map-section { background: var(--light-bg); }

.map-wrapper { border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-lg); }
.map-wrapper iframe { display: block; }

.access-card {
    background: var(--white); border-radius: 16px; padding: 24px;
    border: 1px solid var(--border-color); height: 100%;
    border-left: 3px solid var(--cactus-green); transition: var(--transition);
}
.access-card:hover { box-shadow: var(--shadow-md); transform: translateY(-4px); }
.access-card h5 {
    font-family: 'Playfair Display', serif; font-size: 1rem;
    color: var(--cactus-green); margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.access-card ul { list-style: none; padding: 0; margin: 0; }
.access-card ul li {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 0.875rem; color: var(--text-gray);
    margin-bottom: 8px;
}
.access-card ul li i { color: var(--cactus-green); font-size: 0.7rem; margin-top: 4px; flex-shrink: 0; }

/* FAQ */
.faq-section { background: var(--white); }

.faq-item {
    background: var(--white); border: 1px solid var(--border-color);
    border-radius: 14px; margin-bottom: 12px; overflow: hidden;
    transition: var(--transition);
}
.faq-item:hover { border-color: rgba(26,71,42,0.3); box-shadow: var(--shadow-sm); }

.faq-btn {
    width: 100%; background: none; border: none; padding: 20px 24px;
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; text-align: left; transition: var(--transition);
}
.faq-btn:hover { background: rgba(26,71,42,0.03); }
.faq-btn.open { background: rgba(26,71,42,0.04); }

.faq-question {
    display: flex; align-items: center; gap: 14px;
    font-size: 0.95rem; font-weight: 600; color: var(--text-dark);
}
.faq-question i { color: var(--gold-accent); font-size: 0.9rem; width: 18px; }
.faq-toggle {
    width: 28px; height: 28px; min-width: 28px; border-radius: 50%;
    background: rgba(26,71,42,0.06); display: flex; align-items: center;
    justify-content: center; color: var(--cactus-green); font-size: 0.75rem;
    transition: var(--transition);
}
.faq-btn.open .faq-toggle { background: var(--cactus-green); color: var(--white); transform: rotate(45deg); }

.faq-body {
    max-height: 0; overflow: hidden; transition: max-height .35s ease;
}
.faq-body.open { max-height: 300px; }
.faq-body-inner {
    padding: 0 24px 20px 56px;
    font-size: 0.9rem; color: var(--text-gray); line-height: 1.8;
}

/* ── RESPONSIVE ── */
@media (max-width: 991px) {
    .hero-stat-bar { gap: 24px; }
    .form-header { padding: 28px 28px; }
    .form-body { padding: 28px 28px; }
}
@media (max-width: 767px) {
    .contact-hero { min-height: 80vh; background-attachment: scroll; }
    .py-section { padding: 60px 0; }
    .form-header, .form-body { padding: 22px 20px; }
    .hero-stat-bar { gap: 18px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="contact-hero">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="hero-eyebrow">
                    <i class="fas fa-star" style="font-size:9px;"></i>
                    Cactus Palace — Cotonou, Bénin
                    <i class="fas fa-star" style="font-size:9px;"></i>
                </div>
                <h1>
                    Entrer en<br>
                    <em>contact avec nous</em>
                </h1>
                <p class="hero-lead">
                    Notre équipe est à votre disposition 24h/24 pour répondre à toutes vos questions,
                    prendre vos réservations et vous offrir l'accompagnement que vous méritez.
                </p>
                <div class="hero-stat-bar">
                    <div class="hero-stat">
                        <div class="num">24/7</div>
                        <div class="lbl">Disponible</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">&lt; 2h</div>
                        <div class="lbl">Réponse moyenne</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">5★</div>
                        <div class="lbl">Service client</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="contact-info-section py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Où nous trouver</span>
            <h2 class="section-title">Nos coordonnées</h2>
            <p class="section-subtitle mx-auto" style="max-width:560px;">
                Que vous souhaitiez nous appeler, nous écrire ou nous rendre visite, voici toutes les façons de nous joindre.
            </p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-info-card">
                    <div class="contact-icon-wrap"><i class="fas fa-map-marker-alt"></i></div>
                    <h4>Adresse</h4>
                    <p>Haie Vive, Cotonou<br>République du Bénin<br><a href="https://maps.google.com" target="_blank">Voir sur la carte <i class="fas fa-external-link-alt ms-1"></i></a></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="80">
                <div class="contact-info-card">
                    <div class="contact-icon-wrap"><i class="fas fa-phone"></i></div>
                    <h4>Téléphone</h4>
                    <p>
                        <a href="tel:+22901900000000">+229 01 90 00 00 00</a><br>
                        <a href="tel:+22902900000000">+229 02 90 00 00 00</a><br>
                        <span style="font-size:12px;color:var(--text-gray);">Disponible 24h/24 — 7j/7</span>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="160">
                <div class="contact-info-card">
                    <div class="contact-icon-wrap"><i class="fas fa-envelope"></i></div>
                    <h4>Email</h4>
                    <p>
                        <a href="mailto:contact@cactushotel.com">contact@cactushotel.com</a><br>
                        <a href="mailto:reservation@cactushotel.com">reservation@cactushotel.com</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="240">
                <div class="contact-info-card">
                    <div class="contact-icon-wrap"><i class="fas fa-clock"></i></div>
                    <h4>Horaires</h4>
                    <p>
                        Réception : <strong style="color:var(--cactus-green);">24h/24</strong><br>
                        Restaurant : 7h – 23h<br>
                        Spa : 8h – 21h
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="form-section py-section" id="contactForm">
    <div class="container">
        <div class="row g-5 align-items-start">

            
            <div class="col-lg-7" data-aos="fade-right">
                <div class="contact-form-wrap">
                    <div class="form-header">
                        <h3>Envoyez-nous un message</h3>
                        <p>Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.</p>
                    </div>
                    <div class="form-body">

                        
                        <?php if(session('success')): ?>
                        <div class="cf-alert success">
                            <i class="fas fa-check-circle fa-lg"></i>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                        <div class="cf-alert danger">
                            <i class="fas fa-exclamation-circle fa-lg"></i>
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('frontend.contact.submit')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="cf-label" for="name"><i class="fas fa-user me-1"></i>Nom complet *</label>
                                    <input type="text" class="cf-input" id="name" name="name"
                                           placeholder="Jean Dupont" required value="<?php echo e(old('name')); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="cf-label" for="email"><i class="fas fa-envelope me-1"></i>Adresse email *</label>
                                    <input type="email" class="cf-input" id="email" name="email"
                                           placeholder="jean@exemple.com" required value="<?php echo e(old('email')); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="cf-label" for="phone"><i class="fas fa-phone me-1"></i>Téléphone</label>
                                    <input type="tel" class="cf-input" id="phone" name="phone"
                                           placeholder="+229 01 23 45 67" value="<?php echo e(old('phone')); ?>">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="cf-label" for="subject"><i class="fas fa-tag me-1"></i>Sujet *</label>
                                    <select class="cf-input cf-select" id="subject" name="subject" required>
                                        <option value="" disabled selected>Sélectionnez un sujet</option>
                                        <option value="reservation" <?php echo e(old('subject')=='reservation'?'selected':''); ?>>Réservation chambre</option>
                                        <option value="information" <?php echo e(old('subject')=='information'?'selected':''); ?>>Demande d'information</option>
                                        <option value="group"       <?php echo e(old('subject')=='group'?'selected':''); ?>>Événement de groupe</option>
                                        <option value="restaurant"  <?php echo e(old('subject')=='restaurant'?'selected':''); ?>>Réservation restaurant</option>
                                        <option value="spa"         <?php echo e(old('subject')=='spa'?'selected':''); ?>>Spa & Bien-être</option>
                                        <option value="other"       <?php echo e(old('subject')=='other'?'selected':''); ?>>Autre</option>
                                    </select>
                                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-12">
                                    <label class="cf-label" for="message"><i class="fas fa-comment me-1"></i>Message *</label>
                                    <textarea class="cf-input cf-textarea" id="message" name="message"
                                              rows="5" placeholder="Votre message..." required><?php echo e(old('message')); ?></textarea>
                                    <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-12">
                                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:0.875rem;color:var(--text-gray);">
                                        <input type="checkbox" name="newsletter" <?php echo e(old('newsletter')?'checked':''); ?>

                                               style="width:16px;height:16px;accent-color:var(--cactus-green);">
                                        Je souhaite recevoir les offres spéciales et actualités du Cactus Palace
                                    </label>
                                </div>
                                <div class="col-12 pt-2">
                                    <button type="submit" class="btn-send">
                                        <i class="fas fa-paper-plane"></i> Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-5" data-aos="fade-left" data-aos-delay="100">
                <div class="mb-4">
                    <span class="section-tag">Réponse rapide</span>
                    <h2 class="section-title" style="font-size:clamp(1.5rem,2.5vw,2rem);">Nous répondons<br>dans les 2 heures</h2>
                    <p class="section-subtitle">Notre équipe dédiée traite chaque message avec soin et vous répond rapidement.</p>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:flex;align-items:center;gap:16px;padding:20px;background:var(--light-bg);border-radius:14px;border:1px solid var(--border-color);">
                        <div style="width:48px;height:48px;min-width:48px;border-radius:12px;background:linear-gradient(135deg,var(--cactus-green),var(--cactus-light));display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--text-dark);font-size:0.9rem;">Ligne directe</div>
                            <div style="font-size:0.85rem;color:var(--text-gray);">+229 01 90 00 00 00 — 24h/24</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:16px;padding:20px;background:var(--light-bg);border-radius:14px;border:1px solid var(--border-color);">
                        <div style="width:48px;height:48px;min-width:48px;border-radius:12px;background:linear-gradient(135deg,#25D366,#128C7E);display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--text-dark);font-size:0.9rem;">WhatsApp</div>
                            <div style="font-size:0.85rem;color:var(--text-gray);">Réponse instantanée</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:16px;padding:20px;background:var(--light-bg);border-radius:14px;border:1px solid var(--border-color);">
                        <div style="width:48px;height:48px;min-width:48px;border-radius:12px;background:linear-gradient(135deg,var(--gold-accent),#b8962e);display:flex;align-items:center;justify-content:center;color:white;font-size:1.1rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--text-dark);font-size:0.9rem;">Email prioritaire</div>
                            <div style="font-size:0.85rem;color:var(--text-gray);">reservation@cactushotel.com</div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:28px;padding:24px;background:linear-gradient(135deg,var(--cactus-dark),var(--cactus-green));border-radius:20px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(201,169,97,0.12);"></div>
                    <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold-accent);line-height:1;">5★</div>
                    <div style="color:rgba(255,255,255,0.9);font-weight:600;margin:6px 0 4px;font-size:0.95rem;">Cactus Palace</div>
                    <div style="color:rgba(255,255,255,0.55);font-size:0.82rem;">Haie Vive, Cotonou — Bénin</div>
                    <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.1);font-size:12px;color:rgba(255,255,255,0.5);">
                        <i class="fas fa-award me-2" style="color:var(--gold-accent);"></i>
                        Hôtel certifié 5 étoiles depuis 1995
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="map-section py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Localisation</span>
            <h2 class="section-title">Notre emplacement</h2>
            <p class="section-subtitle mx-auto" style="max-width:520px;">Situé au cœur de Haie Vive, le quartier premium de Cotonou, au Bénin.</p>
        </div>

        <div class="map-wrapper mb-4" data-aos="fade-up" data-aos-delay="80">
            <iframe
                src="https://www.google.com/maps/place//@6.3576817,2.3924401,17z/data=!4m6!1m5!3m4!2zNsKwMjEnMjcuNyJOIDLCsDIzJzQyLjEiRQ!8m2!3d6.3576817!4d2.395015?hl=fr&entry=ttu&g_ep=EgoyMDI2MDIxNy4wIKXMDSoASAFQAw%3D%3D"
                width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy"
                title="Localisation Cactus Palace, Cotonou">
            </iframe>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="access-card">
                    <h5><i class="fas fa-taxi"></i>Taxi / Transport</h5>
                    <ul>
                        <li><i class="fas fa-circle"></i>Station de taxis devant l'hôtel</li>
                        <li><i class="fas fa-circle"></i>Service de transfert aéroport disponible</li>
                        <li><i class="fas fa-circle"></i>Zémidjan (moto-taxi) à proximité</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="80">
                <div class="access-card">
                    <h5><i class="fas fa-car"></i>En voiture</h5>
                    <ul>
                        <li><i class="fas fa-circle"></i>Parking sécurisé sur place</li>
                        <li><i class="fas fa-circle"></i>Service valet disponible</li>
                        <li><i class="fas fa-circle"></i>Accès facilité depuis l'aéroport</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="160">
                <div class="access-card">
                    <h5><i class="fas fa-plane"></i>Depuis l'aéroport</h5>
                    <ul>
                        <li><i class="fas fa-circle"></i>Aéroport Cadjehoun : 15 min</li>
                        <li><i class="fas fa-circle"></i>Navette privée sur réservation</li>
                        <li><i class="fas fa-circle"></i>Accueil VIP disponible</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="faq-section py-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">FAQ</span>
            <h2 class="section-title">Questions fréquentes</h2>
            <p class="section-subtitle mx-auto" style="max-width:520px;">Trouvez rapidement des réponses à vos questions les plus courantes.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9" data-aos="fade-up" data-aos-delay="60">

                <div class="faq-item">
                    <button class="faq-btn" onclick="toggleFaq(this)">
                        <div class="faq-question"><i class="fas fa-clock"></i>Quels sont les horaires de check-in et check-out ?</div>
                        <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-body">
                        <div class="faq-body-inner">Le check-in est disponible à partir de 15h00. Le check-out doit être effectué avant 13h00. Un check-in anticipé ou un check-out tardif peut être organisé sous réserve de disponibilité. Contactez la réception pour plus d'informations.</div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="toggleFaq(this)">
                        <div class="faq-question"><i class="fas fa-paw"></i>L'hôtel accepte-t-il les animaux de compagnie ?</div>
                        <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-body">
                        <div class="faq-body-inner">Oui, le Cactus Palace accueille les animaux de compagnie de petite taille (chiens et chats jusqu'à 8 kg). Des frais de 25 000 FCFA par nuit sont applicables. Un lit et des gamelles sont fournis sur demande.</div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="toggleFaq(this)">
                        <div class="faq-question"><i class="fas fa-plane"></i>Proposez-vous un service de transfert depuis l'aéroport ?</div>
                        <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-body">
                        <div class="faq-body-inner">Oui, nous proposons un service de navette privée depuis l'aéroport international Cadjehoun de Cotonou (à 15 minutes). Le service doit être réservé au moins 48 heures à l'avance. Des véhicules berline et 4x4 sont disponibles.</div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="toggleFaq(this)">
                        <div class="faq-question"><i class="fas fa-credit-card"></i>Quels sont les moyens de paiement acceptés ?</div>
                        <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-body">
                        <div class="faq-body-inner">Nous acceptons les paiements par carte (Visa, MasterCard), espèces en FCFA, virement bancaire pour les réservations de groupe, et Mobile Money (MTN MoMo, Moov Money).</div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-btn" onclick="toggleFaq(this)">
                        <div class="faq-question"><i class="fas fa-wifi"></i>Le Wi-Fi est-il inclus ?</div>
                        <div class="faq-toggle"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-body">
                        <div class="faq-body-inner">Oui, une connexion Wi-Fi haut débit en fibre optique est disponible gratuitement dans toutes les chambres et les espaces communs de l'hôtel, 24h/24.</div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p style="font-size:0.9rem;color:var(--text-gray);margin-bottom:16px;">Vous ne trouvez pas la réponse à votre question ?</p>
                    <a href="#contactForm" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--cactus-green);color:white;border-radius:10px;font-size:0.9rem;font-weight:600;text-decoration:none;transition:var(--transition);"
                       onmouseover="this.style.background='var(--cactus-light)'" onmouseout="this.style.background='var(--cactus-green)'">
                        <i class="fas fa-envelope"></i> Nous écrire directement
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 750, easing: 'ease-out-cubic', once: true, offset: 60 });

function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const body = item.querySelector('.faq-body');
    const isOpen = btn.classList.contains('open');

    // Close all
    document.querySelectorAll('.faq-btn.open').forEach(b => {
        b.classList.remove('open');
        b.closest('.faq-item').querySelector('.faq-body').classList.remove('open');
    });

    if (!isOpen) {
        btn.classList.add('open');
        body.classList.add('open');
    }
}

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.cf-alert').forEach(a => {
        a.style.transition = 'opacity .5s ease';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 5000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\HotelManagement\resources\views/frontend/pages/contact.blade.php ENDPATH**/ ?>