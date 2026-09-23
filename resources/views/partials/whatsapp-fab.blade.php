@php
    // Bouton WhatsApp flottant (commercial/support). Numéro normalisé en format international.
    $__waRaw = config('services.whatsapp.support');
    $__cc = (string) config('services.whatsapp.default_country', '229');
    $__wa = preg_replace('/\D+/', '', (string) $__waRaw);
    // Préfixe international "00" éventuel retiré, puis indicatif pays ajouté si absent.
    // NB : au Bénin (2020+) les mobiles font 10 chiffres commençant par 01 et le 0 se
    // conserve à l'international (+229 01...), donc on n'enlève PAS le 0 national.
    if (str_starts_with($__wa, '00')) {
        $__wa = substr($__wa, 2);
    }
    if ($__wa && ! str_starts_with($__wa, $__cc)) {
        $__wa = $__cc.$__wa;
    }
    $__choices = [
        ['fa-circle-play', __('whatsapp.choice_demo'),    __('whatsapp.msg_demo')],
        ['fa-hotel',       __('whatsapp.choice_hotel'),   __('whatsapp.msg_hotel')],
        ['fa-tags',        __('whatsapp.choice_pricing'), __('whatsapp.msg_pricing')],
        ['fa-life-ring',   __('whatsapp.choice_help'),    __('whatsapp.msg_help')],
    ];
@endphp
@if ($__wa)
<style>
    .wa-fab { position:fixed; right:20px; bottom:20px; z-index:1000; font-family:system-ui,-apple-system,sans-serif; }
    .wa-btn { width:58px; height:58px; border-radius:50%; border:0; cursor:pointer; background:#25d366; color:#fff;
        font-size:1.7rem; display:grid; place-items:center; box-shadow:0 12px 30px -8px rgba(37,211,102,.7); transition:transform .15s; }
    .wa-btn:hover { transform:scale(1.06); }
    .wa-menu { position:absolute; right:0; bottom:72px; width:280px; background:#fff; color:#0b141a; border-radius:16px;
        box-shadow:0 24px 60px -20px rgba(0,0,0,.5); overflow:hidden; opacity:0; visibility:hidden; transform:translateY(10px);
        transition:opacity .18s, transform .18s, visibility .18s; }
    .wa-fab.open .wa-menu { opacity:1; visibility:visible; transform:none; }
    .wa-head { background:#075e54; color:#fff; padding:14px 16px; }
    .wa-head b { display:block; font-size:.92rem; }
    .wa-head span { font-size:.76rem; opacity:.85; }
    .wa-list { padding:8px; display:flex; flex-direction:column; gap:4px; }
    .wa-item { display:flex; align-items:center; gap:11px; padding:11px 12px; border-radius:10px; color:#0b141a;
        font-size:.88rem; font-weight:500; text-decoration:none; transition:background .12s; }
    .wa-item:hover { background:#f0f2f5; color:#0b141a; }
    .wa-item i { color:#25d366; width:18px; text-align:center; }
    @media (max-width:480px){ .wa-menu { width:min(280px,calc(100vw - 40px)); } }
</style>
<div class="wa-fab" id="waFab">
    <div class="wa-menu" role="dialog" aria-label="{{ __('whatsapp.title') }}">
        <div class="wa-head">
            <b>{{ __('whatsapp.title') }}</b>
            <span>{{ __('whatsapp.subtitle') }}</span>
        </div>
        <div class="wa-list">
            @foreach ($__choices as $c)
                <a class="wa-item" target="_blank" rel="noopener"
                   href="https://wa.me/{{ $__wa }}?text={{ rawurlencode($c[2]) }}">
                    <i class="fas {{ $c[0] }}"></i> {{ $c[1] }}
                </a>
            @endforeach
        </div>
    </div>
    <button type="button" class="wa-btn" aria-label="{{ __('whatsapp.aria') }}"
            onclick="document.getElementById('waFab').classList.toggle('open')">
        <i class="fab fa-whatsapp"></i>
    </button>
</div>
<script>
    (function () {
        document.addEventListener('click', function (e) {
            var fab = document.getElementById('waFab');
            if (fab && fab.classList.contains('open') && !fab.contains(e.target)) fab.classList.remove('open');
        });
    })();
</script>
@endif
