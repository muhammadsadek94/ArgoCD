<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Enum\FavouriteType;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonBasicInformationResource extends JsonResource
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
        /** @var \App\Domains\Course\Models\Lesson $lesson */
        $lesson = $this;

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'timing'        => $this->time,
            'type'          => $this->type,
            'is_free'       => $this->is_free,
            'is_watched'    => $request->user('api') ? $request->user('api')->watched_lessons()->where('lesson_id', $this->id)->count() > 0 : false,
            'is_available'  => lesson_access_permission($lesson),
            'chapter_id'    => $this->chapter_id,
            'is_fav'        => $this->isFav($request),
            'drip_content'  => $this->drip_time($this),
            'sort'          => $this->sort,

        ];
    }

    private function isFav(Request $request)
    {
        if (is_null($this->auth())) return false;

        return $this->auth()->favourite_lessons()->where([
            'favourable_id'   => $this->id,
            'favourable_type' => FavouriteType::LESSON
        ])->count() > 0;
    }

    private function  drip_time($lesson)
    {

        $user = auth()->guard('api')->user();
        $CourseEnrollment = CourseEnrollment::where('course_id', $lesson->course_id)->where('user_id', $user->id)->first();
        // check for free lesson as user may not be enrolled yet
        if ($CourseEnrollment) {
            $enrollment_time = CourseEnrollment::where('course_id', $lesson->course_id)->where('user_id', $user->id)->first()->created_at;
        } else
            return true;

        $chapter = Chapter::where('id', $lesson->chapter_id)->first();
        if (Carbon::now() > $enrollment_time->addDays($chapter->drip_time))
            return true;
        else
            return false;
    }
}
