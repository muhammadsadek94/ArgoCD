<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Menu;

use App\Domains\Course\Http\Resources\Api\V1\LearnPathBasicResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class MenuResource extends JsonResource
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
            'sort'              => $this->sort,
            // 'courses'           => CourseBasicInfoResource::collection($this->courses),
            // 'paths'             => LearnPathBasicResource::collection($this->paths),
            'sub_categories'    => SubCourseCategoryResource::collection($this->sub_categories)
        ];
    }



}
