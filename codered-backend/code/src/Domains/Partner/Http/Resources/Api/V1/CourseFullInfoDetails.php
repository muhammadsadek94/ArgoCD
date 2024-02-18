<?php

namespace App\Domains\Partner\Http\Resources\Api\V1;

use App\Domains\Partner\Http\Resources\Api\V1\Chapter\ChapterFullInfoResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseFullInfoDetails extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'activation'          => $this->activation,
            'image'               => new FileResource($this->image),
            'cover'               => new FileResource($this->cover),
            'category'            => new CourseCategoryResource($this->category),
            'brief'               => $this->brief,
            'description'         => $this->description,
            'level'               => $this->level,
            'timing'              => $this->timing,
            'learn'               => $this->learn,
            'course_syllabus'     => ChapterFullInfoResource::collection($this->chapters()->has('lessons')->active()->get()),
            'instructors'         => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'          => new InstructorBasicInfoResource($this->user),
            'intro_video'         => $this->intro_video,
            'is_free'             => $this->is_free,
            'slug_url'       => $this->slug_url,
        ];
    }

}
