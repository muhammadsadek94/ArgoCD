<?php

namespace App\Domains\User\Http\Controllers\Api\V1\User\Profile;

use App\Domains\Course\Features\Api\V1\User\GetUserCertificates;
use App\Domains\User\Features\Api\V1\User\Profile\CheckPasswordFeature;
use App\Domains\User\Features\Api\V1\User\Profile\UseVoucherFeature;
use App\Domains\User\Features\Api\V1\User\Profile\SetDailyGoalFeature;
use App\Domains\User\Features\Api\V1\User\Profile\UpdateProfileFeature;
use App\Domains\User\Features\Api\V1\User\Profile\ChangePasswordFeature;
use App\Domains\User\Features\Api\V1\User\Profile\UserDetailsFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V1\User\Device\UpdateDeviceInfoFeature;
use App\Domains\User\Features\Api\V1\User\Profile\ProUserDashboardFeature;
use App\Domains\User\Features\Api\V1\User\Profile\EnrolledInCoursesFeature;
//use App\Domains\User\Features\Api\V1\User\Profile\Payments\GetInvoicesFeature;
use App\Domains\User\Features\Api\V1\User\Profile\EnrolledInMicroDegreesFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetCompletedCoursesFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetCoursesWithLabsFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetInterestsCategoriesFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetMightLikeLearnPathsFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetPurchasedCoursesFeature;
use App\Domains\User\Features\Api\V1\User\Profile\Course\GetPurchasedLearnPathsFeature;
use App\Domains\User\Features\Api\V1\User\Profile\MicrodegreeUserDashboardFeature;
//use App\Domains\User\Features\Api\V1\User\Profile\Payments\RequestCancelSubscriptionFeature;

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
     * Check User's password
     */
    public function postCheckPassword()
    {
        return $this->serve(CheckPasswordFeature::class);
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

    public function getProUserDashboard()
    {
        return $this->serve(ProUserDashboardFeature::class);
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

   /* public function getInvoices()
    {
        return $this->serve(GetInvoicesFeature::class);
    }*/

   /* public function postCancelSubscriptionRequest()
    {
        return $this->serve(RequestCancelSubscriptionFeature::class);
    }*/

    public function getMightLikeLearnPaths()
    {
        return $this->serve(GetMightLikeLearnPathsFeature::class);
    }

    public function getPurchasedLearnPaths()
    {
        return $this->serve(GetPurchasedLearnPathsFeature::class);
    }

    public function getPurchasedCourses()
    {
        return $this->serve(GetPurchasedCoursesFeature::class);
    }

    public function getCoursesWithLabs()
    {
        return $this->serve(GetCoursesWithLabsFeature::class);
    }

    public function getCertificates()
    {
        return $this->serve(GetUserCertificates::class);
    }

}
