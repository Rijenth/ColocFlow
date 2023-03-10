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
                $this->mergeWhen($this->resource->roommates()->exists(), fn () => [
                    'roommates' => [
                        'data' => $this->resource->roommates->map(fn ($user) => [
                            'type' => "Users",
                            'id' => $user->getKey(),
                        ])
                    ]
                ]),
            ]
        ];
    }

    public function with($request)
    {
        $included = [];

        if ($request->has('include') && $request->get('include') === 'owner') {
            $included['owner'] = $this->mergeWhen($this->resource->owner()->exists(), fn () => [new UserResource($this->resource->owner)]);
        }

        if ($request->has('include') && $request->get('include') === 'roommates') {
            $included['roommates'] = $this->mergeWhen($this->resource->roommates()->exists(), fn () => [UserResource::collection($this->resource->roommates)]);
        }

        return ['included' => $included];
    }
}
