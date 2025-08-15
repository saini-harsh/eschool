<?php

use App\Http\Controllers\Admin\Academic\SectionController;
use App\Http\Controllers\Admin\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\NonWorkingStaffController;
use App\Http\Controllers\Admin\StudentController;

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

    Route::prefix('admin')->group(function(){
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('institutions')->group(function(){
            Route::get('/index',[InstitutionController::class,'Index'])->name('admin.institutions.index');
            Route::get('/create',[InstitutionController::class,'Create'])->name('admin.institutions.create');
            Route::post('/store',[InstitutionController::class,'Store'])->name('admin.institutions.store');
            Route::get('/edit/{institution}', [InstitutionController::class, 'Edit'])->name('admin.institutions.edit');
            Route::post('/update/{institution}',[InstitutionController::class,'Update'])->name('admin.institutions.update');
            Route::post('/delete/{institution}', [InstitutionController::class, 'Delete'])->name('admin.institutions.delete');
        });
        Route::prefix('teachers')->group(function(){
            Route::get('/index',[TeacherController::class,'Index'])->name('admin.teachers.index');
            Route::get('/create',[TeacherController::class,'Create'])->name('admin.teachers.create');
            Route::post('/store',[TeacherController::class,'Store'])->name('admin.teachers.store');
            Route::get('/edit/{teacher}', [TeacherController::class, 'Edit'])->name('admin.teachers.edit');
            Route::post('/update/{teacher}',[TeacherController::class,'Update'])->name('admin.teachers.update');
            Route::post('/delete/{teacher}', [TeacherController::class, 'Delete'])->name('admin.teachers.delete');
        });
        Route::prefix('nonworkingstaff')->group(function(){
            Route::get('/index',[NonWorkingStaffController::class,'Index'])->name('admin.nonworkingstaff.index');
            Route::get('/create',[NonWorkingStaffController::class,'Create'])->name('admin.nonworkingstaff.create');
            Route::post('/store',[NonWorkingStaffController::class,'Store'])->name('admin.nonworkingstaff.store');
            Route::get('/edit/{nonworkingstaff}', [NonWorkingStaffController::class, 'Edit'])->name('admin.nonworkingstaff.edit');
            Route::post('/update/{nonworkingstaff}',[NonWorkingStaffController::class,'Update'])->name('admin.nonworkingstaff.update');
            Route::post('/delete/{nonworkingstaff}', [NonWorkingStaffController::class, 'Delete'])->name('admin.nonworkingstaff.delete');
        });
        Route::prefix('students')->group(function(){
            Route::get('/index',[StudentController::class,'Index'])->name('admin.students.index');
            Route::get('/create',[StudentController::class,'Create'])->name('admin.students.create');
            Route::post('/store',[StudentController::class,'Store'])->name('admin.students.store');
            Route::get('/edit/{student}', [StudentController::class, 'Edit'])->name('admin.students.edit');
            Route::post('/update/{student}',[StudentController::class,'Update'])->name('admin.students.update');
            Route::post('/delete/{student}', [StudentController::class, 'Delete'])->name('admin.students.delete');
        });

        Route::prefix('attendance')->group(function(){
            Route::get('/',[AttendanceController::class,'Index'])->name('admin.attendance');
            Route::post('/filter', [AttendanceController::class, 'filter']);
        });

        // SECTIONS
        Route::prefix('sections')->group(function(){
            Route::get('/',[SectionController::class,'Index'])->name('admin.sections.index');
            Route::post('/store',[SectionController::class,'store'])->name('admin.sections.store');
            Route::get('/list',[SectionController::class,'getSections'])->name('admin.sections.list');
            Route::post('/{id}/status',[SectionController::class,'updateStatus'])->name('admin.sections.status');
        });

    });
});



require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
