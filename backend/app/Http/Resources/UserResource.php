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
                $this->mergeWhen($this->resource->owner()->exists(), fn () => [
                    'Owner' => [
                        'data' => [
                            'type' => 'Colocations',
                            'id' => $this->resource->owner->getKey(),
                        ]
                    ]
                ]),
                $this->mergeWhen($this->resource->roomate()->exists(), fn () => [
                    'Roomate' => [
                        'data' => [
                            'type' => 'Colocations',
                            'id' => $this->resource->roomate->getKey(),
                        ]
                    ]
                ]),
            ]
        ];
    }


}
