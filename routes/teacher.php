<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Teacher\Setting\SettingsController;
use App\Http\Controllers\Teacher\Administration\StudentController;


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
            Route::get('/create', [StudentController::class, 'Create'])->name('teacher.students.create');
            Route::post('/store', [StudentController::class, 'Store'])->name('teacher.students.store');
            Route::get('/show/{student}', [StudentController::class, 'Show'])->name('teacher.students.show');
            Route::get('/edit/{student}', [StudentController::class, 'Edit'])->name('teacher.students.edit');
            Route::post('/update/{student}', [StudentController::class, 'Update'])->name('teacher.students.update');
            Route::post('/delete/{student}', [StudentController::class, 'Delete'])->name('teacher.students.delete');
            Route::get('/classes/{institutionId}', [StudentController::class, 'getClassesByInstitution'])->name('teacher.students.classes');
            Route::get('/teachers/{institutionId}', [StudentController::class, 'getTeachersByInstitution'])->name('teacher.students.teachers');
            Route::get('/sections/{classId}', [StudentController::class, 'getSectionsByClass'])->name('teacher.students.sections');
            Route::post('/status/{id}', [StudentController::class, 'updateStatus'])->name('teacher.students.status');
        });
    });
    // Route::post('logout', [TeacherController::class, 'logout'])->name('teacher.logout');

});
