<?php

use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'profiles', 'as' => 'profiles.'], function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::post('/create', [ProfilController::class, 'store'])->name('store');
        Route::patch('/{profil}', [ProfilController::class, 'update'])->name('update');
        Route::delete('/{profil}', [ProfilController::class, 'destroy'])->name('destroy');
    });
});
