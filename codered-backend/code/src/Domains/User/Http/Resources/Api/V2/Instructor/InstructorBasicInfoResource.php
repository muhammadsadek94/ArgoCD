<?php

namespace App\Domains\User\Http\Resources\Api\V2\Instructor;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class InstructorBasicInfoResource extends JsonResource
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
            "id"                    => $this->id,
            "full_name"             => $this->full_name,
            "current_employer"      => $this->instructor_profile->current_employer,
            "job"                   => $this->instructor_profile->job,
            "image"                 => new FileResource($this->image),
            "summary"               => $this->instructor_profile ? $this->instructor_profile->profile_summary : null,
        ];
    }

}
