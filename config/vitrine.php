<?php

/*
|--------------------------------------------------------------------------
| Vitrine — images premium par défaut
|--------------------------------------------------------------------------
| Utilisées quand l'hôtelier n'a pas encore uploadé ses propres visuels,
| pour que la vitrine soit toujours élégante (au lieu de blocs de couleur).
| URLs CDN Unsplash (stables).
*/

$u = fn (string $id, int $w = 900) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w={$w}&q=80";

return [
    // Grande image d'en-tête par défaut
    'default_cover' => $u('1566073771259-6a8506099945', 1920),

    // Photos de chambres (rotation déterministe par index)
    'rooms' => [
        $u('1611892440504-42a792e24d32'),
        $u('1582719478250-c89cae4dc85b'),
        $u('1631049307264-da0ec9d70304'),
        $u('1590490360182-c33d57733427'),
        $u('1618773928121-c32242e63f39'),
        $u('1631049421450-348ccd7f8949'),
    ],

    // Galerie
    'gallery' => [
        $u('1564501049412-61c2a3083791', 700),
        $u('1571896349842-33c89424de2d', 700),
        $u('1551882547-ff40c63fe5fa', 700),
        $u('1542314831-068cd1dbfeeb', 700),
        $u('1445019980597-93fa8acb246c', 700),
        $u('1540541338287-41700207dee6', 700),
        $u('1584132967334-10e028bd69f7', 700),
        $u('1521783593447-5702b9bfd267', 700),
    ],
];
