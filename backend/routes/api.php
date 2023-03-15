<?php

use App\Http\Controllers\ColocationChargeController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\RoommateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/colocations', ColocationController::class)
        ->only(['store', 'show', 'update', 'index']);

    Route::apiResource('/colocations/{colocation}/roommates', RoommateController::class)
        ->only(['index']);

    Route::apiResource('/colocations/{colocation}/charges', ColocationChargeController::class)
        ->only(['index']);
});
