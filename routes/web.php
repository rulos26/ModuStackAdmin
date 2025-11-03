<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para servir archivos estáticos si es necesario
Route::get('/css/{file}', function ($file) {
    $path = public_path("css/{$file}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('file', '.*');

Route::get('/js/{file}', function ($file) {
    $path = public_path("js/{$file}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('file', '.*');
