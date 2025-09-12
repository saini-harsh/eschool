<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Teacher\Setting\SettingsController;
use App\Http\Controllers\Teacher\Administration\StudentController;
use App\Http\Controllers\Teacher\Routine\LessonPlanController;
use App\Http\Controllers\Teacher\Routine\AssignmentController;
use App\Http\Controllers\Teacher\Routine\RoutineController;
use App\Http\Controllers\Teacher\Academic\SchoolClassController;


Route::middleware('teacher')->group(function () {
    Route::prefix('teacher')->group(function () {

        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

        Route::prefix('settings')->group(function () {
            Route::get('/index', [SettingsController::class, 'index'])->name('teacher.settings.index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('teacher.settings.profile');
            Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('teacher.settings.change-password');
            Route::post('/delete-profile-image', [SettingsController::class, 'deleteProfileImage'])->name('teacher.settings.delete-profile-image');
        });

        Route::prefix('students')->group(function () {
            Route::get('/index', [StudentController::class, 'Index'])->name('teacher.students.index');
            Route::get('/show/{student}', [StudentController::class, 'Show'])->name('teacher.students.show');
            Route::get('/sections/{classId}', [StudentController::class, 'getSectionsByClass'])->name('teacher.students.sections');
            Route::post('/get-by-class-section', [StudentController::class, 'getStudentsByClassSection'])->name('teacher.students.get-by-class-section');
        });
          // Classes
          Route::prefix('classes')->group(function () {
             Route::get('/',[SchoolClassController::class,'index'])->name('teacher.classes.index');
         });
        // ROUTINE MANAGEMENT
        Route::prefix('routines')->group(function () {
            Route::get('/', [RoutineController::class, 'index'])->name('teacher.routines.index');
            Route::get('/report', [RoutineController::class, 'getRoutineReport'])->name('teacher.routines.report');

            // API routes for dynamic dropdowns
            Route::get('/sections/{classId}', [RoutineController::class, 'getSectionsByClass'])->name('teacher.routines.sections');
        });
        // LESSON PLANS
        Route::prefix('lesson-plans')->group(function () {
            Route::get('/', [LessonPlanController::class, 'index'])->name('teacher.lesson-plans.index');
            Route::post('/', [LessonPlanController::class, 'store'])->name('teacher.lesson-plans.store');
            Route::get('/{id}/edit', [LessonPlanController::class, 'edit'])->name('teacher.lesson-plans.edit');
            Route::POST('/{id}', [LessonPlanController::class, 'update'])->name('teacher.lesson-plans.update');
            Route::delete('/{id}', [LessonPlanController::class, 'destroy'])->name('teacher.lesson-plans.destroy');
            Route::post('/{id}/status', [LessonPlanController::class, 'updateStatus'])->name('teacher.lesson-plans.status');
            Route::get('/{id}/download', [LessonPlanController::class, 'download'])->name('teacher.lesson-plans.download');

            // API routes for dynamic dropdowns
            Route::get('/teachers/{institutionId}', [LessonPlanController::class, 'getTeachersByInstitution'])->name('teacher.lesson-plans.teachers');
            Route::get('/classes/{institutionId}', [LessonPlanController::class, 'getClassesByInstitution'])->name('teacher.lesson-plans.classes');
            Route::get('/classes-by-teacher/{institutionId}/{teacherId}', [LessonPlanController::class, 'getClassesByTeacher'])->name('teacher.lesson-plans.classes-by-teacher');
            Route::get('/subjects/{institutionId}/{classId}', [LessonPlanController::class, 'getSubjectsByInstitutionClass'])->name('teacher.lesson-plans.subjects');
        });

        // ASSIGNMENTS
        Route::prefix('assignments')->group(function () {
            Route::get('/', [AssignmentController::class, 'index'])->name('teacher.assignments.index');
            Route::post('/', [AssignmentController::class, 'store'])->name('teacher.assignments.store');
            Route::get('/{id}/edit', [AssignmentController::class, 'edit'])->name('teacher.assignments.edit');
            Route::POST('/{id}', [AssignmentController::class, 'update'])->name('teacher.assignments.update');
            Route::delete('/{id}', [AssignmentController::class, 'destroy'])->name('teacher.assignments.destroy');
            Route::post('/{id}/status', [AssignmentController::class, 'updateStatus'])->name('teacher.assignments.status');
            Route::get('/{id}/download', [AssignmentController::class, 'download'])->name('teacher.assignments.download');

            // API routes for dynamic dropdowns
            Route::get('/sections/{classId}', [AssignmentController::class, 'getSectionsByClass'])->name('teacher.assignments.sections');
            Route::get('/subjects/{institutionId}/{classId}', [AssignmentController::class, 'getSubjectsByInstitutionClass'])->name('teacher.assignments.subjects');
        });
    });
    // Route::post('logout', [TeacherController::class, 'logout'])->name('teacher.logout');

});
