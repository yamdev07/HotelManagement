@extends('layouts.app')
 
@section('title', 'Plan de Salle')
 
@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&family=Fraunces:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --bg:     #181818;
    --bg2:    #232323;
    --bg3:    #292929;
    --bg4:    #323232;
    --accent: #C9A14A;
    --accent2:#A88A3C;
    --text:   #F5F5F5;
    --text2:  #E0E0E0;
    --muted:  #888;
    --muted2: #555;
    --danger: #C04040;
    --success:#3D8B5E;
    --trans:  cubic-bezier(.4,0,.2,1);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }


  /* ── Top Nav ── */
  #topnav {
    height: 56px; background: var(--bg2); border-bottom: 1px solid rgba(212,168,83,.12);
    display: flex; align-items: center; padding: 0 18px; gap: 14px; flex-shrink: 0; z-index: 10;
  }
  .brand { display: flex; align-items: center; gap: 10px; margin-right: 14px; }
  .brand-mark svg { width: 24px; height: 24px; fill: var(--gold); }
  .b1 { font-family: 'Fraunces', serif; font-size: .95rem; color: var(--gold); line-height: 1.1; }
  .b2 { font-size: .62rem; color: var(--muted); letter-spacing: .06em; text-transform: uppercase; }
  .room-tabs { display: flex; gap: 6px; overflow-x: auto; flex: 1; scrollbar-width: none; }
  .room-tabs::-webkit-scrollbar { display: none; }
  .rtab {
    background: var(--bg3); border: 1px solid var(--muted2);
    color: var(--text2); padding: 5px 14px; border-radius: 6px;
    font-size: .78rem; cursor: pointer; white-space: nowrap;
    transition: all .18s var(--trans); font-family: inherit;
  }
  .rtab:hover { border-color: var(--accent2); color: var(--text); background: var(--bg4); }
  .rtab.active { background: var(--accent); border-color: var(--accent2); color: var(--bg); font-weight: 600; }
  .nav-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }
  .role-chip { display: flex; align-items: center; gap: 6px; font-size: .72rem; color: var(--muted); }
  .pulse { width: 7px; height: 7px; border-radius: 50%; background: var(--green); animation: blink 2s infinite; }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
  .user-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--gold4); color: var(--gold3);
    font-size: .72rem; font-weight: 700; display: flex; align-items: center; justify-content: center;
  }

  /* ── Toolbar ── */
  #toolbar {
    height: 48px; background: var(--bg3); border-bottom: 1px solid rgba(212,168,83,.08);
    display: flex; align-items: center; padding: 0 16px; gap: 6px; flex-shrink: 0;
    justify-content: space-between;
  }
  .tg { display: flex; align-items: center; gap: 6px; }
  .tbtn {
    display: flex; align-items: center; gap: 6px; padding: 7px 13px;
    background: var(--bg4); border: 1px solid var(--muted2);
    color: var(--text2); border-radius: 7px; font-size: .78rem; cursor: pointer;
    transition: all .15s; font-family: inherit;
  }
  .tbtn svg { width: 14px; height: 14px; flex-shrink: 0; }
  .tbtn:hover { background: var(--accent2); border-color: var(--accent); color: var(--bg); }
  .tbtn:active { transform: scale(.97); }
  .tbtn.gold { background: var(--accent); border-color: var(--accent2); color: var(--bg); }
  .tbtn.gold:hover { background: var(--accent2); color: var(--text); }
  .tbtn.red { background: var(--danger); border-color: var(--danger); color: #fff; }
  .tbtn.red:hover { background: #a03030; }
  .tsep { width: 1px; height: 22px; background: rgba(255,255,255,.08); margin: 0 4px; }
  .snap-lbl {
    display: flex; align-items: center; gap: 6px; font-size: .75rem;
    color: var(--muted); cursor: pointer; user-select: none; padding: 0 6px;
  }
  .snap-lbl input { display: none; }
  .snap-lbl.on { color: var(--gold); }
  .zv { font-size: .78rem; color: var(--muted); min-width: 40px; text-align: center; font-variant-numeric: tabular-nums; }

  /* ── Main Layout ── */
  #layout { display: flex; flex: 1; overflow: hidden; }

  /* ── Palette ── */
  #palette {
    width: 146px; flex-shrink: 0;
    background: var(--bg2); border-right: 1px solid rgba(255,255,255,.05);
    overflow-y: auto; padding: 10px 8px 20px; scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.1) transparent;
  }
  #palette::-webkit-scrollbar { width: 3px; }
  #palette::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); }
  .ph { font-size: .6rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted2); padding: 8px 4px 4px; }
  .pg { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
  .pi {
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    padding: 8px 4px; border-radius: 8px; cursor: grab; user-select: none;
    background: var(--bg3); border: 1px solid rgba(255,255,255,.05);
    transition: border-color .15s, background .15s;
  }
  .pi:hover { border-color: rgba(212,168,83,.3); background: var(--bg4); }
  .pi:active { cursor: grabbing; transform: scale(.96); }
  .pi span { font-size: .6rem; color: var(--muted); text-align: center; line-height: 1.2; }
  .pdiv { height: 1px; background: rgba(255,255,255,.05); margin: 8px 0; grid-column: 1/-1; }

  /* ── Canvas Area ── */
  #canvas-area {
    flex: 1; position: relative; overflow: hidden;
    background:
      linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 24px 24px;
    background-color: var(--bg);
    cursor: default;
  }
  #room-boundary {
    position: absolute; border: 2px dashed rgba(212,168,83,.18);
    border-radius: 4px; pointer-events: none; transition: all .3s;
  }
  #room-label {
    position: absolute; top: 14px; left: 50%; transform: translateX(-50%);
    font-family: 'Fraunces', serif; font-size: .85rem; color: rgba(212,168,83,.3);
    pointer-events: none; white-space: nowrap; letter-spacing: .04em;
  }
  #room-canvas { position: absolute; inset: 0; }

  /* ── Canvas Elements ── */
  .ce {
    position: absolute; cursor: pointer; user-select: none;
    display: flex; align-items: center; justify-content: center;
    font-size: .6rem; color: var(--cream); font-weight: 700;
    border-radius: 4px; transition: box-shadow .12s, outline .12s;
  }
  .ce:hover { outline: 1px solid rgba(212,168,83,.5); }
  .ce.selected { outline: 2px solid var(--gold); box-shadow: 0 0 0 3px rgba(212,168,83,.15); }
  .ce-label {
    position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%);
    white-space: nowrap; font-size: .6rem; color: var(--muted); pointer-events: none;
    font-weight: 500; text-shadow: 0 1px 2px rgba(0,0,0,.8);
  }

  /* ── Right Panel ── */
  #right-panel {
    width: 192px; flex-shrink: 0;
    background: var(--bg2); border-left: 1px solid rgba(255,255,255,.05);
    overflow-y: auto; padding: 10px 0 20px; scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.1) transparent;
  }
  #right-panel::-webkit-scrollbar { width: 3px; }
  .ps { padding: 10px 14px; border-bottom: 1px solid rgba(255,255,255,.05); }
  .pt { font-size: .65rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted2); margin-bottom: 10px; font-weight: 700; }
  .pr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; font-size: .75rem; }
  .pl { color: var(--muted); font-size: .72rem; }
  .pi2 {
    background: var(--bg3); border: 1px solid rgba(255,255,255,.08);
    color: var(--cream); padding: 4px 7px; border-radius: 5px; font-size: .75rem;
    font-family: inherit; width: 80px; text-align: right;
  }
  .pi2.full { width: 100%; margin-top: 4px; text-align: left; }
  .pi2:focus { outline: 1px solid var(--gold2); border-color: var(--gold2); }
  .est { text-align: center; padding: 16px 0; }
  .est-icon { margin-bottom: 8px; }
  .est p { font-size: .72rem; color: var(--muted); line-height: 1.4; }

  /* Stats grid */
  .sg { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
  .sb2 {
    background: var(--bg3); border-radius: 7px; padding: 8px 6px;
    text-align: center; border: 1px solid rgba(255,255,255,.04);
  }
  .sb2.scap { grid-column: 1/-1; background: var(--bg4); border-color: var(--accent2); }
  .sn { font-size: 1.1rem; font-weight: 700; color: var(--accent); font-variant-numeric: tabular-nums; }
  .sb2.scap .sn { font-size: 1.3rem; }
  .sl { font-size: .6rem; color: var(--muted); margin-top: 2px; }

  /* Legend */
  .li { display: flex; align-items: center; gap: 8px; font-size: .72rem; color: var(--muted); margin-bottom: 5px; }
  .ld { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; border: 1px solid var(--muted2); }

  /* Shortcuts */
  .kbd { background: var(--bg3); border: 1px solid rgba(255,255,255,.1); color: var(--muted); padding: 2px 6px; border-radius: 4px; font-size: .65rem; font-family: monospace; }

  /* ── Status Bar ── */
  #statusbar {
    height: 28px; background: var(--bg3); border-top: 1px solid var(--muted2);
    display: flex; align-items: center; padding: 0 14px; gap: 10px; flex-shrink: 0;
    font-size: .67rem;
  }
  .si { color: var(--muted); }
  .sa { color: var(--accent); font-weight: 600; }
  .sdot { width: 3px; height: 3px; border-radius: 50%; background: var(--muted2); }

  /* ── Toast ── */
  #toast {
    position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%);
    background: var(--bg4); border: 1px solid var(--accent2);
    color: var(--text); padding: 9px 20px; border-radius: 8px;
    font-size: .8rem; pointer-events: none; opacity: 0;
    transition: opacity .25s; z-index: 9999; white-space: nowrap;
    box-shadow: 0 8px 24px rgba(0,0,0,.4);
  }
  #toast.show { opacity: 1; }
  #toast.ok  { border-color: var(--success); color: #7de0a4; }
  #toast.err { border-color: var(--danger); color: #e08080; }
</style>
@endpush

@section('content')
<div id="topnav">
  <div class="brand">
    <div class="brand-mark"><svg viewBox="0 0 24 24"><path d="M3 3h7v7H3zm11 0h7v7h-7zM3 14h7v7H3zm14 3a3 3 0 100-6 3 3 0 000 6z"/></svg></div>
    <div class="brand-text"><div class="b1">{{ config('app.name') }}</div><div class="b2">Plan de Salle</div></div>
  </div>

  <div class="room-tabs" id="room-tabs">
    @foreach($rooms as $room)
    <button class="rtab {{ $loop->first ? 'active' : '' }}" data-room="{{ $room->id }}" data-name="{{ $room->name }}">
      {{ $room->name }}
    </button>
    @endforeach
  </div>

  <div class="nav-right">
    <div class="role-chip">
      <span class="pulse"></span>
      {{ auth()->user()->role === 'superadmin' ? 'Super Admin' : 'Administrateur' }}
    </div>
    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
  </div>
</div>

<div id="toolbar">
  <div class="tg">
    <button class="tbtn" id="btn-undo">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10H13a7 7 0 110 14H8"/><path d="M3 10l4-4M3 10l4 4"/></svg>Annuler
    </button>
    <button class="tbtn" id="btn-redo">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10H11A7 7 0 100 24H16"/><path d="M21 10l-4-4M21 10l-4 4"/></svg>Rétablir
    </button>
    <div class="tsep"></div>
    <label class="snap-lbl">
      <input type="checkbox" id="snap-toggle" checked>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Grille magnétique
    </label>
  </div>
  <div class="tg">
    <button class="tbtn" id="btn-zoom-out" style="padding:7px 10px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg></button>
    <span class="zv" id="zoom-val">100%</span>
    <button class="tbtn" id="btn-zoom-in" style="padding:7px 10px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg></button>
    <div class="tsep"></div>
    <button class="tbtn red" id="btn-clear">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Vider
    </button>
    <button class="tbtn" id="btn-export">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Exporter
    </button>
    <button class="tbtn gold" id="btn-save">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>Enregistrer
    </button>
  </div>
</div>

<div id="layout">
  <div id="palette">
    {{-- Tables --}}
    <div class="ph">Tables</div>
    <div class="pg">
      <div class="pi" draggable="true" data-type="t-r4" data-cap="4">
        <svg width="46" height="46" viewBox="0 0 46 46"><circle cx="23" cy="23" r="18" fill="#2A2010" stroke="#D4A853" stroke-width="1.5"/><circle cx="23" cy="7" r="3.5" fill="#D4A853" opacity=".85"/><circle cx="23" cy="39" r="3.5" fill="#D4A853" opacity=".85"/><circle cx="7" cy="23" r="3.5" fill="#D4A853" opacity=".85"/><circle cx="39" cy="23" r="3.5" fill="#D4A853" opacity=".85"/><text x="23" y="27" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#D4A853">4p</text></svg>
        <span>Ronde 4p</span>
      </div>
      <div class="pi" draggable="true" data-type="t-r2" data-cap="2">
        <svg width="38" height="38" viewBox="0 0 40 40"><circle cx="20" cy="20" r="13" fill="#2A2010" stroke="#D4A853" stroke-width="1.5"/><circle cx="20" cy="5" r="3" fill="#D4A853" opacity=".85"/><circle cx="20" cy="35" r="3" fill="#D4A853" opacity=".85"/><text x="20" y="24" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#D4A853">2p</text></svg>
        <span>Ronde 2p</span>
      </div>
      <div class="pi" draggable="true" data-type="t-q4" data-cap="4">
        <svg width="52" height="38" viewBox="0 0 52 38"><rect x="6" y="9" width="40" height="20" rx="4" fill="#2A2010" stroke="#D4A853" stroke-width="1.5"/><rect x="11" y="3" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="33" y="3" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="11" y="29" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="33" y="29" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><text x="26" y="22" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#D4A853">4p</text></svg>
        <span>Rect. 4p</span>
      </div>
      <div class="pi" draggable="true" data-type="t-q6" data-cap="6">
        <svg width="62" height="38" viewBox="0 0 62 38"><rect x="4" y="9" width="54" height="20" rx="4" fill="#2A2010" stroke="#D4A853" stroke-width="1.5"/><rect x="8" y="3" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="27" y="3" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="46" y="3" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="8" y="29" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="27" y="29" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><rect x="46" y="29" width="8" height="6" rx="2" fill="#D4A853" opacity=".8"/><text x="31" y="22" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#D4A853">6p</text></svg>
        <span>Rect. 6p</span>
      </div>
      <div class="pi" draggable="true" data-type="t-q8" data-cap="8">
        <svg width="76" height="36" viewBox="0 0 76 36"><rect x="4" y="8" width="68" height="20" rx="4" fill="#2A2010" stroke="#D4A853" stroke-width="1.5"/><rect x="7" y="2" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="20" y="2" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="42" y="2" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="62" y="2" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="7" y="28" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="20" y="28" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="42" y="28" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><rect x="62" y="28" width="7" height="6" rx="2" fill="#D4A853" opacity=".75"/><text x="38" y="21" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#D4A853">8p</text></svg>
        <span>Banquet 8p</span>
      </div>
      <div class="pi" draggable="true" data-type="t-bar" data-cap="0">
        <svg width="60" height="28" viewBox="0 0 60 28"><rect x="3" y="7" width="54" height="14" rx="3" fill="#1A2E20" stroke="#3D8B5E" stroke-width="1.5"/><text x="30" y="17" text-anchor="middle" font-size="9" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#3D8B5E">Bar</text></svg>
        <span>Comptoir</span>
      </div>
    </div>
    <div class="pdiv"></div>
    <div class="ph">Chaises</div>
    <div class="pg">
      <div class="pi" draggable="true" data-type="c-std" data-cap="1">
        <svg width="34" height="36" viewBox="0 0 34 36"><rect x="4" y="4" width="26" height="18" rx="5" fill="#252018" stroke="#6B6355" stroke-width="1.5"/><rect x="6" y="6" width="22" height="7" rx="3" fill="#3A3020"/><rect x="6" y="22" width="6" height="9" rx="2" fill="#4A4030"/><rect x="22" y="22" width="6" height="9" rx="2" fill="#4A4030"/></svg>
        <span>Chaise</span>
      </div>
      <div class="pi" draggable="true" data-type="c-arm" data-cap="1">
        <svg width="38" height="36" viewBox="0 0 38 36"><rect x="5" y="6" width="28" height="18" rx="6" fill="#252018" stroke="#6B6355" stroke-width="1.5"/><rect x="7" y="8" width="24" height="7" rx="4" fill="#3A3020"/><rect x="2" y="10" width="5" height="11" rx="2.5" fill="#4A4030"/><rect x="31" y="10" width="5" height="11" rx="2.5" fill="#4A4030"/></svg>
        <span>Fauteuil</span>
      </div>
      <div class="pi" draggable="true" data-type="c-stool" data-cap="1">
        <svg width="32" height="38" viewBox="0 0 32 38"><ellipse cx="16" cy="13" rx="13" ry="11" fill="#252018" stroke="#6B6355" stroke-width="1.5"/><rect x="14" y="24" width="4" height="10" rx="2" fill="#4A4030"/><rect x="7" y="32" width="18" height="3" rx="1.5" fill="#3A3020"/></svg>
        <span>Tabouret</span>
      </div>
      <div class="pi" draggable="true" data-type="c-bench" data-cap="3">
        <svg width="56" height="30" viewBox="0 0 56 30"><rect x="3" y="10" width="50" height="17" rx="5" fill="#252018" stroke="#6B6355" stroke-width="1.5"/><rect x="3" y="4" width="50" height="9" rx="3" fill="#3A3020"/></svg>
        <span>Banquette</span>
      </div>
    </div>
    <div class="pdiv"></div>
    <div class="ph">Décoration</div>
    <div class="pg">
      <div class="pi" draggable="true" data-type="d-plant" data-cap="0">
        <svg width="38" height="44" viewBox="0 0 38 44"><ellipse cx="19" cy="18" rx="15" ry="14" fill="#162215" stroke="#3D8B5E" stroke-width="1.5"/><ellipse cx="19" cy="16" rx="9" ry="8" fill="#1E3020" opacity=".9"/><rect x="16" y="31" width="6" height="11" rx="2" fill="#3D8B5E"/></svg>
        <span>Plante</span>
      </div>
      <div class="pi" draggable="true" data-type="d-cash" data-cap="0">
        <svg width="38" height="36" viewBox="0 0 38 36"><rect x="4" y="7" width="30" height="24" rx="4" fill="#152030" stroke="#3A6FA8" stroke-width="1.5"/><rect x="8" y="11" width="14" height="9" rx="2" fill="#3A6FA8" opacity=".35"/><text x="19" y="28" text-anchor="middle" font-size="7" font-family="Plus Jakarta Sans,sans-serif" font-weight="700" fill="#3A6FA8">CAISSE</text></svg>
        <span>Caisse</span>
      </div>
    </div>
  </div>

  <div id="canvas-area">
    <div id="room-boundary"></div>
    <div id="room-label">{{ $rooms->first()->name ?? 'Salle principale' }}</div>
    <div id="room-canvas"></div>
  </div>

  <div id="right-panel">
    <div class="ps">
      <div class="pt">Propriétés</div>
      <div id="no-sel" class="est">
        <div class="est-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6B6355" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 9h.01M15 9h.01M9 15h6"/></svg></div>
        <p>Sélectionnez un élément pour voir ses propriétés</p>
      </div>
      <div id="sel-panel" style="display:none;">
        <div class="pr"><span class="pl">Type</span><span id="sel-type" style="font-size:11px;font-weight:700;color:var(--gold);letter-spacing:.03em;">—</span></div>
        <div class="pr"><span class="pl">X</span><input class="pi2" id="sel-x" type="number" step="8"></div>
        <div class="pr"><span class="pl">Y</span><input class="pi2" id="sel-y" type="number" step="8"></div>
        <div class="pr"><span class="pl">Rotation</span><input class="pi2" id="sel-rot" type="number" step="45" min="0" max="315"></div>
        <div><span class="pl">Étiquette</span><input class="pi2 full" id="sel-label" type="text" placeholder="Nom / numéro…"></div>
        <button class="tbtn red" id="btn-del-sel" style="width:100%;margin-top:14px;justify-content:center;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Supprimer
        </button>
      </div>
    </div>
    <div class="ps">
      <div class="pt">Statistiques</div>
      <div class="sg">
        <div class="sb2"><div class="sn" id="st-tables">0</div><div class="sl">Tables</div></div>
        <div class="sb2"><div class="sn" id="st-chairs">0</div><div class="sl">Chaises</div></div>
        <div class="sb2 scap"><div class="sn" id="st-cap">0</div><div class="sl">Capacité totale</div></div>
        <div class="sb2"><div class="sn" id="st-total">0</div><div class="sl">Éléments</div></div>
      </div>
    </div>
    <div class="ps">
      <div class="pt">Légende</div>
      <div class="li"><div class="ld" style="background:#D4A853;"></div>Tables</div>
      <div class="li"><div class="ld" style="background:#6B6355;"></div>Chaises / Assises</div>
      <div class="li"><div class="ld" style="background:#3D8B5E;"></div>Bar / Comptoir</div>
      <div class="li"><div class="ld" style="background:#3A6FA8;"></div>Caisse</div>
      <div class="li"><div class="ld" style="background:#3D8B5E;opacity:.5;"></div>Décoration</div>
    </div>
    <div class="ps">
      <div class="pt">Raccourcis</div>
      <div class="li"><span style="color:var(--cream2);">Déplacer</span><span class="kbd" style="margin-left:auto;">↑↓←→</span></div>
      <div class="li"><span style="color:var(--cream2);">Annuler</span><span class="kbd" style="margin-left:auto;">Ctrl Z</span></div>
      <div class="li"><span style="color:var(--cream2);">Rétablir</span><span class="kbd" style="margin-left:auto;">Ctrl Y</span></div>
      <div class="li"><span style="color:var(--cream2);">Supprimer</span><span class="kbd" style="margin-left:auto;">Del</span></div>
    </div>
  </div>
</div>

<div id="statusbar">
  <div class="si">Salle : <span class="sa" id="sb-room">{{ $rooms->first()->name ?? '—' }}</span></div>
  <div class="sdot"></div>
  <div class="si" id="sb-msg">Prêt — glissez des éléments sur le plan</div>
  <div class="sdot"></div>
  <div class="si">Rôle : <span class="sa">{{ auth()->user()->role === 'superadmin' ? 'Super Admin' : 'Admin' }}</span></div>
  <div class="sdot"></div>
  <div class="si">{{ auth()->user()->name }}</div>
</div>

<div id="toast"></div>
@endsection

@push('scripts')
<script>
(function () {
  /* ════════════ CONFIG ════════════ */
  const SAVE_URL  = '{{ route("admin.floor-plan.save") }}';
  const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
  const GRID      = 8;
  const ROOM_DATA = @json($roomData);

  /* ════════════ STATE ════════════ */
  let currentRoom = null;
  let roomStates  = {};   // { roomId: { elements: [], history: [], future: [] } }
  let selected    = null;
  let dragging    = null;
  let dragOffset  = { x: 0, y: 0 };
  let snapEnabled = true;
  let zoom        = 1;
  let toastTimer  = null;

  /* ════════════ DOM ════════════ */
  const canvas      = document.getElementById('room-canvas');
  const snapToggle  = document.getElementById('snap-toggle');
  const snapLabel   = document.querySelector('.snap-lbl');
  const zoomVal     = document.getElementById('zoom-val');
  const stTables    = document.getElementById('st-tables');
  const stChairs    = document.getElementById('st-chairs');
  const stCap       = document.getElementById('st-cap');
  const stTotal     = document.getElementById('st-total');
  const selPanel    = document.getElementById('sel-panel');
  const noSel       = document.getElementById('no-sel');
  const selType     = document.getElementById('sel-type');
  const selX        = document.getElementById('sel-x');
  const selY        = document.getElementById('sel-y');
  const selRot      = document.getElementById('sel-rot');
  const selLabel    = document.getElementById('sel-label');
  const sbMsg       = document.getElementById('sb-msg');
  const sbRoom      = document.getElementById('sb-room');
  const toast       = document.getElementById('toast');
  const roomLabel   = document.getElementById('room-label');
  const boundary    = document.getElementById('room-boundary');

  /* ════════════ ELEMENT SIZES ════════════ */
  const SIZES = {
    't-r4':  { w: 56, h: 56 }, 't-r2': { w: 40, h: 40 },
    't-q4':  { w: 64, h: 40 }, 't-q6': { w: 80, h: 40 }, 't-q8': { w: 88, h: 40 },
    't-bar': { w: 80, h: 32 },
    'c-std': { w: 36, h: 36 }, 'c-arm': { w: 40, h: 36 },
    'c-stool': { w: 34, h: 38 }, 'c-bench': { w: 64, h: 32 },
    'd-plant': { w: 40, h: 44 }, 'd-cash': { w: 40, h: 36 },
  };
  const COLORS = {
    't-r4': '#2A2010', 't-r2': '#2A2010', 't-q4': '#2A2010',
    't-q6': '#2A2010', 't-q8': '#2A2010', 't-bar': '#1A2E20',
    'c-std': '#252018', 'c-arm': '#252018', 'c-stool': '#252018', 'c-bench': '#252018',
    'd-plant': '#162215', 'd-cash': '#152030',
  };
  const BORDERS = {
    't-r4': '#D4A853', 't-r2': '#D4A853', 't-q4': '#D4A853',
    't-q6': '#D4A853', 't-q8': '#D4A853', 't-bar': '#3D8B5E',
    'c-std': '#6B6355', 'c-arm': '#6B6355', 'c-stool': '#6B6355', 'c-bench': '#6B6355',
    'd-plant': '#3D8B5E', 'd-cash': '#3A6FA8',
  };
  function getCapacity(type) {
    return { 't-r4':4,'t-r2':2,'t-q4':4,'t-q6':6,'t-q8':8,'c-std':1,'c-arm':1,'c-stool':1,'c-bench':3 }[type] ?? 0;
  }
  function isTable(type) { return type.startsWith('t-'); }
  function isChair(type) { return type.startsWith('c-'); }

  /* ════════════ HELPERS ════════════ */
  function snap(v) { return snapEnabled ? Math.round(v / GRID) * GRID : v; }
  function uid() { return '_' + Math.random().toString(36).slice(2, 9); }

  function state() { return roomStates[currentRoom]; }

  function saveHistory() {
    const s = state();
    s.history.push(JSON.stringify(s.elements));
    if (s.history.length > 60) s.history.shift();
    s.future = [];
  }

  function showToast(msg, type = '') {
    clearTimeout(toastTimer);
    toast.textContent = msg;
    toast.className = 'show ' + type;
    toastTimer = setTimeout(() => { toast.className = ''; }, 2200);
  }

  function setMsg(msg) { if (sbMsg) sbMsg.textContent = msg; }

  /* ════════════ RENDER ════════════ */
  function render() {
    if (!currentRoom) return;
    const s = state();
    canvas.innerHTML = '';
    let tables = 0, chairs = 0, cap = 0, total = 0;

    s.elements.forEach(el => {
      total++;
      if (isTable(el.type))  tables++;
      if (isChair(el.type))  chairs++;
      cap += getCapacity(el.type);

      const sz = SIZES[el.type] ?? { w: 48, h: 48 };
      const div = document.createElement('div');
      div.className = 'ce' + (el.id === (selected?.id) ? ' selected' : '');
      div.style.cssText = `
        left:${el.x}px; top:${el.y}px;
        width:${sz.w}px; height:${sz.h}px;
        background:${COLORS[el.type] ?? '#222'};
        border:1.5px solid ${BORDERS[el.type] ?? '#555'};
        transform:rotate(${el.rot ?? 0}deg);
        border-radius:${el.type.startsWith('t-r') ? '50%' : '5px'};
      `;
      div.dataset.id = el.id;

      if (el.label) {
        const lbl = document.createElement('div');
        lbl.className = 'ce-label';
        lbl.textContent = el.label;
        div.appendChild(lbl);
      }

      div.addEventListener('mousedown', onElMouseDown);
      canvas.appendChild(div);
    });

    stTables.textContent = tables;
    stChairs.textContent = chairs;
    stCap.textContent    = cap;
    stTotal.textContent  = total;

    updateSelPanel();
  }

  function updateSelPanel() {
    if (!selected) {
      selPanel.style.display = 'none';
      noSel.style.display    = '';
      return;
    }
    noSel.style.display    = 'none';
    selPanel.style.display = '';
    selType.textContent  = selected.type;
    selX.value    = selected.x;
    selY.value    = selected.y;
    selRot.value  = selected.rot ?? 0;
    selLabel.value = selected.label ?? '';
  }

  /* ════════════ ROOMS ════════════ */
  function initRooms() {
    document.querySelectorAll('.rtab').forEach(tab => {
      const id = tab.dataset.room;
      roomStates[id] = {
        elements: (ROOM_DATA[id] ?? []).map(e => ({ ...e, id: e.id ?? uid() })),
        history: [],
        future: [],
      };
      tab.addEventListener('click', () => switchRoom(id));
    });
    const first = document.querySelector('.rtab');
    if (first) switchRoom(first.dataset.room);
  }

  function switchRoom(id) {
    currentRoom = id;
    document.querySelectorAll('.rtab').forEach(t => t.classList.toggle('active', t.dataset.room === id));
    const name = document.querySelector(`.rtab[data-room="${id}"]`)?.dataset.name ?? '—';
    if (roomLabel) roomLabel.textContent = name;
    if (sbRoom) sbRoom.textContent = name;
    selected = null;
    render();
    updateBoundary();
    setMsg('Salle : ' + name);
  }

  function updateBoundary() {
    const ca = canvas.getBoundingClientRect();
    if (boundary) {
      boundary.style.cssText = `left:24px;top:24px;right:24px;bottom:24px;`;
    }
  }

  /* ════════════ DRAG FROM PALETTE ════════════ */
  document.querySelectorAll('.pi[draggable]').forEach(pi => {
    pi.addEventListener('dragstart', e => {
      e.dataTransfer.setData('type', pi.dataset.type);
      e.dataTransfer.setData('cap',  pi.dataset.cap);
    });
  });

  canvas.addEventListener('dragover', e => { e.preventDefault(); e.dataTransfer.dropEffect = 'copy'; });
  canvas.addEventListener('drop', e => {
    e.preventDefault();
    const type = e.dataTransfer.getData('type');
    if (!type || !currentRoom) return;
    const rect = canvas.getBoundingClientRect();
    const sz   = SIZES[type] ?? { w: 48, h: 48 };
    const x = snap(e.clientX - rect.left - sz.w / 2);
    const y = snap(e.clientY - rect.top  - sz.h / 2);
    saveHistory();
    state().elements.push({ id: uid(), type, x, y, rot: 0, label: '' });
    render();
    setMsg('Élément ajouté');
  });

  /* ════════════ ELEMENT DRAG (MOVE) ════════════ */
  function onElMouseDown(e) {
    if (e.button !== 0) return;
    e.stopPropagation();
    const id   = e.currentTarget.dataset.id;
    const el   = state().elements.find(el => el.id === id);
    if (!el) return;
    selected   = el;
    dragging   = el;
    const rect = e.currentTarget.getBoundingClientRect();
    dragOffset = { x: e.clientX - rect.left, y: e.clientY - rect.top };
    saveHistory();
    render();
    setMsg('Déplacement…');
  }

  window.addEventListener('mousemove', e => {
    if (!dragging) return;
    const rect = canvas.getBoundingClientRect();
    dragging.x = snap(e.clientX - rect.left - dragOffset.x);
    dragging.y = snap(e.clientY - rect.top  - dragOffset.y);
    render();
  });

  window.addEventListener('mouseup', () => {
    if (dragging) { dragging = null; setMsg('Prêt'); }
  });

  canvas.addEventListener('mousedown', e => {
    if (e.target === canvas || e.target === document.getElementById('room-canvas')) {
      selected = null; render();
    }
  });

  /* ════════════ PROPERTY PANEL ════════════ */
  function syncSelected(field, value) {
    if (!selected) return;
    const el = state().elements.find(e => e.id === selected.id);
    if (el) { el[field] = value; selected = el; render(); }
  }
  selX.addEventListener('input', () => syncSelected('x', parseInt(selX.value) || 0));
  selY.addEventListener('input', () => syncSelected('y', parseInt(selY.value) || 0));
  selRot.addEventListener('input', () => syncSelected('rot', parseInt(selRot.value) || 0));
  selLabel.addEventListener('input', () => syncSelected('label', selLabel.value));

  document.getElementById('btn-del-sel').addEventListener('click', () => {
    if (!selected) return;
    saveHistory();
    state().elements = state().elements.filter(e => e.id !== selected.id);
    selected = null;
    render();
    setMsg('Élément supprimé');
  });

  /* ════════════ KEYBOARD ════════════ */
  window.addEventListener('keydown', e => {
    if (!selected || e.target.tagName === 'INPUT') return;
    const step = e.shiftKey ? GRID * 3 : GRID;
    const el   = state().elements.find(el => el.id === selected.id);
    if (!el) return;
    if (e.key === 'Delete' || e.key === 'Backspace') {
      saveHistory();
      state().elements = state().elements.filter(el => el.id !== selected.id);
      selected = null; render(); setMsg('Supprimé');
    }
    if (e.key === 'ArrowLeft')  { saveHistory(); el.x -= step; render(); }
    if (e.key === 'ArrowRight') { saveHistory(); el.x += step; render(); }
    if (e.key === 'ArrowUp')    { saveHistory(); el.y -= step; render(); }
    if (e.key === 'ArrowDown')  { saveHistory(); el.y += step; render(); }
    if ((e.ctrlKey || e.metaKey) && e.key === 'z') { e.preventDefault(); undo(); }
    if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.shiftKey && e.key === 'z'))) { e.preventDefault(); redo(); }
  });

  /* ════════════ UNDO / REDO ════════════ */
  function undo() {
    const s = state();
    if (!s.history.length) { showToast('Rien à annuler', ''); return; }
    s.future.push(JSON.stringify(s.elements));
    s.elements = JSON.parse(s.history.pop());
    selected = null; render(); setMsg('Annuler');
  }
  function redo() {
    const s = state();
    if (!s.future.length) { showToast('Rien à rétablir', ''); return; }
    s.history.push(JSON.stringify(s.elements));
    s.elements = JSON.parse(s.future.pop());
    selected = null; render(); setMsg('Rétablir');
  }
  document.getElementById('btn-undo').addEventListener('click', undo);
  document.getElementById('btn-redo').addEventListener('click', redo);

  /* ════════════ SNAP ════════════ */
  snapToggle.addEventListener('change', () => {
    snapEnabled = snapToggle.checked;
    snapLabel.classList.toggle('on', snapEnabled);
    setMsg(snapEnabled ? 'Grille magnétique activée' : 'Grille magnétique désactivée');
  });
  snapLabel.classList.add('on');

  /* ════════════ ZOOM ════════════ */
  function setZoom(z) {
    zoom = Math.min(2, Math.max(.3, z));
    canvas.style.transform = `scale(${zoom})`;
    canvas.style.transformOrigin = '0 0';
    if (zoomVal) zoomVal.textContent = Math.round(zoom * 100) + '%';
  }
  document.getElementById('btn-zoom-in').addEventListener('click',  () => setZoom(zoom + .1));
  document.getElementById('btn-zoom-out').addEventListener('click', () => setZoom(zoom - .1));

  /* ════════════ CLEAR ════════════ */
  document.getElementById('btn-clear').addEventListener('click', () => {
    if (!currentRoom) return;
    if (!confirm('Vider le plan de cette salle ?')) return;
    saveHistory();
    state().elements = [];
    selected = null; render(); setMsg('Plan vidé');
  });

  /* ════════════ EXPORT (PNG) ════════════ */
  document.getElementById('btn-export').addEventListener('click', () => {
    showToast('Export PNG bientôt disponible', '');
  });

  /* ════════════ SAVE ════════════ */
  document.getElementById('btn-save').addEventListener('click', async () => {
    const roomData = {};
    Object.entries(roomStates).forEach(([id, s]) => { roomData[id] = s.elements; });

    setMsg('Enregistrement…');
    try {
      const res = await fetch(SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ room_data: roomData }),
      });
      const data = await res.json();
      if (data.success) {
        showToast('Plan enregistré ✓', 'ok');
        setMsg('Enregistré avec succès');
      } else {
        showToast('Erreur lors de l\'enregistrement', 'err');
        setMsg('Erreur d\'enregistrement');
      }
    } catch (err) {
      showToast('Erreur réseau', 'err');
      setMsg('Erreur réseau');
    }
  });

  /* ════════════ INIT ════════════ */
  initRooms();
  updateBoundary();
  window.addEventListener('resize', updateBoundary);
})();
</script>
@endpush
