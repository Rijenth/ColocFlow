<?php

namespace App\Listeners;

use App\Events\ColocationUpdated;

class UpdateChargeRentAmount
{
    /**
     * Handle the event.
     */
    public function handle(ColocationUpdated $event): void
    {
        if (isset($event->data['attributes']['monthly_rent']) && $event->colocation->charges()->exists()) {
            $colocation = $event->colocation;

            $rentCharge = $colocation->charges()->firstWhere('name', 'Loyer');

            $updatedMonthlyRent = (float) $event->data['attributes']['monthly_rent'];

            if ($rentCharge->amount !== $updatedMonthlyRent) {
                $rentCharge->users()->detach();

                $rentCharge->update(
                    [
                        'amount' => $updatedMonthlyRent,
                        'amount_affected' => 0,
                    ]
                );
            }
        }
    }
}
