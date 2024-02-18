<?php
namespace App\Domains\Cms\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\JsonResource;

class BrandResource extends JsonResource {
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "alt_text"         => $this->alt_text,
            "image"         => $this->image
        ];
    }
}
