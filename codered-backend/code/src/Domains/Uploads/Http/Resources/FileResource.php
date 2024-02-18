<?php

namespace App\Domains\Uploads\Http\Resources;

use Illuminate\Support\Facades\Storage;
use INTCore\OneARTFoundation\Http\JsonResource;

class FileResource extends JsonResource
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
            'id' => $this->id,
            'path' => $this->path,
//            'sas_url' => getSasBlob($this->path, $this->container),
            'full_url' => $this->full_url,
            'mime_type' => $this->mime_type,
//            'container' => $this->container,
        ];
    }
}
