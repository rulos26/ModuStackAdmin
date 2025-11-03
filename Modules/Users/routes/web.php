<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Users Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your Users module.
|
*/

Route::prefix('users')->group(function () {
    Route::get('/', function () {
        return view('users::welcome');
    })->name('users.index');
});

