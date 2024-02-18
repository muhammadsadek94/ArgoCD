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


class CourseReportFeature extends Feature
{

    public function handle(CourseEnterpriseRepository $courseEnterpriseRepository, UserEnterpriseRepository $user_repository, Request $request)
    {
        $admin = auth()->user();
        $courses = $this->run(CourseReportJob::class , [
            'request' => $request,
            'sortBy' => 'count(course_enrollment.id)',
            'table'    => 'course_enrollment'
        ]);
        $course_chart = $user_repository->getEnterpriseCoursesCharts($request, $admin->id);

        $table_statistics = $this->run(CourseTableStatisticsJob::class , [
            'request' => $request
        ]);
        $min_watched = $courseEnterpriseRepository->getEnterpriseMinsWatchedCharts($request, $admin->id);


        return $this->run(RespondWithJsonJob::class , [
            "content" => [
                'courses' => new EnterpriseCourseReportsCollection($courses),
                "table_statistics" => $table_statistics,
                'charts' => [
                    "course_chart" => $course_chart,
                    "min_watched" => $min_watched,
                ]
            ]
        ]);
    }
}
