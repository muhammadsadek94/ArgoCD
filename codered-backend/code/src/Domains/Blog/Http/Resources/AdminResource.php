<?php

namespace App\Domains\Blog\Http\Resources;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'name'  => $this->name,
            'photo' => new FileResource($this->image),
            'email' => $this->email,
        ];
    }
}
