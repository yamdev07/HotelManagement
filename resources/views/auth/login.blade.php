<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('login.page_title') }} · {{ config('app.name', 'checkinHub') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --brand:#4f46e5; --brand2:#7c3aed; --ink:#0f172a; }
        * { font-family:'Inter',system-ui,sans-serif; box-sizing:border-box; }
        html,body { height:100%; }
        body { margin:0; color:var(--ink); overflow:hidden; }

        .split { display:grid; grid-template-columns:1.05fr 1fr; height:100vh; }

        .side {
            position:relative; padding:4rem; color:#fff; overflow:hidden;
            background:linear-gradient(135deg,var(--brand),var(--brand2),#6d28d9);
            background-size:200% 200%; animation:grad 12s ease infinite;
            display:flex; flex-direction:column; justify-content:center;
        }
        @keyframes grad { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .blob { position:absolute; border-radius:50%; filter:blur(2px); background:var(--white, #fff); animation:float 9s ease-in-out infinite; }
        @keyframes float { 0%,100%{ transform:translateY(0) } 50%{ transform:translateY(-26px) } }
        .grid-deco { position:absolute; inset:0; background-image:radial-gradient(rgba(255,255,255,.12) 1px,transparent 1px); background-size:26px 26px; opacity:.4; mask-image:linear-gradient(180deg,transparent,#000 40%,transparent); }
        .side-inner { position:relative; z-index:2; max-width:460px; }
        .brand { font-size:2rem; font-weight:800; display:flex; align-items:center; gap:.7rem; }
        .side h1 { font-size:clamp(2rem,3.2vw,3rem); font-weight:800; line-height:1.1; letter-spacing:-.02em; margin:2rem 0 1rem; }
        .feat { display:flex; gap:1rem; align-items:flex-start; margin-top:1.4rem; }
        .feat-ico { width:46px;height:46px;border-radius:14px;background:var(--white, #fff);display:grid;place-items:center;flex-shrink:0;backdrop-filter:blur(4px); }

        .panel { display:flex; align-items:center; justify-content:center; padding:2rem; background:var(--white, #fff); }
        .form-wrap { width:100%; max-width:420px; }
        .form-control { border-radius:14px; padding:.9rem 1rem .9rem 2.8rem; border:1px solid #e5e7eb; background:#f8fafc; transition:.25s; }
        .form-control:focus { border-color:var(--brand); box-shadow:0 0 0 .25rem rgba(79,70,229,.15); background:var(--white, #fff); }
        .input-ico { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#94a3b8; transition:.25s; }
        .position-relative:focus-within .input-ico { color:var(--brand); }
        .pw-eye { position:absolute; right:.85rem; top:50%; transform:translateY(-50%); background:none; border:none;
            color:#94a3b8; cursor:pointer; padding:4px; line-height:1; z-index:3; }
        .pw-eye:hover { color:var(--brand); }
        .btn-brand { background:linear-gradient(135deg,var(--brand),var(--brand2)); border:none; color:#fff; border-radius:14px; padding:.95rem; font-weight:600; width:100%; transition:.25s; }
        .btn-brand:hover { transform:translateY(-2px); box-shadow:0 16px 34px -12px var(--brand); color:#fff; filter:brightness(1.05); }
        .link-brand { color:var(--brand); font-weight:600; text-decoration:none; }
        .lang-toggle { border:1px solid rgba(255,255,255,.3); border-radius:8px; padding:.35rem .75rem; font-weight:600; font-size:.85rem; color:#fff; background:var(--white, #fff); text-decoration:none; transition:.2s; backdrop-filter:blur(4px); }
        .lang-toggle:hover { background:var(--white, #fff); border-color:rgba(255,255,255,.5); color:#fff; }

        @keyframes up { from{ opacity:0; transform:translateY(22px) } to{ opacity:1; transform:none } }
        @keyframes inLeft { from{ opacity:0; transform:translateX(-26px) } to{ opacity:1; transform:none } }
        .anim { opacity:0; animation:up .7s cubic-bezier(.2,.7,.2,1) forwards; }
        .anim-l { opacity:0; animation:inLeft .8s cubic-bezier(.2,.7,.2,1) forwards; }
        .d1{animation-delay:.05s} .d2{animation-delay:.15s} .d3{animation-delay:.25s} .d4{animation-delay:.35s} .d5{animation-delay:.45s} .d6{animation-delay:.55s}

        .glow { position:absolute; width:140%; aspect-ratio:1; left:-20%; top:-20%; z-index:1;
            background:conic-gradient(from 0deg, transparent, rgba(255,255,255,.18), transparent 30%);
            animation:spin 14s linear infinite; mix-blend-mode:overlay; }
        @keyframes spin { to { transform:rotate(360deg); } }

        .float-ico { position:absolute; color:rgba(255,255,255,.16); z-index:1; animation:drift 12s ease-in-out infinite; }
        @keyframes drift { 0%,100%{ transform:translateY(0) rotate(0); } 50%{ transform:translateY(-30px) rotate(12deg); } }

        .shine { background:linear-gradient(90deg,#fff,#e0d7ff,#fff,#c4b5fd,#fff); background-size:250% 100%;
            -webkit-background-clip:text; background-clip:text; color:transparent; animation:shine 5s linear infinite; }
        @keyframes shine { to { background-position:250% 0; } }

        .brand i { position:relative; }
        .brand i::after { content:''; position:absolute; inset:-8px; border-radius:50%; border:2px solid rgba(255,255,255,.5); animation:ring 2.4s ease-out infinite; }
        @keyframes ring { 0%{ transform:scale(.7); opacity:.8 } 100%{ transform:scale(1.6); opacity:0 } }

        .btn-brand { position:relative; overflow:hidden; }
        .btn-brand::before { content:''; position:absolute; top:0; left:-120%; width:60%; height:100%;
            background:linear-gradient(120deg,transparent,rgba(255,255,255,.45),transparent); transform:skewX(-20deg); animation:sweep 3.2s ease-in-out infinite; }
        @keyframes sweep { 0%{ left:-120% } 55%,100%{ left:140% } }

        .blob { transition:transform .4s cubic-bezier(.2,.7,.2,1); will-change:transform; }
        .feat { transition:transform .25s; }
        .feat:hover { transform:translateX(6px); }
        .feat:hover .feat-ico { background:var(--white, #fff); }
        .feat-ico { transition:.25s; }

        @media (max-width:860px){
            body{ overflow:auto; }
            .split{ grid-template-columns:1fr; height:auto; min-height:100vh; }
            .side{ min-height:38vh; padding:2.5rem; }
        }
        @media (prefers-reduced-motion: reduce){ *{ animation:none!important; } .anim,.anim-l{ opacity:1!important; } }
    </style>
</head>
<body>
<div class="split">
    <!-- MARQUE -->
    <aside class="side" id="side">
        <div class="glow"></div>
        <div class="grid-deco"></div>
        <span class="blob" data-depth="22" style="width:260px;height:260px;top:-40px;right:-60px;"></span>
        <span class="blob" data-depth="-18" style="width:170px;height:170px;bottom:6%;left:-50px;animation-delay:-3s;"></span>
        <span class="blob" data-depth="30" style="width:90px;height:90px;top:30%;right:22%;animation-delay:-6s;"></span>

        <i class="fas fa-bed float-ico"   style="font-size:2.2rem;top:18%;left:12%;"></i>
        <i class="fas fa-key float-ico"   style="font-size:1.6rem;top:70%;left:18%;animation-delay:-2s;"></i>
        <i class="fas fa-bell-concierge float-ico" style="font-size:2rem;top:24%;right:14%;animation-delay:-4s;"></i>
        <i class="fas fa-star float-ico"  style="font-size:1.3rem;bottom:18%;right:24%;animation-delay:-5s;"></i>
        <i class="fas fa-martini-glass float-ico" style="font-size:1.6rem;bottom:30%;left:42%;animation-delay:-7s;"></i>

        <div class="side-inner">
            <div class="anim-l d1" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <a href="{{ route('landing') }}" class="brand" style="color:inherit;text-decoration:none;">
                    <i class="fas fa-hotel"></i> {{ config('app.name', 'checkinHub') }}
                </a>
                <a href="{{ route('lang.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" class="lang-toggle">{{ __('landing.nav_switch_lang') }}</a>
            </div>
            <h1 class="anim-l d2">{{ __('login.side_title_1') }}<br><span class="shine">{{ __('login.side_title_2') }}</span></h1>
            <p class="anim-l d3" style="opacity:.9;font-size:1.05rem;">{{ __('login.side_description') }}</p>

            <div class="anim-l d4 feat"><div class="feat-ico"><i class="fas fa-shield-halved"></i></div>
                <div><div class="fw-semibold">{{ __('login.side_feat_1_title') }}</div><div class="small" style="opacity:.8;">{{ __('login.side_feat_1_desc') }}</div></div></div>
            <div class="anim-l d5 feat"><div class="feat-ico"><i class="fas fa-bolt"></i></div>
                <div><div class="fw-semibold">{{ __('login.side_feat_2_title') }}</div><div class="small" style="opacity:.8;">{{ __('login.side_feat_2_desc') }}</div></div></div>
            <div class="anim-l d6 feat"><div class="feat-ico"><i class="fas fa-headset"></i></div>
                <div><div class="fw-semibold">{{ __('login.side_feat_3_title') }}</div><div class="small" style="opacity:.8;">{{ __('login.side_feat_3_desc') }}</div></div></div>
        </div>
    </aside>

    <!-- FORMULAIRE -->
    <section class="panel">
        <div class="form-wrap">
            <div class="anim d1">
                <h2 class="fw-bold mb-1">{{ __('login.form_title') }}</h2>
                <p class="text-secondary mb-4">{{ __('login.form_description') }}</p>
            </div>

            @if (session('failed') || session('error'))
                <div class="alert alert-danger py-2 anim">{{ session('failed') ?? session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success py-2 anim">{{ session('success') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 anim d2">
                    <label class="form-label fw-semibold">{{ __('login.field_email') }}</label>
                    <div class="position-relative">
                        <i class="fas fa-envelope input-ico"></i>
                        <input type="text" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('login.field_email_placeholder') }}">
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 anim d3">
                    <label class="form-label fw-semibold">{{ __('login.field_password') }}</label>
                    <div class="position-relative">
                        <i class="fas fa-lock input-ico"></i>
                        <input type="password" name="password" id="loginPassword" required
                               style="padding-right:2.7rem;"
                               class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('login.field_password_placeholder') }}">
                        <button type="button" class="pw-eye" id="loginPwEye" tabindex="-1" aria-label="{{ __('login.field_password_toggle') }}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 anim d4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">{{ __('login.remember_me') }}</label>
                    </div>
                    <a href="/forgot-password" class="link-brand small">{{ __('login.forgot_password') }}</a>
                </div>

                <button type="submit" class="btn-brand anim d5"><i class="fas fa-arrow-right-to-bracket me-2"></i>{{ __('login.submit') }}</button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0 anim d6">
                {{ __('login.no_account') }} <a href="{{ route('hotel.register') }}" class="link-brand">{{ __('login.free_trial') }}</a>
            </p>
            <p class="text-center small mt-2 mb-0 anim d6">
                <a href="{{ route('landing') }}" class="link-brand"><i class="fas fa-arrow-left me-1"></i>{{ __('login.back_to_site') }}</a>
            </p>
        </div>
    </section>
</div>

<script>
    const side = document.getElementById('side');
    const blobs = side ? side.querySelectorAll('.blob') : [];
    if (side && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
        side.addEventListener('mousemove', (e) => {
            const r = side.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - .5;
            const y = (e.clientY - r.top) / r.height - .5;
            blobs.forEach(b => {
                const d = parseFloat(b.dataset.depth || 16);
                b.style.transform = `translate(${x * d}px, ${y * d}px)`;
            });
        });
        side.addEventListener('mouseleave', () => blobs.forEach(b => b.style.transform = ''));
    }

    const pwEye = document.getElementById('loginPwEye');
    const pwInput = document.getElementById('loginPassword');
    if (pwEye && pwInput) {
        pwEye.addEventListener('click', () => {
            const icon = pwEye.querySelector('i');
            if (pwInput.type === 'password') {
                pwInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }
</script>
</body>
</html>
