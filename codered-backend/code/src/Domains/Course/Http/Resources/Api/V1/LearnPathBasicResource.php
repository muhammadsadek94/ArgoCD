<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Enum\LearnPathsDeadlineType;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Models\UserSubscription;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathBasicResource extends JsonResource
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
            'id'   =>$this->id,
            'name'                      => $this->name,
            'duration'                  => $this->getDuration() ?? 0,
            'slug_url'                  => $this->slug_url,
            'progress'                  => $this->courses ? $this->progress() : 0,
            'total_courses'             => $this->courses ? $this->courses->count() : 0,
            'completion_percentage'      => $this->courses ? $this->getCompletedPercentage($request) : 0,
        ];

    }


    private function getDuration() {
        $user = Auth::guard('api')->user();
        if(!$user) return;

        $subsription = $user->active_subscription->first();

        if($this->deadline_type == LearnPathsDeadlineType::STATIC) {
            $deadline = $this->expiration_date;
        }
        else {
            $deadline = Carbon::parse($subsription->created_at)->addDays($this->expiration_days);
        }

        return $deadline ? Carbon::parse($deadline)->format('d M Y') : null;
    }

    private function progress()
    {
        $user = Auth::guard('api')->user();
        if(!$user) return 0;

        $courses_ids = $this->courses->pluck('id');
        $completed_courses_count = $this->completedCourses->where('user_id', $user->id)->whereIn('course_id', $courses_ids)->count();

        return $completed_courses_count;
    }

    private function getCompletedPercentage($request)
    {
        if(!$request->user('api')) return 0;
        if(!$this->courses_load->count()) return 0;
        if(!$this->courses_count) return 0;
        $user = $request->user('api');
        $courses_ids = $this->courses_load->pluck('id');
        $completed_courses = $this->completedCourses->where('user_id', $user->id)->whereIn('course_id', $courses_ids)->count();
        return round($completed_courses / $this->courses_count) * 100;
    }

}
