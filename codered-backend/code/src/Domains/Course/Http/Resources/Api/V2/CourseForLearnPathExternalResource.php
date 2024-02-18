<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Partner\Http\Resources\Api\V2\Chapter\ChapterFullInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseForLearnPathExternalResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'slug_url'              => $this->slug_url,
            'duration'              => intdiv($this->timing, 60) . ' hrs ' . ($this->timing % 60 ? $this->timing % 60 . ' mins' : ''),
            'chapters_count'        => @$this->agg_count_course_chapters,
            'labs_count'            => @$this->agg_lessons['total_labs'],
            'chapters'              => ChapterFullInfoResource::collection($this->chapters),
        ];
    }

}
