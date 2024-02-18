<?php

namespace App\Domains\User\Features\Api\V1\User\Profile;

use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Course\Http\Resources\Api\V1\LearnPathWithCoursesResource;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Http\Resources\Api\V1\BundlesWithCoursesResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryWithCoursesResource;
use App\Domains\Course\Http\Resources\Api\V1\LearnPathBasicResource;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonBasicInfoResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Repositories\CourseCategoryRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\PackageSubscriptionRepository;
use App\Domains\User\Repositories\UserRepository;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class ProUserDashboardFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }

    public function handle(Request $request, UserRepository $user_repository, CourseRepositoryInterface $course_repository, CourseCategoryRepositoryInterface $category_repository)
    {
        $user = $request->user();
        $watched_lessons = $user_repository->countWatchedVideosTodayCourses($user->id);
        $activities = $user_repository->getLastActivitiesCourses($user->id);
        $recommended_to_watch = $user_repository->getUpComingRecommendedCourses($user->id);

        $last_watched_course = $user_repository->getLastWatchedCourse($user->id);
        $last_watched_lesson = $user_repository->getLastWatchedLesson($user->id);
        $last_watched_lesson = $user_repository->getNextLesson($last_watched_lesson);
//        dd($last_watched_lesson);
        //        $last_watched_lesson = $user->lesson_history()->latest()->first();
        $next_watched_lesson=$user_repository->getNextLesson($last_watched_lesson);

        $inprogress_courses = $user_repository->getInProgressCourses($user->id);
        $learnPaths = $user_repository->getLearnPathsPackage($user->id);
        $recommended_courses = $course_repository->getRecommendedCourses($user);
        $coming_soon = $course_repository->getComingSoonCourses();
        $favorites_categories = $category_repository->getUserFavouriteCategories($request->user());
//        $available_enrollment = $course_repository->recommendCoursesDependingSubscriptions($user);
        $bundles_packages = $user_repository->getUserBundles($user->id);
        $freeCourses = $course_repository->getFreeCoursesForUnsubscribedUser($user);
        $essentialCourses = $course_repository->getEssentialCourses();
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'last_watched_course' => $last_watched_course ? new CourseBasicInfoResource($last_watched_course) : null,
                'last_watched_lesson' => $next_watched_lesson ? new LessonBasicInfoResource($next_watched_lesson) : null,
                'goals'               => [
                    'daily_target'         => $user->daily_target,
                    'watched_lessons'      => $watched_lessons,
                    'activities'           => $activities,
                    'recommended_to_watch' => LessonBasicInfoResource::collection($recommended_to_watch),
                ],
                'inprogress_courses'  => CourseBasicInfoResource::collection($inprogress_courses),
                'recommended_courses' => CourseBasicInfoResource::collection($recommended_courses),
//                'my_courses'          => CourseBasicInfoResource::collection($available_enrollment),
                'bundles'             => BundlesWithCoursesResource::collection($bundles_packages),
                'learnPaths'             => LearnPathBasicResource::collection($learnPaths),
                'freeCourses'         => CourseBasicInfoResource::collection($freeCourses),
                'essentialCourses'         => CourseBasicInfoResource::collection($essentialCourses),
                'most_coming_soon'     => CourseBasicInfoResource::collection($coming_soon),
                'favorites_categories' => CourseCategoryWithCoursesResource::collection($favorites_categories),

            ]
        ]);
    }


}
