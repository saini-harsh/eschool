<?php
use App\Http\Controllers\API\Teacher\LoginController;
use App\Http\Controllers\API\Teacher\Administration\StudentController;
use App\Http\Controllers\API\Teacher\Academic\SchoolClassController;
use App\Http\Controllers\API\Teacher\Academic\AttendanceController;
use App\Http\Controllers\API\Teacher\Academic\RoutineController;
use App\Http\Controllers\API\Teacher\Academic\LessonPlanController;


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
        
        // Attendance Management Route
        Route::prefix('Attendance')->group(function () {
            Route::post('/FilterAttendance', [AttendanceController::class, 'filterAttendance']);
            Route::post('/GetStudentsForAttendance', [AttendanceController::class, 'getStudentsForAttendance']);
            Route::post('/MarkAttendanceStudent', [AttendanceController::class, 'markAttendanceStudent']);
            Route::post('/MarkAttendanceTeacher', [AttendanceController::class, 'markAttendanceTeacher']);
        });

        // Routine Management Route
        Route::prefix('Routine')->group(function () {
            Route::post('/ClassRoutineReport', [RoutineController::class, 'getRoutineReport']);
        });

        // Lesson Plans Management Route
        Route::prefix('LessonPlans')->group(function () {
            Route::post('/LessonPlanList', [LessonPlanController::class, 'lessonPlanList']);
            Route::post('/AddLessonPlan', [LessonPlanController::class, 'addLessonPlan']);
            Route::post('/EditLessonPlan', [LessonPlanController::class, 'editLessonPlan']);
        });
    });
});
