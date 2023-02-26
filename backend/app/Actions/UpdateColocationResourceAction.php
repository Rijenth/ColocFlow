<?php

namespace App\Actions;

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

            $colocation->fill($data['attributes']);
        }

        $colocation->save();

        if (isset($data['relationships'])) {
/*             if (isset($data['relationships']['owner'])) {
                $this->updateOwnerRelationship($colocation, $data['relationships']);
            } */

            if (isset($data['relationships']['roomates'])) {
                $this->updateRoomatesRelationship($colocation, $data['relationships']['roomates']['data']);
            }
        }

        return $colocation->fresh();
    }

/*     public function updateOwnerRelationship(Colocation $colocation, array $data): void
    {
        $user = User::firstOrFail($data['owner']['data']['id']);

        $colocation->owner()->associate($user);
    } */

    public function updateRoomatesRelationship(Colocation $colocation, array $data): void
    {
        if (! empty($data)) {
            $user = User::findOrFail($data['id']);

            $colocation->roomates()->save($user);
        }
    }
}
