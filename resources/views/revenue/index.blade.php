@extends('template.master')

@section('title', 'Revenus')

@section('content')
@php $fmt = fn ($n) => number_format((float) $n, 0, ',', ' '); @endphp
<style>
.rev-page{
  --card:#fff; --page:#f8faf9; --line:#e9edea; --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b;
  --tint:#f4f7f5; --acc:var(--g600,#2e8540); --acc-t:color-mix(in srgb,var(--g500,#2e8540) 13%,#fff);
  --r:14px; --sh:0 1px 2px rgba(20,40,30,.05); display:flex;flex-direction:column;gap:18px;color:var(--ink);
}
html[data-theme="dark"] .rev-page{
  --card:#161b18; --page:#0f1311; --line:#28312b; --ink:#e8ede9; --ink2:#9aa39c; --ink3:#6b746d;
  --tint:#1b211d; --acc-t:color-mix(in srgb,var(--g500,#2e8540) 22%,#161b18); --sh:0 1px 2px rgba(0,0,0,.3);
}
.rev-head h1{font-size:1.5rem;margin:0;display:flex;align-items:center;gap:12px}
.rev-head h1 .ic{width:40px;height:40px;border-radius:10px;background:var(--acc-t);color:var(--acc);display:grid;place-items:center;font-size:1.05rem}
.rev-head p{margin:6px 0 0;color:var(--ink2);font-size:.9rem}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:900px){.stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:520px){.stats{grid-template-columns:1fr}}
.stat{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);padding:16px 18px}
.stat .lbl{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--ink3);display:flex;align-items:center;gap:7px}
.stat .val{font-size:1.55rem;font-weight:800;margin-top:8px;line-height:1.05;font-variant-numeric:tabular-nums}
.stat .val small{font-size:.85rem;font-weight:600;color:var(--ink3)}
.stat .sub{font-size:.8rem;color:var(--ink2);margin-top:3px}
.stat.accent{background:linear-gradient(135deg,var(--acc),color-mix(in srgb,var(--acc) 70%,#000));border:0;color:#fff}
.stat.accent .lbl,.stat.accent .sub{color:rgba(255,255,255,.85)}
.grid2{display:grid;grid-template-columns:1.6fr 1fr;gap:16px}
@media(max-width:900px){.grid2{grid-template-columns:1fr}}
.panel{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden}
.panel .hd{padding:15px 18px;border-bottom:1px solid var(--line);font-weight:800;display:flex;align-items:center;gap:9px}
.panel .hd small{font-weight:500;color:var(--ink3);margin-left:auto;font-size:.8rem}
.panel .bd{padding:18px}
/* Graphe barres */
.chart{display:flex;align-items:flex-end;gap:6px;height:180px;padding-top:8px}
.chart .col{flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;height:100%;justify-content:flex-end}
.chart .bar{width:100%;max-width:26px;border-radius:6px 6px 3px 3px;background:linear-gradient(180deg,var(--acc),color-mix(in srgb,var(--acc) 60%,transparent));min-height:3px;transition:height .3s;position:relative}
.chart .bar:hover{filter:brightness(1.1)}
.chart .cap{font-size:.62rem;color:var(--ink3);white-space:nowrap}
/* Répartition paiements */
.mix{display:flex;flex-direction:column;gap:12px}
.mix-row .top{display:flex;align-items:center;justify-content:space-between;font-size:.85rem;margin-bottom:5px}
.mix-row .nm{display:flex;align-items:center;gap:8px;font-weight:600;color:var(--ink)}
.mix-row .amt{font-variant-numeric:tabular-nums;color:var(--ink2)}
.mix-row .track{height:8px;border-radius:99px;background:var(--tint);overflow:hidden}
.mix-row .fill{height:100%;border-radius:99px}
.mix-empty,.top-empty{color:var(--ink3);font-size:.85rem;text-align:center;padding:20px 0}
/* Top chambres */
.toproom{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--line)}
.toproom:last-child{border-bottom:0}
.toproom .rank{width:26px;height:26px;border-radius:8px;background:var(--tint);color:var(--acc);font-weight:800;display:grid;place-items:center;font-size:.8rem;flex:none}
.toproom .rn{font-weight:600}.toproom .rv{margin-left:auto;font-weight:700;font-variant-numeric:tabular-nums}
.mini{display:flex;gap:14px;flex-wrap:wrap;color:var(--ink2);font-size:.85rem}
.mini b{color:var(--ink)}
</style>

<div class="rev-page">
    <div class="rev-head">
        <h1><span class="ic"><i class="fas fa-chart-line"></i></span> Revenus</h1>
        <p>Pilotage financier de votre établissement, encaissements, occupation et tendances.</p>
    </div>

    {{-- Chiffres clés --}}
    <div class="stats">
        <div class="stat accent">
            <div class="lbl"><i class="fas fa-sack-dollar"></i> Aujourd'hui</div>
            <div class="val">{{ $fmt($revToday) }} <small>{{ $currency }}</small></div>
            <div class="sub">Encaissé ce jour</div>
        </div>
        <div class="stat">
            <div class="lbl"><i class="fas fa-calendar-days"></i> Ce mois</div>
            <div class="val">{{ $fmt($revMonth) }} <small>{{ $currency }}</small></div>
            <div class="sub">{{ $reservationsMonth }} réservation(s) · {{ $checkinsMonth }} arrivée(s)</div>
        </div>
        <div class="stat">
            <div class="lbl"><i class="fas fa-bed"></i> Occupation</div>
            <div class="val">{{ $occupancy }}<small>%</small></div>
            <div class="sub">{{ $occupiedToday }} / {{ $totalRooms }} chambre(s) aujourd'hui</div>
        </div>
        <div class="stat">
            <div class="lbl"><i class="fas fa-chart-simple"></i> RevPAR</div>
            <div class="val">{{ $fmt($revpar) }} <small>{{ $currency }}</small></div>
            <div class="sub">Revenu par chambre (ce mois)</div>
        </div>
    </div>

    {{-- Graphe 14 jours --}}
    <div class="panel">
        <div class="hd"><i class="fas fa-chart-column" style="color:var(--acc)"></i> Encaissements, 14 derniers jours
            <small>max {{ $fmt($dailyMax) }} {{ $currency }}</small></div>
        <div class="bd">
            <div class="chart">
                @foreach ($daily as $d)
                    <div class="col">
                        <div class="bar" style="height:{{ max(2, round($d['amount'] / $dailyMax * 100)) }}%"
                             title="{{ $d['label'] }} : {{ $fmt($d['amount']) }} {{ $currency }}"></div>
                        <div class="cap">{{ $d['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid2">
        {{-- Répartition moyens de paiement --}}
        <div class="panel">
            <div class="hd"><i class="fas fa-wallet" style="color:var(--acc)"></i> Moyens de paiement <small>ce mois</small></div>
            <div class="bd">
                @if ($mix->isEmpty())
                    <div class="mix-empty">Aucun encaissement ce mois pour l'instant.</div>
                @else
                    <div class="mix">
                        @foreach ($mix as $m)
                            <div class="mix-row">
                                <div class="top">
                                    <span class="nm"><i class="fas {{ $m['icon'] }}" style="color:{{ $m['color'] }}"></i> {{ $m['label'] }}</span>
                                    <span class="amt">{{ $fmt($m['amount']) }} {{ $currency }} · {{ $m['pct'] }}%</span>
                                </div>
                                <div class="track"><div class="fill" style="width:{{ max(2, $m['pct']) }}%;background:{{ $m['color'] }}"></div></div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Top chambres --}}
        <div class="panel">
            <div class="hd"><i class="fas fa-trophy" style="color:var(--acc)"></i> Top chambres <small>ce mois</small></div>
            <div class="bd">
                @if ($topRooms->isEmpty())
                    <div class="top-empty">Pas encore de revenus par chambre ce mois.</div>
                @else
                    @foreach ($topRooms as $i => $r)
                        <div class="toproom">
                            <span class="rank">{{ $i + 1 }}</span>
                            <span class="rn">Chambre {{ $r->number }}</span>
                            <span class="rv">{{ $fmt($r->total) }} {{ $currency }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="bd mini">
            <span><i class="fas fa-coins" style="color:var(--acc)"></i> Total encaissé (depuis le début) : <b>{{ $fmt($revTotal) }} {{ $currency }}</b></span>
        </div>
    </div>
</div>
@endsection
