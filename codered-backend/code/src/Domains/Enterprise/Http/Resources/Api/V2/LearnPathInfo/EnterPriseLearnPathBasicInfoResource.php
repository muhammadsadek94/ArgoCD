<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Enum\LearnPathType;

use INTCore\OneARTFoundation\Http\JsonResource;

class EnterPriseLearnPathBasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $user = $request->user('api');

        return [
            'id'                        => $this->id,
            'name'                      => $this->package->name,
            'slug_url'                  => $this->slug_url,
            'description'               => $this->package->description,
            'features'                  => [],
            'type'                      => 99 , // TODO:: THIS REFER TO FRONTEND TO USE LEARN PATH as TITLE INSTEAD OF SKILL PATH... ETC
            'timing'                    => $this->getVideosDuration(),
            'courses_count'             => $this->package->courses->count() ? $this->package->courses->count() : 0,
            'type_name'                 => "Learning Path",
            'completion_percentage'     => $this->package->courses->count() ? $this->completionPercentage($request) : 0,
            'certificate'               => null,
        ];


    }

    private function getVideosDuration()
    {
        $timing = $this->getCourses()->sum('timing');
        $duration = ceil($timing / 60);
        return $duration;
    }

    public function getCourses()
    {
        $courses_ids = $this->package->courses()->orderBy('sort','Asc')->pluck('course_id');
        $courses = Course::whereIn('id', $courses_ids)->active();

        return $courses;
    }


    private function completionPercentage($request) {
        if(!$request->user('api')) return 0;
        if(!$this->package->courses()->count()) return 0;

        return round($this->completedCoursesCount($request) / $this->package->courses()->count() * 100);
    }

    private function completedCoursesCount($request) {

        if(!$request->user('api')) return 0;

        $user = $request->user('api');

        $completed_courses = $this->getCourses()->whereHas('completedCourses', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return $completed_courses;
    }


}
