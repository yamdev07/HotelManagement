@extends('platform.layout')

@section('title', 'Modifier ' . $hotel->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-pen me-2"></i> {{ $hotel->name }}</h3>
        <a href="{{ route('platform.hotels.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('platform.hotels.update', $hotel) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom de l'hôtel *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Devise</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $hotel->currency) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fin d'abonnement</label>
                        <input type="date" name="subscription_ends_at" class="form-control"
                               value="{{ old('subscription_ends_at', optional($hotel->subscription_ends_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email de contact</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $hotel->contact_email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $hotel->contact_phone) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
