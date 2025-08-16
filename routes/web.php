<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\NonWorkingStaffController;
use App\Http\Controllers\Admin\Academic\SectionController;
use App\Http\Controllers\Admin\Academic\SubjectController;
use App\Http\Controllers\Admin\Academic\SchoolClassController;
use App\Http\Controllers\Admin\Academic\AssignClassTeacherController;

Route::get('/', function () {
    return view('frontend.index');
});

// Auth routes

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected page example
Route::middleware('admin')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('institutions')->group(function () {
            Route::get('/index', [InstitutionController::class, 'Index'])->name('admin.institutions.index');
            Route::get('/create', [InstitutionController::class, 'Create'])->name('admin.institutions.create');
            Route::post('/store', [InstitutionController::class, 'Store'])->name('admin.institutions.store');
            Route::get('/edit/{institution}', [InstitutionController::class, 'Edit'])->name('admin.institutions.edit');
            Route::post('/update/{institution}', [InstitutionController::class, 'Update'])->name('admin.institutions.update');
            Route::post('/delete/{institution}', [InstitutionController::class, 'Delete'])->name('admin.institutions.delete');
        });
        Route::prefix('teachers')->group(function () {
            Route::get('/index', [TeacherController::class, 'Index'])->name('admin.teachers.index');
            Route::get('/create', [TeacherController::class, 'Create'])->name('admin.teachers.create');
            Route::post('/store', [TeacherController::class, 'Store'])->name('admin.teachers.store');
            Route::get('/edit/{teacher}', [TeacherController::class, 'Edit'])->name('admin.teachers.edit');
            Route::post('/update/{teacher}', [TeacherController::class, 'Update'])->name('admin.teachers.update');
            Route::post('/delete/{teacher}', [TeacherController::class, 'Delete'])->name('admin.teachers.delete');
        });
        Route::prefix('nonworkingstaff')->group(function () {
            Route::get('/index', [NonWorkingStaffController::class, 'Index'])->name('admin.nonworkingstaff.index');
            Route::get('/create', [NonWorkingStaffController::class, 'Create'])->name('admin.nonworkingstaff.create');
            Route::post('/store', [NonWorkingStaffController::class, 'Store'])->name('admin.nonworkingstaff.store');
            Route::get('/edit/{nonworkingstaff}', [NonWorkingStaffController::class, 'Edit'])->name('admin.nonworkingstaff.edit');
            Route::post('/update/{nonworkingstaff}', [NonWorkingStaffController::class, 'Update'])->name('admin.nonworkingstaff.update');
            Route::post('/delete/{nonworkingstaff}', [NonWorkingStaffController::class, 'Delete'])->name('admin.nonworkingstaff.delete');
        });
        Route::prefix('students')->group(function () {
            Route::get('/index', [StudentController::class, 'Index'])->name('admin.students.index');
            Route::get('/create', [StudentController::class, 'Create'])->name('admin.students.create');
            Route::post('/store', [StudentController::class, 'Store'])->name('admin.students.store');
            Route::get('/edit/{student}', [StudentController::class, 'Edit'])->name('admin.students.edit');
            Route::post('/update/{student}', [StudentController::class, 'Update'])->name('admin.students.update');
            Route::post('/delete/{student}', [StudentController::class, 'Delete'])->name('admin.students.delete');
        });

        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'Index'])->name('admin.attendance');
            Route::post('/filter', [AttendanceController::class, 'filter']);
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

        Route::prefix('calender')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'index'])->name('admin.academic.calendar.index');
            Route::get('/events', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'getEvents'])->name('admin.academic.calendar.events');
            Route::post('/events', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'store'])->name('admin.academic.calendar.store');
            Route::put('/events/{id}', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'update'])->name('admin.academic.calendar.update');
            Route::delete('/events/{id}', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'destroy'])->name('admin.academic.calendar.destroy');
            Route::get('/events/{id}', [App\Http\Controllers\Admin\Academic\CalendarController::class, 'getEvent'])->name('admin.academic.calendar.show');
        });

        // Event Management Routes
        Route::prefix('events')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\Academic\EventController::class, 'index'])->name('admin.events.index');
            Route::post('/', [App\Http\Controllers\Admin\Academic\EventController::class, 'store'])->name('admin.events.store');
            Route::get('/list', [App\Http\Controllers\Admin\Academic\EventController::class, 'getEvents'])->name('admin.events.list');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\Academic\EventController::class, 'edit'])->name('admin.events.edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\Academic\EventController::class, 'update'])->name('admin.events.update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\Academic\EventController::class, 'destroy'])->name('admin.events.destroy');
            Route::post('/{id}/status', [App\Http\Controllers\Admin\Academic\EventController::class, 'updateStatus'])->name('admin.events.status');
        });
    });
});



require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
