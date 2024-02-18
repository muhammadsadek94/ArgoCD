<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Microdegree;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;

class PackageResource extends JsonResource
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
            'name'     => $this->name,
            'amount'   => $this->amount,
            'type'     => $this->type,
            'features' => $this->features,
            'url'      => $this->url,
        ];
    }
}
