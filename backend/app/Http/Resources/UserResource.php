<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->toArray(),
            'relationships' => [
                $this->mergeWhen($this->resource->owner()->exists(), fn () => [
                    'owner' => [
                        'data' => [
                            'type' => 'colocations',
                            'id' => $this->resource->owner->getKey(),
                        ]
                    ]
                ]),
                $this->mergeWhen($this->resource->roommate()->exists(), fn () => [
                    'roommate' => [
                        'data' => [
                            'type' => 'colocations',
                            'id' => $this->resource->roommate->getKey(),
                        ]
                    ]
                ]),
            ]
        ];
    }


}
