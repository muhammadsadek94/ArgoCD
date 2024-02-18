<?php
namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Models\CourseReview;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseDetailsV2Resource extends JsonResource {
    public function toArray($request)
    {
        $courses = $this->courses()->active()->where('is_featured', 1)->limit(6)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->label_color,
            'courses' => $courses ? CourseBasicInfoV2Resource::collection($courses) : []
        ];
    }
}
