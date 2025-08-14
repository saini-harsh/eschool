<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\InstitutionController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected page example
Route::middleware('admin')->group(function () {
    // Place admin-protected routes here, e.g.:
    Route::prefix('admin')->group(function(){
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/institutions',[InstitutionController::class,'Index'])->name('admin.institutions.index');
        Route::get('/add-institution',[InstitutionController::class,'Create'])->name('admin.institutions.create');
        Route::post('/store-institution',[InstitutionController::class,'Store'])->name('admin.institutions.store');
        Route::get('/institution/{institution}', [InstitutionController::class, 'Edit'])->name('admin.institutions.edit');
        Route::post('/institution/{institution}',[InstitutionController::class,'Update'])->name('admin.institutions.update');

        Route::get('/teachers',[TeacherController::class,'Index'])->name('admin.teachers.index');
        Route::get('/add-teacher',[TeacherController::class,'Create'])->name('admin.teachers.create');
        Route::post('/store-teacher',[TeacherController::class,'Store'])->name('admin.teachers.store');
        Route::get('/teacher/{teacher}', [TeacherController::class, 'Edit'])->name('admin.teachers.edit');
        Route::post('/teacher/{teacher}',[TeacherController::class,'Update'])->name('admin.teachers.update');
    });
});

require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
