<?php

namespace App\Domains\Course\Features\Api\V2;

use App\Domains\Cms\Http\Resources\Api\V1\BrandResource;
use App\Domains\Cms\Http\Resources\Api\V1\SliderResource;
use App\Domains\Cms\Models\Brand;
use App\Domains\Cms\Models\Slider;
use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseDetailsV2Resource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathCollection;

use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Repositories\CourseCategoryRepository;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathInfoResource;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Partner\Repositories\CourseLevelRepository;
use Illuminate\Http\Request;

class HomepageFeature extends Feature
{
    public function __construct()
    {
    }

    public function handle(
        LearnPathInfoRepositoryInterface $learnPathRepo,
        CourseRepositoryInterface $courseRepositoryInterface,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository,
        Request  $request

    ) {
        $user_id = null;
        $user = $request->user('api');

        if ($user){
            $user_id = $user->id;
        }

        $free_course = $courseRepositoryInterface->getFreeCourses(limit: 16);

        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels' => $course_level_repository->getLevels(),
            'job_role' => JobRoleResource::collection($jobRoleRepository->getActiveJobRole()),
            'specialty_area' => SpecialtyAreaResource::collection($specialtyAreaRepository->getActiveSpecialtyArea()),
        ];

        $career_learnPaths = $learnPathRepo->getPathWithFilters($request, 'career', $user_id);

        $skill_learnPaths = $learnPathRepo->getPathWithFilters($request, 'skill', $user_id);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "free_courses" => CourseBasicInfoResource::collection($free_course),
                "filters" => $filters,
                "skill_learnPaths"  => new  LearnPathCollection($skill_learnPaths),
                "career_learnPaths" => new LearnPathCollection($career_learnPaths)
            ]
        ]);
    }
}
