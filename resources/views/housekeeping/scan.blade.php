@extends('template.master')

@section('title', __('housekeeping.scan.title'))

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

* { box-sizing: border-box; margin: 0; padding: 0; }

.scan-page {
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
    margin-bottom: 24px;
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
    box-shadow: 0 4px 10px rgb(from var(--g500) r g b / .3);
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
.btn-green {
    background: var(--green-600);
    color: white;
}
.btn-green:hover {
    background: var(--green-700);
    transform: translateY(-1px);
    color: white;
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
}
.btn-outline {
    background: var(--white);
    color: var(--gray-600);
    border: 1.5px solid var(--gray-200);
}
.btn-outline:hover {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.btn-lg {
    padding: 12px 24px;
    font-size: .9rem;
}

/* ══════════════════════════════════════════════
   CARD
══════════════════════════════════════════════ */
.card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--rxl);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}
.card-header {
    padding: 16px 20px;
    border-bottom: 1.5px solid var(--gray-200);
    background: var(--green-600);
    color: white;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header i {
    color: white;
}
.card-header.light {
    background: var(--gray-100);
    color: var(--gray-700);
}
.card-header.light i {
    color: var(--green-600);
}
.card-body {
    padding: 24px;
}
.card-footer {
    padding: 16px 20px;
    border-top: 1.5px solid var(--gray-200);
    background: var(--gray-50);
}

/* ══════════════════════════════════════════════
   SCAN AREA
══════════════════════════════════════════════ */
.scan-area {
    text-align: center;
}
.scan-frame {
    border: 2px dashed var(--gray-300);
    border-radius: var(--rl);
    padding: 20px;
    background: var(--gray-50);
    margin-bottom: 16px;
}
#reader {
    width: 100%;
    min-height: 300px;
}
#reader video {
    width: 100%;
    border-radius: var(--r);
}

/* ══════════════════════════════════════════════
   ALERT
══════════════════════════════════════════════ */
.alert {
    padding: 14px 18px;
    border-radius: var(--rl);
    border: 1.5px solid;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.alert-green {
    background: var(--green-50);
    border-color: var(--green-200);
    color: var(--green-700);
}
.alert-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════
   FORM
══════════════════════════════════════════════ */
.form-control, .form-select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--r);
    font-size: .85rem;
    transition: var(--transition);
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--green-400);
    box-shadow: 0 0 0 3px rgb(from var(--g500) r g b / .1);
}
.form-control-lg {
    padding: 14px 18px;
    font-size: .95rem;
}
.form-select-lg {
    padding: 14px 18px;
    font-size: .95rem;
}
hr {
    border: none;
    border-top: 1.5px solid var(--gray-200);
    margin: 24px 0;
}

/* ══════════════════════════════════════════════
   LIST GROUP
══════════════════════════════════════════════ */
.list-group {
    list-style: none;
    padding: 0;
}
.list-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid var(--gray-200);
}
.list-item:last-child {
    border-bottom: none;
}
.list-item i {
    width: 24px;
    color: var(--green-600);
}

/* ══════════════════════════════════════════════
   BADGES
══════════════════════════════════════════════ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: .7rem;
    font-weight: 600;
}
.badge-green { background: var(--green-50); color: var(--green-700); border: 1.5px solid var(--green-200); }
.badge-red { background: var(--red-50); color: var(--red-500); border: 1.5px solid var(--red-100); }
.badge-gray { background: var(--gray-100); color: var(--gray-600); border: 1.5px solid var(--gray-200); }

/* ══════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════ */
.modal-content {
    border-radius: var(--rxl);
    border: 1.5px solid var(--gray-200);
}
.modal-header {
    background: var(--green-600);
    color: white;
    border-bottom: 1.5px solid var(--gray-200);
    padding: 16px 20px;
}
.modal-header .btn-close {
    filter: invert(1);
}
.modal-title i {
    color: white;
}
.modal-body {
    padding: 20px;
}
.modal-footer {
    border-top: 1.5px solid var(--gray-200);
    padding: 16px 20px;
}
code {
    background: var(--gray-100);
    color: var(--gray-700);
    padding: 2px 6px;
    border-radius: var(--r);
    font-family: var(--mono);
}
</style>

<div class="scan-page">

    {{-- Breadcrumb --}}
    <div class="breadcrumb anim-1">
        <a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <a href="{{ route('housekeeping.index') }}">Housekeeping</a>
        <span class="sep"><i class="fas fa-chevron-right fa-xs"></i></span>
        <span class="current">{{ __('housekeeping.scan.breadcrumb_scan') }}</span>
    </div>

    {{-- En-tête --}}
    <div class="page-header anim-2">
        <div>
            <div class="header-title">
                <span class="header-icon"><i class="fas fa-qrcode"></i></span>
                <h1>{!! __('housekeeping.scan.header') !!}</h1>
            </div>
            <p class="header-subtitle">{{ __('housekeeping.scan.header_desc') }}</p>
        </div>
        <a href="{{ route('housekeeping.index') }}" class="btn btn-gray">
            <i class="fas fa-arrow-left"></i> {{ __('housekeeping.scan.btn_back') }}
        </a>
    </div>

    {{-- Scanner --}}
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-camera"></i> {!! __('housekeeping.scan.card_scan') !!}
                </div>
                <div class="card-body">
                    <div class="scan-area">
                        <div class="scan-frame">
                            <div id="reader" style="min-height:300px;"></div>
                        </div>
                        <div class="alert alert-green">
                            <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                            <div>{{ __('housekeeping.scan.scan_alert') }}</div>
                        </div>
                    </div>

                    <hr>

                    {{-- Saisie manuelle --}}
                    <h6 class="fw-semibold mb-3"><i class="fas fa-keyboard me-2" style="color:var(--green-600);"></i> {!! __('housekeeping.scan.manual_title') !!}</h6>
                    <form id="manualForm" action="{{ route('housekeeping.scan.process') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-lg" name="room_number" placeholder="{{ __('housekeeping.scan.placeholder_room') }}" required>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg" name="action" required>
                                    <option value="">Action</option>
                                    <option value="start-cleaning">{{ __('housekeeping.scan.action_start') }}</option>
                                    <option value="finish-cleaning">{{ __('housekeeping.scan.action_finish') }}</option>
                                    <option value="maintenance">{{ __('housekeeping.scan.action_maintenance') }}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" form="manualForm" class="btn btn-green btn-lg w-100">
                        <i class="fas fa-paper-plane"></i> {{ __('housekeeping.scan.btn_validate') }}
                    </button>
                </div>
            </div>

            {{-- Dernières actions --}}
            <div class="card mt-4">
                <div class="card-header light">
                    <i class="fas fa-history"></i> {!! __('housekeeping.scan.recent_actions') !!}
                </div>
                <div class="card-body p-0">
                    <div class="list-group">
                        <div class="list-item">
                            <div><i class="fas fa-door-closed"></i> Chambre 101</div>
                            <span class="badge badge-green">{{ __('housekeeping.scan.status_cleaned') }} 09:30</span>
                        </div>
                        <div class="list-item">
                            <div><i class="fas fa-door-closed"></i> Chambre 205</div>
                            <span class="badge badge-gray">{{ __('housekeeping.scan.status_cleaning') }}</span>
                        </div>
                        <div class="list-item">
                            <div><i class="fas fa-door-closed"></i> Chambre 308</div>
                            <span class="badge badge-red">{{ __('housekeeping.scan.status_to_clean') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode;

document.addEventListener('DOMContentLoaded', function() {
    html5QrCode = new Html5Qrcode("reader");
    
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        onScanSuccess,
        onScanError
    ).catch(() => showCameraError());
});

function onScanSuccess(decodedText) {
    html5QrCode.stop();
    const num = extractRoomNumber(decodedText);
    if (num) showActionModal(num, decodedText);
    else { alert("{{ __('housekeeping.scan.error_qr') }}"); restartScan(); }
}

function onScanError(err) { console.warn(err); }

function extractRoomNumber(t) {
    const m = t.match(/ROOM-(\d+)|CHAMBRE-(\d+)|\b(\d{3})\b/i);
    return m ? (m[1]||m[2]||m[3]) : null;
}

function showActionModal(room, qr) {
    const html = `
        <div class="modal fade" id="scanModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-door-closed"></i> Chambre ${room}</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-green mb-3">
                            <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
                            <div><code>${qr}</code></div>
                        </div>
                        <form id="scanForm" method="POST" action="{{ route('housekeeping.scan.process') }}">
                            @csrf
                            <input type="hidden" name="room_number" value="${room}">
                            <div class="mb-3">
                                <select class="form-select" name="action" required>
                                    <option value="">Action</option>
                                    <option value="start-cleaning">{{ __('housekeeping.scan.action_start') }}</option>
                                    <option value="finish-cleaning">{{ __('housekeeping.scan.action_finish') }}</option>
                                    <option value="maintenance">{{ __('housekeeping.scan.action_maintenance') }}</option>
                                </select>
                            </div>
                            <div id="maintenanceFields" class="d-none">
                                <input type="text" class="form-control" name="maintenance_reason" placeholder="{{ __('housekeeping.scan.reason_placeholder') }}">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-gray" data-bs-dismiss="modal" id="rescanBtn">{{ __('housekeeping.scan.btn_rescan') }}</button>
                        <button class="btn btn-green" form="scanForm">{{ __('housekeeping.scan.btn_validate') }}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    
    const modal = new bootstrap.Modal(document.getElementById('scanModal'));
    modal.show();
    
    document.querySelector('#scanModal select[name="action"]').addEventListener('change', function() {
        document.getElementById('maintenanceFields').classList.toggle('d-none', this.value !== 'maintenance');
    });
    
    document.getElementById('scanModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
        restartScan();
    });
    
    document.getElementById('rescanBtn').addEventListener('click', () => modal.hide());
}

function restartScan() {
    setTimeout(() => {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanError
        ).catch(() => {});
    }, 500);
}

function showCameraError() {
    document.getElementById('reader').innerHTML = `
        <div class="alert alert-green text-center p-4">
            <i class="fas fa-video-slash fa-2x mb-2"></i>
            <p>{{ __('housekeeping.scan.error_camera') }}</p>
            <small>{{ __('housekeeping.scan.error_manual') }}</small>
        </div>
    `;
}

window.addEventListener('beforeunload', () => html5QrCode?.stop());
</script>

@endsection