<?php

namespace App\Http\Controllers;

use App\Actions\UpdateChargeResourceAction;
use App\Http\Requests\StoreChargeRequest;
use App\Http\Resources\ChargeResource;
use App\Models\Colocation;

class ColocationChargeController extends Controller
{
    public function __construct(
        public UpdateChargeResourceAction $updateChargeResourceAction,
    ) {
    }

    public function index(Colocation $colocation)
    {
        return ChargeResource::collection($colocation->charges);
    }

    public function store(StoreChargeRequest $request, Colocation $colocation)
    {
        $validated = $request->validated();

        $charge = $colocation->charges()->make();

        $charge = $this->updateChargeResourceAction->execute($charge, $validated['data']);

        return new ChargeResource($charge);
    }
}
