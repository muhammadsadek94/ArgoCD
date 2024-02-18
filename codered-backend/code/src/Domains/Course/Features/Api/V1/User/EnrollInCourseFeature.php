<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Events\Course\CourseEnrollment;
use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Course\Enum\CourseType;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Jobs\Api\V1\User\EnrollInCourseJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;

class EnrollInCourseFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {

        $user = $request->user('api');
        //        $course = $course_repository->find($course_id);
        $course = Course::where(['id' => $request->course_id])
            ->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        $course_id = $course->id;

        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status'  => 1001

                ]
            ]);
        }

        if ($course->course_type == CourseType::MICRODEGREE) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You are not applicable to enroll in microdegree',
                    'status'  => 1002

                ]
            ]);
        }

        $this->run(EnrollInCourseJob::class, [
            'user'      => $user,
            'course_id' => $course_id,
        ]);

        event(new CourseEnrollment($course, $user));

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }
}
