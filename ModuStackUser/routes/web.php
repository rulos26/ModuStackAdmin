<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users Management
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('users/{user}/activate', [\App\Http\Controllers\UserController::class, 'activate'])->name('users.activate');
    Route::post('users/{user}/deactivate', [\App\Http\Controllers\UserController::class, 'deactivate'])->name('users.deactivate');

    // Roles Management
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{activityLog}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('activity-logs/export/pdf', [\App\Http\Controllers\ActivityLogController::class, 'exportPdf'])->name('activity-logs.export.pdf');
    Route::get('activity-logs/export/excel', [\App\Http\Controllers\ActivityLogController::class, 'exportExcel'])->name('activity-logs.export.excel');
});

require __DIR__.'/auth.php';
