<?php

namespace App\Domains\Course\Features\Api\V2\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\FreeLessonInformationResource;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Jobs\Api\V1\User\AddToWatchHistoryJob;
use App\Domains\Course\Repositories\LessonRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonInformationResource;


class GetFreeLessonFeature extends Feature
{

    use Authenticated;

    public function handle(Request $request, LessonRepositoryInterface $lesson_repository, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        $lesson = $lesson_repository->find($request->lesson_id);
        $course = $course_repository->find($lesson->course_id);
        if ($lesson->type != LessonType::VIDEO) {

            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }
        if (!$lesson->is_free) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'not have allowed to be here',
                    'status' => 1001

                ]
            ]);


        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'lesson' => new FreeLessonInformationResource($lesson),
            ]
        ]);
    }
}
