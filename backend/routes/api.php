<?php

use App\Http\Controllers\ChargeController;
use App\Http\Controllers\ColocationChargeController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ColocationRelationshipController;
use App\Http\Controllers\ColocationRoommateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRelationshipController;
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
    Route::apiResource('/charges', ChargeController::class)
        ->only(['update', 'show']);

    Route::apiResource('/colocations', ColocationController::class)
        ->only(['store', 'show', 'update', 'index']);

    Route::apiResource('/colocations/{colocation}/roommates', ColocationRoommateController::class)
        ->only(['index']);

    Route::apiResource('/colocations/{colocation}/relationships', ColocationRelationshipController::class)
        ->only(['destroy']);

    Route::apiResource('/colocations/{colocation}/charges', ColocationChargeController::class)
        ->only(['index', 'store']);

    Route::apiResource('/users', UserController::class)
        ->only(['update']);

    Route::apiResource('/users/{user}/relationships', UserRelationshipController::class)
        ->only(['destroy']);
});
