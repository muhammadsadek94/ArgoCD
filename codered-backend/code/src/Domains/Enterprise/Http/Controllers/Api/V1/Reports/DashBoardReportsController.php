<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\Reports;

use App\Domains\Enterprise\Features\Api\V1\Reports\CourseCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseScoreReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\DashBoardReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserScoreReportFeature;
use INTCore\OneARTFoundation\Http\Controller;

class DashBoardReportsController extends Controller
{
    /**
     * Get Users
     */
    public function dashBoard()
    {
        return $this->serve(DashBoardReportFeature::class);
    }


}
