<?php

namespace App\Listeners;

use App\Events\ChargeUpdated;

class UpdateChargeAmountAffectedAttribute
{
    /**
     * Handle the event.
     */
    public function handle(ChargeUpdated $event): void
    {
        $charge = $event->charge;

        $charge->amount_affected = $charge->users()->sum('amount');

        $charge->save();
    }
}
