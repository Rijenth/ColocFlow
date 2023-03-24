<?php

namespace App\Actions;

use App\Events\ChargeUpdated;
use App\Models\Charge;
use App\Models\User;

class UpdateChargeResourceAction
{
    public function execute(Charge $charge, array $data): Charge
    {
        if (isset($data['attributes'])) {
            if (isset($data['attributes']['amount']) && ($data['attributes']['amount'] !== $charge->amount)) {
                $charge->users()->detach();
            }

            $charge->fill($data['attributes']);
        }

        $charge->save();

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['users'])) {
                $this->updateChargeUserRelationship($charge, $data['relationships']['users']['data']);
            }
        }

        ChargeUpdated::dispatch($charge, $data);

        return $charge->fresh();
    }

    public function updateChargeUserRelationship(Charge $charge, array $data): void
    {
        if (! empty($data)) {
            $user = User::findOrFail($data['id']);

            $previouslyAffectedAmount = ($charge->users()->find($user->id)->pivot?->amount) ?? 0;

            $newlyAffectedAmount = ($charge->users()->sum('amount') + $data['attributes']['amount']) - $previouslyAffectedAmount;

            abort_if(
                $newlyAffectedAmount > $charge->amount,
                422,
                'The amount of the charge cannot be exceeded.'
            );

            $charge->users()->syncWithoutDetaching(
                [
                    $user->id => [
                        'amount' => $data['attributes']['amount'],
                    ],
                ]
            );
        }
    }
}
