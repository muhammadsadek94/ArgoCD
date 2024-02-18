<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\Reports;

use App\Domains\Enterprise\Features\Api\V1\Reports\subAccount\SubAccountCompletedCourseReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\subAccount\SubAccountCompletionRateReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\subAccount\SubAccountEnrollmentReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\subAccount\SubAccountReportFeature;
use App\Domains\Enterprise\Features\Api\V1\Reports\subAccount\SubAccountScoreReportFeature;
use INTCore\OneARTFoundation\Http\Controller;

class SubAccountReportsController extends Controller
{
    /**
     * Get SubAccounts
     */
    public function SubAccounts()
    {
        return $this->serve(SubAccountReportFeature::class);
    }

    public function SubAccountsCompletionRate()
    {
        return $this->serve(SubAccountCompletionRateReportFeature::class);
    }

    public function SubAccountsCompletedCourse()
    {
        return $this->serve(SubAccountCompletedCourseReportFeature::class);
    }

    public function SubAccountsEnrollment()
    {
        return $this->serve(SubAccountEnrollmentReportFeature::class);
    }

    public function SubAccountsScore()
    {
        return $this->serve(SubAccountScoreReportFeature::class);
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
