<?php

namespace App\Domains\Course\Features\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Jobs\Api\V1\User\AddToWatchHistoryJob;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonInformationResource;


class GetLessonFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');

        $lesson = $lesson_repository->find($request->lesson_id);
        $course = $course_repository->find($lesson->course_id);

        $user?->load('all_course_enrollments');

        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }

        if (!lesson_access_permission($lesson)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You subscription does not have access to this lesson',
                    'status' => 1005

                ]
            ]);
        }



        if ($user->all_course_enrollments()->where('course_id', $lesson->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'lesson' => new LessonInformationResource($lesson),
            ]
        ]);
    }
}
