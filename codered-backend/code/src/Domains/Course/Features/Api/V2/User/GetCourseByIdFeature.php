<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V2\CourseInformationResource;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Http\Resources\BundlesInformationResource;
use App\Domains\Cms\Http\Resources\Api\V1\BrandResource;
use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathBasicInfoResource;
use App\Domains\Payments\Repositories\LearnPathInfoRepository;
use DB;
use Framework\Traits\SelectColumnTrait;

class GetCourseByIdFeature extends Feature
{

    use SelectColumnTrait;

    public function handle(Request $request, CourseRepositoryInterface $course_repository, BundlesRepositoryInterface $bundles_repository, LearnPathInfoRepository $learnRepo)
    {
        $user = auth()->guard('api')->user();

        $course = Course::where(function ($query) use ($request) {
            $query->where('courses.id', $request->course_id)
                ->orWhere('courses.slug_url', $request->course_id);
        })
            ->ActiveOrHide()
            ->with([
                'chapters'         => function ($query) {
                    $query->active();
                },
                'chapters.lessons' => function ($query) use ($user) {
                    $query->active()->with('chapter','course');
                    /*->with(['course' => function ($query) use ($user) {
                        $query
                            ->select('id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price')
                            ->with(['completedPercentageLoad' => function ($query) use ($user) {
                                $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user?->id);
                            }])
                            ->withCount(['lessons' => function ($query) {
                                $query->where('activation', 1)->whereHas('chapter', function ($query) {
                                    $query->where('activation', 1);
                                });
                            }]);*/
                    //                        }]);
                },
                //                'completedCourses' => function ($query) use ($user) {
                //                    $query->when($user, function ($query) use ($user) {
                //                        $query->where('user_id', $user?->id)
                //                            ->select('id', 'course_id', 'user_id');
                //                    });
                //                },
                'tags'             => function ($query) {
                    $query->select('course_tags.id', 'name', 'activation')->active();
                },
                'category'         => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active()
                        ->with('image');
                },
                'sub'              => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active();
                },
                'instructors'      => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'user'             => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id', 'activation')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'jobRoles', 'specialtyAreas', 'packages',
                'sub.image:id,path,full_url,mime_type',
                'cover:id,path,full_url,mime_type',
                'image:id,path,full_url,mime_type',
            ])
            ->firstOrFail();

        $related_courses = [];

        if (!empty($course->course_category_id)) {
            $related_courses = $course_repository->getCoursesByCategoryId($course->course_category_id, $course->id, user: $user);
        }

        $paths = $learnRepo->getLearningPathRelatedWithCourses($course->id);

        $brands = $course ? $course->tools : [];

        $bundles = $bundles_repository->getBundleByCourseId($request->course_id);

        $free_courses = $course_repository->getFreeCourses(user: $user);

        $user?->load([
            'all_course_enrollments',
            'active_subscription' => function ($query) {
                $query->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
                    ->with('package:id,access_type,access_id,access_permission');
            }]);

        $user?->load([
            'watched_lessons' => function ($query) use ($course) {
                $query->where('watched_lessons.course_id', $course->id)->orderBy('watched_lessons.id', 'desc')->select('name', 'lessons.id', 'time', 'type', 'lessons.chapter_id', 'sort', 'lessons.course_id')->with('course:id,slug_url');
            }]);

        if ($user) {
            $user->chapters_packages = collect([]);
            $subscription_id = $user->all_course_enrollments
                ->where('id', $request->course_id)
                ->sortByDesc('pivot.created_at')
                ->first()?->pivot?->user_subscription_id;

            if ($subscription_id) {
                $user_subscription = $user->active_subscription->where('id', $subscription_id)->first();
                $user->installment_chapters = $user_subscription?->is_installment;
                $user->paid_installment_count = $user_subscription?->paid_installment_count;
                $package_subscription = $user_subscription?->package;
                $user->course_user_subscription = $user_subscription;
                $user->chapters_packages = $package_subscription?->chapters;
            }
        }

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course'          => new CourseInformationResource($course),
//                'related_courses' => [],
//                'bundles'         => [],
//                'free_courses'    => [],
//                'paths'           => [],
//                'brands'          => [],
                'related_courses' => CourseBasicInfoResource::collection($related_courses),
                'bundles'         => BundlesInformationResource::collection($bundles),
                'free_courses'    => CourseBasicInfoResource::collection($free_courses),
                'paths'           => $paths ? new LearnPathBasicInfoResource($paths) : null,
                'brands'          => count($brands) ? BrandResource::collection($brands) : []
            ]
        ]);
    }
}
