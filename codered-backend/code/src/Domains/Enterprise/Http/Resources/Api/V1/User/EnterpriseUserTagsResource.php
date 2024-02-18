<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

class EnterpriseUserTagsResource extends JsonResource
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
            "id"             => $this->id,
            'name'           =>  $this->name,
            'type'           =>  $this->type,
            'count'          =>count( $this->users),
        ];
    }
}
