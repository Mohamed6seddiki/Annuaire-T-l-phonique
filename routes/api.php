<?php

use App\Http\Controllers\Api\StandardController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::apiResource('standards', StandardController::class);
});

Route::get('relations', [StandardController::class, 'relations']);
