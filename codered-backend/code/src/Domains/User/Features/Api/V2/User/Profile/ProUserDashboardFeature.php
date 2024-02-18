<?php

namespace App\Domains\User\Features\Api\V2\User\Profile;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\User\Models\User;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\Microdegree\MicrodegreeBasicInfoResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use Framework\Traits\SelectColumnTrait;

class ProUserDashboardFeature extends Feature
{

    use SelectColumnTrait;

    const CACHE_TTL = 86400;

    public function __construct()
    {
    }

    public function handle(
        Request $request,
        UserRepository $user_repository,
        CourseRepositoryInterface $course_repository,
        PackageSubscriptionRepositoryInterface $package_repository
    )
    {
        /** @var User $user */
        $user = $request->user();

        // return Cache::remember("website_user_dashboard_{$user->id}", SELF::CACHE_TTL, function () use ($user, $package_repository, $user_repository, $course_repository) {

        $user->load([
            'course_enrollments',
            'active_subscription' => function ($query) {
                $query->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
                    ->with('package:id,access_type,access_id,access_permission');
            }])->load('all_course_enrollments', 'categories:id,name,image_id,activation,label_color,icon_class_name,cat_parent_id,sort');

//        $user?->load('watched_lessons:id,course_id,chapter_id');

        $micro_degree = $user->microdegree_enrollments()
            ->where('expired_at', '>', now())
            ->select(SelectColumnTrait::$coursesColumns)
            ->with([
                'completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }])
            ->with('microdegree:id,course_id,estimated_time')
            ->with(SelectColumnTrait::$imageColumnsInline)
            ->groupBy('course_id')->get();

        $certifications = $user->certifications_enrollments()
            ->where('expired_at', '>', now())
            ->with(['completedPercentageLoad' => function ($query) use ($user) {
                $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
            }])
            ->with('microdegree:id,course_id,estimated_time')
            ->with(SelectColumnTrait::$imageColumnsInline)
            ->groupBy('course_id')->get();

            //            $purchased_learn_paths = $package_repository->getPurchasedLearnPaths($user); //TODO: enhancements

            $continue_learning = $user_repository->getNotCompletedCoursesForDashboard($user, limit: 10);

            //            $dd_courses = $user_repository->getPurchasedCourses($user);

            //$learn_paths = $user_repository->getLearnPathsCateogryBased($user);

            $free_courses = $user_repository->getFreeCoursesForUser($user);

            //            $bundles_packages = $user_repository->getUserBundles($user->id, $user);

            $essential = [];
            if (!$user->hasActiveSubscription() || $user->categories->where('parent_id', 'b7c92f95-468a-432f-b91d-88eade3531b0')->count() > 0) {
                $essential = $course_repository->essentials($user);
            }

            $hightest_rate = $course_repository->hightest_rate($user);

            $random_user_catergory = $this->getRandomUserCategory($user);
            $best_sellers_category = $random_user_catergory;
            $top_10_category = $random_user_catergory;
            $editorial_picks_category = $random_user_catergory;
            $popular_courses_category = $random_user_catergory;
            $top10 = $course_repository->top10($user, $random_user_catergory);

            $active_courses = $this->getActiveCourses($user);

            $popular_courses = $user_repository->getPopularCoursesCategoryBased($user->id, $popular_courses_category);

        $best_sellers = $this->getCoursesByCategoryAndType($active_courses, $best_sellers_category, 'is_best_seller', 15);
        $editorial_picks = $this->getCoursesByCategoryAndType($active_courses, $editorial_picks_category, 'is_editorial_pick', 15);
        $feature_course = $this->getCoursesByCategoryAndType($active_courses, take: 5);
        $top_10_watched = $this->getCoursesByCategoryAndType($active_courses, $best_sellers_category, 'is_editorial_pick', 10);

            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'micro_degrees'     => MicrodegreeBasicInfoResource::collection($micro_degree),
                    'certifications'    => MicrodegreeBasicInfoResource::collection($certifications),
                    //                    'purchased_learn_paths' => LearnPathInfoResource::collection($purchased_learn_paths),
                    //                    'learn_paths'           => LearnPathInfoResource::collection($learn_paths),
                    //                    'bundles'               => BundlesWithCoursesV2Resource::collection($bundles_packages),

                    //                    'purchased_courses' => CourseBasicInfoResource::collection($purchased_courses),
                    'free_courses'      => CourseBasicInfoResource::collection($free_courses),
                    'continue_learning' => CourseBasicInfoResource::collection($continue_learning),
                    'popular_courses'   => CourseBasicInfoResource::collection($popular_courses),
                    'essential'         => CourseBasicInfoResource::collection($essential),
                    'feature_course'    => CourseBasicInfoResource::collection($feature_course),
                    'top10'             => CourseBasicInfoResource::collection($top10),
                    'top_10_watched'    => CourseBasicInfoResource::collection($top_10_watched),
                    'editorial_picks'   => CourseBasicInfoResource::collection($editorial_picks),
                    'hightest_rate'     => CourseBasicInfoResource::collection($hightest_rate),
                    'best_sellers'      => CourseBasicInfoResource::collection($best_sellers),

                    'first_category'           => $popular_courses_category->name ?? null,
                    'top10_category'           => $top_10_category->name ?? null,
                    'editorial_picks_category' => $editorial_picks_category->name ?? null,
                    'best_sellers_category'    => $best_sellers_category->name ?? null,
                ]
            ]);
//        });
    }

    private function getRandomUserCategory(User $user)
    {
        $random_user_category = $user->categories->count() ? $user->categories : CourseCategory::child()->inRandomOrder()->limit(2)->get();
        return $random_user_category->shuffle()->first();
    }

    private function getActiveCourses(?User $user = null)
    {
        $query = Course::query();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->limit(50)
        ->inRandomOrder();
        return $query->get();
    }

    private function getCoursesByCategoryAndType($courses, ?CourseCategory $category = null, ?string $type = null, $take = 30)
    {

        $courses = $courses->when(!empty($type), function ($query) use ($type) {
            $query->where($type, 1);
        })
            ->take($take);
        if (!empty($category)) {
            $courses = $courses->where(function ($q) use ($category) {
                $q->where('course_sub_category_id', $category->id)
                    ->orWhere('course_category_id', $category->id);
            });
        }

        return $courses;
    }
}
