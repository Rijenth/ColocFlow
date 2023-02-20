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
                $this->mergeWhen($this->resource->user->exists(), [
                    'user' => [
                        'data' => [
                            'type' => "User",
                            'id' => $this->resource->user->getKey(),
                        ]
                    ]
                ])
            ]
        ];
    }
}
