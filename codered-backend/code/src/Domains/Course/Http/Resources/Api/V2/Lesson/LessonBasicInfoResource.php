<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Enum\FavouriteType;
use App\Domains\User\Enum\SubscribeStatus;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonBasicInfoResource extends JsonResource
{
    use Authenticated;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user('api');
//        $course = $this->course;
        $lesson = $this->resource;
        $first_chapter = $this->chapter;
        $favourite_lessons = $user ? $user->favourite_lessons : [];
        $course_enrollment = $user ? $user->all_course_enrollments->where('id', $this->course_id)->first() : null;

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'timing'            => $this->time,
            'type'              => $this->type,
            'course_type'       => $this->course_type,
            'is_free'           => $this->is_free, // deprecated
            'is_watched'        => $user ? $user->watched_lessons->where('id', $this->id)->count() > 0 : false,
            'course_id'         => $this->course_id,
//            'category_name'     => $course->category?->name ?? $course->sub?->name,
            'chapter_id'        => $this->chapter_id,
            'is_fav'            => $this->isFav($favourite_lessons),
            'after_chapter'     => $this->after_chapter,
            'course' => $this->whenLoaded('course', function () {
                return new CourseBasicInfoResource($this->course);
            }),
            'is_available'      => lesson_access_permission($lesson),
            'drip_content'      => $this->drip_time($this, $course_enrollment, $first_chapter),

        ];
    }

    private function isFav($favourite_lessons)
    {
        if (is_null($this->auth())) return false;

        return $favourite_lessons->where([
            'favourable_id'   => $this->id,
            'favourable_type' => FavouriteType::LESSON
        ])->count() > 0;
    }

    private function drip_time($lesson, $course_enrollment, $first_chapter)
    {
        $user = auth()->guard('api')->user();
        if ($user) {

            if ($user->chapters_packages?->count()) {
                $chapter_package = $user->chapters_packages->where('id', $first_chapter->id)->first();
                if ($user->course_user_subscription?->status == SubscribeStatus::TRIAL && $chapter_package?->pivot->is_free_trial) return true;
                if ($user->course_user_subscription?->is_installment && $user->paid_installment_count >= $chapter_package?->pivot->after_installment_number) return true;
                if ($user->course_user_subscription?->status == SubscribeStatus::ACTIVE && !$user->course_user_subscription?->is_installment) return true;

                return false;
            }

            $CourseEnrollment = $course_enrollment;

            if ($CourseEnrollment) {
                $enrollment_time = $course_enrollment?->pivot->created_at;
            } else
                return true;
            if (Carbon::now() > $enrollment_time->addDays($first_chapter->drip_time))
                return true;
            else
                return false;
        } else
            return false;
    }
}
