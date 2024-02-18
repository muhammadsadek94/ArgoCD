<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Instructor;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\User\Enum\SubscribeStatus;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use phpDocumentor\Reflection\Types\Float_;

class VideoAnalysisResource extends JsonResource
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
            'id'                             => $this->id,
            'name'                           => $this->name,
            'course_name'                    => $this->course->name,
            'course_category'                => $this->course->category->name ?? '',
            'published_date'                 => $this->created_at ? $this->created_at->format('d M Y') : '',
            'average_completion_percentage'  => $this->averagedCompletionPercentage()   ,
            'views'                          => $this->getViews(),
            'min_views'                      => $this->getMinViews()
        ];
    }

    private function getViews(): int
    {

        $all_views_instructors = WatchHistoryTime::where([
            'subscription_type' => SubscribeStatus::ACTIVE,
            'lesson_id' => $this->id
        ])->count();

        return number_format($all_views_instructors);
    }

    private function averagedCompletionPercentage(): int
    {
        $completionPercentage = 0;
        $time =(int)$this->time / 60;
        if ( $this->getViews() && $time > 0 ){
            $completionPercentage =(int) $this->getMinViews() /(int) $this->getViews() / $time;
        }
        return number_format($completionPercentage *100);
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
            'lesson_id'         => $this->id
        ])->get();

        $total_watched_seconds_lessons = 0;
        foreach ($all_views_course as $view_lesson) {
            $total_watched_seconds_lessons += $view_lesson->watched_time;
        }
        $total_watched_mins_instructor = $total_watched_seconds_lessons / 60;
        return number_format($total_watched_mins_instructor);
    }
}
