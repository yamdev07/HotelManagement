<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Dossier public sur hébergement scindé (app dans private/, web root dans web/)
|--------------------------------------------------------------------------
|
| Sur ce type d'hébergement, la racine servie ("web/") est à côté du dossier
| de l'application ("private/"). Par défaut public_path() pointe vers
| private/public, qui n'est PAS servi : les images de chambres uploadées via
| public_path() n'étaient donc pas accessibles. Si un dossier "web" existe à
| côté de la racine de l'app, on l'utilise comme dossier public. En local ce
| dossier n'existe pas, donc le comportement par défaut est conservé.
|
*/

$webRoot = dirname($app->basePath()).DIRECTORY_SEPARATOR.'web';
if (is_dir($webRoot)) {
    $app->usePublicPath($webRoot);
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
