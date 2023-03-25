<?php

namespace App\Actions;

use App\Events\ColocationUpdated;
use App\Models\Colocation;
use App\Models\User;

class UpdateColocationResourceAction
{
    public function execute(Colocation $colocation, array $data): Colocation
    {
        if (isset($data['attributes'])) {
            if (isset($data['attributes']['access_key'])) {
                $data['attributes']['access_key'] = bcrypt($data['attributes']['access_key']);
            }

            if (isset($data['attributes']['max_roommates'])) {
                abort_if($data['attributes']['max_roommates'] < count($colocation->roommates), 422, "Max roommates can't be lower than current roommates");
            }

            $colocation->fill($data['attributes']);
        }

        $colocation->save();

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['roommates'])) {
                $this->updateroommatesRelationship($colocation, $data['relationships']['roommates']['data']);
            }
        }

        ColocationUpdated::dispatch($colocation, $data);

        return $colocation->fresh();
    }

    public function updateroommatesRelationship(Colocation $colocation, array $data): void
    {
        abort_if(count($colocation->roommates) + 1 > $colocation->max_roommates, 409, "Max roommates reached, can't add more roommates");

        if (! empty($data)) {
            $user = ($data['id'] === auth()->user()->id) ? auth()->user() : User::findOrFail($data['id']);

            $colocation->roommates()->save($user);
        }
    }
}
