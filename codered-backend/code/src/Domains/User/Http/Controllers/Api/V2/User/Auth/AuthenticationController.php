<?php

namespace App\Domains\User\Http\Controllers\Api\V2\User\Auth;

use App\Domains\User\Features\Api\V2\User\Auth\LoginUserFeature;
use App\Domains\User\Features\Api\V2\User\Auth\LogoutUserFeature;
use App\Domains\User\Features\Api\V2\User\Auth\RegisterUserFeature;
use App\Domains\User\Features\Api\V2\User\Auth\RegisterUserWithoutPasswordFeature;
use INTCore\OneARTFoundation\Http\Controller;

class AuthenticationController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return $this->serve(RegisterUserFeature::class);
    }
    public function registerWithOutPassword()
    {
        return $this->serve(RegisterUserWithoutPasswordFeature  ::class);
    }

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
