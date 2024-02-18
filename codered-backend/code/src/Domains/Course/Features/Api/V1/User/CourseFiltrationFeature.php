<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Bundles\Repositories\BundlesRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseCollection;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;


class CourseFiltrationFeature extends Feature
{

    public function handle(Request $request, CourseRepositoryInterface $course_repository, CourseCategoryRepositoryInterface $category_repository, CourseLevelRepository $course_level_repository)
    {
        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveCategories()),
            'levels'     => $course_level_repository->getLevels(),
        ];

        $courses = $course_repository->courseFiltration($request);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'result'  => new CourseCollection($courses),
                'filters' => $filters
            ]
        ]);
    }
}
