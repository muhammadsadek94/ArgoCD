<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor\Auth;

use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\User\Features\Api\V1\User\Auth\LoginUserFeature;
use App\Domains\User\Features\Api\V1\User\Auth\LogoutUserFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V1\Instructor\Auth\RegisterInstructorFeature;
use App\Domains\User\Http\Requests\Api\V1\Instructor\RegisterInstructorRequest;

class RegisterController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return $this->serve(RegisterInstructorFeature::class);
    }

    public function validateRegistration(RegisterInstructorRequest $request)
    {
        // The purpose of this route to validation forms before submit directly to database
        return dispatch_now(new RespondWithJsonJob([
            'status' => true
        ]));
    }

    //    /**
    //     * authorize user to system.
    //     *
    //     * @return \Illuminate\Http\Response
    //     */
    //    public function login()
    //    {
    //        return $this->serve(LoginUserFeature::class);
    //    }
    //
    //    /**
    //     * revoke user authoirzation token.
    //     *
    //     * @return \Illuminate\Http\Response
    //     */
    //    public function logout()
    //    {
    //        return $this->serve(LogoutUserFeature::class);
    //    }


}
