<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Colocation;

class ColocationRoommateController extends Controller
{
    public function index(Colocation $colocation)
    {
        return UserResource::collection($colocation->roommates);
    }
}
