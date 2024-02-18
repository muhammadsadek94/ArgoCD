<?php

namespace App\Domains\User\Http\Controllers\Api\V1\Instructor;

use App\Domains\User\Features\Api\V1\Instructor\Profile\GetInstructorFeature;
use INTCore\OneARTFoundation\Http\Controller;

class InstructorController extends Controller
{

    /**
     * get instructor page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInstructor()
    {
        return $this->serve(GetInstructorFeature::class);
    }
  
}
