<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColocationRelationshipRequest;
use App\Models\Charge;
use App\Models\Colocation;
use App\Models\User;

class ColocationRelationshipController extends Controller
{
    public function destroy(ColocationRelationshipRequest $request, Colocation $colocation, string $relationship)
    {
        $validated = $request->validated();

        if ($relationship === 'roommates') {
            $roommatesIds = collect($validated['data'])->pluck('id')->toArray();

            $colocation->roommates()->each(function (User $user) use ($roommatesIds) {
                if (in_array($user->id, $roommatesIds)) {
                    $user->roommate()->dissociate();

                    $chargesIds = $user->charges()->pluck('charges.id')->toArray();

                    $user->charges()->detach();

                    $user->save();

                    if (count($chargesIds) > 0) {
                        $charges = Charge::findMany($chargesIds);

                        $charges->each(function (Charge $charge) {
                            $charge->amount_affected = $charge->users()->sum('amount');

                            $charge->save();
                        });
                    }
                }
            });
        }

        return response()->noContent();
    }
}
