<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// Fix: Ensure GET requests are properly detected (some servers/proxies may modify REQUEST_METHOD)
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'HEAD') {
    // Check if this is actually a GET request that was converted to HEAD
    // by checking Accept header or other indicators
    if (isset($_SERVER['HTTP_ACCEPT']) && !empty($_SERVER['HTTP_ACCEPT'])) {
        // Browser is sending Accept header, this is likely a GET request
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }
}

$request = Request::capture();
$app->handleRequest($request);

