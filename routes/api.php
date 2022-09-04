<?php

use App\Http\Controllers\ApiHealthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api'],
], function() {
    Route::get('health', [ApiHealthController::class, 'index']);

    Route::prefix('email')->group(function() {
        Route::get('initialize', [EmailController::class, 'initialize'])->name('send.initialize');
    });

    Route::middleware(['authorized'])->group(function() {
        Route::prefix('email')->group(function() {
            Route::post('send', [EmailController::class, 'send'])->name('send.email');
        });
        Route::prefix('captcha')->group(function() {
            Route::get('refresh', [CaptchaController::class, 'refresh'])->name('captcha.refresh');
        });
    });
});

Route::fallback(fn () => response('Not found.', 404));
