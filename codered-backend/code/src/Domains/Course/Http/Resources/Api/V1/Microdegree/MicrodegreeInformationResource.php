<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Microdegree;

use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class MicrodegreeInformationResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            'brief'          => $this->brief,
            'intro_video'    => $this->intro_video,
            'is_featured'    => $this->is_featured,
            'estimated_time' => $this->microdegree->estimated_time,
            'image'          => new FileResource($this->image),
            'cover'          => new FileResource($this->cover),
            'syllabus_url'   => $this->microdegree ? $this->microdegree->syllabus_id : null,
            'learn'          => $this->learn,
            'prerequisites'  => $this->microdegree->prerequisites,
            'average_salary' => $this->microdegree->average_salary,
            'faq'            => $this->microdegree->faq,
            'instructors'    => InstructorBasicInfoResource::collection($this->instructors),
            'packages'       => PackageResource::collection($this->packages),
            'slug_url'       => $this->slug_url,
        ];
    }
}
