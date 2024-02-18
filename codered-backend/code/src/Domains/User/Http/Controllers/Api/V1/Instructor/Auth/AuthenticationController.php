<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor\Auth;

use App\Domains\User\Features\Api\V1\Instructor\Auth\LoginUserFeature;
use App\Domains\User\Features\Api\V1\Instructor\Auth\LogoutUserFeature;
use INTCore\OneARTFoundation\Http\Controller;

class AuthenticationController extends Controller
{


    /**
     * authorize user to system.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return $this->serve(LoginUserFeature::class);
    }

    /**
     * revoke user authoirzation token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return $this->serve(LogoutUserFeature::class);
    }



}
