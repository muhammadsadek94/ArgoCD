<?php

namespace App\Domains\Course\Features\Api\V2\User;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseInternalResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use Framework\Traits\SelectColumnTrait;

class GetInternalCourseFeature extends Feature
{

    use SelectColumnTrait;

    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {
        $user = $request->user('api');

        $course = Course::where(function ($query) use ($request) {
            $query->where('id', $request->course_id)
                ->orWhere('slug_url', $request->course_id);
        })
            ->ActiveOrHide()
            ->withCount('cousre_enrollments as enrollment_count')
            ->with([
                'chapters' => function ($query) {
                    $query->select('id', 'course_id', 'name', 'description', 'drip_time', 'activation', 'agg_lessons')
                        ->active()->whereHas('lessons', function ($query) {
                            $query->active();
                        });
                },
                'chapters.lessons' => function ($query) use ($user) {
                    $query->active()
                        ->select('id', 'chapter_id', 'name', 'course_id', 'time', 'type', 'is_free', 'activation')
                        ->with('course.category:id,name,image_id')
                        ->with('course.sub:id,name,image_id')
                        ->with('course.category.image:id,path,full_url,mime_type')
                        ->with('course.sub.image:id,path,full_url,mime_type')
                        ->with('course.image:id,path,full_url,mime_type')
                        ->with(['course' => function ($query) use ($user) {
                            $query
                                ->select('id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price')
                                ->with(['completedPercentageLoad' => function ($query) use ($user) {
                                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user?->id);
                                }])
                                ->withCount(['lessons' => function ($query) {
                                    $query->where('activation', 1)->whereHas('chapter', function ($query) {
                                        $query->where('activation', 1);
                                    });
                                }]);
                        }])
                        ->with('chapter:id,name,course_id,name,description,drip_time');
                },
                'completedCourses' => function ($query) use ($user) {
                    $query->when($user, function ($query) use ($user) {
                        $query->where('user_id', $user?->id)
                            ->select('id', 'course_id', 'user_id');
                    });
                },
                'tags' => function ($query) {
                    $query->select('course_tags.id', 'name', 'activation')->active();
                },
                'category' => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active();
                },
                'sub' => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active();
                },
                'instructors' => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'surveys' => function ($query) use ($user) {
                    $query->select('id', 'course_id', 'user_id')->where('user_id', $user?->id);
                },
                'user' => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id', 'activation')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                },
                'microdegree:id,slack_url', 'cover:id,path,full_url,mime_type', 'image:id,path,full_url,mime_type',
            ])
            ->withCount('assessments')
            ->firstOrFail();


        $request->course_id = $course->id;

        $user?->load('all_course_enrollments');

        $user?->load(['active_subscription' => function ($query) {
            $query->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
                ->with('package:id,access_type,access_id,access_permission,name,amount,is_installment,installment_count,free_trial_days');
        }]);

        $user?->load(['watched_lessons' => function ($query) use ($course) {
            $query->where('watched_lessons.course_id', $course->id)
                ->orderBy('watched_lessons.id', 'desc')
                ->select('name', 'lessons.id', 'time', 'type', 'lessons.chapter_id', 'sort', 'lessons.course_id')
                ->with('course:id,slug_url');
        }]);



        if (!has_access_course_eager($course, $user)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    'message' => 'You must have active subscription',
                    'status' => 1001

                ]
            ]);
        }

        if ($user->all_course_enrollments->where('id', $request->course_id)->count() == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "message",
                    'message' => trans('You are not enrolled in this course'),
                    'status' => 1002

                ]
            ]);
        }

        $user->chapters_packages = collect([]);

        $subscription_id = $user->all_course_enrollments->where('id', $request->course_id)->sortByDesc('pivot.created_at')->first()?->pivot?->user_subscription_id;

        if ($subscription_id) {
            $user_subscription = $user->active_subscription->where('id', $subscription_id)->first();
            $user->installment_chapters = $user_subscription?->is_installment;
            $user->paid_installment_count = $user_subscription?->paid_installment_count;
            $package_subscription = $user_subscription?->package;
            $user->course_user_subscription = $user_subscription;
            $user->chapters_packages = $package_subscription?->chapters;
        }

        $related_courses = [];
        if (!empty($course->course_category_id)) {
            $related_courses = $course_repository->getCoursesByCategoryId($course->course_category_id, $course->id, user: $user);
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course'          => new CourseInternalResource($course),
                'related_courses' => CourseBasicInfoResource::collection($related_courses),
            ]
        ]);
    }
}
