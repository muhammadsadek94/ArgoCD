<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseInternalResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseInformationResource;


class GetInternalCourseFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');
//        $course = $course_repository->find($request->course_id);
        $course = Course::where(['id' => $request->course_id])->orWhere(['slug_url' => $request->course_id])->firstOrFail();
        $request->course_id = $course->id;
        if (!has_access_course($course)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }


        if ($user->course_enrollments()->where('course_id', $request->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }

        $related_courses = [];
        if (!empty($course->course_category_id)) {
            $related_courses = $course_repository->getCoursesByCategoryId($course->course_category_id, $course->id);
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course' => new CourseInternalResource($course),
                'related_courses' => CourseBasicInfoResource::collection($related_courses),
            ]
        ]);
    }
}
