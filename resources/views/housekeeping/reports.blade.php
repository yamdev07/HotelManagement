@extends('template.master')

@section('title', 'Rapports de Nettoyage')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Rapports de Nettoyage
                    </h1>
                    <p class="text-muted mb-0">Analyses et statistiques détaillées</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <a href="{{ route('housekeeping.daily-report') }}" class="btn btn-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        Rapport Quotidien
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('housekeeping.reports') }}" method="GET" id="reportFilters">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="date" name="date" 
                                           value="{{ $selectedDate->format('Y-m-d') }}">
                                    <button class="btn btn-outline-secondary" type="button" id="todayBtn">
                                        Aujourd'hui
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="employee" class="form-label">Femme de chambre</label>
                                <select class="form-select" id="employee" name="employee">
                                    <option value="">Toutes</option>
                                    @foreach(\App\Models\User::role('housekeeping')->get() as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="room_type" class="form-label">Type de chambre</label>
                                <select class="form-select" id="room_type" name="room_type">
                                    <option value="">Tous</option>
                                    @foreach(\App\Models\RoomType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i>
                                    Filtrer
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-redo me-2"></i>
                                    Réinitialiser
                                </button>
                                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel me-2"></i>
                                    Exporter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Chambres nettoyées</h6>
                            <h2 class="fw-bold text-primary">{{ $stats['total_cleaned'] }}</h2>
                            <small class="text-muted">{{ $selectedDate->format('d/m/Y') }}</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bed fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Temps moyen</h6>
                            <h2 class="fw-bold text-info">{{ $stats['average_cleaning_time'] }} min</h2>
                            <small class="text-muted">Par chambre</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Disponibles</h6>
                            <h2 class="fw-bold text-success">{{ $stats['cleaned_by_status'][\App\Models\Room::STATUS_AVAILABLE] ?? 0 }}</h2>
                            <small class="text-muted">Nettoyées et disponibles</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Occupées</h6>
                            <h2 class="fw-bold text-warning">{{ $stats['cleaned_by_status'][\App\Models\Room::STATUS_OCCUPIED] ?? 0 }}</h2>
                            <small class="text-muted">Nettoyées et occupées</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Liste détaillée -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <strong>Détail des chambres nettoyées</strong>
                        </div>
                        <span class="badge bg-light text-primary">{{ $cleanedRooms->count() }} chambres</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($cleanedRooms->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="cleaningReportTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Chambre</th>
                                        <th>Type</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Durée</th>
                                        <th>Agent</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cleanedRooms as $room)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $room->number }}</span>
                                        </td>
                                        <td>{{ $room->type->name ?? 'Standard' }}</td>
                                        <td>
                                            @if($room->cleaning_started_at)
                                                {{ $room->cleaning_started_at->format('H:i') }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->cleaning_completed_at)
                                                {{ $room->cleaning_completed_at->format('H:i') }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->cleaning_started_at && $room->cleaning_completed_at)
                                                @php
                                                    $duration = $room->cleaning_started_at->diffInMinutes($room->cleaning_completed_at);
                                                    $color = $duration > 60 ? 'danger' : ($duration > 45 ? 'warning' : 'success');
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $duration }} min
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($room->cleaned_by)
                                                <span class="badge bg-info">
                                                    {{ \App\Models\User::find($room->cleaned_by)->name ?? 'Inconnu' }}
                                                </span>
                                            @else
                                                <span class="text-muted">Auto</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $room->room_status_id == \App\Models\Room::STATUS_AVAILABLE ? 'success' : ($room->room_status_id == \App\Models\Room::STATUS_OCCUPIED ? 'info' : 'warning') }}">
                                                {{ $room->roomStatus->name ?? 'Inconnu' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" 
                                                        onclick="showRoomDetails({{ $room->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('housekeeping.show-maintenance-form', $room->id) }}" 
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-tools"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-dark mb-2">Aucune donnée disponible</h5>
                            <p class="text-muted">Aucune chambre nettoyée à cette date</p>
                        </div>
                    @endif
                </div>
                @if($cleanedRooms->count() > 0)
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Durée de nettoyage: 
                                    <span class="text-success">≤45 min</span> | 
                                    <span class="text-warning">45-60 min</span> | 
                                    <span class="text-danger">≥60 min</span>
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Généré le {{ now()->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Graphiques et statistiques -->
        <div class="col-md-4">
            <!-- Répartition par agent -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-users me-2"></i>
                    <strong>Répartition par agent</strong>
                </div>
                <div class="card-body">
                    @if($cleanedByUser->count() > 0)
                        <canvas id="employeeChart" height="200"></canvas>
                        <div class="mt-3">
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($cleanedByUser as $name => $count)
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td class="text-end">{{ $count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dates disponibles -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-calendar me-2"></i>
                    <strong>Dates disponibles</strong>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($availableDates as $date)
                        <a href="{{ route('housekeeping.reports', ['date' => $date]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedDate->format('Y-m-d') == $date ? 'active' : '' }}">
                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            <span class="badge bg-primary rounded-pill">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapport mensuel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chart-line me-2"></i>
                    <strong>Tendances mensuelles</strong>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Rapports avancés</h6>
                                <p class="mb-0">
                                    Consultez les 
                                    <a href="{{ route('housekeeping.monthly-stats') }}" class="alert-link">
                                        statistiques mensuelles
                                    </a> 
                                    pour une analyse plus détaillée des performances.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('housekeeping.monthly-stats') }}" class="btn btn-success">
                            <i class="fas fa-chart-bar me-2"></i>
                            Voir les statistiques mensuelles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails chambre -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-door-closed me-2"></i>
                    Détails de la chambre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomDetailsContent">
                <!-- Les détails seront chargés ici -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le graphique des employés
    const employeeChart = document.getElementById('employeeChart');
    if (employeeChart) {
        const ctx = employeeChart.getContext('2d');
        
        // Données du graphique
        const labels = {!! json_encode($cleanedByUser->keys()) !!};
        const data = {!! json_encode($cleanedByUser->values()) !!};
        const backgroundColors = generateColors(data.length);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
    
    // Bouton "Aujourd'hui"
    document.getElementById('todayBtn').addEventListener('click', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;
        document.getElementById('reportFilters').submit();
    });
    
    // Tri du tableau
    $('#cleaningReportTable').DataTable({
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
    });
});

// Générer des couleurs pour le graphique
function generateColors(count) {
    const colors = [
        '#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236',
        '#166a8f', '#00a950', '#58595b', '#8549ba', '#ff6384'
    ];
    return colors.slice(0, count);
}

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('date').value = '';
    document.getElementById('employee').value = '';
    document.getElementById('room_type').value = '';
    document.getElementById('reportFilters').submit();
}

// Exporter vers Excel
function exportToExcel() {
    // Cette fonction nécessiterait une implémentation backend
    // Pour l'instant, on fait une alerte
    alert('L\'export Excel nécessite une implémentation backend. Pour l\'instant, utilisez l\'impression du navigateur.');
    
    // Alternative: ouvrir dans une nouvelle fenêtre pour impression
    const table = document.getElementById('cleaningReportTable').cloneNode(true);
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Export Excel</title></head><body>');
    printWindow.document.write('<h2>Rapport de nettoyage - {{ $selectedDate->format("d/m/Y") }}</h2>');
    printWindow.document.write(table.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

// Afficher les détails d'une chambre
function showRoomDetails(roomId) {
    // Simuler un chargement AJAX
    const content = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `;
    
    document.getElementById('roomDetailsContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
    modal.show();
    
    // Ici, vous devriez faire un appel AJAX pour récupérer les détails
    // Pour l'exemple, on simule un chargement
    setTimeout(() => {
        document.getElementById('roomDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations générales</h6>
                    <table class="table table-sm">
                        <tr><td>Chambre:</td><td><strong>101</strong></td></tr>
                        <tr><td>Type:</td><td>Standard</td></tr>
                        <tr><td>Étage:</td><td>1</td></tr>
                        <tr><td>Statut:</td><td><span class="badge bg-success">Disponible</span></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Dernier nettoyage</h6>
                    <table class="table table-sm">
                        <tr><td>Début:</td><td>09:15</td></tr>
                        <tr><td>Fin:</td><td>09:45</td></tr>
                        <tr><td>Durée:</td><td>30 minutes</td></tr>
                        <tr><td>Agent:</td><td>Marie Dupont</td></tr>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <h6>Historique récent</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Nettoyage complet</span>
                        <small class="text-muted">Aujourd'hui, 09:45</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Inspection</span>
                        <small class="text-muted">Hier, 16:30</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Nettoyage rapide</span>
                        <small class="text-muted">Hier, 11:20</small>
                    </li>
                </ul>
            </div>
        `;
    }, 500);
}
</script>
@endpush