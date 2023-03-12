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

        $colocation->charges()->createMany([
            [
                "name" => "Rent",
                "amount" => $colocation->monthly_rent,
                "key" => "rent",
            ],
            [
                "name" => "Electricity",
                "key" => "electricity_charge",
            ],
            [
                "name" => "Heating",
                "key" => "heating_charge",
            ],
            [
                "name" => "Internet",
                "key" => "internet_charge",
            ],
            [
                "name" => "Gas",
                "key" => "gas_charge",
            ],
            [
                "name" => "Others",
                "key" => "others_charge",
            ],
            [
                "name" => "Water",
                "key" => "water_charge",
            ],
        ]);
    }
}
