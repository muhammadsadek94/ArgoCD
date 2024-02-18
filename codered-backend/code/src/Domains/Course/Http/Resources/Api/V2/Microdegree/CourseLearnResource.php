<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseLearnResource extends JsonResource
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
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'image'          => new FileResource($this->image),
        ];
    }
}
