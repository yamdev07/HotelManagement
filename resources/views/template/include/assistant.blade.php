@auth
@if (! empty(config('services.groq.key')))
<div id="aiAssistant" class="aia-wrap" data-endpoint="{{ route('assistant.chat') }}">
    <button type="button" class="aia-fab" id="aiaFab" aria-label="Assistant IA" title="Assistant IA">
        <i class="fas fa-wand-magic-sparkles"></i>
        <span class="aia-dot"></span>
    </button>

    <div class="aia-panel" id="aiaPanel" role="dialog" aria-label="Assistant de gestion">
        <div class="aia-head">
            <div class="aia-ava"><i class="fas fa-wand-magic-sparkles"></i></div>
            <div class="aia-htxt">
                <div class="aia-name">Assistant IA</div>
                <div class="aia-sub">Votre copilote de gestion</div>
            </div>
            <button type="button" class="aia-close" id="aiaClose" aria-label="Fermer"><i class="fas fa-times"></i></button>
        </div>

        <div class="aia-body" id="aiaBody">
            <div class="aia-msg aia-bot">Bonjour {{ explode(' ', trim(auth()->user()->name))[0] ?? '' }} 👋 Demandez-moi l'état de l'hôtel (chambres libres, arrivées du jour, encaissements…) ou comment faire une action dans l'app.</div>
            <div class="aia-sugg">
                <button type="button" class="aia-chip">Combien de chambres libres ?</button>
                <button type="button" class="aia-chip">Qui arrive aujourd'hui ?</button>
                <button type="button" class="aia-chip">Combien j'ai encaissé aujourd'hui ?</button>
            </div>
        </div>

        <form class="aia-input" id="aiaForm" autocomplete="off">
            <input type="text" id="aiaText" placeholder="Écrivez votre message…" maxlength="1000" required>
            <button type="submit" aria-label="Envoyer"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<style>
    .aia-wrap{ --aia:var(--g600,#2e8540); --aia-panel:#fff; --aia-line:#e6ebe8; --aia-ink:#1a211d; --aia-ink2:#5c655f;
        --aia-bot:#f2f5f3; position:fixed; right:22px; bottom:22px; z-index:1080; font-family:inherit; }
    html[data-theme="dark"] .aia-wrap{ --aia-panel:#161b18; --aia-line:#28312b; --aia-ink:#e8ede9; --aia-ink2:#9aa39c; --aia-bot:#1f2622; }

    .aia-fab{ width:58px; height:58px; border-radius:50%; border:none; cursor:pointer; position:relative;
        background:var(--aia); color:#fff; font-size:1.35rem; box-shadow:0 12px 30px -8px var(--aia);
        display:grid; place-items:center; transition:transform .2s; }
    .aia-fab:hover{ transform:translateY(-3px) scale(1.05); }
    .aia-dot{ position:absolute; top:5px; right:7px; width:11px; height:11px; border-radius:50%; background:#22c55e; box-shadow:0 0 0 3px rgba(255,255,255,.25); animation:aiaPulse 2s infinite; }
    @keyframes aiaPulse{ 0%,100%{opacity:1} 50%{opacity:.35} }

    .aia-panel{ position:absolute; right:0; bottom:72px; width:min(390px,calc(100vw - 30px)); height:min(580px,calc(100vh - 130px));
        background:var(--aia-panel); border:1px solid var(--aia-line); border-radius:18px; overflow:hidden;
        display:none; flex-direction:column; box-shadow:0 28px 64px -18px rgba(0,0,0,.35); }
    .aia-panel.open{ display:flex; animation:aiaUp .22s ease; }
    @keyframes aiaUp{ from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:translateY(0)} }

    .aia-head{ display:flex; align-items:center; gap:11px; padding:13px 15px; background:var(--aia); color:#fff; }
    .aia-ava{ width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.18); display:grid; place-items:center; }
    .aia-name{ font-weight:700; font-size:.95rem; line-height:1.1; }
    .aia-sub{ font-size:.74rem; opacity:.8; }
    .aia-close{ margin-left:auto; background:transparent; border:none; color:#fff; opacity:.85; font-size:1rem; cursor:pointer; padding:6px; }
    .aia-close:hover{ opacity:1; }

    .aia-body{ flex:1; overflow-y:auto; padding:15px; display:flex; flex-direction:column; gap:10px; background:var(--aia-panel); }
    .aia-msg{ max-width:85%; padding:10px 13px; border-radius:13px; font-size:.9rem; line-height:1.5; white-space:pre-wrap; word-wrap:break-word; }
    .aia-bot{ align-self:flex-start; background:var(--aia-bot); color:var(--aia-ink); border-bottom-left-radius:4px; }
    .aia-user{ align-self:flex-end; background:var(--aia); color:#fff; border-bottom-right-radius:4px; }
    .aia-sugg{ display:flex; flex-wrap:wrap; gap:6px; }
    .aia-chip{ border:1px solid var(--aia-line); background:transparent; color:var(--aia); border-radius:100px; padding:6px 11px; font-size:.78rem; font-weight:600; cursor:pointer; transition:.15s; }
    .aia-chip:hover{ background:var(--aia); color:#fff; }
    .aia-typing{ align-self:flex-start; color:var(--aia-ink2); font-size:.85rem; padding:4px 2px; }
    .aia-typing span{ display:inline-block; width:6px; height:6px; margin:0 1px; border-radius:50%; background:currentColor; animation:aiaBlink 1.2s infinite; }
    .aia-typing span:nth-child(2){ animation-delay:.2s } .aia-typing span:nth-child(3){ animation-delay:.4s }
    @keyframes aiaBlink{ 0%,100%{opacity:.2} 50%{opacity:1} }

    .aia-input{ display:flex; gap:8px; padding:11px; border-top:1px solid var(--aia-line); background:var(--aia-panel); }
    .aia-input input{ flex:1; background:transparent; border:1px solid var(--aia-line); border-radius:11px; padding:10px 12px; color:var(--aia-ink); font-size:.9rem; }
    .aia-input input:focus{ outline:none; border-color:var(--aia); }
    .aia-input button{ width:42px; border:none; border-radius:11px; background:var(--aia); color:#fff; cursor:pointer; transition:.2s; }
    .aia-input button:disabled{ opacity:.5; cursor:default; }
</style>

<script>
(function () {
    var wrap = document.getElementById('aiAssistant');
    if (!wrap) return;
    var endpoint = wrap.dataset.endpoint;
    var fab = document.getElementById('aiaFab');
    var panel = document.getElementById('aiaPanel');
    var body = document.getElementById('aiaBody');
    var form = document.getElementById('aiaForm');
    var input = document.getElementById('aiaText');
    var sendBtn = form.querySelector('button');
    var csrf = document.querySelector('meta[name="csrf-token"]');
    csrf = csrf ? csrf.getAttribute('content') : '';
    var history = [];
    var busy = false;

    function toggle(open) {
        panel.classList.toggle('open', open);
        if (open) setTimeout(function () { input.focus(); }, 100);
    }
    fab.addEventListener('click', function () { toggle(!panel.classList.contains('open')); });
    document.getElementById('aiaClose').addEventListener('click', function () { toggle(false); });

    function bubble(text, who) {
        var d = document.createElement('div');
        d.className = 'aia-msg ' + (who === 'user' ? 'aia-user' : 'aia-bot');
        d.textContent = text;
        body.appendChild(d);
        body.scrollTop = body.scrollHeight;
    }
    function typing(on) {
        var ex = document.getElementById('aiaTyping');
        if (on && !ex) {
            var d = document.createElement('div');
            d.id = 'aiaTyping'; d.className = 'aia-typing';
            d.innerHTML = '<span></span><span></span><span></span>';
            body.appendChild(d); body.scrollTop = body.scrollHeight;
        } else if (!on && ex) { ex.remove(); }
    }
    function send(text) {
        if (!text || busy) return;
        var sugg = body.querySelector('.aia-sugg'); if (sugg) sugg.remove();
        busy = true; sendBtn.disabled = true;
        bubble(text, 'user');
        history.push({ role: 'user', content: text });
        typing(true);
        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ messages: history.slice(-12) })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            typing(false);
            var reply = (d && d.reply) ? d.reply : "Désolé, je n'ai pas pu répondre.";
            bubble(reply, 'bot');
            if (d && d.ok) history.push({ role: 'assistant', content: reply });
        })
        .catch(function () { typing(false); bubble("Erreur réseau. Réessayez.", 'bot'); })
        .finally(function () { busy = false; sendBtn.disabled = false; input.focus(); });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = input.value.trim();
        input.value = '';
        send(text);
    });
    body.addEventListener('click', function (e) {
        if (e.target.classList.contains('aia-chip')) send(e.target.textContent.trim());
    });
})();
</script>
@endif
@endauth
