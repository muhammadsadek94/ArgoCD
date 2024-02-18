<?php

namespace App\Domains\Notification\Http\Resources;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\User\Http\Resources\Api\V1\User\UserBasicInfoResource;

class GetMyNotificationResource extends JsonResource
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
            "action_type" => $this->action_type,
            "action_id" => $this->action_id,
            "data" => $this->data,
            "user" => new UserBasicInfoResource($this->notifier),
            "is_read" => !is_null($this->read_at),
            'created_at' => $this->created_at
        ];
    }
}
