<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V1\Chapter\ChapterBasicInfoResource;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseInternalResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'description'         => $this->description,
            'chapters'              => ChapterBasicInfoResource::collection($this->chapters()->has('lessons')->active()->get()),
            'instructors'           => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'            => new InstructorBasicInfoResource($this->user),
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completed_course_id'   => $this->getCompletedCourseId($request),
            'is_finished'           => $this->watchedAllLessons($request),
            'is_free'               => $this->is_free,
            'slug_url'              => $this->slug_url,
            'completion_percentage' => $this->when($request->user('api'), function () use ($request) {
                return $this->completedPercentage($request->user('api'));
            }),
            'tags'          => $this->tags()->active()->get()->map(function ($row){return $row->name;})

        ];
    }

    private function getCompletedCourseId($request)
    {
        $user = $request->user('api');

        return CompletedCourses::where([
                'user_id'   => $user->id,
                'course_id' => $this->id
            ])->first()->id ?? null;

    }

    private function watchedAllLessons($request): bool
    {
        return $this->when($request->user('api'), function () use ($request) {
           return $this->isCourseFinished($request->user('api'));
        });
    }

}
