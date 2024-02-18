<?php

namespace App\Domains\Blog\Http\Resources;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class QuoteResource extends JsonResource
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
            //TODO: add response of api that's required for frontend

            'id' => $this->id,
            'author_name' => $this->author_name,
            'author_position' => $this->author_position,
            'quote' => $this->quote,
            'author_image' => new FileResource($this->author_image),
            'created_at' => $this->created_at->format('m D'),
        ];
    }
}
