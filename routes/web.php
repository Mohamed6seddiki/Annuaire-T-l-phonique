<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SdirectionController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StandardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/employees', [DashboardController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('employees.store');

Route::put('/employees/{standard}', [DashboardController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('employees.update');

Route::delete('/employees/{standard}', [DashboardController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('employees.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('admin')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->middleware('throttle:10,60')->name('users.store');
    });
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('standards', StandardController::class);
    Route::resource('directions', DirectionController::class);
    Route::resource('sdirections', SdirectionController::class);
    Route::resource('departements', DepartementController::class);
    Route::resource('sites', SiteController::class);
});


 /*
         Route::prefix('referentiel')->name('referentiel.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReferentielController::class, 'index'])->name('index');
        Route::post('{type}', [\App\Http\Controllers\ReferentielController::class, 'store'])->name('store');
        Route::put('{type}/{id}', [\App\Http\Controllers\ReferentielController::class, 'update'])->name('update');
        Route::delete('{type}/{id}', [\App\Http\Controllers\ReferentielController::class, 'destroy'])->name('destroy');
    });

    */



require __DIR__.'/auth.php';