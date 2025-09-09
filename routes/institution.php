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
use App\Http\Controllers\Institution\Academic\CalendarController;
use App\Http\Controllers\Institution\Academic\EventController;

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

         // Classes
         Route::prefix('classes')->group(function () {
            Route::get('/',[SchoolClassController::class,'index'])->name('admin.classes.index');
            Route::post('/store',[SchoolClassController::class,'store'])->name('admin.classes.store');
            Route::get('/list',[SchoolClassController::class,'getSchoolClasses'])->name('admin.classes.list');
            Route::get('/edit/{id}', [SchoolClassController::class, 'edit'])->name('admin.classes.edit');
            Route::post('/update/{id}', [SchoolClassController::class, 'update'])->name('admin.classes.update');
            Route::post('/delete/{id}', [SchoolClassController::class, 'delete'])->name('admin.classes.delete');
            Route::post('/{id}/status',[SchoolClassController::class,'updateStatus'])->name('admin.classes.status');
        });

        // SECTIONS
        Route::prefix('sections')->group(function(){
            Route::get('/',[SectionController::class,'index'])->name('admin.sections.index');
            Route::post('/store',[SectionController::class,'store'])->name('admin.sections.store');
            Route::get('/list',[SectionController::class,'getSections'])->name('admin.sections.list');
            Route::get('/edit/{id}', [SectionController::class, 'edit'])->name('admin.sections.edit');
            Route::post('/update/{id}', [SectionController::class, 'update'])->name('admin.sections.update');
            Route::post('/delete/{id}', [SectionController::class, 'delete'])->name('admin.sections.delete');
            Route::post('/{id}/status',[SectionController::class,'updateStatus'])->name('admin.sections.status');
        });

        // SUBJECTS
        Route::prefix('subjects')->group(function () {
            Route::get('/', [SubjectController::class, 'Index'])->name('admin.subjects.index');
            Route::post('/store', [SubjectController::class, 'store'])->name('admin.subjects.store');
            Route::get('/list', [SubjectController::class, 'getSubjects'])->name('admin.subjects.list');
            Route::post('/{id}/status', [SubjectController::class, 'updateStatus'])->name('admin.subjects.status');
            Route::get('/edit/{id}', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
            Route::post('/update/{id}', [SubjectController::class, 'update'])->name('admin.subjects.update');
            Route::post('/delete/{id}', [SubjectController::class, 'delete'])->name('admin.subjects.delete');
        });

        // ASSIGN CLASS TEACHER
        Route::prefix('academic')->group(function () {
            Route::get('/assign-teacher', [AssignClassTeacherController::class, 'index'])->name('admin.academic.assign-teacher.index');
            Route::get('/classes-by-institution/{id}', [AssignClassTeacherController::class, 'getClassesByInstitution']);
            Route::get('/teachers-by-institution/{id}', [AssignClassTeacherController::class, 'getTeachersByInstitution']);
            Route::get('/sections-by-class/{id}', [AssignClassTeacherController::class, 'getSectionsByClass']);
            Route::post('/assign-class-teacher', [AssignClassTeacherController::class, 'store'])->name('assign-class-teacher.store');
        });

        // Academic Calendar Routes
        Route::prefix('calendar')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('admin.academic.calendar.index');
            Route::get('/events', [CalendarController::class, 'getEvents'])->name('admin.academic.calendar.events');
            Route::get('/check-database', [CalendarController::class, 'checkDatabase'])->name('admin.academic.calendar.check-database');
            Route::post('/events', [CalendarController::class, 'store'])->name('admin.academic.calendar.store');
            Route::put('/events/{id}', [CalendarController::class, 'update'])->name('admin.academic.calendar.update');
            Route::delete('/events/{id}', [CalendarController::class, 'destroy'])->name('admin.academic.calendar.destroy');
            Route::get('/events/{id}', [CalendarController::class, 'getEvent'])->name('admin.academic.calendar.show');
        });

        // Event Management Routes
        Route::prefix('events')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('admin.events.index');
            Route::post('/', [EventController::class, 'store'])->name('admin.events.store');
            Route::get('/list', [EventController::class, 'getEvents'])->name('admin.events.list');
            Route::get('/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
            Route::put('/{id}', [EventController::class, 'update'])->name('admin.events.update');
            Route::post('/delete/{id}', [EventController::class, 'delete'])->name('admin.events.delete');
            Route::post('/{id}/status', [EventController::class, 'updateStatus'])->name('admin.events.status');
        });
    });
    
    
});
