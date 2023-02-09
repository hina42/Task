<?php

use App\Http\Controllers\Managerauth\AuthenticatedSessionController;
use App\Http\Controllers\Managerauth\ConfirmablePasswordController;
use App\Http\Controllers\Managerauth\EmailVerificationNotificationController;
use App\Http\Controllers\Managerauth\EmailVerificationPromptController;
use App\Http\Controllers\Managerauth\NewPasswordController;
use App\Http\Controllers\Managerauth\PasswordController;
use App\Http\Controllers\Managerauth\PasswordResetLinkController;
use App\Http\Controllers\Managerauth\RegisteredUserController;
use App\Http\Controllers\Managerauth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manager','middleware' => ['guest:manager']], function() {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('manager.register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('manager.register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('manager.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('manager.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('manager.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('manager.password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('manager.password.store');
});

Route::group(['prefix' => 'manager','middleware' => ['auth:manager']], function() {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('manager.verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('manager.verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('manager.verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('manager.password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('manager.password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('manager.logout');
});

