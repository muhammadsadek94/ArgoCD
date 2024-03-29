<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class CourseCategoryWithCoursesV2Resource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'color_class' => $this->label_color,
            'category_icon_class_name' => $this->icon_class_name,
            'image'       => new FileResource($this->image),
            'courses'     => CourseBasicInfoV2Resource::collection($this->courses()->Course()->Active()->with(['image', 'cover'])->latest()->limit(3)->get())
        ];
    }
}
