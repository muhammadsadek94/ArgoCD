<?php

namespace App\Domains\User\Http\Controllers\Api\V2\Instructor\Auth;

use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\User\Features\Api\V2\Instructor\Auth\RegisterInstructorFeature;

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


}
