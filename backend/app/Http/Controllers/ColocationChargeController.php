<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColocationChargeResource;
use App\Models\Colocation;

class ColocationChargeController extends Controller
{
    public function index(Colocation $colocation)
    {
        return ColocationChargeResource::collection($colocation->charges);
    }
}
