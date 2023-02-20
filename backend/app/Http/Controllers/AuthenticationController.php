<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('username', $credentials['username'])->first();

            $request->session()->regenerate();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
            ]);
        }

        return response()->json([
            "status" => 401,
            "detail" => "Unauthorized",
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $authUser = auth()->user();

        if ($authUser) {
            $user = User::where('username', $authUser->username)->first();
            $user->tokens()->delete();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
