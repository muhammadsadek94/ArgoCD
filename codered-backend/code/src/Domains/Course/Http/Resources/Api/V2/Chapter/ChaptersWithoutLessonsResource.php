<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Chapter;

use INTCore\OneARTFoundation\Http\JsonResource;


class ChaptersWithoutLessonsResource extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
        ];
    }

}
