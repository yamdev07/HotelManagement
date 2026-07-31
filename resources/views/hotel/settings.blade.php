@extends('template.master')

@section('title', __('hotel-settings.page_title'))

@section('content')
<style>
/* ═══════════ Mon établissement — design épuré ═══════════ */
.settings-page {
  --card:#fff; --page:#f8faf9; --line:#e9edea; --line2:#dce2de;
  --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b; --tint:#f4f7f5;
  --bad:#b4342a; --bad-t:#fbe9e7;
  --acc: var(--g600, #2e8540);
  --acc2: var(--g500, #2e8540);
  --acc-t: color-mix(in srgb, var(--g500, #2e8540) 13%, var(--card));
  --r:12px; --r-sm:9px; --sh:0 1px 2px rgba(20,40,30,.05);
  display:flex; flex-direction:column; gap:18px;
  font-family:'DM Sans',system-ui,sans-serif; color:var(--ink);
  max-width:1080px;
}
html[data-theme="dark"] .settings-page {
  --card:#161b18; --page:#0f1311; --line:#262e29; --line2:#323b35;
  --ink:#e9eeeb; --ink2:#97a29b; --ink3:#6e7872; --tint:#1b211d;
  --bad:#e27469; --bad-t:#2a1714;
  --acc-t: color-mix(in srgb, var(--g500,#4fb268) 20%, var(--card));
}
.settings-page * { box-sizing:border-box; }

.set-head { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
.set-head h1 { margin:0; font-size:1.3rem; font-weight:680; letter-spacing:-.02em; display:flex; align-items:center; gap:10px; }
.set-head h1 .ic { width:38px; height:38px; border-radius:10px; background:var(--acc-t); color:var(--acc); display:grid; place-items:center; }
.btn-ghost, .btn-acc {
  display:inline-flex; align-items:center; gap:7px; border-radius:9px; padding:9px 15px;
  font-size:.82rem; font-weight:600; cursor:pointer; border:1px solid var(--line); text-decoration:none; font-family:inherit;
}
.btn-ghost { background:var(--card); color:var(--ink2); }
.btn-ghost:hover { border-color:var(--line2); color:var(--ink); }
.btn-acc { background:var(--acc); color:#fff; border-color:var(--acc); }
.btn-acc:hover { background:var(--acc2); color:#fff; }

.set-alert { border-radius:var(--r-sm); padding:12px 15px; font-size:.85rem; border:1px solid; }
.set-alert.ok  { background:var(--acc-t); border-color:color-mix(in srgb,var(--acc) 30%,var(--line)); color:var(--acc); }
.set-alert.err { background:var(--bad-t); border-color:color-mix(in srgb,var(--bad) 30%,var(--line)); color:var(--bad); }
.set-alert ul { margin:0; padding-left:18px; }

.panel { background:var(--card); border:1px solid var(--line); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; }
.panel-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 18px; border-bottom:1px solid var(--line); }
.panel-title { display:flex; align-items:center; gap:10px; font-size:.95rem; font-weight:660; }
.panel-title .ic { width:28px; height:28px; border-radius:8px; background:var(--acc-t); color:var(--acc); display:grid; place-items:center; font-size:.8rem; }
.panel-sub { font-size:.78rem; color:var(--ink3); font-weight:400; margin-top:2px; }
.panel-body { padding:18px; display:flex; flex-direction:column; gap:16px; }

.grid { display:grid; gap:14px; }
.g2 { grid-template-columns:1fr 1fr; } .g3 { grid-template-columns:repeat(3,1fr); }
@media(max-width:720px){ .g2,.g3 { grid-template-columns:1fr; } }
.field { display:flex; flex-direction:column; gap:6px; }
.field.col-span { grid-column:1/-1; }
.lbl { font-size:.75rem; font-weight:600; color:var(--ink2); }
.inp, .sel, textarea.inp {
  width:100%; padding:9px 12px; border:1px solid var(--line2); border-radius:8px;
  background:var(--card); color:var(--ink); font:inherit; font-size:.85rem; transition:border-color .15s, box-shadow .15s;
}
.inp:focus, .sel:focus, textarea.inp:focus { outline:none; border-color:var(--acc); box-shadow:0 0 0 3px var(--acc-t); }
.inp.is-invalid { border-color:var(--bad); }
.err-txt { font-size:.72rem; color:var(--bad); }
.hint { font-size:.72rem; color:var(--ink3); }

/* Apparence (light/dark/system) */
.appearance { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:560px){ .appearance { grid-template-columns:1fr; } }
.appear-opt {
  position:relative; display:flex; align-items:center; gap:11px; padding:13px 14px;
  border:1.5px solid var(--line2); border-radius:var(--r-sm); cursor:pointer; background:var(--card); transition:all .15s;
}
.appear-opt:hover { border-color:var(--acc); }
.appear-opt input { position:absolute; opacity:0; }
.appear-opt .ai { width:34px; height:34px; border-radius:9px; background:var(--tint); color:var(--ink2); display:grid; place-items:center; font-size:.95rem; }
.appear-opt .an { font-weight:640; font-size:.86rem; }
.appear-opt .ad { font-size:.72rem; color:var(--ink3); }
.appear-opt.on { border-color:var(--acc); background:var(--acc-t); }
.appear-opt.on .ai { background:var(--acc); color:#fff; }
.appear-opt.on .an { color:var(--acc); }
.appear-opt .check { margin-left:auto; width:9px; height:9px; border-radius:50%; background:var(--acc); opacity:0; }
.appear-opt.on .check { opacity:1; }

/* Palette presets — cartes étiquetées (comme le screenshot) */
.pal-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:560px){ .pal-grid { grid-template-columns:1fr 1fr; } }
.pal-card {
  position:relative; display:flex; align-items:center; gap:11px; padding:12px 14px;
  border:1.5px solid var(--line2); border-radius:var(--r-sm); cursor:pointer; background:var(--card); transition:all .15s;
}
.pal-card:hover { border-color:var(--acc); }
.pal-card .pdot { width:20px; height:20px; border-radius:50%; flex:none; box-shadow:0 0 0 1px rgba(0,0,0,.06) inset; }
.pal-card .pname { font-weight:640; font-size:.86rem; }
.pal-card .pcheck { margin-left:auto; width:9px; height:9px; border-radius:50%; background:var(--acc); opacity:0; }
.pal-card.on { border-color:var(--acc); background:var(--acc-t); }
.pal-card.on .pcheck { opacity:1; }

/* Background — cartes vignettes */
.bg-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
@media(max-width:900px){ .bg-grid { grid-template-columns:repeat(3,1fr); } }
@media(max-width:640px){ .bg-grid { grid-template-columns:1fr 1fr; } }
.bg-card {
  position:relative; border:1.5px solid var(--line2); border-radius:var(--r-sm); overflow:hidden;
  cursor:pointer; background:var(--card); transition:all .15s; padding:0;
}
.bg-card:hover { border-color:var(--acc); }
.bg-card .thumb { height:78px; background-size:cover; background-position:center; }
.bg-card .thumb.none { background:repeating-linear-gradient(45deg, var(--tint), var(--tint) 8px, var(--card) 8px, var(--card) 16px); }
.bg-card .cap { display:flex; align-items:center; justify-content:space-between; padding:8px 11px; font-size:.78rem; font-weight:600; }
.bg-card .cap .bgcheck { width:9px; height:9px; border-radius:50%; background:var(--acc); opacity:0; }
.bg-card.on { border-color:var(--acc); box-shadow:0 0 0 2px var(--acc-t); }
.bg-card.on .cap .bgcheck { opacity:1; }
.bg-import { display:inline-flex; align-items:center; gap:8px; margin-top:14px; }

.custom-color { display:flex; align-items:center; gap:10px; }
.custom-color input[type=color] { width:44px; height:40px; border:1px solid var(--line2); border-radius:9px; background:var(--card); cursor:pointer; padding:3px; }

/* logo / cover preview */
.media-box { border:1px dashed var(--line2); border-radius:var(--r-sm); background:var(--tint); min-height:130px; display:grid; place-items:center; padding:14px; }
.media-box img { max-height:110px; max-width:100%; border-radius:6px; }

/* switches */
.switches { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
@media(max-width:720px){ .switches { grid-template-columns:1fr 1fr; } }
.switch { display:flex; align-items:center; gap:10px; padding:11px 13px; border:1px solid var(--line); border-radius:var(--r-sm); }
.switch label { font-size:.82rem; font-weight:550; cursor:pointer; }
.toggle { position:relative; width:38px; height:22px; flex:none; }
.toggle input { position:absolute; opacity:0; width:100%; height:100%; margin:0; cursor:pointer; }
.toggle .track { position:absolute; inset:0; background:var(--line2); border-radius:20px; transition:background .15s; }
.toggle .track::before { content:""; position:absolute; top:3px; left:3px; width:16px; height:16px; border-radius:50%; background:var(--white, #fff); transition:transform .15s; }
.toggle input:checked + .track { background:var(--acc); }
.toggle input:checked + .track::before { transform:translateX(16px); }

/* service repeater */
.svc-row { display:grid; grid-template-columns:1fr 1fr 2fr auto; gap:10px; align-items:center; margin-bottom:10px; }
@media(max-width:720px){ .svc-row { grid-template-columns:1fr 1fr; } }
.icon-btn-sm { width:36px; height:36px; border-radius:8px; border:1px solid var(--line2); background:var(--card); color:var(--bad); cursor:pointer; display:grid; place-items:center; }
.icon-btn-sm:hover { background:var(--bad-t); }

/* danger zone */
.danger { border:1px solid color-mix(in srgb,var(--bad) 40%,var(--line)); border-radius:var(--r); overflow:hidden; }
.danger-head { background:var(--bad-t); color:var(--bad); padding:13px 18px; font-weight:660; display:flex; align-items:center; gap:9px; }
.danger-body { padding:18px; }
.btn-danger { background:var(--bad); color:#fff; border:0; border-radius:8px; padding:9px 15px; font-weight:600; font-size:.82rem; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:7px; }
.btn-danger-outline { background:var(--card); color:var(--bad); border:1px solid color-mix(in srgb,var(--bad) 40%,var(--line)); border-radius:8px; padding:9px 15px; font-weight:600; font-size:.82rem; cursor:pointer; font-family:inherit; }
</style>

<div class="settings-page">

    <div class="set-head">
        <h1><span class="ic"><i class="fas fa-palette"></i></span> {{ __('hotel-settings.header') }}</h1>
        <a href="{{ $hotel->publicUrl() }}" target="_blank" class="btn-ghost">
            <i class="fas fa-up-right-from-square"></i> {{ __('hotel-settings.view_site') }}
        </a>
    </div>

    @if (session('success'))
        <div class="set-alert ok"><i class="fas fa-check-circle me-1"></i> {{ session('success') }}</div>
    @endif
    @if ($errors->any() && !$errors->hasAny(['password', 'confirmation']))
        <div class="set-alert err"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('hotel.settings.update') }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:18px;">
        @csrf @method('PUT')

        {{-- ═══ APPARENCE & MARQUE ═══ --}}
        <section class="panel">
            <div class="panel-head">
                <div class="panel-title"><span class="ic"><i class="fas fa-swatchbook"></i></span>
                    <div>{{ __('hotel-settings.card_appearance') ?? 'Apparence & marque' }}
                        <div class="panel-sub">{{ __('hotel-settings.appearance_sub') ?? "Thème et couleur d'accent de votre espace." }}</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                {{-- Thème --}}
                <div class="field">
                    <span class="lbl">{{ __('hotel-settings.theme_label') ?? "Thème de l'interface" }}</span>
                    @php $tm = old('theme_mode', $hotel->themeMode()); @endphp
                    <div class="appearance">
                        @foreach ([['light','fa-sun','Clair','Fond clair'],['dark','fa-moon','Sombre','Fond sombre'],['system','fa-desktop','Système','Selon l\'appareil']] as $opt)
                            <label class="appear-opt {{ $tm === $opt[0] ? 'on' : '' }}" data-theme-opt="{{ $opt[0] }}">
                                <input type="radio" name="theme_mode" value="{{ $opt[0] }}" {{ $tm === $opt[0] ? 'checked' : '' }}>
                                <span class="ai"><i class="fas {{ $opt[1] }}"></i></span>
                                <span><span class="an">{{ $opt[2] }}</span><br><span class="ad">{{ $opt[3] }}</span></span>
                                <span class="check"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Palette d'accent --}}
                <div class="field">
                    <span class="lbl">{{ __('hotel-settings.label_primary_color') }}</span>
                    @php $pc = old('primary_color', $hotel->primaryColor()); @endphp
                    <div class="pal-grid">
                        @foreach ($palette as $pname => $phex)
                            <button type="button" class="pal-card {{ strtolower($pc) === strtolower($phex) ? 'on' : '' }}"
                                    data-color="{{ $phex }}" data-name="{{ $pname }}">
                                <span class="pdot" style="background:{{ $phex }}"></span>
                                <span class="pname">{{ $pname }}</span>
                                <span class="pcheck"></span>
                            </button>
                        @endforeach
                    </div>
                    <div class="custom-color" style="margin-top:14px;">
                        <input type="color" id="pc-color" name="primary_color" value="{{ $pc }}">
                        <input type="text" id="pc-text" class="inp @error('primary_color') is-invalid @enderror" value="{{ $pc }}" style="max-width:140px" readonly>
                        <span class="hint">{{ __('hotel-settings.label_primary_hint') }}</span>
                    </div>
                    @error('primary_color')<div class="err-txt">{{ $message }}</div>@enderror
                </div>

                {{-- Couleur secondaire (sidebar / fond marketing) --}}
                <div class="field" style="max-width:320px;">
                    <span class="lbl">{{ __('hotel-settings.label_secondary_color') }}</span>
                    <div class="custom-color">
                        <input type="color" name="secondary_color" value="{{ old('secondary_color', $hotel->secondaryColor()) }}"
                               oninput="document.getElementById('sc-text').value=this.value" style="width:44px;height:40px;border:1px solid var(--line2);border-radius:9px;padding:3px;cursor:pointer;background:var(--card);">
                        <input type="text" id="sc-text" class="inp" value="{{ old('secondary_color', $hotel->secondaryColor()) }}" style="max-width:140px" readonly>
                        <span class="hint">{{ __('hotel-settings.label_secondary_hint') }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ FOND (préférence locale par appareil) ═══ --}}
        @php $backgrounds = config('appearance.backgrounds'); @endphp
        <section class="panel">
            <div class="panel-head">
                <div class="panel-title"><span class="ic"><i class="fas fa-images"></i></span>
                    <div>{{ __('hotel-settings.card_background') ?? 'Fond' }}
                        <div class="panel-sub">Habillez votre espace d'un fond intégré ou de votre image. Un léger voile garde la lisibilité.</div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="bg-grid" id="bgGrid">
                    <button type="button" class="bg-card" data-bg="none">
                        <div class="thumb none"></div>
                        <div class="cap"><span>Aucun</span><span class="bgcheck"></span></div>
                    </button>
                    @foreach ($backgrounds as $key => $b)
                        <button type="button" class="bg-card" data-bg="{{ $key }}">
                            <div class="thumb" style="background:{{ $b['css'] }};background-size:cover;background-position:center;"></div>
                            <div class="cap"><span>{{ $b['label'] }}</span><span class="bgcheck"></span></div>
                        </button>
                    @endforeach
                    <button type="button" class="bg-card" data-bg="custom" id="bgCustomCard">
                        <div class="thumb" id="bgCustomThumb" style="background:linear-gradient(135deg,#c7d2fe,#a5b4fc)"></div>
                        <div class="cap"><span>Mon image</span><span class="bgcheck"></span></div>
                    </button>
                </div>
                <label class="btn-ghost bg-import" style="cursor:pointer">
                    <i class="fas fa-image"></i> Importer une image
                    <input type="file" id="bgFile" accept="image/*" hidden>
                </label>
                <div class="hint" style="margin-top:8px">L'image reste sur cet appareil (préférence locale, non envoyée au serveur).</div>
            </div>
        </section>

        {{-- ═══ IDENTITÉ & INFOS ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-circle-info"></i></span> {{ __('hotel-settings.card_info') }}</div></div>
            <div class="panel-body">
                <div class="grid g2">
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_name') }}</span>
                        <input type="text" name="name" class="inp @error('name') is-invalid @enderror" value="{{ old('name', $hotel->name) }}" maxlength="255" required>
                        @error('name')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_currency') }}</span>
                        @php $cur = old('currency', $hotel->currency); @endphp
                        <select name="currency" class="sel">
                            @unless (array_key_exists($cur, $currencies))
                                @if ($cur)<option value="{{ $cur }}" selected>{{ $cur }}</option>@endif
                            @endunless
                            @foreach ($currencies as $code => $label)
                                <option value="{{ $code }}" {{ $cur === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_email') }}</span>
                        <input type="email" name="contact_email" class="inp @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $hotel->contact_email) }}">
                        @error('contact_email')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_phone') }}</span>
                        <input type="tel" name="contact_phone" class="inp @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $hotel->contact_phone) }}" placeholder="+229 01 02 03 04" pattern="[0-9+\s().\-]{6,20}" maxlength="20">
                        @error('contact_phone')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field col-span">
                        <span class="lbl">{{ __('hotel-settings.label_address') }}</span>
                        <input type="text" name="address" class="inp" value="{{ old('address', $hotel->address) }}">
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ LOGO ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-image"></i></span> {{ __('hotel-settings.card_logo') }}</div></div>
            <div class="panel-body">
                <div class="grid g2" style="align-items:center;">
                    <div class="media-box">
                        @if ($hotel->logoUrl())
                            <img src="{{ $hotel->logoUrl() }}" alt="Logo">
                        @else
                            <span style="color:var(--ink3)"><i class="fas fa-hotel fa-3x"></i></span>
                        @endif
                    </div>
                    <div class="field">
                        <input type="file" name="logo" class="inp @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg,image/*">
                        <span class="hint">{{ __('hotel-settings.logo_formats') }}</span>
                        @error('logo')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ VITRINE PUBLIQUE ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-globe"></i></span> {{ __('hotel-settings.card_showcase') }}</div></div>
            <div class="panel-body">
                <div class="field">
                    <span class="lbl">{{ __('hotel-settings.label_tagline') }}</span>
                    <input type="text" name="tagline" class="inp @error('tagline') is-invalid @enderror" value="{{ old('tagline', $hotel->tagline) }}" placeholder="Ex : Votre confort, notre priorité">
                    @error('tagline')<div class="err-txt">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <span class="lbl">{{ __('hotel-settings.label_description') }}</span>
                    <textarea name="description" class="inp" rows="3" placeholder="Présentez votre établissement…">{{ old('description', $hotel->description) }}</textarea>
                </div>
                <div class="grid g2" style="align-items:center;">
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_cover') }}</span>
                        <input type="file" name="cover_image" class="inp" accept=".jpg,.jpeg,.png,.webp,image/*">
                        <span class="hint">{{ __('hotel-settings.cover_hint') }}</span>
                    </div>
                    <div class="media-box" style="min-height:90px;">
                        @if ($hotel->coverUrl())
                            <img src="{{ $hotel->coverUrl() }}" alt="Couverture">
                        @else
                            <span style="color:var(--ink3);font-size:.8rem">Aucune image de couverture</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ À PROPOS ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-circle-info"></i></span> {{ __('hotel-settings.card_about') }}</div></div>
            <div class="panel-body">
                <div class="grid g2">
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_about_title') }}</span>
                        <input type="text" name="about_title" class="inp @error('about_title') is-invalid @enderror" value="{{ old('about_title', $hotel->about_title) }}" placeholder="Une expérience d'exception">
                        @error('about_title')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <span class="lbl">{{ __('hotel-settings.label_about_text') }}</span>
                        <textarea name="about_text" class="inp" rows="2" placeholder="Décrivez votre établissement…">{{ old('about_text', $hotel->about_text) }}</textarea>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ SERVICES ═══ --}}
        <section class="panel">
            <div class="panel-head">
                <div class="panel-title"><span class="ic"><i class="fas fa-concierge-bell"></i></span> {{ __('hotel-settings.card_services') }}</div>
                <button type="button" class="btn-ghost" id="add-service"><i class="fas fa-plus"></i> {{ __('hotel-settings.btn_add') }}</button>
            </div>
            <div class="panel-body">
                <p class="hint" style="margin:0">{{ __('hotel-settings.services_hint') }}</p>
                <div id="services-list">
                    @php $svcRows = old('services', $hotel->services ?: []); @endphp
                    @foreach ($svcRows as $i => $svc)
                        <div class="svc-row">
                            <input type="text" name="services[{{ $i }}][icon]" class="inp" value="{{ $svc['icon'] ?? '' }}" placeholder="fa-star">
                            <input type="text" name="services[{{ $i }}][title]" class="inp" value="{{ $svc['title'] ?? '' }}" placeholder="Titre">
                            <input type="text" name="services[{{ $i }}][description]" class="inp" value="{{ $svc['description'] ?? '' }}" placeholder="Description">
                            <button type="button" class="icon-btn-sm remove-service"><i class="fas fa-trash"></i></button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ═══ RÉSEAUX SOCIAUX ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-share-nodes"></i></span> {{ __('hotel-settings.card_social') }}</div></div>
            <div class="panel-body">
                @php $soc = old('socials', $hotel->socials ?: []); @endphp
                <div class="grid g2">
                    <div class="field">
                        <span class="lbl"><i class="fab fa-facebook-f" style="color:#1877f2"></i> Facebook</span>
                        <input type="text" name="socials[facebook]" class="inp @error('socials.facebook') is-invalid @enderror" value="{{ $soc['facebook'] ?? '' }}" placeholder="https://facebook.com/…">
                        @error('socials.facebook')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <span class="lbl"><i class="fab fa-instagram" style="color:#e1306c"></i> Instagram</span>
                        <input type="text" name="socials[instagram]" class="inp @error('socials.instagram') is-invalid @enderror" value="{{ $soc['instagram'] ?? '' }}" placeholder="https://instagram.com/…">
                        @error('socials.instagram')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <span class="lbl"><i class="fab fa-whatsapp" style="color:#25d366"></i> WhatsApp</span>
                        <input type="text" name="socials[whatsapp]" class="inp" value="{{ $soc['whatsapp'] ?? '' }}" placeholder="https://wa.me/229…">
                    </div>
                    <div class="field">
                        <span class="lbl"><i class="fas fa-globe" style="color:var(--ink3)"></i> Site web</span>
                        <input type="text" name="socials[website]" class="inp @error('socials.website') is-invalid @enderror" value="{{ $soc['website'] ?? '' }}" placeholder="https://…">
                        @error('socials.website')<div class="err-txt">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ SECTIONS VITRINE (toggles) ═══ --}}
        <section class="panel">
            <div class="panel-head"><div class="panel-title"><span class="ic"><i class="fas fa-toggle-on"></i></span> {{ __('hotel-settings.card_sections') }}</div></div>
            <div class="panel-body">
                <div class="switches">
                    @php
                        $sections = [
                            'show_rooms'      => __('hotel-settings.section_rooms'),
                            'show_restaurant' => __('hotel-settings.section_restaurant'),
                            'show_services'   => __('hotel-settings.section_services'),
                            'show_contact'    => __('hotel-settings.section_contact'),
                        ];
                    @endphp
                    @foreach ($sections as $field => $label)
                        <div class="switch">
                            <span class="toggle">
                                <input type="hidden" name="{{ $field }}" value="0">
                                <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" {{ old($field, $hotel->$field) ? 'checked' : '' }}>
                                <span class="track"></span>
                            </span>
                            <label for="{{ $field }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div>
            <button type="submit" class="btn-acc"><i class="fas fa-save"></i> {{ __('hotel-settings.btn_save') }}</button>
        </div>
    </form>

    @if (auth()->user()->id === $hotel->owner_user_id)
        {{-- Zone de danger : clôture de l'établissement (issue #191) --}}
        <div class="danger">
            <div class="danger-head"><i class="fas fa-triangle-exclamation"></i> Zone de danger</div>
            <div class="danger-body">
                <h3 style="margin:0 0 6px;font-size:.95rem;font-weight:680;">Supprimer définitivement mon établissement</h3>
                <p style="color:var(--ink2);font-size:.85rem;margin:0 0 14px;">
                    Cette action est <strong>irréversible</strong>. Elle supprime votre établissement
                    « {{ $hotel->name }} » et <strong>toutes</strong> ses données (personnel, chambres, clients,
                    réservations, paiements, historique). Vos accès seront immédiatement révoqués.
                </p>
                @if ($errors->hasAny(['password', 'confirmation']))
                    <div class="set-alert err" style="margin-bottom:12px;">
                        <ul>
                            @foreach ($errors->get('password') as $e)<li>{{ $e }}</li>@endforeach
                            @foreach ($errors->get('confirmation') as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <button type="button" class="btn-danger-outline" onclick="document.getElementById('dangerDelete').style.display='block';this.style.display='none';">
                    <i class="fas fa-trash"></i> Supprimer mon établissement
                </button>
                <div id="dangerDelete" style="display:{{ $errors->hasAny(['password','confirmation']) ? 'block' : 'none' }};margin-top:14px;">
                    <form method="POST" action="{{ route('hotel.account.destroy') }}" style="border:1px solid var(--line);border-radius:var(--r-sm);padding:16px;max-width:420px;display:flex;flex-direction:column;gap:12px;">
                        @csrf @method('DELETE')
                        <div class="field">
                            <span class="lbl">Votre mot de passe</span>
                            <input type="password" name="password" class="inp" required autocomplete="current-password">
                        </div>
                        <div class="field">
                            <span class="lbl">Tapez <strong>SUPPRIMER</strong> pour confirmer</span>
                            <input type="text" name="confirmation" class="inp" placeholder="SUPPRIMER" required>
                        </div>
                        <div>
                            <button type="submit" class="btn-danger"
                                    onclick="return confirm('Dernière confirmation : supprimer définitivement « {{ $hotel->name }} » et toutes ses données ?');">
                                <i class="fas fa-trash"></i> Je supprime définitivement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
(function () {
    // ── Services (répéteur) ──
    const list = document.getElementById('services-list');
    let idx = list.querySelectorAll('.svc-row').length;
    const rowHtml = (i) => `
        <div class="svc-row">
            <input type="text" name="services[${i}][icon]" class="inp" placeholder="fa-star">
            <input type="text" name="services[${i}][title]" class="inp" placeholder="{{ __('hotel-settings.service_placeholder_title') }}">
            <input type="text" name="services[${i}][description]" class="inp" placeholder="{{ __('hotel-settings.service_placeholder_description') }}">
            <button type="button" class="icon-btn-sm remove-service"><i class="fas fa-trash"></i></button>
        </div>`;
    document.getElementById('add-service').addEventListener('click', () => list.insertAdjacentHTML('beforeend', rowHtml(idx++)));
    list.addEventListener('click', (e) => { if (e.target.closest('.remove-service')) e.target.closest('.svc-row').remove(); });

    // ── Apparence : aperçu live du thème ──
    document.querySelectorAll('.appear-opt').forEach(opt => {
        opt.addEventListener('click', () => {
            document.querySelectorAll('.appear-opt').forEach(o => o.classList.remove('on'));
            opt.classList.add('on');
            const mode = opt.getAttribute('data-theme-opt');
            try { localStorage.setItem('theme', mode); } catch (e) {}
            const resolved = mode === 'system'
                ? (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : mode;
            document.documentElement.setAttribute('data-theme', resolved);
        });
    });

    // ── Palette : aperçu live de l'accent (met à jour --hotel-primary → tout se recolore) ──
    const pcColor = document.getElementById('pc-color');
    const pcText  = document.getElementById('pc-text');
    function applyAccent(hex) {
        pcColor.value = hex; pcText.value = hex;
        document.documentElement.style.setProperty('--hotel-primary', hex);
        document.querySelectorAll('.pal-card').forEach(s =>
            s.classList.toggle('on', (s.getAttribute('data-color') || '').toLowerCase() === hex.toLowerCase()));
    }
    document.querySelectorAll('.pal-card').forEach(sw =>
        sw.addEventListener('click', () => applyAccent(sw.getAttribute('data-color'))));
    pcColor.addEventListener('input', () => applyAccent(pcColor.value));

    // ── Fond : préférence locale par appareil (aperçu live + persistance) ──
    const bgGrid = document.getElementById('bgGrid');
    function markBg(key) {
        bgGrid.querySelectorAll('.bg-card').forEach(c => c.classList.toggle('on', c.getAttribute('data-bg') === key));
    }
    // état initial
    try { markBg(localStorage.getItem('app-bg') || 'none'); } catch (e) { markBg('none'); }
    var customThumb = document.getElementById('bgCustomThumb');
    try {
        var savedImg = localStorage.getItem('app-bg-custom');
        if (savedImg) customThumb.style.backgroundImage = 'url(' + savedImg + ')';
    } catch (e) {}

    bgGrid.querySelectorAll('.bg-card').forEach(card => {
        card.addEventListener('click', function () {
            var key = this.getAttribute('data-bg');
            if (key === 'custom' && !(localStorage.getItem('app-bg-custom'))) {
                document.getElementById('bgFile').click();
                return;
            }
            try { localStorage.setItem('app-bg', key); } catch (e) {}
            markBg(key);
            if (window.applyAppBg) window.applyAppBg();
        });
    });
    document.getElementById('bgFile').addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (ev) {
            var url = ev.target.result;
            try { localStorage.setItem('app-bg-custom', url); localStorage.setItem('app-bg', 'custom'); } catch (err) {}
            customThumb.style.backgroundImage = 'url(' + url + ')';
            markBg('custom');
            if (window.applyAppBg) window.applyAppBg();
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endsection
