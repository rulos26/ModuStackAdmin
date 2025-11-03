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
