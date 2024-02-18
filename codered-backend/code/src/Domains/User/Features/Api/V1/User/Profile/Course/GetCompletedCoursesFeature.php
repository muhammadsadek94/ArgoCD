<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Course;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V1\CompletedCourseResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class GetCompletedCoursesFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository)
    {
        $user = $request->user('api');

        $completed_courses = $user_repository->getCompletedCourses($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'completed_courses' => CompletedCourseResource::collection($completed_courses)
            ]
        ]);
    }
}
