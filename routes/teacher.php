<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;

Route::middleware('teacher')->group(function () {
    Route::prefix('teacher')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

        Route::get('/attendance',[TeacherController::class, 'attendance'])->name('teacher.attendance');
    });
    Route::post('logout', [TeacherController::class, 'logout'])->name('teacher.logout');

});
