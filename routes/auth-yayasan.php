<?php

use App\Http\Controllers\yayasan\Auth\LoginController;
use App\Http\Controllers\yayasan\Auth\yayasanAuthenticatedSessionController;
use App\Http\Controllers\yayasan\Auth\ConfirmablePasswordController;
use App\Http\Controllers\yayasan\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\yayasan\Auth\EmailVerificationPromptController;
use App\Http\Controllers\yayasan\Auth\NewPasswordController;
use App\Http\Controllers\yayasan\Auth\PasswordController;
use App\Http\Controllers\yayasan\Auth\PasswordResetLinkController;
use App\Http\Controllers\yayasan\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


Route::prefix('yayasan')->middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('yayasan.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('yayasan.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('yayasan.password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('yayasan.password.store');

    Route::get('login', [LoginController::class, 'create'])->name('yayasan.login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('yayasan.verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('yayasan.verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('yayasan.verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('yayasan.password.confirm');

    Route::put('password', [PasswordController::class, 'update'])->name('yayasan.password.update');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
});