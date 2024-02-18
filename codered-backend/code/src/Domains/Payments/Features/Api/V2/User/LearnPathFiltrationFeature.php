<?php

namespace App\Domains\Payments\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Partner\Repositories\CourseLevelRepository;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathCollection;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class LearnPathFiltrationFeature extends Feature
{


    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }
    public function handle(
        LearnPathInfoRepositoryInterface $learnPathRepo,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository,
        Request  $request

    ) {
        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels' => $course_level_repository->getLevels(),
            'job_role' =>JobRoleResource::collection( $jobRoleRepository->getActiveJobRole()),
            'specialty_area' =>SpecialtyAreaResource::collection( $specialtyAreaRepository->getActiveSpecialtyArea()),
        ];
        $career_learnPaths = $learnPathRepo->getPathWithFilters($request ,'career');

        $skill_learnPaths = $learnPathRepo->getPathWithFilters($request ,'skill');
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "filters"=>$filters,
                "skill_learnPaths"  => new  LearnPathCollection( $skill_learnPaths),
                "career_learnPaths" => new LearnPathCollection( $career_learnPaths)
            ]
        ]);
    }
}

