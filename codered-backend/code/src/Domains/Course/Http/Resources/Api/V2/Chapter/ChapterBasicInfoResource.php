<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Chapter;

use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Course\Enum\LessonType;
use App\Domains\User\Enum\SubscribeStatus;
use Carbon\Carbon;

class ChapterBasicInfoResource extends JsonResource
{

    private $new_installment_type = false;
    private $closed_for_free_trial = false;
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                         => $this->id,
            'name'                       => $this->name,
            'lessons'                    => LessonBasicInfoResource::collection($this->lessons),
            'description'                => $this->description,
            'drip_content'               => $this->getAccess(),
            'waiting_duration'           => !$this->getAccess() ? $this->getWaitingDuration() : 0,
            'watched_lessons_percentage' => $this->watchedLessonsPercentage(),
            'lessons_count'              => $this->getLessonsCount(),
            'watched_lessons_count'      => $this->watchedLessonsCount(),
        ];
    }

    private function getAccess()
    {
        $user = auth()->guard('api')->user();

        if ($user->chapters_packages?->count()) {
            if ($user->course_user_subscription?->is_installment) {
                $this->new_installment_type = true;
            } else {
                $this->closed_for_free_trial = true;
            }

            $chapter_package = $user->chapters_packages->where('id', $this->id)->first();
            if (!$chapter_package) return true;
            if ($user->course_user_subscription?->status == SubscribeStatus::TRIAL && $chapter_package?->pivot?->is_free_trial) return true;
            if ($user->course_user_subscription?->is_installment && $user->paid_installment_count >= $chapter_package?->pivot?->after_installment_number) return true;
            if ($user->course_user_subscription?->status == SubscribeStatus::ACTIVE && !$user->course_user_subscription?->is_installment) return true;

            return false;
        }

        $all_course_enrollments = $user->all_course_enrollments;
        $enrollment_time = $all_course_enrollments->where('id', $this->course_id)?->first()?->created_at;

        if (Carbon::now() > $enrollment_time?->addDays($this->drip_time))
            return true;
        else
            return false;
    }

    private function watchedLessonsPercentage()
    {
        $user = auth()->guard('api')->user();
        if ($user->watched_lessons->where('chapter_id', $this->id)->count() == 0) return 0;
        return round(($user->watched_lessons->where('chapter_id', $this->id)->count() / ($this->getLessonsCount())) * 100);
    }

    private function getWaitingDuration()
    {
        $user = auth()->guard('api')->user();

        if ($this->closed_for_free_trial)
            return "This chapter in not included in the free trial";
        if ($this->new_installment_type) {
            return "This chapter will be unlocked after the " . $this->convertNumberToOrdinaryWord() . " installment";
        }

        $all_course_enrollments = $user->all_course_enrollments;
        $enrollment_time = $all_course_enrollments->where('id', $this->course_id)?->first()->pivot->created_at;
        return "This chapter will be unlocked after " . $this->getNumberOfDaysBetweenTwoDates(Carbon::now(), $enrollment_time->addDays($this->drip_time)) . " Days";
    }

    public function convertNumberToOrdinaryWord()
    {
        $user = auth()->guard('api')->user();
        $chapter_package = $user->chapters_packages->where('id', $this->id)->first();
        $number = $chapter_package?->pivot?->after_installment_number;
        $last_digit = $number % 10;
        $last_two_digits = $number % 100;
        if ($last_digit == 1 && $last_two_digits != 11) {
            return $number . 'st';
        } elseif ($last_digit == 2 && $last_two_digits != 12) {
            return $number . 'nd';
        } elseif ($last_digit == 3 && $last_two_digits != 13) {
            return $number . 'rd';
        } else {
            return $number . 'th';
        }
    }

    public function getNumberOfDaysBetweenTwoDates($date1, $date2)
    {
        $date1 = Carbon::parse($date1);
        $date2 = Carbon::parse($date2);
        $diff_in_days = $date1->diffInDays($date2);
        return $diff_in_days;
    }

    public function lastWatchedLesson()
    {
        $user = auth()->guard('api')->user();
        $last_watched_lesson = $user->watched_lessons->where('chapter_id', $this->id)->orderBy('watched_lessons.created_at', 'desc')->first();
        return $last_watched_lesson;
    }

    public function watchedLessonsCount()
    {
        $user = auth()->guard('api')->user();
        return $user->watched_lessons->where('chapter_id', $this->id)->count();
    }

    /**
     * @return int|mixed
     */
    private function getLessonsCount(): mixed
    {
        return @$this->agg_lessons['total_lessons'] ?? 1;
    }
}
