<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\User\InstructorBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\Chapter\ChapterBasicInfoResource;

class MicrodegreeWithChaptersResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'course_syllabus' => ChapterBasicInfoResource::collection($this->chapters()->has('lessons')->active()->get()),
            'slug_url'       => $this->slug_url,
        ];
    }
}
