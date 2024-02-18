<?php

namespace App\Domains\Course\Http\Controllers\Api\V2;

use App\Domains\Course\Features\Api\V2\Microdegree\InternalMicrodegreeFeature;
use App\Domains\Course\Features\Api\V2\MicroDegreeCourseFeature;
use INTCore\OneARTFoundation\Http\Controller;

class MicroDegreeCourseController extends Controller
{
    public function show() {
        return $this->serve(MicroDegreeCourseFeature::class);
    }

    // public function showInternal() {
    //     return $this->serve(InternalMicrodegreeFeature::class);
    // }
}
