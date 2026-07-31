<?php

/*
 | Fonds d'établissement (préférence locale par appareil).
 | Chaque entrée : label + css (valeur de `background`). Les scènes « montagnes »
 | sont des SVG en data-URI générés ci-dessous (silhouettes en couches).
 | Utilisé par la page Mon établissement (vignettes) ET par le master (application).
 */

$mtn = function (string $sky1, string $sky2, string $m1, string $m2, string $m3): string {
    $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 600' preserveAspectRatio='xMidYMid slice'>"
        . "<defs><linearGradient id='s' x1='0' y1='0' x2='0' y2='1'>"
        . "<stop offset='0' stop-color='{$sky1}'/><stop offset='.55' stop-color='{$sky2}'/><stop offset='1' stop-color='{$m1}'/>"
        . "</linearGradient></defs>"
        . "<rect width='1440' height='600' fill='url(#s)'/>"
        . "<path d='M0 356 240 296 470 372 700 288 940 360 1180 298 1440 352 1440 600 0 600Z' fill='{$m1}' opacity='.5'/>"
        . "<path d='M0 438 300 368 560 438 820 362 1080 438 1440 396 1440 600 0 600Z' fill='{$m2}' opacity='.78'/>"
        . "<path d='M0 520 280 456 600 512 940 450 1440 500 1440 600 0 600Z' fill='{$m3}'/>"
        . "</svg>";

    return 'url("data:image/svg+xml,' . rawurlencode($svg) . '")';
};

return [
    'backgrounds' => [
        'neon'      => ['label' => 'Néon',
            'css' => 'radial-gradient(60% 80% at 18% 12%, rgba(124,58,237,.85), transparent 60%), radial-gradient(65% 85% at 85% 16%, rgba(219,39,119,.8), transparent 60%), radial-gradient(85% 90% at 60% 100%, rgba(37,99,235,.7), transparent 60%), #0b0b1f'],
        'sunset'    => ['label' => 'Coucher de soleil',
            'css' => 'linear-gradient(180deg, #1e3a8a 0%, #6d28d9 34%, #db2777 66%, #f59e0b 100%)'],
        'pastel'    => ['label' => 'Pastel',
            'css' => 'radial-gradient(60% 70% at 80% 16%, rgba(251,207,232,.85), transparent 60%), radial-gradient(70% 75% at 12% 88%, rgba(191,219,254,.75), transparent 60%), linear-gradient(135deg, #eef2ff, #f0fdf4)'],
        'spectrum'  => ['label' => 'Spectre',
            'css' => 'radial-gradient(70% 90% at 26% 16%, rgba(37,99,235,.9), transparent 60%), radial-gradient(70% 90% at 72% 22%, rgba(16,185,129,.85), transparent 60%), radial-gradient(95% 90% at 50% 100%, rgba(245,158,11,.7), transparent 60%), #0a0f1a'],
        'golden'    => ['label' => 'Aube dorée',    'css' => $mtn('#fbd38d', '#f6a86b', '#5b7ea6', '#3c5a7d', '#22384f')],
        'mauve'     => ['label' => 'Brume mauve',   'css' => $mtn('#efe3ff', '#cbb6ef', '#9d86c9', '#6f5aa0', '#463869')],
        'bluedusk'  => ['label' => 'Crépuscule bleu','css' => $mtn('#cfe9ec', '#9dc7cf', '#5f9aa6', '#3d7280', '#244a56')],
        'moonlight' => ['label' => 'Clair de lune',
            'css' => 'radial-gradient(circle at 28% 30%, rgba(255,255,255,.92) 0%, rgba(253,230,200,.5) 8%, transparent 22%), linear-gradient(180deg, #fbcfe8 0%, #fde7c8 100%)'],
    ],
];
