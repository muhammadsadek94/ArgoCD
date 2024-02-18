<?php

namespace App\Domains\OpenApi\Http\Controllers\Api\V1\User;

use App\Domains\OpenApi\Features\Api\V1\User\GetFeaturedCoursesFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class CourseController extends Controller
{


    public function getFeaturedCourses()
    {
        return $this->serve(GetFeaturedCoursesFeature::class);
    }

}
