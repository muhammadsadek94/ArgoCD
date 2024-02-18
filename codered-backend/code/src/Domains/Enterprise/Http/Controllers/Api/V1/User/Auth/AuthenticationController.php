<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\User\Auth;

use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Enterprise\Features\Api\V1\User\Auth\LoginUserFeature;
use App\Domains\Enterprise\Features\Api\V1\User\Auth\LogoutUserFeature;
use App\Domains\Enterprise\Features\Api\V1\User\Auth\RegisterUserFeature;

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
