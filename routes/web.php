<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/employees', [DashboardController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('employees.store');

Route::put('/employees/{employee}', [DashboardController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('employees.update');

Route::delete('/employees/{employee}', [DashboardController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('employees.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
