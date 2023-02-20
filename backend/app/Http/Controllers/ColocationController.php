<?php

namespace App\Http\Controllers;

use App\Actions\CreateColocationResourceAction;
use App\Http\Requests\StoreColocationRequest;

class ColocationController extends Controller
{
    public function __construct(
        private CreateColocationResourceAction $createColocationResourceAction,
    ) {}

    public function store(StoreColocationRequest $request)
    {
        $validated = $request->validated();

        $colocation = $this->createColocationResourceAction->execute($validated['data']);

        return $colocation;
    }
}
