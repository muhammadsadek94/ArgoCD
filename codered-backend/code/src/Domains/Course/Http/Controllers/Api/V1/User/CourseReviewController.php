<?php


namespace App\Domains\Course\Http\Controllers\Api\V1\User;

use App\Domains\Course\Features\Api\V1\User\SaveCourseReviewFeature;
use INTCore\OneARTFoundation\Http\Controller;


class CourseReviewController extends Controller
{

    public function store()
    {
        return $this->serve(SaveCourseReviewFeature::class);
    }



}
