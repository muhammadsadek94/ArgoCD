<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\User;

use App\Domains\Enterprise\Features\Api\V1\User\Auth\CreateUserWithFileFeature;
use App\Domains\Enterprise\Features\Api\V1\User\GetEnterpriseDataToCreateUsers;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\CreateUserWithFileRequest;
use App\Domains\Enterprise\Features\Api\V1\User\UpdateProfileFeature;
use App\Domains\Enterprise\Features\Api\V1\User\ChangePasswordFeature;

use INTCore\OneARTFoundation\Http\Controller;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEnterpriseData()
    {
        return $this->serve(GetEnterpriseDataToCreateUsers::class);
    }

    /**
     * Store a newly created resource in storage using excel sheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUserWithFile(CreateUserWithFileRequest $request)
    {
        return $this->serve(CreateUserWithFileFeature::class);
    }

    /**
     * Update profile information
     * @return mixed
     */
    public function patchProfile()
    {
        return $this->serve(UpdateProfileFeature::class);
    }


    /**
     * Update User's password
     */
    public function postChangePassword()
    {
        return $this->serve(ChangePasswordFeature::class);
    }


}
