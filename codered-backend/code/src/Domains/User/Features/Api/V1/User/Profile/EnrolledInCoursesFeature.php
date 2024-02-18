<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;


class EnrolledInCoursesFeature extends Feature
{

    public function handle(Request $request)
    {
        $user = $request->user('api');
        $courses = $user->course_enrollments;

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => CourseBasicInfoResource::collection($courses),
            ]
        ]);
    }
}
