<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor\Auth;

use App\Domains\User\Features\Api\V1\Instructor\ForgetPassword\ResetPasswordFeature;
use App\Domains\User\Features\Api\V1\Instructor\ForgetPassword\SendForgetPasswordCodeFeature;
use App\Domains\User\Features\Api\V1\Instructor\ForgetPassword\ValidateForgetPasswordCodeFeature;
use INTCore\OneARTFoundation\Http\Controller;

class ForgetPasswordController extends Controller
{

    /**
     * Request send reset password
     */
    public function postSendResetPasswordCode()
    {
        return $this->serve(SendForgetPasswordCodeFeature::class);
    }

    /**
     * Validate code
     */
    public function postValidateCode()
    {
        return $this->serve(ValidateForgetPasswordCodeFeature::class);
    }

    /**
     * Update Password
     */
    public function postResetassword()
    {
        return $this->serve(ResetPasswordFeature::class);
    }
}
