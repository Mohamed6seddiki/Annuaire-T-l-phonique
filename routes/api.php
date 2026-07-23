<?php

use App\Http\Controllers\Api\StandardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('standards', StandardController::class)->middleware('throttle:60,1');
});

Route::middleware('auth:sanctum')->get('relations', [StandardController::class, 'relations']);
