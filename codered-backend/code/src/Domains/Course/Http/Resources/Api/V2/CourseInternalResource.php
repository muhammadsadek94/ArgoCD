<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Http\Resources\Api\V2\Chapter\ChapterBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonBasicInformationResource;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonCheckpointResource;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonTinyInformationResource;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseReview;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\User\InstructorBasicInfoResource;
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

        $user = $request->user('api');
        $all_course_enrollments = $user->all_course_enrollments;
        $completion_percentage = $this->completedPercentageLoad->first();
        $userHasReview = $user && CourseReview::where([
                'course_id' => $this->id,
                'user_id'   => $user->id
            ])->exists();
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'image'                 => new FileResource($this->image),
            'cover'                 => new FileResource($this->cover),
            'description'           => $this->description,
            'chapters'              => ChapterBasicInfoResource::collection($this->chapters),
            'chapters_count'        => $this->agg_count_course_chapters,
            'labs_count'            => @$this->agg_lessons['total_labs'] ?? 0,
            'videos_duration'       => $this->getVideosDuration(),
            'course_level'          => $this->level,
            'instructors'           => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'            => new InstructorBasicInfoResource($this->user),
            'completed_course_id'   => $this->getCompletedCourseId($request),
            'is_finished'           => $this->watchedAllLessons($request),
            'is_free'               => $this->is_free,
            'slug_url'              => $this->slug_url,
            'course_type'           => $this->course_type, //1=course, 2=microdegree
            'slack_url'             => $this->microdegree ? $this->microdegree->slack_url : '',
            'enrolled'              => $user ? $all_course_enrollments->where('id', $this->id)->count() > 0 : false,
            'completion_percentage' => $user ? round($completion_percentage?->completed_percentage) : 0,
            'tags'                      => $this->tags->map(function ($row) {
                return $row->name;
            }),
            'course_tags'            => $this->course_tags,
            'has_access'             => $user ? has_access_course_eager($this->resource, $user) : false,
            'last_watched_lesson'    => $this->lastWatchedLesson() ? new LessonTinyInformationResource($this->lastWatchedLesson()) : null,
            'user_finished_survey'   => $user ? $this->userFinishedSurevy($user) : false,
            'checkpoints'            => LessonCheckpointResource::collection($this->checkpoints()->active()->get()),
            'course_type_name'       => CourseType::getCourseType($this->course_type),
            'user_added_review' => $userHasReview,
            'has_assessment' => $this->assessments_count  > 0
        ];
    }

    private function getVideosDuration() {
        $duration = 0;
        foreach ($this->lessons as $lesson) {
            $duration += (float)$lesson->time;
        }
        return $duration;
    }

    private function getCompletedCourseId($request)
    {
        $user = $request->user('api');

        if (!$user) return null;

        return $this->completedCourses->first()->id ?? null;
    }

    private function watchedAllLessons($request): bool
    {
        return $this->when($request->user('api'), function () use ($request) {
            return $this->isCourseFinished($request->user('api'));
        });
    }

    private function lastWatchedLesson()
    {
        $user = auth()->guard('api')->user();
        $watchedLesson = $user->watched_lessons->first();
        return $watchedLesson ? $watchedLesson : null;
    }

    private function userFinishedSurevy($user)
    {
        return $this->surveys->where('user_id', $user->id)->count() > 0;
    }

}
