<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'head'], '/', function () {
    return view('welcome');
})->name('home');
