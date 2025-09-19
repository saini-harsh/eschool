<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\AttendanceController;

Route::middleware('student')->group(function () {
    Route::prefix('student')->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

        // Attendance Management
        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('student.attendance');
            Route::get('/my-attendance-matrix', [AttendanceController::class, 'getMyAttendanceMatrix']);
            Route::get('/stats', [AttendanceController::class, 'getAttendanceStats']);
        });

        Route::prefix('settings')->group(function () {
            Route::get('/index', [SettingsController::class, 'index'])->name('student.settings.index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('student.settings.profile');
            Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('student.settings.change-password');
            Route::post('/delete-profile-image', [SettingsController::class, 'deleteProfileImage'])->name('student.settings.delete-profile-image');
        });
    });


    // Logout
    // Route::post('logout', [StudentController::class, 'logout'])->name('student.logout');
});
