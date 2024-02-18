<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class GetCoursesWithLabsFeature extends Feature
{

    public function handle(CourseRepositoryInterface $course_repository)
    {
        $courses_with_labs = $course_repository->getCoursesWithLabs(30, ['image', 'category']);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses_with_labs' => CourseBasicInfoResource::collection($courses_with_labs),
            ]
        ]);
    }
}
