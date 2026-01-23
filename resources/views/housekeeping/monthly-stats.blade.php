@extends('template.master')

@section('title', 'Statistiques Mensuelles - ' . $selectedMonth->format('F Y'))

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 fw-bold text-dark">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Statistiques Mensuelles
                    </h1>
                    <p class="text-muted mb-0">Performances et analyses pour {{ $selectedMonth->translatedFormat('F Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('housekeeping.reports') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                    <div class="input-group" style="width: 200px;">
                        <input type="month" class="form-control" id="monthSelector" 
                               value="{{ $selectedMonth->format('Y-m') }}">
                        <button class="btn btn-primary" type="button" onclick="changeMonth()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI mensuels -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Chambres nettoyées</h6>
                    <h1 class="fw-bold text-primary display-4">
                        {{ $monthlyStats->sum('cleaned_count') }}
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-bed me-1"></i>
                        Total mensuel
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Moyenne journalière</h6>
                    <h1 class="fw-bold text-info display-4">
                        {{ round($monthlyStats->avg('cleaned_count')) }}
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Par jour
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Temps moyen</h6>
                    <h1 class="fw-bold text-success display-4">
                        {{ round($monthlyStats->avg('avg_time')) }} min
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Par chambre
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Jours actifs</h6>
                    <h1 class="fw-bold text-warning display-4">
                        {{ $monthlyStats->count() }}
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-calendar-check me-1"></i>
                        Jours avec activité
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique d'évolution -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-chart-area me-2"></i>
                    <strong>Évolution quotidienne</strong>
                </div>
                <div class="card-body">
                    <canvas id="dailyEvolutionChart" height="300"></canvas>
                </div>
            </div>

            <!-- Top femmes de chambre -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-trophy me-2"></i>
                    <strong>Top 10 - Femmes de chambre</strong>
                </div>
                <div class="card-body">
                    @if($topCleaners->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Agent</th>
                                        <th>Chambres nettoyées</th>
                                        <th>Moyenne/jour</th>
                                        <th>Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCleaners as $index => $cleaner)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <span class="badge bg-warning">1</span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary">2</span>
                                            @elseif($index == 2)
                                                <span class="badge bg-danger">3</span>
                                            @else
                                                <span class="text-muted">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px;">
                                                    {{ substr($cleaner['name'], 0, 1) }}
                                                </div>
                                                <span>{{ $cleaner['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">{{ $cleaner['count'] }}</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ round($cleaner['count'] / $monthlyStats->count()) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = ($cleaner['count'] / $monthlyStats->sum('cleaned_count')) * 100;
                                                $width = min(100, $percentage * 2);
                                                $color = $percentage > 15 ? 'success' : ($percentage > 10 ? 'info' : 'warning');
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-{{ $color }}" 
                                                     style="width: {{ $width }}%">
                                                    {{ round($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques latérales -->
        <div class="col-md-4">
            <!-- Chambres les plus nettoyées -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-star me-2"></i>
                    <strong>Chambres les plus actives</strong>
                </div>
                <div class="card-body">
                    @if($mostCleanedRooms->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($mostCleanedRooms as $room)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <span class="badge bg-primary me-2">{{ $room->number }}</span>
                                    <small class="text-muted">{{ $room->type }}</small>
                                </div>
                                <span class="badge bg-info rounded-pill">{{ $room->cleaned_count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-bed fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Répartition par jour -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-chart-pie me-2"></i>
                    <strong>Activité par jour de semaine</strong>
                </div>
                <div class="card-body">
                    <canvas id="dayOfWeekChart" height="200"></canvas>
                </div>
            </div>

            <!-- Mois disponibles -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-history me-2"></i>
                    <strong>Historique des mois</strong>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($availableMonths as $month)
                        @php
                            $monthDate = Carbon\Carbon::createFromFormat('Y-m', $month);
                        @endphp
                        <a href="{{ route('housekeeping.monthly-stats', ['month' => $month]) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedMonth->format('Y-m') == $month ? 'active' : '' }}">
                            <div>
                                <i class="fas fa-calendar me-2"></i>
                                {{ $monthDate->translatedFormat('F Y') }}
                            </div>
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

    <!-- Résumé détaillé -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-file-alt me-2"></i>
                    <strong>Résumé mensuel détaillé</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>
                                Statistiques clés
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Total chambres nettoyées:</td>
                                    <td class="text-end fw-bold">{{ $monthlyStats->sum('cleaned_count') }}</td>
                                </tr>
                                <tr>
                                    <td>Moyenne par jour:</td>
                                    <td class="text-end">{{ round($monthlyStats->avg('cleaned_count'), 1) }}</td>
                                </tr>
                                <tr>
                                    <td>Meilleur jour:</td>
                                    <td class="text-end">
                                        @php
                                            $bestDay = $monthlyStats->sortByDesc('cleaned_count')->first();
                                        @endphp
                                        @if($bestDay)
                                            {{ Carbon\Carbon::parse($bestDay->date)->format('d/m') }} 
                                            ({{ $bestDay->cleaned_count }} chambres)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jours sans activité:</td>
                                    <td class="text-end">
                                        {{ $selectedMonth->daysInMonth - $monthlyStats->count() }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Performances
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Temps moyen de nettoyage:</td>
                                    <td class="text-end">{{ round($monthlyStats->avg('avg_time')) }} minutes</td>
                                </tr>
                                <tr>
                                    <td>Efficacité estimée:</td>
                                    <td class="text-end">
                                        @php
                                            $totalMinutes = $monthlyStats->sum(function($day) {
                                                return $day->cleaned_count * ($day->avg_time ?? 30);
                                            });
                                            $efficiency = ($totalMinutes / ($monthlyStats->count() * 8 * 60)) * 100;
                                        @endphp
                                        {{ round(min(100, $efficiency)) }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Taux d'occupation moyen:</td>
                                    <td class="text-end">85%</td>
                                </tr>
                                <tr>
                                    <td>Productivité relative:</td>
                                    <td class="text-end">
                                        @if($monthlyStats->avg('cleaned_count') > 15)
                                            <span class="badge bg-success">Élevée</span>
                                        @elseif($monthlyStats->avg('cleaned_count') > 10)
                                            <span class="badge bg-warning">Moyenne</span>
                                        @else
                                            <span class="badge bg-danger">Faible</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary" onclick="exportMonthlyReport()">
                            <i class="fas fa-file-excel me-2"></i>
                            Exporter le rapport mensuel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    const monthlyData = {!! json_encode($monthlyStats) !!};
    
    // Graphique d'évolution quotidienne
    const evolutionCtx = document.getElementById('dailyEvolutionChart').getContext('2d');
    
    const labels = monthlyData.map(day => {
        const date = new Date(day.date);
        return date.getDate() + '/' + (date.getMonth() + 1);
    });
    
    const cleanedData = monthlyData.map(day => day.cleaned_count);
    const avgTimeData = monthlyData.map(day => Math.round(day.avg_time) || 0);
    
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Chambres nettoyées',
                    data: cleanedData,
                    borderColor: '#4dc9f6',
                    backgroundColor: 'rgba(77, 201, 246, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Temps moyen (min)',
                    data: avgTimeData,
                    borderColor: '#f67019',
                    backgroundColor: 'rgba(246, 112, 25, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jours du mois'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de chambres'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Temps (minutes)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // Graphique par jour de semaine
    const dayOfWeekCtx = document.getElementById('dayOfWeekChart').getContext('2d');
    
    // Calculer les totaux par jour de semaine
    const dayTotals = {
        'Lundi': 0, 'Mardi': 0, 'Mercredi': 0, 'Jeudi': 0,
        'Vendredi': 0, 'Samedi': 0, 'Dimanche': 0
    };
    
    monthlyData.forEach(day => {
        const date = new Date(day.date);
        const dayName = date.toLocaleDateString('fr-FR', { weekday: 'long' });
        dayTotals[dayName] += day.cleaned_count;
    });
    
    new Chart(dayOfWeekCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(dayTotals),
            datasets: [{
                label: 'Chambres nettoyées',
                data: Object.values(dayTotals),
                backgroundColor: [
                    '#4dc9f6', '#f67019', '#f53794', '#537bc4',
                    '#acc236', '#166a8f', '#00a950'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de chambres'
                    }
                }
            }
        }
    });
});

// Changer le mois
function changeMonth() {
    const month = document.getElementById('monthSelector').value;
    if (month) {
        window.location.href = `{{ route('housekeeping.monthly-stats') }}?month=${month}`;
    }
}

// Exporter le rapport mensuel
function exportMonthlyReport() {
    // Simuler l'export (à implémenter avec un backend)
    const reportData = {
        month: "{{ $selectedMonth->format('F Y') }}",
        totalCleaned: {{ $monthlyStats->sum('cleaned_count') }},
        avgPerDay: {{ round($monthlyStats->avg('cleaned_count'), 1) }},
        avgTime: {{ round($monthlyStats->avg('avg_time')) }}
    };
    
    // Ouvrir une fenêtre d'impression avec un résumé
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Rapport Mensuel - ${reportData.month}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; border-bottom: 2px solid #4dc9f6; padding-bottom: 10px; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .summary { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                </style>
            </head>
            <body>
                <h1>Rapport Mensuel - ${reportData.month}</h1>
                <p>Généré le: ${new Date().toLocaleString()}</p>
                
                <div class="summary">
                    <h3>Résumé des performances</h3>
                    <ul>
                        <li>Total chambres nettoyées: ${reportData.totalCleaned}</li>
                        <li>Moyenne par jour: ${reportData.avgPerDay} chambres</li>
                        <li>Temps moyen de nettoyage: ${reportData.avgTime} minutes</li>
                    </ul>
                </div>
                
                <h3>Détails par jour</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Chambres nettoyées</th>
                            <th>Temps moyen</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generateDailyRows()}
                    </tbody>
                </table>
                
                <div style="margin-top: 30px; text-align: center; color: #666;">
                    <p>Rapport généré automatiquement par le système de gestion des femmes de chambre</p>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Générer les lignes du tableau
function generateDailyRows() {
    const monthlyData = {!! json_encode($monthlyStats) !!};
    let rows = '';
    
    monthlyData.forEach(day => {
        const date = new Date(day.date);
        rows += `
            <tr>
                <td>${date.toLocaleDateString('fr-FR')}</td>
                <td>${day.cleaned_count}</td>
                <td>${Math.round(day.avg_time) || 0} minutes</td>
            </tr>
        `;
    });
    
    return rows;
}
</script>
@endpush