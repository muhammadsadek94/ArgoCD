<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Models\Course;
use Framework\Traits\SelectColumnTrait;
use INTCore\OneARTFoundation\Http\JsonResource;
use Mpdf\Tag\Dd;

class BundlesWithCoursesV2Resource extends JsonResource
{

    use SelectColumnTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'name' => $this->package->name,
            'courses' => $this->getCourses($this->package)
        ];
    }

    private function getCourses($package)
    {
        $courses = $package->loaded_courses;
        return CourseBasicInfoV2Resource::collection($courses);
    }
}
