<?php

use App\Http\Controllers\Teacher\Administration\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Teacher\Setting\SettingsController;
use App\Http\Controllers\Teacher\Administration\StudentController;
use App\Http\Controllers\Teacher\Routine\LessonPlanController;
use App\Http\Controllers\Teacher\Routine\AssignmentController;
use App\Http\Controllers\Teacher\Routine\RoutineController;
use App\Http\Controllers\Teacher\Academic\SchoolClassController;
use App\Http\Controllers\Teacher\Academic\EventController;


Route::middleware('teacher')->group(function () {
    Route::prefix('teacher')->group(function () {

        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
        
        // Events Management
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('teacher.events.index');
            Route::get('/api', [EventController::class, 'getEvents'])->name('teacher.events.api');
            Route::get('/upcoming', [EventController::class, 'getUpcomingEvents'])->name('teacher.events.upcoming');
        });

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

        Route::prefix('attendance')->group(function () {
            Route::get('/', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'index'])->name('teacher.attendance');
            Route::get('/mark', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'markAttendancePage'])->name('teacher.attendance.mark-page');
            Route::get('/matrix', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'getAttendanceMatrix']);
            Route::post('/mark', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'markAttendance'])->name('teacher.attendance.mark');
            Route::put('/{id}', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'updateAttendance'])->name('teacher.attendance.update');
            Route::get('/my-attendance-matrix', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'getMyAttendanceMatrix']);
            Route::post('/mark-my-attendance', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'markMyAttendance'])->name('teacher.attendance.mark-my');
            
            // AJAX routes for dynamic dropdowns
            Route::get('/sections/{classId}', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'getSectionsByClass']);
            Route::get('/students', [\App\Http\Controllers\Teacher\Administration\AttendanceController::class, 'getStudentsByClassSection']);
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
            Route::get('/{id}/submissions', [AssignmentController::class, 'viewSubmissions'])->name('teacher.assignments.submissions');
            Route::post('/{id}/grade', [AssignmentController::class, 'gradeAssignment'])->name('teacher.assignments.grade');
            Route::get('/submission/{id}/download', [AssignmentController::class, 'downloadStudentSubmission'])->name('teacher.assignments.download-submission');

            // API routes for dynamic dropdowns
            Route::get('/sections/{classId}', [AssignmentController::class, 'getSectionsByClass'])->name('teacher.assignments.sections');
            Route::get('/subjects/{institutionId}/{classId}', [AssignmentController::class, 'getSubjectsByInstitutionClass'])->name('teacher.assignments.subjects');
        });

    });


    // Route::post('logout', [TeacherController::class, 'logout'])->name('teacher.logout');

});
