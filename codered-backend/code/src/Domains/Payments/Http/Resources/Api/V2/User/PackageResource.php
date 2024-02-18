<?php

namespace App\Domains\Payments\Http\Resources\Api\V2\User;

use App\Domains\Payments\Enum\AccessType;
use INTCore\OneARTFoundation\Http\JsonResource;

class PackageResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->package->name,
            'amount'        => $this->package->amount,
            'type'          => $this->package->type,
            'description'   => $this->package->description,
            'url'           => $this->package->url,
            'expiration'    => $this->courseEnrollments?->sortByDesc('created_at')->first()?->expired_at ?? $this->expired_at,
            'learn_path'    => $this->package->learn_path_id,
            'course_type'   => $this->package->course_type,
            'course_id'     => $this->courseEnrollments?->sortByDesc('created_at')->first()?->course_id,
            'is_pro'        => $this->package->access_type == AccessType::PRO,
        ];
    }
}
