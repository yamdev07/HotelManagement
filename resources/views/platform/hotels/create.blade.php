@extends('platform.layout')

@section('title', 'Nouvel hôtel')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-plus me-2"></i> Nouvel hôtel</h3>
        <a href="{{ route('platform.hotels.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('platform.hotels.store') }}" method="POST">
                @csrf

                <h5 class="mb-3">Établissement</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom de l'hôtel *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Devise</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', 'CFA') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fin d'abonnement</label>
                        <input type="date" name="subscription_ends_at" class="form-control" value="{{ old('subscription_ends_at') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email de contact</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Administrateur de l'hôtel</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="admin_name" class="form-control" value="{{ old('admin_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email *</label>
                        <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mot de passe *</label>
                        <input type="text" name="admin_password" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Créer l'hôtel
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
