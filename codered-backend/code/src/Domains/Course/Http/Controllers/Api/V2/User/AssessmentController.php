<?php


namespace App\Domains\Course\Http\Controllers\Api\V2\User;

use App\Domains\Course\Features\Api\V2\User\GetCourseAssessmentFeature;
use App\Domains\Course\Features\Api\V2\User\SubmitAssessmentFeature;
use App\Domains\Course\Features\Api\V2\User\SubmitAssessmentOneAnswerFeature;
use App\Domains\Course\Features\Api\V2\User\ValidateProctorUserForAssessmentFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Course\Features\Api\V2\User\GetCompletedCourseResultFeature;

class AssessmentController extends Controller
{

    public function validateCredentials()
    {
        return $this->serve(ValidateProctorUserForAssessmentFeature::class);
    }

    public function show()
    {

        return $this->serve(GetCourseAssessmentFeature::class);
    }

    public function store()
    {
        return $this->serve(SubmitAssessmentFeature::class);
    }

    public function storeAnswer()
    {
        return $this->serve(SubmitAssessmentOneAnswerFeature::class);
    }

    public function getResult($id)
    {
        return $this->serve(GetCompletedCourseResultFeature::class);
    }

}
