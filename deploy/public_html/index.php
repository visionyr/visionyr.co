<?php

/*
|--------------------------------------------------------------------------
| Front Controller for shared hosting
|--------------------------------------------------------------------------
|
| Copy this file into the document root (public_html) together with the rest
| of the project's public/ folder — .htaccess, favicon.ico, robots.txt, build/.
|
| It finds the application in either supported layout:
|
|   Preferred — application outside the web root:
|       domains/yoursite/laravel/       app, config, vendor, .env
|       domains/yoursite/public_html/   this file and the other public assets
|
|   Fallback — everything in the web root:
|       domains/yoursite/public_html/   the whole project, this file at the top
|
|   It also still works in the ordinary layout, with this file in public/.
|
| The preferred layout is the one to use where the host allows it: nothing
| outside public/ is reachable over HTTP even if .htaccess stops being applied.
|
*/

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Whichever of these holds the autoloader is the application root. Checking for
// the file itself rather than a folder name means this one file works unchanged
// in every layout, including the ordinary local one.
$app_base = null;

foreach ([__DIR__.'/../laravel', __DIR__, __DIR__.'/..'] as $candidate) {
    if (is_file($candidate.'/vendor/autoload.php')) {
        $app_base = $candidate;
        break;
    }
}

if ($app_base === null) {
    http_response_code(500);
    exit('Application not found. Check that the project was uploaded and that vendor/ exists.');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $app_base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $app_base.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $app_base.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
