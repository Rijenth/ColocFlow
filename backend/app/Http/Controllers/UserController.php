<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['data']['attributes']['username'],
            'password' => Hash::make($data['data']['attributes']['password']),
            'firstname' => $data['data']['attributes']['firstname'],
            'lastname' => $data['data']['attributes']['lastname'],
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return new UserResource($user, $accessToken);

    }
}
