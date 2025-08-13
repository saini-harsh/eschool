<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionController;

Route::middleware('institution')->group(function () {
    Route::get('institution/dashboard', [InstitutionController::class, 'dashboard'])->name('institution.dashboard');
    Route::post('logout', [InstitutionController::class, 'logout'])->name('institution.logout');
});
