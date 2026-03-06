@extends('template.master')

@section('title', 'Rapports & Analytics')

@section('content')
<div class="container-fluid py-4">

    <!-- ================================================ -->
    <!-- PAGE HEADER -->
    <!-- ================================================ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">📊 Rapports & Analytics</h3>
            <small class="text-muted">Performance • Finances • Opérations • Tendances</small>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Imprimer
            </button>
            <button class="btn btn-hotel-primary" id="exportPdf">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- ================================================ -->
    <!-- FILTERS & DATE RANGE -->
    <!-- ================================================ -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Période</label>
                    <select name="period" class="form-select" id="periodSelect">
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Hier</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="quarter" {{ request('period') == 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Cette année</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                    </select>
                </div>
                <div class="col-md-3" id="dateRangeStart" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3" id="dateRangeEnd" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-hotel-primary w-100">
                        <i class="fas fa-filter me-2"></i>Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================================================ -->
    <!-- KPI CARDS - ROW 1 (FINANCIAL) -->
    <!-- ================================================ -->
    <div class="row g-3 mb-4">

        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Chiffre d'affaires</h6>
                        <h4 class="fw-bold">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} CFA</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payments -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-credit-card fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Paiements encaissés</h6>
                        <h4 class="fw-bold">{{ number_format($totalPaymentsAmount ?? 0, 0, ',', ' ') }} CFA</h4>
                        <small class="text-muted">{{ $paymentsCount ?? 0 }} transactions</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Night Rate -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-moon fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Prix moyen / nuit</h6>
                        <h4 class="fw-bold">{{ number_format($averageNightRate ?? 0, 0, ',', ' ') }} CFA</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- RevPAR -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">RevPAR</h6>
                        <h4 class="fw-bold">{{ number_format($revPAR ?? 0, 0, ',', ' ') }} CFA</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- KPI CARDS - ROW 2 (OPERATIONAL) -->
    <!-- ================================================ -->
    <div class="row g-3 mb-4">

        <!-- Occupancy Rate -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-bed fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Taux d'occupation</h6>
                        <h4 class="fw-bold">{{ $occupancyRate ?? 0 }}%</h4>
                        <small class="text-muted">{{ $occupiedRooms ?? 0 }}/{{ $totalRooms ?? 0 }} chambres</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-ins / Check-outs -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-exchange-alt fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Arrivées / Départs</h6>
                        <h4 class="fw-bold">{{ $checkinsCount ?? 0 }} / {{ $checkoutsCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Nights -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-purple text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-sun fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Nuitées vendues</h6>
                        <h4 class="fw-bold">{{ $totalNights ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Stay -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Séjour moyen</h6>
                        <h4 class="fw-bold">{{ $averageStayLength ?? 0 }} nuits</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- CHARTS SECTION - ROW 1 -->
    <!-- ================================================ -->
    <div class="row g-4 mb-4">

        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">📈 Évolution du chiffre d'affaires</h6>
                    <span class="badge bg-primary">{{ $periodLabel ?? 'Période sélectionnée' }}</span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">💳 Répartition des paiements</h6>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" style="height: 250px;"></canvas>
                    <div class="mt-3 small">
                        @foreach($paymentSummary ?? [] as $method)
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="{{ $method['icon'] }} me-1"></i>{{ $method['label'] }}</span>
                            <span class="fw-bold">{{ number_format($method['percentage'] ?? 0, 1) }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- CHARTS SECTION - ROW 2 -->
    <!-- ================================================ -->
    <div class="row g-4 mb-4">

        <!-- Occupancy Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">🏨 Occupation des chambres</h6>
                </div>
                <div class="card-body">
                    <canvas id="occupancyChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Room Status Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">🧹 Statut des chambres</h6>
                </div>
                <div class="card-body">
                    <canvas id="roomStatusChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Checkout Times Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">⏰ Horaires de départ</h6>
                </div>
                <div class="card-body">
                    <canvas id="checkoutTimesChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- TABLES SECTION - TOP PERFORMERS -->
    <!-- ================================================ -->
    <div class="row g-4">

        <!-- Top Rooms -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">🏆 Top 5 chambres les plus rentables</h6>
                    <a href="{{ route('room.index') }}" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Chambre</th>
                                    <th>Type</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Revenu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRooms ?? [] as $room)
                                <tr>
                                    <td>
                                        <strong>{{ $room['number'] ?? 'N/A' }}</strong>
                                        @if(!empty($room['name']))
                                        <small class="d-block text-muted">{{ $room['name'] }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $room['type'] ?? 'N/A' }}</td>
                                    <td>{{ $room['nights'] ?? 0 }}</td>
                                    <td class="text-end fw-bold">{{ number_format($room['revenue'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">👥 Top 5 clients</h6>
                    <a href="{{ route('customer.index') }}" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Séjours</th>
                                    <th>Nuitées</th>
                                    <th class="text-end">Dépenses totales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer['name'] ?? 'N/A' }}</strong>
                                        @if(!empty($customer['email']))
                                        <small class="d-block text-muted">{{ $customer['email'] }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $customer['stays'] ?? 0 }}</td>
                                    <td>{{ $customer['nights'] ?? 0 }}</td>
                                    <td class="text-end fw-bold">{{ number_format($customer['spent'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- RECEPTIONIST PERFORMANCE TABLE -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">👤 Performance des réceptionnistes</h6>
                    <a href="{{ route('receptionist.stats') }}" class="text-decoration-none small">Détails</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Réceptionniste</th>
                                    <th>Sessions</th>
                                    <th>Check-ins</th>
                                    <th>Check-outs</th>
                                    <th>Réservations</th>
                                    <th class="text-end">Encaissé</th>
                                    <th class="text-center">Productivité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receptionistPerformance ?? [] as $recep)
                                <tr>
                                    <td><strong>{{ $recep['name'] ?? 'N/A' }}</strong></td>
                                    <td>{{ $recep['sessions'] ?? 0 }}</td>
                                    <td>{{ $recep['checkins'] ?? 0 }}</td>
                                    <td>{{ $recep['checkouts'] ?? 0 }}</td>
                                    <td>{{ $recep['reservations'] ?? 0 }}</td>
                                    <td class="text-end fw-bold">{{ number_format($recep['total'] ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ $recep['productivity'] ?? 0 }}%;" 
                                                aria-valuenow="{{ $recep['productivity'] ?? 0 }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $recep['productivity'] ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune donnée disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- PAYMENT SUMMARY TABLE -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">💰 Synthèse détaillée des paiements</h6>
                    <a href="{{ route('payment.index') }}" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Méthode</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">%</th>
                                    <th class="text-end">Transactions</th>
                                    <th class="text-end">Moyenne par transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentSummary ?? [] as $method)
                                <tr>
                                    <td>
                                        <i class="{{ $method['icon'] ?? 'fas fa-circle' }} me-2"></i>{{ $method['label'] ?? 'N/A' }}
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format($method['amount'] ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-end">{{ number_format($method['percentage'] ?? 0, 1) }}%</td>
                                    <td class="text-end">{{ $method['count'] ?? 0 }}</td>
                                    <td class="text-end">{{ number_format($method['average'] ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-end">{{ number_format($totalPaymentsAmount ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td class="text-end">100%</td>
                                    <td class="text-end">{{ $paymentsCount ?? 0 }}</td>
                                    <td class="text-end">{{ number_format($averagePayment ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================ -->
    <!-- RECENT TRANSACTIONS -->
    <!-- ================================================ -->
    <div class="row g-4 mt-2">

        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">🔄 Dernières transactions</h6>
                    <a href="{{ route('transaction.index') }}" class="text-decoration-none small">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Chambre</th>
                                    <th>Arrivée</th>
                                    <th>Départ</th>
                                    <th class="text-end">Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $transaction)
                                <tr>
                                    <td>#{{ $transaction->id }}</td>
                                    <td>{{ $transaction->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->room->number ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</td>
                                    <td class="text-end fw-bold">{{ number_format($transaction->total_price ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'reservation' => 'info',
                                                'active' => 'success',
                                                'completed' => 'secondary',
                                                'cancelled' => 'danger',
                                                'no_show' => 'warning'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$transaction->status] ?? 'secondary' }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Aucune transaction récente
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    // ================================================
    // DATE RANGE TOGGLE
    // ================================================
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelect = document.getElementById('periodSelect');
        if (periodSelect) {
            periodSelect.addEventListener('change', function() {
                const isCustom = this.value === 'custom';
                document.getElementById('dateRangeStart').style.display = isCustom ? 'block' : 'none';
                document.getElementById('dateRangeEnd').style.display = isCustom ? 'block' : 'none';
            });
        }
    });

    // ================================================
    // CHART DATA FROM CONTROLLER
    // ================================================
    const revenueLabels = @json($revenueChartLabels ?? []);
    const revenueData = @json($revenueChartData ?? []);
    
    const paymentLabels = @json($paymentChartLabels ?? []);
    const paymentData = @json($paymentChartData ?? []);
    
    const occupied = {{ $occupiedRooms ?? 0 }};
    const available = {{ $availableRooms ?? 0 }};
    
    const roomStatusLabels = @json($roomStatusLabels ?? []);
    const roomStatusData = @json($roomStatusData ?? []);
    
    const checkoutTimesData = @json($checkoutTimesData ?? [0, 0, 0]);

    // ================================================
    // CHART 1: REVENUE LINE CHART
    // ================================================
    if (document.getElementById('revenueChart')) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Si pas de données, afficher un message
        if (!revenueData || revenueData.length === 0 || revenueData.every(v => v === 0)) {
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée disponible pour cette période', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Chiffre d\'affaires (CFA)',
                        data: revenueData,
                        borderWidth: 3,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return (context.raw || 0).toLocaleString() + ' CFA';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' CFA';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // ================================================
    // CHART 2: PAYMENT METHODS DOUGHNUT
    // ================================================
    if (document.getElementById('paymentChart')) {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        
        if (!paymentData || paymentData.length === 0 || paymentData.every(v => v === 0)) {
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.textAlign = 'center';
            ctx.fillText('Aucun paiement pour cette période', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentData,
                        backgroundColor: [
                            '#28a745', // cash - green
                            '#dc3545', // card - red
                            '#17a2b8', // mobile - cyan
                            '#ffc107', // transfer - yellow
                            '#6f42c1', // fedapay - purple
                            '#fd7e14', // check - orange
                            '#6c757d'  // other - gray
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value.toLocaleString()} CFA (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // ================================================
    // CHART 3: OCCUPANCY PIE CHART
    // ================================================
    if (document.getElementById('occupancyChart')) {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        const total = occupied + available;
        
        if (total === 0) {
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune chambre disponible', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Occupées', 'Disponibles'],
                    datasets: [{
                        data: [occupied, available],
                        backgroundColor: ['#dc3545', '#28a745'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = occupied + available;
                                    let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} chambres (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // ================================================
    // CHART 4: ROOM STATUS CHART
    // ================================================
    if (document.getElementById('roomStatusChart')) {
        const ctx = document.getElementById('roomStatusChart').getContext('2d');
        
        if (!roomStatusData || roomStatusData.length === 0 || roomStatusData.every(v => v === 0)) {
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée de statut', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: roomStatusLabels,
                    datasets: [{
                        label: 'Nombre de chambres',
                        data: roomStatusData,
                        backgroundColor: [
                            '#28a745', // disponible - green
                            '#dc3545', // occupée - red
                            '#ffc107', // maintenance - yellow
                            '#17a2b8', // réservée - cyan
                            '#6f42c1', // nettoyage - purple
                            '#6c757d'  // sale - gray
                        ],
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    }

    // ================================================
    // CHART 5: CHECKOUT TIMES CHART
    // ================================================
    if (document.getElementById('checkoutTimesChart')) {
        const ctx = document.getElementById('checkoutTimesChart').getContext('2d');
        
        if (!checkoutTimesData || checkoutTimesData.every(v => v === 0)) {
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.textAlign = 'center';
            ctx.fillText('Aucun départ enregistré', ctx.canvas.width/2, ctx.canvas.height/2);
        } else {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Avant 12h', '12h-14h (largesse)', 'Après 14h (late checkout)'],
                    datasets: [{
                        data: checkoutTimesData,
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    }

    // ================================================
    // EXPORT PDF FUNCTION
    // ================================================
    document.getElementById('exportPdf')?.addEventListener('click', function() {
        const element = document.querySelector('.container-fluid');
        const opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: 'rapport-hotel-{{ now()->format('Y-m-d') }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, letterRendering: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });

</script>

<style>
    .bg-purple { background-color: #6f42c1; }
    .badge { font-size: 0.8rem; padding: 0.35rem 0.65rem; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-2px); }
    .table th { font-weight: 600; color: #495057; }
    .btn-hotel-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    .btn-hotel-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        border-radius: 10px;
        font-size: 0.75rem;
        line-height: 20px;
    }
</style>
@endsection