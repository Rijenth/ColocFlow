<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

// Route qui necessite une authentification
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('authentication.logout');

    Route::get('/auth-user', function (Request $request) {
        $user = User::find($request->user()->id);
        return response()->json([
            'data' => new UserResource($user),
        ]);
    })->name('authentication.user');


});


Route::post('/register', [UserController::class, 'store'])->name('user.register');

Route::post('/login', [AuthenticationController::class, 'login'])->name('login');


