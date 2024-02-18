<?php

namespace App\Domains\Enterprise\Features\Api\V1\Reports;

use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseCourseReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserBasicInfoResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\CourseReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportChartJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserTableStatisticsJob;
use App\Domains\Enterprise\Repositories\CourseEnterpriseRepository;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\User\Enum\UserActivation;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;

use Illuminate\Support\Facades\Cache;

class DashBoardReportFeature extends Feature
{
    const CACHE_TTL = 86400;

    public function handle(UserEnterpriseRepository $user_repository, CourseEnterpriseRepository $course_repository, Request $request)
    {
        $admin = auth()->user();


//        return Cache::remember("v1_3enterprise_dashboard_{$admin->id}", self::CACHE_TTL, function () use ($admin, $user_repository, $course_repository, $request) {

            $user_ids = $user_repository->getUsersQuery($request, $admin->id)->pluck('id');
            $sub_accounts = $user_repository->getEnterpriseSubAccount($request, $admin->id, 100);
//            $sub_accounts = $user_repository->getUsersQuery($request, $admin->id)->get();
            $courses = [];
            $courses = $course_repository->getEnterpriseCourses($request, $admin->id);



            $total_watched_min = $user_repository->getEnterpriseTotalMinWatched($request, $user_ids);
            $total_completed_courses = $user_repository->getEnterpriseTotalCompleted($request, $user_ids);
            $average_score = 0;
            $total_course_enrollments = $user_repository->getTotalCourseEnrollments($user_ids);

            $totalActiveUsers = $user_repository->getEnterpriseTotalUser($request, $admin->id, UserActivation::ACTIVE);
            $totalInActiveUsers = $user_repository->getEnterpriseTotalInactiveUser($request, $admin->id, UserActivation::SUSPEND);
            $totalScoreGrowth = $user_repository->getTotalScoreGrowth($request, $admin->id);
            $totalUserGrowth = $user_repository->getTotalUserGrowth($request, $user_ids);

            $total_users_spent_on_site = collect([]);
            $total_users_spent_on_site = $user_repository->getLastActivitiesForUsers(
                $request,
                $user_ids,
                !is_null($request->last_days) && $request->last_days <= 30 ? $request->last_days : 30
            );


            $total_users_spent_on_site_subAccount1 = collect([]);
            $total_users_spent_on_site_subAccount1 = $user_repository->getLastActivitiesForUsersForSubAccount(
                $request,
                $admin->id,
                $request->sub_account1 ? $request->sub_account1 : (isset($sub_accounts[0]) ? $sub_accounts[0]->id : null),
                $request->time_spent_by_subAccount_users ? $request->time_spent_by_subAccount_users : 30
            );

            $total_users_spent_on_site_subAccount2 = collect([]);
            $total_users_spent_on_site_subAccount2 = $user_repository->getLastActivitiesForUsersForSubAccount(
                $request,
                $admin->id,
                $request->sub_account2 ? $request->sub_account2 : (isset($sub_accounts[1]) ? $sub_accounts[1]->id : null),
                $request->time_spent_by_subAccount_users ? $request->time_spent_by_subAccount_users : 30
            );

            $courseSatisfaction = $user_repository->getEnterpriseCourseSatisfaction($request, $user_ids);
            $top_watched_courses = $user_repository->getEnterpriseTopWatchedCourses($request, $user_ids);
            // $completion_rate_charts = $user_repository->getEnterpriseScoreCharts($request, $admin->id);

            return $this->run(RespondWithJsonJob::class, [
                "content" => [

                    'courses' => new EnterpriseCourseReportsCollection($courses),
                    'sub_accounts' => EnterpriseUserReportsDetailsResource::collection($sub_accounts),
                    "total_watched_min" => (int) $total_watched_min,
                    "average_time_to_finish_course" => 0,
                    "average_score" => 0,
                    "total_completed_courses" => $total_completed_courses,
                    "total_course_enrollments" => $total_course_enrollments,
                    "totalActiveUsers" => (int) $totalActiveUsers,
                    "totalInActiveUsers" => (int) $totalInActiveUsers,
                    "courseSatisfaction" => $courseSatisfaction ? (int) $courseSatisfaction : 0,
                    "totalScoreGrowth" =>  $totalScoreGrowth,
                    "totalUserGrowth" => $totalUserGrowth,
                    'charts' => [
                        "total_users_spent_on_site" => $total_users_spent_on_site,
                        "total_users_spent_on_site_subAccount1" => $total_users_spent_on_site_subAccount1 ? $total_users_spent_on_site_subAccount1 : [],
                        "total_users_spent_on_site_subAccount2" => $total_users_spent_on_site_subAccount2 ? $total_users_spent_on_site_subAccount2 : [],
                        "top_watched_courses" => $top_watched_courses,
                    ]
                ]
            ]);
//        });
    }
}
