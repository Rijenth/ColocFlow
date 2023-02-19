<?php

use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
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
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->noContent();
    })->name('authentication.logout');

    Route::get('/auth-user', function (Request $request) {
        return response()->json([
            'message' => 'You are logged in',
            'user' => $request->user(),
        ]);
    })->name('authentication.user');


});


Route::post('/register', [UserController::class, 'store'])->name('user.register');

Route::post('/login', function(Request $request) {
    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return response()->noContent();
    }

    return response()->json([
        "status" => 401,
        "detail" => "Unauthorized",
        'message' => 'The provided credentials do not match our records.',
    ], 401);

})->name('login');


