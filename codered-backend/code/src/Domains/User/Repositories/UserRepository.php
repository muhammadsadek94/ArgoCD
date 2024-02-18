<?php

namespace App\Domains\User\Repositories;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonActivationStatus;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Foundation\Repositories\Repository;
use App\Domains\Course\Models\WatchingHistory;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Foundation\Repositories\RepositoryInterface;
use Carbon\Carbon;
use DB;
use Framework\Traits\SelectColumnTrait;
use stdClass;

class UserRepository extends Repository implements RepositoryInterface
{

    use SelectColumnTrait;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Enterprise Users
     */

    /**
     * @param $enterprise_id
     * @return int
     */
    public function getEnterpriseUsers($enterprise_id)
    {
        $query = $this->getModel()->newQuery();
        $users = $query->where(function ($query) use ($enterprise_id) {
            $query->where('enterprise_id', $enterprise_id)
                ->orWhere('subaccount_id', $enterprise_id);
        })->get();
        return $users;
    }

    public function getLearnPaths($user_id)
    {
        return PackageSubscription::join("user_subscriptions", function ($join) {
            $join->on("package_subscriptions.id", "=", "user_subscriptions.package_id");
        })
            ->join("users", function ($join) {
                $join->on("user_subscriptions.user_id", "=", "users.id");
            })
            ->whereIn('access_type', [AccessType::LEARN_PATH_SKILL, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE])
            //            ->where("package_subscriptions.type", "=", SubscriptionPackageType::Enterprise)
            ->where("users.id", "=", $user_id)
            ->get()->map(function ($package) {
                $package->progress = $this->progress($package);
                return $package;
            })->toArray();
    }

    public function getLearnPathsCateogryBased($user)
    {
        $user_packages = $user->purchased_subscription()->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))->pluck('package_id');

        $user_packages = $user_packages->filter(function ($value, $key) {
            return $value != null;
        });

        $learn_paths_ids = PackageSubscription::whereIn('id', $user_packages)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $learn_paths = LearnPathInfo::whereNotIn('package_id', $user_packages)
            ->select('id', 'name', 'description', 'image_id', 'cover_id', 'slug_url', 'type', 'features', 'activation', 'metadata', 'package_id', 'price')
            ->whereNotIn('id', $learn_paths_ids)
            ->where(function ($query) {
                $query->where('type', '!=', LearnPathType::BUNDLE_CATEGORY)
                    ->where('type', '!=', LearnPathType::BUNDLE_COURSES);
            })
            ->with('allCourses:courses.id,timing', 'image:id,path,full_url,mime_type', 'cover:id,path,full_url,mime_type', 'package_subscription:id,name,amount,description,activation,access_type,access_id,access_permission,type,duration,deadline_type')
            ->with([
                'completedCourses' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedCoursesColumns)->where('user_id', $user->id);
                }])
            ->with([
                'user_subscriptions' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->orderBy('expired_at', 'DESC')->first();
                }])
            ->get();

        return $learn_paths;
    }

    private function progress($package)
    {

        return CourseWeight::select(DB::raw(' AVG((course_weights.weight/100) * completed_course_percentages.completed_percentage) as avg_progress'))
            ->join("completed_course_percentages", function ($join) {
                $join->on("course_weights.course_id", "=", "completed_course_percentages.course_id");
            })
            ->join("users", function ($join) {
                $join->on("completed_course_percentages.user_id", "=", "users.id");
            })
            ->join("package_subscriptions", function ($join) {
                $join->on("course_weights.package_subscription_id", "=", "package_subscriptions.id");
            })
            ->where("package_subscriptions.id", "=", $package?->id)
            ->where("users.id", "=", $this->id)
            ->first()->avg_progress;
    }
    /**
     * Pro users
     */

    /**
     * @param $user_id
     * @return int
     */
    public function countWatchedVideosTodayCourses($user_id)
    {

        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);
        $query = $query->whereDate('created_at', date('Y-m-d'))
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::COURSE);
            })
            ->distinct('lesson_id');
        return $query->count();
    }

    /**
     * @param $user_id
     * @param $course_id
     * @return int
     */
    public function countWatchedVideosTodayMicroCourse($user_id)
    {

        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);

        $query = $query->whereDate('created_at', date('Y-m-d'))
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE);
            })
            ->distinct('lesson_id');

        return $query->count();
    }

    public function countWatchedVideosThisWeekMicroCourse($user_id, $week_start_date, $week_end_date)
    {

        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);

        $query = $query->whereBetween('created_at', [Carbon::parse($week_start_date)->toDateTimeString(), Carbon::parse($week_end_date)->toDateTimeString()])
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE);
            })
            ->distinct('lesson_id');

        return $query->count();
    }

    public function countWatchedVideosInSelectedDay($user_id, $selected_days)
    {
        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);

        $query = $query->whereIn(DB::raw("DATE(created_at)"), $selected_days)
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE);
            })
            // ->distinct('lesson_id')
            ->distinct(DB::raw('DATE(created_at)'));

        return $query->count();
    }

    public function daysWatchedVideosThisWeekMicroCourse($user, $week_start_date, $week_end_date)
    {

        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user->id);

        $query = $query->whereBetween('created_at', [Carbon::parse($week_start_date)->startOfDay()->toDateTimeString(), Carbon::parse($week_end_date)->endOfDay()->toDateTimeString()])
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE);
            })
            ->distinct('lesson_id');

        return $query->pluck('created_at');
    }

    /**
     * @param     $user_id
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLastActivitiesCourses($user_id, $days = 7)
    {
        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);
        $query = $query->where('created_at', '>=', now()->subDays($days))
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::COURSE);
            })
            ->groupBy('lesson_id');
        $query = $query->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date'  => $date,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }

    /**
     * Get Last Watched Course
     *
     * @param $user_id
     * @return Course
     */
    public function getLastWatchedCourse($user_id): ?Course
    {
        $query = $this->getModel()->newQuery();
        $user = $query->find($user_id);
        return $user->course_history()
            ->where('course_type', CourseType::COURSE)
            ->latest('watching_histories.created_at')
            ->first();
    }

    /**
     * Get Last Watched Lesson
     *
     * @param $user_id
     * @return Lesson
     */
    public function getLastWatchedLesson($user_id): ?Lesson
    {
        $query = $this->getModel()->newQuery();
        $user = $query->find($user_id);
        return $user->lesson_history()
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::COURSE);
            })->latest('watching_histories.created_at')->first();
    }

    /**
     * Get Last Watched Lesson
     *
     * @param $lastWatchLesson
     * @return Lesson
     */
    public function getNextLesson($lastWatchLesson): ?Lesson
    {
        if (!$lastWatchLesson) {
            return null;
        }
        $nextLesson = Lesson::where([
            "course_id"  => $lastWatchLesson->course_id,
            "chapter_id" => $lastWatchLesson->chapter_id,
            ['sort', '>', $lastWatchLesson->sort]
        ])->whereHas('course')->active()->orderBy('sort', 'ASC')->first();

        if (!$nextLesson) {

            $currentChapter = Chapter::active()->find($lastWatchLesson->chapter_id);
            $nextChapter = Chapter::where(['course_id' => $currentChapter->course_id, ['sort', '>', $currentChapter->sort]])->active()->orderBy('sort', 'ASC')->first();
            if (!$nextChapter) {
                return null;
            }

            $nextLesson = Lesson::where([
                "course_id"  => $nextChapter->course_id,
                "chapter_id" => $nextChapter->id,
                ['sort', '>', 0]
            ])->whereHas('course')->active()->first();
            if (!$nextLesson) {
                return null;
            }
        }

        return $nextLesson;
    }

    /**
     * @param $user_id
     * @param $limit
     * @return
     */
    public function getInProgressCourses($user_id, $limit = 60)
    {
        $query = $this->getModel()->newQuery();
        $user = $query->find($user_id);
        return $user->completed_course_percentages()->where('course_type', CourseType::COURSE)
            ->select('courses.id', 'courses.created_at', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price', 'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment')
            ->with(['image:id,path,full_url,mime_type', 'category', 'cover', 'sub', 'reviews:id,course_id,rate,user_id'])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user_id) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user_id);
                }])
            ->groupBy('id')
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getNotCompletedCourses($user, $limit = 60)
    {

        return $user->completed_course_percentages()
            ->where('course_type', CourseType::COURSE)
            ->select(
                'courses.id', 'courses.created_at', 'courses.name', 'courses.brief', 'courses.level', 'courses.timing', 'courses.course_sub_category_id',
                'courses.course_category_id', 'courses.course_type', 'courses.image_id', 'courses.slug_url', 'courses.is_free', 'courses.price', 'courses.discount_price',
                'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment'
            //                DB::raw('AVG(course_reviews.rate) AS avg_reviews'), DB::raw('count(course_reviews.rate) AS total_reviews'),
            )
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user_id) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user_id);
                }])
            ->whereBetween('completed_percentage', [0, 100])
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getNotCompletedCoursesForDashboard($user, $limit = 60)
    {

        return $user->completed_course_percentages()
            ->where('course_type', CourseType::COURSE)
            ->select(
                'courses.id', 'courses.created_at', 'courses.name', 'courses.brief', 'courses.level', 'courses.timing', 'courses.course_sub_category_id',
                'courses.course_category_id', 'courses.course_type', 'courses.image_id', 'courses.slug_url', 'courses.is_free', 'courses.price', 'courses.discount_price',
                'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment'
            )
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }])
            ->whereBetween('completed_percentage', [0, 99])
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getPopularCoursesCategoryBased($user_id, ?CourseCategory $category = null)
    {

        $query = $this->getModel()->newQuery();
        $user = $query->select(
            'id',
            "first_name",
            "last_name",
            'activation',
            'image_id',
            'type',
        )->find($user_id);

        $user_courses = $user->course_enrollments()->pluck('courses.id');

        $courses = $category?->coursesAssignWithSub()
            ->select(
                'courses.id', 'courses.created_at', 'courses.name', 'courses.brief', 'courses.level', 'courses.timing', 'courses.course_sub_category_id', 'courses.course_category_id', 'courses.course_type', 'courses.image_id', 'courses.slug_url', 'courses.is_free', 'courses.price', 'courses.discount_price',
                'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment'
            )
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user_id) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user_id);
                }])
            ->orderBy('agg_count_course_enrollment', 'desc')
            ->WhereNotIn('courses.id', $user_courses)
            ->active()
            ->limit(10);

        if (!empty($category)) {
            $query = $query->where(function ($q) use ($category) {
                $q->where('course_sub_category_id', $category->id)
                    ->orWhere('course_category_id', $category->id);
            });
        }

        return $courses?->get() ?? collect([]);
    }

    public function getUpComingRecommendedCourses($user_id, $limit = 3)
    {
        $lesson = $this->getLastWatchedLesson($user_id);
        if (is_null($lesson)) {
            return [];
        }

        $chapter_lessons = Lesson::where('sort', '>', $lesson->sort)
            ->where('chapter_id', $lesson->chapter_id)
            ->whereHas('course', function ($query) use ($lesson) {
                $query->where('course_type', CourseType::COURSE)
                    ->where('id', $lesson->course_id);
            })
            ->active()
            ->limit($limit)
            ->get();

        if (count($chapter_lessons) == 0) {
            $pre_chapter = Chapter::active()->find($lesson->chapter_id);
            $chapter_lessons = Chapter::where([
                'course_id' => $lesson->course_id,
                ['sort', '>', $pre_chapter->sort]
            ])->active()->orderBy('sort', 'ASC')->first();
            if (empty($chapter_lessons)) {
                return [];
            }
            $chapter_lessons = $chapter_lessons->lessons()
                ->active()
                ->limit($limit)
                ->get();
        }

        return $chapter_lessons;
    }

    /**
     * subscribed bundle
     */
    public function getUserBundles($user_id, ?User $user = null)
    {
        $query = $this->getModel()->newQuery();

        if (!$user) {
            $user = $query->find($user_id)->load([
                'active_subscription' => function ($query) {
                    $query->with('package');
                }]);
        }

        $packages = collect([]);

        $has_bundle_courses = $user->active_subscription->filter(function ($subscription) {
                return $subscription->package?->access_type == AccessType::COURSES;
            })->count() > 0;

        if (!empty($has_bundle_courses)) {
            $packages = $packages->merge($user->active_subscription->filter(function ($subscription) {
                return $subscription->package?->access_type == AccessType::COURSES;
            }));
        }

        $has_bundle_categories = $user->active_subscription->filter(function ($subscription) {
                return $subscription->package?->access_type == AccessType::COURSE_CATEGORY;
            })->count() > 0;

        if (!empty($has_bundle_categories)) {
            $packages = $packages->merge($user->active_subscription->filter(function ($subscription) {
                return $subscription->package?->access_type == AccessType::COURSE_CATEGORY;
            }));
        }

        $courses = Course::ActiveOrHide()
            ->select(SelectColumnTrait::$coursesColumns)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                SelectColumnTrait::$reviewsColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->latest()
            ->get();

        $packages->map(function ($subscription) use ($courses) {
            if (gettype($subscription->package?->access_id) == 'array') {
                $subscription->package->loaded_courses = $courses->whereIn('id', $subscription->package?->access_id);
            } elseif (gettype(json_decode($subscription->package?->access_id)) == 'array') {
                $subscription->package->loaded_courses = $courses->whereIn('id', json_decode($subscription->package?->access_id));
            } else {
                $subscription->package->loaded_courses = $courses->where('id', $subscription->package?->access_id);
            }
        });

        return $packages->unique('package_id')->all();
    }
    /**
     * Microdegree users
     */

    /**
     * Get Last Watched Course
     *
     * @param $user_id
     * @return Course
     */
    public function getLastWatchedMicrodegree($user_id): ?Course
    {
        $query = $this->getModel()->newQuery();
        $user = $query->find($user_id);
        return $user->course_history()
            ->where('course_type', CourseType::COURSE)
            ->select('courses.id', 'courses.created_at', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price')
            ->with(['image:id,path,full_url,mime_type', 'category', 'cover', 'sub', 'reviews:id,course_id,rate,user_id'])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user_id) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user_id);
                }])
            ->latest()
            ->first();
    }

    /**
     * Get Last Watched Lesson
     *
     * @param $user_id
     * @param $micro_degree_id
     * @return Lesson
     */
    public function getLastWatchedLessonMicrodegree($user_id, $micro_degree_id): ?Lesson
    {
        $query = $this->getModel()->newQuery();
        $user = $query->find($user_id);
        return $user->lesson_history()
            ->whereHas('course', function ($query) use ($micro_degree_id) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->where('id', $micro_degree_id);
            })
            ->latest('watching_histories.created_at')
            ->first();
    }

    /**
     * @param     $user_id
     * @param     $micro_degree_id
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLastActivitiesMicrodegrees($user_id, $micro_degree_id, $days = 7)
    {
        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);
        $query = $query->where('created_at', '>=', now()->subDays($days))
            ->whereHas('course', function ($query) use ($micro_degree_id) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->where('id', $micro_degree_id);
            })
            ->groupBy('lesson_id');
        $query = $query->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date'  => $date,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }

    /**
     * @param $user_id
     * @param $micro_degree_id
     * @return int
     */
    public function countWatchedVideosTodayMicrodegrees($user_id, $micro_degree_id)
    {
        $query = (new WatchingHistory)->newQuery();
        $query = $query->where('user_id', $user_id);
        $query = $query->whereDate('created_at', date('Y-m-d'))
            ->whereHas('course', function ($query) use ($micro_degree_id) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->where('id', $micro_degree_id);
            })
            ->distinct('lesson_id');
        return $query->count();
    }

    /**
     * @param     $user_id
     * @param     $micro_degree_id
     * @param int $limit
     * @return array
     */
    public function getUpComingRecommendedMicrodegree($user_id, $micro_degree_id, $limit = 3)
    {
        $lesson = $this->getLastWatchedLessonMicrodegree($user_id, $micro_degree_id);
        if (is_null($lesson)) {
            return [];
        }

        $chapter_lessons = Lesson::where('sort', '>', $lesson->sort)
            ->whereHas('course', function ($query) use ($micro_degree_id) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->where('id', $micro_degree_id);
            })
            ->active()
            ->limit($limit)
            ->get();

        if (count($chapter_lessons) == 0) {
            $pre_chapter = Chapter::find($lesson->chapter_id);
            $chapter_lessons = Chapter::where([
                'course_id' => $micro_degree_id,
                ['sort', '>', $pre_chapter->sort]
            ])->first();
            if (empty($chapter_lessons)) {
                return [];
            }
            $chapter_lessons = $chapter_lessons->lessons()
                ->active()
                ->limit($limit)
                ->get();
        }

        return $chapter_lessons;
    }

    /**
     * @param $user_id
     * @param $micro_degree_id
     * @return array
     */
    public function getMicrodegreeCompletionProgress($user_id, $micro_degree_id)
    {
        $query_watching_history = (new WatchingHistory)->newQuery();
        $query_watching_history = $query_watching_history->where('user_id', $user_id);
        $query_watching_history = $query_watching_history->whereHas('course', function ($query) use ($micro_degree_id) {
            $query->where('course_type', CourseType::MICRODEGREE)
                ->where('id', $micro_degree_id);
        })
            ->distinct('lesson_id');
        $watched_lessons = $query_watching_history->count();

        $total_lessons = Lesson::active()->where(['course_id' => $micro_degree_id, 'type' => LessonType::VIDEO])->count();

        return [
            'watched_lessons' => $watched_lessons,
            'total_lessons'   => $total_lessons,
            'completion'      => $total_lessons > 0 ? $watched_lessons / $total_lessons * 100 : 0
        ];
    }

    /**
     * Others
     */

    /**
     * Get Completed courses with certificates
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedCourses(User $user)
    {
        return $user->completed_courses()->latest()->get();
    }

    /**
     * Get Completed learnPaths with certificates
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedLearnPaths(User $user)
    {
        return $user->completed_learn_paths()->latest()->get();
    }

    /**
     * Get Completed course by id with certificates
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedCourseById(User $user, $certificate_id)
    {
        return $user->completed_courses()->where('certificate_id', $certificate_id)->latest()->first();
    }

    /**
     * Get payments and transaction history
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsHistory(User $user)
    {
        return $user->transactions()->latest()->get();
    }

    public function getPurchasedCourses(User $user, $limit = 8)
    {
        // get user's purchased indevidual package
        $courses_ids = [];

        $indevidual_courses_subscription = $user->active_subscription;

        foreach ($indevidual_courses_subscription as $subscription) {
            $package = $subscription->package;
            $courses_ids[] = !is_array($package?->access_id) ? array_flatten(json_decode($package?->access_id, 1)) : $package?->access_id;
        }
        $courses_ids = array_flatten($courses_ids);
        if (count($courses_ids) > 0) {
            return Course::whereIn('id', $courses_ids)->with(['image', 'category', 'cover', 'sub', 'reviews'])
                ->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }])
                ->latest()->limit($limit)->get();
        }
        return [];
    }

    public function getCoursesWithLabs(User $user)
    {
        $courses = $user->courses()->active()->whereHas('lessons', function ($query) {
            $query->where('type', LessonType::LAB);
        })
            ->latest()
            ->get();

        return $courses;
    }

    public function lessonsCompletedForOneMonth(User $user)
    {
        $query = WatchedLesson::whereBetween('created_at', [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            })
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('j/n');
        });

        $months = [];
        $lessons = [];

        foreach ($grouped_data as $date => $items) {
            array_push($months, $date);
            array_push($lessons, $items->count());
        }

        $lessons_completed = new stdClass();
        $lessons_completed->months = $months;
        $lessons_completed->lessons = $lessons;

        return $lessons_completed;
    }

    public function minsWatchedThisWeek(User $user)
    {
        $query = WatchHistoryTime::whereBetween('created_at', [Carbon::now()->subDays(6)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            })
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('D');
        });

        $days = [];
        $minutes = [];

        foreach ($grouped_data as $date => $items) {
            array_push($days, $date);
            array_push($minutes, ceil($items->sum('watched_time') / 60));
        }

        $mins_watched = new stdClass();
        $mins_watched->days = $days;
        $mins_watched->minutes = $minutes;

        return $mins_watched;
    }

    public function totalLessonsWatched($user)
    {

        $query = (new WatchedLesson)->newQuery();
        $query = $query->where('user_id', $user->id)
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            });
        return $query->count();
    }

    public function totalMinsWatched($user)
    {
        $seconds_watched = WatchHistoryTime::where('user_id', $user->id)
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            })
            ->sum('watched_time');
        $mins_watched = $seconds_watched ? ceil($seconds_watched / 60) : 0;

        return $mins_watched;
    }

    public function totalEnrolledCourses($user)
    {
        $query = CourseEnrollment::where('user_id', $user->id)->whereHas('course', function ($query) {
            $query->where('activation', CourseActivationStatus::ACTIVE)
                ->where('course_type', CourseType::COURSE);
        })->distinct('course_id');
        return $query->count();
    }

    public function totalCompletedCourses($user)
    {
        $query = CompletedCourses::where('user_id', $user->id)
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE)
                    ->where('course_type', CourseType::COURSE);
            });
        return $query->count();
    }

    public function countWatchedVideosToday($user_id)
    {
        $query = (new WatchedLesson)->newQuery();
        $query = $query->where('user_id', $user_id)
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            });
        $query = $query->whereDate('created_at', date('Y-m-d'))
            ->distinct('lesson_id');
        return $query->count();
    }

    public function countWatchedVideosThisWeek($user_id)
    {
        $query = (new WatchedLesson)->newQuery();
        $query = $query->where('user_id', $user_id)
            ->whereHas('course', function ($query) {
                $query->where('activation', CourseActivationStatus::ACTIVE);
            })
            ->whereHas('lesson', function ($query) {
                $query->where('activation', LessonActivationStatus::ACTIVE);
            });
        $query = $query->whereBetween('created_at', [Carbon::now()->subDays(6)->toDateTimeString(), Carbon::now()->toDateTimeString()])
            ->distinct('lesson_id');
        return $query->count();
    }

    public function getFreeCoursesForUser($user)
    {
        //        $query = $this->getModel()->newQuery();
        //        $user = $query->find($user->id);

        $user_courses = $user->course_enrollments()->pluck('courses.id');
        $courses = Course::where('is_free', 1)
            ->select(
                'courses.id', 'courses.created_at', 'courses.name', 'courses.brief', 'courses.level', 'courses.timing', 'courses.course_sub_category_id', 'courses.course_category_id', 'courses.course_type', 'courses.image_id', 'courses.slug_url', 'courses.is_free', 'courses.price', 'courses.discount_price', 'courses.activation',
                'agg_avg_reviews', 'agg_count_reviews', 'agg_count_course_enrollment' ,'agg_lessons'
            )
            ->where('courses.activation', 1)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->with([
                'completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }])
            ->orderBy('agg_count_course_enrollment', 'desc')
            ->whereNotIn('courses.id', $user_courses)
            ->limit(10)
            ->get();

        return $courses;
    }

    public function getUpcomingLessons($lastLesson, $take)
    {
        if (!$lastLesson) {
            return null;
        }
        $nextLessons = Lesson::where([
            "course_id"  => $lastLesson->course_id,
            "chapter_id" => $lastLesson->chapter_id,

            ['sort', '>', $lastLesson->sort]
        ])->whereHas('course', function ($query) {
            $query->where('course_type', CourseType::MICRODEGREE);
        })->active()->orderBy('sort', 'ASC')->take($take)->get();

        if (!$nextLessons or $nextLessons->count() < $take) {

            $currentChapter = Chapter::active()->find($lastLesson->chapter_id);
            $nextChapter = Chapter::where(['course_id' => $currentChapter->course_id, ['sort', '>', $currentChapter->sort]])->active()->orderBy('sort', 'ASC')->first();

            $countLessons = $nextLessons->count() > 0 ? $nextLessons->count() : 0;
            if ($nextChapter) {
                $nextLessons = Lesson::where([
                    "course_id"  => $nextChapter->course_id,
                    "chapter_id" => $nextChapter->id,
                    "type"       => LessonType::VIDEO,
                    ['sort', '>', 0]
                ])->whereHas('course', function ($query) {
                    $query->where('course_type', CourseType::MICRODEGREE);
                })->active()->take($take)->get();
                if (!$nextLessons) {
                    return null;
                }
            }
        }

        return $nextLessons;
    }

    public function getActivitiesMonthly(User $user, Course $course)
    {
        $watchLessons = WatchedLesson::where([
            'user_id'   => $user->id,
            'course_id' => $course->id
        ])
            ->selectRaw("COUNT(watched_lessons.id) as totalLessons, DATE_FORMAT(watched_lessons.created_at, '%M') AS month")
            ->groupByRaw("DATE_FORMAT(watched_lessons.created_at, '%m-%Y')")
            ->get();
        return $watchLessons;
    }

    public function findByMail(string $email): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->getModel()->where('email', $email)->first();
    }
}
