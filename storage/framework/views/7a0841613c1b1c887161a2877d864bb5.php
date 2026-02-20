

<?php $__env->startSection('title', 'Scanner QR Code'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 fw-bold text-dark">
                                <i class="fas fa-qrcode me-2 text-primary"></i>
                                Scanner QR Code
                            </h1>
                            <p class="text-muted mb-0">Scanner le code QR d'une chambre pour la gérer</p>
                        </div>
                        <a href="<?php echo e(route('housekeeping.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Zone de scan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-camera me-2"></i>
                    <strong>Scanner une chambre</strong>
                </div>
                <div class="card-body text-center">
                    <div id="scan-area" class="mb-4">
                        <div class="border rounded p-4 mb-3" style="background: #f8f9fa;">
                            <div id="reader" style="width: 100%; min-height: 300px;"></div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Pointez la caméra vers le QR code de la chambre
                        </div>
                    </div>

                    <!-- Ou saisie manuelle -->
                    <div class="mb-4">
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-keyboard me-2"></i>
                            Saisie manuelle
                        </h6>
                        <form id="manualForm" action="<?php echo e(route('housekeeping.scan.process')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           name="room_number" 
                                           placeholder="Numéro de chambre (ex: 101)" 
                                           required
                                           autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-lg" name="action" required>
                                        <option value="">Action...</option>
                                        <option value="start-cleaning">Démarrer nettoyage</option>
                                        <option value="finish-cleaning">Nettoyage terminé</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-grid">
                        <button type="submit" form="manualForm" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>
                            Valider la saisie
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dernières actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Dernières actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-door-closed text-success me-2"></i>
                                <span>Chambre 101</span>
                            </div>
                            <span class="badge bg-success">Nettoyée à 09:30</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-door-closed text-warning me-2"></i>
                                <span>Chambre 205</span>
                            </div>
                            <span class="badge bg-warning">En nettoyage</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-door-closed text-danger me-2"></i>
                                <span>Chambre 308</span>
                            </div>
                            <span class="badge bg-danger">À nettoyer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    #reader {
        position: relative;
    }
    
    #reader video {
        width: 100%;
        border-radius: 8px;
    }
    
    #reader__scan_region {
        border: 2px dashed #6c757d !important;
        border-radius: 8px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Librairie HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le scanner
    const html5QrCode = new Html5Qrcode("reader");
    
    // Config du scanner
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 } 
    };
    
    // Démarrer le scanner
    html5QrCode.start(
        { facingMode: "environment" }, 
        config,
        onScanSuccess,
        onScanError
    ).catch(err => {
        console.error("Erreur démarrage scanner:", err);
        showCameraError();
    });
    
    // Fonction de succès du scan
    function onScanSuccess(decodedText, decodedResult) {
        // Arrêter le scanner
        html5QrCode.stop();
        
        // Extraire le numéro de chambre du QR code
        const roomNumber = extractRoomNumber(decodedText);
        
        if (roomNumber) {
            // Afficher une modale pour choisir l'action
            showActionModal(roomNumber, decodedText);
        } else {
            alert("QR code non reconnu. Format attendu: ROOM-XXX");
            // Redémarrer le scanner
            setTimeout(() => {
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config,
                    onScanSuccess,
                    onScanError
                );
            }, 1000);
        }
    }
    
    // Fonction d'erreur du scan
    function onScanError(error) {
        // Ignorer les erreurs courantes
        console.warn("Scan error:", error);
    }
    
    // Extraire le numéro de chambre
    function extractRoomNumber(text) {
        // Formats supportés: ROOM-101, CHAMBRE-101, 101, etc.
        const match = text.match(/ROOM-(\d+)|CHAMBRE-(\d+)|(\d{3})/i);
        if (match) {
            return match[1] || match[2] || match[3];
        }
        return null;
    }
    
    // Afficher la modale d'action
    function showActionModal(roomNumber, qrCode) {
        const modalHtml = `
            <div class="modal fade" id="actionModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-door-closed me-2"></i>
                                Chambre ${roomNumber}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                QR code scanné: <code>${qrCode}</code>
                            </div>
                            <form id="scanForm" method="POST" action="<?php echo e(route('housekeeping.scan.process')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="room_number" value="${roomNumber}">
                                <div class="mb-3">
                                    <label class="form-label">Action à effectuer :</label>
                                    <select class="form-select" name="action" required>
                                        <option value="">Choisir une action...</option>
                                        <option value="start-cleaning">Démarrer nettoyage</option>
                                        <option value="finish-cleaning">Nettoyage terminé</option>
                                        <option value="maintenance">Signaler maintenance</option>
                                    </select>
                                </div>
                                <div id="maintenanceFields" class="d-none">
                                    <div class="mb-3">
                                        <label class="form-label">Raison :</label>
                                        <input type="text" class="form-control" name="maintenance_reason" placeholder="Description du problème">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="resumeScan">
                                <i class="fas fa-redo me-2"></i>
                                Rescanner
                            </button>
                            <button type="submit" form="scanForm" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>
                                Valider
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Ajouter la modale au DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Afficher la modale
        const modal = new bootstrap.Modal(document.getElementById('actionModal'));
        modal.show();
        
        // Gérer l'affichage des champs maintenance
        const actionSelect = document.querySelector('#actionModal select[name="action"]');
        const maintenanceFields = document.getElementById('maintenanceFields');
        
        actionSelect.addEventListener('change', function() {
            if (this.value === 'maintenance') {
                maintenanceFields.classList.remove('d-none');
            } else {
                maintenanceFields.classList.add('d-none');
            }
        });
        
        // Redémarrer le scan après fermeture
        document.getElementById('actionModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
            setTimeout(() => {
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config,
                    onScanSuccess,
                    onScanError
                );
            }, 500);
        });
        
        // Bouton rescanner
        document.getElementById('resumeScan').addEventListener('click', function() {
            modal.hide();
        });
    }
    
    // Erreur caméra
    function showCameraError() {
        const scanArea = document.getElementById('scan-area');
        scanArea.innerHTML = `
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-video-slash fa-2x me-3"></i>
                    <div>
                        <h6 class="mb-1">Caméra non disponible</h6>
                        <p class="mb-0">Veuillez utiliser la saisie manuelle ou vérifier les permissions de la caméra.</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Nettoyer à la fermeture
    window.addEventListener('beforeunload', function() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/housekeeping/scan.blade.php ENDPATH**/ ?>