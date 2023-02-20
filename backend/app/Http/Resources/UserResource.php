<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'Users',
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->toArray(),
            'relationships' => [
                $this->mergeWhen($this->resource->colocations->isNotEmpty(), [
                    'colocations' => [
                        'data' => $this->resource->colocations->map(fn ($colocation) => [
                            'type' => "Colocation",
                            'id' => $colocation->getKey(),
                        ])
                    ]
                ])
            ]
        ];
    }


}
