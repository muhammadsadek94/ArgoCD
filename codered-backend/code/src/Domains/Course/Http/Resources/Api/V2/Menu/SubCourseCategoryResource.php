<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Menu;

use INTCore\OneARTFoundation\Http\JsonResource;


class SubCourseCategoryResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'parent_category'   => $this->cat_parent_id,
            'courses'           => CourseBasicInfoResource::collection($this->coursesAssignWithSub),
            'paths'             => LearnPathBasicResource::collection($this->pathsAssignWithSub),
        ];
    }
}
