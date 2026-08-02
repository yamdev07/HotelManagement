@auth
@if (! empty(config('services.groq.key')))
<div id="aiAssistant" class="aia-wrap" data-endpoint="{{ route('assistant.chat') }}" data-transcribe="{{ route('assistant.transcribe') }}" data-execute="{{ route('assistant.execute') }}">
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
            <button type="button" class="aia-hbtn" id="aiaSpeaker" aria-label="Lecture vocale" title="Lire les réponses à voix haute"><i class="fas fa-volume-xmark"></i></button>
            <button type="button" class="aia-hbtn aia-close" id="aiaClose" aria-label="Fermer"><i class="fas fa-times"></i></button>
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
            <button type="button" class="aia-mic" id="aiaMic" aria-label="Message vocal" title="Parler"><i class="fas fa-microphone"></i></button>
            <input type="text" id="aiaText" placeholder="Écrivez ou parlez…" maxlength="1000">
            <button type="submit" class="aia-send" aria-label="Envoyer"><i class="fas fa-paper-plane"></i></button>
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
    .aia-hbtn{ background:transparent; border:none; color:#fff; opacity:.85; font-size:1rem; cursor:pointer; padding:6px; }
    .aia-hbtn:hover{ opacity:1; }
    #aiaSpeaker{ margin-left:auto; }
    #aiaSpeaker.on{ color:#c9f5d8; opacity:1; }

    .aia-body{ flex:1; overflow-y:auto; padding:15px; display:flex; flex-direction:column; gap:10px; background:var(--aia-panel); }
    .aia-msg{ max-width:85%; padding:10px 13px; border-radius:13px; font-size:.9rem; line-height:1.5; white-space:pre-wrap; word-wrap:break-word; }
    .aia-bot{ align-self:flex-start; background:var(--aia-bot); color:var(--aia-ink); border-bottom-left-radius:4px; }
    .aia-readbtn{ border:none; background:transparent; color:var(--aia); cursor:pointer; font-size:.78rem; margin-left:6px; padding:2px 6px; border-radius:6px; opacity:.5; transition:.15s; vertical-align:baseline; }
    .aia-readbtn:hover{ opacity:1; background:color-mix(in srgb, var(--aia) 14%, transparent); }
    .aia-readbtn.playing{ opacity:1; color:#dc2626; }
    .aia-user{ align-self:flex-end; background:var(--aia); color:#fff; border-bottom-right-radius:4px; }
    .aia-sugg{ display:flex; flex-wrap:wrap; gap:6px; }
    .aia-chip{ border:1px solid var(--aia-line); background:transparent; color:var(--aia); border-radius:100px; padding:6px 11px; font-size:.78rem; font-weight:600; cursor:pointer; transition:.15s; }
    .aia-chip:hover{ background:var(--aia); color:#fff; }
    .aia-confirm{ align-self:stretch; background:var(--aia-bot); border:1px solid var(--aia-line); border-radius:13px; padding:12px 14px; }
    .aia-confirm-txt{ font-size:.9rem; color:var(--aia-ink); line-height:1.45; }
    .aia-confirm-txt i{ color:#d99e00; margin-right:4px; }
    .aia-confirm-btns{ display:flex; gap:8px; justify-content:flex-end; margin-top:11px; }
    .aia-cbtn{ border:1px solid var(--aia-line); background:transparent; color:var(--aia-ink2); border-radius:9px; padding:7px 14px; font-size:.83rem; font-weight:700; cursor:pointer; }
    .aia-cbtn.ok{ background:var(--aia); color:#fff; border-color:transparent; }
    .aia-cbtn:disabled{ opacity:.6; cursor:default; }
    .aia-typing{ align-self:flex-start; color:var(--aia-ink2); font-size:.85rem; padding:4px 2px; }
    .aia-typing span{ display:inline-block; width:6px; height:6px; margin:0 1px; border-radius:50%; background:currentColor; animation:aiaBlink 1.2s infinite; }
    .aia-typing span:nth-child(2){ animation-delay:.2s } .aia-typing span:nth-child(3){ animation-delay:.4s }
    @keyframes aiaBlink{ 0%,100%{opacity:.2} 50%{opacity:1} }

    .aia-input{ display:flex; gap:8px; padding:11px; border-top:1px solid var(--aia-line); background:var(--aia-panel); }
    .aia-input input{ flex:1; background:transparent; border:1px solid var(--aia-line); border-radius:11px; padding:10px 12px; color:var(--aia-ink); font-size:.9rem; }
    .aia-input input:focus{ outline:none; border-color:var(--aia); }
    .aia-input button{ width:42px; flex:0 0 42px; border-radius:11px; cursor:pointer; transition:.2s; border:none; }
    .aia-input .aia-send{ background:var(--aia); color:#fff; }
    .aia-input .aia-mic{ background:transparent; color:var(--aia); border:1px solid var(--aia-line); }
    .aia-input .aia-mic:hover{ background:var(--aia); color:#fff; }
    .aia-input .aia-mic.rec{ background:#dc2626; color:#fff; border-color:#dc2626; animation:aiaRec 1s infinite; }
    @keyframes aiaRec{ 0%,100%{box-shadow:0 0 0 0 rgba(220,38,38,.5)} 50%{box-shadow:0 0 0 7px rgba(220,38,38,0)} }
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
    var sendBtn = form.querySelector('.aia-send');
    var micBtn = document.getElementById('aiaMic');
    var speakerBtn = document.getElementById('aiaSpeaker');
    var transcribeUrl = wrap.dataset.transcribe;
    var executeUrl = wrap.dataset.execute;
    var csrf = document.querySelector('meta[name="csrf-token"]');
    csrf = csrf ? csrf.getAttribute('content') : '';
    var history = [];
    var busy = false;

    // ── Lecture vocale (synthèse du navigateur) ──
    var ttsOK = ('speechSynthesis' in window) && ('SpeechSynthesisUtterance' in window);
    var speakOn = localStorage.getItem('aiaSpeak') === '1';
    var voices = [];
    function loadVoices() { if (ttsOK) { voices = window.speechSynthesis.getVoices() || []; } }
    if (ttsOK) { loadVoices(); window.speechSynthesis.onvoiceschanged = loadVoices; }
    var MALE_HINTS = ['david','mark','george','paul','henri','alain','claude','thomas','guillaume','male','homme','fritz','christopher','ryan','guy','eric','brian','james','william','antoine','nicolas','remy','jean'];
    function isMale(v) { var n = (v.name || '').toLowerCase(); return MALE_HINTS.some(function (h) { return n.indexOf(h) > -1; }); }
    function pickVoice(lang) {
        if (!voices.length) loadVoices();
        var p = lang.slice(0, 2).toLowerCase();
        var forLang = voices.filter(function (v) { return v.lang && v.lang.toLowerCase().indexOf(p) === 0; });
        return forLang.filter(isMale)[0]   // 1) voix d'homme dans la bonne langue (idéal)
            || voices.filter(isMale)[0]     // 2) sinon n'importe quelle voix d'homme (l'utilisateur veut un homme)
            || forLang[0]                   // 3) sinon une voix de la langue
            || voices.filter(function (v) { return v.default; })[0]
            || voices[0] || null;
    }
    function detectLang(text) {
        return (/\b(the|you|your|are|room|hello|price|available|today|rooms)\b/i.test(text) && !/[àâçéèêëîïôûùü]/i.test(text)) ? 'en-US' : 'fr-FR';
    }
    function renderSpeaker() {
        speakerBtn.classList.toggle('on', speakOn);
        speakerBtn.querySelector('i').className = speakOn ? 'fas fa-volume-high' : 'fas fa-volume-xmark';
    }
    renderSpeaker();
    // Lecture immédiate d'un texte (à la demande) — ignore le réglage auto.
    function doSpeak(text, onDone) {
        if (!ttsOK || !text) { if (onDone) onDone(); return; }
        try {
            // On n'annule que si nécessaire (cancel()+speak() immédiat casse Chrome).
            if (window.speechSynthesis.speaking || window.speechSynthesis.pending) window.speechSynthesis.cancel();
            var lang = detectLang(text);
            var u = new SpeechSynthesisUtterance(text);
            u.lang = lang;
            var v = pickVoice(lang);
            if (v) u.voice = v;
            u.rate = 1; u.pitch = 1; u.volume = 1;
            if (onDone) { u.onend = onDone; u.onerror = onDone; }
            window.speechSynthesis.speak(u); // synchrone => reste dans le geste utilisateur
        } catch (e) { if (onDone) onDone(); }
    }
    // Lecture automatique (seulement si l'option est activée).
    function speak(text) { if (speakOn) doSpeak(text); }
    speakerBtn.addEventListener('click', function () {
        speakOn = !speakOn;
        localStorage.setItem('aiaSpeak', speakOn ? '1' : '0');
        renderSpeaker();
        if (speakOn) {
            if (!ttsOK) { bubble("La lecture vocale n'est pas supportée par ce navigateur.", 'bot'); return; }
            speak('Lecture vocale activée.'); // retour immédiat (dans le geste => fiable)
        } else if (ttsOK) {
            window.speechSynthesis.cancel();
        }
    });

    function toggle(open) {
        panel.classList.toggle('open', open);
        if (open) setTimeout(function () { input.focus(); }, 100);
        else if ('speechSynthesis' in window) window.speechSynthesis.cancel();
    }
    fab.addEventListener('click', function () { toggle(!panel.classList.contains('open')); });
    document.getElementById('aiaClose').addEventListener('click', function () { toggle(false); });

    function bubble(text, who) {
        var d = document.createElement('div');
        d.className = 'aia-msg ' + (who === 'user' ? 'aia-user' : 'aia-bot');
        if (who !== 'bot' || !ttsOK) {
            d.textContent = text;
        } else {
            // Message de l'assistant : texte + bouton « lire ce message ».
            var span = document.createElement('span');
            span.className = 'aia-msg-txt';
            span.textContent = text;
            var read = document.createElement('button');
            read.className = 'aia-readbtn';
            read.type = 'button';
            read.title = 'Lire ce message';
            read.setAttribute('aria-label', 'Lire ce message');
            read.innerHTML = '<i class="fas fa-volume-high"></i>';
            read.addEventListener('click', function () {
                var wasPlaying = read.classList.contains('playing');
                // Réinitialise les autres boutons + coupe la lecture en cours.
                Array.prototype.forEach.call(body.querySelectorAll('.aia-readbtn.playing'), function (b) { b.classList.remove('playing'); });
                if (ttsOK) window.speechSynthesis.cancel();
                if (wasPlaying) return; // re-clic = stop
                read.classList.add('playing');
                doSpeak(text, function () { read.classList.remove('playing'); });
            });
            d.appendChild(span);
            d.appendChild(read);
        }
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
            speak(reply);
            if (d && d.pending) confirmCard(d.pending);
        })
        .catch(function () { typing(false); bubble("Erreur réseau. Réessayez.", 'bot'); })
        .finally(function () { busy = false; sendBtn.disabled = false; input.focus(); });
    }

    // Carte de confirmation avant une action qui modifie des données.
    function confirmCard(pending) {
        var card = document.createElement('div');
        card.className = 'aia-confirm';
        var txt = document.createElement('div');
        txt.className = 'aia-confirm-txt';
        txt.innerHTML = '<i class="fas fa-triangle-exclamation"></i> ' + (pending.summary || 'Confirmer cette action ?');
        var btns = document.createElement('div');
        btns.className = 'aia-confirm-btns';
        var ok = document.createElement('button');
        ok.className = 'aia-cbtn ok'; ok.innerHTML = '<i class="fas fa-check"></i> Confirmer';
        var no = document.createElement('button');
        no.className = 'aia-cbtn no'; no.textContent = 'Annuler';
        btns.appendChild(no); btns.appendChild(ok);
        card.appendChild(txt); card.appendChild(btns);
        body.appendChild(card); body.scrollTop = body.scrollHeight;

        no.addEventListener('click', function () { card.remove(); bubble('Action annulée.', 'bot'); });
        ok.addEventListener('click', function () {
            ok.disabled = true; no.disabled = true; ok.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            fetch(executeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ tool: pending.tool, args: pending.args || {} })
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                card.remove();
                var msg = (d && d.message) ? d.message : ((d && d.ok) ? 'Action effectuée.' : "L'action a échoué.");
                bubble(msg, 'bot'); speak(msg);
                history.push({ role: 'assistant', content: msg });
            })
            .catch(function () { card.remove(); bubble("Erreur lors de l'exécution.", 'bot'); });
        });
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

    // ── Message vocal (enregistrement -> Groq Whisper -> texte) ──
    var mediaRec = null, chunks = [], recording = false;
    micBtn.addEventListener('click', function () {
        if (busy) return;
        if (recording) { if (mediaRec) mediaRec.stop(); return; }
        if (!navigator.mediaDevices || !window.MediaRecorder) {
            bubble("La saisie vocale n'est pas supportée par ce navigateur.", 'bot');
            return;
        }
        navigator.mediaDevices.getUserMedia({ audio: true }).then(function (stream) {
            chunks = [];
            mediaRec = new MediaRecorder(stream);
            mediaRec.ondataavailable = function (e) { if (e.data && e.data.size) chunks.push(e.data); };
            mediaRec.onstop = function () {
                recording = false; micBtn.classList.remove('rec');
                stream.getTracks().forEach(function (t) { t.stop(); });
                var blob = new Blob(chunks, { type: (mediaRec && mediaRec.mimeType) || 'audio/webm' });
                if (!blob.size) return;
                var fd = new FormData();
                fd.append('audio', blob, 'message.webm');
                micBtn.disabled = true; input.placeholder = 'Transcription…';
                fetch(transcribeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd })
                    .then(function (r) { return r.json(); })
                    .then(function (d) {
                        if (d && d.ok && d.text) send(d.text);
                        else bubble("Je n'ai pas compris l'audio. Réessayez.", 'bot');
                    })
                    .catch(function () { bubble("Erreur lors de la transcription.", 'bot'); })
                    .finally(function () { micBtn.disabled = false; input.placeholder = 'Écrivez ou parlez…'; });
            };
            mediaRec.start();
            recording = true; micBtn.classList.add('rec');
        }).catch(function () {
            bubble("Micro inaccessible. Autorisez le micro dans votre navigateur.", 'bot');
        });
    });
})();
</script>
@endif
@endauth
