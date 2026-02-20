

<?php $__env->startSection('title', $room->name . ' - Hôtel Cactus Palace'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section avec l'image principale en fond -->
    <section class="hero-section-room-details" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?php echo e($room->first_image_url ?? asset('img/room/gamesetting.png')); ?>'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <div class="mb-3">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star text-warning"></i>
                        <?php endfor; ?>
                        <span class="ms-2">(24 avis)</span>
                    </div>
                    <h1 class="display-3 fw-bold mb-3"><?php echo e($room->name); ?></h1>
                    <p class="lead mb-4"><?php echo e($room->view ?? 'Découvrez notre chambre luxueuse'); ?></p>
                    
                    <!-- Badges d'informations -->
                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                        <div class="badge-info-item">
                            <i class="fas fa-user-friends me-2"></i><?php echo e($room->capacity); ?> Personnes
                        </div>
                        <div class="badge-info-item">
                            <i class="fas fa-expand-arrows-alt me-2"></i><?php echo e($room->size ?? '25'); ?> m²
                        </div>
                        <div class="badge-info-item">
                            <i class="fas fa-bed me-2"></i><?php echo e($room->type->name ?? 'Standard'); ?>

                        </div>
                        <div class="badge-info-item">
                            <i class="fas fa-<?php echo e($room->is_available_today ? 'check-circle' : 'times-circle'); ?> me-2"></i>
                            <?php echo e($room->is_available_today ? 'Disponible' : 'Non disponible'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section principale avec galerie et réservation -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Colonne gauche - Galerie d'images -->
                <div class="col-lg-8">
                    <!-- Image principale -->
                    <div class="main-image-container mb-3">
                        <?php
                            // Image principale par défaut
                            $mainImage = asset('img/room/gamesetting.png');
                            
                            // Chercher une image dans les images de la chambre
                            if($room->images && $room->images->count() > 0) {
                                $firstImage = $room->images->first();
                                $testPath = 'img/room/' . $room->number . '/' . $firstImage->url;
                                if(file_exists(public_path($testPath))) {
                                    $mainImage = asset($testPath);
                                }
                            }
                        ?>
                        
                        <img src="<?php echo e($mainImage); ?>" 
                             alt="<?php echo e($room->name); ?>" 
                             id="mainRoomImage"
                             class="img-fluid rounded-4 shadow"
                             style="width: 100%; height: 450px; object-fit: cover;">
                        
                        <!-- Badge de disponibilité sur l'image -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge-availability <?php echo e($room->is_available_today ? 'badge-available' : 'badge-unavailable'); ?>">
                                <i class="fas fa-<?php echo e($room->is_available_today ? 'check-circle' : 'times-circle'); ?> me-1"></i>
                                <?php echo e($room->is_available_today ? 'Disponible' : 'Non disponible'); ?>

                            </span>
                        </div>
                    </div>
                    
                    <!-- Miniatures -->
                    <?php if($room->images && $room->images->count() > 0): ?>
                    <div class="row g-2">
                        <?php $__currentLoopData = $room->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $thumbPath = 'img/room/' . $room->number . '/' . $image->url;
                                $thumbUrl = file_exists(public_path($thumbPath)) ? asset($thumbPath) : asset('img/room/gamesetting.png');
                            ?>
                            <div class="col-3">
                                <img src="<?php echo e($thumbUrl); ?>" 
                                     alt="Miniature <?php echo e($index+1); ?>"
                                     class="img-fluid rounded-3 thumbnail-image"
                                     style="height: 100px; width: 100%; object-fit: cover; cursor: pointer; <?php echo e($index == 0 ? 'border: 3px solid #4CAF50;' : ''); ?>"
                                     onclick="changeMainImage('<?php echo e($thumbUrl); ?>', this)">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Description détaillée -->
                    <div class="room-description-card mt-4">
                        <h3 class="section-title">
                            <i class="fas fa-align-left me-2"></i>Description de la chambre
                        </h3>
                        <div class="description-content">
                            <p class="lead-text"><?php echo e($room->name); ?> - <?php echo e($room->type->name ?? 'Chambre Standard'); ?></p>
                            <p class="main-description"><?php echo e($room->view ?? 'Profitez d\'un séjour exceptionnel dans cette chambre luxueuse.'); ?></p>
                            
                            <?php if($room->description): ?>
                                <p class="full-description"><?php echo e($room->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Équipements -->
                    <?php if($room->facilities && $room->facilities->count() > 0): ?>
                    <div class="facilities-card mt-4">
                        <h3 class="section-title">
                            <i class="fas fa-concierge-bell me-2"></i>Équipements & Services
                        </h3>
                        <div class="row g-3">
                            <?php $__currentLoopData = $room->facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="facility-item">
                                    <div class="facility-icon">
                                        <i class="fas fa-<?php echo e($facility->icon ?? 'check'); ?>"></i>
                                    </div>
                                    <div class="facility-info">
                                        <strong><?php echo e($facility->name); ?></strong>
                                        <?php if($facility->description): ?>
                                            <p class="small text-muted mb-0"><?php echo e($facility->description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Colonne droite - Réservation -->
                <div class="col-lg-4">
                    <div class="booking-card sticky-top" style="top: 20px;">
                        <div class="booking-header">
                            <h4>Réserver cette chambre</h4>
                            <p class="mb-0">Séjournez dans le luxe</p>
                        </div>
                        
                        <div class="booking-body">
                            <!-- Prix -->
                            <div class="price-box text-center mb-4">
                                <span class="price-label">À partir de</span>
                                <div class="price-amount"><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA</div>
                                <span class="price-period">par nuit</span>
                            </div>
                            
                            <!-- Formulaire de réservation - Redirige vers /reservation -->
                            <form action="<?php echo e(route('frontend.reservation')); ?>" method="GET">
                                <input type="hidden" name="room_id" value="<?php echo e($room->id); ?>">
                                
                                <!-- Dates -->
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>Dates de séjour
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" 
                                                   class="form-control date-input" 
                                                   id="check_in" 
                                                   name="check_in"
                                                   value="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                                                   min="<?php echo e(date('Y-m-d')); ?>">
                                            <small class="text-muted">Arrivée</small>
                                        </div>
                                        <div class="col-6">
                                            <input type="date" 
                                                   class="form-control date-input" 
                                                   id="check_out" 
                                                   name="check_out"
                                                   value="<?php echo e(date('Y-m-d', strtotime('+2 day'))); ?>"
                                                   min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>">
                                            <small class="text-muted">Départ</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Adultes (enfants supprimé) -->
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-friends me-2"></i>Nombre de personnes
                                    </label>
                                    <select class="form-select" id="adults" name="adults">
                                        <?php for($i = 1; $i <= $room->capacity; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e($i == 2 ? 'selected' : ''); ?>><?php echo e($i); ?> personne<?php echo e($i > 1 ? 's' : ''); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <!-- Bouton unique - Vérifier et réserver -->
                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn-check-availability">
                                        <i class="fas fa-check-circle me-2"></i>Réserver maintenant
                                    </button>
                                    
                                    <a href="<?php echo e(route('frontend.contact')); ?>?room_id=<?php echo e($room->id); ?>" 
                                       class="btn-contact">
                                        <i class="fas fa-envelope me-2"></i>Nous contacter
                                    </a>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>Réservation sécurisée
                                    </small>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Informations complémentaires -->
                        <div class="booking-footer">
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Check-in: 15h00 - 22h00</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span>Check-out: avant 12h00</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-wifi"></i>
                                <span>Wi-Fi gratuit inclus</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chambres similaires -->
    <?php if(isset($relatedRooms) && $relatedRooms->count() > 0): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-large">Chambres similaires</h2>
                <p class="text-muted">Découvrez d'autres chambres qui pourraient vous plaire</p>
            </div>
            
            <div class="row g-4">
                <?php $__currentLoopData = $relatedRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedRoom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="room-card-similar">
                        <div class="room-card-image">
                            <?php
                                $relatedImage = asset('img/default/default-room.png');
                                if($relatedRoom->images && $relatedRoom->images->count() > 0) {
                                    $testPath = 'img/room/' . $relatedRoom->number . '/' . $relatedRoom->images->first()->url;
                                    if(file_exists(public_path($testPath))) {
                                        $relatedImage = asset($testPath);
                                    }
                                }
                            ?>
                            <img src="<?php echo e($relatedImage); ?>" alt="<?php echo e($relatedRoom->name); ?>">
                            <span class="badge-status <?php echo e($relatedRoom->is_available_today ? 'badge-available' : 'badge-unavailable'); ?>">
                                <?php echo e($relatedRoom->is_available_today ? 'Disponible' : 'Sur demande'); ?>

                            </span>
                        </div>
                        <div class="room-card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5><?php echo e($relatedRoom->name); ?></h5>
                                <span class="room-capacity">
                                    <i class="fas fa-user-friends"></i> <?php echo e($relatedRoom->capacity); ?>

                                </span>
                            </div>
                            <p class="text-muted small"><?php echo e($relatedRoom->type->name ?? 'Standard'); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="room-price"><?php echo e(number_format($relatedRoom->price, 0, ',', ' ')); ?> FCFA</span>
                                    <small class="text-muted d-block">par nuit</small>
                                </div>
                                <a href="<?php echo e(route('frontend.room.details', $relatedRoom->id)); ?>" class="btn-view">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Hero Section */
.hero-section-room-details {
    padding: 120px 0 80px;
    margin-bottom: 20px;
}

.badge-info-item {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 0.95rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Badges disponibilité */
.badge-availability {
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.badge-available {
    background: rgba(76, 175, 80, 0.9);
    color: white;
}

.badge-unavailable {
    background: rgba(244, 67, 54, 0.9);
    color: white;
}

/* Images */
.main-image-container {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.thumbnail-image {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.thumbnail-image:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
}

/* Description */
.room-description-card, .facilities-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border: 1px solid #e8f5e9;
}

.section-title {
    color: #2E7D32;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.lead-text {
    font-size: 1.2rem;
    color: #1B5E20;
    font-weight: 500;
    margin-bottom: 15px;
}

.main-description {
    font-size: 1rem;
    line-height: 1.8;
    color: #4a5568;
    margin-bottom: 15px;
}

.full-description {
    color: #666;
    line-height: 1.8;
}

/* Facilities */
.facility-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.facility-item:hover {
    background: #e8f5e9;
    transform: translateX(5px);
}

.facility-icon {
    width: 40px;
    height: 40px;
    background: #4CAF50;
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Carte de réservation */
.booking-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border: 1px solid #e8f5e9;
}

.booking-header {
    background: linear-gradient(135deg, #2E7D32, #4CAF50);
    color: white;
    padding: 20px;
    text-align: center;
}

.booking-header h4 {
    margin: 0 0 5px 0;
    font-size: 1.3rem;
}

.booking-body {
    padding: 25px;
}

.price-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 15px;
    border: 1px solid #e8f5e9;
}

.price-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2E7D32;
    line-height: 1.2;
}

.price-period {
    font-size: 0.85rem;
    color: #999;
}

.form-label {
    color: #2E7D32;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.date-input, .form-select {
    border: 1px solid #c8e6c9;
    border-radius: 10px;
    padding: 10px;
}

.date-input:focus, .form-select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
}

.btn-check-availability {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
    text-align: center;
}

.btn-check-availability:hover {
    background: #2E7D32;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
    color: white;
}

.btn-contact {
    background: white;
    color: #4CAF50;
    border: 2px solid #4CAF50;
    padding: 13px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    display: block;
}

.btn-contact:hover {
    background: #4CAF50;
    color: white;
}

.booking-footer {
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #e8f5e9;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    color: #666;
    font-size: 0.9rem;
}

.info-item i {
    color: #4CAF50;
    width: 20px;
}

/* Chambres similaires */
.room-card-similar {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.room-card-similar:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(76, 175, 80, 0.2);
}

.room-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.room-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s ease;
}

.room-card-similar:hover .room-card-image img {
    transform: scale(1.1);
}

.badge-status {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.75rem;
    font-weight: 600;
}

.room-card-body {
    padding: 20px;
}

.room-capacity {
    background: #e8f5e9;
    color: #2E7D32;
    padding: 3px 10px;
    border-radius: 5px;
    font-size: 0.8rem;
    font-weight: 600;
}

.room-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #4CAF50;
}

.btn-view {
    background: #4CAF50;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: #2E7D32;
    color: white;
}

.section-title-large {
    color: #2E7D32;
    font-size: 2.2rem;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section-room-details {
        padding: 80px 0 40px;
    }
    
    .badge-info-item {
        width: 100%;
        text-align: center;
    }
    
    .main-image-container img {
        height: 300px !important;
    }
    
    .booking-card {
        margin-top: 20px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Variables pour la galerie
let currentImageIndex = 0;
const images = [
    <?php if($room->images && $room->images->count() > 0): ?>
        <?php $__currentLoopData = $room->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $imgPath = 'img/room/' . $room->number . '/' . $image->url;
                $imgUrl = file_exists(public_path($imgPath)) ? asset($imgPath) : asset('img/room/gamesetting.png');
            ?>
            '<?php echo e($imgUrl); ?>',
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
];

// Changer l'image principale
function changeMainImage(src, element) {
    document.getElementById('mainRoomImage').src = src;
    
    // Mettre à jour les miniatures
    document.querySelectorAll('.thumbnail-image').forEach(thumb => {
        thumb.style.border = 'none';
    });
    
    if (element) {
        element.style.border = '3px solid #4CAF50';
    }
}

// Fonction pour naviguer dans la galerie
function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    document.getElementById('mainRoomImage').src = images[currentImageIndex];
    
    // Mettre à jour les miniatures
    document.querySelectorAll('.thumbnail-image').forEach((thumb, index) => {
        thumb.style.border = index === currentImageIndex ? '3px solid #4CAF50' : 'none';
    });
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    document.getElementById('mainRoomImage').src = images[currentImageIndex];
    
    // Mettre à jour les miniatures
    document.querySelectorAll('.thumbnail-image').forEach((thumb, index) => {
        thumb.style.border = index === currentImageIndex ? '3px solid #4CAF50' : 'none';
    });
}

// Ajouter les contrôles de navigation
document.addEventListener('DOMContentLoaded', function() {
    if (images.length > 1) {
        const container = document.querySelector('.main-image-container');
        const navHtml = `
            <div class="position-absolute top-50 start-0 end-0 d-flex justify-content-between px-3" style="transform: translateY(-50%);">
                <button class="btn btn-light btn-sm rounded-circle shadow" onclick="prevImage()" style="width: 40px; height: 40px;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-light btn-sm rounded-circle shadow" onclick="nextImage()" style="width: 40px; height: 40px;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', navHtml);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/frontend/pages/room-details.blade.php ENDPATH**/ ?>