<?php

namespace App\Domains\Partner\Features\Api\V1;

use App\Domains\Partner\Http\Resources\Api\V1\CourseFullInfoDetails;
use App\Domains\Partner\Jobs\Api\V1\ValidatePartnerCredentialsJob;
use App\Domains\Partner\Repositories\Interfaces\CourseRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\LessonRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetCourseDetailsFeature extends Feature
{
    public function handle(Request $request, CourseRepositoryInterface $course_repository)
    {
        $partner = $this->run(ValidatePartnerCredentialsJob::class, [
            'partner_name'   => $request->header('Partner-Id'),
            'partner_secret' => $request->header('Secret-Key'),
        ]);

        if (!$partner){
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name'    => 'message',
                    'message' => 'You credentials does not match our records',
                ],
                'status' => 401
            ]);
        }

        $course = $course_repository->findCourseById($partner->id, $request->course);

        if(empty($course)) abort(404, "Course not found");

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'course' => new CourseFullInfoDetails($course)
            ]
        ]);
    }
}
