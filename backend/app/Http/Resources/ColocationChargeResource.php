<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColocationChargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'colocation-charges',
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->toArray(),
            'relationships' => [
                $this->mergeWhen($this->resource->colocation()->exists(), fn () => [
                    'colocation' => [
                        'data' => [
                            'type' => 'colocations',
                            'id' => $this->resource->colocation->getKey(),
                        ],
                    ],
                ]),
            ],
        ];
    }
}
