<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\Reports;

use App\Domains\Enterprise\Features\Api\V1\Reports\UserCompletedCourseReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\UserScoreReportFeature;
use INTCore\OneARTFoundation\Http\Controller;

class UserReportsController extends Controller
{
    /**
     * Get Users
     */
    public function users()
    {
        return $this->serve(UserReportFeature::class);
    }

    public function usersCompletionRate()
    {
        return $this->serve(UserCompletionRateReportFeature::class);
    }

    public function usersCompletedCourse()
    {
        return $this->serve(UserCompletedCourseReportFeature::class);
    }

    public function usersEnrollment()
    {
        return $this->serve(UserEnrollmentReportFeature::class);
    }

    public function usersScore()

    {
        return $this->serve(UserScoreReportFeature::class);
    }




    public function show()
    {
    }

    public function update()
    {
    }
    public function deleteAll()
    {
    }


}
