<?php

namespace App\Domains\Course\Http\Controllers\Api\V2\User;

use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V2\User\Microdegree\GetInternalMicrodegreeFeature;
use App\Domains\Course\Features\Api\V2\MicroDegreeCourseFeature;


class MicrodegreeController extends Controller
{
    public function internal() {
        return $this->serve(GetInternalMicrodegreeFeature::class);
    }
    
    public function show() {
        return $this->serve(MicroDegreeCourseFeature::class);
    }
}
