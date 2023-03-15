<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JsonApiResource extends JsonResource
{
    public function getAttributes()
    {
        return $this->resource->getAttributes();
    }

    public function attributesToArray()
    {
        return [
            'type' => $this->resource->getTable(),
            'id' => $this->resource->getKey(),
            'attributes' => $this->resource->attributesToArray(),
        ];
    }

    public function with($request)
    {
        return [];
    }
}
