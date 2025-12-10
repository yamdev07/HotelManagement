@extends('frontend.layouts.master')

@section('title', 'Contact - Hôtel Cactus Palace')

@section('content')
    <!-- Hero Section Contact -->
    <section class="hero-section-contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">Contactez-nous</h1>
                    <p class="lead mb-4">Nous sommes là pour répondre à toutes vos questions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Informations de contact -->
    <section class="py-5" style="background-color: #F1F8E9;">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-top: 4px solid #4CAF50;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #4CAF50;">
                                <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Adresse</h4>
                            <p class="text-muted mb-0">
                                fidjrossé<br>
                                fidjossé, Cotonou, Bénin<br>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-top: 4px solid #4CAF50;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #4CAF50;">
                                <i class="fas fa-phone fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Téléphone</h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-phone me-2"></i>+229 01 90000000
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-fax me-2"></i>+33 019000000
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="background-color: white; border-top: 4px solid #4CAF50;">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px; background-color: #4CAF50;">
                                <i class="fas fa-envelope fa-2x text-white"></i>
                            </div>
                            <h4 style="color: #2E7D32;">Email</h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope me-2"></i>contact@cactushotel.com
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-2"></i>reservation@cactushotel.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="text-center mb-5">
                        <h2 style="color: #2E7D32;">Envoyez-nous un message</h2>
                        <p class="text-muted">Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais</p>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('frontend.contact.submit') }}" method="POST" id="contactForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                                <i class="fas fa-user me-1"></i>Nom complet *
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="name" 
                                                   name="name" 
                                                   required
                                                   placeholder="Votre nom et prénom"
                                                   style="border: 1px solid #C8E6C9;"
                                                   value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                                <i class="fas fa-envelope me-1"></i>Adresse email *
                                            </label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email" 
                                                   name="email" 
                                                   required
                                                   placeholder="votre.email@exemple.com"
                                                   style="border: 1px solid #C8E6C9;"
                                                   value="{{ old('email') }}">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                                <i class="fas fa-phone me-1"></i>Téléphone
                                            </label>
                                            <input type="tel" 
                                                   class="form-control" 
                                                   id="phone" 
                                                   name="phone"
                                                   placeholder="+33 1 23 45 67 89"
                                                   style="border: 1px solid #C8E6C9;"
                                                   value="{{ old('phone') }}">
                                            @error('phone')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                                <i class="fas fa-tag me-1"></i>Sujet *
                                            </label>
                                            <select class="form-select" 
                                                    id="subject" 
                                                    name="subject" 
                                                    required
                                                    style="border: 1px solid #C8E6C9;">
                                                <option value="" selected disabled>Sélectionnez un sujet</option>
                                                <option value="reservation" {{ old('subject') == 'reservation' ? 'selected' : '' }}>Réservation</option>
                                                <option value="information" {{ old('subject') == 'information' ? 'selected' : '' }}>Demande d'information</option>
                                                <option value="group" {{ old('subject') == 'group' ? 'selected' : '' }}>Événement de groupe</option>
                                                <option value="restaurant" {{ old('subject') == 'restaurant' ? 'selected' : '' }}>Réservation restaurant</option>
                                                <option value="spa" {{ old('subject') == 'spa' ? 'selected' : '' }}>Spa & Bien-être</option>
                                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('subject')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message" class="form-label" style="color: #2E7D32; font-weight: 500;">
                                                <i class="fas fa-comment me-1"></i>Message *
                                            </label>
                                            <textarea class="form-control" 
                                                      id="message" 
                                                      name="message" 
                                                      rows="6" 
                                                      required
                                                      placeholder="Votre message..."
                                                      style="border: 1px solid #C8E6C9;">{{ old('message') }}</textarea>
                                            @error('message')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="newsletter" 
                                                   name="newsletter"
                                                   style="border-color: #4CAF50;"
                                                   {{ old('newsletter') ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="newsletter">
                                                Je souhaite recevoir les offres spéciales et actualités du Luxury Palace
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 text-center">
                                        <button type="submit" 
                                                class="btn btn-lg"
                                                style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 12px 40px;">
                                            <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Carte Google Maps -->
    <section class="py-5" style="background-color: #E8F5E9;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">Notre emplacement</h2>
                <p class="text-muted">Venez nous rendre visite au cœur de Paris</p>
            </div>
            
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15862.376912314255!2d2.385758007812476!3d6.3572829999999945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1023540d7b99c067%3A0x2c6d1f2e5b0f6b4a!2sFidjross%C3%A9%2C%20Cotonou%2C%20B%C3%A9nin!5e0!3m2!1sfr!2sfr!4v1699791234567!5m2!1sfr!2sfr"
                            width="100%"
                            height="450"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Carte de Fidjrossé, Cotonou, Bénin">
                        </iframe>
                    </div>
                </div>
            </div>
            
            <!-- Informations d'accès -->
            <div class="row mt-4 g-4">
                <div class="col-md-4">
                    <div class="card border-0 h-100" style="background-color: transparent; border-left: 3px solid #4CAF50;">
                        <div class="card-body">
                            <h5 style="color: #2E7D32;">
                                <i class="fas fa-subway me-2"></i>Métro
                            </h5>
                            <ul class="list-unstyled text-muted">
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Charles de Gaulle - Étoile (lignes 1, 2, 6)</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>George V (ligne 1)</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Franklin D. Roosevelt (lignes 1, 9)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 h-100" style="background-color: transparent; border-left: 3px solid #4CAF50;">
                        <div class="card-body">
                            <h5 style="color: #2E7D32;">
                                <i class="fas fa-bus me-2"></i>Bus
                            </h5>
                            <ul class="list-unstyled text-muted">
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Lignes : 73, 83, 93</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Arrêt : Champs-Élysées - Clemenceau</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Noctilien : N11, N24</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 h-100" style="background-color: transparent; border-left: 3px solid #4CAF50;">
                        <div class="card-body">
                            <h5 style="color: #2E7D32;">
                                <i class="fas fa-car me-2"></i>Voiture
                            </h5>
                            <ul class="list-unstyled text-muted">
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Parking public à proximité</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Service valet disponible</li>
                                <li><i class="fas fa-circle fa-xs me-2" style="color: #4CAF50;"></i>Station de taxi devant l'hôtel</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2E7D32;">Questions fréquentes</h2>
                <p class="text-muted">Trouvez rapidement des réponses à vos questions</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="faqAccordion">
                        <!-- Question 1 -->
                        <div class="accordion-item mb-3 border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq1"
                                        style="background-color: #F1F8E9; color: #2E7D32; font-weight: 500;">
                                    <i class="fas fa-question-circle me-3" style="color: #4CAF50;"></i>
                                    Quels sont les horaires de check-in et check-out ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #F9FDF8;">
                                    <p>Le check-in est possible à partir de 15h00 et le check-out doit être effectué avant 12h00.</p>
                                    <p>Un check-in anticipé ou un check-out tardif peut être organisé sous réserve de disponibilité et peut engendrer des frais supplémentaires.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 2 -->
                        <div class="accordion-item mb-3 border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq2"
                                        style="background-color: #F1F8E9; color: #2E7D32; font-weight: 500;">
                                    <i class="fas fa-question-circle me-3" style="color: #4CAF50;"></i>
                                    L'hôtel accepte-t-il les animaux de compagnie ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #F9FDF8;">
                                    <p>Oui, le Luxury Palace accepte les animaux de compagnie (chiens et chats) jusqu'à 8kg.</p>
                                    <p>Des frais supplémentaires de 50€ par nuit sont appliqués. Un lit et des gamelles sont fournis sur demande.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 3 -->
                        <div class="accordion-item mb-3 border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq3"
                                        style="background-color: #F1F8E9; color: #2E7D32; font-weight: 500;">
                                    <i class="fas fa-question-circle me-3" style="color: #4CAF50;"></i>
                                    Proposez-vous des services de navette depuis l'aéroport ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #F9FDF8;">
                                    <p>Oui, nous proposons un service de navette privée depuis les aéroports de Paris (Charles de Gaulle, Orly et Le Bourget).</p>
                                    <p>Le service doit être réservé au minimum 48 heures à l'avance. Des véhicules de luxe (Berline, Van) sont disponibles.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Question 4 -->
                        <div class="accordion-item mb-3 border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq4"
                                        style="background-color: #F1F8E9; color: #2E7D32; font-weight: 500;">
                                    <i class="fas fa-question-circle me-3" style="color: #4CAF50;"></i>
                                    Quels sont les moyens de paiement acceptés ?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #F9FDF8;">
                                    <p>Nous acceptons les cartes de crédit suivantes : Visa, MasterCard, American Express.</p>
                                    <p>Les paiements en espèces (en euros) sont également acceptés, ainsi que les virements bancaires pour les réservations de groupes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted mb-3">Vous ne trouvez pas la réponse à votre question ?</p>
                        <a href="#contactForm" class="btn" style="color: #4CAF50; border-color: #4CAF50; background-color: transparent;">
                            <i class="fas fa-envelope me-2"></i>Contactez-nous directement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
.hero-section-contact {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 150px 0;
}

/* Style pour les formulaires */
.form-control:focus,
.form-select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
}

/* Style pour la carte */
.map-container iframe {
    border: none;
}

/* Style pour les accordéons */
.accordion-button:not(.collapsed) {
    background-color: #4CAF50 !important;
    color: white !important;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    border-color: #4CAF50;
}

/* Style pour les cartes de contact */
.card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Boutons avec effet hover */
.btn[style*="background-color: #4CAF50"]:hover {
    background-color: #388E3C !important;
    border-color: #388E3C !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.btn[style*="color: #4CAF50"]:hover {
    background-color: #4CAF50 !important;
    color: white !important;
    border-color: #4CAF50 !important;
}

/* Alertes */
.alert {
    border-radius: 10px;
    border: none;
}

.alert-success {
    background-color: #E8F5E9;
    color: #2E7D32;
    border-left: 4px solid #4CAF50;
}

.alert-danger {
    background-color: #FFEBEE;
    color: #C62828;
    border-left: 4px solid #F44336;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section-contact {
        padding: 100px 0;
    }
    
    .hero-section-contact h1 {
        font-size: 2.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Validation basique côté client
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            let isValid = true;
            
            // Réinitialiser les bordures
            [name, email, subject, message].forEach(input => {
                input.style.borderColor = '#C8E6C9';
            });
            
            // Validation des champs requis
            if (!name.value.trim()) {
                name.style.borderColor = '#F44336';
                isValid = false;
            }
            
            if (!email.value.trim() || !isValidEmail(email.value)) {
                email.style.borderColor = '#F44336';
                isValid = false;
            }
            
            if (!subject.value) {
                subject.style.borderColor = '#F44336';
                isValid = false;
            }
            
            if (!message.value.trim()) {
                message.style.borderColor = '#F44336';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir correctement tous les champs obligatoires (*)');
            }
        });
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Animation pour les cartes
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Auto-dismiss des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush