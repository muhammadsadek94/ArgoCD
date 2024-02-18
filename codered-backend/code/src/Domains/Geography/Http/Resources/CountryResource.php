<?php

namespace App\Domains\Geography\Http\Resources;

use INTCore\OneARTFoundation\Http\JsonResource;

class CountryResource extends JsonResource
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
            'cities' => $this->when($request->has('load_cities'), function() {
                return CityResource::collection($this->cities);
            })
        ];
    }
}
