@extends('template.master')
@section('title', __("activity.page_title_activity_details", ["id" => $activity->id]))
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  #f0faf0;
    --green-100: #d4edda;
    --green-500: #2e8540;
    --green-600: #1e6b2e;
    --green-700: #155221;

    --red-50:    #fee2e2;
    --red-100:   #fecaca;
    --red-500:   #b91c1c;
    --red-600:   #991b1b;

    --gray-50:   #f8f9f8;
    --gray-100:  #eff0ef;
    --gray-200:  #dde0dd;
    --gray-300:  #c2c7c2;
    --gray-400:  #9ba09b;
    --gray-500:  #737873;
    --gray-600:  #545954;
    --gray-700:  #3a3e3a;
    --gray-800:  #252825;
    --gray-900:  #131513;

    --white:     #ffffff;
    --surface:   #f7f9f7;

    --shadow-xs: 0 1px 2px rgba(0,0,0,.04);
    --shadow-sm: 0 1px 6px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 2px 4px rgba(0,0,0,.04);

    --r:   8px;
    --rl:  14px;
    --rxl: 20px;
    --transition: all .2s ease;
    --font: 'DM Sans', system-ui, sans-serif;
    --mono: 'DM Mono', monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

.act-detail-page {
    background: var(--surface);
    min-height: 100vh;
    padding: 24px 32px;
    font-family: var(--font);
    color: var(--gray-800);
}

/* ── Animations ── */
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlide .4s ease both; }
.anim-2 { animation: fadeSlide .4s .08s ease both; }

/* ══════════════════════════════════════════════
   BREADCRUMB
══════════════════════════════════════════════ */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .8rem;
    color: var(--gray-400);
    margin-bottom: 20px;
}
.breadcrumb a {
    color: var(--gray-400);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb a:hover {
    color: var(--green-600);
}
.breadcrumb .sep {
    color: var(--gray-300);
}
.breadcrumb .current {
    color: var(--gray-600);
    font-weight: 500;
}

/* ══════════════════════════════════════════════
   HEADER
══════════════════════════════════════════════ */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}
.header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.header-icon {
    width: 48px;
    height: 48px;
    background: var(--green-600);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.3);
}
.header-title h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}
.header-title em {
    font-style: normal;
    color: var(--green-600);
}
.header-subtitle {
    color: var(--gray-500);
    font-size: .8rem;
    margin: 6px 0 0 60px;
}

/* ══════════════════════════════════════════════
   BUTTONS
══════════════════════════════════════════════ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--r);
    font-size: .8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}
.btn-gray {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-gray:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
    transform: translateY(-1px);
}
.btn-sm {
    padding: 6px 14px;
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   CARDS
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-xs);
    transition: var(--transition);
}
.card:hover {
    border-color: var(--green-300);
    box-shadow: var(--shadow-md);
}
.card-header {
    padding: 16px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-header h5 {
    font-size: .95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h5 i {
    color: var(--green-600);
}
.card-body {
    padding: 22px;
}

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar-lg {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.4rem;
    box-shadow: 0 4px 10px rgba(46,133,64,.2);
    flex-shrink: 0;
}
.avatar-md {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--green-50);
    color: var(--green-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table {
    width: 100%;
    border-collapse: collapse;
}
.table tr {
    border-bottom: 1px solid var(--gray-200);
}
.table tr:last-child {
    border-bottom: none;
}
.table th {
    padding: 12px 0;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    width: 120px;
    vertical-align: top;
}
.table td {
    padding: 12px 0;
    font-size: .85rem;
    color: var(--gray-800);
}

/* ══════════════════════════════════════════════
   CODE
══════════════════════════════════════════════ */
.code-block {
    background: var(--gray-800);
    color: var(--gray-300);
    padding: 16px;
    border-radius: var(--rl);
    font-size: .75rem;
    font-family: var(--mono);
    overflow-x: auto;
    max-height: 400px;
    border: 1.5px solid var(--gray-700);
}
.inline-code {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 2px 8px;
    border-radius: var(--r);
    font-family: var(--mono);
    font-size: .7rem;
}

/* ══════════════════════════════════════════════
   GRID
══════════════════════════════════════════════ */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .detail-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 32px 16px;
}
.empty-state i {
    font-size: 2.5rem;
    color: var(--gray-300);
    margin-bottom: 12px;
}
.empty-state h5 {
    font-size: .9rem;
    font-weight: 600;
    color: var(--gray-600);
}
.empty-state p {
    color: var(--gray-400);
    font-size: .75rem;
}
</style>

<div class="act-detail-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home fa-xs"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('activity.index') }}">{{ __('activity.breadcrumb_activity_log') }}</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ __('activity.breadcrumb_details', ['id' => $activity->id]) }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div class="header-title">
            <span class="header-icon"><i class="fas fa-info-circle"></i></span>
            <h1>{!! __('activity.header_details_title', ['id' => $activity->id]) !!}</h1>
        </div>
        <p class="header-subtitle">{{ __('activity.header_details_subtitle') }}</p>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('activity.index') }}" class="btn btn-gray btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('activity.action_back') }}
        </a>
        <button onclick="window.print()" class="btn btn-gray btn-sm">
            <i class="fas fa-print"></i> {{ __('activity.action_print') }}
        </button>
    </div>

    {{-- Grille principale --}}
    <div class="detail-grid">

        {{-- Informations générales --}}
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> {{ __('activity.detail_general_info') }}</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <td><span class="badge badge-gray">#{{ $activity->id }}</span></td>
                    </tr>
                    <tr>
                        <th>{{ __('activity.table_date_time') }}</th>
                        <td>
                            <div style="font-weight:500;">{{ $activity->created_at->format('d/m/Y') }}</div>
                            <div style="font-size:.7rem; color:var(--gray-500);">{{ $activity->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('activity.table_description') }}</th>
                        <td style="font-weight:500;">{{ $activity->description }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('activity.table_event') }}</th>
                        <td>
                            @php
                                $badgeClass = match($activity->event) {
                                    'created' => 'badge-green',
                                    'updated' => 'badge-gray',
                                    'deleted' => 'badge-red',
                                    default => 'badge-gray'
                                };
                                $eventLabel = match($activity->event) {
                                    'created' => __('activity.event_created'),
                                    'updated' => __('activity.event_updated'),
                                    'deleted' => __('activity.event_deleted'),
                                    default => ucfirst($activity->event)
                                };
                                $eventIcon = match($activity->event) {
                                    'created' => 'fa-plus-circle',
                                    'updated' => 'fa-edit',
                                    'deleted' => 'fa-trash-alt',
                                    default => 'fa-history'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <i class="fas {{ $eventIcon }}"></i> {{ $eventLabel }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('activity.detail_log_name') }}</th>
                        <td><span class="inline-code">{{ $activity->log_name }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Utilisateur --}}
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user"></i> {{ __('activity.detail_user') }}</h5>
            </div>
            <div class="card-body">
                @if($activity->causer)
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-lg">
                            {{ substr($activity->causer->name, 0, 1) }}
                        </div>
                        <div>
                            <h5 style="font-weight:600; margin-bottom:2px;">{{ $activity->causer->name }}</h5>
                            <p style="color:var(--gray-500); margin:0;">{{ $activity->causer->email }}</p>
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td>{{ $activity->causer_id }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><span class="inline-code">{{ class_basename($activity->causer_type) }}</span></td>
                        </tr>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fas fa-robot"></i>
                        <h5>{{ __('activity.detail_system_action') }}</h5>
                        <p>{{ __('activity.detail_no_user') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Objet concerné --}}
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-cube"></i> {{ __('activity.detail_subject') }}</h5>
            </div>
            <div class="card-body">
                @if($activity->subject)
                    @php
                        $modelName = class_basename($activity->subject_type);
                        $modelIcon = match($modelName) {
                            'User' => 'fa-user',
                            'Room' => 'fa-bed',
                            'Transaction' => 'fa-receipt',
                            'Payment' => 'fa-credit-card',
                            'Customer' => 'fa-user-tie',
                            default => 'fa-cube'
                        };
                    @endphp
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-md">
                            <i class="fas {{ $modelIcon }}"></i>
                        </div>
                        <div>
                            <h5 style="font-weight:600; margin-bottom:2px;">{{ $modelName }}</h5>
                            <p style="color:var(--gray-500); margin:0;">ID: {{ $activity->subject_id }}</p>
                        </div>
                    </div>
                    
                    @if(method_exists($activity->subject, 'getNameAttribute') && $activity->subject->getNameAttribute())
                        <div class="d-flex gap-3 mb-2">
                            <span style="color:var(--gray-500); width:80px;">{{ __('activity.table_name') }}</span>
                            <span>{{ $activity->subject->getNameAttribute() }}</span>
                        </div>
                    @endif
                    
                    @if($activity->subject_url)
                        <div class="mt-4">
                            <a href="{{ $activity->subject_url }}" class="btn btn-gray btn-sm">
                                <i class="fas fa-external-link-alt"></i> {{ __('activity.action_view_object') }}
                            </a>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fas fa-trash-alt"></i>
                        <h5>{{ __('activity.detail_deleted_object') }}</h5>
                        <p>{{ __('activity.detail_deleted_object_desc') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Informations techniques --}}
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-microchip"></i> {{ __('activity.detail_tech_info') }}</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>IP Address</th>
                        <td><span class="inline-code">{{ $activity->properties['ip_address'] ?? 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td style="font-size:.7rem;">{{ $activity->properties['user_agent'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td style="font-size:.7rem; word-break:break-word;">{{ $activity->properties['url'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Méthode</th>
                        <td>
                            @if(isset($activity->properties['method']))
                                <span class="badge badge-gray">{{ $activity->properties['method'] }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Propriétés complètes --}}
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-code"></i> {{ __('activity.detail_properties') }}</h5>
            <span class="badge badge-gray">{{ __('activity.properties_count', ['count' => $activity->properties->count()]) }}</span>
        </div>
        <div class="card-body">
            @if($activity->properties->count() > 0)
                <pre class="code-block">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                
                @if(isset($activity->properties['old']) || isset($activity->properties['attributes']))
                <div style="margin-top:20px;">
                    <h6 style="font-weight:600; margin-bottom:12px;">
                        <i class="fas fa-exchange-alt me-2" style="color:var(--green-600);"></i> {{ __('activity.detail_modifications') }}
                    </h6>
                    <div style="background:var(--gray-50); border-radius:var(--rl); padding:16px;">
                        @if(isset($activity->properties['old']))
                        <div style="margin-bottom:16px;">
                            <div style="font-size:.7rem; color:var(--gray-500); margin-bottom:4px;">{{ __('activity.detail_old_values') }}</div>
                            <pre class="code-block" style="max-height:150px;">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                        
                        @if(isset($activity->properties['attributes']))
                        <div>
                            <div style="font-size:.7rem; color:var(--gray-500); margin-bottom:4px;">{{ __('activity.detail_new_values') }}</div>
                            <pre class="code-block" style="max-height:150px;">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>{{ __('activity.detail_no_property') }}</h5>
                    <p>{{ __('activity.detail_no_property_desc') }}</p>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Activity details page loaded');
});
</script>

@endsection