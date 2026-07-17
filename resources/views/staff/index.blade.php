@extends('template.master')

@section('title', 'Personnel')

@section('content')
@php
    $counts = [
        'Receptionist' => $staff->where('role', 'Receptionist')->count(),
        'Housekeeping' => $staff->where('role', 'Housekeeping')->count(),
        'Servant'      => $staff->where('role', 'Servant')->count(),
        'Cuisiner'     => $staff->where('role', 'Cuisiner')->count(),
    ];
    $roleColors = [
        'Receptionist' => ['#6366f1', 'rgba(99,102,241,.12)'],
        'Housekeeping' => ['#0ea5e9', 'rgba(14,165,233,.12)'],
        'Servant'      => ['#f59e0b', 'rgba(245,158,11,.12)'],
        'Cuisiner'     => ['#ef4444', 'rgba(239,68,68,.12)'],
    ];
@endphp

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="sp-hero-ico"><i class="fas fa-user-tie"></i></span>
            <div>
                <h3 class="mb-0 fw-bold">Gestion du <span class="text-primary">personnel</span></h3>
                <div class="text-muted small">Créez les comptes de votre équipe · limités à votre établissement</div>
            </div>
        </div>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addStaff">
            <i class="fas fa-plus me-1"></i> Nouveau membre
        </button>
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

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="sp-card"><span class="sp-ico" style="background:rgba(22,163,74,.12);color:#16a34a"><i class="fas fa-users"></i></span>
                <div class="sp-val">{{ $staff->count() }}</div><div class="sp-lab">Membres au total</div></div>
        </div>
        @foreach ($counts as $role => $n)
            <div class="col-6 col-lg-2">
                <div class="sp-card"><span class="sp-ico" style="background:{{ $roleColors[$role][1] }};color:{{ $roleColors[$role][0] }}"><i class="fas fa-user"></i></span>
                    <div class="sp-val">{{ $n }}</div><div class="sp-lab">{{ \Illuminate\Support\Str::of($roles[$role])->before(' (') }}</div></div>
            </div>
        @endforeach
    </div>

    {{-- Formulaire d'ajout (repliable) --}}
    <div class="collapse {{ $errors->any() ? 'show' : '' }} mb-4" id="addStaff">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-1"><i class="fas fa-user-plus me-2 text-primary"></i>Ajouter un membre</h5>
                <p class="text-muted small">Chaque membre reçoit ses <strong>propres identifiants</strong> et n'accède qu'à ce que son rôle permet. Vous ne partagez jamais votre mot de passe.</p>
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nom complet *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Email * <small class="text-muted">(identifiant)</small></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                        <div class="col-md-4"><label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                        <div class="col-md-4"><label class="form-label">Rôle *</label>
                            <select name="role" class="form-select" required>
                                <option value="">— Choisir —</option>
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-md-4"><label class="form-label">Mot de passe *</label>
                            <input type="text" name="password" class="form-control" value="{{ old('password') }}" required placeholder="8+ caractères, lettres et chiffres"></div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Créer le compte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Liste --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent d-flex align-items-center justify-content-between flex-wrap gap-2 border-0 pt-3">
            <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Mon équipe <span class="badge bg-light text-dark border ms-1">{{ $staff->count() }}</span></h5>
            <div class="position-relative" style="max-width:320px;">
                <i class="fas fa-search position-absolute text-muted" style="left:12px;top:50%;transform:translateY(-50%)"></i>
                <input type="text" id="staffSearch" class="form-control ps-5" placeholder="Rechercher un membre…">
            </div>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="staffTable">
                    <thead><tr><th style="width:44px">#</th><th>Membre</th><th>Rôle</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                    @forelse ($staff as $i => $m)
                        <tr class="staff-row">
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="sp-avatar" style="background:{{ $roleColors[$m->role][1] ?? '#eef2ff' }};color:{{ $roleColors[$m->role][0] ?? '#4f46e5' }}">{{ strtoupper(mb_substr($m->name,0,1)) }}</span>
                                    <div>
                                        <div class="fw-semibold">{{ $m->name }}</div>
                                        <div class="small text-muted">{{ $m->email }}@if($m->phone) · {{ $m->phone }}@endif</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge" style="background:{{ $roleColors[$m->role][1] ?? '#eef2ff' }};color:{{ $roleColors[$m->role][0] ?? '#4f46e5' }};font-weight:600">{{ $roles[$m->role] ?? $m->role }}</span></td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-secondary" title="Réinitialiser le mot de passe" onclick="document.getElementById('rp-{{ $m->id }}').classList.toggle('d-none')"><i class="fas fa-key"></i></button>
                                <form action="{{ route('staff.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer le compte de {{ $m->name }} ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr id="rp-{{ $m->id }}" class="d-none">
                            <td></td>
                            <td colspan="3">
                                <form action="{{ route('staff.reset', $m) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <input type="text" name="password" class="form-control form-control-sm" style="max-width:340px" placeholder="Nouveau mot de passe (8+, lettres + chiffres)" required>
                                    <button class="btn btn-sm btn-primary text-nowrap">Réinitialiser le mot de passe</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-5">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block opacity-50"></i>
                            Aucun membre pour l'instant. Cliquez sur <strong>« Nouveau membre »</strong> pour ajouter votre première recrue.
                        </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .sp-hero-ico{width:52px;height:52px;border-radius:14px;background:var(--hotel-primary,#16a34a);color:#fff;display:grid;place-items:center;font-size:1.3rem;}
    .sp-card{background:#fff;border:1px solid #eef0f4;border-radius:16px;padding:16px;height:100%;transition:transform .2s,border-color .2s;}
    .sp-card:hover{transform:translateY(-3px);border-color:var(--hotel-primary,#c7d2fe);}
    .sp-ico{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-size:1rem;margin-bottom:10px;}
    .sp-val{font-size:1.7rem;font-weight:800;line-height:1;}
    .sp-lab{font-size:.8rem;color:#64748b;margin-top:3px;}
    .sp-avatar{width:38px;height:38px;border-radius:10px;display:grid;place-items:center;font-weight:700;flex-shrink:0;}
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
                if(rp && rp.id && rp.id.startsWith('rp-') && !match) rp.classList.add('d-none');
            });
        });
    })();
</script>
@endsection
