<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Course\Http\Resources\Api\V1\LearnPathWithCoursesResource;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Http\Resources\Api\V1\BundlesWithCoursesResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryWithCoursesResource;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonBasicInfoResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\PackageSubscriptionRepository;
use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class LearnPathByIdFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request, UserRepository $user_repository )
    {
        $user = $request->user();
        $learnPaths = PackageSubscription::find($request->id);
        $watched_lessons = $user_repository->countWatchedVideosTodayCourses($user->id);
        $activities = $user_repository->getLastActivitiesCourses($user->id);
        $recommended_to_watch = $user_repository->getUpComingRecommendedCourses($user->id);
        $inprogress_courses = $user_repository->getInProgressCourses($user->id);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'learnPaths'             => new LearnPathWithCoursesResource($learnPaths),
                'goals'               => [
                    'daily_target'         => $user->daily_target,
                    'watched_lessons'      => $watched_lessons,
                    'activities'           => $activities,
                    'recommended_to_watch' => LessonBasicInfoResource::collection($recommended_to_watch),
                ],
                'inprogress_courses'  => CourseBasicInfoResource::collection($inprogress_courses),
            ]
        ]);
    }


}
