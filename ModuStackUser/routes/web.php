<?php

use Illuminate\Support\Facades\Route;

// Route for home - accept both GET and HEAD explicitly
Route::get('/', function () {
    return view('welcome');
})->name('home');
