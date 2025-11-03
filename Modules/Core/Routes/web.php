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
    
    Route::get('/users-count', function () {
        try {
            $usersCount = \App\Models\User::count();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'module' => 'Core',
                    'integrated_module' => 'Users',
                    'users_count' => $usersCount,
                    'timestamp' => now()->toIso8601String(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener conteo de usuarios',
                'error' => $e->getMessage(),
            ], 500);
        }
    })->name('core.users-count');
});

