<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use App\Domains\Enterprise\Http\Resources\Api\V2\LearnPathInfo\EnterPriseLearnPathBasicInfoResource;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepositoryInterface;
use App\Domains\OpenApi\Http\Requests\Api\V2\User\EnrolledCoursesRequest;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\CourseInfoResource;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathBasicInfoResource;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathCollection;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;

class MyLearningPathsFeature extends Feature
{

    public function handle(
        Request $request,
        PackageSubscriptionRepositoryInterface $package_repository,
        LearnPathInfoRepositoryInterface $learnPathRepo,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository,
        EnterpriseLearnPathRepository $enterpriseLearnPathRepository
        )
    {
        $user = $request->user('api');

        $purchased_enterprise_learn_paths =  $enterpriseLearnPathRepository->getPurchasedLearnPathsWhereNotFinishedOrEnrolled($user);

        $purchased_learn_paths = $package_repository->getPurchasedLearnPathsWhereNotFinishedOrEnrolled($user);

        $completed_learning_paths = $package_repository->getPurchasedLearnPathsWhereFinished($user);

        $in_progress_learning_paths = $package_repository->getPurchasedLearnPathsWhereInProgress($user);

        $in_progress_enterprise_learning_paths = $enterpriseLearnPathRepository->getPurchasedEnterPriseLearnPathsWhereInProgress($user);

        $career_learnPaths = $learnPathRepo->getPathWithFilters($request, 'career', $user->id);

        $skill_learnPaths = $learnPathRepo->getPathWithFilters($request, 'skill', $user->id);

        $filters = [
            'categories' => CourseCategoryResource::collection($category_repository->getActiveSubCategories()),
            'levels' => $course_level_repository->getLevels(),
            'job_role' => JobRoleResource::collection($jobRoleRepository->getActiveJobRole()),
            'specialty_area' => SpecialtyAreaResource::collection($specialtyAreaRepository->getActiveSpecialtyArea()),
        ];

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'purchased_learning_paths'                          => LearnPathBasicInfoResource::collection($purchased_learn_paths),
                'in_progress_learning_paths'                        => LearnPathBasicInfoResource::collection($in_progress_learning_paths),
                'completed_learning_paths'                          => LearnPathBasicInfoResource::collection($completed_learning_paths),
                'purchased_enterprise_learning_paths'               => EnterPriseLearnPathBasicInfoResource::collection($purchased_enterprise_learn_paths),
                'purchased_enterprise_in_progress_learning_paths'   => EnterPriseLearnPathBasicInfoResource::collection($in_progress_enterprise_learning_paths),
                "skill_learnPaths"                                  => new LearnPathCollection($skill_learnPaths),
                "career_learnPaths"                                 => new LearnPathCollection($career_learnPaths),
                "filters"                                           => $filters,
            ]
        ]);
    }
}
