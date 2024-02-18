<?php

namespace App\Domains\Geography\Http\Resources;

use App\Domains\Property\Models\Property;
use INTCore\OneARTFoundation\Http\JsonResource;

class CityResource extends JsonResource
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
            "id" => $this->id,
            "name_en" => $this->name_en,
        ];
    }
}
