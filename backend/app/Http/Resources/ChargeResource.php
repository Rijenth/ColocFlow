<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'charges',
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
                $this->mergeWhen($this->resource->users()->exists(), fn () => [
                    'users' => [
                        'data' => $this->resource->users->map(fn ($user) => [
                            'type' => 'users',
                            'id' => $user->getKey(),
                            'attributes' => [
                                'amount' => (float) $user->pivot->amount,
                            ],
                        ]),
                    ],
                ]),
            ],
        ];
    }

    public function with($request)
    {
        return [
            //
        ];
    }
}
