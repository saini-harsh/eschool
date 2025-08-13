<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::middleware('student')->group(function () {
    // Dashboard
    Route::get('student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');


    // Logout
    Route::post('logout', [StudentController::class, 'logout'])->name('student.logout');
});
