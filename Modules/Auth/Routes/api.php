<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth Module API Routes
|--------------------------------------------------------------------------
|
| Rutas API del módulo de autenticación
|
*/

// Rutas API del módulo Auth
// El prefijo 'api' se aplica en AuthServiceProvider
Route::prefix('auth')->group(function () {
    // Rutas públicas
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

