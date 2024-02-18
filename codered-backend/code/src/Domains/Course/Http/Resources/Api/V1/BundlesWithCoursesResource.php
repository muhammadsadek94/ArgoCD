<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class BundlesWithCoursesResource extends JsonResource
{
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
        if (!$package->access_id) // if there is package with no access id return empty course array
            return [];
        $courses = Course::ActiveOrHide()
            ->with(['image', 'category', 'cover'])
            ->latest()
            ->where(function ($query) use ($package) {
                return $query->whereIn('id', json_decode($package->access_id));
            })
            ->get();
        return CourseBasicInfoResource::collection($courses);
    }
}
