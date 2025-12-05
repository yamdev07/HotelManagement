@extends('template.master')
@section('title', 'Mon Profil')

@section('content')
<div id="profile" class="fade-in">

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 text-gradient mb-1">Bonjour, {{ auth()->user()->name }} !</h1>
                    <p class="text-muted mb-0">Gérez vos informations personnelles et mot de passe ici.</p>
                </div>
                <div class="text-end">
                    <div class="text-muted small">{{ now()->format('l, F j, Y') }}</div>
                    <div class="fw-bold">{{ now()->format('g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Photo de profil --}}
        <div class="col-lg-4 mb-4">
            <div class="card card-lh text-center p-3">
                <img src="{{ $user->avatar ?? '/img/default.png' }}" class="rounded-circle mb-3" width="140" height="140">

                <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" class="form-control mb-2">
                    <button class="btn btn-hotel-primary w-100">Changer la photo</button>
                </form>
            </div>
        </div>

        {{-- Infos du profil --}}
        <div class="col-lg-8 mb-4">
            <div class="card card-lh p-3 mb-4">
                <h4>Informations du compte</h4>
                <p><strong>Rôle :</strong> {{ $user->role }}</p>

                <form action="{{ route('profile.update.info') }}" method="POST">
                    @csrf
                    <label>Nom complet</label>
                    <input type="text" name="name" class="form-control mb-2" value="{{ $user->name ?? '' }}">

                    <label>Email</label>
                    <input type="email" name="email" class="form-control mb-2" value="{{ $user->email ?? '' }}">

                    <label>Téléphone</label>
                    <input type="text" name="phone" class="form-control mb-2" value="{{ $user->phone ?? '' }}">

                    <button class="btn btn-hotel-success mt-2">Mettre à jour</button>
                </form>
            </div>

            {{-- Changer le mot de passe --}}
            <div class="card card-lh p-3">
                <h4>Changer le mot de passe</h4>

                <form action="{{ route('profile.update.password') }}" method="POST">
                    @csrf

                    <label>Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control mb-2">

                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control mb-2">

                    <label>Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control mb-2">

                    <button class="btn btn-hotel-warning mt-2">Modifier le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
