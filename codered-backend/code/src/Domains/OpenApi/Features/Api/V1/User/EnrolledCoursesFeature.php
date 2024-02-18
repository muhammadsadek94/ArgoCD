<?php

namespace App\Domains\OpenApi\Features\Api\V1\User;

use App\Domains\OpenApi\Http\Requests\Api\V1\User\EnrolledCoursesRequest;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\CourseInfoResource;
use App\Domains\User\Models\User;


class EnrolledCoursesFeature extends Feature
{

    public function handle(EnrolledCoursesRequest $request)
    {
        $user = $request->user('api');

        $courses = $user->course_enrollments;

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => CourseInfoResource::collection($courses),
            ]
        ]);
    }
}
