@if (($hotel->show_assistant ?? true) && ! empty(config('services.groq.key')))
<div id="aiAssistant" class="ai-wrap" data-endpoint="{{ route('public.hotel.assistant', $hotel->slug) }}" data-hotel="{{ $hotel->name }}">
    <button type="button" class="ai-fab" id="aiFab" aria-label="Ouvrir l'assistant">
        <i class="fas fa-comment-dots"></i>
        <span class="ai-fab-dot"></span>
    </button>

    <div class="ai-panel" id="aiPanel" role="dialog" aria-label="Assistant virtuel">
        <div class="ai-head">
            <div class="ai-head-ava"><i class="fas fa-robot"></i></div>
            <div class="ai-head-txt">
                <div class="ai-head-name">Assistant</div>
                <div class="ai-head-sub">{{ $hotel->name }}</div>
            </div>
            <button type="button" class="ai-close" id="aiClose" aria-label="Fermer"><i class="fas fa-times"></i></button>
        </div>

        <div class="ai-body" id="aiBody">
            <div class="ai-msg ai-bot">Bonjour&nbsp;! 👋 Je suis l'assistant de {{ $hotel->name }}. Posez-moi vos questions sur les chambres, les prix, les services… ou pour réserver.</div>
        </div>

        <form class="ai-input" id="aiForm" autocomplete="off">
            <input type="text" id="aiText" placeholder="Écrivez votre message…" maxlength="1000" required>
            <button type="submit" aria-label="Envoyer"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<style>
    .ai-wrap{ position:fixed; right:22px; bottom:22px; z-index:1200; font-family:var(--sans, system-ui); }
    .ai-fab{ width:60px; height:60px; border-radius:50%; border:none; cursor:pointer; position:relative;
        background:var(--c,#2e8540); color:#fff; font-size:1.5rem; box-shadow:0 14px 34px -8px var(--c,#2e8540);
        display:grid; place-items:center; transition:transform .2s, box-shadow .2s; }
    .ai-fab:hover{ transform:translateY(-3px) scale(1.04); }
    .ai-fab-dot{ position:absolute; top:6px; right:8px; width:11px; height:11px; border-radius:50%; background:#22c55e; box-shadow:0 0 0 3px rgba(255,255,255,.15); animation:aiPulse 2s infinite; }
    @keyframes aiPulse{ 0%,100%{opacity:1} 50%{opacity:.35} }

    .ai-panel{ position:absolute; right:0; bottom:76px; width:min(380px,calc(100vw - 32px)); height:min(560px,calc(100vh - 130px));
        background:#0f1216; border:1px solid rgba(255,255,255,.10); border-radius:20px; overflow:hidden;
        display:none; flex-direction:column; box-shadow:0 30px 70px -20px rgba(0,0,0,.7); backdrop-filter:blur(12px); }
    .ai-panel.open{ display:flex; animation:aiUp .25s ease; }
    @keyframes aiUp{ from{opacity:0; transform:translateY(12px)} to{opacity:1; transform:translateY(0)} }

    .ai-head{ display:flex; align-items:center; gap:11px; padding:14px 16px; background:linear-gradient(135deg, color-mix(in srgb, var(--c,#2e8540) 26%, #0f1216), #0f1216); border-bottom:1px solid rgba(255,255,255,.08); }
    .ai-head-ava{ width:38px; height:38px; border-radius:50%; background:var(--c,#2e8540); color:#fff; display:grid; place-items:center; box-shadow:0 0 18px -3px var(--c,#2e8540); }
    .ai-head-name{ color:#fff; font-weight:700; font-size:.98rem; line-height:1.1; }
    .ai-head-sub{ color:rgba(255,255,255,.55); font-size:.76rem; }
    .ai-close{ margin-left:auto; background:transparent; border:none; color:rgba(255,255,255,.6); font-size:1rem; cursor:pointer; padding:6px; }
    .ai-close:hover{ color:#fff; }

    .ai-body{ flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:10px; }
    .ai-msg{ max-width:82%; padding:10px 13px; border-radius:14px; font-size:.9rem; line-height:1.5; white-space:pre-wrap; word-wrap:break-word; }
    .ai-bot{ align-self:flex-start; background:rgba(255,255,255,.06); color:#e8ecf0; border:1px solid rgba(255,255,255,.08); border-bottom-left-radius:4px; }
    .ai-user{ align-self:flex-end; background:var(--c,#2e8540); color:#fff; border-bottom-right-radius:4px; }
    .ai-typing{ align-self:flex-start; color:rgba(255,255,255,.5); font-size:.85rem; padding:6px 4px; }
    .ai-typing span{ display:inline-block; width:6px; height:6px; margin:0 1px; border-radius:50%; background:currentColor; animation:aiBlink 1.2s infinite; }
    .ai-typing span:nth-child(2){ animation-delay:.2s } .ai-typing span:nth-child(3){ animation-delay:.4s }
    @keyframes aiBlink{ 0%,100%{opacity:.2} 50%{opacity:1} }

    .ai-input{ display:flex; gap:8px; padding:12px; border-top:1px solid rgba(255,255,255,.08); background:#0c0f12; }
    .ai-input input{ flex:1; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.10); border-radius:12px; padding:11px 13px; color:#fff; font-size:.9rem; }
    .ai-input input::placeholder{ color:rgba(255,255,255,.4); }
    .ai-input input:focus{ outline:none; border-color:var(--c,#2e8540); }
    .ai-input button{ width:44px; border:none; border-radius:12px; background:var(--c,#2e8540); color:#fff; cursor:pointer; font-size:.95rem; transition:.2s; }
    .ai-input button:disabled{ opacity:.5; cursor:default; }
</style>

<script>
(function () {
    var wrap = document.getElementById('aiAssistant');
    if (!wrap) return;
    var endpoint = wrap.dataset.endpoint;
    var fab = document.getElementById('aiFab');
    var panel = document.getElementById('aiPanel');
    var body = document.getElementById('aiBody');
    var form = document.getElementById('aiForm');
    var input = document.getElementById('aiText');
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
    document.getElementById('aiClose').addEventListener('click', function () { toggle(false); });

    function bubble(text, who) {
        var d = document.createElement('div');
        d.className = 'ai-msg ' + (who === 'user' ? 'ai-user' : 'ai-bot');
        d.textContent = text;
        body.appendChild(d);
        body.scrollTop = body.scrollHeight;
        return d;
    }
    function typing(on) {
        var ex = document.getElementById('aiTyping');
        if (on && !ex) {
            var d = document.createElement('div');
            d.id = 'aiTyping'; d.className = 'ai-typing';
            d.innerHTML = '<span></span><span></span><span></span>';
            body.appendChild(d); body.scrollTop = body.scrollHeight;
        } else if (!on && ex) { ex.remove(); }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var text = input.value.trim();
        if (!text || busy) return;

        busy = true; sendBtn.disabled = true;
        bubble(text, 'user');
        history.push({ role: 'user', content: text });
        input.value = '';
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
        .catch(function () {
            typing(false);
            bubble("Désolé, une erreur réseau est survenue. Réessayez.", 'bot');
        })
        .finally(function () { busy = false; sendBtn.disabled = false; input.focus(); });
    });
})();
</script>
@endif
