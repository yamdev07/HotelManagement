<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__.'/../private/laravel/storage/framework/maintenance.php')) {
    require __DIR__.'/../private/laravel/storage/framework/maintenance.php';
}

require __DIR__.'/../private/laravel/vendor/autoload.php';

$app = require_once __DIR__.'/../private/laravel/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
