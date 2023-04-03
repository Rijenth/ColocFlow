<?php

namespace App\Listeners;

use App\Events\ColocationCreated;

class CreateChargesForColocation
{
    /**
     * Handle the event.
     */
    public function handle(ColocationCreated $event): void
    {
        $colocation = $event->colocation;

        $data = $event->data['attributes'];

        $storeCharges = [];

        $storeCharges[] = [
            'amount' => $colocation->monthly_rent,
            'amount_affected' => 0,
            'name' => 'Loyer',
        ];

        if (isset($data['charges'])) {
            foreach ($data['charges'] as $charge) {
                $storeCharges[] = [
                    'amount' => $charge['amount'],
                    'amount_affected' => 0,
                    'name' => ucwords($charge['name']),
                ];
            }
        }

        $colocation->charges()->createMany($storeCharges);
    }
}
