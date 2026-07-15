<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $mockEmployees = [
        ['nom' => 'Dupont', 'prenom' => 'Jean', 'telephone' => '00 01', 'service' => 'Technique', 'departement' => 'IT', 'service_color' => '#1d4ed8', 'service_bg' => '#eff6ff', 'service_border' => '#bfdbfe'],
        ['nom' => 'Leroy', 'prenom' => 'Alice', 'telephone' => '00 02', 'service' => 'Ventes', 'departement' => 'Marketing', 'service_color' => '#15803d', 'service_bg' => '#f0fdf4', 'service_border' => '#bbf7d0'],
        ['nom' => 'Martin', 'prenom' => 'Sophie', 'telephone' => '00 03', 'service' => 'Design', 'departement' => 'Produit', 'service_color' => '#7c3aed', 'service_bg' => '#f5f3ff', 'service_border' => '#ddd6fe'],
        ['nom' => 'Bernard', 'prenom' => 'Luc', 'telephone' => '00 04', 'service' => 'Technique', 'departement' => 'Support', 'service_color' => '#1d4ed8', 'service_bg' => '#eff6ff', 'service_border' => '#bfdbfe'],
        ['nom' => 'Dubois', 'prenom' => 'Marc', 'telephone' => '00 05', 'service' => 'Ventes', 'departement' => 'B2B', 'service_color' => '#15803d', 'service_bg' => '#f0fdf4', 'service_border' => '#bbf7d0'],
    ];

    $perPage = 10;
    $page = request()->get('page', 1);
    $total = 5;
    $items = $page === 1 ? $mockEmployees : array_slice($mockEmployees, 0, min(10, max(0, $total - ($page - 1) * $perPage)));

    $employees = new LengthAwarePaginator(
        $items,
        $total,
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('dashboard', compact('employees'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
