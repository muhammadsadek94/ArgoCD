<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class CourseCategoryResource extends JsonResource
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
            'image'       => new FileResource($this->whenLoaded('image'))
        ];
    }
}
