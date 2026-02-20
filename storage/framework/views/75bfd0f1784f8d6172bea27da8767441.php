

<?php $__env->startSection('title', 'Réservation en ligne - Hôtel Cactus Palace'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #4CAF50;
    --primary-dark: #2E7D32;
    --primary-light: #E8F5E9;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Hero */
.hero-reservation {
    background: linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)), 
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920') center/cover;
    color: white;
    padding: 100px 0 60px;
    text-align: center;
}
.hero-reservation h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

/* Cards */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    margin-bottom: 1.5rem;
}
.card-header {
    background: var(--primary);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 1rem 1.5rem;
    border: none;
}
.card-header h4 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
}
.card-body {
    padding: 1.5rem;
}

/* Forms */
.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: .5rem;
}
.form-control,
.form-select {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: .75rem 1rem;
    transition: all .3s;
}
.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(76,175,80,.1);
}

/* Section Headers */
.section-header {
    display: flex;
    align-items: center;
    gap: .5rem;
    color: var(--primary-dark);
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: .75rem;
    border-bottom: 2px solid var(--primary-light);
}

/* Room Cards - Compact List */
.room-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    cursor: pointer;
    transition: all .3s;
    background: white;
    margin-bottom: .75rem;
}
.room-card:hover {
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(76,175,80,.15);
}
.room-card.selected {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: 0 0 0 3px rgba(76,175,80,.2);
}
.room-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .5rem;
}
.room-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--primary-dark);
}
.room-number {
    font-size: .85rem;
    color: #666;
}
.room-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.room-price {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--primary);
}
.room-price-unit {
    font-size: .8rem;
    color: #999;
}
.room-info {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .85rem;
    color: #666;
}
.room-total {
    font-size: .9rem;
    font-weight: 600;
}

/* Compact scrollable list */
#roomsList::-webkit-scrollbar {
    width: 8px;
}
#roomsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
#roomsList::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
}
#roomsList::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}

/* Summary Box */
.summary-box {
    background: var(--primary-light);
    border-left: 4px solid var(--primary);
    border-radius: 8px;
    padding: 1.25rem;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: .75rem;
}
.summary-row:last-child {
    margin-bottom: 0;
}
.summary-total {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary);
}

/* Buttons */
.btn {
    border-radius: 8px;
    padding: .75rem 1.5rem;
    font-weight: 600;
    transition: all .3s;
}
.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
}
.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
}
.btn-primary:disabled {
    background: #ccc;
    border-color: #ccc;
}
.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

/* Features List */
.feature-item {
    display: flex;
    align-items: start;
    gap: 1rem;
    margin-bottom: 1rem;
}
.feature-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}
.feature-content h6 {
    margin-bottom: .25rem;
    font-weight: 600;
}
.feature-content small {
    color: #666;
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 3rem 1rem;
}
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}
.empty-state i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

/* Sticky Sidebar */
.sticky-sidebar {
    position: sticky;
    top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-reservation {
        padding: 60px 0 40px;
    }
    .hero-reservation h1 {
        font-size: 1.75rem;
    }
    .card-body {
        padding: 1rem;
    }
    .room-price {
        font-size: 1.25rem;
    }
    .summary-total {
        font-size: 1.25rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="hero-reservation">
    <div class="container">
        <h1>Réservation en ligne</h1>
        <p class="lead">Choisissez votre chambre et réservez en quelques clics</p>
    </div>
</section>


<section class="py-5">
    <div class="container">
        <div class="row">
            
            
            <div class="col-lg-8">
                <form id="reservationForm" action="<?php echo e(route('frontend.reservation.request')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-user me-2"></i>Vos informations</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" required>
                                </div>
                                
                                
                                <div class="col-md-6">
                                    <label class="form-label">Genre <span class="text-danger">*</span></label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Sélectionnez votre genre</option>
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profession <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="job" placeholder="Votre profession" required>
                                </div>
                                
                                
                                <div class="col-md-6">
                                    <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="birthdate" required 
                                        max="<?php echo e(date('Y-m-d', strtotime('-18 years'))); ?>" 
                                        value="<?php echo e(date('Y-m-d', strtotime('-30 years'))); ?>">
                                    <small class="text-muted">Vous devez avoir au moins 18 ans</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-calendar me-2"></i>Dates du séjour</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Arrivée <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Départ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Personnes <span class="text-danger">*</span></label>
                                    <select class="form-select" name="adults" id="adults">
                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e($i == 2 ? 'selected' : ''); ?>><?php echo e($i); ?> personne<?php echo e($i > 1 ? 's' : ''); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary w-100" id="checkAvailability">
                                    <i class="fas fa-search me-2"></i>Rechercher les chambres disponibles
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-bed me-2"></i>Choisissez votre chambre</h4>
                        </div>
                        <div class="card-body">
                            
                            <div id="roomFilters" style="display:none">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="typeFilter">
                                            <option value="">Tous les types</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="priceFilter">
                                            <option value="">Tous les prix</option>
                                            <option value="50000">≤ 50 000 FCFA</option>
                                            <option value="100000">≤ 100 000 FCFA</option>
                                            <option value="150000">≤ 150 000 FCFA</option>
                                            <option value="200000">≤ 200 000 FCFA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="alert alert-info small mb-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="roomCount">0 chambre(s) disponible(s)</span>
                                </div>
                            </div>
                            
                            
                            <div id="roomsList" style="max-height: 400px; overflow-y: auto;">
                                <div class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <p class="text-muted">Cliquez sur "Rechercher" pour voir les chambres</p>
                                </div>
                            </div>
                            <input type="hidden" name="room_id" id="selected_room_id">
                        </div>
                    </div>
                    
                    
                    <div class="card">
                        <div class="card-body">
                            <label class="form-label">Demandes spéciales (optionnel)</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Précisez vos demandes particulières..."></textarea>
                            
                            
                            <div id="summary" class="summary-box mt-4" style="display:none">
                                <h6 class="fw-bold mb-3">Récapitulatif</h6>
                                <div class="summary-row">
                                    <span>Chambre:</span>
                                    <strong id="summaryRoom">-</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Nuits:</span>
                                    <strong id="summaryNights">0</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Prix/nuit:</span>
                                    <strong id="summaryPrice">-</strong>
                                </div>
                                <hr>
                                <div class="summary-row">
                                    <span class="fw-bold">Total:</span>
                                    <span class="summary-total" id="summaryTotal">0 FCFA</span>
                                </div>
                            </div>
                            
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Confirmer ma réservation
                                </button>
                            </div>
                            
                            <p class="text-center text-muted small mt-3">
                                <i class="fas fa-lock me-1"></i>
                                Réservation sécurisée
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            
            
            <div class="col-lg-4">
                <div class="card sticky-sidebar">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-info-circle me-2" style="color:var(--primary)"></i>
                            Pourquoi réserver chez nous ?
                        </h5>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Meilleur tarif garanti</h6>
                                <small>Prix les plus bas du marché</small>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Annulation gratuite</h6>
                                <small>Jusqu'à 48h avant l'arrivée</small>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-ban"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Sans frais cachés</h6>
                                <small>Prix transparent</small>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="feature-content">
                                <h6>Support 24/7</h6>
                                <small>Équipe disponible</small>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="fw-bold mb-3">Besoin d'aide ?</h6>
                        <div class="d-grid gap-2">
                            <a href="https://wa.me/229XXXXX" target="_blank" class="btn btn-success">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="tel:+229XXXXX" class="btn btn-outline-primary">
                                <i class="fas fa-phone me-2"></i>Appeler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>


<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary);color:white;border:none">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Réservation confirmée !
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle fa-4x mb-3" style="color:var(--primary)"></i>
                <h4 id="modalTitle">Merci pour votre réservation !</h4>
                <p id="modalMessage" class="text-muted"></p>
                
                <div id="modalDetails" class="alert alert-success text-start" style="display:none">
                    <p class="mb-1"><strong>Réf:</strong> <span id="refNumber"></span></p>
                    <p class="mb-1"><strong>Client:</strong> <span id="modalName"></span></p>
                    <p class="mb-1"><strong>Arrivée:</strong> <span id="modalCheckIn"></span></p>
                    <p class="mb-1"><strong>Départ:</strong> <span id="modalCheckOut"></span></p>
                    <p class="mb-0"><strong>Total:</strong> <span id="modalTotal"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const adults = document.getElementById('adults');
    const checkBtn = document.getElementById('checkAvailability');
    const submitBtn = document.getElementById('submitBtn');
    const roomsList = document.getElementById('roomsList');
    const roomFilters = document.getElementById('roomFilters');
    const typeFilter = document.getElementById('typeFilter');
    const priceFilter = document.getElementById('priceFilter');
    const roomCount = document.getElementById('roomCount');
    const summary = document.getElementById('summary');
    const form = document.getElementById('reservationForm');
    
    let allRooms = [];
    let selectedRoom = null;
    
    // Init dates
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    checkIn.value = tomorrow.toISOString().split('T')[0];
    checkIn.min = tomorrow.toISOString().split('T')[0];
    
    const dayAfter = new Date();
    dayAfter.setDate(dayAfter.getDate() + 2);
    checkOut.value = dayAfter.toISOString().split('T')[0];
    checkOut.min = dayAfter.toISOString().split('T')[0];
    
    // Check if room_id in URL (coming from rooms page)
    const urlParams = new URLSearchParams(window.location.search);
    const preSelectedRoomId = urlParams.get('room_id');
    
    if (preSelectedRoomId) {
        // Auto-trigger availability check
        checkBtn.click();
    }
    
    // Update checkout min
    checkIn.addEventListener('change', function() {
        const nextDay = new Date(this.value);
        nextDay.setDate(nextDay.getDate() + 1);
        checkOut.min = nextDay.toISOString().split('T')[0];
        if (checkOut.value <= this.value) {
            checkOut.value = nextDay.toISOString().split('T')[0];
        }
    });
    
    // Check availability
    checkBtn.addEventListener('click', async function() {
        if (!checkIn.value || !checkOut.value) {
            alert('Veuillez sélectionner les dates');
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Recherche...';
        roomsList.innerHTML = '<div class="loading-state"><div class="spinner-border text-success"></div><p class="mt-2">Recherche des chambres...</p></div>';
        
        try {
            const params = new URLSearchParams({
                check_in: checkIn.value,
                check_out: checkOut.value,
                adults: adults.value
            });
            
            const response = await fetch('/api/available-rooms?' + params);
            const data = await response.json();
            
            if (data.rooms && data.rooms.length > 0) {
                allRooms = data.rooms;
                
                // Populate type filter
                const types = [...new Set(data.rooms.map(r => r.type_name))];
                typeFilter.innerHTML = '<option value="">Tous les types</option>';
                types.forEach(type => {
                    typeFilter.innerHTML += `<option value="${type}">${type}</option>`;
                });
                
                roomFilters.style.display = 'block';
                displayRooms(data.rooms);
                
                // Auto-select pre-selected room
                if (preSelectedRoomId) {
                    setTimeout(() => {
                        const roomCard = document.querySelector(`[data-id="${preSelectedRoomId}"]`);
                        if (roomCard) {
                            roomCard.click();
                            roomCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 300);
                }
            } else {
                roomFilters.style.display = 'none';
                roomsList.innerHTML = '<div class="empty-state"><i class="fas fa-bed"></i><h5>Aucune chambre disponible</h5><p class="text-muted">Essayez d\'autres dates</p></div>';
            }
        } catch (error) {
            roomFilters.style.display = 'none';
            roomsList.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-triangle text-danger"></i><h5>Erreur</h5><p>Réessayez plus tard</p></div>';
        }
        
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-search me-2"></i>Rechercher les chambres disponibles';
    });
    
    // Display rooms - COMPACT LIST
    function displayRooms(rooms) {
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);
        
        roomCount.textContent = `${rooms.length} chambre(s) disponible(s)`;
        
        let html = '';
        rooms.forEach(room => {
            const total = room.price * nights;
            const isPreSelected = preSelectedRoomId == room.id;
            html += `
                <div class="room-card ${isPreSelected ? 'selected' : ''}" data-id="${room.id}" data-price="${room.price}" data-name="${room.name}" data-number="${room.number}" data-type="${room.type_name || ''}">
                    <div class="room-card-header">
                        <div>
                            <div class="room-name">${room.name}</div>
                            <div class="room-number">Chambre ${room.number}</div>
                        </div>
                        <span class="badge bg-success">
                            <i class="fas fa-users me-1"></i>${room.capacity}
                        </span>
                    </div>
                    <div class="room-meta">
                        <div>
                            <span class="room-price">${room.price.toLocaleString()}</span>
                            <span class="room-price-unit">FCFA/nuit</span>
                        </div>
                        <div class="room-info">
                            <i class="fas fa-door-closed"></i>
                            ${room.type_name || 'Standard'}
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary room-total">${total.toLocaleString()} FCFA</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        roomsList.innerHTML = html;
        
        // Add click handlers
        document.querySelectorAll('.room-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                
                selectedRoom = {
                    id: this.dataset.id,
                    price: parseFloat(this.dataset.price),
                    name: this.dataset.name,
                    number: this.dataset.number
                };
                
                document.getElementById('selected_room_id').value = selectedRoom.id;
                updateSummary();
            });
        });
        
        // Auto-select if pre-selected
        if (preSelectedRoomId) {
            const preSelectedCard = document.querySelector(`[data-id="${preSelectedRoomId}"]`);
            if (preSelectedCard) {
                selectedRoom = {
                    id: preSelectedCard.dataset.id,
                    price: parseFloat(preSelectedCard.dataset.price),
                    name: preSelectedCard.dataset.name,
                    number: preSelectedCard.dataset.number
                };
                document.getElementById('selected_room_id').value = selectedRoom.id;
                updateSummary();
            }
        }
    }
    
    // Filters
    typeFilter.addEventListener('change', filterRooms);
    priceFilter.addEventListener('change', filterRooms);
    
    function filterRooms() {
        const typeVal = typeFilter.value;
        const priceVal = priceFilter.value ? parseFloat(priceFilter.value) : null;
        
        const filtered = allRooms.filter(room => {
            if (typeVal && room.type_name !== typeVal) return false;
            if (priceVal && room.price > priceVal) return false;
            return true;
        });
        
        displayRooms(filtered);
    }
    
    // Update summary
    function updateSummary() {
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / 86400000);
        const total = selectedRoom.price * nights;
        
        document.getElementById('summaryRoom').textContent = `${selectedRoom.name} (${selectedRoom.number})`;
        document.getElementById('summaryNights').textContent = nights;
        document.getElementById('summaryPrice').textContent = selectedRoom.price.toLocaleString() + ' FCFA';
        document.getElementById('summaryTotal').textContent = total.toLocaleString() + ' FCFA';
        
        summary.style.display = 'block';
        submitBtn.disabled = false;
        
        // Scroll to summary
        summary.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    // Submit form
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!selectedRoom) {
            alert('Sélectionnez une chambre');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('modalMessage').textContent = data.message || 'Réservation confirmée';
                
                if (data.transaction) {
                    document.getElementById('modalDetails').style.display = 'block';
                    document.getElementById('refNumber').textContent = 'RES-' + String(data.transaction.id).padStart(6, '0');
                    document.getElementById('modalName').textContent = formData.get('name');
                    document.getElementById('modalCheckIn').textContent = checkIn.value;
                    document.getElementById('modalCheckOut').textContent = checkOut.value;
                    document.getElementById('modalTotal').textContent = document.getElementById('summaryTotal').textContent;
                }
                
                new bootstrap.Modal(document.getElementById('successModal')).show();
                
                // Reset
                form.reset();
                summary.style.display = 'none';
                roomFilters.style.display = 'none';
                roomsList.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>Recherchez des chambres</p></div>';
                submitBtn.disabled = true;
                selectedRoom = null;
                allRooms = [];
                
                // Remove room_id from URL
                window.history.replaceState({}, document.title, window.location.pathname);
            } else {
                alert('Erreur: ' + (data.message || 'Réessayez'));
            }
        } catch (error) {
            alert('Erreur de connexion');
        }
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmer ma réservation';
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/frontend/pages/reservation.blade.php ENDPATH**/ ?>