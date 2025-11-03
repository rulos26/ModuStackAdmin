<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

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

// ============================================================================
// RUTAS API DEL MÓDULO AUTH
// ============================================================================
// Solución para hostings que bloquean rutas /api/:
// 1. Rutas con prefijo /api/ (intento principal)
// 2. Rutas alternativas sin prefijo (backup si hosting bloquea /api/)

// SOLUCIÓN 1: Rutas con prefijo /api/ (estándar)
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
        Route::post('register', [AuthController::class, 'register'])->name('api.auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');

        // Rutas protegidas con Sanctum
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', [AuthController::class, 'profile'])->name('api.auth.profile');
            Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        });
    });
});

// SOLUCIÓN 2: Rutas alternativas sin prefijo /api/ (si el hosting bloquea /api/)
// Estas rutas funcionan SIEMPRE porque no usan /api/
Route::middleware(['api'])->group(function () {
    Route::prefix('auth')->group(function () {
        // Rutas públicas alternativas
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');

        // Rutas protegidas alternativas
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', [AuthController::class, 'profile'])->name('auth.profile');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        });
    });
    
    // Ruta de prueba alternativa
    Route::get('/test-api', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API funcionando desde ruta alternativa (sin /api/)',
            'timestamp' => now()->toIso8601String(),
        ]);
    })->name('test.api');
});
