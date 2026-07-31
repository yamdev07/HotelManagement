@extends('template.master')

@section('title', __('billing.page_title'))

@section('content')
@php
    $expired = $hotel->isSubscriptionExpired();
    $daysLeft = $hotel->subscription_ends_at ? now()->startOfDay()->diffInDays($hotel->subscription_ends_at->startOfDay(), false) : null;
    $currency = $hotel->displayCurrency();
    $fmt = fn ($n) => number_format($n, 0, ',', ' ');
    $statusKey = ! $hotel->is_active ? 'suspended' : ($expired ? 'expired' : 'active');
@endphp
<style>
.bill-page{
  --card:#fff; --page:#f8faf9; --line:#e9edea; --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b;
  --tint:#f4f7f5; --acc:var(--g600,#2e8540); --acc2:var(--g500,#2e8540);
  --acc-t:color-mix(in srgb,var(--g500,#2e8540) 12%,#fff);
  --ok:#1f7a3d; --ok-t:#e7f5ec; --bad:#b4342a; --bad-t:#fbe9e7; --warn:#9a6a00; --warn-t:#fdf3e0;
  --r:14px; --r-sm:10px; --sh:0 1px 2px rgba(20,40,30,.05); display:flex;flex-direction:column;gap:18px;color:var(--ink);
}
html[data-theme="dark"] .bill-page{
  --card:#161b18; --page:#0f1311; --line:#28312b; --ink:#e8ede9; --ink2:#9aa39c; --ink3:#6b746d;
  --tint:#1b211d; --acc-t:color-mix(in srgb,var(--g500,#2e8540) 22%,#161b18);
  --ok-t:#12271a; --bad-t:#3a1e1b; --warn-t:#2c2410; --sh:0 1px 2px rgba(0,0,0,.3);
}
.bill-head{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
.bill-head h1{font-size:1.5rem;margin:0;display:flex;align-items:center;gap:12px}
.bill-head h1 .ic{width:40px;height:40px;border-radius:10px;background:var(--acc-t);color:var(--acc);display:grid;place-items:center;font-size:1.05rem}
.pill{font-size:.8rem;font-weight:800;padding:6px 14px;border-radius:999px;display:inline-flex;align-items:center;gap:7px}
.pill.active{background:var(--ok-t);color:var(--ok)} .pill.expired{background:var(--bad-t);color:var(--bad)} .pill.suspended{background:var(--tint);color:var(--ink2)}
.flash{border-radius:10px;padding:12px 16px;font-size:.9rem;font-weight:600;display:flex;align-items:center;gap:9px}
.flash.ok{background:var(--ok-t);color:var(--ok)} .flash.err{background:var(--bad-t);color:var(--bad)}
.notice{border-radius:12px;padding:14px 18px;font-size:.92rem;display:flex;gap:11px;align-items:flex-start}
.notice.warn{background:var(--warn-t);color:var(--warn)} .notice.info{background:var(--acc-t);color:var(--acc)}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
@media(max-width:720px){.stats{grid-template-columns:1fr}}
.stat{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);padding:16px 18px}
.stat .lbl{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--ink3)}
.stat .val{font-size:1.5rem;font-weight:800;margin-top:6px;line-height:1.1}
.stat .sub{font-size:.82rem;color:var(--ink2);margin-top:3px}
.stat .sub.bad{color:var(--bad)}
.panel{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden}
.panel .hd{padding:15px 18px;border-bottom:1px solid var(--line);font-weight:800;display:flex;align-items:center;gap:9px}
.panel .hd small{font-weight:500;color:var(--ink2);margin-left:auto;font-size:.82rem}
.panel .bd{padding:18px}
.plans{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
@media(max-width:820px){.plans{grid-template-columns:1fr}}
.plan{position:relative;border:1.5px solid var(--line);border-radius:var(--r-sm);padding:16px;cursor:pointer;transition:border-color .15s,box-shadow .15s;background:var(--card);display:flex;flex-direction:column;gap:8px}
.plan:hover{border-color:var(--acc2)}
.plan.selected{border-color:var(--acc);box-shadow:0 0 0 3px var(--acc-t)}
.plan input{position:absolute;opacity:0;pointer-events:none}
.plan .top{display:flex;align-items:center;justify-content:space-between;gap:8px}
.plan .nm{font-weight:800;font-size:1.05rem;display:flex;align-items:center;gap:8px}
.plan .nm .dot{width:16px;height:16px;border-radius:50%;border:2px solid var(--line);flex:none}
.plan.selected .nm .dot{border-color:var(--acc);background:radial-gradient(circle,var(--acc) 45%,transparent 50%)}
.plan .tag{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;padding:3px 9px;border-radius:999px;background:var(--acc-t);color:var(--acc)}
.plan .tag.cur{background:var(--tint);color:var(--ink2)}
.plan .price{font-size:1.35rem;font-weight:900} .plan .price small{font-size:.72rem;font-weight:600;color:var(--ink3)}
.plan .desc{font-size:.82rem;color:var(--ink2)}
.plan ul{list-style:none;margin:6px 0 0;padding:0;display:flex;flex-direction:column;gap:5px}
.plan ul li{font-size:.82rem;color:var(--ink2);display:flex;gap:8px;align-items:flex-start}
.plan ul li i{color:var(--acc);margin-top:3px;font-size:.72rem}
.pay-row{display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;margin-top:18px;padding-top:18px;border-top:1px solid var(--line)}
.pay-field{display:flex;flex-direction:column;gap:6px}
.pay-field label{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:var(--ink3)}
.pay-field select{padding:11px 13px;border:1.5px solid var(--line);border-radius:10px;background:var(--card);color:var(--ink);font-size:.95rem;min-width:150px}
.pay-total{margin-left:auto;text-align:right}
.pay-total .l{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:var(--ink3)}
.pay-total .v{font-size:1.7rem;font-weight:900;color:var(--acc)}
.pay-submit{border:0;border-radius:11px;background:var(--acc);color:#fff;padding:14px 26px;font-weight:800;font-size:1rem;cursor:pointer;display:inline-flex;align-items:center;gap:9px;white-space:nowrap}
.pay-submit:hover{filter:brightness(1.06)}
.htable{width:100%;border-collapse:collapse;font-size:.88rem}
.htable th{text-align:left;font-size:.7rem;text-transform:uppercase;letter-spacing:.04em;color:var(--ink3);padding:8px 10px;border-bottom:1px solid var(--line)}
.htable td{padding:10px;border-bottom:1px solid var(--line);color:var(--ink2)}
.htable tr:last-child td{border-bottom:0}
.htable .amt{text-align:right;font-variant-numeric:tabular-nums;color:var(--ink);font-weight:700}
.tagm{font-size:.68rem;font-weight:800;padding:2px 9px;border-radius:999px}
.tagm.trial{background:var(--acc-t);color:var(--acc)} .tagm.renew{background:var(--ok-t);color:var(--ok)} .tagm.new{background:var(--tint);color:var(--ink2)}
</style>

<div class="bill-page">
    <div class="bill-head">
        <h1><span class="ic"><i class="fas fa-credit-card"></i></span> {{ __('billing.header') }}</h1>
        <span class="pill {{ $statusKey }}">
            <i class="fas fa-{{ $statusKey === 'active' ? 'circle-check' : ($statusKey === 'expired' ? 'circle-exclamation' : 'lock') }}"></i>
            {{ ! $hotel->is_active ? __('billing.status_suspended') : ($expired ? __('billing.status_expired') : __('billing.status_active')) }}
        </span>
    </div>

    @if (session('success'))
        <div class="flash ok"><i class="fas fa-circle-check"></i>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash err"><i class="fas fa-triangle-exclamation"></i>{{ session('error') }}</div>
    @endif

    {{-- État courant --}}
    <div class="stats">
        <div class="stat">
            <div class="lbl">{{ __('billing.card_current_plan') }}</div>
            <div class="val">{{ $hotel->planName() }}</div>
            <div class="sub">{{ $fmt($hotel->monthlyPrice()) }} {{ $currency }} / mois · {{ $hotel->countryName() }}</div>
        </div>
        <div class="stat">
            <div class="lbl">{{ __('billing.card_due_date') }}</div>
            <div class="val">{{ $hotel->subscription_ends_at?->format('d/m/Y') ?? '—' }}</div>
            <div class="sub {{ $expired ? 'bad' : '' }}">
                @if ($expired) {{ __('billing.card_expired') }}
                @elseif ($daysLeft !== null) {{ __('billing.card_days_left', ['count' => $daysLeft]) }} @endif
            </div>
        </div>
        <div class="stat">
            <div class="lbl">{{ __('billing.card_total_paid') }}</div>
            <div class="val">{{ $fmt($hotel->totalPaid()) }} {{ $currency }}</div>
            <div class="sub">{{ __('billing.card_renewals', ['count' => $hotel->renewalsCount()]) }}</div>
        </div>
    </div>

    @if ($suspendedByAdmin)
        <div class="notice warn">
            <i class="fas fa-lock" style="margin-top:2px"></i>
            <div>{{ __('billing.alert_suspended') }}
                @if ($hotel->suspension_reason)<strong>{{ __('billing.alert_suspended_reason') }} {{ $hotel->suspension_reason }}.</strong>@endif
                {{ __('billing.alert_suspended_text') }}</div>
        </div>
    @elseif (! $configured)
        <div class="notice info">
            <i class="fas fa-circle-info" style="margin-top:2px"></i>
            <div>{{ __('billing.alert_not_configured') }}</div>
        </div>
    @else
        {{-- Choix de formule + paiement --}}
        <div class="panel">
            <div class="hd"><i class="fas fa-arrows-rotate" style="color:var(--acc)"></i> {{ __('billing.form_title') }}
                <small>{{ __('billing.form_desc', ['country' => $hotel->countryName()]) }}</small>
            </div>
            <div class="bd">
                <form action="{{ route('billing.checkout') }}" method="POST" id="billForm">
                    @csrf
                    <div class="plans">
                        @php $taglines = ['starter' => __('flash.plan_starter_tagline'), 'pro' => __('flash.plan_pro_tagline'), 'business' => __('flash.plan_business_tagline')]; @endphp
                        @foreach ($tiers as $key => $tier)
                            @php
                                $price = \App\Models\Hotel::priceFor($key, $hotel->country);
                                $isCurrent = $hotel->plan === $key;
                                $features = is_array($tier['features'] ?? null) ? array_slice($tier['features'], 0, 4) : [];
                            @endphp
                            <label class="plan {{ $isCurrent ? 'selected' : '' }}" data-price="{{ $price }}" data-key="{{ $key }}">
                                <input type="radio" name="plan" value="{{ $key }}" {{ $isCurrent ? 'checked' : '' }} required>
                                <div class="top">
                                    <span class="nm"><span class="dot"></span>{{ $tier['name'] }}</span>
                                    @if ($isCurrent)<span class="tag cur">{{ __('billing.card_current_plan') }}</span>
                                    @elseif (! empty($tier['popular']))<span class="tag">{{ __('billing.form_popular') }}</span>@endif
                                </div>
                                <div class="price">{{ $fmt($price) }} <small>{{ $currency }}{{ __('billing.form_per_month') }}</small></div>
                                <div class="desc">{{ $taglines[$key] ?? ($tier['tagline'] ?? '') }}</div>
                                @if ($features)
                                    <ul>@foreach ($features as $f)<li><i class="fas fa-check"></i>{{ $f }}</li>@endforeach</ul>
                                @endif
                            </label>
                        @endforeach
                    </div>

                    <div class="pay-row">
                        <div class="pay-field">
                            <label>{{ __('billing.form_duration') }}</label>
                            <select name="months" id="billMonths">
                                @foreach ($months as $m)
                                    <option value="{{ $m }}">{{ __('billing.form_months', ['count' => $m]) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pay-total">
                            <div class="l">Total</div>
                            <div class="v"><span id="billTotal">{{ $fmt($hotel->monthlyPrice()) }}</span> {{ $currency }}</div>
                        </div>
                        <button type="submit" class="pay-submit"><i class="fas fa-lock"></i> <span id="billBtnLabel">{{ __('billing.form_submit') }}</span></button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Historique --}}
    @if ($hotel->subscriptions->count())
        <div class="panel">
            <div class="hd"><i class="fas fa-clock-rotate-left" style="color:var(--ink3)"></i> {{ __('billing.history_title') }}</div>
            <div class="bd" style="overflow-x:auto">
                <table class="htable">
                    <thead><tr>
                        <th>{{ __('billing.history_date') }}</th><th>{{ __('billing.history_plan') }}</th>
                        <th>{{ __('billing.history_type') }}</th><th class="amt">{{ __('billing.history_amount') }}</th>
                        <th>{{ __('billing.history_until') }}</th>
                    </tr></thead>
                    <tbody>
                    @foreach ($hotel->subscriptions as $s)
                        <tr>
                            <td>{{ $s->starts_at?->format('d/m/Y') }}</td>
                            <td style="color:var(--ink);font-weight:600">{{ ucfirst($s->plan) }}</td>
                            <td>
                                @if ($s->status === 'trial')<span class="tagm trial">{{ __('billing.history_trial') }}</span>
                                @elseif ($s->is_renewal)<span class="tagm renew">{{ __('billing.history_renewal') }}</span>
                                @else<span class="tagm new">{{ __('billing.history_subscription') }}</span>@endif
                            </td>
                            <td class="amt">{{ $fmt($s->amount) }} {{ $s->currency }}</td>
                            <td>{{ $s->ends_at?->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
(function () {
    var form = document.getElementById('billForm');
    if (!form) return;
    var plans = Array.prototype.slice.call(form.querySelectorAll('.plan'));
    var monthsSel = document.getElementById('billMonths');
    var totalEl = document.getElementById('billTotal');
    var btnLabel = document.getElementById('billBtnLabel');
    var currentKey = @json($hotel->plan);
    var LBL_RENEW = @json(__('billing.form_submit'));
    var LBL_CHANGE = @json(__('billing.form_change') ?? 'Changer de formule');

    function fmt(n){ return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' '); }

    function selected(){ return plans.find(function(p){ return p.querySelector('input').checked; }); }

    function refresh(){
        var p = selected();
        plans.forEach(function(x){ x.classList.toggle('selected', x === p); });
        if (!p) return;
        var price = parseInt(p.getAttribute('data-price'), 10) || 0;
        var months = parseInt(monthsSel.value, 10) || 1;
        totalEl.textContent = fmt(price * months);
        btnLabel.textContent = (p.getAttribute('data-key') === currentKey) ? LBL_RENEW : LBL_CHANGE;
    }

    plans.forEach(function(p){ p.addEventListener('click', function(){ p.querySelector('input').checked = true; refresh(); }); });
    monthsSel.addEventListener('change', refresh);
    refresh();
})();
</script>
@endsection
