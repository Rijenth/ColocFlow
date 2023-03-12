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
            "name" => "Rent",
            "amount" => $colocation->monthly_rent,
            "key" => "rent",
        ];

        if (isset($data['charges'])) {
            foreach ($data['charges'] as $charge) {
                $storeCharges[] = [
                    "name" => ucwords(explode("_", $charge['key'])[0]),
                    "amount" => $charge['amount'],
                    "key" => $charge['key'],
                ];
            }
        };

        $colocation->charges()->createMany($storeCharges);
    }
}
