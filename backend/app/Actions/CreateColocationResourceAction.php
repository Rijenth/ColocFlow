<?php

namespace App\Actions;

use App\Events\ColocationCreated;
use App\Http\Resources\ColocationResource;
use App\Models\User;

class CreateColocationResourceAction
{
    public function __construct(
        private UpdateColocationResourceAction $updateColocationResourceAction,
    ) {
    }

    public function execute(array $data): ColocationResource
    {
        $user = User::find(auth()->user()->id);

        $colocation = $user->owner()->make();

        $colocation = $this->updateColocationResourceAction->execute($colocation, $data);

        ColocationCreated::dispatch($colocation, $data);

        return new ColocationResource($colocation);
    }
}
