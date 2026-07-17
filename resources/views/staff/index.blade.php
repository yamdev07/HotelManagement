@extends('template.master')

@section('title', 'Personnel')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h3 class="mb-0"><i class="fas fa-user-tie me-2"></i> Mon personnel</h3>
        <span class="text-muted">{{ $staff->count() }} membre(s)</span>
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

    <div class="row g-4">
        {{-- Formulaire d'ajout --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-user-plus me-2 text-primary"></i>Ajouter un membre</h5>
                    <p class="text-muted small">Chaque membre reçoit ses <strong>propres identifiants</strong> et des droits limités à son rôle. Vous n'avez pas à partager votre mot de passe.</p>
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            <small class="text-muted">Servira d'identifiant de connexion.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle *</label>
                            <select name="role" class="form-select" required>
                                <option value="">— Choisir —</option>
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe *</label>
                            <input type="text" name="password" class="form-control" value="{{ old('password') }}" required
                                   placeholder="8+ caractères, lettres et chiffres">
                            <small class="text-muted">Communiquez-le au membre. Il pourra le changer.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Créer le compte</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-users me-2 text-primary"></i>Équipe</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Membre</th><th>Rôle</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                            @forelse ($staff as $m)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $m->name }}</div>
                                        <div class="small text-muted">{{ $m->email }}@if($m->phone) · {{ $m->phone }}@endif</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $roles[$m->role] ?? $m->role }}</span></td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-sm btn-outline-secondary" title="Réinitialiser le mot de passe"
                                                onclick="document.getElementById('rp-{{ $m->id }}').classList.toggle('d-none')">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <form action="{{ route('staff.destroy', $m) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer le compte de {{ $m->name }} ?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="rp-{{ $m->id }}" class="d-none">
                                    <td colspan="3">
                                        <form action="{{ route('staff.reset', $m) }}" method="POST" class="d-flex gap-2 align-items-center">
                                            @csrf
                                            <input type="text" name="password" class="form-control form-control-sm" placeholder="Nouveau mot de passe (8+, lettres+chiffres)" required>
                                            <button class="btn btn-sm btn-primary text-nowrap">Réinitialiser</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">Aucun membre pour l'instant. Ajoutez votre première recrue !</td></tr>
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
