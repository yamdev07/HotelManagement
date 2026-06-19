@extends('template.master')

@section('title', 'Mon établissement')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-palette me-2"></i> Personnalisation de l'établissement</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('hotel.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">
            {{-- Identité & infos --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-circle-info me-2"></i>Informations</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nom de l'établissement *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Devise</label>
                                <input type="text" name="currency" class="form-control" value="{{ old('currency', $hotel->currency) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email de contact</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $hotel->contact_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $hotel->contact_phone) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Adresse</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $hotel->address) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Couleurs --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-palette me-2"></i>Couleurs de la marque</div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Couleur principale</label>
                                <div class="input-group">
                                    <input type="color" name="primary_color" class="form-control form-control-color"
                                           value="{{ old('primary_color', $hotel->primaryColor()) }}"
                                           oninput="document.getElementById('pc').value=this.value">
                                    <input type="text" id="pc" class="form-control" value="{{ old('primary_color', $hotel->primaryColor()) }}" readonly>
                                </div>
                                <small class="text-muted">Boutons, liens et accents.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Couleur secondaire</label>
                                <div class="input-group">
                                    <input type="color" name="secondary_color" class="form-control form-control-color"
                                           value="{{ old('secondary_color', $hotel->secondaryColor()) }}"
                                           oninput="document.getElementById('sc').value=this.value">
                                    <input type="text" id="sc" class="form-control" value="{{ old('secondary_color', $hotel->secondaryColor()) }}" readonly>
                                </div>
                                <small class="text-muted">Fond de la barre latérale.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-image me-2"></i>Logo</div>
                    <div class="card-body text-center">
                        <div class="mb-3 p-4 rounded-3 bg-light d-flex align-items-center justify-content-center" style="min-height:140px;">
                            @if ($hotel->logoUrl())
                                <img src="{{ $hotel->logoUrl() }}" alt="Logo" style="max-height:110px; max-width:100%;">
                            @else
                                <span class="text-muted"><i class="fas fa-hotel fa-3x"></i></span>
                            @endif
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-2">PNG, JPG, SVG ou WEBP — max 2 Mo.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu de la vitrine publique --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-globe me-2"></i>Contenu de la vitrine</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Slogan</label>
                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $hotel->tagline) }}"
                               placeholder="Ex : Votre confort, notre priorité">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Présentez votre établissement…">{{ old('description', $hotel->description) }}</textarea>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Image de couverture</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <small class="text-muted">Affichée en bandeau de la vitrine — max 4 Mo.</small>
                    </div>
                    <div class="col-md-5">
                        @if ($hotel->coverUrl())
                            <img src="{{ $hotel->coverUrl() }}" alt="Couverture" class="img-fluid rounded-3" style="max-height:90px;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
        </div>
    </form>
</div>
@endsection
