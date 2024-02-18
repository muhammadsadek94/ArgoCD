<?php

namespace App\Domains\Partner\Http\Resources\Api\V1;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
