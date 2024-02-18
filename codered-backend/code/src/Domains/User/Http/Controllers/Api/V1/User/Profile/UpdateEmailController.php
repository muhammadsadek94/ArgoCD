<?php

namespace App\Domains\User\Http\Controllers\Api\V1\User\Profile;

use App\Domains\User\Features\Api\V1\User\Profile\UpdateEmail\RequestUpdateEmailFeature;
use App\Domains\User\Features\Api\V1\User\Profile\UpdateEmail\UpdateEmailFeature;
use INTCore\OneARTFoundation\Http\Controller;

class UpdateEmailController extends Controller
{

    public function postRequest()
    {
        return $this->serve(RequestUpdateEmailFeature::class);
    }

    public function postVerify()
    {
        return $this->serve(UpdateEmailFeature::class);
    }

}
