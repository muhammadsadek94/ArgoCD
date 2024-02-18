<?php

namespace App\Domains\User\Http\Resources\Api\V2\User;

use INTCore\OneARTFoundation\Http\JsonResource;

class ReviewUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "first_name" => $this->user?->first_name,
            "last_name" => $this->user?->last_name,
            'rate' => $this->rate,
            "recommendation" => $this->recommendation
        ];
    }
}
