<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
            return [
            'type' => 'Colocations',
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->toArray(),
            'relationships' => [
                $this->mergeWhen($this->resource->owner()->exists(), fn () => [
                    'owner' => [
                        'data' => [
                            'type' => "Users",
                            'id' => $this->resource->owner->getKey(),
                        ]
                    ]
                ]),
                $this->mergeWhen($this->resource->roomates()->exists(), fn () => [
                    'roomates' => [
                        'data' => $this->resource->roomates->map(fn ($user) => [
                            'type' => "Users",
                            'id' => $user->getKey(),
                        ])
                    ]
                ]),
            ]
        ];
    }
}
