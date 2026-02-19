@extends('frontend.layouts.master')

@section('title', 'Réservation - Hôtel Cactus Palace')

@section('content')
    <!-- Hero Section de la réservation -->
    <section class="hero-section-reservation">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h1 class="display-4 mb-3">Réservation en ligne</h1>
                    <p class="lead mb-4">Choisissez vos dates et nous vous trouverons la chambre parfaite</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulaire de réservation -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-success text-white py-3">
                            <h4 class="mb-0">
                                <i class="fas fa-calendar-check me-2"></i>
                                Formulaire de réservation
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <form id="reservationForm">
                                @csrf
                                
                                <!-- Informations personnelles -->
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-user me-2"></i>Vos informations
                                </h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Nom complet <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Téléphone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Pays</label>
                                        <input type="text" class="form-control" id="country" name="country" value="Burkina Faso">
                                    </div>
                                </div>

                                <!-- Détails du séjour -->
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Détails du séjour
                                </h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Date d'arrivée <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check_in" name="check_in" 
                                               min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Date de départ <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check_out" name="check_out" 
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Adultes <span class="text-danger">*</span></label>
                                        <select class="form-select" id="adults" name="adults">
                                            @for($i = 1; $i <= 4; $i++)
                                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Enfants (0-12 ans)</label>
                                        <select class="form-select" id="children" name="children">
                                            @for($i = 0; $i <= 3; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium">Type de chambre</label>
                                        <select class="form-select" id="room_type" name="room_type">
                                            <option value="">Tous les types</option>
                                            @foreach($roomTypes ?? [] as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Demandes spéciales -->
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-comment me-2"></i>Demandes spéciales
                                </h5>
                                <div class="mb-4">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="Précisez vos demandes particulières (régime alimentaire, besoins spécifiques, etc.)"></textarea>
                                </div>

                                <!-- Résumé des prix (caché initialement) -->
                                <div class="price-summary p-3 bg-light rounded mb-4" id="priceSummary" style="display: none;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Nuits:</span>
                                        <strong id="nightsCount">0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Prix estimé par nuit:</span>
                                        <strong id="pricePerNight">À déterminer</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total estimé:</span>
                                        <strong id="totalPrice" class="text-success">0 FCFA</strong>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Le prix exact sera confirmé après vérification de la disponibilité
                                    </p>
                                </div>

                                <!-- Boutons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg py-3">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer ma demande de réservation
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="checkPriceBtn">
                                        <i class="fas fa-calculator me-2"></i>
                                        Vérifier le prix estimé
                                    </button>
                                </div>

                                <p class="text-muted small mt-3 text-center">
                                    <i class="fas fa-lock me-1"></i>
                                    Vos informations sont sécurisées. Nous vous répondrons sous 24h.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar avec informations -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-info-circle me-2"></i>Pourquoi réserver chez nous ?
                            </h5>
                            
                            <div class="feature-list">
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-1">Meilleur tarif garanti</h6>
                                        <small class="text-muted">Nous vous offrons le meilleur prix</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-1">Annulation gratuite</h6>
                                        <small class="text-muted">Jusqu'à 48h avant l'arrivée</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-1">Sans frais de dossier</h6>
                                        <small class="text-muted">Aucun frais caché</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                    <div>
                                        <h6 class="mb-1">Service client 24/7</h6>
                                        <small class="text-muted">Une question ? Nous sommes là</small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-3">Besoin d'aide ?</h5>
                            <p class="small text-muted mb-3">
                                Notre équipe est à votre disposition pour vous aider à choisir la chambre idéale.
                            </p>
                            <div class="d-grid">
                                <a href="https://wa.me/226XXXXXXXXX" target="_blank" class="btn btn-success">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    WhatsApp
                                </a>
                                <a href="tel:+226XXXXXXXXX" class="btn btn-outline-success mt-2">
                                    <i class="fas fa-phone me-2"></i>
                                    Appeler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.hero-section-reservation {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0;
}

.form-control, .form-select {
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #1A472A;
    box-shadow: 0 0 0 0.2rem rgba(26, 71, 42, 0.25);
}

.price-summary {
    border-left: 4px solid #1A472A;
}

.btn-success {
    background-color: #1A472A;
    border-color: #1A472A;
}

.btn-success:hover {
    background-color: #2E5C3F;
    border-color: #2E5C3F;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .hero-section-reservation {
        padding: 60px 0;
    }
    
    .hero-section-reservation h1 {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const priceSummary = document.getElementById('priceSummary');
    const nightsCount = document.getElementById('nightsCount');
    const totalPrice = document.getElementById('totalPrice');
    const checkPriceBtn = document.getElementById('checkPriceBtn');
    
    // Initialiser les dates par défaut
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    const dayAfter = new Date(Date.now() + 2 * 86400000).toISOString().split('T')[0];
    
    if (checkIn) {
        checkIn.min = today;
        checkIn.value = tomorrow;
    }
    if (checkOut) {
        checkOut.min = tomorrow;
        checkOut.value = dayAfter;
    }
    
    // Vérifier le prix estimé
    checkPriceBtn.addEventListener('click', function() {
        if (!checkIn.value || !checkOut.value) {
            alert('Veuillez sélectionner les dates de séjour');
            return;
        }
        
        const nights = Math.ceil((new Date(checkOut.value) - new Date(checkIn.value)) / (1000 * 60 * 60 * 24));
        
        if (nights < 1) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        // Prix estimé (sera remplacé par un appel API)
        const estimatedPricePerNight = 85000; // À remplacer par le prix réel de la chambre
        const estimatedTotal = nights * estimatedPricePerNight;
        
        nightsCount.textContent = nights;
        document.getElementById('pricePerNight').textContent = estimatedPricePerNight.toLocaleString('fr-FR') + ' FCFA';
        totalPrice.textContent = estimatedTotal.toLocaleString('fr-FR') + ' FCFA';
        priceSummary.style.display = 'block';
    });
    
    // Soumission du formulaire
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validation des dates
        if (!checkIn.value || !checkOut.value) {
            alert('Veuillez sélectionner les dates de séjour');
            return;
        }
        
        if (new Date(checkOut.value) <= new Date(checkIn.value)) {
            alert('La date de départ doit être après la date d\'arrivée');
            return;
        }
        
        // Récupérer les données
        const formData = new FormData(this);
        
        // Afficher un message de confirmation
        alert('Merci pour votre demande de réservation ! Nous vous contacterons dans les plus brefs délais pour confirmer la disponibilité.');
        
        // Réinitialiser le formulaire (optionnel)
        this.reset();
        
        // Ici, vous pouvez ajouter un appel AJAX pour envoyer la réservation
        // sans utiliser le système d'administration
    });
});
</script>
@endpush