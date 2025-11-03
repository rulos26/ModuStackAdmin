<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Users Module Routes
|--------------------------------------------------------------------------
|
| Rutas del módulo Users
|
*/

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
});

