<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para servir archivos estáticos desde /public/ (compatibilidad)
Route::get('/public/css/{file}', function ($file) {
    $path = public_path("css/{$file}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('file', '.*');

Route::get('/public/js/{file}', function ($file) {
    $path = public_path("js/{$file}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('file', '.*');

// Rutas API del módulo Auth (registradas aquí para evitar problemas con hosting)
// Solución alternativa: mover rutas API a web.php ya que algunos hostings no procesan bien routes/api.php
Route::prefix('api')->group(function () {
    // Ruta de prueba
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API funcionando correctamente desde web.php',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
    
    // Cargar rutas del módulo Auth
    Route::prefix('auth')->group(function () {
        // Rutas públicas
        Route::post('register', [\Modules\Auth\Http\Controllers\AuthController::class, 'register']);
        Route::post('login', [\Modules\Auth\Http\Controllers\AuthController::class, 'login']);

        // Rutas protegidas con Sanctum
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', [\Modules\Auth\Http\Controllers\AuthController::class, 'profile']);
            Route::post('logout', [\Modules\Auth\Http\Controllers\AuthController::class, 'logout']);
        });
    });
});
