<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Framework\Traits\SelectColumnTrait;
use Illuminate\Http\Request;

class MyCoursesFeature extends Feature
{
    use SelectColumnTrait;

    public function handle(Request $request, CourseRepositoryInterface $courseRepository)
    {
        $user = $request->user('api');
        $not_completed_courses = $courseRepository->getNotCompletedCourses($user);
        $completed_courses = $courseRepository->getCompletedCourses($user);

        $user?->load('watched_lessons:id,course_id,chapter_id');

        $user?->load(['active_subscription' => function ($query) {
            $query->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
                ->with('package:id,access_type,access_id,access_permission');
        }]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses'                  => CourseBasicInfoResource::collection($not_completed_courses),
                'completed_courses'        => CourseBasicInfoResource::collection($completed_courses),
            ]
        ]);
    }
}
