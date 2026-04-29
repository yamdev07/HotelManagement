<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$menuCount = App\Models\Menu::count();
$first = App\Models\Menu::first();

echo "menu_count={$menuCount}\n";
if ($first) {
    echo "first_menu_id={$first->id} name={$first->name} category={$first->category} price={$first->price}\n";
}
