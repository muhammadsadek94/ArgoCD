<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Auth;

use App\Domains\User\Features\Api\V2\User\ForgetPassword\ResetPasswordFeature;
use App\Domains\User\Features\Api\V2\User\ForgetPassword\SendForgetPasswordCodeFeature;
use App\Domains\User\Features\Api\V2\User\ForgetPassword\ValidateForgetPasswordCodeFeature;
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
