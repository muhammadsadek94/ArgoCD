<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathInfoResource;

use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseCategoryWithCoursesResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Microdegree\MicrodegreeBasicInfoResource;

class MyCoursesFeature extends Feature
{

    public function __construct()
    {
    }

    public function handle(
        Request $request,
        UserRepository $user_repository,
        PackageSubscriptionRepositoryInterface $package_repository,
        CourseCategoryRepositoryInterface $category_repository
    ) {
        $user = $request->user();

        $micro_degree = [];
        $purchased_learn_paths = [];
        $purchased_bundles = [];
        $purchased_courses = [];
        $free_courses = [];
        $popular_courses = [];
        $learn_paths = [];
        $favorites_categories = [];
        $micro_degree = $user->microdegree_enrollments()->groupBy('course_id')->get();



        if ($user->hasActiveSubscription()) {
            $favorites_categories = $category_repository->getUserFavouriteCategories($request->user());
            $learn_paths = $user_repository->getLearnPathsCateogryBased($user);
            $popular_courses = $user_repository->getPopularCoursesCategoryBased($user->id);

        } else {
            $purchased_learn_paths = $package_repository->getPurchasedLearnPaths($user);
            $purchased_bundles = $package_repository->getPurchasedBundles($user);
            $purchased_courses = $user_repository->getPurchasedCourses($user);
            $free_courses = $user_repository->getFreeCoursesForUser($user);
        }





        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                //PRO
                'favorites_categories'           => CourseCategoryWithCoursesResource::collection($favorites_categories),
                'popular_courses'                => CourseBasicInfoResource::collection($popular_courses),
                'learn_paths'                    => LearnPathInfoResource::collection($learn_paths),

                //NON-PRO

                'purchased_learn_paths'          => LearnPathInfoResource::collection($purchased_learn_paths),
                'purchased_bundles'               => LearnPathInfoResource::collection($purchased_bundles),
                'purchased_courses'              => CourseBasicInfoResource::collection($purchased_courses),
                'free_courses'                   => CourseBasicInfoResource::collection($free_courses),


                //all
                'micro_degrees'                  => MicrodegreeBasicInfoResource::collection($micro_degree),
            ]
        ]);
    }
}
