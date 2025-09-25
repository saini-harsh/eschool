<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\Institution\Setting\SettingsController;
use App\Http\Controllers\Institution\Administration\TeacherController;
use App\Http\Controllers\Institution\Administration\StudentController;
use App\Http\Controllers\Institution\Administration\NonWorkingStaffController;
use App\Http\Controllers\Institution\Academic\SchoolClassController;
use App\Http\Controllers\Institution\Academic\SectionController;
use App\Http\Controllers\Institution\Academic\SubjectController;
use App\Http\Controllers\Institution\Academic\AssignClassTeacherController;
use App\Http\Controllers\Institution\Academic\AssignSubjectController;
use App\Http\Controllers\Institution\Academic\CalendarController;
use App\Http\Controllers\Institution\Academic\EventController;
use App\Http\Controllers\Institution\Communication\EmailSmsController;
use App\Http\Controllers\Institution\Routine\RoutineController;
use App\Http\Controllers\Institution\Routine\LessonPlanController;

Route::middleware('institution')->group(function () {
   
    Route::prefix('institution')->group(function () {
        Route::get('/dashboard', [InstitutionController::class, 'dashboard'])->name('institution.dashboard');

        Route::prefix('settings')->group(function () {
            Route::get('/index', [SettingsController::class, 'index'])->name('institution.settings.index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('institution.settings.profile');
            Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('institution.settings.change-password');
            Route::post('/delete-profile-image', [SettingsController::class, 'deleteProfileImage'])->name('institution.settings.delete-profile-image');
        });

        Route::prefix('teachers')->group(function () {
            Route::get('/index', [TeacherController::class, 'Index'])->name('institution.teachers.index');
            Route::get('/show/{teacher}', [TeacherController::class, 'Show'])->name('institution.teachers.show');
            Route::get('/create', [TeacherController::class, 'Create'])->name('institution.teachers.create');
            Route::post('/store', [TeacherController::class, 'Store'])->name('institution.teachers.store');
            Route::get('/edit/{teacher}', [TeacherController::class, 'Edit'])->name('institution.teachers.edit');
            Route::post('/update/{teacher}', [TeacherController::class, 'Update'])->name('institution.teachers.update');
            Route::post('/delete/{teacher}', [TeacherController::class, 'Delete'])->name('institution.teachers.delete');
            Route::post('/status/{id}', [TeacherController::class, 'updateStatus'])->name('institution.teachers.status');
        });
        Route::prefix('students')->group(function () {
            Route::get('/index', [StudentController::class, 'Index'])->name('institution.students.index');
            Route::get('/create', [StudentController::class, 'Create'])->name('institution.students.create');
            Route::post('/store', [StudentController::class, 'Store'])->name('institution.students.store');
            Route::get('/show/{student}', [StudentController::class, 'Show'])->name('institution.students.show');
            Route::get('/edit/{student}', [StudentController::class, 'Edit'])->name('institution.students.edit');
            Route::post('/update/{student}', [StudentController::class, 'Update'])->name('institution.students.update');
            Route::post('/delete/{student}', [StudentController::class, 'Delete'])->name('institution.students.delete');
            Route::get('/classes/{institutionId}', [StudentController::class, 'getClassesByInstitution'])->name('institution.students.classes');
            Route::get('/teachers/{institutionId}', [StudentController::class, 'getTeachersByInstitution'])->name('institution.students.teachers');
            Route::get('/sections/{classId}', [StudentController::class, 'getSectionsByClass'])->name('institution.students.sections');
            Route::post('/status/{id}', [StudentController::class, 'updateStatus'])->name('institution.students.status');
        });
        Route::prefix('nonworkingstaff')->group(function () {
            Route::get('/index', [NonWorkingStaffController::class, 'Index'])->name('institution.nonworkingstaff.index');
            Route::get('/create', [NonWorkingStaffController::class, 'Create'])->name('institution.nonworkingstaff.create');
            Route::post('/store', [NonWorkingStaffController::class, 'Store'])->name('institution.nonworkingstaff.store');
            Route::get('/edit/{nonworkingstaff}', [NonWorkingStaffController::class, 'Edit'])->name('institution.nonworkingstaff.edit');
            Route::post('/update/{nonworkingstaff}', [NonWorkingStaffController::class, 'Update'])->name('institution.nonworkingstaff.update');
            Route::post('/delete/{nonworkingstaff}', [NonWorkingStaffController::class, 'Delete'])->name('institution.nonworkingstaff.delete');
            Route::post('/status/{id}', [NonWorkingStaffController::class, 'updateStatus'])->name('institution.nonworkingstaff.status');
        });

        Route::prefix('attendance')->group(function () {
            Route::get('/', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'index'])->name('institution.attendance');
            Route::get('/mark', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'markAttendancePage'])->name('institution.attendance.mark-page');
            Route::get('/matrix', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getAttendanceMatrix']);
            Route::post('/mark', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'markAttendance'])->name('institution.attendance.mark');
            Route::put('/{id}', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'updateAttendance'])->name('institution.attendance.update');
            Route::post('/{id}/confirm', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'confirmAttendance'])->name('institution.attendance.confirm');
            Route::delete('/{id}', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'deleteAttendance'])->name('institution.attendance.delete');
            
            // AJAX routes for dynamic dropdowns
            Route::get('/sections/{classId}', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getSectionsByClass']);
            Route::get('/teachers', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getTeachersByClassSection']);
            Route::get('/students', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getStudentsByClassSection']);
            Route::get('/institution-teachers', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getTeachersByInstitution']);
            Route::get('/institution-staff', [\App\Http\Controllers\Institution\Administration\AttendanceController::class, 'getStaffByInstitution']);
        });

         // Classes
         Route::prefix('classes')->group(function () {
            Route::get('/',[SchoolClassController::class,'index'])->name('institution.classes.index');
            Route::post('/store',[SchoolClassController::class,'store'])->name('institution.classes.store');
            Route::get('/list',[SchoolClassController::class,'getSchoolClasses'])->name('institution.classes.list');
            Route::get('/edit/{id}', [SchoolClassController::class, 'edit'])->name('institution.classes.edit');
            Route::post('/update/{id}', [SchoolClassController::class, 'update'])->name('institution.classes.update');
            Route::post('/delete/{id}', [SchoolClassController::class, 'delete'])->name('institution.classes.delete');
            Route::post('/{id}/status',[SchoolClassController::class,'updateStatus'])->name('institution.classes.status');
        });

        // SECTIONS
        Route::prefix('sections')->group(function(){
            Route::get('/',[SectionController::class,'index'])->name('institution.sections.index');
            Route::post('/store',[SectionController::class,'store'])->name('institution.sections.store');
            Route::get('/list',[SectionController::class,'getSections'])->name('institution.sections.list');
            Route::get('/edit/{id}', [SectionController::class, 'edit'])->name('institution.sections.edit');
            Route::post('/update/{id}', [SectionController::class, 'update'])->name('institution.sections.update');
            Route::post('/delete/{id}', [SectionController::class, 'delete'])->name('institution.sections.delete');
            Route::post('/{id}/status',[SectionController::class,'updateStatus'])->name('institution.sections.status');
        });

        // SUBJECTS
        Route::prefix('subjects')->group(function () {
            Route::get('/', [SubjectController::class, 'Index'])->name('institution.subjects.index');
            Route::post('/store', [SubjectController::class, 'store'])->name('institution.subjects.store');
            Route::get('/list', [SubjectController::class, 'getSubjects'])->name('institution.subjects.list');
            Route::post('/{id}/status', [SubjectController::class, 'updateStatus'])->name('institution.subjects.status');
            Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('institution.subjects.edit');
            Route::post('/update/{id}', [SubjectController::class, 'update'])->name('institution.subjects.update');
            Route::post('/delete/{id}', [SubjectController::class, 'delete'])->name('institution.subjects.delete');
        });

        // ASSIGN CLASS TEACHER
        Route::prefix('academic')->group(function () {
            Route::prefix('assign-class-teacher')->group(function () {
                Route::get('/', [AssignClassTeacherController::class, 'index'])->name('institution.academic.assign-teacher.index');
                Route::post('/', [AssignClassTeacherController::class, 'store'])->name('institution.assign-class-teacher.store');
                Route::get('/list', [AssignClassTeacherController::class, 'getAssignments'])->name('institution.assign-class-teacher.list');

                // AJAX routes for dynamic dropdowns (must come before parameterized routes)
                Route::get('/classes/{institutionId}', [AssignClassTeacherController::class, 'getClassesByInstitution']);
                Route::get('/teachers/{institutionId}', [AssignClassTeacherController::class, 'getTeachersByInstitution']);
                Route::get('/sections/{classId}', [AssignClassTeacherController::class, 'getSectionsByClass']);

                // Parameterized routes (must come after specific routes)
                Route::get('/{id}/edit', [AssignClassTeacherController::class, 'edit'])->name('institution.assign-class-teacher.edit');
                Route::post('/{id}', [AssignClassTeacherController::class, 'update'])->name('institution.assign-class-teacher.update');
                Route::delete('/{id}', [AssignClassTeacherController::class, 'destroy'])->name('institution.assign-class-teacher.destroy');
                Route::post('/{id}/status', [AssignClassTeacherController::class, 'updateStatus'])->name('institution.assign-class-teacher.status');
            });
        });

        // ASSIGN SUBJECT
        Route::prefix('assign-subject')->group(function () {
            Route::get('/', [AssignSubjectController::class, 'index'])->name('institution.assign-subject.index');
            Route::post('/', [AssignSubjectController::class, 'store'])->name('institution.assign-subject.store');
            Route::get('/list', [AssignSubjectController::class, 'getAssignments'])->name('institution.assign-subject.list');

            // AJAX routes for dynamic dropdowns (must come before parameterized routes)
            Route::get('/classes/{institutionId}', [AssignSubjectController::class, 'getClassesByInstitution']);
            Route::get('/teachers/{institutionId}', [AssignSubjectController::class, 'getTeachersByInstitution']);
            Route::get('/subjects/{institutionId}/{classId}', [AssignSubjectController::class, 'getSubjectsByInstitutionClass']);
            Route::get('/sections/{classId}', [AssignSubjectController::class, 'getSectionsByClass']);

            // Parameterized routes (must come after specific routes)
            Route::get('/{id}/edit', [AssignSubjectController::class, 'edit'])->name('institution.assign-subject.edit');
            Route::post('/{id}', [AssignSubjectController::class, 'update'])->name('institution.assign-subject.update');
            Route::delete('/{id}', [AssignSubjectController::class, 'destroy'])->name('institution.assign-subject.destroy');
            Route::post('/{id}/status', [AssignSubjectController::class, 'updateStatus'])->name('institution.assign-subject.status');
        });

        

        // Academic Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('institution.academic.calendar.index');
            Route::get('/events', [CalendarController::class, 'getEvents'])->name('institution.academic.calendar.events');
            Route::get('/check-database', [CalendarController::class, 'checkDatabase'])->name('institution.academic.calendar.check-database');
            Route::post('/events', [CalendarController::class, 'store'])->name('institution.academic.calendar.store');
            Route::put('/events/{id}', [CalendarController::class, 'update'])->name('institution.academic.calendar.update');
            Route::delete('/events/{id}', [CalendarController::class, 'destroy'])->name('institution.academic.calendar.destroy');
            Route::get('/events/{id}', [CalendarController::class, 'getEvent'])->name('institution.academic.calendar.show');
        });

        // Event Management Routes
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('institution.events.index');
            Route::post('/', [EventController::class, 'store'])->name('institution.events.store');
            Route::get('/list', [EventController::class, 'getEvents'])->name('institution.events.list');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('institution.events.edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('institution.events.update');
            Route::post('/delete/{id}', [EventController::class, 'delete'])->name('institution.events.delete');
            Route::post('/{id}/status', [EventController::class, 'updateStatus'])->name('institution.events.status');
        });

        // Email / Sms Routes
        Route::prefix('email-sms')->group(function () {
            Route::get('/', [EmailSmsController::class, 'index'])->name('institution.email-sms.index');
            Route::get('/index', [EmailSmsController::class, 'index'])->name('institution.email-sms.index');
            Route::post('/store', [EmailSmsController::class, 'store'])->name('institution.email-sms.store');
            Route::get('/list', [EmailSmsController::class, 'getEmailSms'])->name('institution.email-sms.list');
            Route::get('/edit/{id}', [EmailSmsController::class, 'edit'])->name('institution.email-sms.edit');
            Route::post('/update/{id}', [EmailSmsController::class, 'update'])->name('institution.email-sms.update');
            Route::post('/delete/{id}', [EmailSmsController::class, 'delete'])->name('institution.email-sms.delete');
            Route::post('/{id}/status', [EmailSmsController::class, 'updateStatus'])->name('institution.email-sms.status');

            // Group selection routes
            Route::get('/institutions', [EmailSmsController::class, 'getInstitutions'])->name('institution.email-sms.institutions');
            Route::get('/teachers/{institutionId}', [EmailSmsController::class, 'getTeachersByInstitution'])->name('institution.email-sms.teachers');
            Route::get('/students/{institutionId}', [EmailSmsController::class, 'getStudentsByInstitution'])->name('institution.email-sms.students');
            Route::get('/parents/{institutionId}', [EmailSmsController::class, 'getParentsByInstitution'])->name('institution.email-sms.parents');
            Route::get('/non-working-staff/{institutionId}', [EmailSmsController::class, 'getNonWorkingStaffByInstitution'])->name('institution.email-sms.non-working-staff');

            // Class selection routes
            Route::get('/classes/{institutionId}', [EmailSmsController::class, 'getClassesByInstitution'])->name('institution.email-sms.classes');
            Route::get('/sections/{classId}', [EmailSmsController::class, 'getSectionsByClass'])->name('institution.email-sms.sections');
            Route::get('/class-students-parents/{classId}/{sectionId?}', [EmailSmsController::class, 'getStudentsAndParentsByClassSection'])->name('institution.email-sms.class-students-parents');
        });

        // ROUTINE MANAGEMENT
        Route::prefix('routines')->group(function () {
            Route::get('/', [RoutineController::class, 'index'])->name('institution.routines.index');
            Route::get('/create', [RoutineController::class, 'create'])->name('institution.routines.create');
            Route::post('/', [RoutineController::class, 'store'])->name('institution.routines.store');
            Route::get('/report', [RoutineController::class, 'getRoutineReport'])->name('institution.routines.report');
            Route::post('/{id}/status', [RoutineController::class, 'updateStatus'])->name('institution.routines.status');
            Route::delete('/{id}', [RoutineController::class, 'destroy'])->name('institution.routines.destroy');

            // API routes for dynamic dropdowns
            Route::get('/classes/{institutionId}', [RoutineController::class, 'getClassesByInstitution'])->name('institution.routines.classes');
            Route::get('/sections/{classId}', [RoutineController::class, 'getSectionsByClass'])->name('institution.routines.sections');
            Route::get('/subjects/{institutionId}/{classId}', [RoutineController::class, 'getSubjectsByInstitutionClass'])->name('institution.routines.subjects');
            Route::get('/teachers/{institutionId}', [RoutineController::class, 'getTeachersByInstitution'])->name('institution.routines.teachers');



        });
        // LESSON PLANS
        Route::prefix('lesson-plans')->group(function () {
            Route::get('/', [LessonPlanController::class, 'index'])->name('institution.lesson-plans.index');
            Route::post('/', [LessonPlanController::class, 'store'])->name('institution.lesson-plans.store');
            Route::get('/{id}/edit', [LessonPlanController::class, 'edit'])->name('institution.lesson-plans.edit');
            Route::POST('/{id}', [LessonPlanController::class, 'update'])->name('institution.lesson-plans.update');
            Route::delete('/{id}', [LessonPlanController::class, 'destroy'])->name('institution.lesson-plans.destroy');
            Route::post('/{id}/status', [LessonPlanController::class, 'updateStatus'])->name('institution.lesson-plans.status');
            Route::get('/{id}/download', [LessonPlanController::class, 'download'])->name('institution.lesson-plans.download');

            // API routes for dynamic dropdowns
            Route::get('/teachers/{institutionId}', [LessonPlanController::class, 'getTeachersByInstitution'])->name('institution.lesson-plans.teachers');
            Route::get('/classes/{institutionId}', [LessonPlanController::class, 'getClassesByInstitution'])->name('institution.lesson-plans.classes');
            Route::get('/classes-by-teacher/{institutionId}/{teacherId}', [LessonPlanController::class, 'getClassesByTeacher'])->name('institution.lesson-plans.classes-by-teacher');
            Route::get('/subjects/{institutionId}/{classId}', [LessonPlanController::class, 'getSubjectsByInstitutionClass'])->name('institution.lesson-plans.subjects');
        });
    });
    
    
});
