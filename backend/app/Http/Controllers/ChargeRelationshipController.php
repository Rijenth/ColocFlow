<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRelationshipRequest;
use App\Models\User;

class ChargeRelationshipController extends Controller
{
    public function destroy(UserRelationshipRequest $request, User $user, string $relationship)
    {
        $validated = $request->validated();

        if ($relationship === 'charges') {
            $chargesIds = collect($validated['data'])->pluck('id')->toArray();

            $charges = $user->charges()->whereIn('charge_id', $chargesIds)->get();

            $user->charges()->detach($chargesIds);

            $charges->each(function ($charge) {
                $charge->amount_affected = $charge->users()->sum('amount');

                $charge->save();
            });
        }

        return response()->noContent();
    }
}
