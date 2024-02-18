<?php

namespace App\Domains\OpenApi\Http\Resources\Api\V2\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class SubscriptionResource extends JsonResource
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
            "subscription_id" => $this->subscription_id,
            "expired_at" => $this->expired_at,
            "package_id" => $this->package_id,
            'status' => $this->status
        ];
    }
}
