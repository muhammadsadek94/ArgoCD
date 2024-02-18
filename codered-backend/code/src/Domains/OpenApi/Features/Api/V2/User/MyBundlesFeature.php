<?php

namespace App\Domains\OpenApi\Features\Api\V2\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Http\Resources\Api\V2\JobRoleResource;
use App\Domains\Course\Http\Resources\Api\V2\SpecialtyAreaResource;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseLevelRepository;
use App\Domains\Course\Repositories\JobRoleRepositoryInterface;
use App\Domains\Course\Repositories\SpecialtyAreaRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathBasicInfoResource;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathCollection;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use Illuminate\Http\Request;

class MyBundlesFeature extends Feature
{

    public function handle(
        Request $request, 
        PackageSubscriptionRepositoryInterface $package_repository,
        LearnPathInfoRepositoryInterface $learnPathRepo,
        JobRoleRepositoryInterface $jobRoleRepository,
        SpecialtyAreaRepositoryInterface $specialtyAreaRepository,
        CourseCategoryRepositoryInterface $category_repository,
        CourseLevelRepository $course_level_repository
        )
    {
        $user = $request->user('api');

        $purchased_bundles = $package_repository->getPurchasedBundlesWhereNotFinishedOrEnrolled($user);

        $completed_bundles = $package_repository->getPurchasedBundlesWhereFinished($user);
        
        $in_progress_bundles = $package_repository->getPurchasedBundlesWhereInProgress($user);
        
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'purchased_bundles'      => LearnPathBasicInfoResource::collection($purchased_bundles),
                'in_progress_bundles'    => LearnPathBasicInfoResource::collection($in_progress_bundles),
                'completed_bundles'      => LearnPathBasicInfoResource::collection($completed_bundles),
            ]
        ]);
    }
}
