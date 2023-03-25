<?php

namespace App\Http\Controllers;

use App\Actions\CreateColocationResourceAction;
use App\Actions\UpdateColocationResourceAction;
use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;
use App\Http\Resources\ColocationResource;
use App\Models\Colocation;

class ColocationController extends Controller
{
    public function __construct(
        private CreateColocationResourceAction $createColocationResourceAction,
        private UpdateColocationResourceAction $updateColocationResourceAction
    ) {
    }

    public function index()
    {
        $colocations = Colocation::all();

        return ColocationResource::collection($colocations);
    }

    public function store(StoreColocationRequest $request)
    {
        $validated = $request->validated();

        $colocation = $this->createColocationResourceAction->execute($validated['data']);

        return $colocation;
    }

    public function show(Colocation $colocation)
    {
        return new ColocationResource($colocation);
    }

    public function update(UpdateColocationRequest $request, Colocation $colocation)
    {
        $validated = $request->validated();

        $colocation = $this->updateColocationResourceAction->execute($colocation, $validated['data']);

        return new ColocationResource($colocation);
    }

    public function destroy(Colocation $colocation)
    {
        $colocation->delete();

        return response()->noContent();
    }
}
