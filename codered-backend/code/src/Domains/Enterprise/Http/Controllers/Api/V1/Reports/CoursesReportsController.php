<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\Reports;

use App\Domains\Enterprise\Features\Api\V1\Reports\CourseCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseRatingReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\CourseScoreReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserScoreReportFeature;
use INTCore\OneARTFoundation\Http\Controller;

class CoursesReportsController extends Controller
{
    /**
     * Get Users
     */
    public function courses()
    {
        return $this->serve(CourseReportFeature::class);
    }

    public function coursesCompletionRate()
    {
        return $this->serve(CourseCompletionRateReportFeature::class);
    }


    public function coursesRating()
    {
        return $this->serve(CourseRatingReportFeature::class);
    }

    public function coursesEnrollment()
    {
        return $this->serve(CourseEnrollmentReportFeature::class);
    }

    public function coursesScore()
    {
        return $this->serve(CourseScoreReportFeature::class);
    }


}
