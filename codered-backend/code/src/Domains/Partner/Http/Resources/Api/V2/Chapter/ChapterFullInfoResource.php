<?php

namespace App\Domains\Partner\Http\Resources\Api\V2\Chapter;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Partner\Http\Resources\Api\V2\Lesson\LessonBasicResource;
use App\Domains\Partner\Http\Resources\Api\V2\Lesson\LessonFullInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class ChapterFullInfoResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'videos_count'  => @$this->agg_lessons['total_videos'],
            'labs_count'    => @$this->agg_lessons['total_labs'],
            'duration'      => @$this->agg_lessons['duration_human_text'],
            'lessons'       => $this->whenLoaded('lessons', function () {
                return LessonBasicResource::collection($this->lessons);
            })
        ];
    }

}
