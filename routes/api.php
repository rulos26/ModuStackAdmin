<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Ruta de prueba para verificar que las rutas API funcionan
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API funcionando correctamente',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Las rutas del módulo Auth se cargan desde AuthServiceProvider

