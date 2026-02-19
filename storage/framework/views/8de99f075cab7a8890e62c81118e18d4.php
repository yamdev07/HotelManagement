

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
                            
                            <!-- Formulaire de réservation -->
                            <form id="bookingForm">
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
                                
                                <!-- Adultes -->
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-friends me-2"></i>Adultes
                                    </label>
                                    <select class="form-select" id="adults" name="adults">
                                        <?php for($i = 1; $i <= min($room->capacity, 6); $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e($i == min($room->capacity, 2) ? 'selected' : ''); ?>>
                                                <?php echo e($i); ?> adulte<?php echo e($i > 1 ? 's' : ''); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <!-- Enfants -->
                                <div class="form-group mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-child me-2"></i>Enfants (2-12 ans)
                                    </label>
                                    <select class="form-select" id="children" name="children">
                                        <?php for($i = 0; $i <= 3; $i++): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?> enfant<?php echo e($i > 1 ? 's' : ''); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <!-- Résumé du prix -->
                                <div class="price-summary mb-4" id="priceSummary" style="display: none;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Nuits:</span>
                                        <strong id="nightsCount">0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Prix par nuit:</span>
                                        <strong><?php echo e(number_format($room->price, 0, ',', ' ')); ?> FCFA</strong>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total:</span>
                                        <strong id="totalPrice" class="text-success">0 FCFA</strong>
                                    </div>
                                </div>
                                
                                <!-- Boutons -->
                                <div class="d-grid gap-3">
                                    <button type="button" 
                                            class="btn-check-availability" 
                                            onclick="checkAvailability()">
                                        <i class="fas fa-check-circle me-2"></i>Vérifier disponibilité
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

.price-summary {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #c8e6c9;
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
}

.btn-check-availability:hover {
    background: #2E7D32;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
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

/* Modal */
.modal-content-custom {
    border-radius: 20px;
    overflow: hidden;
}

.modal-header-custom {
    background: linear-gradient(135deg, #2E7D32, #4CAF50);
    color: white;
    padding: 20px;
}

.modal-body-custom {
    padding: 30px;
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

// Calcul du prix
function calculatePrice() {
    const checkin = document.getElementById('check_in').value;
    const checkout = document.getElementById('check_out').value;
    
    if (!checkin || !checkout) return;
    
    const nights = Math.ceil((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
    const pricePerNight = <?php echo e($room->price); ?>;
    const totalPrice = nights * pricePerNight;
    
    document.getElementById('priceSummary').style.display = 'block';
    document.getElementById('nightsCount').textContent = nights;
    document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('fr-FR') + ' FCFA';
}

// Vérifier disponibilité
function checkAvailability() {
    const checkin = document.getElementById('check_in').value;
    const checkout = document.getElementById('check_out').value;
    const adults = document.getElementById('adults').value;
    const children = document.getElementById('children').value;
    
    if (!checkin || !checkout) {
        showAlert('Veuillez sélectionner les dates de séjour.', 'warning');
        return;
    }
    
    const nights = Math.ceil((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
    const totalPrice = nights * <?php echo e($room->price); ?>;
    
    // Ici vous pouvez appeler votre API de vérification
    // Pour l'exemple, on simule une disponibilité
    
    showBookingModal(checkin, checkout, nights, totalPrice, adults, children);
}

// Afficher la modal de réservation
function showBookingModal(checkin, checkout, nights, totalPrice, adults, children) {
    // Formater les dates
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('fr-FR', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    };
    
    const modalHtml = `
        <div class="modal fade" id="bookingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-content-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>Chambre disponible
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body modal-body-custom">
                        <div class="text-center mb-4">
                            <div class="success-animation mb-3">
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            </div>
                            <h4>Votre chambre est disponible !</h4>
                        </div>
                        
                        <div class="booking-summary p-3 mb-4" style="background: #f8f9fa; border-radius: 10px;">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Arrivée</small>
                                    <strong>${formatDate(checkin)}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Départ</small>
                                    <strong>${formatDate(checkout)}</strong>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Durée</small>
                                    <strong>${nights} nuit${nights > 1 ? 's' : ''}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Occupants</small>
                                    <strong>${adults} adulte${adults > 1 ? 's' : ''}${children > 0 ? `, ${children} enfant${children > 1 ? 's' : ''}` : ''}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted d-block">Prix total</small>
                                    <h3 class="text-success mb-0">${totalPrice.toLocaleString('fr-FR')} FCFA</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('frontend.contact')); ?>?room_id=<?php echo e($room->id); ?>&checkin=${checkin}&checkout=${checkout}&adults=${adults}&children=${children}" 
                               class="btn-check-availability">
                                <i class="fas fa-calendar-check me-2"></i>Confirmer la réservation
                            </a>
                            <button type="button" class="btn-contact" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Supprimer l'ancienne modal si elle existe
    const oldModal = document.getElementById('bookingModal');
    if (oldModal) oldModal.remove();
    
    // Ajouter la nouvelle modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher la modal
    const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
    modal.show();
}

// Afficher une alerte
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            <div>${message}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Calculer le prix initial
    calculatePrice();
    
    // Écouter les changements de dates
    document.getElementById('check_in').addEventListener('change', calculatePrice);
    document.getElementById('check_out').addEventListener('change', calculatePrice);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/frontend/pages/room-details.blade.php ENDPATH**/ ?>