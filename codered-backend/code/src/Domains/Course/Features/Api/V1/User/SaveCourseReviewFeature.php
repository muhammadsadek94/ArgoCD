<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Repositories\CourseRepository;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Http\Requests\Api\SaveCourseReviewRequest;
use App\Domains\Course\Jobs\Api\V1\User\SaveCourseReviewJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Models\CourseReview;
use App\Domains\User\Models\User;

class SaveCourseReviewFeature extends Feature
{

    public function handle(SaveCourseReviewRequest $request, CourseRepository $course_repository)
    {
        $user = $request->user('api');

        $course = $course_repository->find($request->course_id);
        if(!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }

        if ($user->all_course_enrollments()->where('course_id', $request->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status'  => 1002

                ]
            ]);
        }

        $this->run(SaveCourseReviewJob::class, [
            'user'           => $user,
            'course_id'      => $request->course_id,
            'name'           => '', //$request->name
            'rate'           => $request->rate,
            'user_goals'     => $request->user_goals,
            'recommendation' => $request->recommendation
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
