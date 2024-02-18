<?php
namespace App\Domains\Cms\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\JsonResource;

class SliderResource extends JsonResource {
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "title"         => $this->title,
            "sub_title"     => $this->sub_title,
            "description"   => $this->description,
            "image"         => $this->image,
            'title_color'   => $this->title_color,
            'sub_title_color'   => $this->sub_title_color,
            'description_color'   => $this->description_color,
            'button_txt'         => $this->button_txt,
            'button_target_url'     => $this->button_target_url
        ];
    }
}
