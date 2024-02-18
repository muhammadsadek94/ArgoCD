<?php

namespace App\Domains\Partner\Features\Api\V1;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Models\Course;
use App\Domains\Partner\Http\Resources\Api\V1\CourseCollection;
use App\Domains\Partner\Jobs\Api\V1\ValidatePartnerCredentialsJob;
use App\Domains\Partner\Repositories\CourseLevelRepository;
use App\Domains\Partner\Repositories\Interfaces\CourseCategoryRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\CourseLevelRepositoryInterface;
use App\Domains\Partner\Repositories\Interfaces\CourseRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class FilterCourseFeature extends Feature
{
    public function handle(Request $request, CourseRepositoryInterface $course_repository,
                           CourseLevelRepository $course_level_repository, CourseCategoryRepositoryInterface $category_repository)
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




        $courses = $course_repository->courseFiltration($request, $partner);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'result'  => new CourseCollection($courses),
            ]
        ]);
    }
}
