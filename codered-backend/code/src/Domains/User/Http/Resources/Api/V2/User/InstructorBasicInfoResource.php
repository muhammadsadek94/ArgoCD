<?php

namespace App\Domains\User\Http\Resources\Api\V2\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

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
            "id"          => $this->id,
            "full_name"  => $this->first_name,
            "image"       => new FileResource($this->image),
            'description' => $this->instructor_profile ? $this->instructor_profile->profile_summary : null,
            'facebook_url' => $this->instructor_profile ?$this->instructor_profile->facebook_url : null,
            'instagram_url' => $this->instructor_profile ?$this->instructor_profile->instagram_url : null,
            'twitter_url' => $this->instructor_profile ?$this->instructor_profile->twitter_url : null,
            'job'  => $this->job
        ];
    }
}
