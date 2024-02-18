<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Enum\CourseType;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Foundation\Traits\Authenticated;

class LessonInternalMicrodegreeResource extends JsonResource
{
    use Authenticated;


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'chapter_id'            => $this->chapter_id,
            'chapter_name'          => $this->chapter->name,
            'course_id'             => $this->course_id,
            'course_slug_url'       => $this->course->slug_url,
            'chapeter_sort'         => $this->chapter->sort,
            'course_type'           => CourseType::getCourseType($this->course->course_type),
            'type'                  => $this->type,
            'free'                  => $this->is_free,
            'video'                 => $this->video ? $this->getVideoData($this->video) : null,
            'course_image'          => new FileResource($this->course->image),
            'manual'                => new FileResource($this->manual),
            'lesson_objectives'     => $this->lesson_objectives()->active()->orderBy('sort')->get()->map(function ($row) {
                return $row->objective_text;
            }),

        ];
    }

    private function getVideoData($video)
    {
        return [
            'account_id' => @$video['account_id'],
            'player_id'  => @$video['player_id'],
            'video_id'   => @$video['video_id'],
            'type'   => @$video['type'],
        ];
    }
}
