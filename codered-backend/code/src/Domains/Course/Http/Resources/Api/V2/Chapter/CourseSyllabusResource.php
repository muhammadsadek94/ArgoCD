<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Chapter;

use App\Domains\Course\Enum\LessonType;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonBasicInfoResource;
use App\Domains\Course\Models\Chapter;

class CourseSyllabusResource extends JsonResource
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
            'lessons'        => LessonBasicInfoResource::collection($this->lessons),
            'description'    => $this->description,
            'lessons_timing' => @$this->agg_lessons['duration_human_text'],
            'lessons_videos' => @$this->agg_lessons['total_videos'],
            'lessons_labs'   => @$this->agg_lessons['total_labs'],
            'drip_content'   => $this->getAccess(),
        ];
    }

    private function getAccess()
    {
        $user = auth()->guard('api')->user();
//        $user?->load('all_course_enrollments');
        $all_course_enrollments = $user?->all_course_enrollments;
        if ($all_course_enrollments) {
            $enrollment_time = $all_course_enrollments->where('id', $this->course_id)?->first()?->pivot?->created_at;

            if (Carbon::now() > $enrollment_time?->addDays($this->drip_time))
                return true;
            else
                return false;
        }

        return false;

    }

}
