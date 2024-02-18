<?php

namespace App\Domains\User\Http\Resources\Api\V1\Instructor;

use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
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
            "total_courses"         => $this->instructor_courses ? $this->instructor_courses->count() : 0,
            "average_ratings"       => $this->getAverageRate(),
            "image"                 => new FileResource($this->image),
            "total_enrollments"     => $this->getTotalEnrollments(),
            "summary"               => $this->instructor_profile->profile_summary,
            "courses"               => $this->instructor_courses ? CourseBasicInfoResource::collection($this->instructor_courses) : [],
            "payee_name"               => $this->instructor_profile?->payee_name,
            "payee_bank_country"               => $this->instructor_profile?->payee_bank_country,
            "payee_branch_name"               => $this->instructor_profile?->payee_branch_name,
            "branch_code"               => $this->instructor_profile?->branch_code,
            "intermediary_bank"               => $this->instructor_profile?->intermediary_bank,
            "routing_number"               => $this->instructor_profile?->routing_number,
            "payee_bank_for_tt"               => $this->instructor_profile?->payee_bank_for_tt,
        ];
    }

    private function getAverageRate()
    {
        if(!$this->instructor_courses) return 0;
        foreach($this->instructor_courses()->active()->get() as $course)
        {
            $reviews = $course->reviews();
            if(!$reviews->get()->count()) return null;
            return round($reviews->sum('rate') / $reviews->count() , 1);
        }
    }

    private function getTotalEnrollments()
    {
        if(!$this->instructor_courses) return 0;
        $total_enrollments = 0;
        foreach($this->instructor_courses()->active()->get() as $course)
        {
            $course->cousre_enrollments()->count();
            if(!$course->cousre_enrollments()->count()) $total_enrollments += 0;
            $total_enrollments += $course->cousre_enrollments()->count();
        }
        return $total_enrollments;
    }
}
