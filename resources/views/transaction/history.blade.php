@extends('template.master')
@section('title', 'Historique Réservation')
@section('content')
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 50px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 13px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid;
            z-index: 1;
        }
        .timeline-item.status-changed::before { border-color: #6f42c1; }
        .timeline-item.payment-added::before { border-color: #198754; }
        .timeline-item.date-changed::before { border-color: #0dcaf0; }
        .timeline-item.note-added::before { border-color: #ffc107; }
        .timeline-item.cancelled::before { border-color: #dc3545; }
        .timeline-item.created::before { border-color: #0d6efd; }
        .timeline-item.marked-arrived::before { border-color: #20c997; }
        .timeline-item.marked-departed::before { border-color: #6c757d; }
        
        .timeline-content {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
        }
        .timeline-content.status-changed { border-left-color: #6f42c1; }
        .timeline-content.payment-added { border-left-color: #198754; }
        .timeline-content.date-changed { border-left-color: #0dcaf0; }
        .timeline-content.note-added { border-left-color: #ffc107; }
        .timeline-content.cancelled { border-left-color: #dc3545; }
        .timeline-content.created { border-left-color: #0d6efd; }
        .timeline-content.marked-arrived { border-left-color: #20c997; }
        .timeline-content.marked-departed { border-left-color: #6c757d; }
        
        .timeline-date {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .timeline-user {
            font-size: 0.9rem;
            color: #495057;
            margin-bottom: 8px;
        }
        .timeline-user i {
            width: 20px;
            text-align: center;
        }
        .timeline-title {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        .timeline-details {
            font-size: 0.95rem;
            color: #212529;
        }
        .badge-history {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .filter-buttons .btn {
            font-size: 0.85rem;
            padding: 5px 12px;
        }
        .history-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .history-summary h5 {
            color: white;
        }
        .diff-positive {
            color: #198754;
            font-weight: bold;
        }
        .diff-negative {
            color: #dc3545;
            font-weight: bold;
        }
        .diff-neutral {
            color: #6c757d;
            font-weight: bold;
        }
        .changes-list {
            list-style: none;
            padding-left: 0;
        }
        .changes-list li {
            padding: 3px 0;
            border-bottom: 1px dashed #e9ecef;
        }
        .changes-list li:last-child {
            border-bottom: none;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('transaction.index') }}">Réservations</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('transaction.show', $transaction) }}">Réservation #{{ $transaction->id }}</a>
                        </li>
                        <li class="breadcrumb-item active">Historique</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4 mb-0">
                            <i class="fas fa-history text-primary me-2"></i>
                            Historique de la Réservation #{{ $transaction->id }}
                        </h2>
                        <p class="text-muted">Journal complet des modifications et événements</p>
                    </div>
                    <a href="{{ route('transaction.show', $transaction) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux détails
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages de session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Résumé de la réservation -->
        <div class="history-summary">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="opacity-75">Client</small>
                        <h5 class="mb-0">{{ $transaction->customer->name }}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="opacity-75">Chambre</small>
                        <h5 class="mb-0">Chambre {{ $transaction->room->number }}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="opacity-75">Statut actuel</small>
                        <h5 class="mb-0">
                            <span class="badge bg-light text-dark px-3 py-2">
                                {{ $transaction->status_label }}
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="opacity-75">Créée le</small>
                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et statistiques -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-filter me-2"></i>Filtrer par type
                        </h5>
                        <div class="filter-buttons">
                            <button class="btn btn-sm btn-outline-primary active" data-filter="all">
                                Tous <span class="badge bg-primary">{{ count($histories ?? []) + 1 }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-purple" data-filter="status-changed">
                                <i class="fas fa-exchange-alt me-1"></i>Statut <span class="badge bg-purple">{{ $statusChangesCount ?? 0 }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-success" data-filter="payment-added">
                                <i class="fas fa-credit-card me-1"></i>Paiements <span class="badge bg-success">{{ $paymentCount ?? 0 }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-info" data-filter="date-changed">
                                <i class="fas fa-calendar-alt me-1"></i>Dates <span class="badge bg-info">{{ $dateChangesCount ?? 0 }}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-filter="note-added">
                                <i class="fas fa-sticky-note me-1"></i>Notes <span class="badge bg-warning text-dark">{{ $noteChangesCount ?? 0 }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $totalModifications ?? 1 }}</h3>
                                        <small class="text-muted">Modifications totales</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $uniqueUsers ?? 1 }}</h3>
                                        <small class="text-muted">Utilisateurs impliqués</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-stream me-2"></i>Chronologie des événements
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-1"></i> Exporter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i> CSV</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(empty($histories) && empty($payments))
                            <!-- Événement de création -->
                            <div class="timeline-item created">
                                <div class="timeline-content created">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="timeline-user">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $transaction->createdBy->name ?? 'Système' }}
                                        <span class="badge-history bg-primary">Création</span>
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas fa-plus-circle me-2"></i>Réservation créée
                                    </div>
                                    <div class="timeline-details">
                                        <p>Nouvelle réservation créée avec les paramètres suivants :</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="changes-list">
                                                    <li><strong>Client :</strong> {{ $transaction->customer->name }}</li>
                                                    <li><strong>Chambre :</strong> Chambre {{ $transaction->room->number }}</li>
                                                    <li><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y H:i') }}</li>
                                                    <li><strong>Départ :</strong> {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y H:i') }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="changes-list">
                                                    <li><strong>Statut initial :</strong> <span class="badge bg-secondary">{{ $transaction->status_label }}</span></li>
                                                    <li><strong>Nuits :</strong> {{ \Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out) }}</li>
                                                    <li><strong>Prix total :</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}</li>
                                                    @if($transaction->notes)
                                                        <li><strong>Note initiale :</strong> {{ $transaction->notes }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Si vous avez des données d'historique, vous pouvez les afficher ici -->
                            <!-- Événement de création -->
                            <div class="timeline-item created">
                                <div class="timeline-content created">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="timeline-user">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $transaction->createdBy->name ?? 'Système' }}
                                        <span class="badge-history bg-primary">Création</span>
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas fa-plus-circle me-2"></i>Réservation créée
                                    </div>
                                    <div class="timeline-details">
                                        <p>Nouvelle réservation créée avec les paramètres suivants :</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="changes-list">
                                                    <li><strong>Client :</strong> {{ $transaction->customer->name }}</li>
                                                    <li><strong>Chambre :</strong> Chambre {{ $transaction->room->number }}</li>
                                                    <li><strong>Arrivée :</strong> {{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y H:i') }}</li>
                                                    <li><strong>Départ :</strong> {{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y H:i') }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="changes-list">
                                                    <li><strong>Statut initial :</strong> <span class="badge bg-secondary">{{ $transaction->status_label }}</span></li>
                                                    <li><strong>Nuits :</strong> {{ \Carbon\Carbon::parse($transaction->check_in)->diffInDays($transaction->check_out) }}</li>
                                                    <li><strong>Prix total :</strong> {{ Helper::formatCFA($transaction->getTotalPrice()) }}</li>
                                                    @if($transaction->notes)
                                                        <li><strong>Note initiale :</strong> {{ $transaction->notes }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Exemple d'événements de modification de statut -->
                            @if($transaction->status == 'active' && $transaction->arrived_at)
                            <div class="timeline-item marked-arrived">
                                <div class="timeline-content marked-arrived">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->arrived_at)->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="timeline-user">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $transaction->arrivedBy->name ?? 'Système' }}
                                        <span class="badge-history bg-success">Arrivée</span>
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas fa-sign-in-alt me-2"></i>Client marqué comme arrivé
                                    </div>
                                    <div class="timeline-details">
                                        <p>Le client est arrivé à l'hôtel et a été enregistré.</p>
                                        <p><strong>Heure d'arrivée réelle :</strong> {{ \Carbon\Carbon::parse($transaction->arrived_at)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($transaction->status == 'completed' && $transaction->departed_at)
                            <div class="timeline-item marked-departed">
                                <div class="timeline-content marked-departed">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->departed_at)->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="timeline-user">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $transaction->departedBy->name ?? 'Système' }}
                                        <span class="badge-history bg-info">Départ</span>
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas fa-sign-out-alt me-2"></i>Client marqué comme parti
                                    </div>
                                    <div class="timeline-details">
                                        <p>Le client a quitté l'hôtel. Le séjour est terminé.</p>
                                        <p><strong>Heure de départ réelle :</strong> {{ \Carbon\Carbon::parse($transaction->departed_at)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($transaction->status == 'cancelled' && $transaction->cancelled_at)
                            <div class="timeline-item cancelled">
                                <div class="timeline-content cancelled">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($transaction->cancelled_at)->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="timeline-user">
                                        <i class="fas fa-user-circle me-1"></i>
                                        {{ $transaction->cancelledBy->name ?? 'Système' }}
                                        <span class="badge-history bg-danger">Annulation</span>
                                    </div>
                                    <div class="timeline-title">
                                        <i class="fas fa-ban me-2"></i>Réservation annulée
                                    </div>
                                    <div class="timeline-details">
                                        @if($transaction->cancel_reason)
                                            <p><strong>Raison de l'annulation :</strong> {{ $transaction->cancel_reason }}</p>
                                        @endif
                                        <p>La réservation a été annulée. Tous les paiements associés ont été remboursés.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Exemple d'événement de paiement -->
                            @if($transaction->payments && count($transaction->payments) > 0)
                                @foreach($transaction->payments as $payment)
                                <div class="timeline-item payment-added">
                                    <div class="timeline-content payment-added">
                                        <div class="timeline-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y à H:i') }}
                                        </div>
                                        <div class="timeline-user">
                                            <i class="fas fa-user-circle me-1"></i>
                                            {{ $payment->createdBy->name ?? 'Système' }}
                                            <span class="badge-history bg-success">Paiement #{{ $payment->id }}</span>
                                        </div>
                                        <div class="timeline-title">
                                            <i class="fas fa-credit-card me-2"></i>Paiement effectué
                                        </div>
                                        <div class="timeline-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Montant :</strong> {{ Helper::formatCFA($payment->amount) }}</p>
                                                    <p><strong>Méthode :</strong> {{ $payment->payment_method_label }}</p>
                                                    <p><strong>Référence :</strong> {{ $payment->reference ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Statut :</strong> 
                                                        <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $payment->status_label }}
                                                        </span>
                                                    </p>
                                                    @if($payment->notes)
                                                        <p><strong>Notes :</strong> {{ $payment->notes }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <!-- Message si pas d'historique détaillé -->
                            @if(empty($histories))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune modification supplémentaire n'a été enregistrée pour cette réservation.
                                L'historique complet inclut les événements automatiques (création, arrivée, départ, paiements).
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Export et actions -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>À propos de l'historique
                                </h6>
                                <small class="text-muted">
                                    Cet historique est généré automatiquement. Toutes les modifications sont enregistrées avec horodatage et auteur.
                                </small>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage des événements
    const filterButtons = document.querySelectorAll('.filter-buttons .btn');
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('outline');
            });
            this.classList.remove('outline');
            this.classList.add('active');
            
            // Filtrer les éléments
            timelineItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else if (item.classList.contains(filter)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Animation
            const visibleItems = document.querySelectorAll('.timeline-item[style="display: block"]');
            visibleItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate__animated', 'animate__fadeIn');
            });
        });
    });
    
    // Confirmation d'impression
    document.querySelector('button[onclick="window.print()"]').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Imprimer l\'historique ?',
            text: 'L\'historique complet sera imprimé au format paysage.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-print me-2"></i>Imprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ajouter un style pour l'impression
                const printStyle = document.createElement('style');
                printStyle.innerHTML = `
                    @media print {
                        .breadcrumb, .btn, .filter-buttons, .dropdown, .alert, .history-summary { display: none !important; }
                        .card { border: none !important; box-shadow: none !important; }
                        .card-header { background: white !important; border-bottom: 2px solid #000 !important; }
                        .container-fluid { padding: 0 !important; }
                        body { font-size: 12px !important; }
                        .timeline::before { left: 10px !important; }
                        .timeline-item { padding-left: 30px !important; }
                        .timeline-item::before { left: 4px !important; width: 12px !important; height: 12px !important; }
                    }
                `;
                document.head.appendChild(printStyle);
                
                window.print();
                
                // Retirer le style après impression
                setTimeout(() => {
                    document.head.removeChild(printStyle);
                }, 100);
            }
        });
    });
    
    // Initialiser l'animation
    timelineItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('animate__animated', 'animate__fadeIn');
    });
    
    // Calcul des statistiques pour les badges
    function updateBadgeCounts() {
        const statusChanged = document.querySelectorAll('.timeline-item.status-changed').length;
        const paymentAdded = document.querySelectorAll('.timeline-item.payment-added').length;
        const dateChanged = document.querySelectorAll('.timeline-item.date-changed').length;
        const noteAdded = document.querySelectorAll('.timeline-item.note-added').length;
        const total = document.querySelectorAll('.timeline-item').length;
        
        // Mettre à jour les badges si nécessaire
        document.querySelector('[data-filter="status-changed"] .badge').textContent = statusChanged;
        document.querySelector('[data-filter="payment-added"] .badge').textContent = paymentAdded;
        document.querySelector('[data-filter="date-changed"] .badge').textContent = dateChanged;
        document.querySelector('[data-filter="note-added"] .badge').textContent = noteAdded;
        document.querySelector('[data-filter="all"] .badge').textContent = total;
    }
    
    // Mettre à jour les compteurs au chargement
    updateBadgeCounts();
});
</script>
@endsection