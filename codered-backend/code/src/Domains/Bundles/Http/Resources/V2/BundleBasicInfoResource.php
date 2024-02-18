<?php

namespace App\Domains\Bundles\Http\Resources\V2;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class BundleBasicInfoResource extends JsonResource
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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'slug_url'                  => $this->slug_url,
            'payment_terms'             => [
                                            'price' => $this->price,
                                        ],
            'image'                     => new FileResource($this->image),
        ];
    }
}
