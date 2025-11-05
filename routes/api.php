<?php
use App\Http\Controllers\API\Teacher\LoginController;
use App\Http\Controllers\API\Teacher\Administration\StudentController;
use App\Http\Controllers\API\Teacher\Academic\SchoolClassController;
use App\Http\Controllers\API\Teacher\Academic\AttendanceController;


Route::prefix('Teacher')->group(function () {
    Route::prefix('Profile')->group(function () {
        Route::post('/Login', [LoginController::class, 'login'])->name('teacher.login');
        Route::post('/ForgotPassword', [LoginController::class, 'ForgotPassword']);
        Route::post('/ResetPassword', [LoginController::class, 'ResetPassword']);
        Route::post('/ChangePassword', [LoginController::class, 'ChangePassword']);
        Route::post('/UpdateProfile', [LoginController::class, 'UpdateProfile']);
        Route::post('/GetProfile', [LoginController::class, 'GetProfile']);
    });

    Route::prefix('Administration')->group(function () {
        Route::post('/ClassWithSections', [StudentController::class, 'classWithSections'])->name('teacher.classWithSections');
        Route::post('/Students', [StudentController::class, 'students'])->name('teacher.students');
        Route::post('/StudentDetail', [StudentController::class, 'studentDetail'])->name('teacher.studentDetail');
    });

    Route::prefix('Academic')->group(function () {
        Route::post('/Classes', [SchoolClassController::class, 'Classes'])->name('teacher.academic.Classes');
        
        // Attendance Management Routes
        Route::prefix('Attendance')->group(function () {
            Route::post('/GetAttendanceData', [AttendanceController::class, 'getAttendanceData'])->name('teacher.academic.attendance.data');
            Route::post('/GetClassSectionAttendance', [AttendanceController::class, 'getClassSectionAttendance'])->name('teacher.academic.attendance.classSection');
            Route::post('/MarkAttendance', [AttendanceController::class, 'markAttendance'])->name('teacher.academic.attendance.mark');
            Route::post('/GetAttendanceSummary', [AttendanceController::class, 'getAttendanceSummary'])->name('teacher.academic.attendance.summary');
        });
    });
});