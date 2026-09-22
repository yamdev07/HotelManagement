@php
    // Checklist d'activation : calculée sur l'état réel de l'hôtel.
    // Réservée à la direction (Admin/Super/Manager) et masquée une fois à 100 %.
    $__u = auth()->user();
    $__h = $__u?->hotel;
    $__show = $__h && in_array($__u->role, ['Admin', 'Super', 'Manager'], true);

    if ($__show) {
        $__roomCount = \App\Models\Room::count();
        $__typeCount = \App\Models\Type::count();
        $__priced    = \App\Models\Room::where('price', '>', 0)->count();
        $__staff     = \App\Models\User::where('hotel_id', $__h->id)
            ->whereIn('role', ['Receptionist', 'Cashier', 'Housekeeping', 'Servant', 'Cuisiner', 'Manager'])->count();
        $__tx        = \App\Models\Transaction::count();
        $__checkin   = \App\Models\Transaction::whereIn('status', ['active', 'completed'])->count();

        $__steps = [
            ['done' => $__h->onboarding_completed_at !== null, 'icon' => 'fa-palette',         'label' => __('checklist.step_personalize'), 'url' => route('onboarding.show')],
            ['done' => $__typeCount > 0,                       'icon' => 'fa-tags',            'label' => __('checklist.step_types'),       'url' => route('type.index')],
            ['done' => $__roomCount > 0,                       'icon' => 'fa-bed',             'label' => __('checklist.step_rooms'),       'url' => route('room.index')],
            ['done' => $__priced > 0,                          'icon' => 'fa-money-bill-wave', 'label' => __('checklist.step_prices'),      'url' => route('room.index')],
            ['done' => $__staff > 0,                           'icon' => 'fa-user-plus',       'label' => __('checklist.step_staff'),       'url' => route('staff.index')],
            ['done' => $__tx > 0,                              'icon' => 'fa-calendar-check',  'label' => __('checklist.step_booking'),     'url' => route('transaction.reservation.createIdentity')],
            ['done' => $__checkin > 0,                         'icon' => 'fa-id-card',         'label' => __('checklist.step_checkin'),     'url' => route('checkin.index')],
        ];
        $__doneCount = count(array_filter($__steps, fn ($s) => $s['done']));
        $__pct = (int) round($__doneCount / max(1, count($__steps)) * 100);
    }
@endphp

@if ($__show && $__pct < 100)
<style>
    .setup-cl-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap; }
    .setup-cl-pct { font-family:'Space Grotesk','DM Sans',sans-serif; font-weight:720; font-size:1.5rem; color:var(--acc); line-height:1; }
    .setup-cl-bar { height:8px; border-radius:20px; background:var(--tint); overflow:hidden; margin:12px 0 4px; }
    .setup-cl-fill { height:100%; border-radius:20px; background:linear-gradient(90deg,var(--acc2),var(--acc)); transition:width .5s ease; }
    .setup-cl-list { display:grid; grid-template-columns:repeat(2,1fr); gap:8px; margin-top:14px; }
    @media (max-width:720px){ .setup-cl-list{ grid-template-columns:1fr; } }
    .setup-cl-item { display:flex; align-items:center; gap:11px; padding:11px 12px; border:1px solid var(--line); border-radius:var(--r-sm,9px); transition:border-color .15s, transform .15s; }
    .setup-cl-item:hover { border-color:var(--line2); transform:translateY(-1px); }
    .setup-cl-item.is-done { opacity:.6; }
    .setup-cl-check { width:26px; height:26px; border-radius:50%; flex:none; display:grid; place-items:center; font-size:.72rem; }
    .setup-cl-check.todo { background:var(--tint); color:var(--ink3); border:1.5px dashed var(--line2); }
    .setup-cl-check.ok   { background:var(--acc); color:#fff; }
    .setup-cl-label { flex:1; font-size:.85rem; font-weight:550; color:var(--ink); }
    .setup-cl-item.is-done .setup-cl-label { text-decoration:line-through; color:var(--ink2); }
    .setup-cl-do { font-size:.74rem; font-weight:700; color:var(--acc); white-space:nowrap; }
</style>
<div class="db-card anim-1">
    <div class="db-card-header">
        <div class="setup-cl-head" style="flex:1">
            <div>
                <h2 class="db-card-title"><span class="db-card-title-dot"></span> {{ __('checklist.title') }}</h2>
                <div class="db-card-subtitle">{{ __('checklist.subtitle') }}</div>
            </div>
            <div class="setup-cl-pct">{{ $__pct }}%</div>
        </div>
    </div>
    <div class="db-card-body">
        <div class="setup-cl-bar"><div class="setup-cl-fill" style="width:{{ $__pct }}%"></div></div>
        <div class="setup-cl-list">
            @foreach ($__steps as $s)
                @if ($s['done'])
                    <div class="setup-cl-item is-done">
                        <span class="setup-cl-check ok"><i class="fas fa-check"></i></span>
                        <span class="setup-cl-label">{{ $s['label'] }}</span>
                    </div>
                @else
                    <a href="{{ $s['url'] }}" class="setup-cl-item">
                        <span class="setup-cl-check todo"><i class="fas {{ $s['icon'] }}"></i></span>
                        <span class="setup-cl-label">{{ $s['label'] }}</span>
                        <span class="setup-cl-do">{{ __('checklist.do') }} <i class="fas fa-arrow-right fa-xs"></i></span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif
