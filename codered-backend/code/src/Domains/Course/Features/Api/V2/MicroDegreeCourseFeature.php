<?php

namespace App\Domains\Course\Features\Api\V2;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Http\Resources\Api\V2\Microdegree\MicrodegreeInformationResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class MicroDegreeCourseFeature extends  Feature
{
    public function handle(
        CourseRepositoryInterface $course_repository,
        Request  $request
    ) {

        $id = $request->route('id');
        $microdegrees = $course_repository->getModel()->active()
        ->where(function($query){
            $query->where('course_type', CourseType::MICRODEGREE)
            ->orWhere('course_type', CourseType::COURSE_CERTIFICATION);
        })
        ->where('id', $id)->orWhere('slug_url',$id)->whereHas('microdegree')->firstOrFail();

        return $this->run(RespondWithJsonJob::class, [
            "content" => new MicrodegreeInformationResource($microdegrees)
        ]);
    }
}
