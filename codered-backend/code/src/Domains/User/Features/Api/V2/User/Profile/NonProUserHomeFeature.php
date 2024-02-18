<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\BundlesWithCoursesV2Resource;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathInfoResource;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class NonProUserHomeFeature extends Feature
{

    public function __construct(){}

    public function handle(
        Request $request,
        UserRepository $user_repository,
        PackageSubscriptionRepositoryInterface $package_repository
        )
    {
        $user = $request->user();


        $purchased_learn_paths = $package_repository->getPurchasedLearnPaths($user);

        $purchased_courses = $user_repository->getPurchasedCourses($user);

        $free_courses = $user_repository->getFreeCoursesForUser($user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
              'purchased_learn_paths'          => LearnPathInfoResource::collection($purchased_learn_paths),
              'purchased_courses'              => CourseBasicInfoResource::collection($purchased_courses),
              'free_courses'                   => CourseBasicInfoResource::collection($free_courses),
              'first_category'                 => $user->categories()->first() ? $user->categories()->first()->name : null
            ]
        ]);
    }


}
