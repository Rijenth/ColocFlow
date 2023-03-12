<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Resources\ColocationResource;
use App\Http\Resources\UserResource;
use App\Models\Colocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the backend of the project',
    ]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('authentication.logout');

    Route::get('/auth-user', function (Request $request) {
        $user = User::find($request->user()->id);
        return response()->json([
            'data' => new UserResource($user),
        ]);
    })->name('authentication.user');

    Route::get('/get-colocation', function(Request $request) {
        $colocation = Colocation::whereHas('owner', function($query) use ($request) {
            $query->where('username', $request->username);
        })->firstOrFail();

        if (Hash::check($request->access_key, $colocation->access_key) === false) {
            return response()->json([
                'message' => 'Access key is incorrect',
            ], 401);
        }

        return new ColocationResource($colocation);
    })->name('colocation.get');
});

Route::post('/register', [UserController::class, 'store'])->name('user.register');

Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
