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
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class SubAccountScoreReportFeature extends Feature
{

    public function handle( UserEnterpriseRepository $user_repository , Request $request)
    {
        $admin = auth()->user();

        $users = $this->run(GetSubAccountsJob::class, [
            'request'=> $request,
            'sortBy'    => 'avg (completed_courses.degree)',
            'table'    => 'completed_courses'
        ]);
        $user_chart = $user_repository->getEnterpriseUsersCharts($request,$admin->id);

        $table_statistics =  $this->run(SubaccountTableStatisticsJob::class, [
            'request'=> $request
        ]);
        $completion_rate_charts = $user_repository->getEnterpriseScoreCharts($request,$admin->id);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'users' => new EnterpriseSubAccountReportsCollection($users),
                "table_statistics" => $table_statistics,
                'charts' => [
                    "user_chart" => $user_chart,
                    "completion_rate_charts" => $completion_rate_charts,
                ]
            ]
        ]);
    }
}
