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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

echo "===== checkinHub — installation =====\n\n";

echo "0) Rafraîchissement des caches (config/routes/vues)...\n";
foreach (['config:clear', 'route:clear', 'view:clear'] as $cmd) {
    try { Artisan::call($cmd); } catch (\Throwable $e) {}
}
echo "   OK (les changements du .env sont pris en compte)\n\n";

echo "1) Migrations...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output()."\n";

echo "1b) Vérification / réparation du schéma SaaS...\n";

// -- Table des abonnements (peut être marquée « migrée » sans exister réellement) --
if (! Schema::hasTable('subscriptions')) {
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('hotel_id');
        $table->string('plan')->default('starter');
        $table->decimal('amount', 15, 2)->default(0);
        $table->string('currency', 10)->default('CFA');
        $table->string('status')->default('active');
        $table->boolean('is_renewal')->default(false);
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
        $table->index(['hotel_id', 'ends_at']);
    });
    echo "   table 'subscriptions' CRÉÉE\n";
} else {
    echo "   table 'subscriptions' OK\n";
}

// -- Colonnes de 'hotels' ajoutées à l'ère SaaS (créées si absentes) --
$hotelCols = [
    'plan'              => fn (Blueprint $t) => $t->string('plan')->nullable(),
    'room_limit'        => fn (Blueprint $t) => $t->integer('room_limit')->nullable(),
    'country'           => fn (Blueprint $t) => $t->string('country', 2)->default('BJ'),
    'currency'          => fn (Blueprint $t) => $t->string('currency', 10)->nullable(),
    'suspension_reason' => fn (Blueprint $t) => $t->string('suspension_reason')->nullable(),
    'subscription_ends_at' => fn (Blueprint $t) => $t->timestamp('subscription_ends_at')->nullable(),
    'owner_user_id'     => fn (Blueprint $t) => $t->unsignedBigInteger('owner_user_id')->nullable(),
    'onboarding_completed_at' => fn (Blueprint $t) => $t->timestamp('onboarding_completed_at')->nullable(),
];
if (Schema::hasTable('hotels')) {
    foreach ($hotelCols as $col => $definition) {
        if (! Schema::hasColumn('hotels', $col)) {
            Schema::table('hotels', function (Blueprint $t) use ($definition) { $definition($t); });
            echo "   hotels.$col AJOUTÉE\n";
        }
    }
}

// -- Colonne legacy 'rooms.view' (longText NOT NULL sans défaut) : la rendre nullable --
// Évite l'erreur "Field 'view' doesn't have a default value" en mode SQL strict.
if (Schema::hasTable('rooms') && Schema::hasColumn('rooms', 'view')) {
    try {
        DB::statement('ALTER TABLE `rooms` MODIFY `view` LONGTEXT NULL');
        echo "   rooms.view rendue nullable\n";
    } catch (\Throwable $e) {
        echo "   rooms.view: ".$e->getMessage()."\n";
    }
}

// -- activity_log.hotel_id (issue #144) : le journal d'activité fuyait entre hôtels --
if (Schema::hasTable('activity_log') && ! Schema::hasColumn('activity_log', 'hotel_id')) {
    try {
        Schema::table('activity_log', function (Blueprint $t) { $t->unsignedBigInteger('hotel_id')->nullable()->after('id')->index(); });
        DB::table('activity_log')
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->where('activity_log.causer_type', 'App\\Models\\User')
            ->whereNull('activity_log.hotel_id')
            ->update(['activity_log.hotel_id' => DB::raw('users.hotel_id')]);
        echo "   activity_log.hotel_id AJOUTÉE + historique rattaché\n";
    } catch (\Throwable $e) { echo "   activity_log.hotel_id: ".$e->getMessage()."\n"; }
}

// -- users.phone (issue #145) : le profil enregistre un téléphone mais la colonne manquait --
if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'phone')) {
    try {
        Schema::table('users', function (Blueprint $t) { $t->string('phone', 30)->nullable()->after('email'); });
        echo "   users.phone AJOUTÉE\n";
    } catch (\Throwable $e) { echo "   users.phone: ".$e->getMessage()."\n"; }
}

// -- Champs client facultatifs à l'accueil (issue #146) : NOT NULL sans défaut -> crash --
if (Schema::hasTable('customers')) {
    foreach (['address' => 'VARCHAR(255)', 'job' => 'VARCHAR(255)', 'birthdate' => 'DATE'] as $col => $type) {
        if (Schema::hasColumn('customers', $col)) {
            try {
                DB::statement("ALTER TABLE `customers` MODIFY `{$col}` {$type} NULL");
                echo "   customers.$col rendue nullable\n";
            } catch (\Throwable $e) { /* déjà nullable */ }
        }
    }
    if (Schema::hasColumn('customers', 'gender')) {
        try {
            DB::statement("ALTER TABLE `customers` MODIFY `gender` ENUM('Male','Female','Other') NOT NULL DEFAULT 'Other'");
            echo "   customers.gender accepte 'Other'\n";
        } catch (\Throwable $e) { /* déjà à jour */ }
    }
}

// -- customers.user_id nullable : une fiche client peut exister sans compte de connexion --
if (Schema::hasTable('customers') && Schema::hasColumn('customers', 'user_id')) {
    try {
        DB::statement('ALTER TABLE `customers` MODIFY `user_id` BIGINT UNSIGNED NULL');
        echo "   customers.user_id rendue nullable\n";
    } catch (\Throwable $e) {
        echo "   customers.user_id: ".$e->getMessage()."\n";
    }
}
echo "\n";

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

echo "3b) Offre complète (Business) pour l'hôtel démo et Cactus...\n";
// Ces hôtels de démonstration doivent garder tous les modules (restaurant, housekeeping, rapports).
// On ne touche PAS aux vrais hôtels clients (Starter/Pro restent gérés par leur plan).
$nBiz = DB::table('hotels')
    ->where(function ($q) {
        $q->where('slug', 'hotel-par-defaut')->orWhere('name', 'like', '%Cactus%');
    })
    ->update(['plan' => 'business']);
echo "   $nBiz hôtel(s) démo passé(s) en Business\n\n";

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
// Robuste : détecte aussi un FAUX dossier "storage" (pas un lien) ou un lien
// pointant au mauvais endroit, et le remplace par le bon lien.
if ($target) {
    $resolved = @realpath($link);
    $correct  = is_link($link) && $resolved === $target;
    if (! $correct) {
        if (file_exists($link) && ! is_link($link)) {
            @rename($link, __DIR__.'/storage_old_'.date('Ymd_His')); // sauvegarde du faux dossier
        } elseif (is_link($link)) {
            @unlink($link); // lien cassé/mauvaise cible
        }
        @symlink($target, $link);
    }
}
echo (is_link($link) && @realpath($link) === $target
    ? "   OK : web/storage -> private/storage/app/public\n"
    : "   ⚠️ Lien incorrect : à créer à la main (WinSCP > New > Link).\n")."\n";

echo "===== TERMINÉ =====\n";
echo "➡️  Connecte-toi : /login  ($SU_EMAIL / $SU_PASS)\n";
echo "⚠️  SUPPRIME MAINTENANT ce fichier web/setup.php !\n";
