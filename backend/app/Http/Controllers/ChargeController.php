<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function store(Colocation $colocation) {
        dd("Ok", $colocation);
    }
}
