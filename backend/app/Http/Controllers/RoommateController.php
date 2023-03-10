<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Colocation;
use Illuminate\Http\Request;

class RoommateController extends Controller
{
    public function index(Colocation $colocation)
    {
        dd($colocation);
        return UserResource::collection($colocation->roommates);
    }
}
