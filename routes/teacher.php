<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;

Route::middleware('teacher')->group(function () {
    Route::get('teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::post('logout', [TeacherController::class, 'logout'])->name('teacher.logout');
});
