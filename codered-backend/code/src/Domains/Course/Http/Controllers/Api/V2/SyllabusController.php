<?php

namespace App\Domains\Course\Http\Controllers\Api\V2;

use App\Domains\Course\Features\Api\V2\GetSyllabusFeature;
use INTCore\OneARTFoundation\Http\Controller;

class SyllabusController extends Controller
{
    public function postGetSyllabus()
    {
        return $this->serve(GetSyllabusFeature::class);
    }

}
