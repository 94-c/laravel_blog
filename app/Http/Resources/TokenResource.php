<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TokenResource extends JsonResource
{

    public function toArray($request)
    {
        $payload = Auth::guard('api')->payload();

        return [
            'data' => [
                'token' => $this->resource,
                'token_type' => 'bearer',
                'payload' => $payload
            ]
        ];
    }
}
