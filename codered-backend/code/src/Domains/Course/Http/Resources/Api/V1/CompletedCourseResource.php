<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class CompletedCourseResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->course->name ?? 'Course Deleted',
            'category'    => $this->course ? new CourseCategoryResource($this->course->category) : 'Not Available',
            'time'        => $this->course->timing ?? 'Not Available',
            'level'       => $this->course->level ?? 'Not Available',
            'certificate' => new FileResource($this->certificate),
            'degree'      => $this->degree
        ];
    }
}
