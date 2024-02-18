<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor\Profile;

use App\Domains\User\Features\Api\V1\Instructor\Profile\ChangePasswordFeature;
use App\Domains\User\Features\Api\V1\Instructor\Profile\InstructorProfileFeature;
use App\Domains\User\Features\Api\V1\Instructor\Profile\UpdateBankInfoFeature;
use App\Domains\User\Features\Api\V1\Instructor\Profile\UpdateProfileFeature;
use INTCore\OneARTFoundation\Http\Controller;

class ProfileController extends Controller
{

    /**
     * get user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        return $this->serve(InstructorProfileFeature::class);
    }

    /**
     * Update profile information
     * @return mixed
     */
    public function patchProfile()
    {
        return $this->serve(UpdateProfileFeature::class);
    }

    /**
     * Update bank information
     * @return mixed
     */
    public function patchBankInfo()
    {
        return $this->serve(UpdateBankInfoFeature::class);
    }

    /**
     * Update User's password
     */
    public function postChangePassword()
    {
        return $this->serve(ChangePasswordFeature::class);
    }




}
