<?php
/*
|--------------------------------------------------------------------------
| checkinHub — Installation via navigateur (hébergement FTP sans SSH)
|--------------------------------------------------------------------------
| À UPLOADER dans web/  puis visiter :
|     https://tondomaine/setup.php?key=checkinhub-setup-2026
|
| Fait : migrations + hôtel par défaut + rattachement des données existantes
|        + création du Super-Admin + lien web/storage.
|
| ⚠️ SUPPRIME CE FICHIER juste après (sécurité).
|--------------------------------------------------------------------------
*/

$SECRET   = 'checkinhub-setup-2026';   // change-le si tu veux
$SU_EMAIL = 'admin@checkinhub.com';
$SU_PASS  = 'CheckinHub@2026';
$SU_NAME  = 'Direction';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    exit('Forbidden');
}

header('Content-Type: text/plain; charset=utf-8');

// Chemin vers Laravel (dans private/, à côté de web/)
require __DIR__.'/../private/vendor/autoload.php';
$app = require_once __DIR__.'/../private/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

echo "===== checkinHub — installation =====\n\n";

echo "1) Migrations...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output()."\n";

echo "2) Hôtel par défaut...\n";
Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\HotelSeeder', '--force' => true]);
$defaultId = Hotel::where('slug', 'hotel-par-defaut')->value('id');
echo "   hôtel par défaut id=".var_export($defaultId, true)."\n\n";

echo "3) Rattachement des données existantes à l'hôtel par défaut...\n";
$tables = ['rooms','types','facilities','images','transactions','customers','payments',
           'transaction_extras','bookings','cashier_sessions','cashier_transactions',
           'restaurant_orders','restaurant_order_items','restaurant_reservations',
           'menus','categories','floor_plans'];
foreach ($tables as $t) {
    if (Schema::hasTable($t) && Schema::hasColumn($t, 'hotel_id')) {
        $n = DB::table($t)->whereNull('hotel_id')->update(['hotel_id' => $defaultId]);
        if ($n) echo "   $t: $n ligne(s) rattachée(s)\n";
    }
}
// Users existants (staff) -> hôtel par défaut, SAUF les Super (plateforme)
if (Schema::hasColumn('users', 'hotel_id')) {
    $n = DB::table('users')->whereNull('hotel_id')->where('role', '!=', 'Super')->update(['hotel_id' => $defaultId]);
    if ($n) echo "   users: $n utilisateur(s) rattaché(s)\n";
}
echo "\n";

echo "4) Compte Super-Admin plateforme (création OU réinitialisation)...\n";
$u = User::firstOrNew(['email' => $SU_EMAIL]);
$u->name       = $SU_NAME;
$u->role       = 'Super';
$u->hotel_id   = null;                 // plateforme = sans hôtel
$u->password   = Hash::make($SU_PASS); // (ré)initialise le mot de passe à chaque exécution
$u->random_key = $u->random_key ?: Str::random(60);
$u->save();
$check = Hash::check($SU_PASS, $u->fresh()->password) ? 'OK' : 'ECHEC';
echo "   Identifiants -> $SU_EMAIL / $SU_PASS   (vérif mot de passe: $check)\n\n";

echo "5) Lien web/storage...\n";
$target = realpath(__DIR__.'/../private/storage/app/public');
$link   = __DIR__.'/storage';
if (! file_exists($link) && $target) {
    @symlink($target, $link);
}
echo (file_exists($link) ? "   OK : web/storage\n" : "   ⚠️ À créer à la main (New > Link dans WinSCP).\n")."\n";

echo "===== TERMINÉ =====\n";
echo "➡️  Connecte-toi : /login  ($SU_EMAIL / $SU_PASS)\n";
echo "⚠️  SUPPRIME MAINTENANT ce fichier web/setup.php !\n";
