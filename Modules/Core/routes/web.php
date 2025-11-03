<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your Core module.
|
*/

Route::prefix('core')->group(function () {
    Route::get('/', function () {
        return view('core::welcome');
    })->name('core.index');
});

