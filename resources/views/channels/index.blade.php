@extends('template.master')

@section('title', 'Synchronisation des calendriers')

@section('content')
<style>
.chan-page{
  --card:#fff; --page:#f8faf9; --line:#e9edea; --ink:#181d1a; --ink2:#5c655f; --ink3:#98a19b;
  --tint:#f4f7f5; --acc:var(--g600,#2e8540); --acc-t:color-mix(in srgb,var(--g500,#2e8540) 13%,#fff);
  --bad:#b4342a; --bad-t:#fbe9e7; --ok:#1f7a3d; --ok-t:#e7f5ec; --r:12px; --sh:0 1px 2px rgba(20,40,30,.05);
  display:flex; flex-direction:column; gap:18px; color:var(--ink);
}
html[data-theme="dark"] .chan-page{
  --card:#161b18; --page:#0f1311; --line:#28312b; --ink:#e8ede9; --ink2:#9aa39c; --ink3:#6b746d;
  --tint:#1b211d; --acc-t:color-mix(in srgb,var(--g500,#2e8540) 22%,#161b18);
  --bad-t:#3a1e1b; --ok-t:#12271a; --sh:0 1px 2px rgba(0,0,0,.3);
}
.chan-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap}
.chan-head h1{font-size:1.5rem;margin:0;display:flex;align-items:center;gap:12px}
.chan-head h1 .ic{width:40px;height:40px;border-radius:10px;background:var(--acc-t);color:var(--acc);display:grid;place-items:center;font-size:1.1rem}
.chan-head p{margin:6px 0 0;color:var(--ink2);font-size:.92rem;max-width:640px}
.chan-btn{display:inline-flex;align-items:center;gap:8px;border:0;border-radius:10px;padding:11px 18px;font-weight:700;font-size:.9rem;cursor:pointer;text-decoration:none;background:var(--acc);color:#fff}
.chan-btn:hover{filter:brightness(1.06);color:#fff}
.chan-btn.ghost{background:var(--card);border:1.5px solid var(--line);color:var(--ink)}
.chan-flash{background:var(--ok-t);border:1px solid color-mix(in srgb,var(--ok) 35%,transparent);color:var(--ok);border-radius:10px;padding:12px 16px;font-size:.9rem;font-weight:600}
.chan-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:16px}
.room-card{background:var(--card);border:1px solid var(--line);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;display:flex;flex-direction:column}
.room-card .rc-hd{padding:14px 16px;border-bottom:1px solid var(--line);font-weight:800;display:flex;align-items:center;gap:9px}
.room-card .rc-hd .num{background:var(--tint);color:var(--acc);border-radius:8px;padding:3px 10px;font-size:.85rem}
.rc-body{padding:16px;display:flex;flex-direction:column;gap:16px}
.rc-lbl{font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--ink3);margin-bottom:7px;display:flex;align-items:center;gap:7px}
.copy-row{display:flex;gap:8px}
.copy-row input{flex:1;min-width:0;padding:9px 11px;border:1.5px solid var(--line);border-radius:9px;background:var(--tint);color:var(--ink2);font-size:.8rem;font-family:ui-monospace,monospace}
.copy-btn{flex:none;border:1.5px solid var(--line);background:var(--card);color:var(--ink);border-radius:9px;padding:0 13px;cursor:pointer;font-size:.85rem}
.copy-btn:hover{border-color:var(--acc);color:var(--acc)}
.feed{display:flex;align-items:center;gap:10px;padding:9px 11px;border:1px solid var(--line);border-radius:9px;background:var(--tint)}
.feed .src{font-weight:700;font-size:.82rem}
.feed .meta{font-size:.72rem;color:var(--ink3);margin-top:2px}
.feed .meta.err{color:var(--bad)}
.feed .grow{flex:1;min-width:0}
.feed .del{border:0;background:transparent;color:var(--ink3);cursor:pointer;font-size:.9rem}
.feed .del:hover{color:var(--bad)}
.feed-empty{font-size:.82rem;color:var(--ink3);font-style:italic}
.add-feed{display:flex;gap:8px;flex-wrap:wrap}
.add-feed select,.add-feed input{padding:9px 11px;border:1.5px solid var(--line);border-radius:9px;background:var(--card);color:var(--ink);font-size:.85rem}
.add-feed select{flex:none}
.add-feed input{flex:1;min-width:140px}
.add-feed button{flex:none;border:0;border-radius:9px;background:var(--acc);color:#fff;padding:0 14px;font-weight:700;cursor:pointer}
.fe{color:var(--bad);font-size:.76rem;margin-top:5px}
.chan-empty{background:var(--card);border:1px dashed var(--line);border-radius:var(--r);padding:40px;text-align:center;color:var(--ink2)}
</style>

<div class="chan-page">
    <div class="chan-head">
        <div>
            <h1><span class="ic"><i class="fas fa-arrows-rotate"></i></span> Synchronisation des calendriers</h1>
            <p>Évitez les doubles réservations entre votre site et Booking.com / Airbnb.
               Donnez le <strong>lien iCal</strong> de chaque chambre à ces plateformes, et collez ici les leurs :
               les dates vendues ailleurs se bloquent automatiquement chez vous.</p>
        </div>
        @if ($rooms->flatMap->calendarFeeds->isNotEmpty())
            <form method="POST" action="{{ route('channels.sync') }}">
                @csrf
                <button type="submit" class="chan-btn"><i class="fas fa-arrows-rotate"></i> Synchroniser maintenant</button>
            </form>
        @endif
    </div>

    @if (session('success'))
        <div class="chan-flash"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
    @endif

    @if ($rooms->isEmpty())
        <div class="chan-empty">
            <p>Ajoutez d'abord des chambres pour gérer leurs calendriers.</p>
            <a href="{{ route('room.index') }}" class="chan-btn ghost"><i class="fas fa-bed"></i> Mes chambres</a>
        </div>
    @else
        <div class="chan-grid">
            @foreach ($rooms as $room)
                <div class="room-card">
                    <div class="rc-hd"><i class="fas fa-bed" style="color:var(--ink3)"></i>
                        {{ $room->name ?: ('Chambre '.$room->number) }}
                        <span class="num">n°{{ $room->number }}</span>
                    </div>
                    <div class="rc-body">
                        {{-- Export : lien à donner aux OTA --}}
                        <div>
                            <div class="rc-lbl"><i class="fas fa-arrow-up-from-bracket"></i> Lien à donner à Booking / Airbnb</div>
                            <div class="copy-row">
                                <input type="text" readonly value="{{ $room->icalUrl() }}" onclick="this.select()">
                                <button type="button" class="copy-btn" data-copy="{{ $room->icalUrl() }}"><i class="fas fa-copy"></i></button>
                            </div>
                        </div>

                        {{-- Import : calendriers OTA configurés --}}
                        <div>
                            <div class="rc-lbl"><i class="fas fa-arrow-down-to-bracket"></i> Calendriers importés</div>
                            <div style="display:flex;flex-direction:column;gap:7px">
                                @forelse ($room->calendarFeeds as $feed)
                                    <div class="feed">
                                        <div class="grow">
                                            <div class="src">{{ $feed->source }}</div>
                                            @if ($feed->last_error)
                                                <div class="meta err"><i class="fas fa-triangle-exclamation"></i> {{ \Illuminate\Support\Str::limit($feed->last_error, 60) }}</div>
                                            @elseif ($feed->last_synced_at)
                                                <div class="meta"><i class="fas fa-check"></i> Sync {{ $feed->last_synced_at->diffForHumans() }}</div>
                                            @else
                                                <div class="meta">En attente de première sync…</div>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('channels.feed.destroy', $feed) }}"
                                              onsubmit="return confirm('Retirer ce calendrier ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="del" title="Retirer"><i class="fas fa-trash-can"></i></button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="feed-empty">Aucun calendrier importé pour l'instant.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Ajout d'un calendrier --}}
                        <form method="POST" action="{{ route('channels.feed.store', $room) }}" class="add-feed">
                            @csrf
                            <select name="source">
                                <option value="Booking.com">Booking.com</option>
                                <option value="Airbnb">Airbnb</option>
                                <option value="Expedia">Expedia</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <input type="url" name="url" placeholder="Coller le lien iCal (https://…)" required>
                            <button type="submit"><i class="fas fa-plus"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.copy-btn').forEach(function (b) {
    b.addEventListener('click', function () {
        var txt = b.getAttribute('data-copy');
        navigator.clipboard && navigator.clipboard.writeText(txt);
        var old = b.innerHTML;
        b.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(function () { b.innerHTML = old; }, 1400);
    });
});
</script>
@endsection
