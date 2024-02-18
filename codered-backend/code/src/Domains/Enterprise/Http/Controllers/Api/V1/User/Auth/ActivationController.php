<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\User\Auth;

use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Enterprise\Features\Api\V1\User\Activation\ActivateUserFeature;
use App\Domains\Enterprise\Features\Api\V1\User\Activation\ResendActivationCodeFeature;

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
