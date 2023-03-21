<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'email' => $data['data']['attributes']['email'],
            'password' => Hash::make($data['data']['attributes']['password']),
            'firstname' => $data['data']['attributes']['firstname'],
            'lastname' => $data['data']['attributes']['lastname'],
        ]);

        return new UserResource($user);
    }
}
