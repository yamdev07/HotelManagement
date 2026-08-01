@extends('template.master')

@section('title', 'Avis clients')

@section('content')
<style>
.rev-page{
  --card:#fff; --line:#e9edea; --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b; --tint:#f4f7f5;
  --acc:var(--g600,#2e8540); --acc-t:color-mix(in srgb,var(--g500,#2e8540) 13%,#fff);
  --ok:#1f7a3d; --ok-t:#e7f5ec; --bad:#b4342a; --bad-t:#fbe9e7; --gold:#d99e00;
  --r:14px; --sh:0 1px 2px rgba(20,40,30,.05);
  display:flex;flex-direction:column;gap:18px;color:var(--ink);
}
html[data-theme="dark"] .rev-page{
  --card:#161b18; --line:#28312b; --ink:#e8ede9; --ink2:#9aa39c; --ink3:#6b746d; --tint:#1b211d;
  --acc-t:color-mix(in srgb,var(--g500,#2e8540) 22%,#161b18); --ok-t:#12271a; --bad-t:#3a1e1b; --sh:0 1px 2px rgba(0,0,0,.3);
}
.rev-page .head h1{font-size:1.5rem;margin:0;display:flex;align-items:center;gap:12px}
.rev-page .head h1 .ic{width:40px;height:40px;border-radius:10px;background:var(--acc-t);color:var(--acc);display:grid;place-items:center;font-size:1.05rem}
.rev-page .head p{margin:6px 0 0;color:var(--ink2);font-size:.9rem}
.rev-page .flash{background:var(--ok-t);border:1px solid color-mix(in srgb,var(--ok) 35%,transparent);color:var(--ok);border-radius:10px;padding:12px 16px;font-size:.9rem;font-weight:600}
.rev-page .panel{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden}
.rev-page .panel .hd{padding:15px 18px;border-bottom:1px solid var(--line);font-weight:800;display:flex;align-items:center;gap:9px}
.rev-page .panel .hd .badge{margin-left:auto;background:var(--tint);color:var(--ink2);border-radius:100px;padding:2px 10px;font-size:.78rem;font-weight:700}
.rev-page .panel .bd{padding:14px 18px;display:flex;flex-direction:column;gap:12px}
.rev-item{border:1px solid var(--line);border-radius:12px;padding:14px 16px;background:var(--tint)}
.rev-item .stars{color:var(--gold);font-size:.95rem;letter-spacing:1px}
.rev-item .txt{margin:.5rem 0;font-size:1rem;line-height:1.55}
.rev-item .meta{font-size:.82rem;color:var(--ink3);display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.rev-item .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.rev-btn{border:1px solid var(--line);background:var(--card);color:var(--ink);border-radius:9px;padding:7px 13px;font-size:.83rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:.15s}
.rev-btn:hover{transform:translateY(-1px)}
.rev-btn.ok{background:var(--acc);color:#fff;border-color:transparent}
.rev-btn.bad{color:var(--bad);border-color:color-mix(in srgb,var(--bad) 30%,transparent)}
.rev-reply-box{margin-top:10px;padding:10px 12px;border-left:3px solid var(--acc);background:var(--acc-t);border-radius:0 10px 10px 0;font-size:.9rem}
.rev-reply-box .lbl{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--acc);font-weight:800;margin-bottom:3px}
details.rev-reply{margin-top:10px}
details.rev-reply summary{cursor:pointer;font-size:.83rem;font-weight:700;color:var(--acc)}
details.rev-reply textarea{width:100%;margin-top:8px;border:1.5px solid var(--line);border-radius:9px;padding:9px 11px;background:var(--card);color:var(--ink);font-size:.9rem}
.rev-empty{color:var(--ink3);font-size:.9rem;padding:6px 2px}
</style>

<div class="rev-page">
    <div class="head">
        <h1><span class="ic"><i class="fas fa-star"></i></span> Avis clients</h1>
        <p>Modérez les avis déposés depuis votre vitrine. Les avis approuvés y sont publiés automatiquement.</p>
    </div>

    @if (session('success'))
        <div class="flash"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif

    @php
        $stars = fn ($n) => str_repeat('★', (int) $n).str_repeat('☆', 5 - (int) $n);
    @endphp

    {{-- En attente --}}
    <div class="panel">
        <div class="hd"><i class="fas fa-clock" style="color:var(--gold)"></i> En attente de modération <span class="badge">{{ $pending->count() }}</span></div>
        <div class="bd">
            @forelse ($pending as $r)
                <div class="rev-item">
                    <div class="stars">{{ $stars($r->rating) }}</div>
                    <div class="txt">“{{ $r->comment }}”</div>
                    <div class="meta"><strong>{{ $r->author_name }}</strong>@if($r->author_city)· {{ $r->author_city }}@endif · {{ $r->created_at->diffForHumans() }}</div>
                    <div class="actions">
                        <form method="POST" action="{{ route('reviews.approve', $r) }}">@csrf
                            <button class="rev-btn ok"><i class="fas fa-check"></i> Approuver</button>
                        </form>
                        <form method="POST" action="{{ route('reviews.reject', $r) }}">@csrf
                            <button class="rev-btn bad"><i class="fas fa-times"></i> Rejeter</button>
                        </form>
                        <form method="POST" action="{{ route('reviews.destroy', $r) }}">@csrf @method('DELETE')
                            <button class="rev-btn" onclick="return confirm('Supprimer définitivement cet avis ?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    <details class="rev-reply">
                        <summary><i class="fas fa-reply"></i> Répondre (publié sous l'avis)</summary>
                        <form method="POST" action="{{ route('reviews.reply', $r) }}">@csrf
                            <textarea name="reply" rows="2" maxlength="1000" placeholder="Votre réponse…" required>{{ $r->reply }}</textarea>
                            <button class="rev-btn ok mt-2" type="submit"><i class="fas fa-paper-plane"></i> Enregistrer la réponse</button>
                        </form>
                    </details>
                </div>
            @empty
                <div class="rev-empty">Aucun avis en attente. 🎉</div>
            @endforelse
        </div>
    </div>

    {{-- Approuvés --}}
    <div class="panel">
        <div class="hd"><i class="fas fa-check-circle" style="color:var(--ok)"></i> Publiés <span class="badge">{{ $approved->count() }}</span></div>
        <div class="bd">
            @forelse ($approved as $r)
                <div class="rev-item">
                    <div class="stars">{{ $stars($r->rating) }}</div>
                    <div class="txt">“{{ $r->comment }}”</div>
                    <div class="meta"><strong>{{ $r->author_name }}</strong>@if($r->author_city)· {{ $r->author_city }}@endif · publié {{ optional($r->approved_at)->diffForHumans() }}</div>
                    @if ($r->reply)
                        <div class="rev-reply-box"><div class="lbl">Votre réponse</div>{{ $r->reply }}</div>
                    @endif
                    <div class="actions">
                        <form method="POST" action="{{ route('reviews.reject', $r) }}">@csrf
                            <button class="rev-btn bad" onclick="return confirm('Retirer cet avis de la vitrine ?')"><i class="fas fa-eye-slash"></i> Dépublier</button>
                        </form>
                        <form method="POST" action="{{ route('reviews.destroy', $r) }}">@csrf @method('DELETE')
                            <button class="rev-btn" onclick="return confirm('Supprimer définitivement cet avis ?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    <details class="rev-reply">
                        <summary><i class="fas fa-reply"></i> {{ $r->reply ? 'Modifier la réponse' : 'Répondre' }}</summary>
                        <form method="POST" action="{{ route('reviews.reply', $r) }}">@csrf
                            <textarea name="reply" rows="2" maxlength="1000" placeholder="Votre réponse…" required>{{ $r->reply }}</textarea>
                            <button class="rev-btn ok mt-2" type="submit"><i class="fas fa-paper-plane"></i> Enregistrer</button>
                        </form>
                    </details>
                </div>
            @empty
                <div class="rev-empty">Aucun avis publié pour le moment.</div>
            @endforelse
        </div>
    </div>

    {{-- Rejetés --}}
    @if ($rejected->isNotEmpty())
        <div class="panel">
            <div class="hd"><i class="fas fa-ban" style="color:var(--bad)"></i> Rejetés <span class="badge">{{ $rejected->count() }}</span></div>
            <div class="bd">
                @foreach ($rejected as $r)
                    <div class="rev-item">
                        <div class="stars">{{ $stars($r->rating) }}</div>
                        <div class="txt" style="opacity:.75">“{{ $r->comment }}”</div>
                        <div class="meta"><strong>{{ $r->author_name }}</strong>@if($r->author_city)· {{ $r->author_city }}@endif</div>
                        <div class="actions">
                            <form method="POST" action="{{ route('reviews.approve', $r) }}">@csrf
                                <button class="rev-btn ok"><i class="fas fa-check"></i> Approuver finalement</button>
                            </form>
                            <form method="POST" action="{{ route('reviews.destroy', $r) }}">@csrf @method('DELETE')
                                <button class="rev-btn" onclick="return confirm('Supprimer définitivement cet avis ?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
