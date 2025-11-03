<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Module Routes
|--------------------------------------------------------------------------
|
| Rutas del módulo Core
|
*/

Route::prefix('core')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Core funcionando',
            'module' => 'Core',
            'version' => core_version(),
        ], 200);
    })->name('core.index');
});

