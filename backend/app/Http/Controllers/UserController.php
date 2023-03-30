<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['data']['attributes']['email'],
            'password' => Hash::make($validated['data']['attributes']['password']),
            'firstname' => $validated['data']['attributes']['firstname'],
            'lastname' => $validated['data']['attributes']['lastname'],
        ]);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (isset($validated['data']['attributes']['password']) && $validated['data']['attributes']['old_password']) {
            abort_if(
                ! Hash::check($validated['data']['attributes']['old_password'], $user->password),
                422,
                'The old password is incorrect.'
            );

            $validated['data']['attributes']['password'] = Hash::make($validated['data']['attributes']['password']);
        }

        $user->fill($validated['data']['attributes']);

        $user->save();

        return new UserResource($user);
    }

    public function destroy(Request $request, User $user)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        return response()->noContent();
    }
}
