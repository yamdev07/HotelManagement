<?php

/*
|--------------------------------------------------------------------------
| checkinHub, Nettoyage (garde uniquement Cactus Hotel)
|--------------------------------------------------------------------------
| À UPLOADER dans web/ puis visiter :
|     https://demo.anyxtech.com/cleanup.php?key=checkinhub-cleanup-2026
|
| - garde l'hôtel Cactus (abonnement ILLIMITÉ + 14 chambres)
| - supprime TOUS les autres hôtels + leurs utilisateurs + données
|
| ⚠️ SUPPRIME CE FICHIER juste après.
|--------------------------------------------------------------------------
*/

$SECRET = 'checkinhub-cleanup-2026';
$ROOM_TARGET = 14;     // nombre de chambres voulu pour Cactus

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    exit('Forbidden');
}
header('Content-Type: text/plain; charset=utf-8');

require __DIR__.'/../private/vendor/autoload.php';
$app = require_once __DIR__.'/../private/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Type;
use App\Models\User;
use App\Support\TenantManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "===== Nettoyage checkinHub =====\n\n";

// 1) Trouver Cactus (slug OU nom contenant "cactus")
$keep = Hotel::withoutGlobalScopes()
    ->where('slug', 'cactus-hotel')
    ->orWhere('name', 'like', '%cactus%')
    ->first();

if (! $keep) {
    exit("❌ ABANDON : aucun hôtel « Cactus » trouvé. Rien n'a été supprimé.\n");
}
echo "Hôtel conservé : {$keep->name} (id={$keep->id})\n\n";

// 2) Supprimer tous les autres hôtels + données
$scoped = ['rooms', 'types', 'facilities', 'images', 'transactions', 'customers', 'payments',
    'transaction_extras', 'bookings', 'cashier_sessions', 'cashier_transactions',
    'restaurant_orders', 'restaurant_order_items', 'restaurant_reservations',
    'menus', 'categories', 'floor_plans'];

$others = Hotel::withoutGlobalScopes()->where('id', '!=', $keep->id)->get();
echo 'Hôtels à supprimer : '.$others->count()."\n";

foreach ($others as $h) {
    DB::transaction(function () use ($h, $scoped) {
        foreach ($scoped as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'hotel_id')) {
                DB::table($t)->where('hotel_id', $h->id)->delete();
            }
        }
        DB::table('subscriptions')->where('hotel_id', $h->id)->delete();
        User::where('hotel_id', $h->id)->delete();
        $h->forceDelete();
    });
    echo "  - supprimé : {$h->name}\n";
}
echo "\n";

// 3) Cactus : abonnement illimité + réactivé
$keep->update([
    'is_active' => true,
    'suspension_reason' => null,
    'subscription_ends_at' => null,   // illimité
    'plan' => 'pro',
    'room_limit' => null,   // chambres illimitées
]);
echo "Cactus : abonnement ILLIMITÉ, réactivé.\n";

// 4) Porter Cactus à 14 chambres
app(TenantManager::class)->setHotelId($keep->id);

$type = Type::first() ?: Type::create(['name' => 'Standard', 'information' => 'Chambre standard']);
$current = Room::count();
echo "Chambres actuelles : {$current}\n";

$n = 100;
while (Room::count() < $ROOM_TARGET) {
    // trouver un numéro libre
    do {
        $n++;
    } while (Room::where('number', (string) $n)->exists());
    $r = new Room;
    $r->number = (string) $n;
    $r->name = 'Chambre '.$n;
    $r->type_id = $type->id;
    $r->room_status_id = Room::STATUS_AVAILABLE; // 1
    $r->price = 25000;
    $r->capacity = 2;
    $r->view = 'Standard';
    $r->save();
}
echo 'Chambres après : '.Room::count()."\n\n";

echo "===== TERMINÉ =====\n";
echo "⚠️  SUPPRIME MAINTENANT web/cleanup.php !\n";
