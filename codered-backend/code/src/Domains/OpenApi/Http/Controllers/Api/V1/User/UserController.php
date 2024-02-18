<?php

namespace App\Domains\OpenApi\Http\Controllers\Api\V1\User;

use App\Domains\OpenApi\Features\Api\V1\AvailableCourseFeature;
use App\Domains\OpenApi\Features\Api\V1\User\CreateUserFeature;
use App\Domains\OpenApi\Features\Api\V1\User\UpdateUserProfileFeature;
use App\Domains\OpenApi\Features\Api\V1\User\EnrolledCoursesFeature;
use App\Domains\OpenApi\Features\Api\V1\User\GetUserCertificatesFeature;
use App\Domains\OpenApi\Http\Resources\Api\V1\User\UserInfoResource;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class UserController extends Controller
{

    public function register()
    {
        return $this->serve(CreateUserFeature::class);
    }

    public function update()
    {
        return $this->serve(UpdateUserProfileFeature::class);
    }

    public function show(Request $request)
    {
        $user = $request->user('api');
        return new UserInfoResource($user);
    }


    public function getEnrolledCourses(){

        return $this->serve(EnrolledCoursesFeature::class);
    }

    public function getAvailableCourses(){

        return $this->serve(AvailableCourseFeature::class);
    }

    public function getUserCertificates()
    {

        return $this->serve(GetUserCertificatesFeature::class);

    }

}
