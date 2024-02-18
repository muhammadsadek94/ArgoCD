<?php

namespace App\Domains\Enterprise\Features\Api\V1\Reports;

use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportChartJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserTableStatisticsJob;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use Illuminate\Support\Facades\Cache;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class UserScoreReportFeature extends Feature
{
    const CACHE_TTL = 86400;

    public function handle( UserEnterpriseRepository $user_repository , Request $request)
    {
        $admin = auth()->user();

//        return Cache::remember("enterprise_dashboard_users_score_{$admin->id}" , self::CACHE_TTL, function () use ($admin, $user_repository, $request) {
            $users = $this->run(UserReportJob::class, [
                'request'=> $request,
//                'sortBy'    => 'avg (completed_courses.degree)',
//                'table'    => 'completed_courses'
            ]);
            $user_chart = $user_repository->getEnterpriseUsersCharts($request,$admin->id);

            $table_statistics =  $this->run(UserTableStatisticsJob::class, [
                'request'=> $request
            ]);
            $completion_rate_charts = $user_repository->getEnterpriseScoreCharts($request,$admin->id);
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'users' => new EnterpriseUserReportsCollection($users),
                    "table_statistics" => $table_statistics,
                    'charts' => [
                        "user_chart" => $user_chart,
                        "completion_rate_charts" => $completion_rate_charts,
                    ]
                ]
            ]);
//        });

    }
}