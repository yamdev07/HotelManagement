@extends('template.master')

@section('title', __('staff.page_title'))

@section('content')
@php
    // Méta d'affichage (libellé court, icône, couleur de la marque de l'hôtel).
    $roleMeta = [
        'Manager'      => ['Direction',                   'fa-user-tie',       'var(--g700)'],
        'Receptionist' => [__('staff.role_receptionist'), 'fa-bell-concierge', 'var(--g600)'],
        'Cashier'      => [__('staff.role_cashier'),      'fa-cash-register',  'var(--g500)'],
        'Housekeeping' => [__('staff.role_housekeeping'), 'fa-broom',          'var(--g400)'],
        'Servant'      => [__('staff.role_servant'),      'fa-utensils',       'var(--g300)'],
        'Cuisiner'     => [__('staff.role_cuisinier'),    'fa-kitchen-set',    'var(--g600)'],
    ];
    // $roles vient du contrôleur : rôles que l'utilisateur courant peut réellement gérer.
    $counts = [];
    foreach ($roles as $key => $label) { $counts[$key] = $staff->where('role', $key)->count(); }
@endphp

<style>
/* ═══════════════════════════════════════════════════════════════
   Page Personnel, design aligné sur le dashboard (maquette validée).
   Accent = couleur de l'hôtel (--g*). Thème clair & sombre.
   ═══════════════════════════════════════════════════════════════ */
.db-page {
  --card: #ffffff; --page: #f8faf9; --line: #e9edea; --line2: #dce2de;
  --ink: #181d1a; --ink2: #5c655f; --ink3: #98a19b; --tint: #f4f7f5;
  --ok: var(--g500); --ok-t: #eaf3ec; --bad: #b4342a; --bad-t: #fbe9e7;
  --info: #3b6c8f; --info-t: #e7f0f6;
  --acc: var(--g600, var(--g500)); --acc2: var(--g500, var(--g500));
  --acc-t: color-mix(in srgb, var(--g500, var(--g500)) 13%, var(--card));
  --r: 12px; --r-sm: 9px; --sh: 0 1px 2px rgba(20,40,30,.05);
  display: flex; flex-direction: column; gap: 22px;
  font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
  color: var(--ink); font-variant-numeric: tabular-nums;
}
html[data-theme="dark"] .db-page {
  --card: #161b18; --page: #0f1311; --line: #262e29; --line2: #323b35;
  --ink: #e9eeeb; --ink2: #97a29b; --ink3: #6e7872; --tint: #1b211d;
  --ok: #4fb268; --ok-t: #16241b; --bad: #e27469; --bad-t: #2a1714;
  --info: #6fa8ce; --info-t: #10202a;
  --acc-t: color-mix(in srgb, var(--g500, #4fb268) 20%, var(--card));
  --sh: 0 1px 2px rgba(0,0,0,.3);
}
.db-page * { box-sizing: border-box; }
@keyframes dbfade { from { opacity:0; transform:translateY(10px);} to { opacity:1; transform:none; } }
.anim-1{animation:dbfade .4s ease both}.anim-2{animation:dbfade .4s .05s ease both}
.anim-3{animation:dbfade .4s .1s ease both}.anim-4{animation:dbfade .4s .15s ease both}
@media (prefers-reduced-motion: reduce){ .db-page [class^="anim-"]{ animation:none; } }
.db-page a { text-decoration:none; color:inherit; }

/* Header */
.db-header { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
.db-brand { display:flex; align-items:center; gap:13px; }
.db-brand-icon { width:42px; height:42px; border-radius:11px; flex:none; background:var(--acc-t); color:var(--acc); display:grid; place-items:center; font-size:1.05rem; }
.db-header-greeting { margin:0; font-size:1.25rem; font-weight:680; letter-spacing:-.02em; }
.db-header-greeting em { font-style:normal; color:var(--acc); }
.db-header-sub { margin:2px 0 0; font-size:.82rem; color:var(--ink3); }

/* Alerts */
.db-alert { border-radius:var(--r); padding:12px 16px; font-size:.85rem; display:flex; gap:9px; align-items:flex-start; }
.db-alert i { margin-top:2px; }
.db-alert-ok { background:var(--ok-t); color:var(--ok); border:1px solid color-mix(in srgb,var(--ok) 25%,transparent); }
.db-alert-bad { background:var(--bad-t); color:var(--bad); border:1px solid color-mix(in srgb,var(--bad) 25%,transparent); }
.db-alert ul { margin:4px 0 0; padding-left:18px; }

/* Stat cards */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
@media (max-width:1100px){ .stats-grid{ grid-template-columns:repeat(2,1fr);} }
@media (max-width:560px){ .stats-grid{ grid-template-columns:1fr;} }
.stat-card { background:var(--card); border:1px solid var(--line); border-radius:var(--r); padding:18px; display:flex; flex-direction:column; gap:8px; box-shadow:var(--sh); transition:border-color .15s, transform .15s; }
.stat-card:hover { border-color:var(--line2); transform:translateY(-1px); }
.stat-card-head { display:flex; align-items:center; justify-content:space-between; }
.stat-card-icon { width:34px; height:34px; border-radius:9px; display:grid; place-items:center; font-size:.95rem; background:var(--acc-t); color:var(--acc); }
.stat-card-value { font-size:1.7rem; font-weight:720; letter-spacing:-.02em; line-height:1; }
.stat-card-label { font-size:.82rem; color:var(--ink2); font-weight:500; }

/* Card + panel */
.db-card { background:var(--card); border:1px solid var(--line); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; }
.db-card-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 18px; border-bottom:1px solid var(--line); }
.db-card-title { display:flex; align-items:center; gap:10px; margin:0; font-size:.95rem; font-weight:660; color:var(--ink); }
.db-card-title-dot { width:8px; height:8px; border-radius:50%; flex:none; background:var(--acc); }
.db-card-subtitle { font-size:.76rem; color:var(--ink3); margin-top:3px; }
.db-card-body { padding:18px; }

/* Buttons */
.btn-db { display:inline-flex; align-items:center; gap:7px; border-radius:8px; padding:8px 14px; font-size:.8rem; font-weight:600; cursor:pointer; border:1px solid transparent; font-family:inherit; transition:all .15s; white-space:nowrap; text-decoration:none; }
.btn-db-primary { background:var(--acc); color:#fff; }
.btn-db-primary:hover { background:var(--acc2); color:#fff; }
.btn-db-ghost { background:var(--card); color:var(--ink2); border-color:var(--line); }
.btn-db-ghost:hover { background:var(--tint); color:var(--ink); border-color:var(--line2); }
.btn-db-icon { width:32px; height:32px; padding:0; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; background:var(--tint); border:1px solid var(--line2); color:var(--ink); cursor:pointer; }
.btn-db-icon:hover { color:var(--acc); border-color:var(--acc); }
.btn-db-icon.danger:hover { color:var(--bad); border-color:var(--bad); background:var(--bad-t); }
html[data-theme="dark"] .db-page .btn-db-icon { background:#232b26; border-color:#3a453e; color:#d3dad5; }

/* Search */
.db-search { position:relative; width:100%; max-width:360px; }
.db-search i { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--ink3); font-size:.85rem; pointer-events:none; }
.db-input { width:100%; padding:9px 12px; border:1px solid var(--line); border-radius:8px; background:var(--card); color:var(--ink); font:inherit; font-size:.83rem; }
.db-search .db-input { padding-left:36px; }
.db-input:focus { outline:none; border-color:var(--acc); box-shadow:0 0 0 3px var(--acc-t); }
.db-label { font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:var(--ink3); font-weight:700; margin-bottom:6px; display:block; }

/* Table */
.db-tbl-scroll { overflow-x:auto; }
.db-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.db-table th { text-align:left; font-size:.66rem; text-transform:uppercase; letter-spacing:.05em; color:var(--ink3); font-weight:700; padding:11px 16px; border-bottom:1px solid var(--line); }
.db-table td { padding:14px 16px; border-bottom:1px solid var(--line); vertical-align:middle; color:var(--ink2); }
.db-table tbody tr:hover { background:var(--tint); }
.db-table tr.sp-extra:hover { background:transparent; }
.sp-num { color:var(--ink3); font-weight:600; }
.staff-cell { display:flex; align-items:center; gap:11px; }
.staff-avatar { width:36px; height:36px; border-radius:10px; display:grid; place-items:center; font-weight:700; font-size:.8rem; flex:none; background:var(--acc-t); color:var(--acc); }
.staff-name { font-weight:640; color:var(--ink); }
.staff-mail { font-size:.74rem; color:var(--ink3); }
.role-chip { display:inline-flex; align-items:center; gap:6px; font-size:.72rem; font-weight:650; padding:4px 11px; border-radius:20px; background:var(--acc-t); color:var(--acc); }
.sp-extra > td { background:var(--tint); }
.sp-inline { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
.actions-cell { display:flex; gap:6px; justify-content:flex-end; }

/* Empty */
.db-empty { text-align:center; padding:44px 20px; color:var(--ink3); }
.db-empty-icon { width:56px; height:56px; border-radius:50%; background:var(--tint); color:var(--ink3); display:grid; place-items:center; font-size:1.3rem; margin:0 auto 12px; }
</style>

<div class="db-page">

    {{-- HEADER --}}
    <div class="db-header anim-1">
        <div class="db-brand">
            <div class="db-brand-icon"><i class="fas fa-user-tie"></i></div>
            <div>
                <h1 class="db-header-greeting">{!! __('staff.header_title') !!}</h1>
                <p class="db-header-sub">{!! __('staff.header_subtitle') !!}</p>
            </div>
        </div>
        <button class="btn-db btn-db-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addStaff">
            <i class="fas fa-plus fa-xs"></i> {{ __('staff.btn_new_member') }}
        </button>
    </div>

    {{-- ALERTES --}}
    @if (session('success'))
        <div class="db-alert db-alert-ok anim-1"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="db-alert db-alert-bad anim-1"><i class="fas fa-triangle-exclamation"></i><div>{{ session('error') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="db-alert db-alert-bad anim-1">
            <i class="fas fa-circle-exclamation"></i>
            <div><strong>{{ __('staff.validation_errors_title') }}</strong>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="stats-grid anim-2">
        <div class="stat-card">
            <div class="stat-card-head">
                <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $staff->count() }}</div>
            <div class="stat-card-label">{{ __('staff.stat_total') }}</div>
        </div>
        @foreach ($roles as $key => $label)
            <div class="stat-card">
                <div class="stat-card-head">
                    <div class="stat-card-icon"><i class="fas {{ $roleMeta[$key][1] ?? 'fa-user' }}"></i></div>
                </div>
                <div class="stat-card-value">{{ $counts[$key] ?? 0 }}</div>
                <div class="stat-card-label">{{ $roleMeta[$key][0] ?? $label }}</div>
            </div>
        @endforeach
    </div>

    {{-- FORMULAIRE D'AJOUT (repliable) --}}
    <div class="collapse @if($errors->any() && old('name')) show @endif" id="addStaff">
        <div class="db-card anim-2">
            <div class="db-card-header">
                <h2 class="db-card-title"><span class="db-card-title-dot"></span> {{ __('staff.btn_new_member') }}</h2>
            </div>
            <div class="db-card-body">
                <p class="db-card-subtitle" style="margin-bottom:16px;">{!! __('staff.form_hint') !!}</p>
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="db-label">{{ __('staff.form_full_name') }}</label>
                            <input type="text" name="name" class="db-input" value="{{ old('name') }}" required></div>
                        <div class="col-md-6"><label class="db-label">{!! __('staff.form_email') !!}</label>
                            <input type="email" name="email" class="db-input" value="{{ old('email') }}" required></div>
                        <div class="col-md-4"><label class="db-label">{{ __('staff.form_phone') }}</label>
                            <input type="text" name="phone" class="db-input" value="{{ old('phone') }}"></div>
                        <div class="col-md-4"><label class="db-label">{{ __('staff.form_role') }}</label>
                            <select name="role" class="db-input" required>
                                <option value="">{{ __('staff.form_role_placeholder') }}</option>
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $roleMeta[$key][0] ?? $label }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-md-4"><label class="db-label">{{ __('staff.form_password') }}</label>
                            <input type="text" name="password" class="db-input" value="{{ old('password') }}" required placeholder="{{ __('staff.form_password_placeholder') }}"></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn-db btn-db-primary"><i class="fas fa-check fa-xs"></i> {{ __('staff.btn_create_account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- LISTE --}}
    <div class="db-card anim-3">
        <div class="db-card-header">
            <div>
                <h2 class="db-card-title"><span class="db-card-title-dot"></span> {{ __('staff.list_title') }}</h2>
                <div class="db-card-subtitle">{{ __('staff.table_registered', ['count' => $staff->count()]) }}</div>
            </div>
            <div class="db-search">
                <i class="fas fa-search"></i>
                <input type="text" id="staffSearch" class="db-input" placeholder="{{ __('staff.action_search_placeholder') }}">
            </div>
        </div>
        <div class="db-tbl-scroll">
            <table class="db-table" id="staffTable">
                <thead><tr><th style="width:40px">#</th><th>{{ __('staff.table_member') }}</th><th>{{ __('staff.table_role') }}</th><th style="text-align:right">{{ __('staff.table_actions') }}</th></tr></thead>
                <tbody>
                @forelse ($staff as $i => $m)
                    <tr class="staff-row" data-id="{{ $m->id }}">
                        <td class="sp-num">{{ $i + 1 }}</td>
                        <td>
                            <div class="staff-cell">
                                <span class="staff-avatar">{{ strtoupper(mb_substr($m->name,0,1)) }}</span>
                                <div>
                                    <div class="staff-name">{{ $m->name }}</div>
                                    <div class="staff-mail">{{ $m->email }}@if($m->phone) · {{ $m->phone }}@endif</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="role-chip"><i class="fas {{ $roleMeta[$m->role][1] ?? 'fa-user' }}"></i> {{ $roleMeta[$m->role][0] ?? $m->role }}</span></td>
                        <td>
                            <div class="actions-cell">
                                <button class="btn-db-icon" title="{{ __('staff.action_edit') }}" onclick="document.getElementById('ed-{{ $m->id }}').classList.toggle('d-none')"><i class="fas fa-pen fa-xs"></i></button>
                                <button class="btn-db-icon" title="{{ __('staff.action_reset_password') }}" onclick="document.getElementById('rp-{{ $m->id }}').classList.toggle('d-none')"><i class="fas fa-key fa-xs"></i></button>
                                <form action="{{ route('staff.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('staff.confirm_delete', ['name' => $m->name]) }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn-db-icon danger" title="{{ __('staff.action_delete') }}"><i class="fas fa-trash fa-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr id="ed-{{ $m->id }}" class="d-none sp-extra">
                        <td></td>
                        <td colspan="3">
                            <form action="{{ route('staff.update', $m) }}" method="POST" class="sp-inline">
                                @csrf @method('PUT')
                                <input type="text" name="name" class="db-input" style="max-width:190px" value="{{ old('name', $m->name) }}" placeholder="{{ __('staff.form_full_name') }}" required>
                                <input type="email" name="email" class="db-input" style="max-width:210px" value="{{ old('email', $m->email) }}" placeholder="{{ __('staff.form_email') }}" required>
                                <input type="text" name="phone" class="db-input" style="max-width:150px" value="{{ old('phone', $m->phone) }}" placeholder="{{ __('staff.form_phone') }}">
                                <select name="role" class="db-input" style="max-width:170px" required>
                                    @foreach ($roles as $key => $label)
                                        <option value="{{ $key }}" {{ old('role', $m->role) === $key ? 'selected' : '' }}>{{ $roleMeta[$key][0] ?? $label }}</option>
                                    @endforeach
                                </select>
                                <button class="btn-db btn-db-primary"><i class="fas fa-check fa-xs"></i> {{ __('staff.action_save') }}</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="rp-{{ $m->id }}" class="d-none sp-extra">
                        <td></td>
                        <td colspan="3">
                            <form action="{{ route('staff.reset', $m) }}" method="POST" class="sp-inline">
                                @csrf
                                <input type="text" name="password" class="db-input" style="max-width:340px" placeholder="{{ __('staff.password_new_placeholder') }}" required>
                                <button class="btn-db btn-db-primary"><i class="fas fa-key fa-xs"></i> {{ __('staff.action_reset') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">
                        <div class="db-empty">
                            <div class="db-empty-icon"><i class="fas fa-user-plus"></i></div>
                            <div>{{ __('staff.empty_title') }}<br>{!! __('staff.empty_desc') !!}</div>
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function(){
        const s = document.getElementById('staffSearch');
        if(!s) return;
        s.addEventListener('input', function(){
            const q = this.value.toLowerCase();
            document.querySelectorAll('#staffTable tbody .staff-row').forEach(function(row){
                const match = row.innerText.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                let sib = row.nextElementSibling;
                while (sib && sib.classList.contains('sp-extra')) {
                    sib.style.display = match ? '' : 'none';
                    if (!match) sib.classList.add('d-none');
                    sib = sib.nextElementSibling;
                }
            });
        });
    })();
</script>
@endsection
