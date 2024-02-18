<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Lesson;

use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use Illuminate\Http\Request;
use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Enum\FavouriteType;
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
        $course = $this->course;
        $course_category = $course->load('category');
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'timing'        => $this->time,
            'type'          => $this->type,
            'is_watched'    => $request->user('api') ? $request->user('api')->watched_lessons()->where('lesson_id', $this->id)->count() > 0 : false,
            'course_name'   => $course->name,
            'course_id'     => $this->course_id,
            'category_name' => $course_category->name,
            'chapter_id'    => $this->chapter_id,
            'is_fav'        => $this->isFav($request),
            'course' => new CourseBasicInfoResource($this->course),

        ];

    }

    private function isFav(Request $request)
    {
        if(is_null($this->auth())) return false;

        return $this->auth()->favourite_lessons()->where([
                'favourable_id'   => $this->id,
                'favourable_type' => FavouriteType::LESSON
            ])->count() > 0;
    }

}
