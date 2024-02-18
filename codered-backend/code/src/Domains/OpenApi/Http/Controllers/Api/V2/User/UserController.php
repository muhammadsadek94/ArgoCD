<?php

namespace App\Domains\OpenApi\Http\Controllers\Api\V2\User;

use App\Domains\OpenApi\Features\Api\V2\AvailableCourseFeature;
use App\Domains\OpenApi\Features\Api\V2\User\CreateUserFeature;
use App\Domains\OpenApi\Features\Api\V2\User\UpdateUserProfileFeature;
use App\Domains\OpenApi\Features\Api\V2\User\EnrolledCoursesFeature;
use App\Domains\OpenApi\Features\Api\V2\User\GetUserCertificateByIdFeature;
use App\Domains\OpenApi\Features\Api\V2\User\GetUserCertificatesFeature;
use App\Domains\OpenApi\Features\Api\V2\User\MyBundlesFeature;
use App\Domains\OpenApi\Features\Api\V2\User\MyCertificationsFeature;
use App\Domains\OpenApi\Features\Api\V2\User\MyCoursesFeature;
use App\Domains\OpenApi\Features\Api\V2\User\MyLearningPathsFeature;
use App\Domains\OpenApi\Features\Api\V2\User\MyMicrodegreesFeature;
use App\Domains\OpenApi\Http\Resources\Api\V2\User\UserInfoResource;
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

    public function getUserCertificateById()
    {
        return $this->serve(GetUserCertificateByIdFeature::class);
    }

    public function myLearningPaths()
    {
        return $this->serve(MyLearningPathsFeature::class);
    }

    public function myMicroDegrees()
    {
        return $this->serve(MyMicrodegreesFeature::class);
    }

    public function myCourses()
    {
        return $this->serve(MyCoursesFeature::class);
    }

    public function myCertifications()
    {
        return $this->serve(MyCertificationsFeature::class);
    }

    public function myBundles()
    {
        return $this->serve(MyBundlesFeature::class);
    }


}
