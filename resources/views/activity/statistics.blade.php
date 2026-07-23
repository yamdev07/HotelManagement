@extends('template.master')
@section('title', 'activity.page_title_statistics')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-chart-bar me-2 text-info"></i>
                                {{ __('activity.header_stats_title') }}
                            </h4>
                            <p class="text-muted mb-0">{{ __('activity.header_stats_subtitle') }}</p>
                        </div>
                        <a href="{{ route('activity-log.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('activity.action_back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted mb-0">{{ __('activity.stats_total') }}</h5>
                            <h2 class="mt-2">{{ number_format($stats['total']) }}</h2>
                        </div>
                        <div class="avatar bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-history text-primary fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">{{ __('activity.stats_registered') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted mb-0">{{ __('activity.stats_today') }}</h5>
                            <h2 class="mt-2">{{ number_format($stats['today']) }}</h2>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-day text-success fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">{{ __('activity.stats_today_desc') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-start border-warning border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted mb-0">{{ __('activity.stats_this_week') }}</h5>
                            <h2 class="mt-2">{{ number_format($stats['this_week']) }}</h2>
                        </div>
                        <div class="avatar bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-week text-warning fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">{{ __('activity.stats_this_week_desc') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card border-start border-info border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted mb-0">{{ __('activity.stats_this_month') }}</h5>
                            <h2 class="mt-2">{{ number_format($stats['this_month']) }}</h2>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-calendar-alt text-info fa-2x"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">{{ __('activity.stats_this_month_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Par événement -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('activity.stats_by_event') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('activity.stats_event') }}</th>
                                    <th>{{ __('activity.stats_count') }}</th>
                                    <th>{{ __('activity.stats_percentage') }}</th>
                                    <th>{{ __('activity.stats_bar') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = $stats['total'];
                                @endphp
                                @foreach($stats['by_event'] as $event => $count)
                                    @php
                                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                        $color = match($event) {
                                            'created' => 'success',
                                            'updated' => 'warning',
                                            'deleted' => 'danger',
                                            'restored' => 'info',
                                            default => 'secondary'
                                        };
                                        $label = match($event) {
                                            'created' => __('activity.event_created'),
                                            'updated' => __('activity.event_updated'),
                                            'deleted' => __('activity.event_deleted'),
                                            'restored' => __('activity.event_restored'),
                                            default => ucfirst($event)
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td>{{ number_format($count) }}</td>
                                        <td>{{ $percentage }}%</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                                     style="width: {{ $percentage }}%;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 des utilisateurs -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('activity.stats_top_users') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('activity.stats_user') }}</th>
                                    <th>{{ __('activity.stats_actions') }}</th>
                                    <th>{{ __('activity.stats_percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['by_user'] as $userStat)
                                    @php
                                        $percentage = $total > 0 ? round(($userStat->count / $total) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($userStat->causer->name, 0, 1) }}
                                                </div>
                                                <span>{{ $userStat->causer->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ number_format($userStat->count) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <small class="text-muted">{{ $percentage }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités par modèle -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('activity.stats_by_model') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('activity.stats_model') }}</th>
                                    <th>{{ __('activity.stats_actions') }}</th>
                                    <th>{{ __('activity.stats_percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['by_model'] as $modelStat)
                                    @php
                                        $percentage = $total > 0 ? round(($modelStat->count / $total) * 100, 1) : 0;
                                        $modelName = class_basename($modelStat->subject_type);
                                    @endphp
                                    <tr>
                                        <td>
                                            <code>{{ $modelName }}</code>
                                        </td>
                                        <td>{{ number_format($modelStat->count) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-info" role="progressbar" 
                                                     style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <small class="text-muted">{{ $percentage }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques journalières -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('activity.stats_recent') }}</h5>
                        <span class="text-muted small">{{ __('activity.stats_last_7_days') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('activity.stats_day') }}</th>
                                    <th>{{ __('activity.stats_activities') }}</th>
                                    <th>{{ __('activity.stats_trend') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $days = [];
                                    for ($i = 6; $i >= 0; $i--) {
                                        $date = now()->subDays($i);
                                        $days[$date->format('Y-m-d')] = 0;
                                    }
                                    
                                    // Comptez les activités pour chaque jour
                                    $recentActivities = \Spatie\Activitylog\Models\Activity::whereDate('created_at', '>=', now()->subDays(6))
                                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                        ->groupBy('date')
                                        ->pluck('count', 'date');
                                    
                                    // Fusionnez avec les jours
                                    foreach ($recentActivities as $date => $count) {
                                        $days[$date] = $count;
                                    }
                                @endphp
                                
                                @foreach($days as $date => $count)
                                    @php
                                        $dayName = \Carbon\Carbon::parse($date)->translatedFormat('l');
                                        $formattedDate = \Carbon\Carbon::parse($date)->format('d/m');
                                        $isToday = $date == now()->format('Y-m-d');
                                    @endphp
                                    <tr class="{{ $isToday ? 'table-active' : '' }}">
                                        <td>
                                            {{ $dayName }}
                                            <small class="text-muted d-block">{{ $formattedDate }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $count }}</span>
                                        </td>
                                        <td>
                                            @if($count > 0)
                                                <div class="progress" style="height: 6px;">
                                                    @php
                                                        $maxCount = max($days);
                                                        $width = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                         style="width: {{ $width }}%;"></div>
                                                </div>
                                            @else
                                                <span class="text-muted small">{{ __('activity.stats_none') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ __('activity.stats_summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_daily_average') }}</span>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $total > 0 ? number_format($total / 30, 1) : 0 }}{{ __('activity.stats_per_day') }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_yesterday') }}</span>
                                    <span class="badge bg-secondary rounded-pill">{{ $stats['yesterday'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_active_users_30d') }}</span>
                                    <span class="badge bg-success rounded-pill">{{ $stats['by_user']->count() }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_tracked_models') }}</span>
                                    <span class="badge bg-info rounded-pill">{{ $stats['by_model']->count() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_creation_rate') }}</span>
                                    <span class="badge bg-warning rounded-pill">
                                        {{ $total > 0 ? number_format(($stats['by_event']['created'] ?? 0) / $total * 100, 1) : 0 }}%
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ __('activity.stats_update_rate') }}</span>
                                    <span class="badge bg-warning rounded-pill">
                                        {{ $total > 0 ? number_format(($stats['by_event']['updated'] ?? 0) / $total * 100, 1) : 0 }}%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('activity-log.export', 'csv') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i> {{ __('activity.stats_export') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
}
.card {
    border-radius: 10px;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.border-start {
    border-left-width: 4px !important;
}
.progress {
    border-radius: 3px;
}
.list-group-item {
    border: none;
    padding: 0.75rem 0;
}
</style>
@endpush

@push('scripts')
<script>
// Simple animation for counters
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('h2');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        if (!isNaN(target)) {
            animateCounter(counter, target);
        }
    });
});

function animateCounter(element, target) {
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current).toLocaleString();
        }
    }, 20);
}
</script>
@endpush
@endsection