<?php

namespace App\Domains\Course\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class GetLookupsFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(CourseRepositoryInterface $course_repository, CourseCategoryRepositoryInterface $category_repository, CourseLevelRepository $course_level_repository)
    {

        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveCategories()),
            'levels' => $course_level_repository->getLevels(),
        ];

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'filters' => $filters
            ]
        ]);
    }
}
