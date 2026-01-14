@extends('template.master')
@section('title', 'Gestion des Paiements')
@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-2">Gestion des Paiements</h1>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            {{ $payments->total() }} paiement(s) enregistré(s)
                        </span>
                        @php
                            $totalAmount = $payments->sum('price');
                            $todayAmount = $payments->where('created_at', '>=', now()->startOfDay())->sum('price');
                        @endphp
                        <span class="badge bg-success">
                            <i class="fas fa-coins me-1"></i>
                            {{ Helper::formatCFA($todayAmount) }} aujourd'hui
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <!-- Menu déroulant Filtre -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?status=all">Tous les paiements</a></li>
                            <li><a class="dropdown-item" href="?status=completed">Complétés</a></li>
                            <li><a class="dropdown-item" href="?status=pending">En attente</a></li>
                            <li><a class="dropdown-item" href="?status=failed">Échoués</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?period=today">Aujourd'hui</a></li>
                            <li><a class="dropdown-item" href="?period=this_week">Cette semaine</a></li>
                            <li><a class="dropdown-item" href="?period=this_month">Ce mois</a></li>
                        </ul>
                    </div>
                    
                    <!-- Bouton Export -->
                    <button class="btn btn-outline-primary" onclick="exportPayments()">
                        <i class="fas fa-file-export me-2"></i>Exporter
                    </button>
                    
                    <!-- Actualiser -->
                    <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary-soft p-3 rounded-circle">
                            <i class="fas fa-money-bill-wave text-primary fa-lg"></i>
                        </div>
                        <span class="badge bg-primary">Total</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ Helper::formatCFA($totalAmount) }}</h2>
                    <p class="text-muted mb-0">Total des paiements</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-success-soft p-3 rounded-circle">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <span class="badge bg-success">Aujourd'hui</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ Helper::formatCFA($todayAmount) }}</h2>
                    <p class="text-muted mb-0">Reçu aujourd'hui</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-info-soft p-3 rounded-circle">
                            <i class="fas fa-users text-info fa-lg"></i>
                        </div>
                        <span class="badge bg-info">Clients</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $payments->unique('transaction.customer_id')->count() }}</h2>
                    <p class="text-muted mb-0">Paiements uniques</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-warning-soft p-3 rounded-circle">
                            <i class="fas fa-bed text-warning fa-lg"></i>
                        </div>
                        <span class="badge bg-warning">Chambres</span>
                    </div>
                    <h2 class="fw-bold display-6 text-dark mb-1">{{ $payments->unique('transaction.room_id')->count() }}</h2>
                    <p class="text-muted mb-0">Chambres payées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau principal -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Historique des Paiements
                    </h5>
                    <p class="text-muted mb-0">Tous les paiements enregistrés dans le système</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Rechercher des paiements..." 
                               id="searchInput" onkeyup="filterPayments()">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="paymentsTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-semibold text-dark" style="width: 60px;">ID</th>
                                <th class="py-3 fw-semibold text-dark">Client & Chambre</th>
                                <th class="py-3 fw-semibold text-dark">Détails du paiement</th>
                                <th class="py-3 fw-semibold text-dark">Date & Heure</th>
                                <th class="py-3 fw-semibold text-dark">Statut</th>
                                <th class="py-3 fw-semibold text-dark">Traité par</th>
                                <th class="pe-4 py-3 fw-semibold text-dark text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $statusClass = [
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        'refunded' => 'info',
                                        'cancelled' => 'secondary'
                                    ][$payment->status] ?? 'secondary';
                                    
                                    $isToday = $payment->created_at->isToday();
                                    $isRecent = $payment->created_at->diffInHours(now()) < 24;
                                @endphp
                                <tr class="hover-row {{ $isToday ? 'table-info' : '' }}">
                                    <!-- ID -->
                                    <td class="ps-4 py-3">
                                        <span class="badge bg-dark">#{{ $payment->id }}</span>
                                        @if($isRecent)
                                            <span class="badge bg-info ms-1">Nouveau</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Client & Chambre -->
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold text-dark">
                                                    {{ $payment->transaction->customer->name ?? 'N/A' }}
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-bed text-muted me-2 fa-sm"></i>
                                                    <span class="text-muted small">
                                                        Chambre {{ $payment->transaction->room->number ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Détails du paiement -->
                                    <td class="py-3">
                                        <div class="mb-1">
                                            <span class="fw-bold text-dark fs-5">{{ Helper::formatCFA($payment->price) }}</span>
                                        </div>
                                        @if($payment->payment_method)
                                            <small class="text-muted">
                                                <i class="fas fa-credit-card me-1"></i>
                                                {{ ucfirst($payment->payment_method) }}
                                            </small>
                                        @endif
                                        @if($payment->notes)
                                            <div class="mt-1">
                                                <small class="text-muted" title="{{ $payment->notes }}">
                                                    <i class="fas fa-sticky-note me-1"></i>
                                                    {{ Str::limit($payment->notes, 30) }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Date & Heure -->
                                    <td class="py-3">
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ Helper::dateFormat($payment->created_at) }}</span>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $payment->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    
                                    <!-- Statut -->
                                    <td class="py-3">
                                        <span class="badge bg-{{ $statusClass }} py-2 px-3">
                                            <i class="fas fa-circle me-1 fa-xs"></i>
                                            @php
                                                $statusTranslations = [
                                                    'completed' => 'Complété',
                                                    'pending' => 'En attente',
                                                    'failed' => 'Échoué',
                                                    'refunded' => 'Remboursé',
                                                    'cancelled' => 'Annulé'
                                                ];
                                            @endphp
                                            {{ $statusTranslations[$payment->status] ?? ucfirst($payment->status) }}
                                        </span>
                                        @if($payment->verified_at)
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="fas fa-check me-1"></i>
                                                    Vérifié
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Traité par -->
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <div class="bg-light rounded-circle p-1">
                                                    <i class="fas fa-user-tie text-secondary fa-sm"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $payment->user->name ?? 'Système' }}</div>
                                                <small class="text-muted">Personnel</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="pe-4 py-3 text-end">
                                        <div class="btn-group" role="group">
                                            <!-- Facture -->
                                            <a href="{{ route('payment.invoice', $payment->id) }}" 
                                               class="btn btn-outline-primary btn-sm px-3"
                                               data-bs-toggle="tooltip" 
                                               title="Voir la facture">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            
                                            <!-- Détails de la transaction -->
                                            <a href="{{ route('transaction.show', $payment->transaction_id) }}" 
                                               class="btn btn-outline-info btn-sm px-3"
                                               data-bs-toggle="tooltip" 
                                               title="Voir la transaction">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            
                                            <!-- Menu déroulant Plus d'actions -->
                                            @if(in_array($payment->status, ['pending', 'completed']))
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm px-3 dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @if($payment->status == 'pending')
                                                            <li>
                                                                <a class="dropdown-item text-success" href="#"
                                                                   onclick="markAsCompleted({{ $payment->id }})">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    Marquer comme complété
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                               onclick="showPaymentDetails({{ $payment->id }})">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                Voir les détails
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        @if($payment->status == 'completed')
                                                            <li>
                                                                <a class="dropdown-item text-warning" href="#"
                                                                   onclick="initiateRefund({{ $payment->id }})">
                                                                    <i class="fas fa-undo me-2"></i>
                                                                    Initier un remboursement
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($payment->status != 'cancelled' && $payment->status != 'refunded')
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                   onclick="cancelPayment({{ $payment->id }})">
                                                                    <i class="fas fa-times-circle me-2"></i>
                                                                    Annuler le paiement
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pied de tableau -->
                <div class="card-footer bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Affichage de {{ $payments->firstItem() }} à {{ $payments->lastItem() }} 
                                sur {{ $payments->total() }} paiements
                            </small>
                        </div>
                        <div class="col-md-6">
                            <!-- Pagination -->
                            @if($payments->hasPages())
                                <nav aria-label="Navigation des pages" class="float-end">
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- Lien Page Précédente -->
                                        @if($payments->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $payments->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        <!-- Éléments de Pagination -->
                                        @foreach(range(1, $payments->lastPage()) as $i)
                                            @if($i == $payments->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $payments->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        <!-- Lien Page Suivante -->
                                        @if($payments->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $payments->nextPageUrl() }}" rel="next">›</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">›</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- État vide -->
                <div class="text-center py-5 my-4">
                    <div class="empty-state">
                        <i class="fas fa-money-bill-wave fa-4x text-muted mb-4 opacity-25"></i>
                        <h3 class="text-dark mb-3">Aucun paiement trouvé</h3>
                        <p class="text-muted mb-4">
                            Aucun enregistrement de paiement trouvé dans la base de données.<br>
                            Les paiements apparaîtront ici lorsque les clients effectueront des paiements.
                        </p>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Aller au tableau de bord
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Info-bulles
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Mettre en évidence les paiements d'aujourd'hui
    highlightTodayPayments();
});

// Filtrer les paiements par recherche
function filterPayments() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('paymentsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

// Exporter les paiements
function exportPayments() {
    if (confirm('Exporter tous les paiements en CSV ?')) {
        window.location.href = '{{ route("transaction.export", "payments") }}';
    }
}

// Marquer le paiement comme complété
function markAsCompleted(paymentId) {
    if (confirm('Marquer ce paiement comme complété ?')) {
        fetch(`/payments/${paymentId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors du marquage du paiement comme complété');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}

// Afficher les détails du paiement
function showPaymentDetails(paymentId) {
    fetch(`/payments/${paymentId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Créer une modale avec les détails du paiement
                const modalHTML = `
                <div class="modal fade" id="paymentDetailsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Détails du paiement #${paymentId}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Informations de paiement</h6>
                                        <dl class="row">
                                            <dt class="col-sm-4">Montant:</dt>
                                            <dd class="col-sm-8">${data.payment.amount_formatted}</dd>
                                            
                                            <dt class="col-sm-4">Statut:</dt>
                                            <dd class="col-sm-8"><span class="badge bg-${data.payment.status_color}">${data.payment.status}</span></dd>
                                            
                                            <dt class="col-sm-4">Méthode:</dt>
                                            <dd class="col-sm-8">${data.payment.method || 'N/A'}</dd>
                                            
                                            <dt class="col-sm-4">Référence:</dt>
                                            <dd class="col-sm-8">${data.payment.reference || 'N/A'}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Détails de la transaction</h6>
                                        <dl class="row">
                                            <dt class="col-sm-4">Client:</dt>
                                            <dd class="col-sm-8">${data.payment.guest_name}</dd>
                                            
                                            <dt class="col-sm-4">Chambre:</dt>
                                            <dd class="col-sm-8">${data.payment.room_number}</dd>
                                            
                                            <dt class="col-sm-4">Date:</dt>
                                            <dd class="col-sm-8">${data.payment.date_formatted}</dd>
                                            
                                            <dt class="col-sm-4">Traité par:</dt>
                                            <dd class="col-sm-8">${data.payment.processed_by}</dd>
                                        </dl>
                                    </div>
                                </div>
                                ${data.payment.notes ? `
                                <div class="mt-3">
                                    <h6>Notes</h6>
                                    <div class="bg-light p-3 rounded">${data.payment.notes}</div>
                                </div>
                                ` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <a href="/payment/${paymentId}/invoice" class="btn btn-primary">
                                    <i class="fas fa-file-invoice me-2"></i>Télécharger la facture
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
                modal.show();
                
                // Nettoyer après que la modale soit masquée
                document.getElementById('paymentDetailsModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            } else {
                alert('Erreur lors du chargement des détails du paiement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails du paiement');
        });
}

// Mettre en évidence les paiements d'aujourd'hui
function highlightTodayPayments() {
    const today = new Date().toISOString().split('T')[0];
    const rows = document.querySelectorAll('#paymentsTable tbody tr');
    
    rows.forEach(row => {
        const dateCell = row.querySelector('td:nth-child(4)');
        if (dateCell) {
            const dateText = dateCell.textContent;
            if (dateText.includes('Aujourd\'hui') || dateText.includes('Today')) {
                row.classList.add('table-info');
            }
        }
    });
}

// Annuler le paiement
function cancelPayment(paymentId) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce paiement ?\n\nCette action ne peut pas être annulée.')) {
        fetch(`/payments/${paymentId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors de l\'annulation du paiement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}

// Initier un remboursement
function initiateRefund(paymentId) {
    const amount = prompt('Entrez le montant du remboursement (FCFA):', '');
    if (amount && !isNaN(amount) && amount > 0) {
        fetch(`/payments/${paymentId}/refund`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Erreur lors du traitement du remboursement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur réseau');
        });
    }
}
</script>

<style>
/* Système de design */
:root {
    --primary: #3b82f6;
    --primary-soft: #dbeafe;
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --danger: #ef4444;
    --danger-soft: #fee2e2;
    --info: #06b6d4;
    --info-soft: #cffafe;
}

/* Cartes */
.card {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

/* Tableau */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    color: #4b5563;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table td, .table th {
    vertical-align: middle;
}

/* Ligne survolée */
.hover-row:hover {
    background-color: #f8fafc !important;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    border-radius: 6px;
}

/* Boutons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
}

.btn-outline-primary:hover {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-outline-info:hover {
    background-color: var(--info);
    border-color: var(--info);
    color: white;
}

/* Avatar */
.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Pagination */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #e5e7eb;
}

.page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
}

/* Couleurs de statut */
.badge.bg-success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.badge.bg-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.badge.bg-info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

/* Utilitaires de couleur */
.bg-primary-soft { background-color: var(--primary-soft); }
.bg-success-soft { background-color: var(--success-soft); }
.bg-warning-soft { background-color: var(--warning-soft); }
.bg-info-soft { background-color: var(--info-soft); }

/* État vide */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state i {
    opacity: 0.3;
}

/* Ligne d'information du tableau */
.table-info {
    background-color: #f0f9ff !important;
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .btn-group {
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection