<?php

namespace App\Domains\User\Features\Api\V1\User\Profile\Course;

use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\User\Repositories\UserRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetPurchasedCoursesFeature extends Feature
{

    public function handle(Request $request, UserRepository $user_repository)
    {
        $user = $request->user('api');

        $courses = $user_repository->getPurchasedCourses($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'purchased_courses' => CourseBasicInfoResource::collection($courses)
            ]
        ]);
    }
}
