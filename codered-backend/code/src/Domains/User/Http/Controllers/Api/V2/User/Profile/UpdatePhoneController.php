<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Profile;

use App\Domains\User\Features\Api\UpdatePhone\RequestUpdatePhoneFeature;
use App\Domains\User\Features\Api\UpdatePhone\UpdatePhoneFeature;
use INTCore\OneARTFoundation\Http\Controller;

class UpdatePhoneController extends Controller
{

    public function postRequest()
    {
        return $this->serve(RequestUpdatePhoneFeature::class);
    }

    public function postVerify()
    {
        return $this->serve(UpdatePhoneFeature::class);
    }

}
