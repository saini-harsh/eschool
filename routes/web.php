<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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
    });
});

require_once __DIR__ . '/institution.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
