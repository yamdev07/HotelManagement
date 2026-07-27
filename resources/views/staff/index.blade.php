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
    // $roles vient du contrôleur : rôles que l'utilisateur courant peut réellement gérer
    // (la Direction n'apparaît que pour l'Admin/Super).
    $counts = [];
    foreach ($roles as $key => $label) { $counts[$key] = $staff->where('role', $key)->count(); }
@endphp

<div class="sp-wrap">

    {{-- ===== HEADER ===== --}}
    <div class="users-header">
        <div class="users-brand">
            <span class="users-brand-icon"><i class="fas fa-user-tie"></i></span>
            <div>
                <div class="users-header-title">{!! __('staff.header_title') !!}</div>
                <div class="users-header-sub">{!! __('staff.header_subtitle') !!}</div>
            </div>
        </div>
        <div class="users-header-actions">
            <button class="btn-db btn-db-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addStaff">
                <i class="fas fa-plus"></i> {{ __('staff.btn_new_member') }}
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    {{-- ===== STAT CARDS ===== --}}
    <div class="stats-grid">
        <div class="stat-card stat-card--total">
            <div class="stat-card-head"><span class="stat-card-icon"><i class="fas fa-users"></i></span></div>
            <div class="stat-card-value">{{ $staff->count() }}</div>
            <div class="stat-card-label">{{ __('staff.stat_total_members') }}</div>
            <div class="stat-card-footer"><i class="fas fa-user-shield"></i> {{ __('staff.stat_your_team') }}</div>
        </div>
        @foreach ($roles as $key => $label)
            @php $m = $roleMeta[$key] ?? [$label, 'fa-user', 'var(--g600)']; @endphp
            <div class="stat-card" style="--bar-c: {{ $m[2] }};">
                <div class="stat-card-head"><span class="stat-card-icon" style="background:var(--g50);color:{{ $m[2] }}"><i class="fas {{ $m[1] }}"></i></span></div>
                <div class="stat-card-value">{{ $counts[$key] }}</div>
                <div class="stat-card-label">{{ $m[0] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ===== FORMULAIRE (repliable) ===== --}}
    <div class="collapse {{ $errors->any() ? 'show' : '' }}" id="addStaff">
        <div class="users-card">
            <div class="users-card-header">
                <div class="users-card-title"><i class="fas fa-user-plus" style="color:var(--g600)"></i> {{ __('staff.form_add_member') }}</div>
            </div>
            <div style="padding: 20px 24px;">
                <p class="sp-hint">{!! __('staff.form_hint') !!}</p>
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="sp-label">{{ __('staff.form_full_name') }}</label>
                            <input type="text" name="name" class="search-input" value="{{ old('name') }}" required></div>
                        <div class="col-md-6"><label class="sp-label">{!! __('staff.form_email') !!}</label>
                            <input type="email" name="email" class="search-input" value="{{ old('email') }}" required></div>
                        <div class="col-md-4"><label class="sp-label">{{ __('staff.form_phone') }}</label>
                            <input type="text" name="phone" class="search-input" value="{{ old('phone') }}"></div>
                        <div class="col-md-4"><label class="sp-label">{{ __('staff.form_role') }}</label>
                            <select name="role" class="search-input" required>
                                <option value="">{{ __('staff.form_role_placeholder') }}</option>
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $roleMeta[$key][0] ?? $label }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-md-4"><label class="sp-label">{{ __('staff.form_password') }}</label>
                            <input type="text" name="password" class="search-input" value="{{ old('password') }}" required placeholder="{{ __('staff.form_password_placeholder') }}"></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn-db btn-db-primary"><i class="fas fa-check"></i> {{ __('staff.btn_create_account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== ACTION BAR (recherche) ===== --}}
    <div class="action-bar">
        <div class="action-left">
            <span class="filter-badge"><i class="fas fa-users"></i> {{ __('staff.action_team') }} <span class="badge-count">{{ $staff->count() }}</span></span>
        </div>
        <div class="action-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="staffSearch" class="search-input" placeholder="{{ __('staff.action_search_placeholder') }}">
            </div>
        </div>
    </div>

    {{-- ===== LISTE ===== --}}
    <div class="users-card">
        <div class="users-card-header">
            <div class="users-card-title"><i class="fas fa-user-group" style="color:var(--g600)"></i> {{ __('staff.list_title') }}</div>
            <span class="filter-badge">{{ __('staff.table_registered', ['count' => $staff->count()]) }}</span>
        </div>
        <div class="table-responsive">
            <table class="users-table" id="staffTable">
                <thead><tr><th style="width:48px">#</th><th>{{ __('staff.table_member') }}</th><th>{{ __('staff.table_role') }}</th><th class="text-end">{{ __('staff.table_actions') }}</th></tr></thead>
                <tbody>
                @forelse ($staff as $i => $m)
                    <tr class="staff-row">
                        <td class="sp-num">{{ $i + 1 }}</td>
                        <td>
                            <div class="user-avatar-cell">
                                <span class="user-avatar staff" style="background:var(--g50);color:{{ $roleMeta[$m->role][2] ?? 'var(--g600)' }}">{{ strtoupper(mb_substr($m->name,0,1)) }}</span>
                                <div>
                                    <div class="sp-name">{{ $m->name }}</div>
                                    <div class="sp-mail">{{ $m->email }}@if($m->phone) · {{ $m->phone }}@endif</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="sp-role" style="color:{{ $roleMeta[$m->role][2] ?? 'var(--g600)' }};background:var(--g50);">{{ $roleMeta[$m->role][0] ?? $m->role }}</span></td>
                        <td class="text-end text-nowrap">
                            <button class="btn-db btn-db-ghost btn-sm" title="{{ __('staff.action_reset_password') }}" onclick="document.getElementById('rp-{{ $m->id }}').classList.toggle('d-none')"><i class="fas fa-key"></i></button>
                            <form action="{{ route('staff.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('staff.confirm_delete', ['name' => $m->name]) }}')">
                                @csrf @method('DELETE')
                                <button class="btn-db btn-db-ghost btn-sm sp-danger" title="{{ __('staff.action_delete') }}"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <tr id="rp-{{ $m->id }}" class="d-none sp-reset">
                        <td></td>
                        <td colspan="3">
                            <form action="{{ route('staff.reset', $m) }}" method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                                @csrf
                                <input type="text" name="password" class="search-input" style="max-width:360px" placeholder="{{ __('staff.password_new_placeholder') }}" required>
                                <button class="btn-db btn-db-primary btn-sm text-nowrap">{{ __('staff.action_reset') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="sp-empty">
                        <i class="fas fa-user-plus"></i>
                        <div>{{ __('staff.empty_title') }}<br>{!! __('staff.empty_desc') !!}</div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Réutilise le système de design des autres modules · couleurs = branding de l'hôtel (--g*) */
.sp-wrap { max-width: 1280px; margin: 0 auto; padding: 8px 4px 40px; }

.users-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; padding:0 0 20px; margin-bottom:20px; border-bottom:1.5px solid var(--s100); }
.users-brand { display:flex; align-items:center; gap:14px; }
.users-brand-icon { width:48px; height:48px; background:var(--g600); border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; flex-shrink:0; box-shadow:0 6px 16px -6px var(--g600); }
.users-header-title { font-size:1.4rem; font-weight:700; color:var(--s900); line-height:1.2; letter-spacing:-.3px; }
.users-header-title em { font-style:normal; color:var(--g600); }
.users-header-sub { font-size:.8rem; color:var(--s400); margin-top:3px; display:flex; align-items:center; gap:8px; }
.users-header-sub i { color:var(--g500); }

.btn-db { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--rl,12px); font-size:.8rem; font-weight:500; border:none; cursor:pointer; transition:.2s; text-decoration:none; white-space:nowrap; line-height:1; }
.btn-db.btn-sm { padding:7px 10px; }
.btn-db-primary { background:var(--g600); color:#fff; box-shadow:0 2px 10px -2px var(--g600); }
.btn-db-primary:hover { background:var(--g700); color:#fff; transform:translateY(-1px); }
.btn-db-ghost { background:var(--white,#fff); color:var(--s600); border:1.5px solid var(--s200); }
.btn-db-ghost:hover { background:var(--s50); border-color:var(--s300); color:var(--s900); }
.sp-danger:hover { color:#dc2626; border-color:#fca5a5; background:#fef2f2; }

.stats-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:22px; }
@media(max-width:1100px){ .stats-grid{ grid-template-columns:repeat(2,1fr);} }
@media(max-width:560px){ .stats-grid{ grid-template-columns:1fr;} }
.stat-card { background:var(--white,#fff); border-radius:var(--rl,14px); padding:20px; border:1.5px solid var(--s100); position:relative; overflow:hidden; transition:.2s; }
.stat-card:hover { transform:translateY(-3px); border-color:var(--g200); box-shadow:0 14px 30px -18px rgba(15,23,42,.35); }
.stat-card::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background:var(--bar-c, var(--g500)); }
.stat-card--total { --bar-c: var(--g600); }
.stat-card-head { display:flex; margin-bottom:14px; }
.stat-card-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:var(--g100); color:var(--g600); }
.stat-card-value { font-size:2rem; font-weight:800; color:var(--s900); line-height:1; letter-spacing:-1px; margin-bottom:4px; }
.stat-card-label { font-size:.8rem; color:var(--s400); }
.stat-card-footer { display:flex; align-items:center; gap:5px; font-size:.72rem; padding-top:12px; margin-top:10px; border-top:1px solid var(--s100); color:var(--g600); }

.action-bar { background:var(--white,#fff); border-radius:var(--rxl,16px); padding:14px 18px; margin-bottom:20px; border:1.5px solid var(--s100); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:14px; }
.action-right { flex:1; max-width:400px; }
.filter-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:30px; font-size:.75rem; font-weight:600; background:var(--g100); color:var(--g700); border:1px solid var(--g200); }
.badge-count { background:var(--white,#fff); padding:2px 7px; border-radius:20px; font-size:.65rem; }
.search-container { position:relative; width:100%; }
.search-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--s400); font-size:.9rem; pointer-events:none; }
.search-input { width:100%; padding:10px 16px 10px 16px; border:1.5px solid var(--s200); border-radius:var(--rl,12px); font-size:.875rem; background:var(--white,#fff); transition:.2s; }
.search-container .search-input { padding-left:42px; }
.search-input:focus { outline:none; border-color:var(--g400); box-shadow:0 0 0 3px var(--g100); }
.sp-label { font-size:.78rem; font-weight:600; color:var(--s600); margin-bottom:5px; display:block; }
.sp-hint { font-size:.82rem; color:var(--s500); margin-bottom:16px; }

.users-card { background:var(--white,#fff); border-radius:var(--rxl,16px); border:1.5px solid var(--s100); overflow:hidden; margin-bottom:20px; }
.users-card-header { padding:16px 22px; border-bottom:1.5px solid var(--s100); display:flex; align-items:center; justify-content:space-between; gap:10px; }
.users-card-title { font-size:1rem; font-weight:700; color:var(--s900); display:flex; align-items:center; gap:8px; }
.users-table { width:100%; border-collapse:collapse; }
.users-table thead th { text-align:left; font-size:.7rem; text-transform:uppercase; letter-spacing:.5px; color:var(--s400); padding:12px 22px; border-bottom:1.5px solid var(--s100); font-weight:600; }
.users-table tbody tr { border-bottom:1px solid var(--s100); transition:.15s; }
.users-table tbody tr:hover { background:var(--g50); }
.users-table td { padding:12px 22px; font-size:.85rem; color:var(--s700); vertical-align:middle; }
.user-avatar-cell { display:flex; align-items:center; gap:11px; }
.user-avatar { width:38px; height:38px; border-radius:10px; display:grid; place-items:center; font-weight:700; flex-shrink:0; }
.sp-num { color:var(--s400); font-weight:600; }
.sp-name { font-weight:600; color:var(--s900); }
.sp-mail { font-size:.76rem; color:var(--s400); }
.sp-role { font-size:.72rem; font-weight:700; padding:4px 11px; border-radius:20px; }
.sp-reset td { background:var(--g50); }
.sp-empty { text-align:center; padding:48px 20px; color:var(--s400); }
.sp-empty i { font-size:1.8rem; opacity:.5; display:block; margin-bottom:10px; }
</style>
<script>
    (function(){
        const s = document.getElementById('staffSearch');
        if(!s) return;
        s.addEventListener('input', function(){
            const q = this.value.toLowerCase();
            document.querySelectorAll('#staffTable tbody .staff-row').forEach(function(row){
                const match = row.innerText.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                const rp = row.nextElementSibling;
                if(rp && rp.id && rp.id.indexOf('rp-') === 0 && !match) rp.classList.add('d-none');
            });
        });
    })();
</script>
@endsection
