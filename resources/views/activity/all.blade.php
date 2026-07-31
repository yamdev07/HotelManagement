@extends('template.master')
@section('title', 'activity.page_title_all_logs')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    /* ── 4 COULEURS (vert, rouge, gris, blanc) ── */
    --green-50:  var(--g50);
    --green-100: var(--g100);
    --green-500: var(--g500);
    --green-600: var(--g600);
    --green-700: var(--g700);

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

* { box-sizing: border-box; }

.log-page {
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
    margin-top: 4px;
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
    white-space: nowrap;
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
.btn-red {
    background: var(--red-500);
    color: white;
}
.btn-red:hover {
    background: var(--red-600);
    transform: translateY(-1px);
    color: white;
}
.btn-sm {
    padding: 6px 12px;
    font-size: .7rem;
}
.btn-icon {
    width: 30px;
    height: 30px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--r);
    border: 1.5px solid var(--gray-200);
    background: var(--white);
    color: var(--gray-500);
}
.btn-icon:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    box-shadow: var(--shadow-xs);
}
.card-header {
    padding: 18px 22px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--white);
}
.card-header h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header h4 i {
    color: var(--green-600);
}
.card-footer {
    padding: 16px 22px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--white);
}

/* ══════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════ */
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table thead th {
    background: var(--gray-50);
    padding: 14px 16px;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--gray-200);
    white-space: nowrap;
}
.table tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    font-size: .8rem;
    vertical-align: middle;
}
.table tbody tr {
    transition: var(--transition);
}
.table tbody tr:hover td {
    background: var(--green-50);
}
.text-muted {
    color: var(--gray-500) !important;
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: .68rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-700); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════ */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 600;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-state i {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}
.empty-state h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 4px;
}
.empty-state p {
    color: var(--gray-400);
}

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    border-bottom: 1.5px solid var(--gray-200);
    padding: 18px 22px;
}
.modal-title {
    font-weight: 600;
    font-size: 1rem;
}
.modal-body {
    padding: 22px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 22px;
}
.form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 6px;
}
.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .8rem;
}
.form-control:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgb(from var(--g500) r g b / .1);
}
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.alert-red {
    background: var(--red-50);
    border-color: var(--red-100);
    color: var(--red-500);
}
</style>

<div class="log-page">

    {{-- En-tête --}}
    <div class="page-header anim-1">
        <div>
            <h1 class="header-title">{!! __('activity.header_all_logs') !!}</h1>
            <p class="header-subtitle">{{ __('activity.header_subtitle_count', ['count' => $activities->count()]) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activity-log.index') }}" class="btn btn-gray btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('activity.action_back_to_log') }}
            </a>
            <div class="dropdown">
                <button class="btn btn-gray btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> {{ __('activity.filter_export') }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('activity-log.export', 'csv') }}">CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('activity-log.export', 'json') }}">JSON</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card anim-2">
        <div class="card-header">
            <h4><i class="fas fa-list"></i> Liste des activités</h4>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="140">{{ __('activity.table_date') }}</th>
                        <th>{{ __('activity.table_description') }}</th>
                        <th width="200">{{ __('activity.table_user') }}</th>
                        <th width="90">{{ __('activity.table_event') }}</th>
                        <th width="90">{{ __('activity.table_subject') }}</th>
                        <th width="60" class="text-center">{{ __('activity.table_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        @php
                            $badgeClass = match($activity->event) {
                                'created' => 'badge-green',
                                'updated' => 'badge-gray',
                                'deleted' => 'badge-red',
                                'restored' => 'badge-green',
                                default => 'badge-gray'
                            };
                            $eventLabel = match($activity->event) {
                                'created' => __('activity.event_created_short'),
                                'updated' => __('activity.event_updated_short'),
                                'deleted' => __('activity.event_deleted_short'),
                                'restored' => __('activity.event_restored_short'),
                                default => ucfirst($activity->event)
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($activity->description, 50) }}</div>
                                @if($activity->properties->count() > 0)
                                    <small class="text-muted">{{ __('activity.properties_count', ['count' => $activity->properties->count()]) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($activity->causer)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">
                                            {{ substr($activity->causer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $activity->causer->name }}</div>
                                            <small class="text-muted">{{ $activity->causer->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">{{ __('activity.system_system') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $eventLabel }}</span>
                            </td>
                            <td>
                                @if($activity->subject)
                                    <span class="badge badge-gray">{{ class_basename($activity->subject_type) }}</span>
                                @else
                                    <span class="text-muted fst-italic">{{ __('activity.system_deleted') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('activity-log.show', $activity->id) }}" 
                                   class="btn-icon"
                                   data-bs-toggle="tooltip" 
                                    title="{{ __('activity.action_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h4>{{ __('activity.empty_no_activity') }}</h4>
                                    <p>{{ __('activity.empty_no_activity_desc_full') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    {{ __('activity.footer_total', ['count' => $activities->count()]) }}
                </div>
                <button class="btn btn-red btn-sm" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                    <i class="fas fa-broom"></i> {{ __('activity.action_cleanup_old') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Modal de nettoyage --}}
    <div class="modal fade" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-broom me-2" style="color:var(--green-600);"></i> {{ __('activity.modal_cleanup_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('activity-log.cleanup') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">{{ __('activity.modal_cleanup_desc') }}</p>
                        <div class="mb-4">
                            <label class="form-label">{{ __('activity.modal_cleanup_days') }}</label>
                            <input type="number" name="days" class="form-control" min="1" max="365" value="30">
                        </div>
                        <div class="alert alert-red">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ __('activity.modal_cleanup_alert') }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-gray" data-bs-dismiss="modal">{{ __('activity.modal_cancel') }}</button>
                        <button type="submit" class="btn btn-red">{{ __('activity.modal_cleanup_confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function(el) {
        return new bootstrap.Tooltip(el);
    });
});
</script>

@endsection