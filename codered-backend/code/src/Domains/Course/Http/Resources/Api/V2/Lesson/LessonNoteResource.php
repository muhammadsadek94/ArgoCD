<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonNoteResource extends JsonResource
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
            'id'     => $this->id,
            'note'   => $this->note,
            'title'     => $this->title,
            'course' => new CourseBasicInfoResource($this->course),
            'lesson' => new LessonBasicInfoResource($this->lesson),
        ];
    }

}
