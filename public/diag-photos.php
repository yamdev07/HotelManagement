<?php

/*
| Diagnostic photos/images en production (hébergement FTP).
| Uploader dans web/ puis ouvrir :
|   https://tondomaine/diag-photos.php?key=checkinhub-diag-2026
| ⚠️ SUPPRIMER ce fichier après usage.
*/

$SECRET = 'checkinhub-diag-2026';
if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    exit('Forbidden');
}
header('Content-Type: text/plain; charset=utf-8');

echo "===== Diagnostic photos =====\n\n";

$web = __DIR__;                       // dossier public (web/)
$private = realpath(__DIR__.'/../private') ?: dirname(__DIR__).'/private';
$target = $private.'/storage/app/public';
$link = $web.'/storage';

echo "1) Lien web/storage\n";
echo '   existe          : '.(file_exists($link) ? 'OUI' : 'NON')."\n";
echo '   est un lien     : '.(is_link($link) ? 'OUI -> '.readlink($link) : 'NON')."\n";
echo "   cible attendue  : $target\n";
echo '   cible existe    : '.(is_dir($target) ? 'OUI' : 'NON')."\n";
$resolved = @realpath($link);
echo '   résolution      : '.($resolved ?: 'ÉCHEC')."\n";
echo '   pointe au bon endroit : '.($resolved && realpath($target) === $resolved ? 'OUI' : 'NON ⚠️')."\n";

// --- Réparation : ?key=...&fixlink=1 ---
if (isset($_GET['fixlink'])) {
    echo "\n   -- RÉPARATION DU LIEN --\n";
    if (file_exists($link) && ! is_link($link)) {
        $bak = $web.'/storage_old_'.date('Ymd_His');
        echo '   faux dossier renommé : '.(@rename($link, $bak) ? 'OUI ('.basename($bak).')' : 'ÉCHEC ⚠️')."\n";
    } elseif (is_link($link)) {
        @unlink($link);
        echo "   ancien lien supprimé\n";
    }
    if (! file_exists($link)) {
        echo '   création du lien : '.(@symlink($target, $link) ? 'OUI' : 'ÉCHEC (symlink interdit par l\'hébergeur ?)')."\n";
        $res = @realpath($link);
        echo '   vérification : '.($res && realpath($target) === $res ? 'OK ✅' : 'toujours KO ⚠️ (la route Laravel /storage prendra le relais)')."\n";
    }
}
echo "\n";

echo "2) Écriture dans storage/app/public\n";
$probe = $target.'/diag-'.time().'.txt';
$w = @file_put_contents($probe, 'ok');
echo '   écriture fichier test : '.($w ? 'OUI' : 'NON ⚠️ (droits ?)')."\n";
if ($w) {
    $viaLink = @file_get_contents($link.'/'.basename($probe));
    echo '   lisible via le lien   : '.($viaLink === 'ok' ? 'OUI' : 'NON ⚠️')."\n";
    $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/storage/'.basename($probe);
    echo "   URL de test : $url\n";
    // Test HTTP automatique (pas besoin d'ouvrir l'URL à la main)
    $ctx = stream_context_create(['http' => ['timeout' => 8, 'ignore_errors' => true]]);
    $body = @file_get_contents($url, false, $ctx);
    $stat = $http_response_header[0] ?? 'aucune réponse';
    echo "   test HTTP auto : $stat".($body === 'ok' ? '  (contenu OK ✅ le service des images FONCTIONNE)' : '  ⚠️')."\n";
    // on laisse le fichier pour le test URL ; retapez ?key=...&clean=1 pour le supprimer
    if (isset($_GET['clean'])) {
        @unlink($probe);
        echo "   (fichier test supprimé)\n";
    }
}
echo "\n";

echo "3) Dossier avatars\n";
$avatars = $target.'/avatars';
echo '   existe       : '.(is_dir($avatars) ? 'OUI' : 'NON (sera créé au 1er upload)')."\n";
if (is_dir($avatars)) {
    $files = array_values(array_diff(scandir($avatars), ['.', '..']));
    echo '   fichiers     : '.count($files)."\n";
    if ($files) {
        echo '   dernier      : '.end($files)."\n";
    }
}
echo "\n";

echo "4) Limites PHP upload\n";
echo '   upload_max_filesize : '.ini_get('upload_max_filesize')."\n";
echo '   post_max_size       : '.ini_get('post_max_size')."\n\n";

echo "5) Caches Laravel figés ?\n";
$rc = $private.'/bootstrap/cache/routes-v7.php';
$cc = $private.'/bootstrap/cache/config.php';
echo '   routes cachées : '.(file_exists($rc) ? 'OUI ⚠️ ('.date('d/m/Y H:i', filemtime($rc)).') -> la route /storage de secours peut être absente' : 'non')."\n";
echo '   config cachée  : '.(file_exists($cc) ? 'OUI ('.date('d/m/Y H:i', filemtime($cc)).')' : 'non')."\n";
echo '   OPcache actif  : '.(function_exists('opcache_get_status') && @opcache_get_status(false) ? 'OUI' : 'non')."\n";
if (isset($_GET['fixcache'])) {
    foreach ([$rc, $cc] as $f) {
        if (file_exists($f)) {
            @unlink($f);
            echo "   supprimé : $f\n";
        }
    }
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "   OPcache vidé\n";
    }
}
echo "\n";

echo "6) Code déployé ?\n";
$pc = @file_get_contents($private.'/app/Http/Controllers/ProfileController.php');
echo '   ProfileController version corrigée (disque public) : '.($pc && str_contains($pc, "store('avatars', 'public')") ? 'OUI' : 'NON ⚠️ (fichier pas uploadé)')."\n";
$um = @file_get_contents($private.'/app/Models/User.php');
echo '   User::getAvatar tolérant : '.($um && str_contains($um, "str_starts_with(\$avatar, 'storage/')") ? 'OUI' : 'NON ⚠️')."\n";
$rt = @file_get_contents($private.'/routes/web.php');
echo '   route /storage de secours : '.($rt && str_contains($rt, "'/storage/{path}'") ? 'OUI' : 'NON ⚠️')."\n\n";

echo "===== FIN =====\n";
echo "Options : &clean=1 (supprime le fichier test) · &fixcache=1 (purge routes/config cachés + OPcache)\n";
echo "⚠️ SUPPRIME ce fichier après usage.\n";
