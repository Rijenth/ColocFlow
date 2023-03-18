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
            'name' => 'Rent',
            'key' => 'rent',
        ];

        if (isset($data['charges'])) {
            foreach ($data['charges'] as $charge) {
                $storeCharges[] = [
                    'amount' => $charge['amount'],
                    'amount_affected' => 0,
                    'name' => ucwords(explode('_', $charge['key'])[0]),
                    'key' => $charge['key'],
                ];
            }
        }

        $colocation->charges()->createMany($storeCharges);
    }
}
