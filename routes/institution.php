<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\Institution\Setting\SettingsController;
use App\Http\Controllers\Institution\Administration\TeacherController;
use App\Http\Controllers\Institution\Administration\StudentController;
use App\Http\Controllers\Institution\Administration\NonWorkingStaffController;

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
    });
    
    
});
