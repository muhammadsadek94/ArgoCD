<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Instructor;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\User\Enum\SubscribeStatus;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class CourseStateBasicInfoResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'views'     => $this->getViews(),
            'enrolled'  => $this->getEnrollments(),
            'min_views' => $this->getMinViews()
        ];
    }

    private function getViews(): int
    {
        return number_format(WatchHistoryTime::where('course_id', $this->id)->where('subscription_type', SubscribeStatus::ACTIVE)->count());
    }

    private function getEnrollments(): int
    {
        return number_format(CourseEnrollment::where('course_id', $this->id)->count());
    }


    /**
     * get instructor watched mins
     * TODO: refactor into repository
     *
     * @return float|int
     */
    private function getMinViews()
    {
        $all_views_course = WatchHistoryTime::where([
            'subscription_type' => SubscribeStatus::ACTIVE,
            'course_id' => $this->id
        ])->get();
        $total_watched_seconds_lessons = 0;
        foreach ($all_views_course as $view_lesson) {
            $total_watched_seconds_lessons += $view_lesson->watched_time;
        }

        $total_watched_mins_instructor = $total_watched_seconds_lessons / 60;
        return (int) number_format($total_watched_mins_instructor);
    }
}
