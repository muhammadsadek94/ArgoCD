<?php


namespace App\Domains\Course\Http\Controllers\Api\V1\User;


use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V1\User\Microdegree\GetMicrodegreesFeature;
use App\Domains\Course\Features\Api\V1\User\Microdegree\GetMicrodegreeByIdFeature;
use App\Domains\Course\Features\Api\V1\User\Microdegree\EnrollInMicrodegreeFeature;
use App\Domains\Course\Features\Api\V1\User\Microdegree\GetInternalMicrodegreeFeature;

class MicrodegreeController extends Controller
{
    public function getMicrodegrees()
    {
        return $this->serve(GetMicrodegreesFeature::class);
    }

    public function show()
    {
        return $this->serve(GetMicrodegreeByIdFeature::class);
    }

    public function getInternal()
    {
        return $this->serve(GetInternalMicrodegreeFeature::class);
    }

    /**
     * TODO: delete this and this route
     */
    public function postEnroll()
    {
//        return $this->serve(EnrollInMicrodegreeFeature::class);
    }



}
