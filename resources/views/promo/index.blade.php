@extends('template.master')

@section('title', __('promo.page_title'))

@section('content')
@php $fmt = fn ($n) => number_format((float) $n, 0, ',', ' '); @endphp
<style>
.promo-page{
  --card:#fff; --line:#e9edea; --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b; --tint:#f4f7f5;
  --acc:var(--g600,#2e8540); --acc-t:color-mix(in srgb,var(--g500,#2e8540) 13%,#fff);
  --ok:#1f7a3d; --ok-t:#e7f5ec; --bad:#b4342a; --bad-t:#fbe9e7; --r:14px; --sh:0 1px 2px rgba(20,40,30,.05);
  display:flex;flex-direction:column;gap:18px;color:var(--ink);
}
html[data-theme="dark"] .promo-page{
  --card:#161b18; --line:#28312b; --ink:#e8ede9; --ink2:#9aa39c; --ink3:#6b746d; --tint:#1b211d;
  --acc-t:color-mix(in srgb,var(--g500,#2e8540) 22%,#161b18); --ok-t:#12271a; --bad-t:#3a1e1b; --sh:0 1px 2px rgba(0,0,0,.3);
}
.promo-head h1{font-size:1.5rem;margin:0;display:flex;align-items:center;gap:12px}
.promo-head h1 .ic{width:40px;height:40px;border-radius:10px;background:var(--acc-t);color:var(--acc);display:grid;place-items:center;font-size:1.05rem}
.promo-head p{margin:6px 0 0;color:var(--ink2);font-size:.9rem}
.flash{background:var(--ok-t);border:1px solid color-mix(in srgb,var(--ok) 35%,transparent);color:var(--ok);border-radius:10px;padding:12px 16px;font-size:.9rem;font-weight:600}
.panel{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden}
.panel .hd{padding:15px 18px;border-bottom:1px solid var(--line);font-weight:800;display:flex;align-items:center;gap:9px}
.panel .bd{padding:18px}
.f-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;align-items:end}
@media(max-width:900px){.f-grid{grid-template-columns:repeat(2,1fr)}}
.f{display:flex;flex-direction:column;gap:6px}
.f label{font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:var(--ink3)}
.f input,.f select{padding:10px 12px;border:1.5px solid var(--line);border-radius:9px;background:var(--card);color:var(--ink);font-size:.9rem}
.f .err{color:var(--bad);font-size:.74rem}
.f-add{grid-column:1/-1;display:flex;justify-content:flex-end}
.btn{border:0;border-radius:10px;padding:11px 20px;font-weight:700;font-size:.9rem;cursor:pointer;background:var(--acc);color:#fff;display:inline-flex;align-items:center;gap:8px}
.btn:hover{filter:brightness(1.06)}
.ptable{width:100%;border-collapse:collapse;font-size:.9rem}
.ptable th{text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;color:var(--ink3);padding:9px 10px;border-bottom:1px solid var(--line)}
.ptable td{padding:11px 10px;border-bottom:1px solid var(--line);color:var(--ink2);vertical-align:middle}
.ptable tr:last-child td{border-bottom:0}
.code{font-family:ui-monospace,monospace;font-weight:800;color:var(--ink);background:var(--tint);padding:3px 9px;border-radius:7px}
.disc{font-weight:800;color:var(--acc)}
.pill{font-size:.72rem;font-weight:800;padding:4px 11px;border-radius:999px}
.pill.on{background:var(--ok-t);color:var(--ok)} .pill.off{background:var(--tint);color:var(--ink3)}
.act{display:inline-flex;gap:6px}
.iconbtn{border:1.5px solid var(--line);background:var(--card);color:var(--ink2);width:34px;height:34px;border-radius:9px;cursor:pointer;font-size:.85rem}
.iconbtn:hover{border-color:var(--acc);color:var(--acc)}
.iconbtn.danger:hover{border-color:var(--bad);color:var(--bad)}
.empty{color:var(--ink3);text-align:center;padding:26px;font-size:.9rem}
</style>

<div class="promo-page">
    <div class="promo-head">
        <h1><span class="ic"><i class="fas fa-tags"></i></span> {{ __('promo.heading') }}</h1>
        <p>{!! __('promo.description') !!}</p>
    </div>

    @if (session('success'))<div class="flash"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>@endif

    {{-- Création --}}
    <div class="panel">
        <div class="hd"><i class="fas fa-plus" style="color:var(--acc)"></i> {{ __('promo.new_code') }}</div>
        <div class="bd">
            <form method="POST" action="{{ route('promo.store') }}">
                @csrf
                <div class="f-grid">
                    <div class="f" style="grid-column:span 2">
                        <label>{{ __('promo.label_code') }}</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="BIENVENUE10" maxlength="40" required style="text-transform:uppercase">
                        @error('code')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_type') }}</label>
                        <select name="type" id="promoType">
                            <option value="percent" {{ old('type') === 'fixed' ? '' : 'selected' }}>{{ __('promo.type_percent') }}</option>
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>{{ __('promo.type_fixed') }}</option>
                        </select>
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_value') }} <span id="promoUnit">(%)</span></label>
                        <input type="number" name="value" value="{{ old('value') }}" step="0.01" min="0.01" placeholder="10" required>
                        @error('value')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_min_nights') }}</label>
                        <input type="number" name="min_nights" value="{{ old('min_nights', 1) }}" min="1" max="60">
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_max_uses') }}</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" placeholder="{{ __('promo.placeholder_unlimited') }}">
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_starts') }}</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at') }}">
                    </div>
                    <div class="f">
                        <label>{{ __('promo.label_ends') }}</label>
                        <input type="date" name="ends_at" value="{{ old('ends_at') }}">
                        @error('ends_at')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="f-add">
                        <button type="submit" class="btn"><i class="fas fa-plus"></i> {{ __('promo.btn_create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste --}}
    <div class="panel">
        <div class="hd"><i class="fas fa-list" style="color:var(--ink3)"></i> {{ __('promo.your_codes') }} ({{ $codes->count() }})</div>
        <div class="bd" style="overflow-x:auto">
            @if ($codes->isEmpty())
                <div class="empty">{{ __('promo.empty') }}</div>
            @else
                <table class="ptable">
                    <thead><tr>
                        <th>{{ __('promo.th_code') }}</th><th>{{ __('promo.th_discount') }}</th><th>{{ __('promo.th_conditions') }}</th><th>{{ __('promo.th_validity') }}</th><th>{{ __('promo.th_usage') }}</th><th>{{ __('promo.th_status') }}</th><th></th>
                    </tr></thead>
                    <tbody>
                    @foreach ($codes as $c)
                        <tr>
                            <td><span class="code">{{ $c->code }}</span></td>
                            <td><span class="disc">{{ $c->label() }}{{ $c->type === 'fixed' ? ' '.($currentHotel->currency ?? 'XOF') : '' }}</span></td>
                            <td>{{ $c->min_nights > 1 ? $c->min_nights.' '.__('promo.nights_min') : '-' }}</td>
                            <td>
                                @if ($c->starts_at || $c->ends_at)
                                    {{ $c->starts_at?->format('d/m/y') ?? '…' }} → {{ $c->ends_at?->format('d/m/y') ?? '…' }}
                                @else, @endif
                            </td>
                            <td>{{ $c->used_count }}{{ $c->max_uses ? ' / '.$c->max_uses : '' }}</td>
                            <td><span class="pill {{ $c->is_active ? 'on' : 'off' }}">{{ $c->is_active ? __('promo.active') : __('promo.inactive') }}</span></td>
                            <td>
                                <div class="act">
                                    <form method="POST" action="{{ route('promo.toggle', $c) }}">@csrf
                                        <button class="iconbtn" title="{{ $c->is_active ? __('promo.btn_deactivate') : __('promo.btn_activate') }}"><i class="fas fa-{{ $c->is_active ? 'pause' : 'play' }}"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('promo.destroy', $c) }}" onsubmit="return confirm('{{ __('promo.confirm_delete') }}')">@csrf @method('DELETE')
                                        <button class="iconbtn danger" title="{{ __('promo.btn_delete') }}"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    var t = document.getElementById('promoType'), u = document.getElementById('promoUnit');
    if (t && u) t.addEventListener('change', function () { u.textContent = t.value === 'fixed' ? '{{ __('promo.unit_fixed') }}' : '(%)'; });
})();
</script>
@endsection
