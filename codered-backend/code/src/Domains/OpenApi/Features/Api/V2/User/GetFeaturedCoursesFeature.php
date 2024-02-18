<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\OpenApi\Http\Resources\Api\V2\User\CourseInfoResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class GetFeaturedCoursesFeature extends Feature
{

    public function handle(CourseRepositoryInterface $course_repository)
    {

        $featured_courses = $course_repository->getFeaturedCourses(30, ['image', 'category']);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'featured_courses' => CourseInfoResource::collection($featured_courses)
            ]
        ]);
    }
}
