<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\PaymentController;

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

        // Assignment Management
        Route::prefix('assignments')->group(function () {
            Route::get('/', [AssignmentController::class, 'index'])->name('student.assignments.index');
            Route::get('/{id}', [AssignmentController::class, 'show'])->name('student.assignments.show');
            Route::post('/{id}/submit', [AssignmentController::class, 'submit'])->name('student.assignments.submit');
            Route::get('/{id}/download-assignment', [AssignmentController::class, 'downloadAssignment'])->name('student.assignments.download-assignment');
            Route::get('/submission/{id}/download', [AssignmentController::class, 'downloadSubmission'])->name('student.assignments.download-submission');
        });

        // Payment Management
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('student.payments.index');
            Route::get('/pending', [PaymentController::class, 'pendingPayments'])->name('student.payments.pending');
            Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('student.payments.history');
            Route::get('/payment/{payment}', [PaymentController::class, 'showPayment'])->name('student.payments.show');
            Route::get('/payment/{payment}/receipt', [PaymentController::class, 'generateReceipt'])->name('student.payments.receipt');
            Route::get('/payment/{payment}/download-receipt', [PaymentController::class, 'downloadReceipt'])->name('student.payments.download-receipt');
            Route::get('/fee/{studentFee}', [PaymentController::class, 'showFee'])->name('student.payments.fee-details');
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
