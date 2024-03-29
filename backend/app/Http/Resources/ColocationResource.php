<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'colocations',
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->toArray(),
            'relationships' => [
                $this->mergeWhen($this->resource->charges()->exists(), fn () => [
                    'charges' => [
                        'data' => $this->resource->charges->map(fn ($charge) => [
                            'type' => 'charges',
                            'id' => $charge->getKey(),
                        ]),
                    ],
                ]),
                $this->mergeWhen($this->resource->owner()->exists(), fn () => [
                    'owner' => [
                        'data' => [
                            'type' => 'users',
                            'id' => $this->resource->owner->getKey(),
                        ],
                    ],
                ]),
                $this->mergeWhen($this->resource->roommates()->exists(), fn () => [
                    'roommates' => [
                        'data' => $this->resource->roommates->map(fn ($user) => [
                            'type' => 'users',
                            'id' => $user->getKey(),
                        ]),
                    ],
                ]),
            ],
        ];
    }

    public function with($request)
    {
        $included = [];

        if (strpos($request->get('include'), ',') !== false) {
            $includes = explode(',', $request->get('include'));
        } else {
            $includes = [$request->get('include')];
        }

        foreach ($includes as $include) {
            if ($include === 'charges') {
                $included['charges'] = $this->mergeWhen($this->resource->charges()->exists(), fn () => ChargeResource::collection($this->resource->charges));
            }

            if ($include === 'owner') {
                $included['owner'] = $this->mergeWhen($this->resource->owner()->exists(), fn () => new UserResource($this->resource->owner));
            }

            if ($include === 'roommates') {
                $included['roommates'] = $this->mergeWhen($this->resource->roommates()->exists(), fn () => UserResource::collection($this->resource->roommates));
            }
        }

        return ['included' => $included];
    }
}
