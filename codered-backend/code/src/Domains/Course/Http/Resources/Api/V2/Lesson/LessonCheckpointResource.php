<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\User\Enum\SubscribeStatus;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonCheckpointResource extends JsonResource
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
        $user = $request->user('api');
        $course = $this->course;
        $lesson = $this->resource;
        $first_chapter = $this->chapter;
        $course_enrollment = $user ? $user->all_course_enrollments->where('id', $course->id)->first() : null;

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'type'              => (int)$this->type,
            'overview'          => $this->overview,
            'outer_overview'    => $this->outer_overview,
            'course_id'         => $this->course_id,
            'chapter_id'        => $this->chapter_id,
            'timing'            => $this->time,
            'course_type'       => $this->course_type,
            'is_free'           => $this->is_free, // deprecated
            'is_watched'        => $user ? $user->watched_lessons->where('id', $this->id)->count() > 0 : false,
            'waiting_duration'  => !$this->drip_time($this, $course_enrollment, $first_chapter) ? $this->getWaitingDuration() : 0,
            'course_name'       => $course->name,
            'course_id'         => $this->course_id,
            'category_name'     => $course->category?->name ?? $course->sub?->name,
            'after_chapter'     => $this->after_chapter,
            'is_available'      => lesson_access_permission($lesson),
            'drip_content'      => $this->drip_time($this, $course_enrollment, $first_chapter),
        ];
    }

    private function drip_time($lesson, $course_enrollment, $first_chapter)
    {
        $user = auth()->guard('api')->user();
        if ($user) {

            if ($user->chapters_packages?->count()) {
                if ($user->course_user_subscription?->is_installment) {
                    $this->new_installment_type = true;
                } else {
                    $this->closed_for_free_trial = true;
                }
                $chapter_package = $user->chapters_packages->where('id', $first_chapter->id)->first();
                if ($user->course_user_subscription?->status == SubscribeStatus::TRIAL && $chapter_package?->pivot->is_free_trial) return true;
                if ($user->course_user_subscription?->is_installment && $user->paid_installment_count >= $chapter_package?->pivot->after_installment_number) return true;
                if ($user->course_user_subscription?->status == SubscribeStatus::ACTIVE && !$user->course_user_subscription?->is_installment) return true;

                return false;
            }

            $CourseEnrollment = $course_enrollment;

            if ($CourseEnrollment) {
                $enrollment_time = $course_enrollment->pivot?->created_at;
            } else
                return true;
            if (Carbon::now() > $enrollment_time->addDays($first_chapter->drip_time))
                return true;
            else
                return false;
        } else
            return false;
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
        return "This chapter will be unlocked after " . $this->getNumberOfDaysBetweenTwoDates(Carbon::now(), $enrollment_time->addDays($this->chapter->drip_time)) . " Days";
    }

    public function convertNumberToOrdinaryWord()
    {
        $user = auth()->guard('api')->user();
        $chapter_package = $user->chapters_packages->where('id', $this->chapter_id)->first();
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
}
