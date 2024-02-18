<?php

namespace App\Domains\User\Http\Resources\Api\V2\Instructor;

use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class InstructorResource extends JsonResource
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
            "id"                    => $this->id,
            "full_name"             => $this->full_name,
            "current_employer"      => $this->instructor_profile->current_employer,
            "designation"           => $this->instructor_profile->designation,
            "job"                   => $this->instructor_profile->job,
            "total_courses"         => $this->instructor_courses ? $this->instructor_courses->count() : 0,
            "average_ratings"       => round($this->instructor_courses_average_rate, 0) ?? 0,
            "image"                 => new FileResource($this->image),
            "total_enrollments"     => $this->getTotalEnrollments(),
            "summary"               => $this->instructor_profile ? $this->instructor_profile->profile_summary : null,
            "courses"               => $this->instructor_courses ? CourseBasicInfoResource::collection($this->instructor_courses) : [],
        ];
    }

    private function getTotalEnrollments()
    {
        if(!$this->instructor_courses) return 0;
        $total_enrollments = 0;
        foreach($this->instructor_courses as $course)
        {
            $course->cousre_enrollments->count();
            if(!$course->cousre_enrollments->count()) $total_enrollments += 0;
            $total_enrollments += $course->cousre_enrollments->count();
        }
        return $total_enrollments;
    }

}
