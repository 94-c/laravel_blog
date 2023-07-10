<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'error' => [
                    'message' => $this->resource[0],
                    'code' => $this->resource[1],
                ]
            ]
        ];
    }
}
