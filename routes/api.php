<?php
use App\Http\Controllers\API\Teacher\LoginController;


Route::prefix('teacher')->group(function () {
    Route::prefix('Profile')->group(function () {
        Route::post('/Login', [LoginController::class, 'login'])->name('teacher.login');
        Route::post('/ForgotPassword', [LoginController::class, 'ForgotPassword']);
        Route::post('/ResetPassword', [LoginController::class, 'ResetPassword']);
        Route::post('/ChangePassword', [LoginController::class, 'ChangePassword']);
        Route::post('/UpdateProfile', [LoginController::class, 'UpdateProfile']);
        Route::post('/GetProfile', [LoginController::class, 'GetProfile']);
    });
});