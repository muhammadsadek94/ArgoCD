<?php

namespace App\Domains\Course\Features\Api\V2\User\Microdegree;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V2\CourseInternalResource;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Http\Resources\Api\V2\Challenge\ChallengeInfoResource;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonCheckpointResource;
use App\Domains\Course\Http\Resources\Api\V2\Lesson\LessonInternalMicrodegreeResource;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\User\Repositories\UserRepository;
use Carbon\Carbon;
use Framework\Traits\SelectColumnTrait;

class GetInternalMicrodegreeFeature extends Feature
{

    use SelectColumnTrait;

    public function handle(Request $request, CourseRepositoryInterface $course_repository, UserRepository $user_repository)
    {
        $user = $request->user('api');

        $course = Course::where(['id' => $request->course_id])->orWhere(['slug_url' => $request->course_id])
            ->ActiveOrHide()
            ->with('microdegree:id,course_id,slack_url')
            ->withCount('cousre_enrollments as enrollment_count')
            ->with([
                'chapters'                => function ($query) {
                    $query->select('id', 'course_id', 'name', 'description', 'drip_time', 'activation')
                        ->active()->whereHas('lessons', function ($query) {
                            $query->active();
                        });
                },
                'chapters.lessons'        => function ($query) use ($user) {
                    $query->active()
                        ->select('id', 'chapter_id', 'name', 'course_id', 'time', 'type', 'is_free', 'activation')
                        ->with('course.cousre_enrollments:id,course_id,user_id')
                        ->with('course.reviews:id,course_id,rate,user_id')
                        ->with('course.category:id,name,image_id')
                        ->with('course.sub:id,name,image_id')
                        ->with('course.category.image:id,path,full_url,mime_type')
                        ->with('course.sub.image:id,path,full_url,mime_type')
                        ->with('course.image:id,path,full_url,mime_type')
                        ->with([
                            'course' => function ($query) use ($user) {
                                $query
                                    ->select('id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price', 'menu_cover_id')
                                    ->with([
                                        'completedPercentageLoad' => function ($query) use ($user) {
                                            $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user?->id);
                                        }])
                                    ->withCount([
                                        'lessons' => function ($query) {
                                            $query->where('activation', 1)->whereHas('chapter', function ($query) {
                                                $query->where('activation', 1);
                                            });
                                        }]);
                            }])
                        ->with('chapter:id,name,course_id,name,description,drip_time');
                },
                'completedCourses'        => function ($query) use ($user) {
                    $query->when($user, function ($query) use ($user) {
                        $query->where('user_id', $user?->id)
                            ->select('id', 'course_id', 'user_id');
                    });
                },
                'tags'                    => function ($query) {
                    $query->select('course_tags.id', 'name', 'activation')->active();
                },
                'category'                => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active();
                },
                'sub'                     => function ($query) {
                    $query->select('id', 'name', 'image_id', 'label_color', 'icon_class_name', 'activation')->active();
                },
                'instructors'             => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'surveys'                 => function ($query) use ($user) {
                    $query->select('id', 'course_id', 'user_id')->where('user_id', $user?->id);
                },
                'user'                    => function ($query) {
                    $query->active()
                        ->select('users.id', 'first_name', 'image_id', 'activation')
                        ->with('instructor_profile:id,user_id,profile_summary,facebook_url,twitter_url,instagram_url')
                        ->with('image:id,path,full_url,mime_type');
                },
                'completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user?->id);
                },
                'cover:id,path,full_url,mime_type', 'image:id,path,full_url,mime_type'
            ])
            ->withCount('assessments')
            ->firstOrFail();

        $request->course_id = $course->id;

        $user?->load('all_course_enrollments:id,user_id');

        $user?->load([
            'active_subscription' => function ($query) {
                $query->select(SelectColumnTrait::$userActiveSubscriptionsColumns)
                    ->with('package:id,access_type,access_id,access_permission');
            }]);

        $user?->load([
            'watched_lessons' => function ($query) use ($course) {
                $query->where('watched_lessons.course_id', $course->id)->orderBy('watched_lessons.id', 'desc')->select('name', 'lessons.id', 'time', 'type', 'lessons.chapter_id', 'sort', 'lessons.course_id')->with('course:id,slug_url');
            }]);

        if (!has_access_course_eager($course, $user)) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You must have active subscription',
                    'status'  => 1001

                ]
            ]);
        }

        $watchedLesssons = WatchedLesson::select('id', 'lesson_id', 'user_id', 'course_id')->where(['user_id' => $user->id, 'course_id' => $course->id])->orderBy('created_at', 'DESC')->first();
        $lastLesson = $watchedLesssons ? $watchedLesssons->lesson : null;

        $activities = WatchedLesson::where(['user_id' => $user->id, 'course_id' => $course->id])
            ->selectRaw("COUNT(id) as totalLessons, DATE_FORMAT(created_at, '%a') as day")
            ->whereRaw("YEARWEEK(created_at)=YEARWEEK(now())")
            ->groupByRaw("DAY(created_at)")->get();

        $course_enrollment = CourseEnrollment::where('user_id', $user->id)->select('id', 'course_id', 'user_id', 'weekly_target', 'week_start_date', 'week_end_date', 'selected_days')->where('course_id', $course->id)->first();

        $dailyTarget = $user->daily_target;

        $weeklyTarget = $course_enrollment?->weekly_target;

        $total_lessons_watched_today = $user_repository->countWatchedVideosThisWeekMicroCourse($user->id, $course_enrollment->week_start_date, $course_enrollment->week_end_date);

        $days_watched_this_week = $user_repository->daysWatchedVideosThisWeekMicroCourse($user, $course_enrollment->week_start_date, $course_enrollment->week_end_date);

        $days_watched_this_week = collect($days_watched_this_week)->map(function ($day) {
            return Carbon::parse($day)->format('Y-m-d');
        });

        $selected_week_days = [];

        $all_week_days = $this->getDatesBetween($course_enrollment->week_start_date, $course_enrollment->week_end_date);

        if (is_array(json_decode($course_enrollment->selected_days))) {
            foreach (json_decode($course_enrollment->selected_days) as $day) {
                $selected_week_days[$day] = $all_week_days[$day];
            }
        } else {
            $all_week_days[0] = Carbon::now()->startOfWeek()->format('Y-m-d');
            $all_week_days[6] = Carbon::now()->endOfWeek()->format('Y-m-d');
        }

        $selected_week_days = array_values($selected_week_days);

        $total_lessons_watched_this_week = $user_repository->countWatchedVideosInSelectedDay($user->id, $selected_week_days);

        $completion_percentage = $user->daily_target ? ceil(($total_lessons_watched_today / $user->daily_target) * 100) . '%' : '0%';

        $upcomingLessons = $user_repository->getUpcomingLessons($lastLesson, $dailyTarget - $total_lessons_watched_today);

        $nextToWatch = $user_repository->getNextLesson($lastLesson);

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

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course'           => new CourseInternalResource($course),
                'upcoming_lessons' => $upcomingLessons,
                'checkpoints'      => LessonCheckpointResource::collection($course->checkpoints()->active()->get()),
                'challenge'        => $course->challenge()->active()->first() ? new ChallengeInfoResource($course->challenge()->active()->first()) : null,
                'activities'       => $activities,
                'next_to_watch'    => $nextToWatch ? new LessonInternalMicrodegreeResource($nextToWatch) : null,
                'weekly_target'    => [
                    "target"                 => $weeklyTarget,
                    'lessons_completed'      => $total_lessons_watched_this_week,
                    'completion_percentage'  => $completion_percentage,
                    'week_start_date'        => $course_enrollment->week_start_date ? Carbon::parse($course_enrollment->week_start_date)->format('jS M') : null,
                    'week_end_date'          => $course_enrollment->week_end_date ? Carbon::parse($course_enrollment->week_end_date)->format('jS M') : null,
                    'selected_days'          => json_decode($course_enrollment->selected_days),
                    'selected_week_days'     => $selected_week_days,
                    'days_watched_this_week' => $days_watched_this_week,
                    'all_week_days'          => $all_week_days,
                ],
                'daily_target'     => [
                    "target"                => $dailyTarget,
                    'lessons_completed'     => $total_lessons_watched_today,
                    'completion_percentage' => $completion_percentage,
                ]
            ]
        ]);
    }

    public function getDatesBetween($startDate, $endDate)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        $dates = [];
        for ($i = $startDate ; $i <= $endDate ; $i += 86400) {
            $dates[] = date('Y-m-d', $i);
        }
        return $dates;
    }

    private function has_access_course($user, $course)
    {
        if ($course->is_free == 1) return 1;

        if ($course->course_type == CourseType::COURSE) {
            if ($user->hasActiveSubscription(AccessType::PRO)) {
                return 1;
            }

            if ($user->allowedToAccessCategory($course->course_category_id)) {
                return 1;
            }

            if ($user->allowedToAccessCourse($course->id)) {
                return 1;
            }
        } elseif ($course->course_type == CourseType::MICRODEGREE || $course->course_type == CourseType::COURSE_CERTIFICATION) {
            return $user->microdegree_certifications_enrollments()->where('course_id', $course->id)->count() > 0;
        }
    }
}
