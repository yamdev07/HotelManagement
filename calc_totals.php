<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RestaurantOrder;

$totalAll = RestaurantOrder::where('status', '!=', 'cancelled')->sum('total');
$totalRoom = RestaurantOrder::where('status', '!=', 'cancelled')->whereNotNull('room_id')->sum('total');
$totalNoRoom = RestaurantOrder::where('status', '!=', 'cancelled')->whereNull('room_id')->sum('total');

echo "TOTAL_ALL:" . $totalAll . "\n";
echo "TOTAL_ROOM:" . $totalRoom . "\n";
echo "TOTAL_NOROOM:" . $totalNoRoom . "\n";
