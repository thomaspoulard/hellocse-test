<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    Route::group(['prefix' => 'profiles', 'as' => 'profiles.'], function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/create', [ProfilController::class, 'store'])->name('store');
            Route::post('/{profil}', [ProfilController::class, 'update'])->name('update');
            Route::delete('/{profil}', [ProfilController::class, 'destroy'])->name('destroy');
        });
    });
});
