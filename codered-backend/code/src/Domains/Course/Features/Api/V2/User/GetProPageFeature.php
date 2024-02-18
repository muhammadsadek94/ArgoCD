<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Cms\Http\Resources\Api\V1\BrandResource;
use App\Domains\Cms\Models\Brand;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseCollection;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Partner\Repositories\CourseLevelRepository;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseDetailsV2Resource;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class GetProPageFeature extends Feature
{


    public function handle(
        CourseRepositoryInterface $course_repository,
        Request $request,
        PackageSubscriptionRepositoryInterface $package_subscription_repository,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository
    )
    {
//        $categories = CourseCategory::with(['courses' => function($query) {
//            $query->latest()->take(6)->get();
//        }])->active()->take(6)->latest()->get();
//
        $brands = Brand::limit(5)->get();

        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels' => $course_level_repository->getLevels(),
            'job_role' =>JobRoleResource::collection( $jobRoleRepository->getActiveJobRole()),
            'specialty_area' =>SpecialtyAreaResource::collection( $specialtyAreaRepository->getActiveSpecialtyArea()),
        ];

        $courses = $course_repository->courseFiltrationV2($request);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                "filters"=>$filters,
                'courses' => isset($courses) ?  CourseBasicInfoResource::collection($courses) : [],
                'brands' => BrandResource::collection($brands)

            ]
        ]);
    }
}
