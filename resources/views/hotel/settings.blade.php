@extends('template.master')

@section('title', __('hotel-settings.page_title'))

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h3 class="mb-0"><i class="fas fa-palette me-2"></i> {{ __('hotel-settings.header') }}</h3>
        <a href="{{ $hotel->publicUrl() }}" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-up-right-from-square me-1"></i> {{ __('hotel-settings.view_site') }}
        </a>
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
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-circle-info me-2"></i>{{ __('hotel-settings.card_info') }}</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">{{ __('hotel-settings.label_name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('hotel-settings.label_currency') }}</label>
                                <input type="text" name="currency" class="form-control" value="{{ old('currency', $hotel->currency) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('hotel-settings.label_email') }}</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $hotel->contact_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('hotel-settings.label_phone') }}</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $hotel->contact_phone) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('hotel-settings.label_address') }}</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $hotel->address) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Couleurs --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-palette me-2"></i>{{ __('hotel-settings.card_colors') }}</div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('hotel-settings.label_primary_color') }}</label>
                                <div class="input-group">
                                    <input type="color" name="primary_color" class="form-control form-control-color"
                                           value="{{ old('primary_color', $hotel->primaryColor()) }}"
                                           oninput="document.getElementById('pc').value=this.value">
                                    <input type="text" id="pc" class="form-control" value="{{ old('primary_color', $hotel->primaryColor()) }}" readonly>
                                </div>
                                <small class="text-muted">{{ __('hotel-settings.label_primary_hint') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('hotel-settings.label_secondary_color') }}</label>
                                <div class="input-group">
                                    <input type="color" name="secondary_color" class="form-control form-control-color"
                                           value="{{ old('secondary_color', $hotel->secondaryColor()) }}"
                                           oninput="document.getElementById('sc').value=this.value">
                                    <input type="text" id="sc" class="form-control" value="{{ old('secondary_color', $hotel->secondaryColor()) }}" readonly>
                                </div>
                                <small class="text-muted">{{ __('hotel-settings.label_secondary_hint') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold"><i class="fas fa-image me-2"></i>{{ __('hotel-settings.card_logo') }}</div>
                    <div class="card-body text-center">
                        <div class="mb-3 p-4 rounded-3 bg-light d-flex align-items-center justify-content-center" style="min-height:140px;">
                            @if ($hotel->logoUrl())
                                <img src="{{ $hotel->logoUrl() }}" alt="Logo" style="max-height:110px; max-width:100%;">
                            @else
                                <span class="text-muted"><i class="fas fa-hotel fa-3x"></i></span>
                            @endif
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-2">{{ __('hotel-settings.logo_formats') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu de la vitrine publique --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-globe me-2"></i>{{ __('hotel-settings.card_showcase') }}</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('hotel-settings.label_tagline') }}</label>
                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $hotel->tagline) }}"
                               placeholder="Ex : Votre confort, notre priorité">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('hotel-settings.label_description') }}</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Présentez votre établissement…">{{ old('description', $hotel->description) }}</textarea>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">{{ __('hotel-settings.label_cover') }}</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <small class="text-muted">{{ __('hotel-settings.cover_hint') }}</small>
                    </div>
                    <div class="col-md-5">
                        @if ($hotel->coverUrl())
                            <img src="{{ $hotel->coverUrl() }}" alt="Couverture" class="img-fluid rounded-3" style="max-height:90px;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bloc "À propos" --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-circle-info me-2"></i>{{ __('hotel-settings.card_about') }}</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">{{ __('hotel-settings.label_about_title') }}</label>
                        <input type="text" name="about_title" class="form-control" value="{{ old('about_title', $hotel->about_title) }}" placeholder="Une expérience d'exception">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">{{ __('hotel-settings.label_about_text') }}</label>
                        <textarea name="about_text" class="form-control" rows="2" placeholder="Décrivez votre établissement…">{{ old('about_text', $hotel->about_text) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Services personnalisés (répéteur dynamique) --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-concierge-bell me-2"></i>{{ __('hotel-settings.card_services') }}</span>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-service"><i class="fas fa-plus me-1"></i>{{ __('hotel-settings.btn_add') }}</button>
            </div>
            <div class="card-body">
                <p class="text-muted small">{{ __('hotel-settings.services_hint') }}</p>
                <div id="services-list">
                    @php $svcRows = old('services', $hotel->services ?: []); @endphp
                    @forelse ($svcRows as $i => $svc)
                        <div class="row g-2 mb-2 align-items-center service-row">
                            <div class="col-md-3"><input type="text" name="services[{{ $i }}][icon]" class="form-control" value="{{ $svc['icon'] ?? '' }}" placeholder="fa-star"></div>
                            <div class="col-md-3"><input type="text" name="services[{{ $i }}][title]" class="form-control" value="{{ $svc['title'] ?? '' }}" placeholder="Titre"></div>
                            <div class="col-md-5"><input type="text" name="services[{{ $i }}][description]" class="form-control" value="{{ $svc['description'] ?? '' }}" placeholder="Description"></div>
                            <div class="col-md-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger remove-service"><i class="fas fa-trash"></i></button></div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Réseaux sociaux --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-share-nodes me-2"></i>{{ __('hotel-settings.card_social') }}</div>
            <div class="card-body">
                <div class="row g-3">
                    @php $soc = old('socials', $hotel->socials ?: []); @endphp
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-facebook-f me-2 text-primary"></i>Facebook</label>
                        <input type="text" name="socials[facebook]" class="form-control" value="{{ $soc['facebook'] ?? '' }}" placeholder="https://facebook.com/…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-instagram me-2 text-danger"></i>Instagram</label>
                        <input type="text" name="socials[instagram]" class="form-control" value="{{ $soc['instagram'] ?? '' }}" placeholder="https://instagram.com/…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-whatsapp me-2 text-success"></i>WhatsApp</label>
                        <input type="text" name="socials[whatsapp]" class="form-control" value="{{ $soc['whatsapp'] ?? '' }}" placeholder="https://wa.me/229…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-globe me-2 text-secondary"></i>Site web</label>
                        <input type="text" name="socials[website]" class="form-control" value="{{ $soc['website'] ?? '' }}" placeholder="https://…">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sections affichées sur la vitrine --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-toggle-on me-2"></i>{{ __('hotel-settings.card_sections') }}</div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $sections = [
                            'show_rooms'      => __('hotel-settings.section_rooms'),
                            'show_restaurant' => __('hotel-settings.section_restaurant'),
                            'show_services'   => __('hotel-settings.section_services'),
                            'show_contact'    => __('hotel-settings.section_contact'),
                        ];
                    @endphp
                    @foreach ($sections as $field => $label)
                        <div class="col-md-3 col-6">
                            <div class="form-check form-switch">
                                <input type="hidden" name="{{ $field }}" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="{{ $field }}" name="{{ $field }}" value="1"
                                       {{ old($field, $hotel->$field) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> {{ __('hotel-settings.btn_save') }}</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const list = document.getElementById('services-list');
        let idx = list.querySelectorAll('.service-row').length;

        const rowHtml = (i) => `
            <div class="row g-2 mb-2 align-items-center service-row">
                <div class="col-md-3"><input type="text" name="services[${i}][icon]" class="form-control" placeholder="fa-star"></div>
                <div class="col-md-3"><input type="text" name="services[${i}][title]" class="form-control" placeholder="{{ __('hotel-settings.service_placeholder_title') }}"></div>
                <div class="col-md-5"><input type="text" name="services[${i}][description]" class="form-control" placeholder="{{ __('hotel-settings.service_placeholder_description') }}"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger remove-service"><i class="fas fa-trash"></i></button></div>
            </div>`;

        document.getElementById('add-service').addEventListener('click', () => {
            list.insertAdjacentHTML('beforeend', rowHtml(idx++));
        });
        list.addEventListener('click', (e) => {
            if (e.target.closest('.remove-service')) e.target.closest('.service-row').remove();
        });
    })();
</script>
@endsection
