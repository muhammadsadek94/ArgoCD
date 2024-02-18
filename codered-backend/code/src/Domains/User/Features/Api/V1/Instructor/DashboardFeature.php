<?php

namespace App\Domains\User\Features\Api\V1\Instructor;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V1\Instructor\CourseStateBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Instructor\VideoAnalysisResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\Lesson;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\Instructor\Payout;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\PayoutRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use DB;
use Log;

class DashboardFeature extends Feature
{
    public function handle(Request $request, PayoutRepository $payout_repository)
    {
        $user = $request->user('api');
        $total_royalties_quarter = number_format($payout_repository->getLastQuarterAmount($user->id));
        $total_mins_watched = number_format($this->getInstructorViewedMins($user->id));
        $royalties_from_individual_course = $this->getRoyaltiesCourses($user->id);
        $royalties_from_individual_bundles = $this->getRoyaltiesBundle($user->id);
        $total_royalties_active = $payout_repository->getTotalPayouts($user->id);
        $total_royalties = $total_royalties_active + $royalties_from_individual_bundles + $royalties_from_individual_course;
        $courses_views = $this->getCoursesViews($user->id);

        $total_enrolled = $this->getTotalEnrollments($user->id);
        $total_views = $this->getTotalViews($user->id);
        $enroll_growth = $this->getTotalEnrollmentsGrowth($user->id);
        $views_growth = $this->getTotalViewsGrowth($user->id);
        $video_analysis = $this->geLatestInstructorLessons($user->id);

        $stats_by_course = Course::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('instructors', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
        })->active()->latest()->get();

        $stats_by_course_basic_info = CourseStateBasicInfoResource::collection($stats_by_course);
        $stats_by_course_basic_data = collect($stats_by_course_basic_info)->sortByDesc('min_views')->values();
        $VideoAnalysisResource = VideoAnalysisResource::collection($video_analysis);
        $video_analysis_data = collect($VideoAnalysisResource)->sortByDesc('min_views')->values();

        $total_advances = number_format($this->getTotalAdvances($user->id));
        $total_royalties_v2 = number_format($payout_repository->getNewTotalRoyalties($user->id));
        $last_quarter_royalties_v2 = number_format($payout_repository->getNewLastQuarterAmount($user->id));

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'total_royalties'                   => number_format($total_royalties),
                'total_royalties_active'            => number_format($total_royalties_active),
                'royalties_from_individual_course'  => number_format($royalties_from_individual_course),
                'royalties_from_individual_bundles' => number_format($royalties_from_individual_bundles),
                'total_royalties_quarter'           => $total_royalties_quarter,
                'total_mins_watched'                => $total_mins_watched,
                'courses_views'                     => $courses_views,
                'state_by_course'                   => $stats_by_course_basic_data,
                'total_enrolled'                    => $total_enrolled,
                'total_views'                       => $total_views,
                'enroll_growth'                     => $enroll_growth,
                'views_growth'                      => $views_growth,
                'video_analysis'                    => $video_analysis_data,
                'total_royalties_v2'                => $total_royalties_v2,
                'last_quarter_royalties_v2'         => $last_quarter_royalties_v2,
                'total_advances_v2'                 => $total_advances,
            ]
        ]);

    }

    public function getTotalAdvances(string $instructor_id): int
    {
        return Course::where('user_id', $instructor_id)
            ->sum("advances");
    }

    /**
     * get instructor watched mins
     * TODO: refactor into repository
     *
     * @param string $instructor_id
     * @return float|int
     */
    private function getInstructorViewedMins(string $instructor_id)
    {
        $all_views_instructors = WatchHistoryTime::where([
            'subscription_type' => SubscribeStatus::ACTIVE,
            'course_type'       => CourseType::COURSE
        ])->whereHas('course', function ($query) use ($instructor_id) {
            $query->where('user_id', $instructor_id);
        })->get();
        $total_watched_seconds_instructor = 0;
        foreach ($all_views_instructors as $view_lesson) {
            $total_watched_seconds_instructor += $view_lesson->watched_time;
        }

        $total_watched_mins_instructor = $total_watched_seconds_instructor / 60;
        return (int) $total_watched_mins_instructor;
    }

    public function getCoursesViews($instructor_id)
    {
        $courses_ids = Course::where('user_id', $instructor_id)->pluck('id')->toArray();

        $views = WatchHistoryTime::whereIn('course_id', $courses_ids)
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->where('course_type', CourseType::COURSE)
            ->where('subscription_type', SubscribeStatus::ACTIVE)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function ($row) {
                return $row->created_at->format('d M');
            })->map(function ($rows) {
                return count($rows);
            });

        $views_parsed_data = [];

        foreach ($views as $key => $value) {
            $views_parsed_data[] = [
                'name'  => $key,
                'value' => $value
            ];
        }

        return $views_parsed_data;
    }

    private function getTotalEnrollments($instructor_id): int
    {
        $courses_ids = Course::where('user_id', $instructor_id)->pluck('id')->toArray();

        return User::join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
            ->where('users.enterprise_id', null)
            ->join('course_enrollment', 'users.id', '=', 'course_enrollment.user_id')
            ->where('user_subscriptions.status', SubscribeStatus::ACTIVE) // Paid subscription
            ->whereRaw('user_subscriptions.created_at <= course_enrollment.created_at')
            ->whereRaw('user_subscriptions.expired_at >= course_enrollment.created_at')
            ->whereIn('course_enrollment.course_id', $courses_ids)
            ->count('course_enrollment.id');

    }

    private function getRoyaltiesBundle($instructor_id): int
    {
        return Payout::where('user_id', $instructor_id)->sum('royalties_bundles');
    }

    private function getRoyaltiesCourses($instructor_id): int
    {
        return Payout::where('user_id', $instructor_id)->sum('royalties_courses');
    }

    private function getTotalViews($instructor_id): int
    {
        return WatchHistoryTime::where('course_type', CourseType::COURSE)
            ->where('subscription_type', SubscribeStatus::ACTIVE)
            ->where('instructor_id', $instructor_id)
            ->whereHas('user', function ($query) {
                return $query->whereNull('enterprise_id');
            })
            ->select(
                DB::raw('count(DISTINCT user_id, lesson_id) as count'))
            ->first()->count;
    }

    private function getTotalEnrollmentsGrowth($instructor_id): int
    {
        $courses_ids = Course::where('user_id', $instructor_id)->pluck('id')->toArray();

        $last_30_day = Carbon::now()->subMonth()->format('Y-m-d');
        $last_60_day = Carbon::now()->subMonths(2)->format('Y-m-d');

        $current = User::join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
            ->where('users.enterprise_id', null)
            ->join('course_enrollment', 'users.id', '=', 'course_enrollment.user_id')
            ->where('user_subscriptions.status', SubscribeStatus::ACTIVE) // Paid subscription
            ->whereRaw('user_subscriptions.created_at <= course_enrollment.created_at')
            ->whereRaw('user_subscriptions.expired_at >= course_enrollment.created_at')
            ->where('course_enrollment.created_at', '>', $last_30_day)
            ->whereIn('course_enrollment.course_id', $courses_ids)
            ->count('course_enrollment.id');

        $last = User::join('user_subscriptions', 'users.id', '=', 'user_subscriptions.user_id')
            ->where('users.enterprise_id', null)
            ->join('course_enrollment', 'users.id', '=', 'course_enrollment.user_id')
            ->where('user_subscriptions.status', SubscribeStatus::ACTIVE) // Paid subscription
            ->whereRaw('user_subscriptions.created_at <= course_enrollment.created_at')
            ->whereRaw('user_subscriptions.expired_at >= course_enrollment.created_at')
            ->whereBetween('course_enrollment.created_at', [$last_60_day, $last_30_day])
            ->whereIn('course_enrollment.course_id', $courses_ids)
            ->count('course_enrollment.id');

        return $this->getIncrementPercentage($current, $last);


    }

    private function getTotalViewsGrowth($instructor_id): int
    {
        $courses_ids = Course::where('user_id', $instructor_id)->pluck('id')->toArray();
        $last_30_day = Carbon::now()->subMonth()->format('Y-m-d');
        $last_60_day = Carbon::now()->subMonths(2)->format('Y-m-d');

        $current = WatchHistoryTime::whereIn('course_id', $courses_ids)
            ->where('subscription_type', SubscribeStatus::ACTIVE)
            ->whereHas('user', function ($query) {
                return $query->whereNull('enterprise_id');
            })
            ->where('created_at', '>', $last_30_day)
            ->count();
        $last = WatchHistoryTime::whereIn('course_id', $courses_ids)
            ->where('subscription_type', SubscribeStatus::ACTIVE)
            ->whereHas('user', function ($query) {
                return $query->whereNull('enterprise_id');
            })
            ->whereBetween('created_at', [$last_60_day, $last_30_day])
            ->count();

        return $this->getIncrementPercentage($current, $last);

    }

    private function getIncrementPercentage(int $new_number, int $original_number): int
    {
        if ($original_number == 0) return 0;
        $increase = $new_number - $original_number;
        $increase_percentage = $increase / $original_number * 100;

        return $increase_percentage;
    }

    private function geLatestInstructorLessons($instructor_id)
    {
        return Lesson::where('type', LessonType::VIDEO)
            ->whereHas('course', function ($query) use ($instructor_id) {
                return $query->where('user_id', $instructor_id)->active();
            })->with('course.sub')->with('course.category')
            ->limit(500)
            ->get();
    }

}
