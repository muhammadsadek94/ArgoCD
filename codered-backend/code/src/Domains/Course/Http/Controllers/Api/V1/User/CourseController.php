<?php


namespace App\Domains\Course\Http\Controllers\Api\V1\User;


use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V1\User\GetLibraryFeature;
use App\Domains\Course\Features\Api\V1\User\GetLookupsFeature;
use App\Domains\Course\Features\Api\V1\User\GetCourseByIdFeature;
use App\Domains\Course\Features\Api\V1\User\EnrollInCourseFeature;
use App\Domains\Course\Features\Api\V1\User\CourseFiltrationFeature;
use App\Domains\Course\Features\Api\V1\User\GetCoursesWithLabsFeature;
use App\Domains\Course\Features\Api\V1\User\GetFeaturedCourseFeature;
use App\Domains\Course\Features\Api\V1\User\GetInternalCourseFeature;
use App\Domains\Course\Features\Api\V1\User\GetProPageFeature;

class CourseController extends Controller
{
    public function getFeaturedCourses()
    {
        return $this->serve(GetFeaturedCourseFeature::class);
    }

    public function getProPageDetails()
    {
        return $this->serve(GetProPageFeature::class);
    }



    public function getLibrary()
    {
        return $this->serve(GetLibraryFeature::class);
    }

    public function getCourseFiltration()
    {
        return $this->serve(CourseFiltrationFeature::class);
    }

    public function show()
    {
        return $this->serve(GetCourseByIdFeature::class);
    }

    public function getInternal()
    {
        return $this->serve(GetInternalCourseFeature::class);
    }

    public function getLookups()
    {
        return $this->serve(GetLookupsFeature::class);
    }

    public function postEnroll()
    {
        return $this->serve(EnrollInCourseFeature::class);
    }

    public function getCoursesWithLabs()
    {
        return $this->serve(GetCoursesWithLabsFeature::class);
    }

}
