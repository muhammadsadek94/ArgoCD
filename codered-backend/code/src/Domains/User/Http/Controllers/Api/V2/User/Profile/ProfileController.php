<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Profile;

use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V2\User\Profile\UserProFeature;
use App\Domains\User\Features\Api\V2\User\Profile\UserDetailsFeature;
use App\Domains\User\Features\Api\V2\User\Profile\UseVoucherFeature;
use App\Domains\User\Features\Api\V2\User\Profile\UpdateProfileFeature;
use App\Domains\User\Features\Api\V2\User\Profile\ProUserStatisticsFeature;
use App\Domains\User\Features\Api\V2\User\Profile\ProUserHomeFeature;
use App\Domains\User\Features\Api\V2\User\Profile\ProUserDashboardFeature;
use App\Domains\User\Features\Api\V2\User\Profile\Payments\RequestCancelSubscriptionFeature;
use App\Domains\User\Features\Api\V2\User\Profile\Payments\GetInvoicesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\NonProUserHomeFeature;
use App\Domains\User\Features\Api\V2\User\Profile\MyCoursesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\MicrodegreeUserDashboardFeature;
use App\Domains\User\Features\Api\V2\User\Profile\EnrolledInMicroDegreesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\EnrolledInCoursesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\Course\GetInterestsCategoriesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\Course\GetCompletedCoursesFeature;
use App\Domains\User\Features\Api\V2\User\Profile\ChangePasswordFeature;
use App\Domains\User\Features\Api\V2\User\Device\UpdateDeviceInfoFeature;
use App\Domains\User\Features\Api\V1\User\Profile\SetDailyGoalFeature;
use App\Domains\User\Features\Api\V1\User\Profile\SetSelectedDaysFeature;
use App\Domains\User\Features\Api\V1\User\Profile\SetWeeklyTargetFeature;

class ProfileController extends Controller
{

    /**
     * get user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        return $this->serve(UserDetailsFeature::class);
    }

    /**
     * Update user basic info
     */
    public function patchProfile()
    {
        return $this->serve(UpdateProfileFeature::class);
    }

    /**
     * Update User's password
     */
    public function postChangePassword()
    {
        return $this->serve(ChangePasswordFeature::class);
    }

    /**
     * Update user's device info
     */
    public function patchDeviceInfo()
    {
        return $this->serve(UpdateDeviceInfoFeature::class);
    }

    public function postDailyTarget()
    {
        return $this->serve(SetDailyGoalFeature::class);
    }

    public function postWeeklyTarget()
    {
        return $this->serve(SetWeeklyTargetFeature::class);
    }

    public function postSelectedDays()
    {
        return $this->serve(SetSelectedDaysFeature::class);
    }

    public function getProUserDashboard()
    {
        return $this->serve(ProUserDashboardFeature::class);
    }

    public function getProUserHome()
    {
        return $this->serve(ProUserHomeFeature::class);
    }

    public function getNonProUserHome()
    {
        return $this->serve(NonProUserHomeFeature::class);
    }

    public function getMyCourses()
    {
        return $this->serve(MyCoursesFeature::class);
    }

    

    public function getProUserStatistics()
    {
        return $this->serve(ProUserStatisticsFeature::class);
    }

    public function getMicroDegreeUserDashboard()
    {
        return $this->serve(MicrodegreeUserDashboardFeature::class);
    }

    public function getEnrolledInMicrodegree()
    {
        return $this->serve(EnrolledInMicroDegreesFeature::class);
    }

    public function getEnrolledInCourses()
    {
        return $this->serve(EnrolledInCoursesFeature::class);
    }

    public function getInterestsCategories()
    {
        return $this->serve(GetInterestsCategoriesFeature::class);
    }

    public function getCompletedCourses()
    {
        return $this->serve(GetCompletedCoursesFeature::class);
    }

    public function getInvoices()
    {
        return $this->serve(GetInvoicesFeature::class);
    }

    public function postCancelSubscriptionRequest()
    {
        return $this->serve(RequestCancelSubscriptionFeature::class);
    }

    public function userPro() {
        return $this->serve(UserProFeature::class);
    }

}
