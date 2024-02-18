<?php

namespace App\Domains\Enterprise\Features\Api\V1\Reports;

use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseCourseReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\Reports\EnterpriseUserReportsDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\CourseReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\CourseTableStatisticsJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportChartJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserReportJob;
use App\Domains\Enterprise\Jobs\Api\V1\Reports\UserTableStatisticsJob;
use App\Domains\Enterprise\Repositories\CourseEnterpriseRepository;
use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\ResourceCollection;


class CourseCompletionRateReportFeature extends Feature
{

    public function handle(CourseEnterpriseRepository $courseEnterpriseRepository, UserEnterpriseRepository $user_repository, Request $request)

            {
        $admin = auth()->user();
        $courses = $this->run(CourseReportJob::class, [
            'request' => $request,
            'sortBy' => 'avg (completed_course_percentages.completed_percentage)',
            'table'    => 'completed_course_percentages'


        ]);
        $table_statistics =  $this->run(CourseTableStatisticsJob::class, [
            'request'=> $request
        ]);
        $user_chart = $user_repository->getEnterpriseUsersCharts($request,$admin->id);
        $course_chart = $user_repository->getEnterpriseCoursesCharts($request, $admin->id);

        $completion_rate_charts = $courseEnterpriseRepository->getEnterpriseCompletionRateCharts($request,$admin->id);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'courses' => new EnterpriseCourseReportsCollection($courses),
                "table_statistics" => $table_statistics,
                'charts' => [
                    "course_chart" => $course_chart,
                    "completion_rate_charts" => $completion_rate_charts,
                ]
            ]
        ]);
    }
}
