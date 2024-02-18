<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor\Profile;

use App\Domains\User\Features\Api\UpdatePhone\RequestUpdatePhoneFeature;
use App\Domains\User\Features\Api\UpdatePhone\UpdatePhoneFeature;
use INTCore\OneARTFoundation\Http\Controller;

class UpdatePhoneController extends Controller
{

    public function postRequest()
    {
        //TODO:
        return $this->serve(RequestUpdatePhoneFeature::class);
    }

    public function postVerify()
    {
        //TODO:
        return $this->serve(UpdatePhoneFeature::class);
    }

}
