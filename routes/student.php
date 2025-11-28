<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\Setting\SettingsController;
use App\Http\Controllers\Student\Academic\EventController;
use App\Http\Controllers\Student\Academic\RoutineController;

Route::middleware('student')->group(function () {
    Route::prefix('student')->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/dashboard/data', [StudentController::class, 'dashboardData'])->name('student.dashboard.data');
        Route::get('/id-card', [StudentController::class, 'printIdCard'])->name('student.id-card');
        
        // Events Management
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('student.events.index');
            Route::get('/api', [EventController::class, 'getEvents'])->name('student.events.api');
            Route::get('/upcoming', [EventController::class, 'getUpcomingEvents'])->name('student.events.upcoming');
        });

        // Routine Management
        Route::prefix('routine')->group(function () {
            Route::get('/', [RoutineController::class, 'index'])->name('student.routine.index');
            Route::get('/api', [RoutineController::class, 'getRoutineData'])->name('student.routine.api');
        });

        // Attendance Management
        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('student.attendance');
            Route::get('/my-attendance-matrix', [AttendanceController::class, 'getMyAttendanceMatrix']);
            Route::get('/stats', [AttendanceController::class, 'getAttendanceStats']);
        });

        // Assignment Management
        Route::prefix('assignments')->group(function () {
            Route::get('/', [AssignmentController::class, 'index'])->name('student.assignments.index');
            Route::get('/{id}', [AssignmentController::class, 'show'])->name('student.assignments.show');
            Route::post('/{id}/submit', [AssignmentController::class, 'submit'])->name('student.assignments.submit');
            Route::get('/{id}/download-assignment', [AssignmentController::class, 'downloadAssignment'])->name('student.assignments.download-assignment');
            Route::get('/submission/{id}/download', [AssignmentController::class, 'downloadSubmission'])->name('student.assignments.download-submission');
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
