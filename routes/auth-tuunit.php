<?php

use App\Http\Controllers\tuunit\auth\LoginController;
use App\Http\Controllers\tuunit\auth\TuunitAuthenticatedSessionController;
use App\Http\Controllers\tuunit\auth\ConfirmablePasswordController;
use App\Http\Controllers\tuunit\auth\EmailVerificationNotificationController;
use App\Http\Controllers\tuunit\auth\EmailVerificationPromptController;
use App\Http\Controllers\tuunit\auth\NewPasswordController;
use App\Http\Controllers\tuunit\auth\PasswordController;
use App\Http\Controllers\tuunit\auth\PasswordResetLinkController;
use App\Http\Controllers\tuunit\auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


Route::prefix('tuunit')->middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('tuunit.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('tuunit.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('tuunit.password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('tuunit.password.store');

    Route::get('login', [LoginController::class, 'create'])->name('tuunit.login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('tuunit.verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('tuunit.verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('tuunit.verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('tuunit.password.confirm');

    Route::put('password', [PasswordController::class, 'update'])->name('tuunit.password.update');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
});