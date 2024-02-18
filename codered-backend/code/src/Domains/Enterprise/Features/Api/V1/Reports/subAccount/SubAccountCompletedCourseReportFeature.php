<?php

namespace App\Domains\Enterprise\Features\Api\V1\Reports\subAccount;

use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\SubAccount\EnterpriseSubAccountReportsCollection;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\SubaccountTableStatisticsJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportChartJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserTableStatisticsJob;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\GetSubAccountsJob;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use Illuminate\Support\Facades\Cache;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class SubAccountCompletedCourseReportFeature extends Feature
{
    const CACHE_TTL = 86400;

    public function handle( UserEnterpriseRepository $user_repository , Request $request)
    {
        $admin = auth()->user();
//        return Cache::remember("enterprise_dashboard_completed_course_{$admin->id}" , self::CACHE_TTL, function () use ($admin, $user_repository, $request) {
            $users = $this->run(GetSubAccountsJob::class, [
                'request'=> $request,
                'sortBy'    => 'count(completed_courses.id)',
                'table'    => 'completed_courses'
            ]);
            $table_statistics =  $this->run(SubaccountTableStatisticsJob::class, [
                'request'=> $request
            ]);
            $user_chart = $user_repository->getEnterpriseUsersCharts($request,$admin->id);

            $completed_Course_chart = $user_repository->getEnterpriseCompletedCourseCharts($request,$admin->id);
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'users' => new EnterpriseSubAccountReportsCollection($users),
                    "table_statistics" => $table_statistics,
                    'charts' => [
                        "user_chart" => $user_chart,
                        "completed_Course_chart" => $completed_Course_chart,
                    ]
                ]
            ]);
//        });
    }
}
