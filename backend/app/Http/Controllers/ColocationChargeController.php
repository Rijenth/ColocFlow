<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChargeResource;
use App\Models\Colocation;

class ColocationChargeController extends Controller
{
    public function index(Colocation $colocation)
    {
        return ChargeResource::collection($colocation->charges);
    }
}
