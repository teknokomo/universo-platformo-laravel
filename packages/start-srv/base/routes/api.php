<?php

use Illuminate\Support\Facades\Route;
use Universo\Start\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Start Package API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the StartServiceProvider, which applies
| the /api/v1 prefix via its own Route::prefix('api/v1') definition.
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('start.auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('start.auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('start.auth.logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('start.auth.refresh');
    Route::get('/user', [AuthController::class, 'user'])->name('start.auth.user');
});
