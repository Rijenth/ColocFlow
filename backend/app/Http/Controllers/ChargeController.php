<?php

namespace App\Http\Controllers;

use App\Actions\UpdateChargeResourceAction;
use App\Http\Requests\UpdateChargeRequest;
use App\Http\Resources\ChargeResource;
use App\Models\Charge;

class ChargeController extends Controller
{
    public function __construct(
        private UpdateChargeResourceAction $updateChargeResourceAction
    ) {
    }

    public function show(Charge $charge)
    {
        return new ChargeResource($charge);
    }

    public function update(UpdateChargeRequest $request, Charge $charge)
    {
        $validated = $request->validated();

        $charge = $this->updateChargeResourceAction->execute($charge, $validated['data']);

        return new ChargeResource($charge);
    }
}
