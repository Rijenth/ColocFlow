<?php

use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
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
    return view('welcome');
});

// Route qui necessite une authentification
Route::middleware(['auth:sanctum'])->group(function () {
    //
});

Route::post('/register', [UserController::class, 'store'])->name('user.register');

Route::post('/login', function (StoreUserRequest $request) {
    dd("rijenth");
})->name('user.login');
