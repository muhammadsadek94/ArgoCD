<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Http\Requests\Api\StartAssessmentRequest;
use App\Domains\Course\Models\ProctorUsers;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Hash;
use INTCore\OneARTFoundation\Feature;

class ValidateProctorUserForAssessmentFeature extends Feature
{

    public function handle(StartAssessmentRequest $request, CourseRepositoryInterface $course_repository)
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

        if (!$this->checkProctorCredentials($request)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('Invalid credentials'),
                ]
            ]);
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => 'true'
            ]
        ]);
    }

    private function checkProctorCredentials($request)
    {
        $proctor_user = ProctorUsers::active()->where('username', $request->username)->first();
        if (empty($proctor_user)) return false;

        if (!in_array($request->course_id, $proctor_user->course_ids)) return false;

        return Hash::check($request->password, $proctor_user->password);
    }
}
