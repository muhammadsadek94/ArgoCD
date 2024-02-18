<?php

namespace App\Domains\OpenApi\Http\Resources\Api\V2\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class UserInfoResource extends JsonResource
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
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "image" => new FileResource($this->image),
            "access_token"      => $this->when(!is_null($this->access_token), function() {
                return $this->access_token->accessToken;
            }),
            'subscription'      => [
                'active' => !!$this->active_subscription(),
                'subscription' => $this->active_subscription
            ]
        ];
    }
}
