<?php

namespace App\Actions;

use App\Models\Colocation;

class UpdateColocationResourceAction
{
    public function execute(Colocation $colocation, array $data): Colocation
    {
        if (isset($data['attributes'])) {
            $colocation->fill($data['attributes']);
        }

        $colocation->save();

        return $colocation->fresh();
    }
}
