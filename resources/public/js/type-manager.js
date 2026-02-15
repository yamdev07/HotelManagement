// types-manager.js - Version SIMPLIFIÉE
document.addEventListener('DOMContentLoaded', function() {
    console.log('Types Manager loaded');
    
    // Gestionnaire pour le bouton "Add New Type"
    const addButton = document.getElementById('add-type-btn');
    if (addButton) {
        addButton.addEventListener('click', loadCreateForm);
    }
    
    // Gestionnaires pour les boutons d'édition
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const typeId = this.getAttribute('data-id');
            const typeName = this.getAttribute('data-name');
            loadEditForm(typeId, typeName);
        });
    });
    
    // Gestionnaires pour les boutons de suppression
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const typeId = this.getAttribute('data-id');
            const typeName = this.getAttribute('data-name');
            confirmDelete(typeId, typeName);
        });
    });
});

// ============================================
// FONCTIONS PRINCIPALES
// ============================================

function loadCreateForm() {
    console.log('Loading create form...');
    
    fetch('/types/create')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            // Nettoyer les modales existantes
            removeExistingModals();
            
            // Ajouter la nouvelle modale
            document.body.insertAdjacentHTML('beforeend', html);
            
            // Afficher la modale
            const modalElement = document.getElementById('create-type-modal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Gérer la soumission du formulaire
            setupCreateForm();
            
            // Nettoyer après fermeture
            modalElement.addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Error loading create form:', error);
            showAlert('Error loading form. Please try again.', 'danger');
        });
}

function loadEditForm(typeId, typeName) {
    console.log(`Loading edit form for type ${typeId}: ${typeName}`);
    
    fetch(`/types/${typeId}/edit`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            // Nettoyer les modales existantes
            removeExistingModals();
            
            // Ajouter la nouvelle modale
            document.body.insertAdjacentHTML('beforeend', html);
            
            // Afficher la modale
            const modalElement = document.getElementById('edit-type-modal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Gérer la soumission du formulaire
            setupEditForm(typeId);
            
            // Nettoyer après fermeture
            modalElement.addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Error loading edit form:', error);
            showAlert('Error loading edit form. Please try again.', 'danger');
        });
}

function confirmDelete(typeId, typeName) {
    if (!confirm(`Are you sure you want to delete "${typeName}"?\n\nThis action cannot be undone.`)) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/types/${typeId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Supprimer la ligne du tableau
            const row = document.getElementById(`type-row-${typeId}`);
            if (row) row.remove();
            // Si plus de types, afficher message
            checkEmptyTable();
        } else {
            showAlert(data.message || 'Error deleting type', 'danger');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showAlert('Error deleting type. Please try again.', 'danger');
    });
}

// ============================================
// FONCTIONS DE SOUTIEN
// ============================================

function setupCreateForm() {
    const form = document.getElementById('create-type-form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(this, 'POST');
    });
}

function setupEditForm(typeId) {
    const form = document.getElementById('edit-type-form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(this, 'PUT', typeId);
    });
}

function submitForm(form, method, typeId = null) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Désactiver le bouton
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    
    // Déterminer l'URL
    let url = form.action;
    if (method === 'PUT' && typeId) {
        url = `/types/${typeId}`;
    }
    
    fetch(url, {
        method: 'POST', // Laravel utilise POST avec _method pour PUT/DELETE
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Fermer la modale
            const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
            if (modal) modal.hide();
            // Recharger la page après 1 seconde
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert(data.message || 'Error saving type', 'danger');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        showAlert('Error saving. Please try again.', 'danger');
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

function removeExistingModals() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.remove();
    });
}

function checkEmptyTable() {
    const tableBody = document.querySelector('table tbody');
    const rows = tableBody.querySelectorAll('tr:not(.empty-row)');
    
    if (rows.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.className = 'empty-row';
        emptyRow.innerHTML = `
            <td colspan="6" class="text-center py-5">
                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                <h4>No Room Types Found</h4>
                <p class="text-muted">Create your first room type to get started</p>
                <button type="button" class="btn btn-primary" onclick="loadCreateForm()">
                    <i class="fas fa-plus me-2"></i>Add First Type
                </button>
            </td>
        `;
        tableBody.appendChild(emptyRow);
    }
}

function showAlert(message, type = 'info') {
    // Supprimer les alertes existantes
    document.querySelectorAll('.custom-alert').forEach(alert => alert.remove());
    
    const alertClass = {
        'success': 'alert-success',
        'danger': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const icon = {
        'success': 'fa-check-circle',
        'danger': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    }[type] || 'fa-info-circle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show custom-alert position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-hide après 5 secondes
    setTimeout(() => {
        if (alert.parentNode) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}