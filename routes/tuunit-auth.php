<?php

use App\Http\Controllers\tuunit\Auth\LoginController;
use App\Http\Controllers\tuunit\Auth\RegisteredUserController;
use App\Http\Controllers\TuunitProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('tuunit')->middleware('guest:tuunit')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('tuunit.register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [LoginController::class, 'create'])->name('tuunit.login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::prefix('tuunit')->middleware('auth:tuunit')->group(function () {

    Route::get('/dashboard', function () {
        return view('tuunit.dashboard');
    })->name('tuunit.dashboard');

    Route::get('/manage-transaksi', action: function () {
        return view('tuunit.manage-transaksi');
    })->name('tuunit.manage-transaksi');

    Route::get(uri: '/laporan-transaksi', action: function () {
        return view('tuunit.laporan-transaksi');
    })->name('tuunit.laporan-transaksi');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', action: [TuunitProfileController::class, 'edit'])->name(name: 'tuunit.profile.edit');
        Route::patch('/profile', action: [TuunitProfileController::class, 'update'])->name('tuunit.profile.update');
        Route::delete('/profile', action: [TuunitProfileController::class, 'destroy'])->name('tuunit.profile.destroy');
    });

    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');
});
