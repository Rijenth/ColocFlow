<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Colocation;

class RoommateController extends Controller
{
    public function index(Colocation $colocation)
    {
        return UserResource::collection($colocation->roommates);
    }
}
