<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use INTCore\OneARTFoundation\Http\JsonResource;

use Carbon\Carbon;

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
            'id'       => $this->id,
            'name'     => $this->name,
            'amount'   => $this->amount,
            'duration' => Carbon::now()->addDays($this->duration)->format('d M Y'),
            'type'     => $this->type,
            'features' => $this->features,
            'url'      => $this->url,
        ];
    }
}
