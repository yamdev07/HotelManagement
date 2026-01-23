@extends('template.master')

@section('title', 'Signalement Maintenance - Chambre ' . $room->number)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 fw-bold text-dark">
                                <i class="fas fa-tools me-2 text-warning"></i>
                                Signalement de maintenance
                            </h1>
                            <p class="text-muted mb-0">Chambre {{ $room->number }}</p>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Détails de la maintenance</strong>
                </div>
                <div class="card-body">
                    <!-- Infos chambre -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <i class="fas fa-door-closed fa-2x me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading">Chambre {{ $room->number }}</h6>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small class="d-block">
                                            <i class="fas fa-layer-group me-2"></i>
                                            Type: {{ $room->type->name ?? 'Standard' }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="d-block">
                                            <i class="fas fa-bed me-2"></i>
                                            Statut: 
                                            <span class="badge bg-{{ $room->room_status_id == \App\Models\Room::STATUS_MAINTENANCE ? 'warning' : 'info' }}">
                                                {{ $room->roomStatus->name ?? 'Inconnu' }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('housekeeping.mark-maintenance', $room->id) }}" method="POST" id="maintenanceForm">
                        @csrf
                        
                        <!-- Raison -->
                        <div class="mb-4">
                            <label for="maintenance_reason" class="form-label fw-bold">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                Type de problème
                            </label>
                            <select class="form-select form-select-lg" id="maintenance_reason" name="maintenance_reason" required>
                                <option value="">Sélectionner le type de problème...</option>
                                <optgroup label="Problèmes techniques">
                                    <option value="Électricité">Problème électrique</option>
                                    <option value="Plomberie">Fuite d'eau / Plomberie</option>
                                    <option value="Climatisation">Climatisation défectueuse</option>
                                    <option value="Chauffage">Problème de chauffage</option>
                                    <option value="Télévision">Télévision défectueuse</option>
                                    <option value="WiFi">Problème de connexion WiFi</option>
                                </optgroup>
                                <optgroup label="Équipements">
                                    <option value="Meuble">Meuble cassé</option>
                                    <option value="Literie">Literie endommagée</option>
                                    <option value="Salle de bain">Équipement salle de bain</option>
                                    <option value="Fenêtre">Fenêtre / Store</option>
                                    <option value="Serrure">Problème de serrure</option>
                                </optgroup>
                                <optgroup label="Nettoyage">
                                    <option value="Nettoyage profond">Nettoyage profond nécessaire</option>
                                    <option value="Désinfection">Désinfection requise</option>
                                    <option value="Odeur">Problème d'odeur</option>
                                </optgroup>
                                <option value="Autre">Autre problème</option>
                            </select>
                        </div>

                        <!-- Description détaillée -->
                        <div class="mb-4">
                            <label for="detailed_description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>
                                Description détaillée
                            </label>
                            <textarea class="form-control" id="detailed_description" 
                                      name="detailed_description" 
                                      rows="4" 
                                      placeholder="Décrivez le problème en détail..."></textarea>
                            <small class="text-muted">
                                Inclure toutes les informations utiles pour l'équipe de maintenance
                            </small>
                        </div>

                        <!-- Urgence -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-exclamation me-2 text-primary"></i>
                                Niveau d'urgence
                            </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check card border h-100">
                                        <input class="form-check-input" type="radio" name="urgency" id="urgency_low" value="low" checked>
                                        <label class="form-check-label card-body" for="urgency_low">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-flag text-success fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Basse</h6>
                                                    <small class="text-muted">Problème mineur</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check card border h-100">
                                        <input class="form-check-input" type="radio" name="urgency" id="urgency_medium" value="medium">
                                        <label class="form-check-label card-body" for="urgency_medium">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-flag text-warning fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Moyenne</h6>
                                                    <small class="text-muted">À traiter rapidement</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check card border h-100">
                                        <input class="form-check-input" type="radio" name="urgency" id="urgency_high" value="high">
                                        <label class="form-check-label card-body" for="urgency_high">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-flag text-danger fa-2x me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Haute</h6>
                                                    <small class="text-muted">Problème critique</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Durée estimée -->
                        <div class="mb-4">
                            <label for="estimated_duration" class="form-label fw-bold">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                Durée estimée de réparation
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg" 
                                       id="estimated_duration" name="estimated_duration" 
                                       min="1" max="168" value="2" required>
                                <span class="input-group-text">heures</span>
                            </div>
                            <small class="text-muted">
                                Estimation du temps nécessaire pour réparer le problème
                            </small>
                        </div>

                        <!-- Photos -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-camera me-2 text-primary"></i>
                                Photos du problème (optionnel)
                            </label>
                            <div class="border rounded p-4 text-center">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-3">
                                    Vous pouvez ajouter des photos pour illustrer le problème
                                </p>
                                <button type="button" class="btn btn-outline-primary" id="addPhotoBtn">
                                    <i class="fas fa-plus me-2"></i>
                                    Ajouter une photo
                                </button>
                                <input type="file" id="photoInput" multiple accept="image/*" class="d-none">
                            </div>
                            <div id="photoPreview" class="row mt-3 d-none">
                                <!-- Les aperçus de photos apparaîtront ici -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </button>
                        <button type="submit" form="maintenanceForm" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-2"></i>
                            Signaler la maintenance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Avertissement -->
            <div class="alert alert-warning mt-4">
                <div class="d-flex">
                    <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading">Important</h6>
                        <p class="mb-0">
                            Une fois signalée, la chambre sera marquée comme "En maintenance" et ne sera plus disponible 
                            pour la vente jusqu'à résolution du problème. L'équipe de maintenance sera automatiquement notifiée.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des photos
    const addPhotoBtn = document.getElementById('addPhotoBtn');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    
    if (addPhotoBtn) {
        addPhotoBtn.addEventListener('click', function() {
            photoInput.click();
        });
    }
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                photoPreview.classList.remove('d-none');
                photoPreview.innerHTML = '';
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 mb-3';
                            
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">${file.name}</small>
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1 w-100 remove-photo" data-index="${index}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            
                            photoPreview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }
    
    // Supprimer une photo
    photoPreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-photo')) {
            const button = e.target.closest('.remove-photo');
            const index = button.getAttribute('data-index');
            
            // Supprimer le fichier du input
            const dt = new DataTransfer();
            const files = Array.from(photoInput.files);
            files.splice(index, 1);
            
            files.forEach(file => dt.items.add(file));
            photoInput.files = dt.files;
            
            // Supprimer l'aperçu
            button.closest('.col-md-3').remove();
            
            // Masquer la prévisualisation si vide
            if (photoPreview.children.length === 0) {
                photoPreview.classList.add('d-none');
            }
        }
    });
    
    // Validation du formulaire
    const form = document.getElementById('maintenanceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const reason = document.getElementById('maintenance_reason').value;
            if (!reason) {
                e.preventDefault();
                alert('Veuillez sélectionner un type de problème');
                return false;
            }
            
            // Vous pouvez ajouter plus de validations ici
            return true;
        });
    }
});
</script>
@endpush