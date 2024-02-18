<?php

namespace App\Domains\Enterprise\Features\Api\V1\Reports;

use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportChartJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserTableStatisticsJob;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class UserCompletedCourseReportFeature extends Feature
{

    public function handle( UserEnterpriseRepository $user_repository , Request $request)
    {
        $admin = auth()->user();
        $users = $this->run(UserReportJob::class, [
            'request'=> $request,
//            'sortBy'    => 'count(completed_courses.id)',
//            'table'    => 'completed_courses'
        ]);
        $table_statistics =  $this->run(UserTableStatisticsJob::class, [
            'request'=> $request
        ]);
        $user_chart = $user_repository->getEnterpriseUsersCharts($request,$admin->id);

        $completed_Course_chart = $user_repository->getEnterpriseCompletedCourseCharts($request,$admin->id);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'users' => new EnterpriseUserReportsCollection($users),
                "table_statistics" => $table_statistics,
                'charts' => [
                    "user_chart" => $user_chart,
                    "completed_Course_chart" => $completed_Course_chart,
                ]
            ]
        ]);
    }
}
