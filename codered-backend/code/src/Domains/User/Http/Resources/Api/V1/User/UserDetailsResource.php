<?php

namespace App\Domains\User\Http\Resources\Api\V1\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                => $this->id,
            "first_name"        => $this->first_name,
            "last_name"         => $this->last_name,
            "phone"             => $this->phone,
            "email"             => $this->email,
            "birth_date"        => $this->birth_date,
            "social_type"       => $this->social_type,
            "social_id"         => $this->social_id,
            "type"              => $this->type,
            "has_password"      => $this->has_password,
            "activation_status" => $this->activation_status,
            "access_token"      => $this->when(!is_null($this->access_token), function() {
                return $this->access_token->accessToken;
            }),
            "image"             => new FileResource($this->image),
            'gender'            => $this->gender,
            'country'           => new CountryResource($this->country),
            'city'              => new CityResource($this->city),
            'daily_target'      => $this->daily_target,
            'isPro'     => !!$this->hasActiveSubscription(),

            'subscription'      => [
                'active' => !!$this->active_subscription(),
                'subscription' => $this->active_subscription
            ]
        ];
    }
}
