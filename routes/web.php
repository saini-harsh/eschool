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
        Route::get('/institutions',[InstitutionController::class,'index'])->name('admin.institutions');
        Route::get('/add-institution',[InstitutionController::class,'AddInstitution'])->name('admin.add-institution');
        Route::post('/store-institution',[InstitutionController::class,'StoreInstitution'])->name('admin.store-institution');
        Route::get('/institution/{institution}', [InstitutionController::class, 'EditInstitution'])->name('admin.edit-institution');
        Route::post('/institution/{institution}',[InstitutionController::class,'UpdateInstitution'])->name('admin.update-institution');

        Route::get('/teachers',[TeacherController::class,'index'])->name('admin.teachers');
        Route::get('/add-teacher',[TeacherController::class,'AddTeacher'])->name('admin.add-teacher');
        Route::post('/store-teacher',[TeacherController::class,'StoreTeacher'])->name('admin.store-teacher');
        Route::get('/teacher/{teacher}', [TeacherController::class, 'EditTeacher'])->name('admin.edit-teacher');
        Route::post('/teacher/{teacher}',[TeacherController::class,'UpdateTeacher'])->name('admin.update-teacher');
    });
});

require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
