<?php

namespace App\Domains\Geography\Http\Resources;

use INTCore\OneARTFoundation\Http\JsonResource;

class NationalityResource extends JsonResource
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
            "name_en"=> $this->nationality_en,
            "name_ar"=> $this->nationality_ar,
        ];
    }
}
