@extends('template.master')

@section('title', 'Analytics Reports')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Analytics & Reports</h3>
            <small class="text-muted">Overview • Revenue • Occupancy • Trends</small>
        </div>

        <div>
            <button class="btn btn-hotel-primary">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">

        <!-- Revenue -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 48px; height: 48px;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h4 class="fw-bold">
                            ${{ number_format($monthlyRevenue->sum(), 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Occupancy -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 48px; height: 48px;">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Occupancy Rate</h6>
                        <h4 class="fw-bold">
                            @php
                                $totalRooms = $occupancy + $available;
                                $rate = $totalRooms > 0 ? round(($occupancy / $totalRooms) * 100) : 0;
                            @endphp
                            {{ $rate }}%
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 48px; height: 48px;">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Available Rooms</h6>
                        <h4 class="fw-bold">{{ $available }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="row g-4">

        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Monthly Revenue Overview</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="height: 260px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Occupancy Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0">Room Occupancy</h6>
                </div>
                <div class="card-body">
                    <canvas id="occupancyChart" style="height: 260px;"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data from controller
    const revenueData = {!! json_encode(array_values($monthlyRevenue->toArray())) !!};
    const revenueMonths = {!! json_encode(array_keys($monthlyRevenue->toArray())) !!};

    const occupied = {{ $occupancy }};
    const available = {{ $available }};

    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueMonths.map(m => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][m-1]),
            datasets: [{
                label: 'Revenue ($)',
                data: revenueData,
                borderWidth: 3,
                borderColor: '#0d6efd',
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Occupancy Pie Chart
    new Chart(document.getElementById('occupancyChart'), {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available'],
            datasets: [{
                data: [occupied, available],
                backgroundColor: ['#198754', '#6c757d']
            }]
        }
    });
</script>
@endsection
