<?php

namespace App\Domains\User\Http\Controllers\Api\V1\User\Auth;

use App\Domains\User\Features\Api\V1\User\Activation\ActivateUserFeature;
use App\Domains\User\Features\Api\V1\User\Activation\ResendActivationCodeFeature;
use INTCore\OneARTFoundation\Http\Controller;

class ActivationController extends Controller
{

    /**
     * Activate user via activation token
     */
    public function postActiveAccount()
    {
        return $this->serve(ActivateUserFeature::class);
    }

    /**
     * Resend activation token
     */
    public function postResendActivationCode()
    {
        return $this->serve(ResendActivationCodeFeature::class);
    }

}
